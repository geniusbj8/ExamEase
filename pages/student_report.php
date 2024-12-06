<?php
// Start the session
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 3) {
    header("Location: login.php");
    exit();
}

// Get the user_id and exam_id from the session and URL
$user_id = $_SESSION['user_id'];
$exam_id = isset($_GET['exam_id']) ? $_GET['exam_id'] : null;
$score = isset($_GET['score']) ? $_GET['score'] : null;

// Include database connection
include('../includes/db_connection.php');

// Fetch the student's answers for the exam
$stmt = $conn->prepare("
    SELECT sa.question_id, sa.answer_id, sa.answer_text, a.is_correct, q.question_text, a.answer_text AS correct_answer
    FROM student_answers sa
    JOIN answers a ON sa.answer_id = a.answer_id
    JOIN questions q ON sa.question_id = q.question_id
    WHERE sa.user_id = ? AND sa.exam_id = ?
");
$stmt->bind_param("ii", $user_id, $exam_id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch all questions and student answers
$student_answers = [];
while ($row = $result->fetch_assoc()) {
    $student_answers[] = $row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Report</title>
    <link rel="stylesheet" href="../assets/css/student_report.css">
</head>
<body>
    <div class="report-container">
        <h2>Exam Report - <?php echo htmlspecialchars($exam_id); ?></h2>
        <p><strong>Your Score:</strong> <?php echo htmlspecialchars($score); ?>%</p>

        <table>
            <thead>
                <tr>
                    <th>Question</th>
                    <th>Your Answer</th>
                    <th>Correct Answer</th>
                    <th>Result</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($student_answers as $answer): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($answer['question_text']); ?></td>
                        <td>
                            <?php 
                                // Display the student's answer
                                echo htmlspecialchars($answer['answer_text'] ?: $answer['answer_text']);
                            ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($answer['correct_answer']); ?>
                        </td>
                        <td>
                            <?php echo ($answer['is_correct'] == 1) ? 'Correct' : 'Incorrect'; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="student_dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>
