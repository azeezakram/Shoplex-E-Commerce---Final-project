<?php 
include('php-config/db-conn.php');
include('php-config/ssession-config.php');
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Fetch user details from the database
$userid = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if profile image is being uploaded
    if (isset($_FILES['profile_image'])) {
        $image = $_FILES['profile_image'];

        // Validate file type
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($image['type'], $allowedTypes)) {
            echo "<script>alert('Only JPG, PNG, and GIF files are allowed.');</script>";
            exit();
        }

        // Upload the image
        $targetDir = "profile_images/";
        $fileName = uniqid() . "-" . basename($image['name']);
        $targetFile = $targetDir . $fileName;

        if (move_uploaded_file($image['tmp_name'], $targetFile)) {
            $query = "UPDATE user SET profile_picture = ? WHERE user_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("si", $fileName, $userid);

            if ($stmt->execute()) {
                echo "<script>alert('Profile image updated successfully.'); window.location.href='profile.php';</script>";
            } else {
                echo "<script>alert('Failed to update database.');</script>";
            }
        } else {
            echo "<script>alert('Failed to upload file.');</script>";
        }
    } else {
        // Update profile details
        $name = $_POST['name'];
        $email = $_POST['email'];
        $address = $_POST['address'];
        $contact = $_POST['contact'];

        if (empty($name) || empty($email)) {
            echo "<script>alert('Name and Email cannot be empty.'); window.location.href='profile.php';</script>";
            exit();
        }

        $query = "UPDATE user SET name = ?, email = ?, address = ?, contact = ? WHERE user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssi", $name, $email, $address, $contact, $userid);

        if ($stmt->execute()) {
            echo "<script>alert('Profile updated successfully.'); window.location.href='profile.php';</script>";
        } else {
            echo "<script>alert('Failed to update profile.'); window.location.href='profile.php';</script>";
        }
    }
}

// Fetch user details to display
$query = "SELECT * FROM user WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userid);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="css/user-profil.css">
</head>
<body>
    <div class="profile-container">
        <div class="profile-sidebar">
            <div class="avatar">
                <img src="profile_images/<?php echo htmlspecialchars($user['profile_picture'] ?? 'default-avatar.png'); ?>" alt="User Avatar">
                <button onclick="toggleImageUpload()" class="change-image-btn">Change Image</button>
                <form id="uploadForm" method="POST" action="profile.php" enctype="multipart/form-data" style="display: none;">
                    <input type="file" name="profile_image" accept="image/*" required>
                    <button type="submit">Upload</button>
                </form>
            </div>
            <div class="menu">
                <button onclick="showSection('information')">INFORMATION</button>
                <button onclick="showSection('profileUpdate')">PROFILE UPDATE</button>
                <button onclick="showSection('sendText')">SEND TEXT</button>
                <button onclick="showSection('messages')">MESSAGES</button>
 
            </div>
        </div>
        
        <div class="profile-details">
            <!-- Information Section -->
            <div id="information" class="section">
                <p><strong>Name:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                <p><strong>Address:</strong> <?php echo htmlspecialchars($user['address'] ?? 'Not provided'); ?></p>
                <p><strong>Contact:</strong> <?php echo htmlspecialchars($user['contact'] ?? 'Not provided'); ?></p>
            </div>
            
            <!-- Profile Update Section -->
            <div id="profileUpdate" class="section" style="display: none;">
                <h2>Update Profile</h2>
                <form method="POST" action="profile.php">
                    <label>Name:</label>
                    <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" /><br>
                    <label>Email:</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" /><br>
                    <label>Address:</label>
                    <input type="text" name="address" value="<?php echo htmlspecialchars($user['address'] ?? ''); ?>" /><br>
                    <label>Contact:</label>
                    <input type="text" name="contact" value="<?php echo htmlspecialchars($user['contact'] ?? ''); ?>" /><br>
                    <button type="submit">Save Changes</button>
                </form>
            </div>

            <!-- Additional Sections -->
            <div id="sendText" class="section" style="display: none;">
                <h2>Send Text</h2>
                <p>This is the send text section.</p>
            </div>
            <div id="messages" class="section" style="display: none;">
                <h2>Messages</h2>
                <p>This is the messages section.</p>
            </div>
            <div id="myCart" class="section" style="display: none;">
                <h2>My Cart</h2>
                <p>This is the cart section.</p>
            </div>
            <div id="logout" class="section" style="display: none;">
                <h2>Logout</h2>
                <p>Are you sure you want to logout?</p>
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </div>

    <script>
        function showSection(sectionId) {
            document.querySelectorAll('.section').forEach(section => {
                section.style.display = 'none';
            });
            document.getElementById(sectionId).style.display = 'block';
        }

        function toggleImageUpload() {
            const form = document.getElementById('uploadForm');
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }
    </script>
</body>
</html>
