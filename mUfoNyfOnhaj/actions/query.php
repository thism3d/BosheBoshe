<?php
/**
 * Admin-triggered live transaction re-check against SSLCommerz.
 */

require_once __DIR__ . '/../inc/auth_guard.php';
require_once __DIR__ . '/../inc/csrf.php';
require_once __DIR__ . '/../inc/flash.php';
require_once __DIR__ . '/../../api/config.php';
require_once __DIR__ . '/../../api/lib/functions.php';
require_once __DIR__ . '/../../api/providers/factory.php';

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

$provider = payment_provider($txn['provider']);
$conn->close();

if (!$provider) {
    flash_set('error', 'Provider not available for this transaction.');
    header('Location: ../transactions.php');
    exit;
}

$data = $provider->queryTransaction($txn['tran_id'])['raw'];
if ($data === null) {
    flash_set('error', 'Could not reach the gateway.');
} else {
    $gwStatus = $data[0]['status'] ?? ($data['status'] ?? 'unknown');
    flash_set('ok', "Live gateway status for {$txn['tran_id']}: $gwStatus");
}

header('Location: ../transactions.php');
exit;
