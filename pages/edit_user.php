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

// Get user ID from URL
if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    // Fetch the user data from the database
    $sql = "SELECT user_id, name, email, role_id FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        $_SESSION['message'] = 'User not found.';
        header("Location: admin_dashboard.php");
        exit();
    }
}

// Handle form submission to update user details
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_user'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    // Update the user in the database
    $sql = "UPDATE users SET name = ?, email = ?, role_id = ? WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssii", $name, $email, $role, $user_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = 'User updated successfully!';
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $_SESSION['message'] = 'Error updating user.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit User || Admin Dashboard</title>
  <link rel="stylesheet" href="../assets/css/manage_users.css">
</head>
<body>

  <!-- Main Content -->
  <div class="content">
    <h1>Edit User</h1>

    <!-- Display Message -->
    <?php if (isset($_SESSION['message'])): ?>
      <p class="message"><?= $_SESSION['message'] ?></p>
      <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <!-- Edit User Form -->
    <section class="user-form">
      <form method="POST" action="">
        <label for="name">Name</label>
        <input type="text" id="name" name="name" value="<?= $user['name'] ?>" required>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?= $user['email'] ?>" required>

        <label for="role">Role</label>
        <select id="role" name="role" required>
          <option value="1" <?= $user['role_id'] == 1 ? 'selected' : '' ?>>Admin</option>
          <option value="2" <?= $user['role_id'] == 2 ? 'selected' : '' ?>>Instructor</option>
          <option value="3" <?= $user['role_id'] == 3 ? 'selected' : '' ?>>Student</option>
        </select>

        <button type="submit" name="edit_user">Update User</button>
      </form>
    </section>
  </div>

  <script src="../assets/js/manage_user.js"></script>

</body>
</html>
