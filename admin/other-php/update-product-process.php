<?php
include '../php-config/db-conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = $_POST['productId'];
    $productType = $_POST['productType'];
    $productName = $_POST['productName'];
    $description = $_POST['description'];
    $parentCategoryId = intval($_POST['parentCategory']);
    $subCategoryId = intval($_POST['subCategory']);

    // Handle price, check if it's present for normal products
    $price = isset($_POST['price']) ? number_format(floatval($_POST['price']), 2, '.', '') : null;
    $discount = number_format(floatval($_POST['discount']) / 100, 2, '.', '');
    $stock = intval($_POST['stock']);
    $shippingFee = number_format(floatval($_POST['shippingFee']), 2, '.', '');

    // For bidding products
    $bidStartingPrice = isset($_POST['bidStartingPrice']) ? number_format(floatval($_POST['bidStartingPrice']), 2, '.', '') : null;
    $bidStartDate = $_POST['bidStartDate'] ?? null;
    $bidEndDate = $_POST['bidEndDate'] ?? null;

    // Handle uploaded images
    $uploadedImages = [];
    if (!empty($_FILES['images'])) {
        $uploadDir = "/images/product-images/";

        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        foreach ($_FILES['images']['tmp_name'] as $key => $tmpName) {
            $fileName = basename($_FILES['images']['name'][$key]);
            $targetPath = $uploadDir . $fileName;

            if (move_uploaded_file($tmpName, $targetPath)) {
                $uploadedImages[] = $targetPath;
            }
        }
    }

    try {
        $conn->begin_transaction();

        // Update normal product
        if ($productType == "normal") {
            $updateProductQuery = "
                UPDATE product SET 
                    category_id = ?, 
                    product_name = ?, 
                    description = ?, 
                    price = ?, 
                    stock = ?, 
                    discount = ?, 
                    shipping_fee = ? 
                WHERE product_id = ?";
            
            $stmt = $conn->prepare($updateProductQuery);
            $stmt->bind_param(
                "issdiddi",
                $subCategoryId,
                $productName,
                $description,
                $price,
                $stock,
                $discount,
                $shippingFee,
                $productId
            );
            $stmt->execute();

            // Handle product images (delete old and insert new ones)
            if (!empty($uploadedImages)) {
                $deleteExistingImagesQuery = "DELETE FROM product_picture WHERE product_id = ?";
                $deleteStmt = $conn->prepare($deleteExistingImagesQuery);
                $deleteStmt->bind_param("i", $productId);
                $deleteStmt->execute();
                $deleteStmt->close();

                $addProductImageQuery = "INSERT INTO product_picture (product_id, picture_path, default_picture) VALUES (?, ?, ?)";
                $imageStmt = $conn->prepare($addProductImageQuery);

                foreach ($uploadedImages as $index => $imagePath) {
                    $defaultPicture = ($index === 0) ? 1 : 0; // First image is default
                    $imageStmt->bind_param("isi", $productId, $imagePath, $defaultPicture);
                    $imageStmt->execute();
                }
                $imageStmt->close();
            }
        }

        // Update bidding product
        if ($productType == "bidding") {
            $updateBiddingProductQuery = "
                UPDATE product SET 
                    category_id = ?, 
                    product_name = ?, 
                    description = ?, 
                    bid_starting_price = ?, 
                    stock = ?, 
                    shipping_fee = ?, 
                    bid_activate = ? 
                WHERE product_id = ?";
            
            $stmt = $conn->prepare($updateBiddingProductQuery);
            $bid_activate = 1;
            $stmt->bind_param(
                "issdidii",
                $subCategoryId,
                $productName,
                $description,
                $bidStartingPrice,
                $stock,
                $shippingFee,
                $bid_activate,
                $productId
            );
            $stmt->execute();

            // Update auction history for bidding products
            $updateAuctionHistoryQuery = "
                UPDATE auction_history 
                SET starting_bid = ?, start_time = ?, end_time = ? 
                WHERE product_id = ? AND is_end = 0";
            
            $stmt = $conn->prepare($updateAuctionHistoryQuery);
            $stmt->bind_param("dssi", $bidStartingPrice, $bidStartDate, $bidEndDate, $productId);
            $stmt->execute();

            // Handle images (delete old and insert new ones)
            if (!empty($uploadedImages)) {
                $deleteExistingImagesQuery = "DELETE FROM product_picture WHERE product_id = ?";
                $deleteStmt = $conn->prepare($deleteExistingImagesQuery);
                $deleteStmt->bind_param("i", $productId);
                $deleteStmt->execute();
                $deleteStmt->close();

                $addProductImageQuery = "INSERT INTO product_picture (product_id, picture_path, default_picture) VALUES (?, ?, ?)";
                $imageStmt = $conn->prepare($addProductImageQuery);

                foreach ($uploadedImages as $index => $imagePath) {
                    $defaultPicture = ($index === 0) ? 1 : 0;
                    $imageStmt->bind_param("isi", $productId, $imagePath, $defaultPicture);
                    $imageStmt->execute();
                }
                $imageStmt->close();
            }
        }

        // Commit transaction
        $conn->commit();

        echo json_encode(['status' => 'success']);
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }

    $stmt->close();
    $conn->close();
}
?>
