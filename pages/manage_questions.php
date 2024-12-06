<?php

session_start();

// Check user authentication
if (!isset($_SESSION['user_id']) || ($_SESSION['role_id'] != 1 && $_SESSION['role_id'] != 2)) {
    header("Location: login.php");
    exit();
}

// Include database connection
include('../includes/db_connection.php');

// Fetch questions and their respective exams
$query = "SELECT q.question_id, q.question_text, e.exam_name 
          FROM questions q 
          JOIN exams e ON q.exam_id = e.exam_id";
$result = mysqli_query($conn, $query);
if (!$result) {
    die('Query failed: ' . mysqli_error($conn));
}

// Fetch exams for dropdown
$examQuery = "SELECT * FROM exams";
$examResult = mysqli_query($conn, $examQuery);
if (!$examResult) {
    die('Error fetching exams: ' . mysqli_error($conn));
}

// Handle add/update question and answers
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $questionId = isset($_POST['questionId']) ? intval($_POST['questionId']) : null;
    $questionText = mysqli_real_escape_string($conn, $_POST['questionText']);
    $examId = intval($_POST['examSelect']);
    $answers = [
        mysqli_real_escape_string($conn, $_POST['answer1']),
        mysqli_real_escape_string($conn, $_POST['answer2']),
        mysqli_real_escape_string($conn, $_POST['answer3']),
        mysqli_real_escape_string($conn, $_POST['answer4'])
    ];
    $correctAnswer = intval($_POST['correctAnswer']);

    if ($questionId) {
        // Update question
        $updateQuery = "UPDATE questions SET question_text = '$questionText', exam_id = $examId WHERE question_id = $questionId";
        mysqli_query($conn, $updateQuery) or die('Error updating question: ' . mysqli_error($conn));

        // Delete existing answers before updating
        $deleteAnswersQuery = "DELETE FROM answers WHERE question_id = $questionId";
        mysqli_query($conn, $deleteAnswersQuery) or die('Error deleting old answers: ' . mysqli_error($conn));

        // Insert new answers
        foreach ($answers as $key => $answer) {
            $isCorrect = ($correctAnswer == ($key + 1)) ? 1 : 0;
            $answerQuery = "INSERT INTO answers (question_id, answer_text, is_correct) 
                            VALUES ($questionId, '$answer', $isCorrect)";
            mysqli_query($conn, $answerQuery) or die('Error inserting answers: ' . mysqli_error($conn));
        }
    } else {
        // Insert new question
        $insertQuery = "INSERT INTO questions (question_text, exam_id) VALUES ('$questionText', $examId)";
        mysqli_query($conn, $insertQuery) or die('Error inserting question: ' . mysqli_error($conn));
        $questionId = mysqli_insert_id($conn);

        // Insert answers
        foreach ($answers as $key => $answer) {
            $isCorrect = ($correctAnswer == ($key + 1)) ? 1 : 0;
            $answerQuery = "INSERT INTO answers (question_id, answer_text, is_correct) VALUES ($questionId, '$answer', $isCorrect)";
            mysqli_query($conn, $answerQuery) or die('Error inserting answers: ' . mysqli_error($conn));
        }
    }

    // Redirect to avoid resubmission
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// Handle deletion
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    parse_str(file_get_contents("php://input"), $deleteParams);
    $questionId = intval($deleteParams['id']);
    $deleteQuery = "DELETE FROM questions WHERE question_id = $questionId";
    if (mysqli_query($conn, $deleteQuery)) {
        http_response_code(200);
        echo json_encode(['message' => 'Question deleted successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to delete question']);
    }
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Questions || Admin Dashboard</title>
  <link rel="stylesheet" href="../assets/css/manage_questions.css">
