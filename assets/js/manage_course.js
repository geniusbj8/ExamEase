document.addEventListener("DOMContentLoaded", function() {
  loadCourses();
  loadExams();

  // Load course list from the server
  function loadCourses() {
    fetch("manage_course.php?fetch_courses")
      .then(response => response.json())
      .then(courses => {
        const tbody = document.querySelector("#courseTable tbody");
        tbody.innerHTML = "";
        courses.forEach(course => {
          const row = document.createElement("tr");
          row.innerHTML = `
            <td>${course.course_name}</td>
            <td>${course.exams || 'None'}</td>
            <td>
              <button class="edit-btn" data-id="${course.course_id}">Edit</button>
              <button class="delete-btn" data-id="${course.course_id}">Delete</button>
            </td>`;
          tbody.appendChild(row);
        });
      });
  }

  // Load exams for the course form
  function loadExams() {
    fetch("manage_course.php?fetch_exams")
      .then(response => response.json())
      .then(exams => {
        const select = document.getElementById("examSelect");
        select.innerHTML = "";
        exams.forEach(exam => {
          const option = document.createElement("option");
          option.value = exam.exam_id;
          option.textContent = exam.exam_name;
          select.appendChild(option);
        });
      });
  }

  // Edit course functionality
  document.querySelector("#courseTable").addEventListener("click", function(e) {
    if (e.target.classList.contains("edit-btn")) {
      const courseId = e.target.getAttribute("data-id");
      fetch(`manage_course.php?edit=${courseId}`)
        .then(response => response.json())
        .then(data => {
          if (data.course_name) {
            document.getElementById("courseName").value = data.course_name;
            // Set the exams for the course (if any)
            const examSelect = document.getElementById("examSelect");
            Array.from(examSelect.options).forEach(option => {
              option.selected = data.exams.includes(parseInt(option.value));
            });
            document.getElementById("formTitle").textContent = "Edit Course";
            document.getElementById("submitButton").textContent = "Update Course";
            document.getElementById("submitButton").setAttribute("name", "edit_course");
            document.getElementById("submitButton").setAttribute("data-id", courseId);
          }
        });
    } else if (e.target.classList.contains("delete-btn")) {
      const courseId = e.target.getAttribute("data-id");
      if (confirm("Are you sure you want to delete this course?")) {
        fetch("manage_course.php", {
          method: "POST",
          headers: { "Content-Type": "application/x-www-form-urlencoded" },
          body: new URLSearchParams({ action: 'delete_course', course_id: courseId })
        })
          .then(response => response.json())
          .then(data => {
            alert(data.message);
            loadCourses(); // Reload the course list
          });
      }
    }
  });

  // Handle form submission (add/edit)
  document.getElementById("courseForm").addEventListener("submit", function(e) {
    e.preventDefault();

    const courseName = document.getElementById("courseName").value;
    const exams = Array.from(document.getElementById("examSelect").selectedOptions).map(option => option.value);
    const action = document.getElementById("submitButton").getAttribute("name");
    const courseId = document.getElementById("submitButton").getAttribute("data-id");

    fetch("manage_course.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: new URLSearchParams({
        action: action,
        course_id: courseId,
        course_name: courseName,
        exam_ids: exams
      })
    })
      .then(response => response.json())
      .then(data => {
        alert(data.message);
        loadCourses(); // Reload the course list after adding or editing
        resetCourseForm(); // Reset the form
      })
      .catch(error => {
        console.error("Error:", error);
        alert("An error occurred while processing your request.");
      });
  });

  // Reset the form for adding a new course
  function resetCourseForm() {
    document.getElementById("courseForm").reset();
    document.getElementById("formTitle").textContent = "Add New Course";
    document.getElementById("submitButton").textContent = "Add Course";
    document.getElementById("submitButton").removeAttribute("data-id");
  }
});