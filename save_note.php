<?php
header('Content-Type: application/json');

$host = 'localhost';                 
$username = 'starkina_leads';                 
$password = 'rlo~Hh)tRHc-';                 
$dbname = 'starkina_leads';  
$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'error' => 'Connection failed: ' . $conn->connect_error]));
}

$data = json_decode(file_get_contents("php://input"), true);
$leadId = $data['leadId'];
$note = $data['note'];

$sql = "INSERT INTO notes (lead_id, note) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $leadId, $note);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to save note.']);
}

$stmt->close();
$conn->close();
?>