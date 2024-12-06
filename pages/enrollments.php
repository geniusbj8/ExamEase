<?php
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
include('../includes/db_connection.php'); // This file contains your mysqli connection setup

// Fetch courses that the student is already enrolled in
$query_enrolled_courses = "
    SELECT c.course_id, c.course_name
    FROM courses c
    JOIN enrollments e ON c.course_id = e.course_id
    WHERE e.user_id = ?";

$stmt = $conn->prepare($query_enrolled_courses);
$stmt->bind_param("i", $user_id);  // "i" for integer
$stmt->execute();
$result_enrolled_courses = $stmt->get_result();
$enrolled_courses = $result_enrolled_courses->fetch_all(MYSQLI_ASSOC);

// Fetch all available courses that the student has not enrolled in yet
$query_available_courses = "
    SELECT c.course_id, c.course_name
    FROM courses c
    WHERE c.course_id NOT IN (SELECT course_id FROM enrollments WHERE user_id = ?)";
  
$stmt = $conn->prepare($query_available_courses);
$stmt->bind_param("i", $user_id);  // "i" for integer
$stmt->execute();
$result_available_courses = $stmt->get_result();
$available_courses = $result_available_courses->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Enrollment</title>
    <link rel="stylesheet" href="../assets/css/enrollments.css">
</head>
<body>
    <h1>Welcome to your Enrollment Dashboard</h1>

    <h2>Your Enrolled Courses</h2>
    <?php if (count($enrolled_courses) > 0): ?>
        <ul>
            <?php foreach ($enrolled_courses as $course): ?>
                <li><?php echo htmlspecialchars($course['course_name']); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>You are not enrolled in any courses yet.</p>
    <?php endif; ?>

    <h2>Available Courses for Enrollment</h2>
    <?php if (count($available_courses) > 0): ?>
        <form action="enroll.php" method="post">
            <select name="course_id" required>
                <option value="">Select a course to enroll in</option>
                <?php foreach ($available_courses as $course): ?>
                    <option value="<?php echo $course['course_id']; ?>"><?php echo htmlspecialchars($course['course_name']); ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Enroll</button>
        </form>
    <?php else: ?>
        <p>You are already enrolled in all available courses.</p>
    <?php endif; ?>

    <!-- Return to Dashboard Button -->
    <a href="student_dashboard.php">
        <button class="return-to-dashboard" type="button">Return to Dashboard</button>
    </a>
</body>
</html>
