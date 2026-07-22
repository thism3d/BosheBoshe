<?php
/**
 * Partner-facing payment initiate endpoint (the aggregator's front door).
 *
 * External websites POST here (either as a plain HTML form submit, which
 * results in the browser being redirected straight to the gateway page, or
 * with response_type=json for server-side integrations that want the
 * redirect URL back as JSON).
 *
 * The gateway is selected per-transaction via the optional `provider`
 * field (default sslcommerz); the endpoint itself is gateway-agnostic and
 * delegates all gateway I/O to api/providers/<provider>.php.
 *
 * See /docs/PARTNER_INTEGRATION_GUIDE.md for the full contract.
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/lib/functions.php';
require_once __DIR__ . '/providers/factory.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    api_json_response(405, ['status' => 'error', 'message' => 'Use POST']);
}

$responseType = isset($_POST['response_type']) && $_POST['response_type'] === 'json' ? 'json' : 'redirect';

function fail(string $responseType, int $httpCode, string $message): void
{
    api_json_response($httpCode, ['status' => 'error', 'message' => $message]);
}

// ---- Validate partner ------------------------------------------------

$apiKey = trim($_POST['api_key'] ?? '');
if ($apiKey === '') {
    fail($responseType, 401, 'Missing api_key');
}

$conn = api_db_connect();
$partner = api_get_partner_by_key($conn, $apiKey);

if (!$partner) {
    fail($responseType, 401, 'Invalid or inactive api_key');
}

// ---- Provider selection ----------------------------------------------

$providerName = strtolower(trim($_POST['provider'] ?? DEFAULT_PROVIDER)) ?: DEFAULT_PROVIDER;
if (!in_array($providerName, SUPPORTED_PROVIDERS, true)) {
    fail($responseType, 400, 'Unsupported provider. Supported: ' . implode(', ', SUPPORTED_PROVIDERS));
}
$provider = payment_provider($providerName);
if (!$provider) {
    fail($responseType, 500, 'Provider not available');
}

// ---- Validate required payment fields ---------------------------------

$amount = isset($_POST['amount']) ? (float) $_POST['amount'] : 0;

$currency = strtoupper(trim($_POST['currency'] ?? 'BDT')) ?: 'BDT';
if (!in_array($currency, SUPPORTED_CURRENCIES, true)) {
    fail($responseType, 400, 'Unsupported currency. Supported: ' . implode(', ', SUPPORTED_CURRENCIES));
}

$custName = trim($_POST['cus_name'] ?? '');
$custEmail = trim($_POST['cus_email'] ?? '');
$custPhone = trim($_POST['cus_phone'] ?? '');
$custAddress = trim($_POST['cus_add1'] ?? '');
$custCity = trim($_POST['cus_city'] ?? '');
$custCountry = trim($_POST['cus_country'] ?? '') ?: 'Bangladesh';
$successUrl = trim($_POST['success_url'] ?? '');
$failUrl = trim($_POST['fail_url'] ?? '');
$cancelUrl = trim($_POST['cancel_url'] ?? '');
$ipnUrl = trim($_POST['ipn_url'] ?? '');
$orderRef = trim($_POST['order_ref'] ?? '');
$emiOption = (isset($_POST['emi_option']) && $_POST['emi_option'] == '1') ? '1' : '0';

$missing = [];
if ($amount <= 0) $missing[] = 'amount';
if ($custName === '') $missing[] = 'cus_name';
if ($custEmail === '' || !filter_var($custEmail, FILTER_VALIDATE_EMAIL)) $missing[] = 'cus_email';
if ($custPhone === '') $missing[] = 'cus_phone';
if ($custAddress === '') $missing[] = 'cus_add1';
if ($custCity === '') $missing[] = 'cus_city';
if (!filter_var($successUrl, FILTER_VALIDATE_URL)) $missing[] = 'success_url';
if (!filter_var($failUrl, FILTER_VALIDATE_URL)) $missing[] = 'fail_url';
if (!filter_var($cancelUrl, FILTER_VALIDATE_URL)) $missing[] = 'cancel_url';

if (!empty($missing)) {
    fail($responseType, 400, 'Missing/invalid field(s): ' . implode(', ', $missing));
}

// ---- Create our own transaction record --------------------------------

$tranId = api_generate_tran_id();

$stmt = $conn->prepare('INSERT INTO api_transactions
    (partner_id, provider, tran_id, partner_order_ref, amount, currency, status,
     customer_name, customer_email, customer_phone, customer_address, customer_city, customer_country,
     partner_success_url, partner_fail_url, partner_cancel_url, partner_ipn_url,
     commission_percent)
    VALUES (?, ?, ?, ?, ?, ?, "INITIATED", ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');

$stmt->bind_param(
    'isssdsssssssssssd',
    $partner['id'],
    $providerName,
    $tranId,
    $orderRef,
    $amount,
    $currency,
    $custName,
    $custEmail,
    $custPhone,
    $custAddress,
    $custCity,
    $custCountry,
    $successUrl,
    $failUrl,
    $cancelUrl,
    $ipnUrl,
    $partner['commission_percent']
);

if (!$stmt->execute()) {
    fail($responseType, 500, 'Could not create transaction record');
}
$stmt->close();

// ---- Open the gateway checkout session --------------------------------

$session = $provider->createSession([
    'tran_id' => $tranId,
    'amount' => $amount,
    'currency' => $currency,
    'emi_option' => $emiOption,
    'cus_name' => $custName,
    'cus_email' => $custEmail,
    'cus_phone' => $custPhone,
    'cus_add1' => $custAddress,
    'cus_city' => $custCity,
    'cus_country' => $custCountry,
]);

if (!$session['ok'] || empty($session['redirect_url'])) {
    $conn->query("UPDATE api_transactions SET status = 'INIT_FAILED', raw_response = '" .
        $conn->real_escape_string(json_encode($session['raw'])) . "' WHERE tran_id = '" .
        $conn->real_escape_string($tranId) . "'");
    fail($responseType, 502, 'Failed to create payment session at the gateway');
}

$redirectUrl = $session['redirect_url'];

$conn->query("UPDATE api_transactions SET raw_response = '" .
    $conn->real_escape_string(json_encode($session['raw'])) . "' WHERE tran_id = '" .
    $conn->real_escape_string($tranId) . "'");

$conn->close();

if ($responseType === 'json') {
    api_json_response(200, [
        'status' => 'success',
        'tran_id' => $tranId,
        'provider' => $providerName,
        'GatewayPageURL' => $redirectUrl,
        'redirect_url' => $redirectUrl,
    ]);
}

header('Location: ' . $redirectUrl);
exit;
