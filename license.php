<?php
header('Content-Type: application/json');

$kid_hex = $_GET['kid'] ?? '3d04975236a44f62857d181597705ee6';
$key_hex = $_GET['key'] ?? '362133e9cb13189ad4fe095ced216f60';

function hexToBase64Url($hex) {
    $bin = hex2bin($hex);
    $b64 = base64_encode($bin);
    $b64url = strtr(rtrim($b64, '='), '+/', '-_');
    return $b64url;
}

if (strlen($kid_hex) !== 32 || strlen($key_hex) !== 32) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid KID or KEY']);
    exit;
}

$kid_b64 = hexToBase64Url($kid_hex);
$key_b64 = hexToBase64Url($key_hex);

echo json_encode([
    'keys' => [
        ['kty'=>'oct','kid'=>$kid_b64,'k'=>$key_b64]
    ],
    'type'=>'temporary'
]);