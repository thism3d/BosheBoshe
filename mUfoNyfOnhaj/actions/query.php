<?php
/**
 * Admin-triggered live transaction re-check against SSLCommerz.
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

$conn = api_db_connect();
$stmt = $conn->prepare('SELECT * FROM api_transactions WHERE id = ? LIMIT 1');
$stmt->bind_param('i', $txnId);
$stmt->execute();
$txn = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$txn) {
    flash_set('error', 'Transaction not found.');
    header('Location: ../transactions.php');
    exit;
}

$url = SSLCZ_TRANS_API . '?' . http_build_query([
    'tran_id' => $txn['tran_id'],
    'store_id' => SSLCOMMERZ_STORE_ID,
    'store_passwd' => SSLCOMMERZ_STORE_PASSWD,
    'format' => 'json',
]);
$result = api_curl_get($url);
$conn->close();

if (!$result['ok']) {
    flash_set('error', 'Could not reach SSLCommerz.');
} else {
    $gwStatus = $result['data'][0]['status'] ?? ($result['data']['status'] ?? 'unknown');
    flash_set('ok', "Live gateway status for {$txn['tran_id']}: $gwStatus");
}

header('Location: ../transactions.php');
exit;
