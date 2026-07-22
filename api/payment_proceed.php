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
$currency = trim($_POST['currency'] ?? 'BDT') ?: 'BDT';
$custName = trim($_POST['cus_name'] ?? '');
$custEmail = trim($_POST['cus_email'] ?? '');
$custPhone = trim($_POST['cus_phone'] ?? '');
$custAddress = trim($_POST['cus_add1'] ?? '');
$custCity = trim($_POST['cus_city'] ?? '');
$custPostcode = trim($_POST['cus_postcode'] ?? '1000');
$successUrl = trim($_POST['success_url'] ?? '');
$failUrl = trim($_POST['fail_url'] ?? '');
$cancelUrl = trim($_POST['cancel_url'] ?? '');
$ipnUrl = trim($_POST['ipn_url'] ?? '');
$orderRef = trim($_POST['order_ref'] ?? '');
$emiOption = (isset($_POST['emi_option']) && $_POST['emi_option'] == '1') ? '1' : '0';
$productCategory = trim($_POST['product_category'] ?? 'general');
$productName = trim($_POST['product_name'] ?? 'Order');

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

$tranId = api_generate_tran_id($partner['partner_name']);

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
    'product_category' => $productCategory,
    'product_name' => $productName,
    'product_profile' => 'general',
    'cus_name' => $custName,
    'cus_email' => $custEmail,
    'cus_add1' => $custAddress,
    'cus_city' => $custCity,
    'cus_postcode' => $custPostcode,
    'cus_country' => 'Bangladesh',
    'cus_phone' => $custPhone,
    'shipping_method' => 'NO',
    'num_of_item' => 1,
    'value_a' => $tranId,
    'value_b' => $orderRef,
    'value_c' => (string) $partner['id'],
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
