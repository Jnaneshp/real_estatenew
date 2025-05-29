<?php
session_start();
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            
            header("Location: ../frontend/home.html");
            exit();
        } else {
            header("Location: ../frontend/login.html?error=invalid");
            exit();
        }
    } else {
        header("Location: ../frontend/login.html?error=invalid");
        exit();
    }
    
    $stmt->close();
} else {
    header("Location: ../frontend/login.html");
    exit();
}
?> 