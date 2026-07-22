<?php
/**
 * SSLCommerz implementation of the PaymentProvider contract.
 *
 * All SSLCommerz-specific behaviour lives here — endpoint URLs, the Session
 * API payload, order validation, refunds, and transaction queries. The
 * rest of the aggregator only ever sees the normalized shapes defined in
 * provider_interface.php.
 *
 * Uniformity note: the Session payload is built to look like the site's
 * own native checkout (sslpayment.php) so SSLCommerz can't tell a
 * partner-brokered payment apart from a native bosheboshe.com order. What
 * hides the SOURCE (which partner/website) is deliberate: fixed
 * "bosheboshe" ship_* identity, native-style tran_id, no partner metadata
 * in the value_* fields. Customer-level fields that genuinely differ per
 * real transaction — currency and cus_country — DO reflect the actual
 * payment (needed for international/multi-currency support); those describe
 * the buyer, not the originating site, so they don't leak the source.
 */

require_once __DIR__ . '/provider_interface.php';
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../lib/functions.php';

class SslcommerzProvider implements PaymentProvider
{
    public function name(): string
    {
        return 'sslcommerz';
    }

    public function createSession(array $txn): array
    {
        $post_data = [
            'store_id' => SSLCOMMERZ_STORE_ID,
            'store_passwd' => SSLCOMMERZ_STORE_PASSWD,
            'total_amount' => $txn['amount'],
            'currency' => $txn['currency'],
            'tran_id' => $txn['tran_id'],
            // Reuse the native store callback URLs so SSLCommerz sees the
            // exact same success/fail/cancel paths for every payment.
            'success_url' => API_SUCCESS_URL,
            'fail_url' => API_FAIL_URL,
            'cancel_url' => API_CANCEL_URL,
            'ipn_url' => API_IPN_URL,
            'emi_option' => $txn['emi_option'] ?? '0',
            'cus_name' => $txn['cus_name'],
            'cus_email' => $txn['cus_email'],
            'cus_add1' => $txn['cus_add1'],
            'cus_city' => $txn['cus_city'],
            'cus_country' => $txn['cus_country'] ?: 'Bangladesh',
            'cus_phone' => $txn['cus_phone'],
            // Merchant of record is always bosheboshe — never partner data.
            'ship_name' => 'bosheboshe',
            'ship_add1' => 'Dinajpur',
            'ship_city' => 'Dinajpur',
            'ship_postcode' => '5200',
            'ship_country' => 'Bangladesh',
            'value_a' => $txn['tran_id'],
            'product_amount' => $txn['amount'],
        ];

        $result = api_curl_post(SSLCZ_SESSION_API, $post_data);

        if (!$result['ok'] || empty($result['data']['GatewayPageURL'])) {
            return ['ok' => false, 'redirect_url' => null, 'raw' => $result['data']];
        }

        return [
            'ok' => true,
            'redirect_url' => $result['data']['GatewayPageURL'],
            'raw' => $result['data'],
        ];
    }

    public function validateCallback(array $callbackPost): array
    {
        $valId = $callbackPost['val_id'] ?? '';
        if ($valId === '') {
            return ['status' => 'VALIDATION_FAILED', 'bank_tran_id' => null,
                    'card_type' => null, 'base_amount_bdt' => null, 'raw' => null];
        }

        $url = SSLCZ_VALIDATION_API . '?' . http_build_query([
            'val_id' => $valId,
            'store_id' => SSLCOMMERZ_STORE_ID,
            'store_passwd' => SSLCOMMERZ_STORE_PASSWD,
            'v' => 1,
            'format' => 'json',
        ]);
        $result = api_curl_get($url);
        $data = $result['data'] ?? [];
        $gwStatus = $data['status'] ?? '';

        $status = in_array($gwStatus, ['VALID', 'VALIDATED'], true) ? 'VALID' : 'VALIDATION_FAILED';

        return [
            'status' => $status,
            'bank_tran_id' => $data['bank_tran_id'] ?? null,
            'card_type' => $data['card_type'] ?? null,
            // For non-BDT charges SSLCommerz reports the settled BDT amount.
            'base_amount_bdt' => isset($data['store_amount']) ? (float) $data['store_amount'] : null,
            'raw' => $data,
        ];
    }

    public function validateByValId(string $valId): array
    {
        return $this->validateCallback(['val_id' => $valId]);
    }

    public function initiateRefund(string $bankTranId, string $refundTransId, float $amount, string $remarks): array
    {
        $url = SSLCZ_TRANS_API . '?' . http_build_query([
            'bank_tran_id' => $bankTranId,
            'refund_trans_id' => $refundTransId,
            'refund_amount' => $amount,
            'refund_remarks' => $remarks,
            'store_id' => SSLCOMMERZ_STORE_ID,
            'store_passwd' => SSLCOMMERZ_STORE_PASSWD,
            'format' => 'json',
        ]);
        $result = api_curl_get($url);
        $data = $result['data'] ?? [];

        return [
            'status' => $data['status'] ?? 'unknown',
            'refund_ref_id' => $data['refund_ref_id'] ?? null,
            'raw' => $data,
        ];
    }

    public function queryRefund(string $refundRefId): array
    {
        $url = SSLCZ_TRANS_API . '?' . http_build_query([
            'refund_ref_id' => $refundRefId,
            'store_id' => SSLCOMMERZ_STORE_ID,
            'store_passwd' => SSLCOMMERZ_STORE_PASSWD,
            'format' => 'json',
        ]);
        $result = api_curl_get($url);
        $data = $result['data'] ?? [];

        return ['status' => $data['status'] ?? 'unknown', 'raw' => $data];
    }

    public function queryTransaction(string $tranId): array
    {
        $url = SSLCZ_TRANS_API . '?' . http_build_query([
            'tran_id' => $tranId,
            'store_id' => SSLCOMMERZ_STORE_ID,
            'store_passwd' => SSLCOMMERZ_STORE_PASSWD,
            'format' => 'json',
        ]);
        $result = api_curl_get($url);
        return ['raw' => $result['data'] ?? null];
    }
}
