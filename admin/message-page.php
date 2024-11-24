<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css">
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
    <div class="main-content">
        <div class="header">
            <h1>Messages Management</h1>
            <div>
                <button>Add User</button>
            </div>
        </div>

        <!-- Table to display users -->
        <table class="user-table">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Placeholder for dynamically loaded user data -->
                <tr>
                    <td>1</td>
                    <td>John Doe</td>
                    <td>john@example.com</td>
                    <td>Admin</td>
                    <td><button>Edit</button> <button>Delete</button></td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Jane Smith</td>
                    <td>jane@example.com</td>
                    <td>User</td>
                    <td><button>Edit</button> <button>Delete</button></td>
                </tr>
                <!-- More users can be added here dynamically -->
            </tbody>
        </table>
    </div>

    <script src="javascript/side-navbar.js"></script>
</body>

</html>