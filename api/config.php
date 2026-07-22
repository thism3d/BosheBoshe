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

// Our own callback URLs that SSLCommerz redirects/POSTs back to.
define('API_SUCCESS_URL', APP_BASE_URL . '/api/payment_success.php');
define('API_FAIL_URL', APP_BASE_URL . '/api/payment_fail.php');
define('API_CANCEL_URL', APP_BASE_URL . '/api/payment_cancel.php');
define('API_IPN_URL', APP_BASE_URL . '/api/ipn.php');

// Default commission percentage applied to newly created partners.
define('DEFAULT_COMMISSION_PERCENT', 1.00);
