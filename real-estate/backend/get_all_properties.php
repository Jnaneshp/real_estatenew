<?php
require_once 'config/database.php';
header('Content-Type: application/json');

$properties = [];
$sql = "SELECT p.*, u.username FROM properties p LEFT JOIN users u ON p.user_id = u.id ORDER BY p.created_at DESC";
$result = $conn->query($sql);
if ($result) {
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
            'owner_initial' => strtoupper(substr($row['username'] ?? 'U', 0, 1)),
            'owner_name' => $row['username'] ?? 'Unknown',
            'status' => $row['status'],
        ];
    }
}
echo json_encode($properties);
?> 