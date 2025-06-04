# PlainAdmin - Free Vanilla JS Bootstrap 5 Admin Dashboard Template

PlainAdmin is a free and open-source **Vanilla JS admin dashboard template** based on Bootstrap 5 that comes with 5 unique dashboard variations, 300+ essential dashboard components, pages, UI elements, charts, graphs, application UI pages, and more. to provide everything you need to build a data-rich backend or admin panel for almost any sort of web app.

[![plainadmindemo](https://github.com/PlainAdmin/plain-free-bootstrap-admin-template/blob/main/plainadmin.jpg)](https://demo.plainadmin.com/)


### [🚀 View Demo](https://demo.plainadmin.com/)

### [✨ Visit Website](https://plainadmin.com/)

### [⬇️ Download Now](https://plainadmin.com/download)

### [📄 Documentation/Installation](https://plainadmin.com/docs/)

### [⚡ PRO Version](https://plainadmin.com/pricing)


It comes with a minimal UI design that suits almost any kind of web project and offers the best possible user experience. PlainAdmin comes with SCSS files, an organized codebase, gulp support for efficient workflow, rich documentation, and everything else that you can expect from a modern admin template.

PlainAdmin is built with vanilla Javascript (no jQuery), Bootstrap 5 and the simplest possible way to make it easy to maintain and easy to port your back-end projects.

### [👉 Check out our Admin Template for Tailwind CSS](https://tailadmin.com)

If you are looking for a high-quality free admin template that comes with all essential dashboard components and features then, PlainAdmin is the perfect choice for you.

## [📄 Documentation](https://plainadmin.com/docs/)
- [Installation](https://plainadmin.com/docs/#installation)
- [Quick Start](https://plainadmin.com/docs/#quick-start)
- [Layout and Theme](https://plainadmin.com/docs/#layout-theme)
- [Colors](https://plainadmin.com/docs/#colors)
- [Alerts](https://plainadmin.com/docs/#alerts)
- [Buttons](https://plainadmin.com/docs/#buttons)
- [Cards](https://plainadmin.com/docs/#cards)
- [Tabs](https://plainadmin.com/docs/#tabs)
- [Forms](https://plainadmin.com/docs/#forms)
- [Icons](https://plainadmin.com/docs/#icons)
- [Tables](https://plainadmin.com/docs/#tables)
- [Credits](https://plainadmin.com/docs/#credits)

### Update Logs - 2.0 :
- Rebranded the entire template.
- Updated the style guide
- The sidebar menu icons have been updated.
- The overall design of the sidebar has been enhanced.
- The header menu icons have been updated.
- The design of the header dropdown has been updated.
- Additional buttons have been added to the Button Page.
- Updated essential dependencies
- The charts code has been adjusted to accommodate the updated version.
- UX and UI enhancements

### Update Logs - 1.2 :
- Added Kanban (App) [PRO]
- Added File Manager (App) [PRO]
- Dependencies updated
- FullCalender updated

### Update Logs - 1.1.2 :
- FullCalender updated
- Dependencies updates

### Update Logs - 1.1.1 :
- Updated to v5.1.3 (Latest)
- Fixed minor bugs
- Changed primary color
- Multiple sidebar variations
- Improved UX
- Optimized codebase

### Update Logs - 1.1 :
- Updated to Bootstrap 5.1.1
- Fixed minor bugs
- Enhanced the UI and Improved Typography

## Environment Configuration

The PHP scripts under `assets/cPhp` expect the following environment variables to be present.
`master-api.php` will terminate early if any of the first three variables are missing:

- `WOOCOMMERCE_CK` – WooCommerce consumer key
- `WOOCOMMERCE_CS` – WooCommerce consumer secret
- `STORE_URL` – URL of your WooCommerce store
- `TRACK17_APIKEY` – API key for communicating with [17TRACK](https://www.17track.net/)

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

Install dependencies and run the test suite. Requires Node.js >=18:

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
