<?php
// disable error
error_reporting(0);

// استلم clearkey كامل من GET
$clearkey = $_GET["clearkey"] ?? '';

// validation
if (empty($clearkey) || strpos($clearkey, ":") === false) {
    http_response_code(503);
    header("Content-Type: application/json");
    $errorjson = array(
        "Status" => "503",
        "Content" => "Validation Failed!",
        "Reason" => "Did not provide ClearKey in format keyid:key"
    );
    echo json_encode($errorjson);
    exit;
}

// نفصل الـ keyid والـ key
list($hex, $hex2) = explode(":", $clearkey);

// تحويل Key ID لـ Base64
$bin = hex2bin($hex);
$finalkeyid64 = str_replace('=', '', base64_encode($bin));

// تحويل Key لـ Base64
$bin2 = hex2bin($hex2);
$finalkey64 = str_replace('=', '', base64_encode($bin2));

// validation إضافي
if (empty($finalkeyid64) || empty($finalkey64)){
    http_response_code(503);
    header("Content-Type: application/json");
    $errorjson = array(
        "Status" => "503",
        "Content" => "Validation Failed!",
        "Reason" => "Key ID or Key isn't complete"
    );
    echo json_encode($errorjson);
    exit;
}

// create JSON for keys
$keys[] = array("kty" => "oct", "k" => $finalkey64, "kid" => $finalkeyid64);

// encode JSON
$license = array("keys" => $keys, "type" => "temporary");

// output JSON
header("Content-Type: application/json");
echo json_encode($license);
?>