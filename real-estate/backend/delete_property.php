<?php
session_start();
require_once 'config/database.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$property_id = isset($data['id']) ? intval($data['id']) : 0;

if (!$property_id) {
    echo json_encode(['success' => false, 'error' => 'Invalid property id']);
    exit();
}

// Delete property images from disk
$img_stmt = $conn->prepare('SELECT image_path FROM property_images WHERE property_id = ?');
$img_stmt->bind_param('i', $property_id);
$img_stmt->execute();
$img_result = $img_stmt->get_result();
while ($img = $img_result->fetch_assoc()) {
    $img_path = dirname(__DIR__) . '/../' . $img['image_path'];
    if (file_exists($img_path)) {
        @unlink($img_path);
    }
}
$img_stmt->close();

// Delete property (will cascade to property_images)
$stmt = $conn->prepare('DELETE FROM properties WHERE id = ?');
$stmt->bind_param('i', $property_id);
$stmt->execute();
if ($stmt->affected_rows > 0) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Delete failed']);
}
$stmt->close(); 