<?php
// process_form.php

$host = 'localhost';                 
$username = 'starkina_leads';                 
$password = 'rlo~Hh)tRHc-';                 
$dbname = 'starkina_leads';  
session_start();

// Ensure OTP is verified before proceeding
if (isset($_SESSION['otp_verified']) && $_SESSION['otp_verified']) {
    // Form data
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $qualification = $_POST['qualification'];
    $course = $_POST['course'];
    $updates = isset($_POST['updates']) ? 'Yes' : 'No';

    // Create MySQL connection
    $conn = new mysqli($host, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert data into MySQL
    $stmt = $conn->prepare("INSERT INTO lead (name, phone, qualification,course, createdAt) VALUES (?, ?, ?,?, NOW())");
    $stmt->bind_param("ssss", $name, $phone, $qualification, $course);

    if ($stmt->execute()) {
        echo "Form data saved to MySQL database!";
        $_SESSION['name'] = $name;
        header('location:thankyou.php');
    } else {
        echo "Error saving data: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo "Please verify OTP first!";
}
