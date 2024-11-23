<?php
include('php-config/ssession-config.php');
include('php-config/db-conn.php');
session_start();

// Check if a search query is provided
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Modify the SQL query to join the user_type table and fetch the user type name
$sql = "SELECT u.user_id, u.user_type_id, u.name, u.email, u.profile_picture, u.last_login, u.created_at, u.updated_at, u.password, ut.type_name 
        FROM user u 
        LEFT JOIN user_type ut ON u.user_type_id = ut.user_type_id 
        WHERE u.name LIKE ? OR u.email LIKE ?"; 

$stmt = $conn->prepare($sql);
$searchTermWithWildcard = "%" . $searchTerm . "%"; // Add wildcards for partial matching
$stmt->bind_param("ss", $searchTermWithWildcard, $searchTermWithWildcard);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="user-page.css">
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
            <a href="sales-analysis.php"><i class="fas fa-inbox"></i> <span>sales-analysis</span></a>
            <a href="banner-page.php"><i class="fas fa-ad"></i> <span>Banners</span></a>
            <!-- <a href="analytics.php"><i class="fas fa-chart-bar"></i> <span>Analytics</span></a> -->
            <!-- <a href="settings.php"><i class="fas fa-cog"></i> <span>Settings</span></a> -->
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a>
    </nav>
</div>

<h2>User Management</h2>

<!-- Search Bar -->
<div class="search-container">
    <form method="get" action="user-page.php">
        <input type="text" name="search" placeholder="Search by name or email..." value="<?php echo htmlspecialchars($searchTerm); ?>">
        <button type="submit"><i class="fas fa-search"></i> Search</button>
    </form>
</div>

<div class="user-management-container">
    <div class="add-user-btn-container">
        <a href="add-user.php" class="add-user-btn">Add User</a>
    </div>
    <div class="user-card-container">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="user-card">
                <div class="user-card-header">
                    <img src="<?php echo !empty($row['profile_picture']) ? htmlspecialchars($row['profile_picture']) : 'default-avatar.png'; ?>" alt="Profile">
                    <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                </div>
                <div class="user-card-body">
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($row['email']); ?></p>
                    
                    <p><strong>User Type:</strong> <?php echo htmlspecialchars($row['type_name']); ?></p> <!-- Displaying the user type name -->
                    <p><strong>Created At:</strong> <?php echo htmlspecialchars($row['created_at']); ?></p>
                    <p><strong>Last Updated:</strong> <?php echo htmlspecialchars($row['updated_at']); ?></p>
                </div>
                <div class="user-card-actions">
                    <a href="other-php/edit-user.php?user_id=<?php echo $row['user_id']; ?>" class="edit-btn">Edit</a>
                    <a href="other-php/delete-user.php?user_id=<?php echo $row['user_id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
