<?php
include '../php-config/db-conn.php'; 

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['productId'])) {
    $productId = $data['productId'];

    $query = "DELETE FROM product WHERE product_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $productId);

    if ($stmt->execute()) {
        // Success
        echo json_encode(["status" => "success", "message" => "Product deleted successfully."]);
    } else {
        // Failure
        echo json_encode(["status" => "error", "message" => "Failed to delete the product."]);
    }

    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid product ID."]);
}

$conn->close();
?>
