<?php

// Include database connection
include('../includes/db_connection.php');

// Check if course_id is passed and not empty
if (isset($_GET['course_id']) && !empty($_GET['course_id'])) {
    $course_id = $_GET['course_id'];

    // Prepare the query to fetch exams based on course_id
    if ($stmt = $conn->prepare("SELECT exam_id, exam_name FROM exams WHERE course_id = ?")) {
        $stmt->bind_param("i", $course_id);
        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch all exams as an associative array
        $exams = [];
        while ($row = $result->fetch_assoc()) {
            $exams[] = $row;
        }

        // Set header for JSON response
        header('Content-Type: application/json');
        echo json_encode($exams);

        // Close statement and connection
        $stmt->close();
    } else {
        // Error handling if the query fails
        http_response_code(500);
        echo json_encode(["error" => "Failed to prepare the SQL statement"]);
    }
} else {
    http_response_code(400);
    echo json_encode(["error" => "Invalid course ID"]);
}

$conn->close();
?>
