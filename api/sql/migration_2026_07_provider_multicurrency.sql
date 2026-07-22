-- Migration: provider abstraction + international currency support.
-- Additive only. Run once per environment on top of the original schema:
--   mysql -u <user> -p <db> < api/sql/migration_2026_07_provider_multicurrency.sql
-- Re-running will error on already-existing columns (MySQL 5.7 has no
-- ADD COLUMN IF NOT EXISTS) — that's harmless, it just means it's applied.

ALTER TABLE api_transactions
    ADD COLUMN provider VARCHAR(30) NOT NULL DEFAULT 'sslcommerz' AFTER partner_id,
    ADD COLUMN customer_country VARCHAR(100) DEFAULT NULL AFTER customer_city,
    ADD COLUMN base_amount_bdt DECIMAL(12,2) DEFAULT NULL AFTER amount;

ALTER TABLE api_transactions ADD KEY idx_provider (provider);

ALTER TABLE api_refunds
    ADD COLUMN provider VARCHAR(30) NOT NULL DEFAULT 'sslcommerz' AFTER transaction_id;
