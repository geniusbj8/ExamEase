// Timer Code
const startingMinutes = 60; // set exam duration in minutes
let time = startingMinutes * 60;

const timerElement = document.getElementById('time-remaining');

function updateTimer() {
  const minutes = Math.floor(time / 60);
  const seconds = time % 60;
  timerElement.innerHTML = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
  time--;

  if (time < 0) {
    clearInterval(timerInterval);
    document.getElementById('exam-form').submit();
    alert("Time's up! Your answers have been submitted.");
  }
}

const timerInterval = setInterval(updateTimer, 1000);

// Submit Form Functionality
document.getElementById('exam-form').addEventListener('submit', function(event) {
  event.preventDefault();
  clearInterval(timerInterval); // Stop the timer when the form is submitted
  alert("Your answers have been submitted.");
  // Additional code to handle form submission (e.g., AJAX, form submission)
});
