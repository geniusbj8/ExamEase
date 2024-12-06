<?php
session_start();

// Ensure user is logged in as a student
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 3) {
    header("Location: login.php");
    exit();
}

// Set user_id from the session
$user_id = $_SESSION['user_id'];

// Include necessary files
require_once('../fpdf/fpdf.php');
require_once('../fpdf/src/autoload.php');  // Autoload FPDI classes

use setasign\Fpdi\Fpdi;  // Using FPDI namespace

// Include database connection
include('../includes/db_connection.php');

try {
    // Fetch student details
    $query_student = $conn->prepare("SELECT name, email FROM users WHERE user_id = ? AND role_id = 3");
    $query_student->bind_param("i", $user_id);
    $query_student->execute();
    $result_student = $query_student->get_result();
    $student = $result_student->fetch_assoc();

    if (!$student) {
        throw new Exception("Student not found.");
    }

    // Fetch student results
    $query_results = $conn->prepare("
        SELECT eg.score, e.exam_name, c.course_name, eg.graded_at
        FROM exam_grades eg
        JOIN exams e ON eg.exam_id = e.exam_id
        JOIN courses c ON e.course_id = c.course_id
        WHERE eg.user_id = ?
        ORDER BY eg.graded_at DESC
    ");
    $query_results->bind_param("i", $user_id);
    $query_results->execute();
    $results = $query_results->get_result()->fetch_all(MYSQLI_ASSOC);

} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}

// Generate PDF with FPDF
if (isset($_GET['download_pdf'])) {
    class PDF extends FPDF {
        // Header
        function Header() {
            $this->SetFont('Arial', 'B', 14);
            $this->SetTextColor(44, 62, 80); // Dark color similar to header
            $this->Cell(0, 10, 'Student Results', 0, 1, 'C');
            $this->Ln(5);
        }

        // Footer
        function Footer() {
            $this->SetY(-15);
            $this->SetFont('Arial', 'I', 8);
            $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
        }

        // Table header
        function TableHeader() {
            $this->SetFont('Arial', 'B', 12);
            $this->SetFillColor(52, 152, 219);  // Blue color for header
            $this->SetTextColor(255, 255, 255); // White text
            $this->Cell(60, 10, 'Course', 1, 0, 'C', true);
            $this->Cell(60, 10, 'Exam', 1, 0, 'C', true);
            $this->Cell(30, 10, 'Score', 1, 0, 'C', true);
            $this->Cell(40, 10, 'Graded At', 1, 1, 'C', true);
        }

        // Table rows
        function TableRow($results) {
            $this->SetFont('Arial', '', 12);
            $this->SetTextColor(0, 0, 0); // Black text
            foreach ($results as $row) {
                $this->Cell(60, 10, $row['course_name'], 1);
                $this->Cell(60, 10, $row['exam_name'], 1);
                $this->Cell(30, 10, $row['score'], 1, 0, 'C');
                $this->Cell(40, 10, $row['graded_at'], 1, 1);
            }
        }

        // Student Info
        function StudentInfo($student) {
            $this->SetFont('Arial', 'B', 12);
            $this->Cell(40, 10, 'Name: ', 0, 0);
            $this->SetFont('Arial', '', 12);
            $this->Cell(0, 10, $student['name'], 0, 1);

            $this->SetFont('Arial', 'B', 12);
            $this->Cell(40, 10, 'Email: ', 0, 0);
            $this->SetFont('Arial', '', 12);
            $this->Cell(0, 10, $student['email'], 0, 1);

            $this->Ln(10);
        }
    }

    $pdf = new PDF();
    $pdf->AddPage();

    // Student Info
    $pdf->StudentInfo($student);

    // Table
    $pdf->TableHeader();
    $pdf->TableRow($results);

    // Add Chart as Image
    $chartImage = "../charts/results_chart.png"; // Ensure this is the correct path to the chart image
    if (file_exists($chartImage)) {
        $pdf->AddPage();
        $pdf->Image($chartImage, 10, 30, 190); // Position the chart in the PDF
    }

    $pdf->Output('D', 'Student_Results.pdf');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Results</title>
    <link rel="stylesheet" href="../assets/css/student_result.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container">
        <h1>Student Results</h1>
        <p><strong>Name:</strong> <?= htmlspecialchars($student['name']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($student['email']) ?></p>
        
        <h2>Grades</h2>
        <?php if ($results): ?>
            <table>
                <thead>
                    <tr>
                        <th>Course</th>
                        <th>Exam</th>
                        <th>Score</th>
                        <th>Graded At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results as $result): ?>
                        <tr>
                            <td><?= htmlspecialchars($result['course_name']) ?></td>
                            <td><?= htmlspecialchars($result['exam_name']) ?></td>
                            <td><?= htmlspecialchars($result['score']) ?></td>
                            <td><?= htmlspecialchars($result['graded_at']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No results found.</p>
        <?php endif; ?>
        
        <h2>Performance Graph</h2>
        <canvas id="resultsChart"></canvas>
        
        <a href="?download_pdf=1" class="download-button">Download as PDF</a>
        <a href="student_dashboard.php" class="back-button">Back to Dashboard</a>
    </div>

    <script>
        // Chart.js Implementation
        const ctx = document.getElementById('resultsChart').getContext('2d');
        const chartData = {
            labels: <?= json_encode(array_column($results, 'exam_name')) ?>,
            datasets: [{
                label: 'Scores',
                data: <?= json_encode(array_column($results, 'score')) ?>,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        };

        const chart = new Chart(ctx, {
            type: 'bar',
            data: chartData,
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Save chart as an image using Blob
        const chartCanvas = document.getElementById('resultsChart');
chartCanvas.toBlob((blob) => {
    const formData = new FormData();
    formData.append('chart', blob, 'results_chart.png');
    
    fetch('../charts/save_chart.php', { 
        method: 'POST', 
        body: formData 
    })
    .then(response => response.text())
    .then(responseText => {
        console.log(responseText); // Check the server response for debugging
    })
    .catch(error => {
        console.error('Error saving chart:', error);
    });
});

    </script>
</body>
</html>
