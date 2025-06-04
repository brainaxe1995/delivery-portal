<?php
// get_refunded_orders.php
//

// Fetches WooCommerce orders with status=refunded ("Refunded Orders")
// and re-emits the X-My-TotalPages header so JS can paginate correctly.

require_once(__DIR__ . '/master-api.php'); // loads $store_url, $consumer_key, $consumer_secret

function callWooAPI($baseUrl, $endpoint, $ck, $cs) {
    $url = rtrim($baseUrl, '/') . $endpoint;
    $ch  = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER,         true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
      'Authorization: Basic ' . base64_encode("$ck:$cs"),
      'Content-Type: application/json',
    ]);

    $raw = curl_exec($ch);

    if (curl_errno($ch)) {
        http_response_code(500);
        echo json_encode(['error' => curl_error($ch)]);
        exit;
    }

    $hsize  = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $header = substr($raw, 0, $hsize);
    $body   = substr($raw, $hsize);
    curl_close($ch);

    // ✅ Extract and re-emit the total pages header
    if (preg_match('/^X-WP-TotalPages:\s*(\d+)/mi', $header, $matches)) {
        header('X-My-TotalPages: ' . $matches[1]);
    }

    return $body;
}

// Pagination inputs
$page      = isset($_GET['page'])     ? (int) $_GET['page']     : 1;
$per_page  = isset($_GET['per_page']) ? (int) $_GET['per_page'] : 20;
$order_id  = isset($_GET['order_id']) ? trim($_GET['order_id']) : '';

// API call
$endpoint = "/wp-json/wc/v3/orders?status=refunded&page={$page}&per_page={$per_page}";
if ($order_id !== '') {
    $endpoint .= '&search=' . urlencode($order_id);
}

header('Content-Type: application/json; charset=utf-8');
echo callWooAPI(
    $store_url,
    $endpoint,
    $consumer_key,
    $consumer_secret
);
exit;
?>
