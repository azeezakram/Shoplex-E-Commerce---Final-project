
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
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
    <main class="main-content">
        <header class="header">
            <h1>Dashboard</h1>
            <div class="user-actions">
                <button>Notifications</button>
                <button>Profile</button>
            </div>
        </header>

        <div class="dashboard-cards">
            <div class="card">
                <div class="card-icon"><i class="fas fa-users"></i></div>
                <h3>Total Users</h3>
                <p>1,254</p>
            </div>
            <div class="card">
                <div class="card-icon"><i class="fas fa-chart-line"></i></div>
                <h3>Revenue</h3>
                <p>$45,230</p>
            </div>
            <div class="card">
                <div class="card-icon"><i class="fas fa-clipboard-list"></i></div>
                <h3>Pending Tasks</h3>
                <p>12</p>
            </div>
            <div class="card">
                <div class="card-icon"><i class="fas fa-server"></i></div>
                <h3>Server Status</h3>
                <p>Operational</p>
            </div>
        </div>
    </main>

    <script>
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('collapsed');
        }
    </script>
</body>

</html>