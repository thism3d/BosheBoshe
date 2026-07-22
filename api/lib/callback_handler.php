<?php
/**
 * Shared logic for handling a gateway callback for an aggregator
 * transaction, regardless of which gateway it came from.
 *
 * Reached two ways, both landing here with the gateway's POST data:
 *   1. via the native store pages (successpayment/failedpayment/cancelpayment),
 *      which carry native_callback_hook.php and hand off when the posted
 *      tran_id belongs to an api_transactions row; OR
 *   2. via the thin api/payment_success.php etc. entry points (direct hits).
 *
 * We look up the transaction, ask its provider to verify the result,
 * update the local row, then 302-redirect the customer's browser back to
 * the partner's own success/fail/cancel URL with an HMAC-signed payload so
 * the partner can trust the data without calling us back.
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/../providers/factory.php';

/**
 * @param string $event one of 'success', 'fail', 'cancel'
 */
function api_handle_callback(string $event): void
{
    $tranId = $_POST['value_a'] ?? $_POST['tran_id'] ?? '';

    if ($tranId === '') {
        http_response_code(400);
        echo 'Missing transaction reference.';
        exit;
    }

    $conn = api_db_connect();

    $stmt = $conn->prepare('SELECT t.*, p.api_secret, p.commission_percent AS partner_commission_percent
        FROM api_transactions t
        JOIN api_partners p ON p.id = t.partner_id
        WHERE t.tran_id = ? LIMIT 1');
    $stmt->bind_param('s', $tranId);
    $stmt->execute();
    $txn = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$txn) {
        http_response_code(404);
        echo 'Unknown transaction.';
        exit;
    }

    $status = 'FAILED';
    $valId = $_POST['val_id'] ?? null;
    $bankTranId = null;
    $cardType = null;
    $baseAmountBdt = null;
    $rawResponse = null;

    if ($event === 'success') {
        $provider = payment_provider($txn['provider']);
        if ($provider) {
            $verified = $provider->validateCallback($_POST);
            $status = $verified['status'];
            $bankTranId = $verified['bank_tran_id'];
            $cardType = $verified['card_type'];
            $baseAmountBdt = $verified['base_amount_bdt'];
            $rawResponse = json_encode($verified['raw']);
        } else {
            $status = 'VALIDATION_FAILED';
        }
    } elseif ($event === 'cancel') {
        $status = 'CANCELLED';
        $rawResponse = json_encode($_POST);
    } else {
        $status = 'FAILED';
        $rawResponse = json_encode($_POST);
    }

    $commissionAmount = null;
    if ($status === 'VALID') {
        $commissionAmount = round(((float) $txn['amount']) * ((float) $txn['partner_commission_percent']) / 100, 2);
    }

    $update = $conn->prepare('UPDATE api_transactions
        SET status = ?, val_id = ?, bank_tran_id = ?, card_type = ?, base_amount_bdt = ?, raw_response = ?, commission_amount = ?
        WHERE tran_id = ?');
    $update->bind_param('ssssdsds', $status, $valId, $bankTranId, $cardType, $baseAmountBdt, $rawResponse, $commissionAmount, $tranId);
    $update->execute();
    $update->close();
    $conn->close();

    // ---- Redirect back to the partner with a signed payload ----------

    $redirectBase = $event === 'success' ? $txn['partner_success_url']
        : ($event === 'cancel' ? $txn['partner_cancel_url'] : $txn['partner_fail_url']);

    $payload = [
        'tran_id' => $tranId,
        'order_ref' => $txn['partner_order_ref'],
        'status' => $status,
        'amount' => $txn['amount'],
        'currency' => $txn['currency'],
        'val_id' => $valId,
        'bank_tran_id' => $bankTranId,
        'card_type' => $cardType,
        'timestamp' => time(),
    ];

    $payload['signature'] = api_sign_payload($payload, $txn['api_secret']);

    $separator = (strpos($redirectBase, '?') === false) ? '?' : '&';
    header('Location: ' . $redirectBase . $separator . http_build_query($payload));
    exit;
}
