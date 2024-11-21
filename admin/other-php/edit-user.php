<?php 
include '../php-config/db-conn.php';
include '../php-config/ssession-config.php';
session_start();

// Check if a user ID is provided
if (!isset($_GET['user_id']) || empty($_GET['user_id'])) {
    die('User ID is missing.');
}

$userId = intval($_GET['user_id']);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $userType = $_POST['user_type'];
    $password = $_POST['password']; // New password field

    // Hash the password before updating it
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Update user details in the database
    $sql = "UPDATE user SET name = ?, email = ?, user_type_id = ?, password = ? WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssisi', $name, $email, $userType, $hashedPassword, $userId);

    if ($stmt->execute()) {
        header('Location: ../user-page.php'); // Redirect to user-management after editing
        exit;
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

// Fetch user details to prefill the form
$sql = "SELECT user_id, name, email, user_type_id , password FROM user WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $userId);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user) {
    die('User not found.');
}

// Fetch all user types to populate the dropdown
$sqlUserTypes = "SELECT user_type_id, type_name FROM user_type"; // Fetching user types from the `user_type` table
$resultUserTypes = $conn->query($sqlUserTypes);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/edit-userpage.css">
</head>
<body>
<?php include('../navbar.php'); ?>
    <div class="container">
        <h2>Edit User</h2>
        <form action="" method="POST">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
            <br>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            <br>
            
            <label for="user_type">User Type:</label>
            <select id="user_type" name="user_type" required>
                <?php 
                // Loop through user types to create the dropdown options
                while ($row = $resultUserTypes->fetch_assoc()) {
                    $selected = ($row['user_type_id'] == $user['user_type_id']) ? 'selected' : ''; // Pre-select the current user type
                    echo "<option value='" . $row['user_type_id'] . "' $selected>" . htmlspecialchars($row['type_name']) . "</option>";
                }
                ?>
            </select>
            <br>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" value="<?php echo htmlspecialchars($user['password']); ?>" required>
            <br>
            
            <button type="submit">Update</button>
            <a href="../user-page.php" class="cancel-btn">Cancel</a>
        </form>
    </div>

    <script type="module" src="../javascript/add-user.js"></script>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
