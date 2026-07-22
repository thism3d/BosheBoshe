<?php
/**
 * Gateway IPN (Instant Payment Notification) listener — server-to-server,
 * more reliable than relying on the customer's browser reaching the
 * callback page. Registered via ipn_url in the session (see config.php).
 *
 * We re-verify independently through the transaction's provider (never
 * trust IPN data on its own) and update the transaction if it isn't
 * already VALID. Then, best-effort, we forward the result to the partner's
 * own ipn_url if they registered one.
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/lib/functions.php';
require_once __DIR__ . '/providers/factory.php';

header('Content-Type: application/json');

$tranId = $_POST['value_a'] ?? $_POST['tran_id'] ?? '';

if ($tranId === '') {
    api_json_response(400, ['status' => 'error', 'message' => 'Missing tran_id']);
}

$conn = api_db_connect();

$stmt = $conn->prepare('SELECT t.*, p.api_secret FROM api_transactions t
    JOIN api_partners p ON p.id = t.partner_id
    WHERE t.tran_id = ? LIMIT 1');
$stmt->bind_param('s', $tranId);
$stmt->execute();
$txn = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$txn) {
    api_json_response(404, ['status' => 'error', 'message' => 'Unknown transaction']);
}

// Already finalized by the browser redirect flow — acknowledge and stop.
if ($txn['status'] === 'VALID') {
    api_json_response(200, ['status' => 'ok', 'message' => 'Already processed']);
}

$provider = payment_provider($txn['provider']);
if (!$provider) {
    api_json_response(500, ['status' => 'error', 'message' => 'Provider not available']);
}

$verified = $provider->validateCallback($_POST);
$status = $verified['status'];
$valId = $_POST['val_id'] ?? null;
$bankTranId = $verified['bank_tran_id'];
$cardType = $verified['card_type'];
$baseAmountBdt = $verified['base_amount_bdt'];
$rawResponse = json_encode($verified['raw']);

$commissionAmount = null;
if ($status === 'VALID') {
    $partnerStmt = $conn->prepare('SELECT commission_percent FROM api_partners WHERE id = ?');
    $partnerStmt->bind_param('i', $txn['partner_id']);
    $partnerStmt->execute();
    $partnerRow = $partnerStmt->get_result()->fetch_assoc();
    $partnerStmt->close();
    $commissionAmount = round(((float) $txn['amount']) * ((float) $partnerRow['commission_percent']) / 100, 2);
}

$update = $conn->prepare('UPDATE api_transactions
    SET status = ?, val_id = ?, bank_tran_id = ?, card_type = ?, base_amount_bdt = ?, raw_response = ?, commission_amount = ?
    WHERE tran_id = ?');
$update->bind_param('ssssdsds', $status, $valId, $bankTranId, $cardType, $baseAmountBdt, $rawResponse, $commissionAmount, $tranId);
$update->execute();
$update->close();
$conn->close();

// Best-effort forward to the partner's own IPN endpoint, if registered.
if (!empty($txn['partner_ipn_url'])) {
    $forwardPayload = [
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
    $forwardPayload['signature'] = api_sign_payload($forwardPayload, $txn['api_secret']);
    api_curl_post($txn['partner_ipn_url'], $forwardPayload);
}

api_json_response(200, ['status' => 'ok']);
