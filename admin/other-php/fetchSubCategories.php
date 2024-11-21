<?php
include '../php-config/db-conn.php';  // Include DB connection

if (isset($_GET['parent_id'])) {
    $parentId = $_GET['parent_id'];

    // Prepare the query to fetch subcategories
    $subCategoriesQuery = "SELECT category_id, category_name FROM category WHERE parent_category_id = ?";
    $stmt = $conn->prepare($subCategoriesQuery);
    $stmt->bind_param("i", $parentId);
    $stmt->execute();
    $result = $stmt->get_result();

    $subCategories = [];
    while ($row = $result->fetch_assoc()) {
        $subCategories[] = [
            'category_id' => $row['category_id'],
            'category_name' => $row['category_name']
        ];
    }

    // Return subcategories as JSON response
    echo json_encode($subCategories);
}
?>



