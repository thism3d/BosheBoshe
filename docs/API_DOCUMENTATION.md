# BosheBoshe Payment Broker API — Internal Documentation

This is the owner-facing reference for the system that lets other websites
create SSLCommerz payments *through* bosheboshe.com. BosheBoshe is the
merchant of record on every one of these transactions; partner sites never
see the SSLCommerz store credentials, only bosheboshe's own API.

For the document partner developers should receive, see
`PARTNER_INTEGRATION_GUIDE.md` in this same folder.

## Why this exists

BosheBoshe already has a live SSLCommerz merchant account (with EMI
enabled). Instead of every partner website applying for their own
SSLCommerz account, they send payment requests to bosheboshe.com, which
creates the SSLCommerz session on their behalf, tracks the transaction,
and takes a commission (default 1%, configurable per partner) once the
payment is confirmed.

## Architecture

```
Partner site  --POST-->  api/payment_proceed.php  --Session API-->  SSLCommerz
                                                                          |
                                                                   customer pays
                                                                          |
Partner site  <--302 (signed)--  api/payment_success.php  <--callback--- SSLCommerz
                                        |
                                (also api/ipn.php, server-to-server, for reliability)
```

All of this lives under `/api/`, kept separate from the site's own
checkout (`payment_proceed.php` / `sslpayment.php` at the repo root, which
are unrelated and were **not** touched — those power BosheBoshe's own cart
checkout and the naming collision is coincidental).

