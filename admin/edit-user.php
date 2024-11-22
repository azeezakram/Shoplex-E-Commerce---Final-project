<?php 
include 'php-config/db-conn.php';
include 'php-config/ssession-config.php';
session_start();

// Check if a user ID is provided
if (!isset($_GET['user_id']) || empty($_GET['user_id'])) {
    die('User ID is missing.');
}

$userId = intval($_GET['user_id']);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $userType = $_POST['user_type'];
    $password = $_POST['password']; // New password field

    // Prepare the SQL query
    if (!empty($password)) {
        // Hash the password before updating it
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $sql = "UPDATE user SET name = ?, email = ?, user_type_id = ?, password = ? WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssisi', $name, $email, $userType, $hashedPassword, $userId);
    } else {
        // Update without password
        $sql = "UPDATE user SET name = ?, email = ?, user_type_id = ? WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssii', $name, $email, $userType, $userId);
    }

    if ($stmt->execute()) {
        header('Location: user-page.php'); // Redirect to user management after editing
        exit;
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

// Fetch user details to prefill the form
$sql = "SELECT user_id, name, email, user_type_id FROM user WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $userId);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user) {
    die('User not found.');
}

// Fetch all user types to populate the dropdown
$sqlUserTypes = "SELECT user_type_id, type_name FROM user_type"; // Fetching user types from the `user_type` table
$resultUserTypes = $conn->query($sqlUserTypes);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/edit-userpage.css">
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

    
    <div class="container">
        <h2>Edit User</h2>
        <form action="" method="POST">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
            <br>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            <br>
            
            <label for="user_type">User Type:</label>
            <select id="user_type" name="user_type" required>
                <?php 
                // Loop through user types to create the dropdown options
                while ($row = $resultUserTypes->fetch_assoc()) {
                    $selected = ($row['user_type_id'] == $user['user_type_id']) ? 'selected' : ''; // Pre-select the current user type
                    echo "<option value='" . $row['user_type_id'] . "' $selected>" . htmlspecialchars($row['type_name']) . "</option>";
                }
                ?>
            </select>
            <br>
            
            <label for="password">Password (leave blank to keep unchanged):</label>
            <input type="password" id="password" name="password">
            <br>
            
            <button type="submit">Update</button>
            <a href="../user-page.php" class="cancel-btn">Cancel</a>
        </form>
    </div>

    <script type="module" src="../javascript/add-user.js"></script>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
