<?php
session_start();

// Check if the user is logged in and their role is either admin (1) or instructor (2)
if (!isset($_SESSION['user_id']) || ($_SESSION['role_id'] != 1 && $_SESSION['role_id'] != 2)) {
    header("Location: login.php");
    exit();
}

include('../includes/db_connection.php');

// Fetch all exams for display
if (isset($_GET['fetch_exams'])) {
    $sql = "SELECT e.exam_id, e.exam_name, e.duration, e.exam_description, c.course_name 
            FROM exams e
            JOIN courses c ON e.course_id = c.course_id";
    $result = $conn->query($sql);
    
    if ($result) {
        $exams = [];
        while ($row = $result->fetch_assoc()) {
            $exams[] = $row;
        }
        echo json_encode($exams); // Send exams as JSON
    } else {
        // Log error if query fails
        error_log("Error fetching exams: " . $conn->error);
        echo json_encode(['status' => 'error', 'message' => 'Error fetching exams']);
    }
    exit();
}

// Handle adding a new exam
if (isset($_POST['action']) && $_POST['action'] == 'add_exam') {
    $examName = $_POST['exam_name'];
    $courseId = $_POST['course_id'];
    $duration = $_POST['duration'];
    $examDescription = $_POST['exam_description'];
    
    // Check if any required fields are empty
    if (empty($examName) || empty($courseId) || empty($duration)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO exams (exam_name, course_id, duration, exam_description) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssis", $examName, $courseId, $duration, $examDescription);
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Exam added successfully']);
    } else {
        error_log("Error adding exam: " . $stmt->error);
        echo json_encode(['status' => 'error', 'message' => 'Error adding exam']);
    }
    exit();
}

// Handle editing an existing exam
if (isset($_POST['action']) && $_POST['action'] == 'edit_exam') {
    $examId = $_POST['exam_id'];
    $examName = $_POST['exam_name'];
    $courseId = $_POST['course_id'];
    $duration = $_POST['duration'];
    $examDescription = $_POST['exam_description'];

    // Check if any required fields are empty
    if (empty($examName) || empty($courseId) || empty($duration)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
        exit();
    }

    $stmt = $conn->prepare("UPDATE exams SET exam_name = ?, course_id = ?, duration = ?, exam_description = ? WHERE exam_id = ?");
    $stmt->bind_param("ssisi", $examName, $courseId, $duration, $examDescription, $examId);
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Exam updated successfully']);
    } else {
        error_log("Error updating exam: " . $stmt->error);
        echo json_encode(['status' => 'error', 'message' => 'Error updating exam']);
    }
    exit();
}

