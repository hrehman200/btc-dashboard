<?php
require_once __DIR__ . '/vendor/autoload.php';

DB::$user = 'root';
DB::$password = '';
DB::$dbName = 'btc-dashboard';

$hourly = DB::query("SELECT * FROM `btc_prices` group by period ORDER BY period");
// $weekly = DB::query("SELECT * FROM `btc_prices` group by DAY(period)");
$daily = DB::query("SELECT * FROM `btc_prices`  group by DATE(period) ORDER BY period");
$monthly = DB::query("SELECT * FROM `btc_prices`  GROUP BY DATE_FORMAT(period,'%Y-%m') ORDER BY period");
$yearly = DB::query("SELECT * FROM `btc_prices`  group by YEAR(period) ORDER BY period");

$hourly_data = [];
foreach ($hourly as $h) {
    $hourly_data[] = ['time' => strtotime($h['period']), 'value' => $h['price']];
}

$daily_data = [];
foreach ($daily as $h) {
    $daily_data[] = ['time' => strtotime($h['period']), 'value' => $h['price']];
}

$monthly_data = [];
foreach ($monthly as $h) {
    $monthly_data[] = ['time' => strtotime($h['period']), 'value' => $h['price']];
}

$yearly_data = [];
foreach ($yearly as $h) {
    $yearly_data[] = ['time' => strtotime($h['period']), 'value' => $h['price']];
}