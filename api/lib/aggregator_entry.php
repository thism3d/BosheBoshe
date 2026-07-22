<?php
/**
 * Aggregator entry logic invoked FROM the native root pages, so partner
 * requests come in through bosheboshe.com's own URLs — /payment_proceed and
 * /sslpayment — rather than a distinct /api/ path. This matches the native
 * checkout's URL surface at every hop (entry, gateway session, and the
 * /successpayment /failedpayment /cancelpayment callbacks), so nothing about
 * a partner payment looks different from a native bosheboshe.com order from
 * the outside.
 *
 * Flow:
 *   root payment_proceed.php  --(POST has api_key)-->  aggregator_initiate()
 *        validates the partner + fields, inserts an api_transactions row,
 *        stashes the transaction context in $GLOBALS, then requires the root
 *        sslpayment.php.
 *   root sslpayment.php  --(context set)-->  aggregator_run_session()
 *        opens the gateway session via the transaction's provider, then
 *        redirects the browser (or returns JSON for response_type=json).
 *
 * A native cart checkout carries no api_key and no context, so it falls
 * straight through both files and runs their existing code untouched.
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/../providers/factory.php';

/** True when the current request is a partner/API call rather than a native checkout. */
function aggregator_is_api_request(): bool
{
    return ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['api_key']);
}

/**
 * Step 1 — runs inside root payment_proceed.php. Validates and records the
 * transaction, then hands off to root sslpayment.php to open the session.
 * Always exits.
 */
function aggregator_initiate(): void
{
    $responseType = (($_POST['response_type'] ?? '') === 'json') ? 'json' : 'redirect';

    $apiKey = trim($_POST['api_key'] ?? '');
    if ($apiKey === '') {
        api_json_response(401, ['status' => 'error', 'message' => 'Missing api_key']);
    }

    $conn = api_db_connect();
    $partner = api_get_partner_by_key($conn, $apiKey);
    if (!$partner) {
        api_json_response(401, ['status' => 'error', 'message' => 'Invalid or inactive api_key']);
    }

    $providerName = strtolower(trim($_POST['provider'] ?? DEFAULT_PROVIDER)) ?: DEFAULT_PROVIDER;
    if (!in_array($providerName, SUPPORTED_PROVIDERS, true)) {
        api_json_response(400, ['status' => 'error', 'message' => 'Unsupported provider. Supported: ' . implode(', ', SUPPORTED_PROVIDERS)]);
    }

    $amount = isset($_POST['amount']) ? (float) $_POST['amount'] : 0;

    $currency = strtoupper(trim($_POST['currency'] ?? 'BDT')) ?: 'BDT';
    if (!in_array($currency, SUPPORTED_CURRENCIES, true)) {
        api_json_response(400, ['status' => 'error', 'message' => 'Unsupported currency. Supported: ' . implode(', ', SUPPORTED_CURRENCIES)]);
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
        api_json_response(400, ['status' => 'error', 'message' => 'Missing/invalid field(s): ' . implode(', ', $missing)]);
    }

    $tranId = api_generate_tran_id();

    $stmt = $conn->prepare('INSERT INTO api_transactions
        (partner_id, provider, tran_id, partner_order_ref, amount, currency, status,
         customer_name, customer_email, customer_phone, customer_address, customer_city, customer_country,
         partner_success_url, partner_fail_url, partner_cancel_url, partner_ipn_url,
         commission_percent)
        VALUES (?, ?, ?, ?, ?, ?, "INITIATED", ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
    $stmt->bind_param(
        'isssdsssssssssssd',
        $partner['id'], $providerName, $tranId, $orderRef, $amount, $currency,
        $custName, $custEmail, $custPhone, $custAddress, $custCity, $custCountry,
        $successUrl, $failUrl, $cancelUrl, $ipnUrl, $partner['commission_percent']
    );
    if (!$stmt->execute()) {
        api_json_response(500, ['status' => 'error', 'message' => 'Could not create transaction record']);
    }
    $stmt->close();
    $conn->close();

    // Hand off to the native SSLCommerz page — the gateway session is opened
    // there, so the request travels through bosheboshe's own /sslpayment page
    // exactly like a native checkout does.
    $GLOBALS['aggregator_txn'] = [
        'provider' => $providerName,
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
        'response_type' => $responseType,
    ];
    require dirname(__DIR__, 2) . '/sslpayment.php';
    exit;
}

/**
 * Step 2 — runs inside root sslpayment.php. Opens the gateway session via the
 * provider and redirects the customer (or returns JSON). Always exits.
 */
function aggregator_run_session(array $txn): void
{
    $provider = payment_provider($txn['provider']);
    if (!$provider) {
        api_json_response(500, ['status' => 'error', 'message' => 'Provider not available']);
    }

    $session = $provider->createSession($txn);

    $conn = api_db_connect();
    $raw = json_encode($session['raw']);
    $tid = $txn['tran_id'];

    if (!$session['ok'] || empty($session['redirect_url'])) {
        $stmt = $conn->prepare("UPDATE api_transactions SET status = 'INIT_FAILED', raw_response = ? WHERE tran_id = ?");
        $stmt->bind_param('ss', $raw, $tid);
        $stmt->execute();
        $stmt->close();
        $conn->close();
        api_json_response(502, ['status' => 'error', 'message' => 'Failed to create payment session at the gateway']);
    }

    $stmt = $conn->prepare("UPDATE api_transactions SET raw_response = ? WHERE tran_id = ?");
    $stmt->bind_param('ss', $raw, $tid);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    if ($txn['response_type'] === 'json') {
        api_json_response(200, [
            'status' => 'success',
            'tran_id' => $txn['tran_id'],
            'provider' => $txn['provider'],
            'GatewayPageURL' => $session['redirect_url'],
            'redirect_url' => $session['redirect_url'],
        ]);
    }

    header('Location: ' . $session['redirect_url']);
    exit;
}
