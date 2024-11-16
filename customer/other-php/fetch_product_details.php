<?php
include '../php-config/db-conn.php';

if (isset($_GET['product_id'])) {
    $productId = (int)$_GET['product_id'];

    // Validate product_id
    if ($productId <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid product ID.']);
        exit;
    }

    // Fetch product details
    $productQuery = $conn->prepare("SELECT * FROM product WHERE product_id = ?");
    $productQuery->bind_param("i", $productId);
    $productQuery->execute();
    $productResult = $productQuery->get_result();

    if ($productResult->num_rows > 0) {
        $product = $productResult->fetch_assoc();

        // Fetch all product images
        $imagesQuery = $conn->prepare("SELECT picture_path FROM product_picture WHERE product_id = ?");
        $imagesQuery->bind_param("i", $productId);
        $imagesQuery->execute();
        $imagesResult = $imagesQuery->get_result();

        $pictures = [];
        while ($image = $imagesResult->fetch_assoc()) {
            $pictures[] = $image['picture_path'];
        }

        // Calculate discounted price
        $price = (float)$product['price'];
        $discount = (float)$product['discount'];
        $discountedPrice = $discount > 0 ? $price - ($price * $discount) : $price;
        $stock = (int)$product['stock'];
        $shippingFee = (float)$product['shipping_fee'];



        $reviewQuery = "SELECT * FROM product_review WHERE product_id = $productId";

        $reviewResult = $conn->query($reviewQuery);


        $totalRating = 0;
        $count = 0;


        $totalRating = 0;
        $count = 0;

        // Default product rating if no reviews
        $productRating = 0;

        if ($reviewResult && $reviewResult->num_rows > 0) {
            while ($reviewRow = $reviewResult->fetch_assoc()) {
                $totalRating += (int)$reviewRow["rating"];
                $count++;
            }

            // Calculate average rating
            if ($count > 0) {
                $productRating = floatval($totalRating / $count);
            }
        }





        // Prepare response
        $response = [
            'success' => true,
            'productId' => $product['product_id'],
            'product_name' => $product['product_name'],
            'description' => $product['description'],
            'product_rating' => $productRating,
            'product_review_count' => $count,
            'original_price' => number_format($price, 2),
            'discounted_price' => number_format($discountedPrice, 2),
            'discount_percentage' => $discount > 0 ? $discount * 100 : 0,
            'pictures' => $pictures,
            'stock' => $stock,
            'shipping_fee' => number_format($shippingFee, 2),
        ];
    } else {
        $response = ['success' => false, 'message' => 'Product not found.'];
    }

    echo json_encode($response);
} else {
    echo json_encode(['success' => false, 'message' => 'No product ID provided.']);
}
