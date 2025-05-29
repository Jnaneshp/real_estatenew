<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../frontend/login.html');
    exit();
}

$user_id = $_SESSION['user_id'];
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$profile_photo = null;

// Handle profile photo upload
if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
    $ext = pathinfo($_FILES['profile_photo']['name'], PATHINFO_EXTENSION);
    $filename = 'profile_' . $user_id . '_' . time() . '.' . $ext;
    $target_dir = dirname(__DIR__) . '/pic/';
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    $target_file = $target_dir . $filename;
    if (move_uploaded_file($_FILES['profile_photo']['tmp_name'], $target_file)) {
        $profile_photo = 'pic/' . $filename;
    }
}

// Update user info
if ($profile_photo) {
    $stmt = $conn->prepare("UPDATE users SET username=?, email=?, profile_photo=? WHERE id=?");
    $stmt->bind_param("sssi", $name, $email, $profile_photo, $user_id);
} else {
    $stmt = $conn->prepare("UPDATE users SET username=?, email=? WHERE id=?");
    $stmt->bind_param("ssi", $name, $email, $user_id);
}

if ($stmt->execute()) {
    // Optionally update session username/email
    $_SESSION['username'] = $name;
    header('Location: ../frontend/home.html?profile=updated');
    exit();
} else {
    header('Location: ../frontend/home.html?profile=error');
    exit();
}
?> 