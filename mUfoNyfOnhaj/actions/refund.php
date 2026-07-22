<?php
/**
 * Admin-triggered refund from the dashboard. Session auth only — the
 * partner's api_secret is not needed here since the admin is already
 * authenticated.
 */

require_once __DIR__ . '/../inc/auth_guard.php';
require_once __DIR__ . '/../inc/csrf.php';
require_once __DIR__ . '/../inc/flash.php';
require_once __DIR__ . '/../../api/config.php';
require_once __DIR__ . '/../../api/lib/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_check()) {
    flash_set('error', 'Invalid request.');
    header('Location: ../transactions.php');
    exit;
}

$txnId = (int) ($_POST['transaction_id'] ?? 0);
$refundAmount = (float) ($_POST['refund_amount'] ?? 0);
$refundRemarks = trim($_POST['refund_remarks'] ?? '');

$conn = api_db_connect();
$stmt = $conn->prepare('SELECT * FROM api_transactions WHERE id = ? LIMIT 1');
$stmt->bind_param('i', $txnId);
$stmt->execute();
$txn = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$txn || $txn['status'] !== 'VALID' || empty($txn['bank_tran_id'])) {
    flash_set('error', 'Transaction is not in a refundable state.');
    header('Location: ../transactions.php');
    exit;
}
if ($refundAmount <= 0 || $refundAmount > (float) $txn['amount'] || $refundRemarks === '') {
    flash_set('error', 'Enter a valid refund amount and remarks.');
    header('Location: ../transactions.php');
    exit;
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

flash_set('ok', "Refund $refundTransId submitted — gateway status: $status");
header('Location: ../transactions.php');
exit;
