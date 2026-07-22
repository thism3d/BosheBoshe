<?php
/**
 * Backward-compatible alias.
 *
 * The canonical partner entry point is now the ROOT URL
 * https://bosheboshe.com/payment_proceed  (see root payment_proceed.php),
 * so a partner request enters through bosheboshe's own page rather than a
 * visible /api/ path. This file is kept only so any integration still
 * posting to /api/payment_proceed.php keeps working — it runs the exact
 * same handler.
 */

require_once __DIR__ . '/lib/aggregator_entry.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    api_json_response(405, ['status' => 'error', 'message' => 'Use POST']);
}

aggregator_initiate();
