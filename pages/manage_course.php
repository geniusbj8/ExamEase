<?php
session_start();

// Check if the user is logged in and their role is either admin (1) or instructor (2)
if (!isset($_SESSION['user_id']) || ($_SESSION['role_id'] != 1 && $_SESSION['role_id'] != 2)) {
    header("Location: login.php");
    exit();
}

include('../includes/db_connection.php');

// Fetch all courses for display
if (isset($_GET['fetch_courses'])) {
    $sql = "SELECT course_id, course_name, description FROM courses";
    $result = $conn->query($sql);
    
    if ($result) {
        $courses = [];
        while ($row = $result->fetch_assoc()) {
            $courses[] = $row;
        }
        echo json_encode($courses); // Send courses as JSON
    } else {
        // Log error if query fails
        error_log("Error fetching courses: " . $conn->error);
        echo json_encode(['status' => 'error', 'message' => 'Error fetching courses']);
    }
    exit();
}

// Handle adding a new course
if (isset($_POST['action']) && $_POST['action'] == 'add_course') {
    $courseName = $_POST['course_name'];
    $courseDescription = $_POST['course_description'];
    
    // Check if course name or description is empty
    if (empty($courseName) || empty($courseDescription)) {
        echo json_encode(['status' => 'error', 'message' => 'Course name and description are required']);
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO courses (course_name, description) VALUES (?, ?)");
    $stmt->bind_param("ss", $courseName, $courseDescription);
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Course added successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error adding course']);
    }
    exit();
}

// Handle editing an existing course
if (isset($_POST['action']) && $_POST['action'] == 'edit_course') {
    $courseId = $_POST['course_id'];
    $courseName = $_POST['course_name'];
    $courseDescription = $_POST['course_description'];

    // Check if course name or description is empty
    if (empty($courseName) || empty($courseDescription)) {
        echo json_encode(['status' => 'error', 'message' => 'Course name and description are required']);
        exit();
    }

    $stmt = $conn->prepare("UPDATE courses SET course_name = ?, description = ? WHERE course_id = ?");
    $stmt->bind_param("ssi", $courseName, $courseDescription, $courseId);
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Course updated successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error updating course']);
    }
    exit();
}

