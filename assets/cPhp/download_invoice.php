<?php
// portal/assets/cPhp/download_invoice.php
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$file = __DIR__ . '/../pdf/invoice_sample.pdf';
if (!file_exists($file)) {
    http_response_code(404);
    echo 'File not found';
    exit;
}
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="invoice-' . $id . '.pdf"');
readfile($file);
exit;
?>
