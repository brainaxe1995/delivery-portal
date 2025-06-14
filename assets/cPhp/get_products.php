<?php
require_once __DIR__ . '/config/bootstrap.php';
require_once __DIR__ . '/master-api.php';  // loads $store_url, $consumer_key, $consumer_secret

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
        echo json_encode(['error'=>curl_error($ch)]);
        exit;
    }
    $hsize  = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $header = substr($raw, 0, $hsize);
    $body   = substr($raw, $hsize);
    curl_close($ch);
    if (preg_match('/^X-WP-TotalPages:\s*(\d+)/mi', $header, $m)) {
        header('X-My-TotalPages: ' . $m[1]);
    }
    return $body;
}

$page     = isset($_GET['page'])     ? (int)$_GET['page']     : 1;
$per_page = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 20;
$status   = $_GET['status'] ?? '';

$endpoint = "/wp-json/wc/v3/products?page={$page}&per_page={$per_page}&context=edit";
$allowed  = ['instock', 'outofstock', 'discontinued'];
if ($status && in_array($status, $allowed, true)) {
    $endpoint .= "&stock_status={$status}";
}

$body     = callWooAPI($store_url, $endpoint, $consumer_key, $consumer_secret);
$products = json_decode($body, true);
if (!is_array($products)) {
    header('Content-Type: application/json; charset=utf-8');
    echo $body;
    exit;
}

$etaFile = __DIR__ . '/../data/restock_eta.json';
$etaData = file_exists($etaFile) ? json_decode(file_get_contents($etaFile), true) : [];
$etaMap  = [];
foreach ($etaData as $e) {
    if (isset($e['id'])) {
        $etaMap[$e['id']] = $e['restock_eta'] ?? null;
    }
}

foreach ($products as &$p) {
    $p['moq'] = '';
    if (!empty($p['meta_data']) && is_array($p['meta_data'])) {
        foreach ($p['meta_data'] as $m) {
            if ($m['key'] === 'moq') {
                $p['moq'] = $m['value'];
                break;
            }
        }
    }

    $p['restock_eta'] = $etaMap[$p['id']] ?? null;

    if (($p['type'] ?? '') === 'variable' && !empty($p['attributes'])) {
        $p['variant_attributes'] = [array_map(function($attr){
            $opts = $attr['options'] ?? [];
            return [
                'name'   => $attr['name'] ?? '',
                'option' => implode(', ', $opts)
            ];
        }, $p['attributes'])];
    }
}
unset($p);

header('Content-Type: application/json; charset=utf-8');

echo json_encode($products);
exit;
