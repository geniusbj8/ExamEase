document.getElementById('questionForm').addEventListener('submit', function(event) {
  event.preventDefault();
  
  // Retrieve form data
  const questionText = document.getElementById('questionText').value;
  const examId = document.getElementById('examSelect').value;
  const answers = Array.from(document.querySelectorAll('.answer-input input[type="text"]')).map(input => input.value);
  const correctAnswer = document.querySelector('input[name="correctAnswer"]:checked').value;
  
  // Log data (or send it to your backend)
  console.log({
    questionText,
    examId,
    answers,
    correctAnswer
  });

  // Clear form (optional)
  this.reset();
});
