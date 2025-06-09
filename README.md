

## Environment Configuration

The PHP scripts under `assets/cPhp` expect the following environment variables to be present.
`master-api.php` will terminate early if any of the first three variables are missing:

- `WOOCOMMERCE_CK` – WooCommerce consumer key
- `WOOCOMMERCE_CS` – WooCommerce consumer secret
- `STORE_URL` – URL of your WooCommerce store
- `TRACK17_APIKEY` – API key for communicating with [17TRACK](https://www.17track.net/)
- `ALERT_EMAIL`  – optional address to email when a shipment delay is detected

Create a `.env` file in the project root containing these variables and export them in your server environment before running the application. If `WOOCOMMERCE_CK`, `WOOCOMMERCE_CS`, or `STORE_URL` are unset you will see the error message `Environment variables WOOCOMMERCE_CK, WOOCOMMERCE_CS and STORE_URL must be set.` when a PHP page loads:

```bash
WOOCOMMERCE_CK=your_key
WOOCOMMERCE_CS=your_secret
STORE_URL=https://example.com
TRACK17_APIKEY=your_api_key
```

## Troubleshooting

If a PHP script exits with the message:

```
Environment variables WOOCOMMERCE_CK, WOOCOMMERCE_CS and STORE_URL must be set.
```

the application could not locate your WooCommerce credentials. Copy `.env.example` to `.env` and fill in your keys:

```bash
cp .env.example .env
# then edit .env
```

Reload your server after exporting the variables so `master-api.php` can read them.

## PHP Version & Local Server

This project requires **PHP 8.0 or later**. You can quickly serve the PHP files
locally using the built‑in development server:

```bash
php -S localhost:8000
```

Then open `http://localhost:8000/index.php` in your browser.

## Running Tests

The test suite is powered by Jest and requires **Node.js 18 or newer**. Be sure to run `npm install` first so all dev dependencies are present. Running `npm test` without installing packages will fail.

```bash
npm install
npm test
```

## Shipment Update Endpoints

Two PHP scripts handle shipment updates:

- `assets/cPhp/upload_manifest.php` – Upload a CSV manifest under the form field
  `manifest` to bulk update orders with tracking numbers, providers and ETAs.
- `assets/cPhp/update_single_shipment.php` – Accepts a JSON body containing an
  `order_id` plus optional `provider`, `tracking_no` and `eta` values. It updates
  a single WooCommerce order and is used when editing rows in `shipments.js`.

## Migrating Refund Requests Data

Earlier versions of this portal read refund requests from
`assets/uploads/refund_requests.json`. Refund data is now pulled directly from
WooCommerce using the REST API. The JSON file is no longer required and can be
deleted. Run the helper script below or remove the file manually:

```bash
scripts/remove-refund-json.sh
```
## Invoice Generation

Invoices are stored in `assets/data/invoices.json` and served from the
`invoices.php` page. When you click the **PDF** button an invoice is
fetched via `assets/cPhp/download_invoice.php`. This script uses the
TCPDF library to render HTML into a PDF file. If a PDF has not been
created yet, the script pulls order details from WooCommerce and then
saves the generated PDF under `assets/uploads/invoices/`.

Because it talks to WooCommerce, `download_invoice.php` requires the
`WOOCOMMERCE_CK`, `WOOCOMMERCE_CS` and `STORE_URL` variables defined in
your `.env` file just like other API scripts.

### Creating a Test Invoice

To generate a non‑blank PDF you need an invoice entry with at least one
item. You can POST JSON data to `assets/cPhp/create_invoice.php`:

```bash
curl -X POST http://localhost:8000/assets/cPhp/create_invoice.php \
  -H 'Content-Type: application/json' \
  -d '{"items":[{"orderNumber":"TEST-1","productName":"Demo","totalCost":9.99,"customerName":"Alice"}]}'
```

Alternatively edit `assets/data/invoices.json` manually and add an
`items` array to one of the example invoices. After saving, download the
PDF with:

```bash
php assets/cPhp/download_invoice.php?id=1 > invoice-1.pdf
```

## Portal Settings

General configuration values are stored in `assets/data/settings.json` and can be edited through `settings.php`. The form lets you update the shipping API key, WooCommerce credentials, interface language, time zone and currency. Changes are saved to the JSON file via AJAX calls to `assets/cPhp/update_settings.php`.
