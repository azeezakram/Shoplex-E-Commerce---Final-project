<?php
include('php-config/db-conn.php');
include('php-config/ssession-config.php');

session_start();
$userId = $_SESSION['user_id']; // User ID from session

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkout'])) {
    $quantities = $_POST['quantities']; // Get updated quantities
    $orderTotal = 0;

    // Update quantities and calculate total
    foreach ($quantities as $productId => $quantity) {
        $quantity = (int)$quantity;

        // Fetch product price from database
        $sql = "SELECT discounted_price FROM carts WHERE user_id = ? AND product_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ii', $userId, $productId);
        $stmt->execute();
        $stmt->bind_result($discountedPrice);
        $stmt->fetch();
        $stmt->close();

        if ($discountedPrice) {
            $totalPrice = $discountedPrice * $quantity;
            $orderTotal += $totalPrice;

            // Update quantity in the cart
            $updateSql = "UPDATE carts SET quantity = ? WHERE user_id = ? AND product_id = ?";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bind_param('iii', $quantity, $userId, $productId);
            $updateStmt->execute();
            $updateStmt->close();
        }
    }

    // Insert into orders table
    $orderSql = "INSERT INTO orders (user_id, total_amount, ordered_at) VALUES (?, ?, NOW())";
    $orderStmt = $conn->prepare($orderSql);
    $orderStmt->bind_param('id', $userId, $orderTotal);
    if ($orderStmt->execute()) {
        $orderId = $orderStmt->insert_id;

        // Insert order details
        foreach ($quantities as $productId => $quantity) {
            $productSql = "SELECT product_name, discounted_price FROM carts WHERE user_id = ? AND product_id = ?";
            $productStmt = $conn->prepare($productSql);
            $productStmt->bind_param('ii', $userId, $productId);
            $productStmt->execute();
            $productResult = $productStmt->get_result();
            $product = $productResult->fetch_assoc();
            $productStmt->close();

            if ($product) {
                $detailSql = "INSERT INTO order_details (order_id, product_id, product_name, quantity, price) 
                              VALUES (?, ?, ?, ?, ?)";
                $detailStmt = $conn->prepare($detailSql);
                $detailStmt->bind_param('iisid', $orderId, $productId, $product['product_name'], $quantity, $product['discounted_price']);
                $detailStmt->execute();
                $detailStmt->close();
            }
        }

        // Clear the cart
        $clearCartSql = "DELETE FROM carts WHERE user_id = ?";
        $clearCartStmt = $conn->prepare($clearCartSql);
        $clearCartStmt->bind_param('i', $userId);
        $clearCartStmt->execute();
        $clearCartStmt->close();

        echo "<script>
        alert('Order placed successfully!');
        window.location.href = 'index.php';
    </script>";
    exit;
        
    } else {
        echo "Failed to place order: " . $orderStmt->error;
    }

    $orderStmt->close();
    $conn->close();
}
?>
