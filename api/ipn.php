<?php
/**
 * SSLCommerz IPN (Instant Payment Notification) listener — server-to-server,
 * more reliable than relying on the customer's browser reaching the
 * success/fail/cancel redirect. Configured via ipn_url in the Session API
 * call (see api/payment_proceed.php).
 *
 * We re-validate independently (never trust IPN data on its own, per the
 * SSLCommerz docs) and update the transaction if it isn't already VALID.
 * Then, best-effort, we forward the result to the partner's own ipn_url
 * if they registered one.
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/lib/functions.php';

header('Content-Type: application/json');

$tranId = $_POST['value_a'] ?? $_POST['tran_id'] ?? '';
$valId = $_POST['val_id'] ?? '';

if ($tranId === '' || $valId === '') {
    api_json_response(400, ['status' => 'error', 'message' => 'Missing tran_id/val_id']);
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

$url = SSLCZ_VALIDATION_API . '?' . http_build_query([
    'val_id' => $valId,
    'store_id' => SSLCOMMERZ_STORE_ID,
    'store_passwd' => SSLCOMMERZ_STORE_PASSWD,
    'v' => 1,
    'format' => 'json',
]);
$result = api_curl_get($url);
$validationStatus = $result['data']['status'] ?? '';

$status = in_array($validationStatus, ['VALID', 'VALIDATED'], true) ? 'VALID' : 'VALIDATION_FAILED';
$bankTranId = $result['data']['bank_tran_id'] ?? null;
$cardType = $result['data']['card_type'] ?? null;
$rawResponse = json_encode($result['data']);

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
    SET status = ?, val_id = ?, bank_tran_id = ?, card_type = ?, raw_response = ?, commission_amount = ?
    WHERE tran_id = ?');
$update->bind_param('sssssds', $status, $valId, $bankTranId, $cardType, $rawResponse, $commissionAmount, $tranId);
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
