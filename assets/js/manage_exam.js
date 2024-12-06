document.getElementById('examForm').addEventListener('submit', function(event) {
  event.preventDefault(); // Prevent the default form submission

  var formData = new FormData(this);
  var xhr = new XMLHttpRequest();
  xhr.open('POST', 'manage_exam.php', true);

  xhr.onload = function() {
    if (xhr.status === 200) {
      var response = JSON.parse(xhr.responseText);
      alert(response.message);  // Show success or error message
      if (response.status === 'success') {
        updateExamTable(response.exams);  // Update the exam table with the new list of exams
        resetForm();
      }
    }
  };

  xhr.send(formData);
});

function updateExamTable(exams) {
  var tableBody = document.querySelector('#examTable tbody');
  tableBody.innerHTML = '';  // Clear current rows

  exams.forEach(function(exam) {
    var row = document.createElement('tr');
    row.innerHTML = `
      <td>${exam.exam_name}</td>
      <td>${exam.course_name}</td>
      <td>
        <a href="manage_exam.php?edit=${exam.exam_id}" class="edit-btn">Edit</a>
        <a href="manage_exam.php?delete=${exam.exam_id}" class="delete-btn" onclick="return deleteExam(event, ${exam.exam_id})">Delete</a>
      </td>
    `;
    tableBody.appendChild(row);
  });
}

function deleteExam(event, examId) {
  event.preventDefault();  // Prevent the default link action

  if (confirm('Are you sure you want to delete this exam?')) {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', `manage_exam.php?delete=${examId}`, true);

    xhr.onload = function() {
      if (xhr.status === 200) {
        var response = JSON.parse(xhr.responseText);
        alert(response.message);  // Show success or error message
        if (response.status === 'success') {
          updateExamTable(response.exams);  // Update the exam table
        }
      }
    };

    xhr.send();
  }
}

function resetForm() {
  document.getElementById('examForm').reset();
  document.getElementById('formTitle').textContent = 'Add New Exam';  // Reset form title
  document.querySelector('#examForm input[name="examId"]').value = '';  // Clear hidden examId field
}
