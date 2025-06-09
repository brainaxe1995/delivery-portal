<?php
require_once __DIR__ . '/config/bootstrap.php';
require_once __DIR__ . '/master-api.php';

$per_page = 10;
$period   = isset($_GET['period']) ? $_GET['period'] : 'yearly';
$endpoint = $period === 'monthly' ? '/wp-json/wc/v3/reports/top_sellers?period=month&per_page=' : '/wp-json/wc/v3/reports/top_sellers?per_page=';
$url = rtrim($store_url,'/').$endpoint.$per_page;
$ch = curl_init($url);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch,CURLOPT_USERPWD, "$consumer_key:$consumer_secret");
$resp = curl_exec($ch);
curl_close($ch);
header('Content-Type: application/json; charset=utf-8');
echo $resp ?: '[]';
