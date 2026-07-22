<?php
/**
 * Shared helpers for the partner payment broker API.
 */

require_once __DIR__ . '/db.php';

/**
 * Send a JSON response and stop execution.
 */
function api_json_response(int $httpCode, array $payload): void
{
    http_response_code($httpCode);
    header('Content-Type: application/json');
    echo json_encode($payload);
    exit;
}

/**
 * Generate a unique bosheboshe-side transaction id, in the same
 * BOSHEBOSHE_TRID_* style the native checkout already sends to
 * SSLCommerz (see sslpayment.php) — no partner name, no "API" marker,
 * nothing that would let SSLCommerz distinguish this from an ordinary
 * bosheboshe.com order.
 */
function api_generate_tran_id(): string
{
    return 'BOSHEBOSHE_TRID_' . time() . strtoupper(bin2hex(random_bytes(3)));
}

function api_generate_key(string $prefix, int $bytes = 24): string
{
    return $prefix . '_' . bin2hex(random_bytes($bytes));
}

/**
 * HMAC-SHA256 sign an associative array of redirect-back data using a
 * partner's private api_secret. Keys are sorted so both sides compute the
 * same signature regardless of array order.
 */
function api_sign_payload(array $data, string $secret): string
{
    ksort($data);
    $canonical = http_build_query($data);
    return hash_hmac('sha256', $canonical, $secret);
}

/**
 * Look up an active partner by api_key. Returns the row as an assoc array,
 * or null if not found / inactive.
 */
function api_get_partner_by_key(mysqli $conn, string $apiKey): ?array
{
    $stmt = $conn->prepare('SELECT * FROM api_partners WHERE api_key = ? AND status = "active" LIMIT 1');
    $stmt->bind_param('s', $apiKey);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    return $row ?: null;
}

/**
 * Perform a POST request (used for the SSLCommerz Session/Initiate API).
 */
function api_curl_post(string $url, array $fields): array
{
    $handle = curl_init();
    curl_setopt($handle, CURLOPT_URL, $url);
    curl_setopt($handle, CURLOPT_TIMEOUT, 30);
    curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($handle, CURLOPT_POST, 1);
    curl_setopt($handle, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);

    $content = curl_exec($handle);
    $code = curl_getinfo($handle, CURLINFO_HTTP_CODE);
    $error = curl_errno($handle);
    curl_close($handle);

    if ($error || $code !== 200 || $content === false) {
        return ['ok' => false, 'data' => null];
    }

    $decoded = json_decode($content, true);
    return ['ok' => true, 'data' => $decoded];
}

/**
 * Perform a GET request (used for Validation / Transaction Query / Refund
 * APIs, which are all GET per the SSLCommerz v4 docs).
 */
function api_curl_get(string $url): array
{
    $handle = curl_init();
    curl_setopt($handle, CURLOPT_URL, $url);
    curl_setopt($handle, CURLOPT_TIMEOUT, 30);
    curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);

    $content = curl_exec($handle);
    $code = curl_getinfo($handle, CURLINFO_HTTP_CODE);
    $error = curl_errno($handle);
    curl_close($handle);

    if ($error || $code !== 200 || $content === false) {
        return ['ok' => false, 'data' => null];
    }

    $decoded = json_decode($content, true);
    return ['ok' => true, 'data' => $decoded];
}

function api_sanitize(string $value): string
{
    return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
}

/**
 * Elevated auth for money-moving endpoints (refunds): requires both
 * api_key and the partner's private api_secret, not just the key.
 */
function api_get_partner_elevated(mysqli $conn, string $apiKey, string $apiSecret): ?array
{
    $stmt = $conn->prepare('SELECT * FROM api_partners WHERE api_key = ? AND api_secret = ? AND status = "active" LIMIT 1');
    $stmt->bind_param('ss', $apiKey, $apiSecret);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    return $row ?: null;
}
