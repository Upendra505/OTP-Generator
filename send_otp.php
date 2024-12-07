<?php
// send_otp.php

// Start session to store OTP
session_start();

// Function to send OTP using Fast2SMS
function sendOtp($phone, $otp)
{
    $apiKey = 'ofq9MSlEeBU0hWNXiy4wLKCFdAgPsJmRupbat6V1O2ZrHk7jYDLQAJ3eo7aOKDxfMCybYnt9vsdwqmpk'; // Replace with your Fast2SMS API Key
    $route = 'otp'; // Use "otp" route for sending OTP
    $flash = 0; // Optional: Set flash to 0 by default

    // Prepare the data to send via POST
    $postData = array(
        'authorization' => $apiKey,
        'variables_values' => $otp,
        'route' => $route,
        'numbers' => $phone,
        'flash' => $flash
    );

    // Fast2SMS API URL
    $url = "https://www.fast2sms.com/dev/bulkV2";

    // Initialize cURL
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => http_build_query($postData),
        CURLOPT_HTTPHEADER => array(
            'authorization: ' . $apiKey,
            'Content-Type: application/x-www-form-urlencoded'
        ),
    ));

    // Execute the request and get the response
    $response = curl_exec($curl);
    curl_close($curl);

    return $response;
}

// Generate a 6-digit OTP and store it in the session
if (isset($_POST['phone'])) {
    $phone = $_POST['phone'];
    $otp = rand(1000, 9999); // Generate random 6-digit OTP
    $_SESSION['otp'] = $otp;

    // Call the function to send OTP
    $response = sendOtp($phone, $otp);

    // Decode the response (assuming it's in JSON format)
    $result = json_decode($response, true);

    if ($result['return'] == true) {
        echo json_encode(array('success' => true, 'message' => 'OTP sent successfully!'));
    } else {
        echo json_encode(array('success' => false, 'message' => 'Failed to send OTP.'));
    }
}
?>