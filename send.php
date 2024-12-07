<?php
require __DIR__ . '/vendor/autoload.php';
use Twilio\Rest\Client;

// Hardcode your Twilio credentials
  // Replace with your actual Twilio Account SID
    // Replace with your actual Twilio Auth Token

// Twilio WhatsApp sandbox number (or a verified WhatsApp number)
$twilio_whatsapp_number = "whatsapp:+14155238886"; // Twilio Sandbox WhatsApp number

// Create a new instance of the Twilio client
$client = new Client($account_sid, $auth_token);

// Function to generate a random 6-digit code
function generateVerificationCode() {
    return rand(100000, 999999);
}

// REST API logic to handle requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the phone number from the request body
    $input = json_decode(file_get_contents('php://input'), true);
    $phone_number = $input['phone_number'];

    if (!$phone_number) {
        http_response_code(400);
        echo json_encode(['error' => 'Phone number is required']);
        exit;
    }

    // Generate a random 6-digit verification code
    $verification_code = generateVerificationCode();

    // Send the code via WhatsApp
    try {
        $message = $client->messages->create(
            "whatsapp:" . $phone_number, // Send to WhatsApp number
            array(
                'from' => $twilio_whatsapp_number, // From Twilio WhatsApp number
                'body' => "Your verification code is: $verification_code"
            )
        );

        // Respond with success
        echo json_encode([
            'message' => 'Verification code sent successfully',
            'verification_code' => $verification_code // Optional: For testing purposes
        ]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to send message: ' . $e->getMessage()]);
    }
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Only POST requests are allowed']);
}
