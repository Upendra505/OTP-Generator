// process_otp.php
require 'vendor/autoload.php'; // Load Twilio's autoload file

use Twilio\Rest\Client;

function sendOtp($phone) {
    $otp = rand(100000, 999999); // Generate a 6-digit OTP

    // Store OTP in session (or temporary storage)
    session_start();
    $_SESSION['otp'] = $otp;

    // Twilio credentials
    $account_sid = 'your_account_sid';
    $auth_token = 'your_auth_token';
    $twilio_number = 'your_twilio_number';

    // Create Twilio client
    $client = new Client($account_sid, $auth_token);

    // Send OTP
    $client->messages->create(
        $phone,
        array(
            'from' => $twilio_number,
            'body' => "Your OTP is: $otp"
        )
    );

    return true;
}

if (isset($_POST['phone'])) {
    $phone = $_POST['phone'];
    sendOtp($phone); // Call function to send OTP
    echo "OTP sent";
}
