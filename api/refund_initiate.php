<?php
/**
 * Refund Initiate — elevated auth (api_key + api_secret), since this moves
 * money. Wraps SSLCommerz's merchantTransIDvalidationAPI.php per the v4
 * docs: GET with bank_tran_id, refund_trans_id, refund_amount,
 * refund_remarks, store_id, store_passwd.
 *
 * POST: api_key, api_secret, tran_id (our tran_id, must belong to this
 * partner and be status VALID), refund_amount, refund_remarks.
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/lib/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    api_json_response(405, ['status' => 'error', 'message' => 'Use POST']);
}

$apiKey = trim($_POST['api_key'] ?? '');
$apiSecret = trim($_POST['api_secret'] ?? '');
if ($apiKey === '' || $apiSecret === '') {
    api_json_response(401, ['status' => 'error', 'message' => 'Missing api_key/api_secret']);
}

$conn = api_db_connect();
$partner = api_get_partner_elevated($conn, $apiKey, $apiSecret);
if (!$partner) {
    api_json_response(401, ['status' => 'error', 'message' => 'Invalid credentials']);
}

$tranId = trim($_POST['tran_id'] ?? '');
$refundAmount = isset($_POST['refund_amount']) ? (float) $_POST['refund_amount'] : 0;
$refundRemarks = trim($_POST['refund_remarks'] ?? '');

if ($tranId === '' || $refundAmount <= 0 || $refundRemarks === '') {
    api_json_response(400, ['status' => 'error', 'message' => 'Required: tran_id, refund_amount, refund_remarks']);
}

$stmt = $conn->prepare('SELECT * FROM api_transactions WHERE tran_id = ? AND partner_id = ? LIMIT 1');
$stmt->bind_param('si', $tranId, $partner['id']);
$stmt->execute();
$txn = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$txn) {
    api_json_response(404, ['status' => 'error', 'message' => 'Transaction not found']);
}
if ($txn['status'] !== 'VALID' || empty($txn['bank_tran_id'])) {
    api_json_response(409, ['status' => 'error', 'message' => 'Transaction is not in a refundable state']);
}
if ($refundAmount > (float) $txn['amount']) {
    api_json_response(400, ['status' => 'error', 'message' => 'refund_amount exceeds transaction amount']);
}

$refundTransId = 'RFND-' . strtoupper(bin2hex(random_bytes(8)));

$insert = $conn->prepare('INSERT INTO api_refunds
    (transaction_id, refund_trans_id, bank_tran_id, refund_amount, refund_remarks, status)
    VALUES (?, ?, ?, ?, ?, "INITIATED")');
$insert->bind_param('issds', $txn['id'], $refundTransId, $txn['bank_tran_id'], $refundAmount, $refundRemarks);
$insert->execute();
$insert->close();

$url = SSLCZ_TRANS_API . '?' . http_build_query([
    'bank_tran_id' => $txn['bank_tran_id'],
    'refund_trans_id' => $refundTransId,
    'refund_amount' => $refundAmount,
    'refund_remarks' => $refundRemarks,
    'store_id' => SSLCOMMERZ_STORE_ID,
    'store_passwd' => SSLCOMMERZ_STORE_PASSWD,
    'format' => 'json',
]);
$result = api_curl_get($url);
$data = $result['data'] ?? [];

$status = $data['status'] ?? 'unknown';
$refundRefId = $data['refund_ref_id'] ?? null;

$update = $conn->prepare('UPDATE api_refunds SET status = ?, refund_ref_id = ?, raw_response = ? WHERE refund_trans_id = ?');
$rawJson = json_encode($data);
$update->bind_param('ssss', $status, $refundRefId, $rawJson, $refundTransId);
$update->execute();
$update->close();
$conn->close();

api_json_response(200, [
    'status' => 'success',
    'refund_trans_id' => $refundTransId,
    'refund_ref_id' => $refundRefId,
    'gateway_status' => $status,
    'raw' => $data,
]);
