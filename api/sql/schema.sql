-- Payment broker API schema. Additive only — does not touch any existing
-- table. Safe to run against the same database as the rest of the site.
-- Run once per environment: mysql -u <user> -p <db> < api/sql/schema.sql

CREATE TABLE IF NOT EXISTS api_partners (
    id INT NOT NULL AUTO_INCREMENT,
    partner_name VARCHAR(150) NOT NULL,
    api_key VARCHAR(120) NOT NULL,
    api_secret VARCHAR(120) NOT NULL,
    website_domain VARCHAR(255) DEFAULT NULL,
    contact_email VARCHAR(255) DEFAULT NULL,
    commission_percent DECIMAL(5,2) NOT NULL DEFAULT 1.00,
    status ENUM('active','inactive') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uniq_api_key (api_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS api_transactions (
    id INT NOT NULL AUTO_INCREMENT,
    partner_id INT NOT NULL,
    provider VARCHAR(30) NOT NULL DEFAULT 'sslcommerz',
    tran_id VARCHAR(100) NOT NULL,
    partner_order_ref VARCHAR(150) DEFAULT NULL,
    amount DECIMAL(12,2) NOT NULL,
    base_amount_bdt DECIMAL(12,2) DEFAULT NULL,
    currency VARCHAR(10) NOT NULL DEFAULT 'BDT',
    status VARCHAR(30) NOT NULL DEFAULT 'INITIATED',
    val_id VARCHAR(120) DEFAULT NULL,
    bank_tran_id VARCHAR(120) DEFAULT NULL,
    card_type VARCHAR(60) DEFAULT NULL,
    customer_name VARCHAR(150) DEFAULT NULL,
    customer_email VARCHAR(255) DEFAULT NULL,
    customer_phone VARCHAR(30) DEFAULT NULL,
    customer_address VARCHAR(255) DEFAULT NULL,
    customer_city VARCHAR(100) DEFAULT NULL,
    customer_country VARCHAR(100) DEFAULT NULL,
    partner_success_url TEXT DEFAULT NULL,
    partner_fail_url TEXT DEFAULT NULL,
    partner_cancel_url TEXT DEFAULT NULL,
    partner_ipn_url TEXT DEFAULT NULL,
    commission_percent DECIMAL(5,2) DEFAULT NULL,
    commission_amount DECIMAL(12,2) DEFAULT NULL,
    raw_response LONGTEXT DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uniq_tran_id (tran_id),
    KEY idx_partner_id (partner_id),
    KEY idx_status (status),
    KEY idx_provider (provider),
    CONSTRAINT fk_api_transactions_partner FOREIGN KEY (partner_id) REFERENCES api_partners(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS api_refunds (
    id INT NOT NULL AUTO_INCREMENT,
    transaction_id INT NOT NULL,
    provider VARCHAR(30) NOT NULL DEFAULT 'sslcommerz',
    refund_trans_id VARCHAR(100) NOT NULL,
    refund_ref_id VARCHAR(120) DEFAULT NULL,
    bank_tran_id VARCHAR(120) NOT NULL,
    refund_amount DECIMAL(12,2) NOT NULL,
    refund_remarks VARCHAR(255) DEFAULT NULL,
    status VARCHAR(30) NOT NULL DEFAULT 'INITIATED',
    raw_response LONGTEXT DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uniq_refund_trans_id (refund_trans_id),
    KEY idx_transaction_id (transaction_id),
    CONSTRAINT fk_api_refunds_transaction FOREIGN KEY (transaction_id) REFERENCES api_transactions(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS panel_admins (
    id INT NOT NULL AUTO_INCREMENT,
    email VARCHAR(255) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uniq_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
