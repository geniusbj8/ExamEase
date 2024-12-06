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

// Fetch the logged-in admin's name
$userId = $_SESSION['user_id'];
$sql = "SELECT name FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$adminName = $user['name'];

// Fetch dashboard statistics in a single query
$statsQuery = "
    SELECT 
        (SELECT COUNT(*) FROM users) AS total_users,
        (SELECT COUNT(*) FROM courses) AS total_courses,
        (SELECT COUNT(*) FROM exams) AS total_exams,
        (SELECT COUNT(*) FROM exam_grades) AS total_results
";
$statsResult = $conn->query($statsQuery);
if ($statsResult) {
    $stats = $statsResult->fetch_assoc(); // This should return a single row
} else {
    // Handle error in case the query fails
    $stats = [
        'total_users' => 0,
        'total_courses' => 0,
        'total_exams' => 0,
        'total_results' => 0
    ];
}

// Fetch users data for management
$userDataQuery = "SELECT user_id, name, email, role_id FROM users";
$userDataResult = $conn->query($userDataQuery);

// Handle user actions like edit or delete
if (isset($_GET['action'])) {
    $user_id = $_GET['user_id'];
    switch ($_GET['action']) {
        case 'edit':
            // Handle editing logic here (you can redirect to a form)
            header("Location: edit_user.php?user_id=" . $user_id);
            break;

        case 'delete':
            // First delete from enrollments
            $stmt = $conn->prepare("DELETE FROM enrollments WHERE user_id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();

            // Then delete the user
            $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
            $stmt->bind_param("i", $user_id);
            if ($stmt->execute()) {
                $_SESSION['message'] = 'User and related enrollments deleted successfully.';
            } else {
                $_SESSION['message'] = 'Error deleting user.';
            }

            // Redirect back to admin_dashboard.php
            header("Location: admin_dashboard.php");
            exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard | ExamEase</title>
  <link rel="stylesheet" href="../assets/css/admin_dashboard.css">
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <div class="sidebar-header">
      <h2>Admin Dashboard</h2>
    </div>
    <ul class="sidebar-links">
      <li><a href="#">Dashboard</a></li>
      <li><a href="#">Manage Users</a></li>
      <li><a href="manage_course.php">Manage Courses</a></li>
      <li><a href="manage_exam.php">Manage Exams</a></li>
      <li><a href="report.php">Reports</a></li>
      <li><a href="admin_profile.php">Profile</a></li>
      <li><a href="logout.php">Logout</a></li>
    </ul>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <!-- Welcome message with admin's name -->
    <p class="welcome">Welcome, <?php echo htmlspecialchars($adminName); ?>!</p>

    <!-- Dashboard Overview -->
    <section class="overview">
      <h1>Dashboard Overview</h1>
      <div class="statistics">
        <div class="stat-box">
          <h3>Users</h3>
          <p><?php echo htmlspecialchars($stats['total_users']); ?></p>
        </div>
        <div class="stat-box">
          <h3>Courses</h3>
          <p><?php echo htmlspecialchars($stats['total_courses']); ?></p>
        </div>
        <div class="stat-box">
          <h3>Exams</h3>
          <p><?php echo htmlspecialchars($stats['total_exams']); ?></p>
        </div>
        <div class="stat-box">
          <h3>Results</h3>
          <p><?php echo htmlspecialchars($stats['total_results']); ?></p>
        </div>
      </div>
    </section>

    <!-- Manage Users Section -->
    <section class="manage-users">
      <h2>Manage Users</h2>
      <a href="add_user.php"><button class="add-user-btn">Add User</button></a>

      <!-- Table for managing users -->
      <table class="users-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
  <?php
  // Display user data dynamically from the database
  if ($userDataResult->num_rows > 0) {
      while ($user = $userDataResult->fetch_assoc()) {
          // Determine role based on role_id
          $roleName = '';
          switch ($user['role_id']) {
              case 1:
                  $roleName = 'Admin';
                  break;
              case 2:
                  $roleName = 'Instructor';
                  break;
              case 3:
                  $roleName = 'Student';
                  break;
              default:
                  $roleName = 'Unknown'; 
          }

          echo "<tr>
                <td>{$user['user_id']}</td>
                <td>{$user['name']}</td>
                <td>{$user['email']}</td>
                <td>{$roleName}</td>
                <td>
                  <a href='?action=edit&user_id={$user['user_id']}'><button>Edit</button></a>
                  <a href='?action=delete&user_id={$user['user_id']}'><button>Delete</button></a>
                </td>
              </tr>";
      }
  } else {
      echo "<tr><td colspan='5'>No users found.</td></tr>";
  }
  ?>
        </tbody>
      </table>
    </section>
  </div>

  <script src="../assets/js/admin_dashboard.js"></script>
  
  <!-- Message after actions like delete -->
  <?php
  if (isset($_SESSION['message'])) {
      $message = htmlspecialchars($_SESSION['message']);
      echo "<script>alert('$message');</script>";
      unset($_SESSION['message']);
  }
  ?>

</body>
</html>
