<?php
// index.php

// Including the database connection file
include('../includes/db_connection.php'); // Assuming you have this file to manage DB connection
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ExamEase || Online Exam System</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  
  <!-- SweetAlert cdn -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
  <!-- Header Section -->
  <header>
    <div class="logo"><a href="#">ExamEase</a></div>
    <nav class="navbar">
      <ul>
        <li><a href="#">Home</a></li>
        <li><a href="#">About Us</a></li>
        <li><a href="#">Contact</a></li>
        <li><button class="login-button" onclick="window.location.href='login.php'">Login</button></li>
      </ul>
    </nav>
  </header>

  <!-- Main Section -->
  <div class="main-section">
    <div class="welcome">
      <h2 class="welcome-title">
        Welcome to ExamEase
      </h2>
      <p class="welcome-description">
        Your number one Online Exam Management System.
      </p>
    </div>
  </div>

  <!-- About Section -->
  <div class="about-section">
    <div class="about">
      <h2 class="about-title">
        About Us
      </h2>
      <p class="about-description">
        Your number one Online Exam Management System.
      </p>
    </div>
  </div>

  <!-- Contact Section -->
  <section class="contact-section">
    <div class="contact">
      <h2 class="contact-title">Contact Us</h2>
      <p class="contact-description">
        Have questions? We'd love to hear from you. Fill out the form below and we'll get back to you soon.
      </p>

      <!-- Contact Form -->
      <form id="contactForm" method="POST" class="contact-form">
        <label for="name">Name</label>
        <input type="text" id="name" name="name" required>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>

        <label for="subject">Subject</label>
        <input type="text" id="subject" name="subject">

        <label for="message">Message</label>
        <textarea id="message" name="message" rows="5" required></textarea>

        <button type="submit" class="submit-button">Send Message</button>
      </form>
    </div>
  </section>

  <!-- Footer Section -->
  <footer class="footer">
    <div class="footer-content">
      <div class="footer-section-about">
        <h3>ExamEase</h3>
        <p>Your trusted online exam management system.</p>
      </div>

      <div class="footer-section links">
        <h4>Quick Links</h4>
        <ul>
          <li><a href="#">Home</a></li>
          <li><a href="#">About Us</a></li>
          <li><a href="#">Contact</a></li>
          <li><a href="#">Support</a></li>
        </ul>
      </div>

      <div class="footer-section-contact">
        <h4>Contact Us</h4>
        <p><i class="fas fa-phone"></i> +254 768 706 146</p>
        <p><i class="fas fa-envelope"></i> benjaminjustin58@gmail.com</p>
      </div>

      <div class="footer-section social">
        <h4>Follow Us</h4>
        <a href="#"><i class="fab fa-facebook-f"></i></a>
        <a href="#"><i class="fab fa-twitter"></i></a>
        <a href="#"><i class="fab fa-instagram"></i></a>
      </div>
    </div>
    <div class="footer-bottom">
      &copy; 2024 ExamEase | All rights reserved
    </div>
  </footer>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="../assets/js/script.js"></script>
</body>

</html>
