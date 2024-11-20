<?php
// Include database connection
include '../php-config/db-conn.php';

// Start the session (make sure it's only called once)
session_start();

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please log in to add items to your cart.']);
    exit;
}

$user_id = $_SESSION['user_id'];
$product_id = intval($_POST['product_id']);
$quantity = intval($_POST['quantity']);
$price_after_discount = doubleval($_POST['price_after_discount']);
$subtotal = doubleval($_POST['subtotal']);
$shipping_fee = doubleval($_POST['shipping_fee']);

// Validate product_id and quantity
if ($product_id <= 0 || $quantity <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid product or quantity.' . $product_id. " " . $quantity]);
    exit;
}

// Disable auto-commit and begin the transaction
$conn->autocommit(false);

try {
    
    $createOrderQuery = $conn->prepare("INSERT INTO orders (buyer_id, total_amount, total_shipping_fee) VALUES (?, ?, ?)");
    $createOrderQuery->bind_param("idd", $user_id, $subtotal, $shipping_fee);  // Bind the integer parameter
    $createOrderQuery->execute();
    $order_id = $conn->insert_id;

    $status_name = "Pending"; // Define the status_name variable
    $getOrderStatusQuery = $conn->prepare("SELECT status_id FROM order_status WHERE status_name = ?");
    $getOrderStatusQuery->bind_param("s", $status_name); // Bind the string parameter
    $getOrderStatusQuery->execute();

    $result = $getOrderStatusQuery->get_result(); // Get the result
    $row = $result->fetch_assoc(); // Fetch the associative array
    $status_id = intval($row['status_id']); // Access the status_id column and convert it to an integer


    $addOrderItemQuery = $conn->prepare("INSERT INTO order_item (order_id, product_id, quantity, price_after_discount, subtotal, shipping_fee, status_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $addOrderItemQuery->bind_param("iiidddi", $order_id, $product_id, $quantity, $price_after_discount, $subtotal, $shipping_fee, $status_id); 
    $addOrderItemQuery->execute();
    echo json_encode(['success' => true, 'message' => 'Order has been placed successfully.']);
    

    // Commit the transaction
    $conn->commit();

} catch (Exception $e) {
    // Roll back the transaction in case of error
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
