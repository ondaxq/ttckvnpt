<?php
header('Content-type: text/html; charset=utf-8');

function execPostRequest($url, $data)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($data))
    );
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    // Execute POST
    $result = curl_exec($ch);
    // Close connection
    curl_close($ch);
    return $result;
}

// MoMo API endpoint
$endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";

// MoMo credentials
$partnerCode = 'MOMOBKUN20180529';
$accessKey = 'klm05TvNBzhg7h7j';
$secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';

// Lấy tổng tiền từ tham số GET
$amount = isset($_GET['tong_tien']) ? $_GET['tong_tien'] : "0";
$orderInfo = "Thanh toán đơn hàng qua MoMo";
$orderId = time() . ""; // Unique order ID
$requestId = time() . "";
$requestType = "captureWallet";

// Chỉnh sửa đường dẫn redirectUrl và ipnUrl nếu cần
$redirectUrl = "http://localhost/thuchanhvnpt/hoan-thanh-dat-hang.php"; 
$ipnUrl = "http://localhost/thuchanhvnpt/ipn_momo.php"; 

$extraData = "";

// Create raw hash string for HMAC SHA256
$rawHash = "accessKey=" . $accessKey .
    "&amount=" . $amount .
    "&extraData=" . $extraData .
    "&ipnUrl=" . $ipnUrl .
    "&orderId=" . $orderId .
    "&orderInfo=" . $orderInfo .
    "&partnerCode=" . $partnerCode .
    "&redirectUrl=" . $redirectUrl .
    "&requestId=" . $requestId .
    "&requestType=" . $requestType;

// Generate signature
$signature = hash_hmac("sha256", $rawHash, $secretKey);

// Prepare request data
$data = array(
    'partnerCode' => $partnerCode,
    'partnerName' => "Test",
    "storeId" => "MomoTestStore",
    'requestId' => $requestId,
    'amount' => $amount,
    'orderId' => $orderId,
    'orderInfo' => $orderInfo,
    'redirectUrl' => $redirectUrl,
    'ipnUrl' => $ipnUrl,
    'lang' => 'vi',
    'extraData' => $extraData,
    'requestType' => $requestType,
    'signature' => $signature
);

// Execute API call
$result = execPostRequest($endpoint, json_encode($data));
$jsonResult = json_decode($result, true); // Decode JSON response

// Kiểm tra kết quả
if (!empty($jsonResult['payUrl'])) {
    header('Location: ' . $jsonResult['payUrl']);
} else {
    echo "Lỗi khi tạo yêu cầu thanh toán: " . json_encode($jsonResult, JSON_PRETTY_PRINT);
}
?>
