<?php
// Include database connection
include '../php-config/db-conn.php';

session_start();

// Check if user is logged in (optional)
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please log in to add items to your cart.']);
    exit;
}

$user_id = $_SESSION['user_id'];
$product_id = intval($_POST['product_id']);
$quantity = intval($_POST['quantity']);

if ($product_id <= 0 || $quantity <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid product or quantity.']);
    exit;
}

try {
    $conn->beginTransaction();

    // Check if a cart exists for the user
    $cartQuery = $conn->prepare("SELECT cart_id FROM cart WHERE buyer_id = :buyer_id");
    $cartQuery->execute([':buyer_id' => $user_id]);
    $cart = $cartQuery->fetch();

    if (!$cart) {
        // Create a new cart
        $createCartQuery = $conn->prepare("INSERT INTO cart (buyer_id) VALUES (:buyer_id");
        $createCartQuery->execute([':buyer_id' => $user_id]);
        $cart_id = $conn->lastInsertId();
    } else {
        $cart_id = $cart['cart_id'];
    }

    // Check if the product is already in the cart
    $cartItemQuery = $conn->prepare("SELECT quantity FROM cart_item WHERE cart_id = :cart_id AND product_id = :product_id");
    $cartItemQuery->execute([':cart_id' => $cart_id, ':product_id' => $product_id]);
    $cartItem = $cartItemQuery->fetch();

    if ($cartItem) {
        // Update the quantity if the product is already in the cart
        $updateCartItemQuery = $conn->prepare("UPDATE cart_item SET quantity = quantity + :quantity WHERE cart_id = :cart_id AND product_id = :product_id");
        $updateCartItemQuery->execute([':quantity' => $quantity, ':cart_id' => $cart_id, ':product_id' => $product_id]);
    } else {
        // Add the product to the cart
        $addCartItemQuery = $conn->prepare("INSERT INTO cart_item (cart_id, product_id, quantity) VALUES (:cart_id, :product_id, :quantity)");
        $addCartItemQuery->execute([':cart_id' => $cart_id, ':product_id' => $product_id, ':quantity' => $quantity]);
    }

    $conn->commit();
    echo json_encode(['success' => true, 'message' => 'Product added to cart successfully.']);
} catch (Exception $e) {
    $conn->rollBack();
    echo json_encode(['success' => false, 'message' => 'Failed to add product to cart. ' . $e->getMessage()]);
}
?>