// Handle deleting an exam
if (isset($_POST['action']) && $_POST['action'] == 'delete_exam') {
    $examId = $_POST['exam_id'];
    
    $stmt = $conn->prepare("DELETE FROM exams WHERE exam_id = ?");
    $stmt->bind_param("i", $examId);
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Exam deleted successfully']);
    } else {
        error_log("Error deleting exam: " . $stmt->error);
        echo json_encode(['status' => 'error', 'message' => 'Error deleting exam']);
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Exams</title>
    <link rel="stylesheet" href="../assets/css/manage_exam.css">
</head>
<body>

<div class="content">
    <h1>Manage Exams</h1>

    <section class="exam-list">
        <h2>Available Exams</h2>
        <table id="examTable">
            <thead>
                <tr>
                    <th>Exam Name</th>
                    <th>Course</th>
                    <th>Duration (minutes)</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Exam data will be loaded dynamically here -->
            </tbody>
        </table>
    </section>

    <section class="exam-form">
        <h2 id="formTitle">Add New Exam</h2>
        <form method="POST" id="examForm">
            <label for="examName">Exam Name</label>
            <input type="text" id="examName" name="exam_name" required>

            <label for="courseSelect">Course</label>
            <select id="courseSelect" name="course_id" required></select>

            <label for="duration">Duration (minutes)</label>
            <input type="number" id="duration" name="duration" min="1" required>

            <label for="examDescription">Exam Description</label>
            <textarea id="examDescription" name="exam_description"></textarea>

            <button type="submit" id="submitButton" name="add_exam">Add Exam</button>
        </form>
    </section>

    <div>
        <a href="manage_course.php"><button class="redirect">Back to Manage Courses</button></a>
        <a href="manage_questions.php"><button class="redirect">Manage Questions</button></a>
        <a href="instructor_dashboard.php"><button class="redirect">Back to Instructor Dashboard</button></a>
        <a href="admin_dashboard.php"><button class="redirect">Back to Admin Dashboard</button></a>
    </div>
</div>

<script>

document.addEventListener("DOMContentLoaded", function() {
    loadExams();
    loadCourses();

    // Load exams from the server
    function loadExams() {
        fetch("manage_exam.php?fetch_exams")
            .then(response => response.json())
            .then(exams => {
                const tbody = document.querySelector("#examTable tbody");
                if (!tbody) {
                    console.error("Table body not found!");
                    return;
                }

                tbody.innerHTML = ""; // Clear any existing rows
                if (exams.length > 0) {
                    exams.forEach(exam => {
                        const row = document.createElement("tr");
                        row.innerHTML = `
                            <td>${exam.exam_name}</td>
                            <td>${exam.course_name}</td>
                            <td>${exam.duration}</td>
                            <td>${exam.exam_description}</td>
                            <td>
                                <button onclick="editExam(${exam.exam_id}, '${exam.exam_name}', ${exam.course_id}, ${exam.duration}, '${exam.exam_description}')">Edit</button>
                                <button onclick="deleteExam(${exam.exam_id})">Delete</button>
                            </td>
                        `;
                        tbody.appendChild(row);
                    });
                } else {
                    tbody.innerHTML = "<tr><td colspan='5'>No exams available</td></tr>";
                }
            })
            .catch(error => {
                console.error("Error fetching exams:", error);
                alert("Error fetching exams. Check console for details.");
            });
    }

    // Load courses for the select dropdown
    function loadCourses() {
        fetch("manage_course.php?fetch_courses")
            .then(response => response.json())
            .then(courses => {
                const courseSelect = document.getElementById("courseSelect");
                courses.forEach(course => {
                    const option = document.createElement("option");
                    option.value = course.course_id;
                    option.textContent = course.course_name;
                    courseSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error("Error fetching courses:", error);
            });
    }

    // Edit exam function
    window.editExam = function(examId, examName, courseId, duration, examDescription) {
        document.getElementById("formTitle").textContent = "Edit Exam";
        document.getElementById("examName").value = examName;
        document.getElementById("courseSelect").value = courseId;
        document.getElementById("duration").value = duration;
        document.getElementById("examDescription").value = examDescription;

        // Change button text to "Update Exam"
        document.getElementById("submitButton").textContent = "Update Exam";

        // Update form submission logic for editing an exam
        document.getElementById("examForm").onsubmit = function(event) {
            event.preventDefault();
            updateExam(examId);
        };
    };

    // Update exam function
    function updateExam(examId) {
        const examName = document.getElementById("examName").value;
        const courseId = document.getElementById("courseSelect").value;
        const duration = document.getElementById("duration").value;
        const examDescription = document.getElementById("examDescription").value;

        fetch("manage_exam.php", {
            method: "POST",
            body: new URLSearchParams({
                action: 'edit_exam',
                exam_id: examId,
                exam_name: examName,
                course_id: courseId,
                duration: duration,
                exam_description: examDescription
            })
        })
        .then(response => response.json())
        .then(result => {
            alert(result.message);
            if (result.status === 'success') {
                loadExams();
                resetForm(); // Reset the form after successful update
            }
        });
    }

    // Add new exam
    document.getElementById("examForm").onsubmit = function(event) {
        event.preventDefault();
        const examName = document.getElementById("examName").value;
        const courseId = document.getElementById("courseSelect").value;
        const duration = document.getElementById("duration").value;
        const examDescription = document.getElementById("examDescription").value;

        fetch("manage_exam.php", {
            method: "POST",
            body: new URLSearchParams({
                action: 'add_exam',
                exam_name: examName,
                course_id: courseId,
                duration: duration,
                exam_description: examDescription
            })
        })
        .then(response => response.json())
        .then(result => {
            alert(result.message);
            if (result.status === 'success') {
                loadExams();
                resetForm(); // Reset form after adding the exam
            }
        });
    };

    // Reset the form
    function resetForm() {
        document.getElementById("examForm").reset();
        document.getElementById("formTitle").textContent = "Add New Exam";
        document.getElementById("submitButton").textContent = "Add Exam";

        // Reset the form's onsubmit handler to add exam logic
        document.getElementById("examForm").onsubmit = function(event) {
            event.preventDefault();
            const examName = document.getElementById("examName").value;
            const courseId = document.getElementById("courseSelect").value;
            const duration = document.getElementById("duration").value;
            const examDescription = document.getElementById("examDescription").value;

            fetch("manage_exam.php", {
                method: "POST",
                body: new URLSearchParams({
                    action: 'add_exam',
                    exam_name: examName,
                    course_id: courseId,
                    duration: duration,
                    exam_description: examDescription
                })
            })
            .then(response => response.json())
            .then(result => {
                alert(result.message);
                if (result.status === 'success') {
                    loadExams();
                    resetForm(); // Reset the form after successful add
                }
            });
        };
    }

    // Delete exam function
    window.deleteExam = function(examId) {
        if (confirm("Are you sure you want to delete this exam?")) {
            fetch("manage_exam.php", {
                method: "POST",
                body: new URLSearchParams({
                    action: 'delete_exam',
                    exam_id: examId
                })
            }).then(response => response.json())
                .then(result => {
                    alert(result.message);
                    if (result.status === 'success') {
                        loadExams();
                    }
                });
        }
    };
});



</script>

</body>
</html>
