<?php
session_start();

// Ensure the user is logged in as a student
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 3) {
    // Redirect to login page if not a student
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];  // Ensure the user_id is initialized
$course_id = $_POST['course_id']; // Get the course ID from the form

// Include database connection
include('../includes/db_connection.php');

// Insert the enrollment record
$query_enroll = "
    INSERT INTO enrollments (user_id, course_id)
    VALUES (?, ?)";

// Prepare and bind the query with mysqli
$stmt = $conn->prepare($query_enroll);
$stmt->bind_param("ii", $user_id, $course_id);  // "ii" means both parameters are integers
$stmt->execute();

// Check if the insert was successful
if ($stmt->affected_rows > 0) {
    // Enrollment was successful, redirect to the enrollments page
    header("Location: enrollments.php");
    exit();
} else {
    // Enrollment failed
    echo "Error: Enrollment failed.";
}

// Close the statement
$stmt->close();
?>
