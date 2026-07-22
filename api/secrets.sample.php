<?php
/**
 * Copy this file to secrets.php (same directory) and fill in real values.
 * secrets.php is gitignored — it must never be committed.
 */

// SSLCommerz merchant credentials (same account used by the main store checkout)
define('SSLCOMMERZ_STORE_ID', 'your_store_id');
define('SSLCOMMERZ_STORE_PASSWD', 'your_store_password');

// "live" or "sandbox" — controls which SSLCommerz host every API call uses
define('SSLCOMMERZ_MODE', 'live');

// Public base URL of this site, no trailing slash (used to build our own
// SSLCommerz callback URLs: {APP_BASE_URL}/api/payment_success.php etc.)
define('APP_BASE_URL', 'https://bosheboshe.com');

// Random 64+ char string, used as a fallback signing key. Generate with:
// bin2hex(random_bytes(32))
define('APP_MASTER_SIGNING_KEY', 'change_me_to_a_long_random_string');
