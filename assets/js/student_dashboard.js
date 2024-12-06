// script.js

document.addEventListener("DOMContentLoaded", function() {
  // Event listener for starting an exam
  const startExamButtons = document.querySelectorAll('.start-exam-btn');

  startExamButtons.forEach(button => {
    button.addEventListener('click', function() {
      alert("Redirecting to the exam page...");
      // You can redirect to a page where the student can take the exam.
      // Or open a modal to display the exam.
    });
  });
});

