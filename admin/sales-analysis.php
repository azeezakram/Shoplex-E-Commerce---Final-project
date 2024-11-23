
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Sales Analysis</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/sales-analysis.css">

   
</head>

<body>
    <!-- Sidebar -->
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
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <header>
            <h1>Sales Analysis Dashboard</h1>
        </header>

        <div class="container">
            <?php
            include('php-config/ssession-config.php');
            include('php-config/db-conn.php');

            $sql = "
                SELECT c.category_name, SUM(oi.quantity * oi.price_after_discount) AS total_sales
                FROM category c
                JOIN category_item ci ON c.category_id = ci.category_id
                JOIN order_item oi ON ci.product_id = oi.product_id
                JOIN orders o ON oi.order_id = o.order_id
                GROUP BY c.category_id, c.category_name
                ORDER BY total_sales DESC
            ";

            $result = $conn->query($sql);

            $totalSales = 0;
            $salesData = [];
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $salesData[] = $row;
                    $totalSales += $row['total_sales'];
                }
            }

            if (!empty($salesData)):
                foreach ($salesData as $row):
                    $percentage = ($row['total_sales'] / $totalSales) * 100; ?>
                    <div class="card">
                        <h2><?= htmlspecialchars($row['category_name']) ?></h2>
                        <p><strong>Total Sales:</strong> $<?= number_format($row['total_sales'], 2) ?></p>
                        <p><strong>Percentage:</strong> <?= number_format($percentage, 2) ?>%</p>
                        <div class="progress-bar">
                            <div class="progress-bar-inner" style="width: <?= $percentage ?>%;"></div>
                        </div>
                    </div>
                <?php endforeach;
            else: ?>
                <div class="card">
                    <p>No sales data available</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>
