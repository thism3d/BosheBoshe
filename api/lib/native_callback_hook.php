<?php
/**
 * Bridge that lets the native store callback pages
 * (successpayment.php / failedpayment.php / cancelpayment.php) double as
 * the aggregator's callback endpoints, so the gateway sees ONE identical
 * set of success/fail/cancel URLs for every payment.
 *
 * Include this at the very top of each native page (before any output) and
 * call aggregator_maybe_handle('success'|'fail'|'cancel'). If the posted
 * tran_id belongs to an aggregator transaction, we take over and hand off
 * to the API callback handler (which redirects the partner) — otherwise we
 * return immediately and the native page runs exactly as before.
 *
 * It is deliberately fail-open: any error (DB down, table missing, bad
 * input) simply falls through to native handling so the live store is
 * never broken by the aggregator.
 */

require_once __DIR__ . '/db.php';

function aggregator_maybe_handle(string $event): void
{
    if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
        return;
    }

    $tranId = trim((string) ($_POST['tran_id'] ?? $_POST['value_a'] ?? ''));
    if ($tranId === '') {
        return;
    }

    $conn = api_db_try_connect();
    if (!$conn) {
        return;
    }

    $isAggregator = false;
    if ($stmt = $conn->prepare('SELECT 1 FROM api_transactions WHERE tran_id = ? LIMIT 1')) {
        $stmt->bind_param('s', $tranId);
        $stmt->execute();
        $isAggregator = (bool) $stmt->get_result()->fetch_row();
        $stmt->close();
    }
    $conn->close();

    if (!$isAggregator) {
        return; // native transaction — let the store page handle it
    }

    require_once __DIR__ . '/../config.php';
    require_once __DIR__ . '/functions.php';
    require_once __DIR__ . '/callback_handler.php';
    api_handle_callback($event); // validates, updates, redirects partner, exits
}
