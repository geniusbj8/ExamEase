<?php
// Include database connection
include('../includes/db_connection.php');

// Validate exam ID
if (isset($_GET['exam_id']) && is_numeric($_GET['exam_id'])) {
    $exam_id = intval($_GET['exam_id']);

    // Fetch results from the exam_grades table
    $stmt = $conn->prepare("
        SELECT 
            u.name AS student_name, 
            eg.score 
        FROM 
            exam_grades eg
        JOIN 
            users u ON eg.user_id = u.user_id
        WHERE 
            eg.exam_id = ?
    ");
    $stmt->bind_param("i", $exam_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    $stmt->close();
    echo json_encode($data);
} else {
    echo json_encode(['error' => 'Invalid exam ID.']);
}

$conn->close();
?>
