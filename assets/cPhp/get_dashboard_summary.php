<?php
// assets/cPhp/get_dashboard_summary.php
require_once(__DIR__ . '/master-api.php'); // loads $store_url, $consumer_key, $consumer_secret

// ── 0) Simple file cache (60s TTL) ──
$cacheFile = sys_get_temp_dir() . '/portal_summary.cache';
$cacheTTL  = 60;
if (file_exists($cacheFile) && time() - filemtime($cacheFile) < $cacheTTL) {
    header('Content-Type: application/json; charset=utf-8');
    echo file_get_contents($cacheFile);
    exit;
}

/**
 * Helper to call the WooCommerce REST API.
 */
function callWooAPI($baseUrl, $endpoint, $ck, $cs) {
    $url = rtrim($baseUrl, '/') . $endpoint;
    $ch  = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Basic ' . base64_encode("$ck:$cs"),
        'Content-Type: application/json'
    ]);
    $resp = curl_exec($ch);
    curl_close($ch);
    return $resp;
}

// ── 1) Fetch all orders ──
$response = callWooAPI(
    $store_url,
    '/wp-json/wc/v3/orders?per_page=100',
    $consumer_key,
    $consumer_secret
);
$orders = json_decode($response, true);

// ── 2) Init summary ──
$summary = [
    'pending'       => 0,
    'in_transit'    => 0,
    'delivered'     => 0,
    'refunded'      => 0,
    'revenue'       => 0.0,
    'top_sellers'   => [],  // will fill in step 5
    'chart_data'    => [],  // last 7 days
    'chart1'        => [],  // last 12 months
    'low_stock'     => 0,
    'notifications' => []   // will fill in step 7
];

// ── 3) Build 7-day & 12-month buckets ──
$today   = new DateTime('today');
$period7 = new DatePeriod((clone $today)->modify('-6 days'), new DateInterval('P1D'), 7);
$chart7  = ['labels'=>[], 'revenue'=>[], 'profit'=>[], 'orders'=>[]];
foreach ($period7 as $dt) {
    $lbl = $dt->format('M j');
    $chart7['labels'][]      = $lbl;
    $chart7['revenue'][$lbl] = 0;
    $chart7['profit'][$lbl]  = 0;
    $chart7['orders'][$lbl]  = 0;
}
$chart12 = ['labels'=>[], 'revenue'=>[]];
for ($i = 11; $i >= 0; $i--) {
    $dt  = new DateTime("first day of -{$i} months");
    $lbl = $dt->format('M Y');
    $chart12['labels'][]      = $lbl;
    $chart12['revenue'][$lbl] = 0;
}

// ── 4) Tally orders & gather per-product stats ──
$salesQty     = [];  // product_id => quantity sold
$salesRevenue = [];  // product_id => total revenue
foreach ($orders as $o) {
    // Status counts
    $st = $o['status'];
    if ($st === 'pending')     $summary['pending']++;
    if ($st === 'in-transit')  $summary['in_transit']++;
    if ($st === 'delivered')   $summary['delivered']++;
    if ($st === 'refunded')    $summary['refunded']++;

    // Monthly revenue
    $tot = (float)$o['total'];
    if (strpos($o['date_created'], date('Y-m')) === 0) {
        $summary['revenue'] += $tot;
    }

    // Per-item accumulation
    foreach ($o['line_items'] as $it) {
        $pid = $it['product_id'];
        $qty = (int)$it['quantity'];
        $rev = (float)$it['total'];
        $salesQty[$pid]     = ($salesQty[$pid]     ?? 0) + $qty;
        $salesRevenue[$pid] = ($salesRevenue[$pid] ?? 0) + $rev;
    }

    // 7-day chart
    $dt   = new DateTime($o['date_created']);
    $l7   = $dt->format('M j');
    if (isset($chart7['revenue'][$l7])) {
        $chart7['revenue'][$l7] += $tot;
        $chart7['profit'][$l7]  += $tot * 0.2;  // example profit
        $chart7['orders'][$l7]++;
    }

    // 12-month chart
    $l12 = $dt->format('M Y');
    if (isset($chart12['revenue'][$l12])) {
        $chart12['revenue'][$l12] += $tot;
    }
}

// ── 5) Build Top-5 Sellers in ONE batch call ──
arsort($salesQty);
$topIds   = array_slice(array_keys($salesQty), 0, 5, true);
if (!empty($topIds)) {
    $idsParam  = implode(',', $topIds);
    $batchResp = callWooAPI(
        $store_url,
        "/wp-json/wc/v3/products?include={$idsParam}&per_page=" . count($topIds),
        $consumer_key,
        $consumer_secret
    );
    $prods = json_decode($batchResp, true);

    foreach ($prods as $prod) {
        $pid     = $prod['id'];
        $summary['top_sellers'][] = [
            'id'       => $pid,
            'name'     => $prod['name'] ?? '–',
            'image'    => $prod['images'][0]['src'] ?? '',
            'category' => $prod['categories'][0]['name'] ?? '-',
            'price'    => $prod['price'] ?? '0',
            'sold'     => $salesQty[$pid]     ?? 0,
            'profit'   => $salesRevenue[$pid] ?? 0,
        ];
    }
}

// ── 6) Count low-stock products ──
$threshold   = 10;
$stockResp   = callWooAPI(
    $store_url,
    '/wp-json/wc/v3/reports/stock',
    $consumer_key,
    $consumer_secret
);
$stockReport = json_decode($stockResp, true);
$lowCount    = 0;
if (is_array($stockReport)) {
    foreach ($stockReport as $item) {
        if (($item['stock_quantity'] ?? 0) <= $threshold) {
            $lowCount++;
        }
    }
}
$summary['low_stock'] = $lowCount;

// ── 7) Build simple notifications ──
$now = new DateTime();
foreach ($orders as $o) {
    if ($o['status'] === 'in-transit') {
        $dt  = new DateTime($o['date_created']);
        $d   = $now->diff($dt)->days;
        if ($d > 5) {
            $summary['notifications'][] = [
                'type'    => 'delay',
                'message' => "Order #{$o['id']} stuck {$d} days",
                'link'    => "/in-transit-orders.php?order_id={$o['id']}"
            ];
        }
    }
    if ($o['status'] === 'refunded') {
        $summary['notifications'][] = [
            'type'    => 'refund',
            'message' => "Refund for Order #{$o['id']}",
            'link'    => "/refunded-orders.php?order_id={$o['id']}"
        ];
    }
}
if ($lowCount > 0) {
    $summary['notifications'][] = [
        'type'    => 'stock',
        'message' => "{$lowCount} products low on stock",
        'link'    => "/products.php?filter=low_stock"
    ];
}

// ── 8) Finalize chart arrays ──
$summary['chart_data'] = [
    'labels'  => $chart7['labels'],
    'revenue' => array_values($chart7['revenue']),
    'profit'  => array_values($chart7['profit']),
    'orders'  => array_values($chart7['orders'])
];
$summary['chart1'] = [
    'labels'  => $chart12['labels'],
    'revenue' => array_values($chart12['revenue'])
];

// ── 9) Cache & output JSON ──
$json = json_encode($summary);
file_put_contents($cacheFile, $json);

header('Content-Type: application/json; charset=utf-8');
echo $json;
exit;
