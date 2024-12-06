<?php
// Start the session to get user information
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 3) {
    // Redirect to login page if not a student
    header("Location: login.php");
    exit();
}

// Set user_id from session
$user_id = $_SESSION['user_id'];

// Include database connection
include('../includes/db_connection.php');

// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['exam_id'], $_POST['answer'])) {
    $exam_id = $_POST['exam_id'];
    $answers = $_POST['answer']; // Array of question_id => answer_id

    // Initialize variables
    $correct_answers = 0; // To count correct answers
    $total_questions = count($answers); // Total questions answered

    // Prepare SQL statement for inserting student answers
    $stmt = $conn->prepare("
        INSERT INTO student_answers (user_id, exam_id, question_id, answer_id, answer_text, selected_at)
        VALUES (?, ?, ?, ?, ?, CURRENT_TIMESTAMP)
    ");
    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }

    foreach ($answers as $question_id => $answer_id) {
        $question_id = intval($question_id);
        $answer_id = intval($answer_id);

        // Check if the answer_id exists in the answers table
        $check_answer_stmt = $conn->prepare("
            SELECT answer_id, is_correct 
            FROM answers 
            WHERE answer_id = ? AND question_id = ?
        ");
        $check_answer_stmt->bind_param("ii", $answer_id, $question_id);
        $check_answer_stmt->execute();
        $check_answer_stmt->store_result();

        if ($check_answer_stmt->num_rows > 0) {
            // If the answer exists, fetch the correctness
            $check_answer_stmt->bind_result($valid_answer_id, $is_correct);
            $check_answer_stmt->fetch();

            // Set answer_text to NULL if it's a multiple-choice answer
            $answer_text = NULL;

            // If it's a correct answer, increase the count
            if ($is_correct == 1) {
                $correct_answers++;
            }

            // Insert answer into student_answers table
            $stmt->bind_param("iiiss", $user_id, $exam_id, $question_id, $valid_answer_id, $answer_text);
            $stmt->execute();
        } else {
            // Handle case where answer_id doesn't exist
            echo "Invalid answer selected for question ID: $question_id.";
            exit();
        }

        $check_answer_stmt->close();
    }

    // Calculate the score as a percentage
    $score = ($total_questions > 0) ? ($correct_answers / $total_questions) * 100 : 0;

    // Store the score in the database (you may need to create an `exam_grades` table or update an existing structure)
    $grade_stmt = $conn->prepare("
        INSERT INTO exam_grades (user_id, exam_id, score, graded_at)
        VALUES (?, ?, ?, CURRENT_TIMESTAMP)
        ON DUPLICATE KEY UPDATE score = VALUES(score), graded_at = CURRENT_TIMESTAMP
    ");
    if (!$grade_stmt) {
        die("Error preparing grade statement: " . $conn->error);
    }

    $grade_stmt->bind_param("iid", $user_id, $exam_id, $score);
    $grade_stmt->execute();

    // Close all statements and the connection
    $stmt->close();
    $grade_stmt->close();
    $conn->close();

    // Redirect to student report page with score
    header("Location: student_report.php?exam_id=$exam_id&score=$score");
    exit();
} else {
    echo "No answers submitted.";
}
?>
