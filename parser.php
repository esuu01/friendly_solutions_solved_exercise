<?php

$file_path = "./wo_for_parse.html";

if (!file_exists($file_path)) return null;

$file = file_get_contents($file_path);

$file_data = new DOMDocument();

$file_data->loadHTML($file);

$result = [];

$result["tracking_number"] = $file_data->getElementById("wo_number")->textContent;
$result["po_number"] = $file_data->getElementById("po_number")->textContent;

// Get date and change to required format
$scheduled_date = $file_data->getElementById("scheduled_date")->textContent;
$scheduled_date = str_replace(array("\r", "\n"), '', $scheduled_date);
$scheduled_date = date("Y-m-d H:i", strtotime($scheduled_date));

$result["date"] = $scheduled_date;

$result["customer"] = $file_data->getElementById("wo_number")->textContent;
$result["trade"] = $file_data->getElementById("trade")->textContent;

// Get NTE, remove useless characters and change to float number
$result["nte"] = $file_data->getElementById("nte")->textContent;
$result["nte"] = floatval(preg_replace('/[^.0-9\-]/', '', $result["nte"]));

$result["store_id"] = $file_data->getElementById("location_name")->textContent;
$result["city"] = $file_data->getElementById("store_id")->textContent;

// Location address
$location = $file_data->getElementById("location_address")->textContent;
$location = explode("\n", trim($location));

$result["address"]["street"] = preg_replace("/[^ A-z\-]/", "", $location[0]);
$result["address"]["number"] = intval(preg_replace("/[^0-9\-]/", "", $location[0]));

// State
$result['state'] = str_replace($result["city"], "", $location[1]);
$result['state'] = preg_replace("/[^A-z\-]/", "", $result['state']);

// Zip code
$result['zip_code'] = str_replace(array($result["city"], $result['state'], " "), "", $location[1]);

// Get phone number, remove useless characters and change to float number
$result["phone_number"] = $file_data->getElementById("location_phone")->textContent;
$result["phone_number"] = floatval(str_replace("-", "", $result["phone_number"]));

$csv = fopen("./output.csv", "w+");

foreach ($result as $key => $value) {
    fputcsv($csv, array($key, $value));
}

fclose($csv);