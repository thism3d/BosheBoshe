<?php
/**
 * DB connection for the partner payment broker API. Reuses the same
 * MySQL server/credentials as the rest of the site (connectserver.php)
 * but returns the connection instead of relying on a global $conn, so
 * each API endpoint controls its own lifecycle.
 */

function api_db_connect(): mysqli
{
    $servername = "localhost";
    $username = "bosheboshe_udtxasd";
    $password = "@RGYhjfasdtU1245";
    $dbname = "bosheboshe_userdatabase";

    mysqli_report(MYSQLI_REPORT_OFF);
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        api_json_response(500, ['status' => 'error', 'message' => 'Database connection failed']);
    }

    $conn->set_charset('utf8mb4');

    return $conn;
}

/**
 * Fail-open variant: returns null instead of emitting a response and
 * exiting when the DB is unreachable. Used by the native callback hook,
 * which must never break the native store pages if the aggregator's DB is
 * momentarily down — it just falls through to normal native handling.
 */
function api_db_try_connect(): ?mysqli
{
    mysqli_report(MYSQLI_REPORT_OFF);
    $conn = @new mysqli('localhost', 'bosheboshe_udtxasd', '@RGYhjfasdtU1245', 'bosheboshe_userdatabase');
    if ($conn->connect_error) {
        return null;
    }
    $conn->set_charset('utf8mb4');
    return $conn;
}
