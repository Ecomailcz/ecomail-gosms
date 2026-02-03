# GoSMS API Usage Examples

Scripts for testing API connection and sending messages via GoSMS API. Add your own keys.

The client instance is resolved through Laravel’s container (Orchestra Testbench): `ApplicationFactory` creates a minimal Laravel app, registers the package’s service provider and config from `.env`, and the scripts use the authenticated client from `app('gosms.authenticated')`.

## Setup

1. Copy the config template:
   ```bash
   cp .env.example .env
   ```
2. In `examples/.env` (or project root `.env`) set:
   - `GOSMS_CLIENT_ID` – GoSMS client ID
   - `GOSMS_CLIENT_SECRET` – GoSMS client secret
   - `GOSMS_CHANNEL_ID` – channel number (integer) for sending

Scripts load `.env` from the `examples/` folder or from the project root.

## Running

From the project root (where `vendor/` is):

```bash
# Verify connection (authentication)
php examples/authenticate.php

# Send a single SMS
php examples/send-single-message.php "+420123456789" "Message text"

# Bulk send (same text to multiple numbers)
php examples/send-bulk-messages.php "+420123456789" "+420987654321"

# Bulk send, poll until status "sent", then print message detail from response
php examples/bulk-send-and-wait-for-sent.php "+420123456789"
```

Use phone numbers in international format including country code (e.g. `+420123456789`).
