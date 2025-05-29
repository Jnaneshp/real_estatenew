<?php
session_start();
require_once 'config/database.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$property_id = isset($data['id']) ? intval($data['id']) : 0;
$user_id = $_SESSION['user_id'];

if (!$property_id) {
    echo json_encode(['success' => false, 'error' => 'Invalid property id']);
    exit();
}

// Ensure the property belongs to the user
$stmt = $conn->prepare('UPDATE properties SET status = "sold" WHERE id = ? AND user_id = ?');
$stmt->bind_param('ii', $property_id, $user_id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Update failed or not your property']);
}
$stmt->close(); 