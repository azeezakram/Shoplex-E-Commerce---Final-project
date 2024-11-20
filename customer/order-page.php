<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Orders</title>
    <!-- Link to the CSS file -->
    <link rel="stylesheet" href="css/order.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    
    <!-- The rest of your PHP code goes here -->
    <?php
    // PHP code to fetch and display orders
    include('php-config/db-conn.php');
    include('php-config/ssession-config.php');

    session_start();

    $userId = $_SESSION['user_id'];

    // Fetch the orders and their items for the logged-in user
    $orderSql = "SELECT o.order_id, o.ordered_at, o.total_amount, o.total_shipping_fee, oi.order_item_id, oi.product_id, oi.quantity, oi.price_after_discount, oi.subtotal, oi.shipping_fee, oi.status_id, oi.shipped_date, oi.expected_delivery_date, oi.delivered_date, p.product_name, s.status_name
                 FROM orders o
                 JOIN order_item oi ON o.order_id = oi.order_id
                 JOIN product p ON oi.product_id = p.product_id
                 JOIN order_status s ON oi.status_id = s.status_id
                 WHERE o.buyer_id = ?
                 ORDER BY o.ordered_at DESC";
                 
    $orderStmt = $conn->prepare($orderSql);
    $orderStmt->bind_param('i', $userId);
    $orderStmt->execute();
    $orderResult = $orderStmt->get_result();

    // Display orders and order items
    if ($orderResult->num_rows > 0) {
        echo '<div class="order-container">';
        echo '<h2>Your Orders</h2>';
        
        while ($order = $orderResult->fetch_assoc()) {
            echo '<div class="order-box">';
            echo '<h3>Order ID: ' . htmlspecialchars($order['order_id']) . '</h3>';
            echo '<p>Order Date: ' . htmlspecialchars($order['ordered_at']) . '</p>';
            echo '<p>Total Amount: LKR ' . number_format($order['total_amount'], 2) . '</p>';
            echo '<p>Shipping Fee: LKR ' . number_format($order['total_shipping_fee'], 2) . '</p>';

            echo '<table class="order-details-table">';
            echo '<tr>';
            echo '<th>Product Name</th>';
            echo '<th>Quantity</th>';
            echo '<th>Price After Discount</th>';
            echo '<th>Subtotal</th>';
            echo '<th>Shipping Fee</th>';
            echo '<th>Status</th>';
            echo '<th>Shipped Date</th>';
            echo '<th>Expected Delivery Date</th>';
            echo '<th>Delivered Date</th>';
            echo '</tr>';

            // Display order items
            do {
                $subtotal = $order['quantity'] * $order['price_after_discount'];
                echo '<tr>';
                echo '<td>' . htmlspecialchars($order['product_name']) . '</td>';
                echo '<td>' . htmlspecialchars($order['quantity']) . '</td>';
                echo '<td>LKR ' . number_format($order['price_after_discount'], 2) . '</td>';
                echo '<td>LKR ' . number_format($subtotal, 2) . '</td>';
                echo '<td>LKR ' . number_format($order['shipping_fee'], 2) . '</td>';
                echo '<td>' . htmlspecialchars($order['status_name']) . '</td>';
                echo '<td>' . ($order['shipped_date'] ? htmlspecialchars($order['shipped_date']) : 'Not Shipped') . '</td>';
                echo '<td>' . ($order['expected_delivery_date'] ? htmlspecialchars($order['expected_delivery_date']) : 'N/A') . '</td>';
                echo '<td>' . ($order['delivered_date'] ? htmlspecialchars($order['delivered_date']) : 'Not Delivered') . '</td>';
                echo '</tr>';
            } while ($order = $orderResult->fetch_assoc()); // Continue loop for all items in the same order

            echo '</table>';
            echo '</div>'; // End order box
        }

        echo '</div>'; // End order container
    } else {
        echo '<p>You have no orders.</p>';
    }

    $orderStmt->close();
    $conn->close();
    ?>
</body>
</html>
