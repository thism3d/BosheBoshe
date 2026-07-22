<?php
/**
 * Partner "enquiry" endpoint — read-only, api_key only.
 *
 * POST api_key + one of (tran_id | order_ref). Returns our locally stored
 * record. Pass live=1 to also re-check the live status directly against
 * SSLCommerz's Transaction Query API before responding.
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/lib/functions.php';
require_once __DIR__ . '/providers/factory.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    api_json_response(405, ['status' => 'error', 'message' => 'Use POST']);
}

$apiKey = trim($_POST['api_key'] ?? '');
if ($apiKey === '') {
    api_json_response(401, ['status' => 'error', 'message' => 'Missing api_key']);
}

$conn = api_db_connect();
$partner = api_get_partner_by_key($conn, $apiKey);
if (!$partner) {
    api_json_response(401, ['status' => 'error', 'message' => 'Invalid or inactive api_key']);
}

$tranId = trim($_POST['tran_id'] ?? '');
$orderRef = trim($_POST['order_ref'] ?? '');

if ($tranId === '' && $orderRef === '') {
    api_json_response(400, ['status' => 'error', 'message' => 'Provide tran_id or order_ref']);
}

if ($tranId !== '') {
    $stmt = $conn->prepare('SELECT * FROM api_transactions WHERE tran_id = ? AND partner_id = ? LIMIT 1');
    $stmt->bind_param('si', $tranId, $partner['id']);
} else {
    $stmt = $conn->prepare('SELECT * FROM api_transactions WHERE partner_order_ref = ? AND partner_id = ? ORDER BY id DESC LIMIT 1');
    $stmt->bind_param('si', $orderRef, $partner['id']);
}
$stmt->execute();
$txn = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$txn) {
    api_json_response(404, ['status' => 'error', 'message' => 'Transaction not found']);
}

$liveCheck = null;
if (($_POST['live'] ?? '') === '1') {
    $provider = payment_provider($txn['provider']);
    if ($provider) {
        $liveCheck = $provider->queryTransaction($txn['tran_id'])['raw'];
    }
}

$conn->close();

api_json_response(200, [
    'status' => 'success',
    'transaction' => [
        'tran_id' => $txn['tran_id'],
        'provider' => $txn['provider'],
        'order_ref' => $txn['partner_order_ref'],
        'amount' => $txn['amount'],
        'currency' => $txn['currency'],
        'base_amount_bdt' => $txn['base_amount_bdt'],
        'status' => $txn['status'],
        'val_id' => $txn['val_id'],
        'bank_tran_id' => $txn['bank_tran_id'],
        'card_type' => $txn['card_type'],
        'created_at' => $txn['created_at'],
        'updated_at' => $txn['updated_at'],
    ],
    'live_check' => $liveCheck,
]);
