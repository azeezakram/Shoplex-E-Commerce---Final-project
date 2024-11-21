<?php
include('php-config/ssession-config.php');
include('php-config/db-conn.php');

// Handle AJAX request to check if email exists
if (isset($_GET['email'])) {
    $email = $_GET['email'];

    $sql = "SELECT * FROM user WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    echo json_encode(['exists' => $result->num_rows > 0]);

    $stmt->close();
    $conn->close();
    exit(); // End script execution for AJAX requests
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $user_type_id = $_POST['user_type_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Encrypt password
    $profile_picture = $_FILES['profile_picture']['name'];
    $target_dir = "uploads/"; // Directory to save profile pictures
    $target_file = $target_dir . basename($_FILES['profile_picture']['name']);

    // Move uploaded file to the server
    if (!empty($profile_picture) && move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file)) {
        $profile_picture_path = $target_file;
    } else {
        $profile_picture_path = null; // No image uploaded
    }

    // Insert user into the database
    $sql = "INSERT INTO user (user_type_id, name, email, password, profile_picture) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issss", $user_type_id, $name, $email, $password, $profile_picture_path);

    if ($stmt->execute()) {
        echo "<script>alert('User added successfully!'); window.location.href = 'user-page.php';</script>";
    } else {
        echo "<script>alert('Error adding user: " . $stmt->error . "');</script>";
    }

    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/add-user.css">
</head>
<body>
<?php include('navbar.php'); ?>
    <div class="form-container">
        <h2>Add New User</h2>
        <form action="add-user.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="user_type_id">User Type:</label>
                <select name="user_type_id" id="user_type_id" required>
                    <option value="1">Admin</option>
                    <option value="2">Buyer</option>
                </select>
            </div>
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required>
            </div>
            <div class="form-group">
                <label for="profile_picture">Profile Picture:</label>
                <input type="file" name="profile_picture" id="profile_picture" accept="image/*">
            </div>
            <button type="submit" class="submit-btn">Add User</button>
        </form>
        <a href="user-page.php" class="back-btn">Back to User Management</a>
    </div>

<script type="module" src="javascript/add-user.js"></script>
</body>
</html>