**SSLCommerz never sees which layer a transaction came from.** The
Session API payload built in `api/payment_proceed.php` is deliberately
shaped to be indistinguishable from the one `sslpayment.php` sends for a
native purchase: same field set, `ship_name`/`ship_add1`/`ship_city` fixed
to bosheboshe's own address (never the partner's), `cus_country`
hardcoded, and `tran_id` generated in the same `BOSHEBOSHE_TRID_<unique>`
style — no `BB-API-`, no partner name, nothing that reads as
aggregator/reseller traffic. There is no `product_category`,
`product_name`, or `cus_postcode` field sent at all (the native flow
doesn't send them either), and no `value_b`/`value_c` metadata — SSLCommerz
gets exactly one merchant-chosen reference (`tran_id`, echoed in
`value_a`) and nothing else. All partner/order-ref bookkeeping happens
only in `api_transactions`, looked up locally by `tran_id` after the fact.
If this payload is ever changed, keep it byte-for-byte aligned with
`sslpayment.php`'s `$post_data` — that's the whole point.

## Database (additive only — see `api/sql/schema.sql`)

- **`api_partners`** — one row per partner website. `api_key` is what they
  send on every request; `api_secret` is private, used only server-side by
  bosheboshe to HMAC-sign the data redirected back to the partner (and
  required, alongside `api_key`, for refund endpoints). `commission_percent`
  is applied to every successful transaction.
- **`api_transactions`** — one row per payment attempt. Tracks status
  (`INITIATED` → `VALID`/`FAILED`/`VALIDATION_FAILED`/`CANCELLED`/
  `INIT_FAILED`), the partner's own success/fail/cancel/ipn URLs, and the
  computed `commission_amount` once a payment is confirmed VALID.
- **`api_refunds`** — one row per refund attempt, linked to a transaction.
- **`panel_admins`** — dashboard login(s). Password is bcrypt-hashed via
  PHP's `password_hash`; there is no plaintext copy anywhere in the repo.

## Secrets

`api/secrets.php` holds the real SSLCommerz store credentials and the app
base URL. It is **gitignored** — `api/secrets.sample.php` is the committed
template. If this ever needs to be set up on a new server, copy the
sample, fill in the real store_id/store_passwd, and generate a fresh
`APP_MASTER_SIGNING_KEY` with `bin2hex(random_bytes(32))`.

## Endpoints

All endpoints live in `/api/`. All are POST except where noted; POST body
is standard `application/x-www-form-urlencoded` (a plain HTML form works).

| Endpoint | Auth | Purpose |
|---|---|---|
| `payment_proceed.php` | `api_key` | Create a payment session, redirect the browser to SSLCommerz (or return JSON with `response_type=json`) |
| `payment_success.php` / `payment_fail.php` / `payment_cancel.php` | none (SSLCommerz calls these) | Validate the result, update the transaction, 302 back to the partner's own URL with a signed payload |
| `ipn.php` | none (SSLCommerz server-to-server) | Backup confirmation path in case the browser never returns; also relays to the partner's `ipn_url` if they registered one |
| `transaction_query.php` | `api_key` | Partner "enquiry" — look up one of their transactions by `tran_id` or `order_ref`; `live=1` also re-checks SSLCommerz directly |
| `refund_initiate.php` | `api_key` **+** `api_secret` | Initiate a refund on a `VALID` transaction |
| `refund_query.php` | `api_key` **+** `api_secret` | Check the status of a previously initiated refund |

Refunds require the elevated (`api_key` + `api_secret`) pair since they
move money; enquiry only needs `api_key` since it's read-only.

Full request/response field lists are in `PARTNER_INTEGRATION_GUIDE.md`.

## SSLCommerz v4 mapping

Confirmed against `https://developer.sslcommerz.com/doc/v4/` at build time:

- Session/Initiate: `POST {host}/gwprocess/v4/api.php`
- Order Validation: `GET {host}/validator/api/validationserverAPI.php` (`val_id`, `store_id`, `store_passwd`)
- Transaction Query (by `tran_id` or `sessionkey`) and both Refund
  endpoints share one host path: `GET {host}/validator/api/merchantTransIDvalidationAPI.php`
  — differentiated by which parameters you send (`tran_id` for a query,
  `bank_tran_id`+`refund_trans_id`+`refund_amount`+`refund_remarks` to
  initiate a refund, `refund_ref_id` to query a refund).
- `{host}` is `https://securepay.sslcommerz.com` in live mode or
  `https://sandbox.sslcommerz.com` in sandbox mode (`SSLCOMMERZ_MODE` in
  `api/secrets.php`).

If SSLCommerz ever changes these paths, the only place to update is
`api/config.php`.

## Dashboard (`/mUfoNyfOnhaj/`)

- **First run:** visit `/mUfoNyfOnhaj/setup.php` once to create the admin
  login — it only works while `panel_admins` is empty, so there's never a
  plaintext password sitting in a file or SQL dump. (Already done for this
  environment's local database — log in directly at
  `/mUfoNyfOnhaj/index.php`.)
- **Overview** (`dashboard.php`) — partner counts, total transaction
  volume, commission earned, recent activity.
- **Transactions** (`transactions.php`) — filterable list; per row you can
  re-query live status at SSLCommerz or submit a refund (admin refunds
  don't need the partner's `api_secret` — you're already authenticated).
- **Partners** (`partners.php`) — create new partner credentials (a fresh
  `api_key`/`api_secret` pair is generated), set commission %, activate/
  deactivate. The seed partner created from the token you supplied is
  listed here as "Default Partner (seed)".

The dashboard path (`/mUfoNyfOnhaj/`) is intentionally unguessable rather
than linked from anywhere on the public site — treat that obscurity as a
minor extra layer, not the actual security boundary (the login is).

## Known gaps / follow-ups worth doing before heavy production use

- No rate limiting on any endpoint — consider adding it at the web server
  level if partner volume grows.
- `payment_proceed.php`'s SSLCommerz call isn't wrapped in a DB
  transaction; a crash between the INSERT and the cURL call would leave an
  `INITIATED` row with no corresponding SSLCommerz session. Harmless (it
  just never gets paid), but worth a periodic cleanup query if it bothers
  you.
- Consider adding a `panel_admins` password-reset flow if more than one
  admin will ever use this.
- **BDT only, by design.** `payment_proceed.php` rejects any `currency`
  other than `BDT` with a `400` — this SSLCommerz account was never
  confirmed provisioned for other currencies, so a partner passing `USD`
  would otherwise hit a broken/unpredictable gateway session. If
  international payments are ever needed, first confirm with SSLCommerz
  that the account supports non-BDT currency, then revisit the hardcoded
  `cus_country`/`ship_country` = `"Bangladesh"` in the Session API payload
  (see the note above) — a genuinely international customer shouldn't be
  tagged as Bangladeshi. Until then, don't just relax the currency check
  without also fixing that.
