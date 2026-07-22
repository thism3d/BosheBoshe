<?php
/**
 * Contract every payment gateway must implement so the rest of the
 * aggregator (payment_proceed, callbacks, ipn, refunds, queries) never
 * has to know which gateway it's talking to.
 *
 * SSLCommerz is the first implementation (providers/sslcommerz.php).
 * Stripe / BTCPayServer / others plug in later by adding a class here and
 * registering it in providers/factory.php — no changes needed in the
 * endpoints themselves.
 *
 * Normalized status vocabulary every provider maps its own result into:
 *   VALID              — payment confirmed / money captured
 *   VALIDATION_FAILED  — gateway reported the payment could not be validated
 *   FAILED             — payment failed
 *   CANCELLED          — customer cancelled
 */
interface PaymentProvider
{
    /**
     * Provider key as stored in api_transactions.provider (e.g. 'sslcommerz').
     */
    public function name(): string;

    /**
     * Create a hosted-checkout session and return where to send the customer.
     *
     * @param array $txn Normalized transaction fields:
     *   tran_id, amount, currency, emi_option,
     *   cus_name, cus_email, cus_phone, cus_add1, cus_city, cus_country
     * @return array ['ok' => bool, 'redirect_url' => string|null, 'raw' => mixed]
     */
    public function createSession(array $txn): array;

    /**
     * Verify a payment from the data the gateway posted to our callback.
     *
     * @return array ['status' => string, 'bank_tran_id' => ?string,
     *                'card_type' => ?string, 'base_amount_bdt' => ?float,
     *                'raw' => mixed]
     */
    public function validateCallback(array $callbackPost): array;

    /**
     * @return array ['status' => string, 'refund_ref_id' => ?string, 'raw' => mixed]
     */
    public function initiateRefund(string $bankTranId, string $refundTransId, float $amount, string $remarks): array;

    /**
     * @return array ['status' => string, 'raw' => mixed]
     */
    public function queryRefund(string $refundRefId): array;

    /**
     * @return array ['raw' => mixed]
     */
    public function queryTransaction(string $tranId): array;
}
