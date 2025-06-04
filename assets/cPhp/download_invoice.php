<?php
// portal/assets/cPhp/download_invoice.php
// Serve an invoice PDF. If the file doesn't exist locally, try generating
// it from WooCommerce order data.

require_once __DIR__ . '/master-api.php';
require_once __DIR__ . '/fpdf.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if (!$id) {
    http_response_code(400);
    echo 'Missing invoice id';
    exit;
}

// Look for a previously generated invoice in uploads/invoices
$localFile = __DIR__ . "/../uploads/invoices/invoice-{$id}.pdf";
if (file_exists($localFile)) {
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="invoice-' . $id . '.pdf"');
    readfile($localFile);
    exit;
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

// --- Generate a very simple PDF invoice ---
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(40, 10, 'Invoice #' . $id);
$pdf->Ln(10);
$pdf->SetFont('Arial', '', 12);
$name = trim(($order['billing']['first_name'] ?? '') . ' ' . ($order['billing']['last_name'] ?? ''));
$pdf->Cell(40, 10, 'Customer: ' . $name);
$pdf->Ln(8);
$pdf->Cell(40, 10, 'Total: ' . ($order['total'] ?? '0.00'));
$pdf->Ln(8);
$pdf->Cell(40, 10, 'Date: ' . substr($order['date_created'] ?? '', 0, 10));
$pdfContent = $pdf->Output('S');

header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="invoice-' . $id . '.pdf"');
header('Content-Length: ' . strlen($pdfContent));
echo $pdfContent;
exit;
?>
