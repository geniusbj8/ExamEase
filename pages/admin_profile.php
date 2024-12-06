<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    header("Location: login.php");
    exit();
}

include('../includes/db_connection.php');

$user_id = $_SESSION['user_id'];

$name = $username = $email = "";
$success_msg = $error_msg = "";

try {
    // Fetch user details (using password_hash column)
    $query = "SELECT name, username, email, password_hash FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user_data = $result->fetch_assoc();
        $name = $user_data['name'];
        $username = $user_data['username'];
        $email = $user_data['email'];
        $hashed_password = $user_data['password_hash'];  // Correct column name
    } else {
        $error_msg = "User details not found. Please try again.";
    }
} catch (Exception $e) {
    $error_msg = "Error fetching profile details: " . $e->getMessage();
}

// Handle form submission (unchanged)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $updated_name = trim($_POST['name']);
    $updated_username = trim($_POST['username']);
    $updated_email = trim($_POST['email']);
    $current_password = trim($_POST['current_password']);
    $updated_password = isset($_POST['new_password']) ? trim($_POST['new_password']) : "";

    if ($updated_name && $updated_username && $updated_email && $current_password) {
        if (hash('sha256', $current_password) === $hashed_password) {
            try {
                // Update name, username, and email
                $update_query = "UPDATE users SET name = ?, username = ?, email = ? WHERE user_id = ?";
                $stmt = $conn->prepare($update_query);
                $stmt->bind_param("sssi", $updated_name, $updated_username, $updated_email, $user_id);

                if ($stmt->execute()) {
                    $success_msg = "Profile updated successfully.";
                    $name = $updated_name;
                    $username = $updated_username;
                    $email = $updated_email;
                } else {
                    $error_msg = "Failed to update profile.";
                }

                // Update password if provided
                if ($updated_password) {
                    $new_hashed_password = hash('sha256', $updated_password);
                    $password_query = "UPDATE users SET password_hash = ? WHERE user_id = ?";  // Correct column name
                    $stmt = $conn->prepare($password_query);
                    $stmt->bind_param("si", $new_hashed_password, $user_id);

                    if ($stmt->execute()) {
                        $success_msg .= " Password updated successfully.";
                    } else {
                        $error_msg = "Failed to update password.";
                    }
                }
            } catch (Exception $e) {
                $error_msg = "Error updating profile: " . $e->getMessage();
            }
        } else {
            $error_msg = "Current password is incorrect.";
        }
    } else {
        $error_msg = "All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile | ExamEase</title>
    <link rel="stylesheet" href="../assets/css/profile.css">
</head>
<body>
    <div class="container">
        <!-- Back to Dashboard Button -->
        <a href="admin_dashboard.php" class="back-button">Back to Dashboard</a>
        <h1>Your Profile</h1>

        <!-- Success and Error Messages -->
        <?php if ($success_msg): ?>
            <p class="success-msg"><?php echo htmlspecialchars($success_msg); ?></p>
        <?php endif; ?>
        <?php if ($error_msg): ?>
            <p class="error-msg"><?php echo htmlspecialchars($error_msg); ?></p>
        <?php endif; ?>

        <!-- Profile Form -->
        <form method="POST" action="admin_profile.php" class="profile-form">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required>

            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>

            <label for="current_password">Current Password:</label>
            <div class="password-toggle">
                <input type="password" id="current_password" name="current_password" required>
                <button type="button" onclick="togglePassword('current_password')">Show</button>
            </div>

            <label for="new_password">New Password (optional):</label>
            <div class="password-toggle">
                <input type="password" id="new_password" name="new_password">
                <button type="button" onclick="togglePassword('new_password')">Show</button>
            </div>

            <button type="submit">Update Profile</button>
        </form>
    </div>

    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>
</body>
</html>

<?php
$conn->close();
?>
