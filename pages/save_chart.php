<?php
// Ensure the request is a POST with the image blob
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['chart'])) {
    $chart = $_FILES['chart'];
    $uploadDir = '../charts/';
    $uploadPath = $uploadDir . 'results_chart.png';

    if (move_uploaded_file($chart['tmp_name'], $uploadPath)) {
        echo 'Chart saved successfully';
    } else {
        echo 'Failed to save chart';
    }
} else {
    echo 'No chart received';
}
?>
