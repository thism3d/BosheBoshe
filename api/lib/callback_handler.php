<?php
/**
 * Shared logic for the three SSLCommerz callback targets
 * (payment_success.php / payment_fail.php / payment_cancel.php).
 *
 * SSLCommerz POSTs here with our own tran_id in $_POST['value_a']
 * (mirrors the pattern already used by the store's own successpayment.php).
 * We update the local api_transactions row, then 302-redirect the browser
 * back to the partner's original success/fail/cancel URL with a signed
 * result payload so the partner can trust the data without calling us
 * again.
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/functions.php';

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
    $valId = null;
    $bankTranId = null;
    $cardType = null;
    $rawResponse = null;

    if ($event === 'success') {
        $valId = $_POST['val_id'] ?? '';
        if ($valId !== '') {
            $url = SSLCZ_VALIDATION_API . '?' . http_build_query([
                'val_id' => $valId,
                'store_id' => SSLCOMMERZ_STORE_ID,
                'store_passwd' => SSLCOMMERZ_STORE_PASSWD,
                'v' => 1,
                'format' => 'json',
            ]);
            $result = api_curl_get($url);
            $rawResponse = json_encode($result['data']);

            $validationStatus = $result['data']['status'] ?? '';
            if (in_array($validationStatus, ['VALID', 'VALIDATED'], true)) {
                $status = 'VALID';
                $bankTranId = $result['data']['bank_tran_id'] ?? null;
                $cardType = $result['data']['card_type'] ?? null;
            } else {
                $status = 'VALIDATION_FAILED';
            }
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
        SET status = ?, val_id = ?, bank_tran_id = ?, card_type = ?, raw_response = ?, commission_amount = ?
        WHERE tran_id = ?');
    $update->bind_param('sssssds', $status, $valId, $bankTranId, $cardType, $rawResponse, $commissionAmount, $tranId);
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
