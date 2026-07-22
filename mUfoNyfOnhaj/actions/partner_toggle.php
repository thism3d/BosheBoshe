<?php
require_once __DIR__ . '/../inc/auth_guard.php';
require_once __DIR__ . '/../inc/csrf.php';
require_once __DIR__ . '/../inc/flash.php';
require_once __DIR__ . '/../../api/lib/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_check()) {
    flash_set('error', 'Invalid request.');
    header('Location: ../partners.php');
    exit;
}

$id = (int) ($_POST['partner_id'] ?? 0);
$newStatus = ($_POST['new_status'] ?? '') === 'active' ? 'active' : 'inactive';

$conn = api_db_connect();
$stmt = $conn->prepare('UPDATE api_partners SET status = ? WHERE id = ?');
$stmt->bind_param('si', $newStatus, $id);
$stmt->execute();
$stmt->close();
$conn->close();

flash_set('ok', 'Partner status updated.');
header('Location: ../partners.php');
exit;
