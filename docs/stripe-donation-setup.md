# Stripe Donations Setup

This guide connects Threefold Artists' donation flow to Stripe **test mode** first, then describes the production switch. The application treats verified Stripe webhooks—not browser return URLs—as the source of truth for donation records, receipts, recurring-support state, refunds, and disputes.

## 1. Prepare Stripe test mode

1. In the Stripe Dashboard, enable **Test mode**.
2. Create or select the account that will receive Threefold Artists donations.
3. Copy its **Publishable key** and **Secret key** from **Developers → API keys**.
4. In **Developers → Webhooks**, add an endpoint:

   ```text
   https://<your-site-domain>/stripe/webhook
   ```

   For the Forge production host, use its final HTTPS domain—not a localhost or temporary URL.

5. Select these events:

   - `checkout.session.completed`
   - `invoice.paid`
   - `invoice.payment_failed`
   - `customer.subscription.created`
   - `customer.subscription.updated`
   - `customer.subscription.deleted`
   - `charge.refunded`
   - `charge.dispute.closed`

6. Save the endpoint and copy its **Signing secret** (`whsec_…`). This is different from the secret API key.

## 2. Configure the server

Set these values in the Forge site's environment file. Do not commit them.

```dotenv
APP_URL=https://<your-site-domain>

STRIPE_KEY=pk_test_...
STRIPE_SECRET=sk_test_...
STRIPE_WEBHOOK_SECRET=whsec_...

# Required for real donor access-link and receipt emails.
MAIL_MAILER=<configured-mailer>
MAIL_FROM_ADDRESS=hello@threefoldartists.org
MAIL_FROM_NAME="Threefold Artists"
```

`STRIPE_KEY` is retained for client-facing configuration; the current donation checkout and portal calls use `STRIPE_SECRET` on the server. The signing secret is mandatory: without `STRIPE_WEBHOOK_SECRET`, `POST /stripe/webhook` returns HTTP 503; with a mismatched secret, it returns HTTP 400.

The approved organisation identity and donation disclosures have safe defaults in `config/donations.php`. Set environment values only if the organisation later changes any of them:

```dotenv
DONATIONS_MAILING_ADDRESS="14014 Moorpark Street\nSuite 308\nSherman Oaks, CA 91423"
DONATIONS_LEGAL_NAME="Threefold Artists, Inc."
DONATIONS_TAX_ID=85-0567934
DONATIONS_TAX_STATUS="Threefold Artists, Inc. is recognized by the Internal Revenue Service as a 501(c)(3) nonprofit charitable organization."
```

These fields do not enable Stripe. The donation receipt and annual statement use the approved legal name, EIN, mailing address, tax-exempt-status statement, deductible-contribution statement, and no-goods/services disclosure.

After updating the environment, run the normal deployment/cache sequence:

```bash
php artisan migrate --force --no-interaction
php artisan optimize:clear
php artisan config:cache
```

Do not use `migrate:fresh`, `db:wipe`, or a broad rollback on the deployed database.

## 3. Enable the public donation surface

In Filament, open **Settings → Site settings** and turn on donations. Until that setting is enabled, donation routes are intentionally hidden/blocked.

The app creates Stripe Checkout prices dynamically for one-time, monthly, quarterly, and annual donations. You do not need to pre-create Stripe Products or Prices for the current implementation.

## 4. Configure Stripe Customer Portal

The application creates a Billing Portal session only after a donor proves ownership through a single-use email link. In Stripe Dashboard, open **Settings → Billing → Customer portal** and activate/configure the portal before exposing it to live donors.

Enable the capabilities the founder approved:

- payment-method updates;
- subscription cancellation;
- invoice history/receipt access, if available for the account;
- subscription updates only if Stripe's configured products/prices and your fundraising policy permit donors to change amounts or cadence.

The app provides a donor-specific **Pause** control for the approved fixed pause periods. Stripe remains authoritative: a pause becomes visible only after Stripe sends the subscription webhook. The portal's exact options are configured in Stripe, not in this codebase.

## 5. Test end-to-end before live mode

1. Visit `/donate` and submit a test donation using a Stripe test card such as `4242 4242 4242 4242` with any future expiry/CVC.
2. Confirm Stripe delivers `checkout.session.completed` for one-time donations or `invoice.paid` for subscriptions.
3. In Filament → **Finance → Donation Ledger**, verify the paid ledger row and receipt timestamp.
4. Verify the donor received the receipt email and can request a secure link at `/my-donations/access`.
5. For recurring donations, use the donor portal to verify the Stripe Billing Portal link and a permitted pause request. The pause becomes locally visible only after Stripe sends its subscription webhook.
6. In Stripe Dashboard, issue a test refund and verify the ledger shows a linked negative adjustment rather than altering the original paid entry.

For local webhook testing, Stripe CLI can forward signed events to the app:

```bash
stripe login
stripe listen --forward-to http://127.0.0.1:8000/stripe/webhook
```

Use the `whsec_…` printed by `stripe listen` as `STRIPE_WEBHOOK_SECRET` for that local session. Then trigger or complete test events through the Stripe Dashboard/CLI. Never use the CLI's local signing secret on the production server.

## 6. Switch to production

After sandbox verification:

1. Complete the organisation's production legal/tax review and separately approve any optional statement identity/disclosure fields.
2. In Stripe, switch to **Live mode** and create a production webhook endpoint at the same HTTPS route.
3. Replace all three test credentials with their matching live values:

   ```dotenv
   STRIPE_KEY=pk_live_...
   STRIPE_SECRET=sk_live_...
   STRIPE_WEBHOOK_SECRET=whsec_...
   ```

4. Clear/cache configuration, then make a controlled live donation and verify the webhook delivery, ledger record, receipt, and donor portal.

Never mix a test secret key with a live webhook signing secret (or the reverse).

## Operational checks and troubleshooting

- **Checkout says donations are unavailable:** `STRIPE_SECRET` is missing or still `sk_test_placeholder`; verify donations are also enabled in Site settings.
- **Stripe shows webhook delivery failures:** confirm the public HTTPS endpoint is exactly `/stripe/webhook`, the signing secret matches that endpoint/mode, and application logs show no unhandled error.
- **A browser success page appeared but no ledger entry exists:** this is expected until a verified webhook arrives. Inspect the Stripe event delivery first.
- **Receipt or access link was not delivered:** verify the Laravel mailer, sender domain, and queue/worker setup. Stripe payment state can still be correct even if mail delivery fails.
- **Duplicate Stripe deliveries:** safe by design. Stripe event IDs are recorded for idempotency, so a repeated event does not create duplicate ledger records.

Keep Stripe API keys and webhook secrets only in Forge/environment-secret storage. Do not put them in Git, browser JavaScript, screenshots, tickets, or chat.
