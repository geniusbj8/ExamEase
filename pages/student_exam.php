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
$user_id = $_SESSION['user_id'];

// Get exam_id and course_id from the URL query string
$exam_id = isset($_GET['exam_id']) ? $_GET['exam_id'] : null;
$course_id = isset($_GET['course_id']) ? $_GET['course_id'] : null;

// Include database connection
include('../includes/db_connection.php');

// Fetch exam details and questions with answers
$questions = [];
if ($exam_id && $course_id) {
    // Fetch exam details
    $stmt = $conn->prepare("SELECT * FROM exams WHERE exam_id = ? AND course_id = ?");
    $stmt->bind_param("ii", $exam_id, $course_id);
    $stmt->execute();
    $exam = $stmt->get_result()->fetch_assoc();

    // Fetch questions and corresponding answers in a single query to reduce database calls
    $stmt = $conn->prepare("
        SELECT q.question_id, q.question_text, a.answer_id, a.answer_text 
        FROM questions q
        LEFT JOIN answers a ON q.question_id = a.question_id
        WHERE q.exam_id = ?
        ORDER BY q.question_id, a.answer_id
    ");
    $stmt->bind_param("i", $exam_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Organize questions and answers in an associative array
    while ($row = $result->fetch_assoc()) {
        $question_id = $row['question_id'];
        if (!isset($questions[$question_id])) {
            $questions[$question_id] = [
                'question_text' => $row['question_text'],
                'answers' => []
            ];
        }
        $questions[$question_id]['answers'][] = [
            'answer_id' => $row['answer_id'],
            'answer_text' => $row['answer_text']
        ];
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Take Exam</title>
    <link rel="stylesheet" href="../assets/css/student_exam.css">
</head>
<body>
    <div class="exam-container">
        <h2><?php echo htmlspecialchars($exam['exam_name']); ?> - Exam</h2>

        <!-- Exam Questions -->
        <form action="submit_exam.php" method="POST">
            <input type="hidden" name="exam_id" value="<?php echo $exam_id; ?>">
            <?php foreach ($questions as $question_id => $question): ?>
                <div class="question">
                    <p><?php echo htmlspecialchars($question['question_text']); ?></p>
                    <ul>
                        <?php foreach ($question['answers'] as $answer): ?>
                            <li>
                                <input type="radio" name="answer[<?php echo $question_id; ?>]" value="<?php echo $answer['answer_id']; ?>"> 
                                <?php echo htmlspecialchars($answer['answer_text']); ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endforeach; ?>

            <button type="submit">Submit Exam</button>
        </form>
    </div>
</body>
</html>
