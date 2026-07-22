<?php
/**
 * Partner-facing payment initiate endpoint.
 *
 * External websites POST here (either as a plain HTML form submit, which
 * results in the browser being redirected straight to the SSLCommerz
 * gateway page, or with response_type=json for server-side integrations
 * that want the GatewayPageURL back as JSON).
 *
 * See /docs/PARTNER_INTEGRATION_GUIDE.md for the full contract.
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/lib/functions.php';

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

// ---- Validate required payment fields ---------------------------------

$amount = isset($_POST['amount']) ? (float) $_POST['amount'] : 0;

// BDT only for now — this SSLCommerz account isn't confirmed provisioned
// for other currencies, so reject anything else outright rather than let
// a partner silently hit a broken gateway session.
$currency = strtoupper(trim($_POST['currency'] ?? 'BDT')) ?: 'BDT';
if ($currency !== 'BDT') {
    fail($responseType, 400, 'Only BDT is supported at this time');
}

$custName = trim($_POST['cus_name'] ?? '');
$custEmail = trim($_POST['cus_email'] ?? '');
$custPhone = trim($_POST['cus_phone'] ?? '');
$custAddress = trim($_POST['cus_add1'] ?? '');
$custCity = trim($_POST['cus_city'] ?? '');
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
    (partner_id, tran_id, partner_order_ref, amount, currency, status,
     customer_name, customer_email, customer_phone, customer_address, customer_city,
     partner_success_url, partner_fail_url, partner_cancel_url, partner_ipn_url,
     commission_percent)
    VALUES (?, ?, ?, ?, ?, "INITIATED", ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');

$stmt->bind_param(
    'issdssssssssssd',
    $partner['id'],
    $tranId,
    $orderRef,
    $amount,
    $currency,
    $custName,
    $custEmail,
    $custPhone,
    $custAddress,
    $custCity,
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

// ---- Call the SSLCommerz Session API -----------------------------------
//
// This payload is deliberately built to look identical to the one the
// site's own checkout sends (see sslpayment.php) — same field set, same
// "bosheboshe" shipping identity, no partner-identifying data anywhere.
// SSLCommerz should not be able to tell a partner-brokered payment apart
// from a native bosheboshe.com purchase; the only thing that varies is
// which merchant-chosen tran_id and success/fail/cancel/ipn URLs we send,
// and those already point at our own domain either way. Which partner
// (and their order_ref) originated a given tran_id lives only in our own
// api_transactions table, looked up after the fact — never sent to
// SSLCommerz.

$post_data = [
    'store_id' => SSLCOMMERZ_STORE_ID,
    'store_passwd' => SSLCOMMERZ_STORE_PASSWD,
    'total_amount' => $amount,
    'currency' => $currency,
    'tran_id' => $tranId,
    'success_url' => API_SUCCESS_URL,
    'fail_url' => API_FAIL_URL,
    'cancel_url' => API_CANCEL_URL,
    'ipn_url' => API_IPN_URL,
    'emi_option' => $emiOption,
    'cus_name' => $custName,
    'cus_email' => $custEmail,
    'cus_add1' => $custAddress,
    'cus_city' => $custCity,
    'cus_country' => 'Bangladesh',
    'cus_phone' => $custPhone,
    'ship_name' => 'bosheboshe',
    'ship_add1' => 'Dinajpur',
    'ship_city' => 'Dinajpur',
    'ship_postcode' => '5200',
    'ship_country' => 'Bangladesh',
    'value_a' => $tranId,
    'product_amount' => $amount,
];

$result = api_curl_post(SSLCZ_SESSION_API, $post_data);

if (!$result['ok'] || empty($result['data']['GatewayPageURL'])) {
    $conn->query("UPDATE api_transactions SET status = 'INIT_FAILED', raw_response = '" .
        $conn->real_escape_string(json_encode($result['data'])) . "' WHERE tran_id = '" .
        $conn->real_escape_string($tranId) . "'");
    fail($responseType, 502, 'Failed to create SSLCommerz session');
}

$gatewayUrl = $result['data']['GatewayPageURL'];

$conn->query("UPDATE api_transactions SET raw_response = '" .
    $conn->real_escape_string(json_encode($result['data'])) . "' WHERE tran_id = '" .
    $conn->real_escape_string($tranId) . "'");

$conn->close();

if ($responseType === 'json') {
    api_json_response(200, [
        'status' => 'success',
        'tran_id' => $tranId,
        'GatewayPageURL' => $gatewayUrl,
    ]);
}

header('Location: ' . $gatewayUrl);
exit;
