<?php
session_start();
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $stmt = $conn->prepare("SELECT id, username, password FROM admins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $admin = $result->fetch_assoc();
        if (password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            
            header("Location: ../frontend/admin_dashboard.php");
            exit();
        } else {
            header("Location: ../frontend/admin_login.html?error=invalid");
            exit();
        }
    } else {
        header("Location: ../frontend/admin_login.html?error=invalid");
        exit();
    }
    
    $stmt->close();
} else {
    header("Location: ../frontend/admin_login.html");
    exit();
}
?> 