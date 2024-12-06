<?php
// Start the session to manage user authentication
session_start();

// Ensure the user is logged in as a student
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 3) {
    // Redirect to login page if not a student
    header("Location: login.php");
    exit();
}

// Set user_id from the session
$user_id = $_SESSION['user_id'];  // Ensure the user_id is initialized

// Include database connection
include('../includes/db_connection.php');

// Queries
try {
    // Get user name
    $user_query = "SELECT name FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($user_query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $user_result = $stmt->get_result();
    $user_data = $user_result->fetch_assoc();
    $user_name = $user_data['name'];  // Fetch the student's name

    // Get enrolled courses
    $courses_query = "SELECT COUNT(*) AS total_courses FROM enrollments WHERE user_id = ?";
    $stmt = $conn->prepare($courses_query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $courses_result = $stmt->get_result();
    $courses_data = $courses_result->fetch_assoc();
    $total_courses = $courses_data['total_courses'];

    // Get completed exams
    $completed_exams_query = "SELECT COUNT(*) AS completed_exams FROM exam_grades WHERE user_id = ?";
    $stmt = $conn->prepare($completed_exams_query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $completed_exams_result = $stmt->get_result();
    $completed_exams_data = $completed_exams_result->fetch_assoc();
    $total_completed_exams = $completed_exams_data['completed_exams'];

    // Get pending exams
    $pending_exams_query = "SELECT COUNT(*) AS pending_exams FROM exams WHERE exam_id NOT IN (SELECT exam_id FROM exam_grades WHERE user_id = ?)";
    $stmt = $conn->prepare($pending_exams_query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $pending_exams_result = $stmt->get_result();
    $pending_exams_data = $pending_exams_result->fetch_assoc();
    $total_pending_exams = $pending_exams_data['pending_exams'];

    // Get available exams
    $available_exams_query = "
    SELECT exams.exam_name, courses.course_name, exams.exam_id, courses.course_id
    FROM exams
    JOIN courses ON exams.course_id = courses.course_id
    JOIN enrollments ON courses.course_id = enrollments.course_id
    WHERE enrollments.user_id = ? 
      AND exams.exam_id NOT IN (
          SELECT exam_id 
          FROM exam_grades 
          WHERE user_id = ?
      )";
    $stmt = $conn->prepare($available_exams_query);
    $stmt->bind_param("ii", $user_id, $user_id);
    $stmt->execute();
    $available_exams_result = $stmt->get_result();  // Ensure to fetch the result

    // Get exam results
    $exam_results_query = "SELECT exams.exam_name, exam_grades.score 
                           FROM exam_grades 
                           JOIN exams ON exam_grades.exam_id = exams.exam_id
                           WHERE exam_grades.user_id = ?";
    $stmt = $conn->prepare($exam_results_query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $exam_results = $stmt->get_result();
} catch (Exception $e) {
    // Handle any errors (database connection/query failures)
    echo "Error: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Dashboard | ExamEase</title>
  <link rel="stylesheet" href="../assets/css/student_dashboard.css">
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <div class="sidebar-header">
      <h2>Student Dashboard</h2>
    </div>
    <ul class="sidebar-links">
      <li><a href="#">Exams</a></li>
      <li><a href="student_result.php">Results</a></li>
      <li><a href="enrollments.php">Enroll</a></li>
      <li><a href="student_profile.php">Profile</a></li>
      <li><a href="logout.php">Logout</a></li>
    </ul>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <!-- Dashboard Overview -->
    <section class="overview">
      <h1>Welcome, <?php echo htmlspecialchars($user_name); ?></h1>
      <div class="statistics">
        <div class="stat-box">
          <h3>Enrolled Courses</h3>
          <p><?php echo $total_courses; ?></p>
        </div>
        <div class="stat-box">
          <h3>Exams Completed</h3>
          <p><?php echo $total_completed_exams; ?></p>
        </div>
        <div class="stat-box">
          <h3>Pending Exams</h3>
          <p><?php echo $total_pending_exams; ?></p>
        </div>
      </div>
    </section>

    <!-- Available Exams Section -->
    <section class="available-exams">
      <h2>Available Exams</h2>
      <!-- Table for listing exams -->
      <table class="exams-table">
        <thead>
          <tr>
            <th>Exam Name</th>
            <th>Course</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($available_exams_result->num_rows > 0): ?>
            <?php while ($exam = $available_exams_result->fetch_assoc()) { ?>
              <tr>
                <td><?php echo htmlspecialchars($exam['exam_name']); ?></td>
                <td><?php echo htmlspecialchars($exam['course_name']); ?></td>
                <td>
                  <a href="student_exam.php?exam_id=<?php echo $exam['exam_id']; ?>&course_id=<?php echo $exam['course_id']; ?>" class="start-exam-btn">Start Exam</a>
                </td>
              </tr>
            <?php } ?>
          <?php else: ?>
            <tr>
              <td colspan="3">No available exams at the moment.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </section>

    <!-- Exam Results Section -->
    <section class="exam-results">
      <h2>Exam Results</h2>
      <table class="results-table">
        <thead>
          <tr>
            <th>Exam Name</th>
            <th>Score</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($exam_results->num_rows > 0): ?>
            <?php while ($result = $exam_results->fetch_assoc()) { ?>
              <tr>
                <td><?php echo htmlspecialchars($result['exam_name']); ?></td>
                <td><?php echo htmlspecialchars($result['score']); ?>%</td>
              </tr>
            <?php } ?>
          <?php else: ?>
            <tr>
              <td colspan="2">No exam results available.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </section>

  </div>

  <script src="../assets/js/student_dashboard.js"></script>

</body>
</html>

<?php
// Close database connection
$conn->close();
?>
