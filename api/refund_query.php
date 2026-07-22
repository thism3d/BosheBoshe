<?php
/**
 * Refund Query — elevated auth (api_key + api_secret). Wraps SSLCommerz's
 * merchantTransIDvalidationAPI.php per the v4 docs: GET with
 * refund_ref_id, store_id, store_passwd.
 *
 * POST: api_key, api_secret, refund_trans_id (ours, returned by
 * refund_initiate.php).
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

$refundTransId = trim($_POST['refund_trans_id'] ?? '');
if ($refundTransId === '') {
    api_json_response(400, ['status' => 'error', 'message' => 'Required: refund_trans_id']);
}

$stmt = $conn->prepare('SELECT r.* FROM api_refunds r
    JOIN api_transactions t ON t.id = r.transaction_id
    WHERE r.refund_trans_id = ? AND t.partner_id = ? LIMIT 1');
$stmt->bind_param('si', $refundTransId, $partner['id']);
$stmt->execute();
$refund = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$refund) {
    api_json_response(404, ['status' => 'error', 'message' => 'Refund not found']);
}
if (empty($refund['refund_ref_id'])) {
    api_json_response(409, ['status' => 'error', 'message' => 'Refund was never successfully initiated at the gateway']);
}

$url = SSLCZ_TRANS_API . '?' . http_build_query([
    'refund_ref_id' => $refund['refund_ref_id'],
    'store_id' => SSLCOMMERZ_STORE_ID,
    'store_passwd' => SSLCOMMERZ_STORE_PASSWD,
    'format' => 'json',
]);
$result = api_curl_get($url);
$data = $result['data'] ?? [];
$status = $data['status'] ?? $refund['status'];

$update = $conn->prepare('UPDATE api_refunds SET status = ?, raw_response = ? WHERE refund_trans_id = ?');
$rawJson = json_encode($data);
$update->bind_param('sss', $status, $rawJson, $refundTransId);
$update->execute();
$update->close();
$conn->close();

api_json_response(200, [
    'status' => 'success',
    'refund_trans_id' => $refundTransId,
    'gateway_status' => $status,
    'raw' => $data,
]);
