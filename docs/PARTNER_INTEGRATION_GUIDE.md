# BosheBoshe Payment API — Integration Guide

Accept payments (cards, mobile banking, EMI) through BosheBoshe's
SSLCommerz merchant account without needing your own SSLCommerz
contract. BosheBoshe settles with SSLCommerz directly and takes a small
commission on each successful payment.

Base URL: `https://bosheboshe.com/api/`

You'll receive an **`api_key`** and (for refunds only) an **`api_secret`**
from BosheBoshe. Keep `api_secret` server-side only — never expose it in
frontend code.

## 1. Start a payment

`POST https://bosheboshe.com/api/payment_proceed.php`

The simplest integration is a plain HTML form — the customer's browser
gets redirected straight through to the SSLCommerz payment page:

```html
<form action="https://bosheboshe.com/api/payment_proceed.php" method="post">
  <input type="hidden" name="api_key" value="YOUR_API_KEY">
  <input type="hidden" name="amount" value="1250.00">
  <input type="hidden" name="currency" value="BDT">
  <input type="hidden" name="order_ref" value="YOUR-ORDER-1234">
  <input type="hidden" name="cus_name" value="Jane Doe">
  <input type="hidden" name="cus_email" value="jane@example.com">
  <input type="hidden" name="cus_phone" value="01712345678">
  <input type="hidden" name="cus_add1" value="123 Example Road">
  <input type="hidden" name="cus_city" value="Dhaka">
  <input type="hidden" name="success_url" value="https://yoursite.com/payment/success">
  <input type="hidden" name="fail_url" value="https://yoursite.com/payment/fail">
  <input type="hidden" name="cancel_url" value="https://yoursite.com/payment/cancel">
  <input type="hidden" name="ipn_url" value="https://yoursite.com/payment/ipn">
  <button type="submit">Pay Now</button>
</form>
```

### Fields

| Field | Required | Notes |
|---|---|---|
| `api_key` | yes | Given to you by BosheBoshe |
| `amount` | yes | Numeric, e.g. `1250.00` |
| `currency` | no | Default `BDT` |
| `order_ref` | no | Your own order/invoice ID — echoed back to you on every callback and query |
| `cus_name`, `cus_email`, `cus_phone`, `cus_add1`, `cus_city` | yes | Customer details required by SSLCommerz |
| `cus_postcode` | no | Default `1000` |
| `success_url`, `fail_url`, `cancel_url` | yes | **Your own pages.** BosheBoshe redirects the customer's browser here once the payment finishes — see §2 |
| `ipn_url` | no | Your server-to-server webhook — see §3 |
| `emi_option` | no | `1` to allow EMI (BosheBoshe's merchant account has EMI enabled), default `0` |
| `product_category`, `product_name` | no | Free text, defaults to `general` / `Order` |
| `response_type` | no | Omit for the redirect flow above. Set to `json` if you're calling this from your own backend instead of a browser form — you'll get back `{"status":"success","tran_id":"...","GatewayPageURL":"..."}` and should redirect your customer to `GatewayPageURL` yourself |

On validation failure you get back `400`/`401`/`502` with
`{"status":"error","message":"..."}`.

## 2. Getting the result back

Once the customer finishes at SSLCommerz, their browser is redirected to
**your** `success_url`, `fail_url`, or `cancel_url` (whichever applies)
with query parameters appended:

```
https://yoursite.com/payment/success?tran_id=BB-API-...&order_ref=YOUR-ORDER-1234&status=VALID&amount=1250.00&currency=BDT&val_id=...&bank_tran_id=...&card_type=...&timestamp=1753...&signature=...
```

**Always verify `signature` before trusting this data** — a customer could
otherwise hand-craft a URL claiming a payment succeeded. Recompute it
server-side with your `api_secret`:

```php
$data = $_GET; // or $_REQUEST
$signature = $data['signature'];
unset($data['signature']);
ksort($data);
$expected = hash_hmac('sha256', http_build_query($data), 'YOUR_API_SECRET');

if (!hash_equals($expected, $signature)) {
    // reject — do not mark the order as paid
}
```

`status` will be one of: `VALID` (paid), `VALIDATION_FAILED`, `FAILED`,
`CANCELLED`. Only treat `VALID` as a confirmed payment.

## 3. IPN (recommended in addition to §2)

Browsers don't always make it back to your success page (closed tab, lost
connection, etc). If you pass `ipn_url` when starting the payment,
BosheBoshe will also `POST` the same signed payload directly to your
server the moment the payment is confirmed — verify the signature exactly
as in §2. Treat this as the source of truth for "did I actually get
paid"; the browser redirect is just a nice UX shortcut.

## 4. Checking a transaction later ("enquiry")

`POST https://bosheboshe.com/api/transaction_query.php`

```
api_key=YOUR_API_KEY
tran_id=BB-API-...      (or order_ref=YOUR-ORDER-1234)
live=1                  (optional — also re-checks SSLCommerz directly, slower)
```

Returns your stored record for that transaction. Use this to reconcile
your own database rather than trusting only the browser redirect.

## 5. Refunds

Refunds require the elevated pair — **both** `api_key` and `api_secret` —
since they move money.

**Initiate:** `POST https://bosheboshe.com/api/refund_initiate.php`

```
api_key=YOUR_API_KEY
api_secret=YOUR_API_SECRET
tran_id=BB-API-...
refund_amount=500.00
refund_remarks=Customer requested partial refund
```

Only works on transactions in `VALID` status, and `refund_amount` can't
exceed the original amount. Returns `refund_trans_id` (yours to keep) and
`gateway_status`.

**Check refund status:** `POST https://bosheboshe.com/api/refund_query.php`

```
api_key=YOUR_API_KEY
api_secret=YOUR_API_SECRET
refund_trans_id=RFND-...
```

## 6. Testing

Ask BosheBoshe whether your `api_key` is pointed at SSLCommerz sandbox or
live mode before going live — this is a setting on BosheBoshe's side, not
something you control per-request.

## 7. Error handling summary

| HTTP code | Meaning |
|---|---|
| 400 | Missing/invalid field(s) — check the `message` |
| 401 | Bad or inactive `api_key` (and/or `api_secret` for refund endpoints) |
| 404 | Transaction/refund not found for your `api_key` |
| 405 | Wrong HTTP method — everything here is POST |
| 409 | Action not valid for the transaction's current state (e.g. refunding an unpaid transaction) |
| 502 | BosheBoshe couldn't reach SSLCommerz — retry later |
