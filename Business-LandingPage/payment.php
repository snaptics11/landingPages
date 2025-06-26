<?php
// PhonePe API Configuration for Testing
$merchantId = 'TEST-M235QRLKY6NAI_25062';
$apiKey = 'ZjE5NTc1NTMtMjVlNS00MDNkLWJkMjUtNDgxZmE0ODQ1YzBh';
$merchantTransactionId = 'MT' . time(); // Unique transaction ID
$amount = 1000; // Amount in paise (e.g., 1000 = ₹10)
$redirectUrl = 'https://yourwebsite.com/redirect_url';
$callbackUrl = 'https://yourwebsite.com/callback_url';

// API Endpoint for Testing
$apiUrl = 'https://api.phonepe.com/apis/pg-sandbox/pg/v1/pay';

// Prepare the request data
$data = [
    'merchantId' => $merchantId,
    'merchantTransactionId' => $merchantTransactionId,
    'amount' => $amount,
    'redirectUrl' => $redirectUrl,
    'redirectMode' => 'POST',
    'callbackUrl' => $callbackUrl,
    'mobileNumber' => '9999999999', // Customer's mobile number
    'paymentInstrument' => [
        'type' => 'PAY_PAGE'
    ]
];

// Convert data to JSON
$jsonData = json_encode($data);

// Initialize cURL session
$ch = curl_init($apiUrl);

// Set cURL options
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'X-VERIFY: ' . hash('sha256', $jsonData . $apiKey),
    'accept: application/json'
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

// Execute cURL session and close it
$response = curl_exec($ch);
curl_close($ch);

// Decode the response
$responseData = json_decode($response, true);

// Check if the request was successful
if (isset($responseData['success']) && $responseData['success']) {
    // Redirect the user to the payment URL
    header('Location: ' . $responseData['data']['instrumentResponse']['redirectInfo']['url']);
    exit;
} else {
    // Handle error
    echo "Error: " . $responseData['message'];
}
?>
