<?php
require_once __DIR__ . '/../inc/auth_guard.php';
require_once __DIR__ . '/../inc/csrf.php';
require_once __DIR__ . '/../inc/flash.php';
require_once __DIR__ . '/../../api/lib/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_check()) {
    flash_set('error', 'Invalid request.');
    header('Location: ../partners.php');
    exit;
}

$name = trim($_POST['partner_name'] ?? '');
$domain = trim($_POST['website_domain'] ?? '');
$email = trim($_POST['contact_email'] ?? '');
$commission = (float) ($_POST['commission_percent'] ?? 1.0);

if ($name === '') {
    flash_set('error', 'Partner name is required.');
    header('Location: ../partners.php');
    exit;
}

$apiKey = api_generate_key('bb_live_key');
$apiSecret = api_generate_key('bb_live_secret');

$conn = api_db_connect();
$stmt = $conn->prepare('INSERT INTO api_partners (partner_name, api_key, api_secret, website_domain, contact_email, commission_percent) VALUES (?, ?, ?, ?, ?, ?)');
$stmt->bind_param('sssssd', $name, $apiKey, $apiSecret, $domain, $email, $commission);
$stmt->execute();
$stmt->close();
$conn->close();

flash_set('ok', "Partner \"$name\" created. Full credentials are shown in the table below whenever you need to hand them to the partner's developer.");
header('Location: ../partners.php');
exit;
