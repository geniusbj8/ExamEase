<?php
// submit_contact.php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include the database connection file
    include('../includes/db_connection.php');

    // Get the form data and sanitize it
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    // Prepare SQL statement to insert data into the contacts table
    $sql = "INSERT INTO contacts (name, email, subject, message) VALUES (?, ?, ?, ?)";

    // Prepare the SQL query
    if ($stmt = mysqli_prepare($conn, $sql)) {
        // Bind the parameters to the prepared statement
        mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $subject, $message);

        // Execute the statement
        if (mysqli_stmt_execute($stmt)) {
            // Return success if insertion is successful
            echo 'success';
        } else {
            // Return an error message if something went wrong
            echo 'Error: ' . mysqli_error($conn);
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    } else {
        // Return error message if the query could not be prepared
        echo 'Error preparing statement: ' . mysqli_error($conn);
    }

    // Close the connection
    mysqli_close($conn);
}
?>
