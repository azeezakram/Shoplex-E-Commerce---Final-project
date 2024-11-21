<?php
include('php-config/ssession-config.php');
include('php-config/db-conn.php');
session_start();

$email = $_POST['email'];
$password = $_POST['password'];

// Initialize the response array
$response = ['success' => false, 'message' => '', 'email_exists' => false];

if ($email && $password) {
    // Prepare and execute the statement to check for the email
    $stmt = $conn->prepare("SELECT * FROM user WHERE email = ? AND user_type_id = ? LIMIT 1");
    $user_type_id = 4;
    $stmt->bind_param('si', $email, $user_type_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the email exists
    if ($result && $result->num_rows > 0) {
        $user_data = $result->fetch_assoc();
        $response['email_exists'] = true; // Set email existence to true

        // Verify the password
        if (password_verify($password, $user_data['password'])) {
            // Password is correct, set session variables
            $_SESSION['user_id'] = $user_data['user_id'];
            $_SESSION['name'] = $user_data['name'];

            // Set cookies to expire in 30 days
            setcookie('user_id', $user_data['user_id'], time() + (86400 * 30), "/"); // 86400 = 1 day
            setcookie('user_name', $user_data['name'], time() + (86400 * 30), "/");

            // Update the last_login timestamp
            $update_stmt = $conn->prepare("UPDATE user SET last_login = NOW() WHERE user_id = ?");
            $update_stmt->bind_param('i', $user_data['user_id']);
            $update_stmt->execute();

            // Include user data in response for localStorage
            $response['is_admin'] = $user_data['user_type_id'] === 4; // Assume user_type_id 1 is admin
            $response['success'] = true;
            $response['user_id'] = $user_data['user_id'];
            $response['user_name'] = $user_data['name'];
        } else {
            // Password is incorrect
            $response['message'] = 'Incorrect password';
        }
    } else {
        // Email does not exist in the database
        $response['message'] = 'Email not found';
    }
} else {
    // Missing email or password
    $response['message'] = 'Missing email or password';
}

// Return the JSON response
echo json_encode($response);
exit;
?>
