<?php
/**
 * Derived configuration for the partner payment broker API.
 * Loads api/secrets.php (gitignored) and exposes the SSLCommerz endpoint
 * URLs for whichever mode (live/sandbox) is configured there.
 */

require_once __DIR__ . '/secrets.php';

$GLOBALS['SSLCZ_HOST'] = (SSLCOMMERZ_MODE === 'sandbox')
    ? 'https://sandbox.sslcommerz.com'
    : 'https://securepay.sslcommerz.com';

define('SSLCZ_SESSION_API', $GLOBALS['SSLCZ_HOST'] . '/gwprocess/v4/api.php');
define('SSLCZ_VALIDATION_API', $GLOBALS['SSLCZ_HOST'] . '/validator/api/validationserverAPI.php');
define('SSLCZ_TRANS_API', $GLOBALS['SSLCZ_HOST'] . '/validator/api/merchantTransIDvalidationAPI.php');

// Callback URLs registered with the gateway. Deliberately the SAME URLs
// the native store checkout uses (see sslpayment.php) — the native
// success/fail/cancel pages carry a small hook (api/lib/native_callback_hook.php)
// that detects an aggregator transaction by its tran_id and hands off to
// the API callback handler. That way the gateway sees one identical set of
// callback URLs for every payment, native or partner-brokered.
define('API_SUCCESS_URL', APP_BASE_URL . '/successpayment');
define('API_FAIL_URL', APP_BASE_URL . '/failedpayment');
define('API_CANCEL_URL', APP_BASE_URL . '/cancelpayment');
// IPN stays on our own /api/ path — it points at bosheboshe's domain and
// reveals nothing about which partner originated the payment, so it does
// not leak the source. It only exists to make confirmation reliable when a
// customer's browser never returns to the callback page.
define('API_IPN_URL', APP_BASE_URL . '/api/ipn.php');

// Default commission percentage applied to newly created partners.
define('DEFAULT_COMMISSION_PERCENT', 1.00);

// Currencies partners may charge in. SSLCommerz converts any non-BDT
// amount to BDT at the current rate before settling. Expand this list as
// the merchant account gets provisioned for more currencies.
define('SUPPORTED_CURRENCIES', ['BDT', 'USD', 'EUR', 'GBP', 'AUD', 'CAD', 'SGD', 'INR', 'MYR']);

// Payment gateways this aggregator can route to. Each must have a case in
// api/providers/factory.php. Add 'stripe', 'btcpay', etc. here as they're
// implemented.
define('SUPPORTED_PROVIDERS', ['sslcommerz']);
define('DEFAULT_PROVIDER', 'sslcommerz');
