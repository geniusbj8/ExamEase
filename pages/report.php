<?php
// Start session and check if user is an admin or instructor
session_start();

// Ensure only admins or instructors can access this page
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role_id'], [1, 2])) { // 1 = Admin, 2 = Instructor
    header("Location: login.php");
    exit();
}

// Include database connection
include('../includes/db_connection.php');

// Fetch selected course and student from the filter, if any
$course_id = isset($_GET['course_id']) ? $_GET['course_id'] : '';
$student_id = isset($_GET['student_id']) ? $_GET['student_id'] : '';

// Modify the query to filter results based on selected course or student
$query = "
    SELECT 
        u.name AS student_name, 
        c.course_name, 
        e.exam_name, 
        r.score 
    FROM exam_grades r
    JOIN users u ON r.user_id = u.user_id
    JOIN exams e ON r.exam_id = e.exam_id
    JOIN courses c ON e.course_id = c.course_id
";

// Apply filters
$filters = [];
if ($course_id) {
    $filters[] = "c.course_id = " . (int)$course_id;
}
if ($student_id) {
    $filters[] = "u.user_id = " . (int)$student_id;
}

if (!empty($filters)) {
    $query .= " WHERE " . implode(" AND ", $filters);
}

$query .= " ORDER BY c.course_name, e.exam_name, u.name";

$results = $conn->query($query);

// Fetch all courses for the filter dropdown
$courses = $conn->query("SELECT * FROM courses")->fetch_all(MYSQLI_ASSOC);

// Fetch all students for the filter dropdown
$students = $conn->query("SELECT user_id, name FROM users WHERE role_id = 3")->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Results Report</title>
    <link rel="stylesheet" href="../assets/css/report.css">
</head>
<body>

    <div class="content">
        <h1>Student Results Report</h1>

        <!-- Filter Section -->
        <div class="filter">
            <form method="GET" action="report.php">
                <label for="filter_course">Filter by Course:</label>
                <select id="filter_course" name="course_id">
                    <option value="">All Courses</option>
                    <?php foreach ($courses as $course): ?>
                        <option value="<?= htmlspecialchars($course['course_id']) ?>" <?= $course_id == $course['course_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($course['course_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="filter_student">Filter by Student:</label>
                <select id="filter_student" name="student_id">
                    <option value="">All Students</option>
                    <?php foreach ($students as $student): ?>
                        <option value="<?= htmlspecialchars($student['user_id']) ?>" <?= $student_id == $student['user_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($student['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit">Filter</button>
            </form>
        </div>

        <!-- Results Table -->
        <table id="resultsTable">
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Course</th>
                    <th>Exam</th>
                    <th>Score</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($results && $results->num_rows > 0): ?>
                    <?php while ($row = $results->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['student_name']) ?></td>
                            <td><?= htmlspecialchars($row['course_name']) ?></td>
                            <td><?= htmlspecialchars($row['exam_name']) ?></td>
                            <td><?= htmlspecialchars($row['score']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">No results found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

      <div>
        <a href="instructor_dashboard.php"><button>Instructor Dashboard</button></a>
        <a href="admin_dashboard.php"><button>Admin Dashboard</button></a>
      </div>
    </div>
</body>
</html>
