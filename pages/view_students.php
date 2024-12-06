<?php
// Start session to manage user authentication
session_start();

// Check if the user is logged in and has instructor privileges
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 2) {
    header("Location: login.php");
    exit();
}

// Include database connection
include('../includes/db_connection.php');

// Handle unenrollment requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['unenroll'])) {
    $userId = $_POST['user_id'];
    $courseId = $_POST['course_id'];

    // Validate inputs
    if (!empty($userId) && !empty($courseId)) {
        $unenrollQuery = "DELETE FROM enrollments WHERE user_id = ? AND course_id = ?";
        $stmt = $conn->prepare($unenrollQuery);
        $stmt->bind_param('ii', $userId, $courseId);

        if ($stmt->execute()) {
            $successMessage = "Student unenrolled successfully.";
        } else {
            $errorMessage = "Error unenrolling student. Please try again.";
        }

        $stmt->close();
    }
}

// Fetch all students along with their courses and exams
$query = "
    SELECT 
        u.user_id,
        u.name AS student_name,
        u.email AS student_email,
        c.course_id,
        c.course_name,
        e.exam_id,
        e.exam_name
    FROM 
        users u
    LEFT JOIN enrollments en ON u.user_id = en.user_id
    LEFT JOIN courses c ON en.course_id = c.course_id
    LEFT JOIN exams e ON c.course_id = e.course_id
    WHERE 
        u.role_id = 3 -- Ensure we fetch only students
    ORDER BY u.user_id, c.course_id, e.exam_id;
";

$result = $conn->query($query);

// Prepare data for display
$students = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $userId = $row['user_id'];

        if (!isset($students[$userId])) {
            $students[$userId] = [
                'name' => $row['student_name'],
                'email' => $row['student_email'],
                'courses' => [],
            ];
        }

        if ($row['course_id']) {
            if (!isset($students[$userId]['courses'][$row['course_id']])) {
                $students[$userId]['courses'][$row['course_id']] = [
                    'course_name' => $row['course_name'],
                    'exams' => [],
                ];
            }

            if ($row['exam_id']) {
                $students[$userId]['courses'][$row['course_id']]['exams'][] = $row['exam_name'];
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Details | ExamEase</title>
    <link rel="stylesheet" href="../assets/css/view_student.css">
</head>
<body>

<div class="main-content">
    <h1>Student Details</h1>
    <div>
      <a href="instructor_dashboard.php" style="text-decoration: none;"><button>Back To Dashboard</button></a>
    </div>
    <?php if (isset($successMessage)): ?>
        <p class="success"><?php echo htmlspecialchars($successMessage); ?></p>
    <?php endif; ?>

    <?php if (isset($errorMessage)): ?>
        <p class="error"><?php echo htmlspecialchars($errorMessage); ?></p>
    <?php endif; ?>

    <?php if (!empty($students)): ?>
        <table class="student-table">
            <thead>
            <tr>
                <th>Student Name</th>
                <th>Email</th>
                <th>Courses & Exams</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($students as $userId => $student): ?>
                <tr>
                    <td><?php echo htmlspecialchars($student['name']); ?></td>
                    <td><?php echo htmlspecialchars($student['email']); ?></td>
                    <td>
                        <?php foreach ($student['courses'] as $courseId => $course): ?>
                            <div class="sub-data">
                                <strong>Course:</strong> <?php echo htmlspecialchars($course['course_name']); ?>
                                <?php if (!empty($course['exams'])): ?>
                                    <ul>
                                        <?php foreach ($course['exams'] as $exam): ?>
                                            <li><?php echo htmlspecialchars($exam); ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php else: ?>
                                    <em>No exams assigned</em>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </td>
                    <td>
                        <form method="POST" action="">
                            <input type="hidden" name="user_id" value="<?php echo $userId; ?>">
                            <label for="course_id_<?php echo $userId; ?>">Course:</label>
                            <select name="course_id" id="course_id_<?php echo $userId; ?>">
                                <?php foreach ($student['courses'] as $courseId => $course): ?>
                                    <option value="<?php echo $courseId; ?>">
                                        <?php echo htmlspecialchars($course['course_name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit" name="unenroll">Unenroll</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No students found.</p>
    <?php endif; ?>
</div>

</body>
</html>
