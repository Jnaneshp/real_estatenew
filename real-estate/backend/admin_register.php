<?php
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $admin_code = $_POST['admin_code']; // Special code for admin registration
    
    // Verify admin code (you should change this to a more secure value)
    $valid_admin_code = "ADMIN123";
    
    if ($admin_code !== $valid_admin_code) {
        header("Location: ../frontend/admin_register.html?error=invalid_code");
        exit();
    }
    
    // Check if username already exists
    $check_stmt = $conn->prepare("SELECT id FROM admins WHERE username = ?");
    $check_stmt->bind_param("s", $username);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    
    if ($result->num_rows > 0) {
        header("Location: ../frontend/admin_register.html?error=username_taken");
        exit();
    }
    $check_stmt->close();
    
    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert new admin user
    $stmt = $conn->prepare("INSERT INTO admins (username, password, email) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $hashed_password, $email);
    
    if ($stmt->execute()) {
        header("Location: ../frontend/admin_login.html?success=1");
        exit();
    } else {
        header("Location: ../frontend/admin_register.html?error=db_error");
        exit();
    }
    
    $stmt->close();
} else {
    header("Location: ../frontend/admin_register.html");
    exit();
}
?> 