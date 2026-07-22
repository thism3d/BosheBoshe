# BosheBoshe Payment Aggregator — Internal Documentation

This is the owner-facing reference for the payment aggregator that lets
other websites take payments *through* bosheboshe.com. BosheBoshe is the
merchant of record on every one of these transactions; partner sites never
see the gateway credentials, only bosheboshe's own API. Today the only
gateway wired up is SSLCommerz, but the whole stack is provider-agnostic so
Stripe / BTCPayServer / others can be added as drop-in handlers later.

For the document partner developers should receive, see
`PARTNER_INTEGRATION_GUIDE.md` in this same folder.

## Why this exists

BosheBoshe already has a live SSLCommerz merchant account (with EMI
enabled). Instead of every partner website applying for their own gateway
account, they send payment requests to bosheboshe.com, which opens the
gateway session on their behalf, tracks the transaction, and takes a
commission (default 1%, configurable per partner) once the payment is
confirmed.

## Architecture

```
Partner --POST--> /payment_proceed (root) --> /sslpayment (root) --(provider)--> gateway
                        |                            |                              |
                 api_transactions row        opens the session               customer pays
                        |                                                          |
Partner <--302 (signed)-- /successpayment (root native page + hook) <------------- gateway
                        |
                (also api/ipn.php, server-to-server, for reliability)
```

**Partner requests enter through BosheBoshe's own root URLs, never a
visible `/api/` path.** The root `payment_proceed.php` and `sslpayment.php`
each carry a tiny branch at the very top: if the request is a partner call
(`payment_proceed.php` checks for an `api_key` in the POST; `sslpayment.php`
checks for the aggregator context handed to it) it runs the aggregator via
`api/lib/aggregator_entry.php` and stops; otherwise the file's existing
native-checkout code runs unchanged. So a partner payment travels through
the same three root pages as a native order at every hop — entry
(`/payment_proceed`), gateway session (`/sslpayment`), and callbacks
(`/successpayment` etc.).

The `/api/` directory still holds all the engine code (libs, providers,
handlers) and the utility endpoints (ipn, transaction_query, refunds).
`api/payment_proceed.php` remains only as a backward-compatible alias that
calls the same `aggregator_initiate()` — new integrations should use the
root URL. The native cart checkout (`payment_proceed.php` /
`sslpayment.php` at the repo root) is otherwise untouched; the branches are
additive and fall through for any non-partner request.

### Provider abstraction (`api/providers/`)

`payment_proceed.php`, the callbacks, IPN, refunds and queries never talk
to a gateway directly — they go through a `PaymentProvider` (see
`providers/provider_interface.php`). `providers/sslcommerz.php` is the only
implementation today; `providers/factory.php` maps a provider key to its
class, and `SUPPORTED_PROVIDERS` in `config.php` lists what's enabled. Each
`api_transactions` / `api_refunds` row records which `provider` handled it.
**To add Stripe/BTCPay:** write `providers/stripe.php` implementing the
interface, add a `case` in the factory, add the key to `SUPPORTED_PROVIDERS`
— no endpoint changes.

### Callback URLs = the native store URLs (gateway sees one identity)

The gateway is given the **same** success/fail/cancel URLs the native
checkout uses — `/successpayment`, `/failedpayment`, `/cancelpayment`
(`API_SUCCESS_URL` etc. in `config.php`). Each of those three native pages
carries a small hook at the very top
(`require api/lib/native_callback_hook.php; aggregator_maybe_handle(...)`).
On every callback the hook does one cheap lookup: is the posted `tran_id`
in `api_transactions`?
- **Yes** → it's a partner payment: hand off to `api_handle_callback()`
  which verifies via the provider, updates the row, and 302-redirects the
  browser back to the partner's own URL with an HMAC-signed payload. The
  native page never renders.
- **No** → it's a native store order: the hook returns immediately and the
  native page runs exactly as before.

The hook is **fail-open** (`api_db_try_connect` returns null instead of
exiting) so a momentary aggregator-DB problem can never break the live
store checkout. Net effect: the gateway sees one identical set of callback
URLs for every payment and can't tell partner traffic from native traffic.

### What the gateway sees (source-hiding)

The session payload built in `providers/sslcommerz.php` looks like the
native `sslpayment.php` request: fixed `ship_name`/`ship_add1`/`ship_city`
= bosheboshe's own address (never the partner's), a `BOSHEBOSHE_TRID_...`
tran_id (no `BB-API-`, no partner name), and no `value_b`/`value_c` partner
metadata — the gateway gets one merchant-chosen reference (`tran_id`,
echoed in `value_a`) and nothing else. Partner + order_ref live only in
`api_transactions`, looked up locally by `tran_id`.

