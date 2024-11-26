<?php
include('../php-config/db-conn.php');
session_start();

$userId = $_SESSION['user_id'];

// Fetch cart ID
$cartSql = "SELECT cart_id FROM cart WHERE buyer_id = ?";
$cartStmt = $conn->prepare($cartSql);
$cartStmt->bind_param('i', $userId);
$cartStmt->execute();
$cartResult = $cartStmt->get_result();
$cart = $cartResult->fetch_assoc();
$cart_id = $cart['cart_id'];


$total_amount = 0;
$total_shipping_fee = 0;

// Create order
$orderSql = "INSERT INTO orders (buyer_id, total_amount, total_shipping_fee) VALUES (?, ?, ?)";
$orderStmt = $conn->prepare($orderSql);
$orderStmt->bind_param('idd', $userId, $total_amount, $total_shipping_fee); // Bind total_amount and total_shipping_fee as 0 initially
$orderStmt->execute();
$orderId = $conn->insert_id; // Get the generated order_id

// Initialize totals


// Fetch cart items
$cartItemsSql = "SELECT * FROM cart_item WHERE cart_id = ?";
$cartItemsStmt = $conn->prepare($cartItemsSql);
$cartItemsStmt->bind_param('i', $cart_id);
$cartItemsStmt->execute();
$cartItemsResult = $cartItemsStmt->get_result();

while ($item = $cartItemsResult->fetch_assoc()) {
    // Fetch product details
    $productDetailSql = "SELECT * FROM product WHERE product_id = ?";
    $productDetailStmt = $conn->prepare($productDetailSql);
    $productDetailStmt->bind_param('i', $item['product_id']);
    $productDetailStmt->execute();
    $productDetailResult = $productDetailStmt->get_result();
    $productDetail = $productDetailResult->fetch_assoc();

    // Calculate price after discount and subtotal
    $price_after_discount = floatval($productDetail['price']) - floatval($productDetail['price']) * floatval($productDetail['discount']);
    $shipping_fee = floatval($productDetail['shipping_fee']);
    $subtotal = $price_after_discount * $item['quantity'];

    // Update totals
    $total_shipping_fee += $shipping_fee;
    $total_amount += $subtotal;

    // Fetch order status ID (assuming "Pending" status exists)
    $status_name = "Pending"; 
    $getOrderStatusQuery = $conn->prepare("SELECT status_id FROM order_status WHERE status_name = ?");
    $getOrderStatusQuery->bind_param("s", $status_name); 
    $getOrderStatusQuery->execute();
    $result = $getOrderStatusQuery->get_result();
    $row = $result->fetch_assoc();
    $status_id = intval($row['status_id']); // Get status ID

    // Insert into order_item
    $detailSql = "INSERT INTO order_item (order_id, product_id, quantity, price_after_discount, subtotal, shipping_fee, status_id) 
                  VALUES (?, ?, ?, ?, ?, ?, ?)";
    $detailStmt = $conn->prepare($detailSql);
    $detailStmt->bind_param('iiidddi', $orderId, $item['product_id'], $item['quantity'], $price_after_discount, $subtotal, $shipping_fee, $status_id);
    $detailStmt->execute();
}

// Update order totals in the orders table
$orderUpdateSql = "UPDATE orders SET total_amount = ?, total_shipping_fee = ? WHERE order_id = ?";
$orderUpdateStmt = $conn->prepare($orderUpdateSql);
$orderUpdateStmt->bind_param('ddi', $total_amount, $total_shipping_fee, $orderId);
$orderUpdateStmt->execute();

// Clear cart items after order is placed
$clearCartSql = "DELETE FROM cart_item WHERE cart_id = ?";
$clearCartStmt = $conn->prepare($clearCartSql);
$clearCartStmt->bind_param('i', $cart_id);
$clearCartStmt->execute();

// Redirect to the orders page after successful checkout
header('Location: ../index.php'); 
exit;
?>
