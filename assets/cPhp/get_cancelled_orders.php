<?php
require_once(__DIR__ . '/master-api.php');

function callWooAPI($baseUrl, $endpoint, $method, $ck, $cs, $bodyData = null) {
    $url = rtrim($baseUrl, '/') . $endpoint;
    $ch = curl_init($url);

    // IMPORTANT: we instruct cURL to capture headers
    curl_setopt($ch, CURLOPT_HEADER, 1); // Return headers + body
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $headers = [
        'Authorization: Basic ' . base64_encode($ck . ':' . $cs),
        'Content-Type: application/json'
    ];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    if (in_array(strtoupper($method), ['POST','PUT','PATCH'], true)) {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
        if (is_array($bodyData)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($bodyData));
        }
    } elseif (strtoupper($method) === 'DELETE') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
    }

    $response = curl_exec($ch);

    $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $rawHeaders = substr($response, 0, $headerSize);  // raw header
    $body       = substr($response, $headerSize);     // actual JSON body

    if (curl_errno($ch)) {
        $error_msg = curl_error($ch);
        curl_close($ch);
        echo json_encode(['error' => 'cURL error: ' . $error_msg]);
        exit;
    }
    curl_close($ch);

    // We pass the headers + body, so the caller can parse them
    return [
        'headers' => $rawHeaders,
        'body'    => $body
    ];
}

// Get pagination params
$page      = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page  = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 20;
$order_id  = isset($_GET['order_id']) ? trim($_GET['order_id']) : '';

$endpoint = "/wp-json/wc/v3/orders?status=cancelled&page={$page}&per_page={$per_page}";
if ($order_id !== '') {
    $endpoint .= '&search=' . urlencode($order_id);
}

$result = callWooAPI($store_url, $endpoint, 'GET', $consumer_key, $consumer_secret);

// We split out the headers from the body
$rawHeaders = $result['headers'];
$bodyJson   = $result['body'];

// Now we want to forward the `X-WP-Total` and `X-WP-TotalPages` to the client
// Let's parse them from $rawHeaders
$total     = 0;
$totalPages= 1;

if (preg_match('/^X-WP-Total:\s*(\d+)/mi', $rawHeaders, $matches)) {
    $total = (int)$matches[1];
}
if (preg_match('/^X-WP-TotalPages:\s*(\d+)/mi', $rawHeaders, $matches2)) {
    $totalPages = (int)$matches2[1];
}

// We set them as custom headers in our response
header('Content-Type: application/json; charset=utf-8');
header("X-My-Total: {$total}");
header("X-My-TotalPages: {$totalPages}");

// Now echo the actual JSON body from WooCommerce
echo $bodyJson;
exit;