</head>
<body>

  <!-- Main Content -->
  <div class="content">
    <h1>Manage Questions and Answers</h1>

    <!-- List of Questions and Answers -->
    <section class="question-list">
      <h2>Existing Questions</h2>
      <table id="questionTable">
        <thead>
          <tr>
            <th>Question Text</th>
            <th>Exam</th>
            <th>Answers</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php
          while ($row = mysqli_fetch_assoc($result)) {
              $questionId = $row['question_id'];
              $questionText = $row['question_text'];
              $examName = $row['exam_name'];

              // Fetch answers for the current question
              $answerQuery = "SELECT answer_id, answer_text, is_correct FROM answers WHERE question_id = $questionId";
              $answerResult = mysqli_query($conn, $answerQuery);
              $answers = [];
              while ($answer = mysqli_fetch_assoc($answerResult)) {
                  $answers[] = $answer;
              }
              ?>
              <tr id="question-<?= $questionId ?>">
                <td><?= htmlspecialchars($questionText) ?></td>
                <td><?= htmlspecialchars($examName) ?></td>
                <td>
                  <ul>
                    <?php foreach ($answers as $answer) { ?>
                      <li>
                        <?= htmlspecialchars($answer['answer_text']) ?> 
                        <?= $answer['is_correct'] ? '<strong>(Correct)</strong>' : '' ?>
                      </li>
                    <?php } ?>
                  </ul>
                </td>
                <td>
                  <button class="edit-btn" data-id="<?= $questionId ?>" onclick="editQuestion(<?= $questionId ?>)">Edit</button>
                  <button class="delete-btn" onclick="deleteQuestion(<?= $questionId ?>)">Delete</button>
                </td>
              </tr>
          <?php } ?>
        </tbody>
      </table>
    </section>

    <!-- Add/Edit Question Form -->
    <section class="question-form">
      <h2 id="formTitle">Add New Question</h2>
      <form id="questionForm" method="POST">
        <input type="hidden" id="questionId" name="questionId">
        <label for="questionText">Question Text</label>
        <input type="text" id="questionText" name="questionText" required>

        <label for="examSelect">Assign to Exam</label>
        <select id="examSelect" name="examSelect" required>
          <?php while ($exam = mysqli_fetch_assoc($examResult)) { ?>
            <option value="<?= $exam['exam_id'] ?>"><?= htmlspecialchars($exam['exam_name']) ?></option>
          <?php } ?>
        </select>

        <!-- Answer Inputs -->
        <div class="answers-section">
          <h3>Answers</h3>
          <div class="answer-input">
            <input type="text" name="answer1" placeholder="Answer 1" required>
            <label>
              <input type="radio" name="correctAnswer" value="1" required> Correct
            </label>
          </div>
          <div class="answer-input">
            <input type="text" name="answer2" placeholder="Answer 2" required>
            <label>
              <input type="radio" name="correctAnswer" value="2"> Correct
            </label>
          </div>
          <div class="answer-input">
            <input type="text" name="answer3" placeholder="Answer 3" required>
            <label>
              <input type="radio" name="correctAnswer" value="3"> Correct
            </label>
          </div>
          <div class="answer-input">
            <input type="text" name="answer4" placeholder="Answer 4" required>
            <label>
              <input type="radio" name="correctAnswer" value="4"> Correct
            </label>
          </div>
        </div>

        <button type="submit" id="submitButton">Add/Update Question</button>
      </form>
    </section>

    <div>
    <a href="manage_exam.php" class="redirect">Back to Manage Exams</a>
    <a href="instructor_dashboard.php" class="redirect">Back to Insturctor Dashboard</a>
    <a href="admin_dashboard.php" class="redirect">Back to Admin Dashboard</a>
  </div>
  
  </div>


  <script>
// This function pre-fills the form with the current data when editing a question
function editQuestion(questionId) {
    // Find the row of the question in the table
    const row = document.querySelector(`button[data-id='${questionId}']`).closest('tr');
    
    // Retrieve the question text and exam name
    const questionText = row.querySelector('td').textContent; // Assuming the question text is in the first column
    const examName = row.querySelector('td:nth-child(2)').textContent; // Assuming the exam name is in the second column
    
    // Populate the form with existing question details
    document.getElementById('questionId').value = questionId;
    document.getElementById('questionText').value = questionText;
    
    // Set the exam select field to the correct exam
    const examSelect = document.getElementById('examSelect');
    for (let i = 0; i < examSelect.options.length; i++) {
        if (examSelect.options[i].text === examName) {
            examSelect.selectedIndex = i;
            break;
        }
    }

    // Pre-populate answers and set the correct answer radio button
    const answerInputs = row.querySelectorAll('td:nth-child(3) ul li');
    answerInputs.forEach((li, index) => {
        const answerText = li.textContent.replace('(Correct)', '').trim();
        const correctAnswer = li.textContent.includes('(Correct)') ? index + 1 : null;

        // Populate the corresponding answer field
        document.querySelector(`input[name='answer${index + 1}']`).value = answerText;
        
        // Set the correct answer radio button
        if (correctAnswer) {
            document.querySelector(`input[name='correctAnswer'][value='${correctAnswer}']`).checked = true;
        }
    });

    // Change the form title to "Edit Question"
    document.getElementById('formTitle').textContent = 'Edit Question';
}

// JavaScript to handle form submission via AJAX to prevent page reload
document.getElementById('questionForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent the default form submission
    
    // Retrieve the form data
    const questionText = document.getElementById('questionText').value;
    const examId = document.getElementById('examSelect').value;
    const answers = Array.from(document.querySelectorAll('.answer-input input[type="text"]')).map(input => input.value);
    const correctAnswer = document.querySelector('input[name="correctAnswer"]:checked').value;
    const questionId = document.getElementById('questionId').value;

    // Create an object with the form data
    const formData = new FormData();
    formData.append('questionText', questionText);
    formData.append('examSelect', examId);
    formData.append('answer1', answers[0]);
    formData.append('answer2', answers[1]);
    formData.append('answer3', answers[2]);
    formData.append('answer4', answers[3]);
    formData.append('correctAnswer', correctAnswer);
    formData.append('questionId', questionId);

    // Send the data via AJAX to the backend
    fetch('manage_questions.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        // Handle the response if needed (e.g., show a success message)
        console.log('Question updated/added successfully');
        window.location.reload(); // Reload the page to reflect the changes
    })
    .catch(error => {
        console.error('Error:', error);
    });
});

// Delete a question
function deleteQuestion(questionId) {
    if (confirm('Are you sure you want to delete this question?')) {
        fetch('manage_questions.php', {
            method: 'DELETE',
            body: new URLSearchParams({
                id: questionId
            })
        })
        .then((response) => {
            if (response.ok) {
                document.getElementById(`question-${questionId}`).remove();
            } else {
                alert('Failed to delete question.');
            }
        });
    }
}

  </script>

</body>
</html>
