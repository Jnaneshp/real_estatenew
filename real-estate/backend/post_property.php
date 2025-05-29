<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../frontend/login.html');
    exit();
}

$user_id = $_SESSION['user_id'];
$title = $_POST['title'] ?? '';
$description = $_POST['description'] ?? '';
$price = $_POST['price'] ?? 0;
$bedrooms = $_POST['bedrooms'] ?? 0;
$bathrooms = $_POST['bathrooms'] ?? 0;
$square_feet = $_POST['square_feet'] ?? 0;
$property_type = $_POST['property_type'] ?? '';
$city = $_POST['city'] ?? '';
$address = $_POST['address'] ?? '';
$status = 'available';
$location = '';
$state = '';
$zip_code = '';
$featured = 0;

// Insert property details
$stmt = $conn->prepare("INSERT INTO properties (user_id, title, description, price, bedrooms, bathrooms, square_feet, property_type, status, location, address, city, state, zip_code, featured) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param(
    "issdiidsssssssi",
    $user_id, $title, $description, $price, $bedrooms, $bathrooms, $square_feet, $property_type,
    $status, $location, $address, $city, $state, $zip_code, $featured
);
if ($stmt->execute()) {
    $property_id = $stmt->insert_id;
    // Handle images
    if (!empty($_FILES['images']['name'][0])) {
        $img_dir = dirname(__DIR__) . '/pic/property_images/';
        if (!is_dir($img_dir)) {
            mkdir($img_dir, 0777, true);
        }
        foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
            if ($_FILES['images']['error'][$key] === UPLOAD_ERR_OK) {
                $ext = pathinfo($_FILES['images']['name'][$key], PATHINFO_EXTENSION);
                $img_name = 'property_' . $property_id . '_' . time() . '_' . $key . '.' . $ext;
                $img_path = $img_dir . $img_name;
                if (move_uploaded_file($tmp_name, $img_path)) {
                    $db_img_path = 'pic/property_images/' . $img_name;
                    $is_primary = ($key === 0) ? 1 : 0;
                    $img_stmt = $conn->prepare("INSERT INTO property_images (property_id, image_path, is_primary) VALUES (?, ?, ?)");
                    $img_stmt->bind_param("isi", $property_id, $db_img_path, $is_primary);
                    $img_stmt->execute();
                    $img_stmt->close();
                }
            }
        }
    }
    $stmt->close();
    header('Location: ../frontend/home.html?property=posted');
    exit();
} else {
    $stmt->close();
    header('Location: ../frontend/post_property.html?error=db');
    exit();
}
?> 