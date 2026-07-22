<?php
/**
 * Returns the PaymentProvider implementation for a given provider key.
 * This is the single place to register a new gateway.
 *
 * To add Stripe / BTCPayServer later:
 *   1. create providers/stripe.php with a class StripeProvider implements PaymentProvider
 *   2. add a case below
 *   3. add its key to SUPPORTED_PROVIDERS in config.php
 * Nothing in the endpoints changes.
 */

require_once __DIR__ . '/provider_interface.php';

function payment_provider(string $name): ?PaymentProvider
{
    switch ($name) {
        case 'sslcommerz':
            require_once __DIR__ . '/sslcommerz.php';
            return new SslcommerzProvider();

        // case 'stripe':
        //     require_once __DIR__ . '/stripe.php';
        //     return new StripeProvider();

        // case 'btcpay':
        //     require_once __DIR__ . '/btcpay.php';
        //     return new BtcpayProvider();

        default:
            return null;
    }
}
