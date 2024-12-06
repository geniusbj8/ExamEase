<?php
// Include database connection
include('../includes/db_connection.php');

// Get the exam_id from the URL parameter
$exam_id = isset($_GET['exam_id']) ? $_GET['exam_id'] : null;

if ($exam_id) {
    // Prepare and execute query to get results for the selected exam
    $stmt = $conn->prepare("
        SELECT u.name AS student_name, r.score
        FROM results r
        JOIN users u ON r.user_id = u.user_id
        WHERE r.exam_id = ?
    ");
    $stmt->bind_param("i", $exam_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $results = [];
    while ($row = $result->fetch_assoc()) {
        $results[] = $row;
    }

    // Check if results were found
    if (empty($results)) {
        echo json_encode(["error" => "No results found for this exam."]);
    } else {
        echo json_encode($results);
    }

    // Close the statement and connection
    $stmt->close();
} else {
    echo json_encode(["error" => "Exam ID is missing."]);
}

$conn->close();
?>
