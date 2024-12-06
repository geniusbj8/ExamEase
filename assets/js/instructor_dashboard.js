// script.js

document.addEventListener("DOMContentLoaded", function() {
  // Add event listener for adding courses
  const addCourseButton = document.querySelector('.add-course-btn');
  const addExamButton = document.querySelector('.add-exam-btn');

  addCourseButton.addEventListener('click', function() {
    alert("Redirecting to course creation form...");
    // You can redirect to a separate page for adding courses or open a modal.
  });

  addExamButton.addEventListener('click', function() {
    alert("Redirecting to exam creation form...");
    // You can redirect to a separate page for adding exams or open a modal.
  });

  // Event listeners for Edit and Delete buttons in courses and exams
  const editCourseButtons = document.querySelectorAll('button:contains("Edit")');
  const deleteCourseButtons = document.querySelectorAll('button:contains("Delete")');
  const editExamButtons = document.querySelectorAll('button:contains("Edit")');
  const deleteExamButtons = document.querySelectorAll('button:contains("Delete")');

  // Event listeners for Edit and Delete in courses
  editCourseButtons.forEach(button => {
    button.addEventListener('click', function() {
      alert("Editing course...");
      // Implement course editing logic here.
    });
  });

  deleteCourseButtons.forEach(button => {
    button.addEventListener('click', function() {
      alert("Deleting course...");
      // Implement course deletion logic here.
    });
  });

  // Event listeners for Edit and Delete in exams
  editExamButtons.forEach(button => {
    button.addEventListener('click', function() {
      alert("Editing exam...");
      // Implement exam editing logic here.
    });
  });

  deleteExamButtons.forEach(button => {
    button.addEventListener('click', function() {
      alert("Deleting exam...");
      // Implement exam deletion logic here.
    });
  });
});


