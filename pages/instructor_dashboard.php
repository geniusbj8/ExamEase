<?php
// Start the session to manage user authentication
session_start();

// Check if the user is logged in and has instructor privileges
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 2) {
    // If not an instructor, redirect to login page
    header("Location: login.php");
    exit();
}

// Include database connection
include('../includes/db_connection.php');

// Fetch the instructor's name from the database using the user_id from the session
$user_id = $_SESSION['user_id'];
$sql = "SELECT name FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch instructor's name
$instructor = $result->fetch_assoc();
$instructor_name = $instructor['name'];

// Fetch dashboard statistics
$courseCountQuery = "SELECT COUNT(*) AS total_courses FROM courses";
$examCountQuery = "SELECT COUNT(*) AS total_exams FROM exams";
$resultCountQuery = "SELECT COUNT(*) AS total_results FROM exam_grades";

// Execute the queries
$courseCountResult = $conn->query($courseCountQuery);
$examCountResult = $conn->query($examCountQuery);
$resultCountResult = $conn->query($resultCountQuery);

// Fetch the results from each query
$courseCount = $courseCountResult->fetch_assoc()['total_courses'];
$examCount = $examCountResult->fetch_assoc()['total_exams'];
$resultCount = $resultCountResult->fetch_assoc()['total_results'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Instructor Dashboard | ExamEase</title>
  <link rel="stylesheet" href="../assets/css/instructor_dashboard.css">
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <div class="sidebar-header">
      <h2>Instructor Dashboard</h2>
    </div>
    <ul class="sidebar-links">
      <li><a href="instructor_dashboard.php">Dashboard</a></li>
      <li><a href="manage_course.php">Manage Courses</a></li>
      <li><a href="manage_exam.php">Manage Exams</a></li>
      <li><a href="manage_questions.php">Manage Questions</a></li>
      <li><a href="view_students.php">View Students</a></li>
      <li><a href="report.php">Report</a></li>
      <li><a href="instructor_profile.php">Profile</a></li>
      <li><a href="logout.php">Logout</a></li>
    </ul>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <!-- Personalized welcome message -->
    <p class="welcome">Welcome, <?php echo htmlspecialchars($instructor_name); ?>!</p>

    <!-- Dashboard Overview -->
    <section class="overview">
      <h1>Dashboard Overview</h1>
      <div class="statistics">
        <div class="stat-box">
          <h3>Courses</h3>
          <p><?php echo $courseCount; ?></p>
        </div>
        <div class="stat-box">
          <h3>Exams</h3>
          <p><?php echo $examCount; ?></p>
        </div>
        <div class="stat-box">
          <h3>Results</h3>
          <p><?php echo $resultCount; ?></p>
        </div>
      </div>
    </section>
  </div>

  <script src="../assets/js/instructor_dashboard.js"></script>
  
  <?php
  if (isset($_SESSION['message'])) {
      // If the session message is an array, convert it to a string
      $message = is_array($_SESSION['message']) ? implode(', ', $_SESSION['message']) : $_SESSION['message'];
      echo "<script>
              alert('$message');
            </script>";
      unset($_SESSION['message']);
  }
  ?>

</body>
</html>
