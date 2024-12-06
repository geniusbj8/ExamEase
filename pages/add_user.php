<?php
// Start the session to manage user authentication
session_start();

// Check if the user is logged in and has admin privileges
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    // If not an admin, redirect to login page
    header("Location: login.php");
    exit();
}

// Include database connection
include('../includes/db_connection.php');

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_user'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $username = $_POST['username']; // Add the username field

    // Determine the password based on the role
    $password = '';
    switch ($role) {
        case 1: // Admin
            $password = 'adminpassword';
            break;
        case 2: // Instructor
            $password = 'instructorpassword';
            break;
        case 3: // Student
            $password = 'studentpassword';
            break;
        default:
            $password = 'defaultpassword'; // Fallback password
            break;
    }

    // Hash the password using SHA2 (256)
    $hashedPassword = hash('sha256', $password);

    // Check if username already exists
    $sql = "SELECT user_id FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['message'] = 'Username already exists.';
    } else {
        // Insert the new user into the database (use password_hash column)
        $sql = "INSERT INTO users (name, email, role_id, username, password_hash) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssiss", $name, $email, $role, $username, $hashedPassword); // Bind the username and password hash

        if ($stmt->execute()) {
            $_SESSION['message'] = 'User added successfully!';
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $_SESSION['message'] = 'Error adding user.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add User || Admin Dashboard</title>
  <link rel="stylesheet" href="../assets/css/manage_users.css">
</head>
<body>

  <!-- Main Content -->
  <div class="content">
    <h1>Add New User</h1>

    <!-- Display Message -->
    <?php if (isset($_SESSION['message'])): ?>
      <p class="message"><?= $_SESSION['message'] ?></p>
      <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <!-- Add User Form -->
    <section class="user-form">
      <form method="POST" action="">
        <label for="name">Name</label>
        <input type="text" id="name" name="name" required>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>

        <label for="username">Username</label> <!-- Add username field -->
        <input type="text" id="username" name="username" required>

        <label for="role">Role</label>
        <select id="role" name="role" required>
          <option value="1">Admin</option>
          <option value="2">Instructor</option>
          <option value="3">Student</option>
        </select>

        <button type="submit" name="add_user">Add User</button>
      </form>
    </section>
    <a href="admin_dashboard.php">Back to Admin Dashboard</a>
  </div>

  <script src="../assets/js/manage_user.js"></script>

</body>
</html>