What *does* vary per real transaction and is intentionally passed through:
`currency` (multi-currency, below) and `cus_country`. Those describe the
buyer and the charge, not the originating website, so they don't leak the
source. `ipn_url` points at our own `/api/ipn.php` — also on bosheboshe's
domain, so it reveals nothing about the partner either.

### Multi-currency

`SUPPORTED_CURRENCIES` in `config.php` = `BDT, USD, EUR, GBP, AUD, CAD,
SGD, INR, MYR`. Partners may charge in any of these; `payment_proceed.php`
rejects anything else with `400`. SSLCommerz converts non-BDT to BDT at its
current rate; when it reports the settled BDT figure (`store_amount`) we
store it in `api_transactions.base_amount_bdt`, and the dashboard sums
that (falling back to `amount` for BDT charges) so volume/commission totals
are always in BDT.

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
| **`/payment_proceed`** (root) → `/sslpayment` (root) | `api_key` | Partner entry. Validates + records the transaction, opens the gateway session, redirects the browser (or returns JSON with `response_type=json`). Selects the gateway via the optional `provider` field. `/api/payment_proceed.php` is a kept-alive alias |
| `/successpayment`, `/failedpayment`, `/cancelpayment` (native pages + hook) | none (gateway calls these) | Verify via the provider, update the transaction, 302 back to the partner's own URL with a signed payload. Native store orders fall through to normal handling |
| `ipn.php` | none (gateway server-to-server) | Backup confirmation path in case the browser never returns; also relays to the partner's `ipn_url` if they registered one |
| `transaction_query.php` | `api_key` | Partner "enquiry" — look up one of their transactions by `tran_id` or `order_ref`; `live=1` also re-checks the gateway directly |
| `refund_initiate.php` | `api_key` **+** `api_secret` | Initiate a refund on a `VALID` transaction |
| `refund_query.php` | `api_key` **+** `api_secret` | Check the status of a previously initiated refund |

The thin `api/payment_success.php` / `payment_fail.php` / `payment_cancel.php`
files still exist and call the same handler, but the gateway no longer
targets them — the native pages are the registered callback URLs now.

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
- **Overview** (`dashboard.php`) — stat tiles (partners, transactions,
  success rate, BDT volume, commission), a 14-day transactions chart, a
  status breakdown, volume-by-currency, and recent activity.
- **Transactions** (`transactions.php`) — filterable list showing
  provider + currency (with the BDT-equivalent for foreign charges); per
  row you can re-query live status at the gateway or submit a refund (admin
  refunds don't need the partner's `api_secret` — you're already
  authenticated).
- **Refunds** (`refunds.php`) — every refund with its gateway status; each
  can be re-queried live.
- **Partners** (`partners.php`) — create new partner credentials (a fresh
  `api_key`/`api_secret` pair is generated), set commission %, activate/
  deactivate, and copy a ready-to-paste integration snippet per partner.
  The seed partner created from the token you supplied is listed here as
  "Default Partner (seed)".

This whole payment system lives under `/mUfoNyfOnhaj/` only — it is
completely separate from the shop-merchant area under `/merchant/`, which
is untouched. The dashboard path is intentionally unguessable rather than
linked from anywhere on the public site — treat that obscurity as a minor
extra layer, not the actual security boundary (the login is). The chart
colours follow the validated dataviz palette (single-hue bars for
magnitude, the fixed status palette for state, theme-aware light/dark).

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
- **IPN is registered (`/api/ipn.php`) but the native checkout doesn't send
  an `ipn_url`.** That's a deliberate reliability choice for partner
  payments and doesn't leak the source (the URL is on bosheboshe's domain).
  If you ever want partner traffic to be *byte-identical* to native even at
  the IPN layer, drop `ipn_url` from `providers/sslcommerz.php::createSession`
  and rely on the browser callback + a periodic reconcile job instead.
- **Currency ↔ country coherence.** For international charges partners
  should pass the real `cus_country`; if they charge in USD but leave the
  country as Bangladesh, some foreign card issuers may decline. That's a
  partner-side data-quality issue, not a bug here.
- Adding a new gateway (Stripe/BTCPay) means implementing the provider
  interface AND deciding that gateway's own callback-URL strategy — the
  native-page hook trick is SSLCommerz-specific (it shares bosheboshe's one
  SSLCommerz store). A new gateway will likely use its own dedicated
  callback routes under `/api/`.
