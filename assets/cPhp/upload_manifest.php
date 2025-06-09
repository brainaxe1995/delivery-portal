<?php
require_once __DIR__ . '/config/bootstrap.php';
// assets/cPhp/upload_manifest.php

require_once(__DIR__ . '/master-api.php');
require_once(__DIR__ . '/server-config.php');

if (empty($_FILES['manifest']['tmp_name'])) {
    http_response_code(400);
    echo json_encode(['error'=>'No file uploaded']);
    exit;
}

// Read CSV (expect header row: order_id,tracking_number,provider,eta)
$rows = array_map('str_getcsv', file($_FILES['manifest']['tmp_name']));
$header = array_shift($rows);

foreach ($rows as $row) {
    $data = array_combine($header, $row);
    $orderId = (int)($data['order_id'] ?? 0);
    if (!$orderId) continue;

    $meta = [];
    if (!empty($data['tracking_number'])) {
        $meta[] = ['key'=>'_wot_tracking_number','value'=>$data['tracking_number']];
    }
    if (!empty($data['provider'])) {
        $meta[] = ['key'=>'_wot_tracking_carrier','value'=>$data['provider']];
    }
    if (!empty($data['eta'])) {
        $meta[] = ['key'=>'_wot_eta','value'=>$data['eta']];
    }
    if (empty($meta)) continue;

    $endpoint = "/wp-json/wc/v3/orders/{$orderId}";
    $payload  = json_encode(['meta_data'=>$meta]);
    $ch = curl_init(rtrim($store_url,'/').$endpoint);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, "$consumer_key:$consumer_secret");
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    $resp   = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($status < 200 || $status >= 300) {
        http_response_code($status ?: 500);
        echo json_encode([
            'error'   => 'WooCommerce API error',
            'details' => $resp,
            'order_id'=> $orderId
        ]);
        exit;
    }
}

echo json_encode(['success'=>true]);
exit;
