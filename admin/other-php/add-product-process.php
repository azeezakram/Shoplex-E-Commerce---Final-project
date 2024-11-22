<?php
include '../php-config/db-conn.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productType = $_POST['productType'];
    $productName = $_POST['productName'];
    $description = $_POST['description'];
    $parentCategoryId = intval($_POST['parentCategory']);
    $subCategoryId = intval($_POST['subCategory']);

    if (isset($_POST['price'])) {
        $price = number_format(floatval($_POST['price']), 2, '.', '');
    } else {
        
        $price = null;  
    }
    
    $discount = number_format(floatval($_POST['discount'])/ 100 , 2, '.', '');
    $stock = intval($_POST['stock']);
    $shippingFee = number_format(floatval($_POST['shippingFee']), 2, '.', '');


    $bidStartingPrice = isset($_POST['bidStartingPrice']) ? number_format(floatval($_POST['bidStartingPrice']), 2, '.', '') : null;
    $bidStartDate = $_POST['bidStartDate'] ?? null;
    $bidEndDate = $_POST['bidEndDate'] ?? null;

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

        // Insert normal product
        if ($productType == "normal") {
            $addProductQuery = "INSERT INTO product (category_id, product_name, description, price, stock, discount, shipping_fee)
                                VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($addProductQuery);
            $stmt->bind_param(
                "issdidd",
                $subCategoryId,
                $productName,
                $description,
                $price,
                $stock,
                $discount,
                $shippingFee
            );
            $stmt->execute();
            $productId = $conn->insert_id; // Get the last inserted product ID

            // Insert product images if available
            if (!empty($uploadedImages)) {
                $addProductImageQuery = "INSERT INTO product_picture (product_id, picture_path, default_picture) VALUES (?, ?, ?)";
                $imageStmt = $conn->prepare($addProductImageQuery);

                foreach ($uploadedImages as $index => $imagePath) {
                    $defaultPicture = ($index === 0) ? 1 : 0; // Mark the first image as default
                    $imageStmt->bind_param("isi", $productId, $imagePath, $defaultPicture);
                    $imageStmt->execute();
                }
                $imageStmt->close();
            }
        }

        // Insert bidding product
        if ($productType == "bidding") {
            $addBiddingProductQuery = "INSERT INTO product (category_id, product_name, description, bid_starting_price, stock, shipping_fee, bid_activate)
                                       VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($addBiddingProductQuery);
            $bid_activate = 1;
            $stmt->bind_param(
                "issdidi",
                $subCategoryId,
                $productName,
                $description,
                $bidStartingPrice,
                $stock,
                $shippingFee,
                $bid_activate
            );
            $stmt->execute();
            $productId = $conn->insert_id;

            $addBiddingProductQuery = "INSERT INTO auction_history (product_id, starting_bid, start_time, end_time)
                                       VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($addBiddingProductQuery);
            $stmt->bind_param(
                "idss",
                $productId,
                $bidStartingPrice,
                $bidStartDate,
                $bidEndDate
            );
            $stmt->execute();

            // Insert product images if available
            if (!empty($uploadedImages)) {
                $addProductImageQuery = "INSERT INTO product_picture (product_id, picture_path, default_picture) VALUES (?, ?, ?)";
                $imageStmt = $conn->prepare($addProductImageQuery);
                foreach ($uploadedImages as $index => $imagePath) {
                    $defaultPicture = ($index === 0) ? 1 : 0; // Mark the first image as default
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
        $conn->rollback();
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }

    

    $stmt->close();
    $conn->close();
}
?>