// Handle deleting a course
if (isset($_POST['action']) && $_POST['action'] == 'delete_course') {
    $courseId = $_POST['course_id'];
    
    $stmt = $conn->prepare("DELETE FROM courses WHERE course_id = ?");
    $stmt->bind_param("i", $courseId);
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Course deleted successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error deleting course']);
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Courses</title>
  <link rel="stylesheet" href="../assets/css/manage_course.css">
</head>
<body>

  <div class="content">
    <h1>Manage Courses</h1>

    <section class="course-list">
      <h2>Available Courses</h2>
      <table id="courseTable">
        <thead>
          <tr>
            <th>Course Name</th>
            <th>Description</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <!-- Course data will be loaded dynamically here -->
        </tbody>
      </table>
    </section>

    <section class="course-form">
      <h2 id="formTitle">Add New Course</h2>
      <form method="POST" id="courseForm">
        <label for="courseName">Course Name</label>
        <input type="text" id="courseName" name="course_name" required>

        <label for="courseDescription">Course Description</label>
        <textarea id="courseDescription" name="course_description" required></textarea>

        <button type="submit" id="submitButton" name="add_course">Add Course</button>
      </form>
    </section>

    <div>
      <a href="manage_exam.php"><button class="redirect">Manage Exams</button></a>
      <a href="instructor_dashboard.php"><button class="redirect">Back to Instructors Dashboard</button></a>
      <a href="admin_dashboard.php"><button class="redirect">Back to Admin Dashboard</button></a>
    </div>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
  loadCourses();

  // Load course list from the server
  function loadCourses() {
    fetch("manage_course.php?fetch_courses")
      .then(response => response.json())
      .then(courses => {
        const tbody = document.querySelector("#courseTable tbody");
        if (!tbody) {
          console.error("Table body not found!");
          return;
        }

        tbody.innerHTML = ""; // Clear any existing rows
        if (courses.length > 0) {
          courses.forEach(course => {
            const row = document.createElement("tr");
            row.innerHTML = `
              <td>${course.course_name}</td>
              <td>${course.description}</td>
              <td>
                <button onclick="editCourse(${course.course_id}, '${course.course_name}', '${course.description}')">Edit</button>
                <button onclick="deleteCourse(${course.course_id})">Delete</button>
              </td>
            `;
            tbody.appendChild(row);
          });
        } else {
          tbody.innerHTML = "<tr><td colspan='3'>No courses available</td></tr>";
        }
      })
      .catch(error => {
        console.error("Error fetching courses:", error);
        alert("Error fetching courses. Check console for details.");
      });
  }

  // Edit course function
  window.editCourse = function(courseId, courseName, courseDescription) {
    document.getElementById("formTitle").textContent = "Edit Course";
    document.getElementById("courseName").value = courseName;
    document.getElementById("courseDescription").value = courseDescription;

    // Change the button text to "Update Course"
    document.getElementById("submitButton").textContent = "Update Course";

    // Update the form's onsubmit logic to handle the edit
    document.getElementById("courseForm").onsubmit = function(event) {
      event.preventDefault();
      updateCourse(courseId);
    };
  };

  // Update course function
  function updateCourse(courseId) {
    const courseName = document.getElementById("courseName").value;
    const courseDescription = document.getElementById("courseDescription").value;

    fetch("manage_course.php", {
      method: "POST",
      body: new URLSearchParams({
        action: 'edit_course',
        course_id: courseId,
        course_name: courseName,
        course_description: courseDescription
      })
    })
    .then(response => response.json())
    .then(result => {
      alert(result.message);
      if (result.status === 'success') {
        loadCourses();
        resetForm(); // Reset the form after successful update
      }
    });
  }

  // Add course function
  document.getElementById("courseForm").onsubmit = function(event) {
    event.preventDefault();
    const courseName = document.getElementById("courseName").value;
    const courseDescription = document.getElementById("courseDescription").value;

    fetch("manage_course.php", {
      method: "POST",
      body: new URLSearchParams({
        action: 'add_course',
        course_name: courseName,
        course_description: courseDescription
      })
    })
    .then(response => response.json())
    .then(result => {
      alert(result.message);
      if (result.status === 'success') {
        loadCourses();
        resetForm(); // Reset the form after successful addition
      }
    });
  };

  // Reset the form to its initial state (for adding a new course)
  function resetForm() {
    document.getElementById("courseForm").reset();
    document.getElementById("formTitle").textContent = "Add New Course";
    document.getElementById("submitButton").textContent = "Add Course";
    
    // Reset the form's onsubmit logic to handle adding a course, not editing
    document.getElementById("courseForm").onsubmit = function(event) {
      event.preventDefault();
      const courseName = document.getElementById("courseName").value;
      const courseDescription = document.getElementById("courseDescription").value;

      fetch("manage_course.php", {
        method: "POST",
        body: new URLSearchParams({
          action: 'add_course',
          course_name: courseName,
          course_description: courseDescription
        })
      })
      .then(response => response.json())
      .then(result => {
        alert(result.message);
        if (result.status === 'success') {
          loadCourses();
          resetForm(); // Reset the form after successful addition
        }
      });
    };
  }

  // Delete course function
  window.deleteCourse = function(courseId) {
    if (confirm("Are you sure you want to delete this course?")) {
      fetch("manage_course.php", {
        method: "POST",
        body: new URLSearchParams({
          action: 'delete_course',
          course_id: courseId
        })
      }).then(response => response.json())
        .then(result => {
          alert(result.message);
          if (result.status === 'success') {
            loadCourses();
          }
        });
    }
  };
});

  </script>

</body>
</html>
