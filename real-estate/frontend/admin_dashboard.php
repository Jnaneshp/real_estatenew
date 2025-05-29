<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Real Estate</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .admin-dashboard {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 250px;
            background: #2c3e50;
            color: white;
            padding: 20px;
        }
        .main-content {
            flex: 1;
            padding: 20px;
            background: #f4f6f9;
        }
        .sidebar h2 {
            margin-bottom: 30px;
            font-size: 24px;
        }
        .nav-item {
            padding: 12px 15px;
            margin: 5px 0;
            cursor: pointer;
            border-radius: 4px;
            transition: background 0.3s;
        }
        .nav-item:hover {
            background: #34495e;
        }
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .stat-card h3 {
            margin: 0 0 10px 0;
            color: #2c3e50;
        }
        .logout-btn {
            margin-top: auto;
            padding: 12px;
            background: #e74c3c;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            display: block;
        }
        .logout-btn:hover {
            background: #c0392b;
        }
    </style>
</head>
<body>
    <div class="admin-dashboard">
        <div class="sidebar">
            <h2>Admin Panel</h2>
            <div class="nav-item">Dashboard</div>
            <div class="nav-item">Users</div>
            <div class="nav-item">Properties</div>
            <div class="nav-item">Inquiries</div>
            <div class="nav-item">Settings</div>
            <a href="../backend/logout.php" class="logout-btn">Logout</a>
        </div>
        <div class="main-content">
            <h1>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></h1>
            <div class="stats-container">
                <div class="stat-card">
                    <h3>Total Users</h3>
                    <p>Loading...</p>
                </div>
                <div class="stat-card">
                    <h3>Total Properties</h3>
                    <p>Loading...</p>
                </div>
                <div class="stat-card">
                    <h3>Active Listings</h3>
                    <p>Loading...</p>
                </div>
                <div class="stat-card">
                    <h3>New Inquiries</h3>
                    <p>Loading...</p>
                </div>
            </div>
            <!-- More dashboard content will be added here -->
        </div>
    </div>
</body>
</html> 