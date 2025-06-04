<?php
// portal/assets/cPhp/download_invoice.php
// Serve an invoice PDF. If the file doesn't exist locally, try generating
// it from WooCommerce order data.

require_once __DIR__ . '/master-api.php';
// Use TCPDF for generating PDFs from HTML templates
require_once '/usr/share/php/tcpdf/tcpdf.php';

function output_pdf($html, $localFile, $id){
    $pdf = new TCPDF();
    $pdf->SetCreator('Delivery Portal');
    $pdf->SetAuthor('Delivery Portal');
    $pdf->SetTitle('Invoice #' . $id);
    $pdf->SetMargins(15, 15, 15);
    $pdf->AddPage();
    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Output($localFile, 'F');

    $pdfContent = file_get_contents($localFile);
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="invoice-' . $id . '.pdf"');
    header('Content-Length: ' . strlen($pdfContent));
    echo $pdfContent;
}

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if (!$id) {
    http_response_code(400);
    echo 'Missing invoice id';
    exit;
}

// Look for a previously generated invoice in uploads/invoices
$localFile = __DIR__ . "/../uploads/invoices/invoice-{$id}.pdf";
// Create folder if it does not exist
if (!is_dir(dirname($localFile))) {
    mkdir(dirname($localFile), 0777, true);
}
if (file_exists($localFile)) {
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="invoice-' . $id . '.pdf"');
    readfile($localFile);
    exit;
}

// Try to load invoice from JSON store
$jsonFile = __DIR__ . '/../data/invoices.json';
if (file_exists($jsonFile)) {
    $invoices = json_decode(file_get_contents($jsonFile), true);
    if (is_array($invoices)) {
        foreach ($invoices as $inv) {
            if ((int)($inv['id'] ?? 0) === $id && !empty($inv['items']) && is_array($inv['items'])) {
                $itemsHtml = '';
                foreach ($inv['items'] as $it) {
                    $itemsHtml .= '<tr>' .
                        '<td>' . htmlspecialchars($it['orderNumber'] ?? '') . '</td>' .
                        '<td>' . htmlspecialchars($it['trackingCode'] ?? '') . '</td>' .
                        '<td>' . htmlspecialchars($it['shippingProof'] ?? '') . '</td>' .
                        '<td>' . htmlspecialchars($it['customerName'] ?? '') . '</td>' .
                        '<td>' . htmlspecialchars($it['address'] ?? '') . '</td>' .
                        '<td>' . htmlspecialchars($it['countryName'] ?? '') . '</td>' .
                        '<td>' . htmlspecialchars($it['productName'] ?? '') . '</td>' .
                        '<td>' . htmlspecialchars($it['stripe'] ?? '') . '</td>' .
                        '<td class="amount">' . htmlspecialchars($it['productCost'] ?? '') . '</td>' .
                        '<td class="amount">' . htmlspecialchars($it['shippingCost'] ?? '') . '</td>' .
                        '<td class="amount">' . htmlspecialchars($it['totalCost'] ?? '') . '</td>' .
                        '<td>' . htmlspecialchars($it['note'] ?? '') . '</td>' .
                        '</tr>';
                }

                $html = <<<HTML
<html>
<head>
<style>
body{font-family: DejaVu Sans, sans-serif; font-size:12px; color:#333;}
table{width:100%; border-collapse:collapse;}
th,td{border:1px solid #ccc; padding:4px;}
th{background:#f0f0f0;}
.amount{text-align:right;}
</style>
</head>
<body>
<h2>Invoice #$id</h2>
<table>
<thead>
<tr>
  <th>Order Number</th>
  <th>Tracking Code</th>
  <th>Shipping Proof</th>
  <th>Customer Name</th>
  <th>Address</th>
  <th>Country Name</th>
  <th>Product Name</th>
  <th>Stripe</th>
  <th>Product Cost</th>
  <th>Shipping Cost</th>
  <th>Total Cost</th>
  <th>Note</th>
</tr>
</thead>
<tbody>
$itemsHtml
</tbody>
</table>
</body>
</html>
HTML;

                output_pdf($html, $localFile, $id);
                exit;
            }
        }
    }
}

// --- Fetch order details from WooCommerce ---
$url = rtrim($store_url, '/') . "/wp-json/wc/v3/orders/{$id}";
$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_USERPWD        => "$consumer_key:$consumer_secret",
    CURLOPT_HTTPHEADER     => ['Content-Type: application/json']
]);
$response = curl_exec($ch);
$code     = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($code !== 200 || $response === false) {
    error_log("WooCommerce invoice request returned HTTP $code: $response");

    if (in_array($code, [401, 403])) {
        http_response_code(500);
        echo 'WooCommerce API credentials may be misconfigured.';
        exit;
    }

    if ($code === 404) {
        http_response_code(404);
        echo 'Order ID ' . $id . ' not found.';
        exit;
    }

    http_response_code(500);
    echo 'Failed to retrieve invoice.';
    exit;
}

$order = json_decode($response, true);
if (!$order) {
    http_response_code(500);
    echo 'Failed to decode order data';
    exit;
}

// --- Build HTML invoice template ---
$name   = trim(($order['billing']['first_name'] ?? '') . ' ' . ($order['billing']['last_name'] ?? ''));
$billing = $order['billing'] ?? [];
$currency = $order['currency'] ?? '$';
$itemsHtml = '';
foreach ($order['line_items'] ?? [] as $it) {
    $p = number_format((float)($it['price'] ?? 0), 2);
    $t = number_format((float)($it['total'] ?? 0), 2);
    $itemsHtml .= '<tr>' .
        '<td>' . htmlspecialchars($it['name']) . '</td>' .
        '<td class="qty">' . (int)$it['quantity'] . '</td>' .
        '<td class="amount">' . $currency . ' ' . $p . '</td>' .
        '<td class="amount">' . $currency . ' ' . $t . '</td>' .
        '</tr>';
}

$html = <<<HTML
<html>
<head>
<style>
body { font-family: DejaVu Sans, sans-serif; color: #333; font-size: 12px; }
.invoice-header { display: flex; justify-content: space-between; border-bottom: 1px solid #ccc; margin-bottom: 20px; }
.invoice-header h1 { font-size: 20px; margin: 0; }
.address { text-align: right; }
table { width: 100%; border-collapse: collapse; margin-top: 20px; }
th, td { border: 1px solid #ccc; padding: 8px; }
th { background: #f0f0f0; }
.amount, .qty { text-align: right; }
</style>
</head>
<body>
<div class="invoice-header">
  <h1>Invoice #$id</h1>
  <div class="address">
    <strong>$name</strong><br/>
    {$billing['address_1'] ?? ''}<br/>
    {$billing['city'] ?? ''} {$billing['postcode'] ?? ''}
  </div>
</div>
<table class="invoice-table">
  <thead>
    <tr><th>Item</th><th class="qty">Qty</th><th class="amount">Price</th><th class="amount">Total</th></tr>
  </thead>
  <tbody>
    $itemsHtml
  </tbody>
  <tfoot>
    <tr><td colspan="3" class="amount"><strong>Total</strong></td><td class="amount">$currency {$order['total']}</td></tr>
  </tfoot>
</table>
</body>
</html>
HTML;

// --- Generate PDF using TCPDF ---
output_pdf($html, $localFile, $id);
exit;
?>
