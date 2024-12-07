<?php
// verify_otp.php

// Start session to retrieve OTP
session_start();

// Set the response type to JSON
header('Content-Type: application/json');

$response = ['success' => false, 'message' => 'Invalid OTP'];

if (isset($_POST['otp'])) {
    $enteredOtp = $_POST['otp'];

    // Compare entered OTP with stored OTP
    if (isset($_SESSION['otp']) && $_SESSION['otp'] == $enteredOtp) {
        $_SESSION['otp_verified'] = true; // Mark as verified
        $response = ['success' => true, 'message' => 'OTP verified'];
    }
}

// Return the JSON-encoded response
echo json_encode($response);
?>