<?php
session_start();
require_once 'config/database.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch properties posted by the user
$properties = [];
$sql = "SELECT * FROM properties WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $property_id = $row['id'];
    // Fetch images for this property
    $img_stmt = $conn->prepare("SELECT image_path FROM property_images WHERE property_id = ?");
    $img_stmt->bind_param("i", $property_id);
    $img_stmt->execute();
    $img_result = $img_stmt->get_result();
    $images = [];
    while ($img = $img_result->fetch_assoc()) {
        $images[] = $img['image_path'];
    }
    $img_stmt->close();
    $properties[] = [
        'id' => $row['id'],
        'title' => $row['title'],
        'description' => $row['description'],
        'price' => $row['price'],
        'bedrooms' => $row['bedrooms'],
        'bathrooms' => $row['bathrooms'],
        'square_feet' => $row['square_feet'],
        'property_type' => $row['property_type'],
        'city' => $row['city'],
        'address' => $row['address'],
        'created_at' => $row['created_at'],
        'images' => $images,
        'owner_initial' => strtoupper(substr($_SESSION['username'] ?? 'U', 0, 1)),
        'owner_name' => $_SESSION['username'] ?? 'You',
        'status' => $row['status'],
    ];
}
$stmt->close();
echo json_encode($properties);
?> 