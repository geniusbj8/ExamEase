<?php
// Start session for error or success messages
session_start();

// Include database connection
include('../includes/db_connection.php');

// Helper function to hash the password using SHA-256
function sha256_password_hash($password) {
    return hash('sha256', $password);
}

// Handle Registration
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    $role_id = $_POST['role_id'];
    $name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Hash the password using SHA-256
    $password_hash = sha256_password_hash($password);

    // Check if the username or email already exists
    $sql = "SELECT * FROM users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['message'] = [
            'title' => 'Error!',
            'text' => 'Username or email already exists.',
            'icon' => 'error'
        ];
    } else {
        // Insert new user into the database
        $sql = "INSERT INTO users (role_id, name, username, email, password_hash) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issss", $role_id, $name, $username, $email, $password_hash);

        if ($stmt->execute()) {
            $_SESSION['message'] = [
                'title' => 'Success!',
                'text' => 'Registration successful. Please log in.',
                'icon' => 'success'
            ];
            header("Location: login.php");  // Redirect to login page after registration
            exit();
        } else {
            $_SESSION['message'] = [
                'title' => 'Error!',
                'text' => 'There was an error during registration.',
                'icon' => 'error'
            ];
        }
    }
}

// Handle Login
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hash the password input to compare
    $password_hash = sha256_password_hash($password);

    // Query to check user credentials
    $sql = "SELECT * FROM users WHERE username = ? AND password_hash = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password_hash);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Successful login, set session variables
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role_id'] = $user['role_id'];

        $_SESSION['message'] = [
            'title' => 'Success!',
            'text' => 'Login successful.',
            'icon' => 'success'
        ];

        // Redirect based on role
        switch ($user['role_id']) {
            case 1: // Administrator
                header("Location: admin_dashboard.php");
                break;
            case 2: // Instructor
                header("Location: instructor_dashboard.php");
                break;
            case 3: // Student
                header("Location: student_dashboard.php");
                break;
            default:
                header("Location: index.php");
                break;
        }
        exit();
    } else {
        $_SESSION['message'] = [
            'title' => 'Error!',
            'text' => 'Invalid username or password.',
            'icon' => 'error'
        ];
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login || ExamEase</title>
  <link rel="stylesheet" href="../assets/css/login.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

  <!-- Login Section -->
  <div class="login-section">
    <div class="login-box">
      <h2>Login</h2>
      <form class="login-form" method="POST">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>

        <button type="submit" name="login" class="login-button">Login</button>

        <div class="login-links">
          <a href="#">Forgot Password?</a>
          <p>Don't have an account? <a href="#" class="sign-up-link">Sign Up</a></p>
        </div>
      </form>
    </div>
  </div>

  <!-- Register Section -->
  <div class="register-section">
    <div class="register-box">
      <h2>Register</h2>
      <form class="register-form" method="POST">
        <label for="role_id">Role:</label>
        <select name="role_id" required>
          <option value="1">Administrator</option>
          <option value="2">Instructor</option>
          <option value="3">Student</option>
        </select>

        <label for="name">Name</label>
        <input type="text" id="name" name="name" required>

        <label for="username">Username</label>
        <input type="text" id="username" name="username" required>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>

        <button type="submit" name="register" class="register-button">Register</button>

        <div class="register-links">
          <p>Already have an account? <a href="#" class="login-link">Login</a></p>
        </div>
      </form>
    </div>
  </div>

  <script src="../assets/js/login.js"></script>

  <?php
if (isset($_SESSION['message'])) {
    echo "<script>
            Swal.fire({
                title: '{$_SESSION['message']['title']}',
                text: '{$_SESSION['message']['text']}',
                icon: '{$_SESSION['message']['icon']}',
                showConfirmButton: false,
                timer: 2000,
                width: '80px',
                heightAuto: false,
                position: 'top-start',
                customClass: {
                    popup: 'custom-swal-popup'
                }
            });
          </script>";
    unset($_SESSION['message']);
}
?>


</body>
</html>
