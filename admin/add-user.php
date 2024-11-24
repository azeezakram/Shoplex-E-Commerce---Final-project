<?php
include'php-config/ssession-config.php';
include'php-config/db-conn.php';

if (isset($_GET['email'])) {
    $email = $_GET['email'];

    $sql = "SELECT * FROM user WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    echo json_encode(['exists' => $result->num_rows > 0]);

    $stmt->close();
    $conn->close();
    exit(); 
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $user_type_id = $_POST['user_type_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); 
    $profile_picture = $_FILES['profile_picture']['name'];
    $target_dir = "/images/user-profile-pictures/"; 
    $target_file = $target_dir . basename($_FILES['profile_picture']['name']);

    if (!empty($profile_picture) && move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file)) {
        $profile_picture_path = $target_file;
    } else {
        $profile_picture_path = null; // No image uploaded
    }

    // Insert user into the database
    $sql = "INSERT INTO user (user_type_id, name, email, password, profile_picture) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issss", $user_type_id, $name, $email, $password, $profile_picture_path);

    if ($stmt->execute()) {
        echo "<script>alert('User added successfully!'); window.location.href = 'user-page.php';</script>";
    } else {
        echo "<script>alert('Error adding user: " . $stmt->error . "');</script>";
    }

    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/add-user.css">
</head>
<body>
<div class="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-header-content">
                <span>Admin Panel</span>
            </div>
            <div class="hamburger" onclick="toggleSidebar()">
                <div class="hamburger-line"></div>
                <div class="hamburger-line"></div>
                <div class="hamburger-line"></div>
            </div>
        </div>
        <nav class="sidebar-nav">
            <a href="dashboard-page.php"><i class="fas fa-home"></i> <span>Dashboard</span></a>
            <a href="user-page.php"><i class="fas fa-users"></i> <span>Users</span></a>
            <a href="inventory-page.php"><i class="fas fa-archive"></i> <span>Inventories</span></a>
            <a href="order-page.php"><i class="fas fa-box"></i> <span>Orders</span></a>
            <a href="bidding-record-page.php"><i class="fas fa-gavel"></i> <span>Bidding Records</span></a>
            <a href="message-page.php"><i class="fas fa-inbox"></i> <span>Messages</span></a>
            <a href="banner-page.php"><i class="fas fa-ad"></i> <span>Banners</span></a>
            <!-- <a href="analytics.php"><i class="fas fa-chart-bar"></i> <span>Analytics</span></a> -->
            <!-- <a href="settings.php"><i class="fas fa-cog"></i> <span>Settings</span></a> -->
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a>
        </nav>
    </div>

    <div class="form-container">
        <h2>Add New User</h2>
        <form action="add-user.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="user_type_id">User Type:</label>
                <select name="user_type_id" id="user_type_id" required>
                    <option value="4">Admin</option>
                    <option value="1">Buyer</option>
                </select>
            </div>
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required>
            </div>
            <div class="form-group">
                <label for="profile_picture">Profile Picture:</label>
                <input type="file" name="profile_picture" id="profile_picture" accept="image/*">
            </div>
            <button type="submit" class="submit-btn">Add User</button>
        </form>
        <a href="user-page.php" class="back-btn">Back to User Management</a>
    </div>

<script type="module" src="javascript/add-user.js"></script>
</body>
</html>