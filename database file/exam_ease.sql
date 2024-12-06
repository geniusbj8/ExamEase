-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 06, 2024 at 11:46 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `exam_ease`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `answers`
--

CREATE TABLE `answers` (
  `answer_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `answer_text` text NOT NULL,
  `is_correct` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `answers`
--

INSERT INTO `answers` (`answer_id`, `question_id`, `answer_text`, `is_correct`, `created_at`, `updated_at`) VALUES
(1, 1, 'Jomo Kenyatta', 1, '2024-11-14 13:26:02', '2024-11-14 13:26:02'),
(2, 1, 'Daniel arap Moi', 0, '2024-11-14 13:26:02', '2024-11-14 13:26:02'),
(3, 1, 'Mwai Kibaki', 0, '2024-11-14 13:26:02', '2024-11-14 13:26:02'),
(4, 1, 'Uhuru Kenyatta', 0, '2024-11-14 13:26:02', '2024-11-14 13:26:02'),
(5, 2, 'Tea', 1, '2024-11-14 13:26:02', '2024-11-14 13:26:02'),
(6, 2, 'Coffee', 0, '2024-11-14 13:26:02', '2024-11-14 13:26:02'),
(7, 2, 'Sugarcane', 0, '2024-11-14 13:26:02', '2024-11-14 13:26:02'),
(8, 2, 'Maize', 0, '2024-11-14 13:26:02', '2024-11-14 13:26:02'),
(9, 3, 'Deforestation', 1, '2024-11-14 13:26:02', '2024-11-14 13:26:02'),
(10, 3, 'Flooding', 0, '2024-11-14 13:26:02', '2024-11-14 13:26:02'),
(11, 3, 'Water Shortage', 0, '2024-11-14 13:26:02', '2024-11-14 13:26:02'),
(12, 3, 'Urbanization', 0, '2024-11-14 13:26:02', '2024-11-14 13:26:02'),
(13, 4, 'Central Processing Unit', 1, '2024-11-14 13:26:02', '2024-11-14 13:26:02'),
(14, 4, 'Computer Programming Unit', 0, '2024-11-14 13:26:02', '2024-11-14 13:26:02'),
(15, 4, 'Control Processing Unit', 0, '2024-11-14 13:26:02', '2024-11-14 13:26:02'),
(16, 4, 'Central Program Unit', 0, '2024-11-14 13:26:02', '2024-11-14 13:26:02'),
(17, 5, 'Kenyan Shilling', 1, '2024-11-14 13:26:02', '2024-11-14 13:26:02'),
(18, 5, 'US Dollar', 0, '2024-11-14 13:26:02', '2024-11-14 13:26:02'),
(19, 5, 'Euro', 0, '2024-11-14 13:26:02', '2024-11-14 13:26:02'),
(20, 5, 'Pound Sterling', 0, '2024-11-14 13:26:02', '2024-11-14 13:26:02'),
(21, 6, '1963', 1, '2024-11-14 13:26:02', '2024-11-14 13:26:02'),
(22, 6, '1970', 0, '2024-11-14 13:26:02', '2024-11-14 13:26:02'),
(23, 6, '1980', 0, '2024-11-14 13:26:02', '2024-11-14 13:26:02'),
(24, 6, '1955', 0, '2024-11-14 13:26:02', '2024-11-14 13:26:02'),
(25, 7, 'Mount Kenya', 1, '2024-11-14 13:26:02', '2024-11-14 13:26:02'),
(26, 7, 'Mount Kilimanjaro', 0, '2024-11-14 13:26:02', '2024-11-14 13:26:02'),
(27, 7, 'Mount Everest', 0, '2024-11-14 13:26:02', '2024-11-14 13:26:02'),
(28, 7, 'Mount Elgon', 0, '2024-11-14 13:26:02', '2024-11-14 13:26:02'),
(29, 8, 'Ngugi wa Thiong’o', 1, '2024-11-14 13:26:02', '2024-11-14 13:26:02'),
(30, 8, 'Grace Ogot', 0, '2024-11-14 13:26:02', '2024-11-14 13:26:02'),
(31, 8, 'Meja Mwangi', 0, '2024-11-14 13:26:02', '2024-11-14 13:26:02'),
(32, 8, 'Micere Mugo', 0, '2024-11-14 13:26:02', '2024-11-14 13:26:02'),
(33, 9, 'Malaria', 1, '2024-11-14 13:26:02', '2024-11-14 13:26:02'),
(34, 9, 'HIV/AIDS', 0, '2024-11-14 13:26:02', '2024-11-14 13:26:02'),
(35, 9, 'Cancer', 0, '2024-11-14 13:26:02', '2024-11-14 13:26:02'),
(36, 9, 'Tuberculosis', 0, '2024-11-14 13:26:02', '2024-11-14 13:26:02'),
(97, 10, 'Jomo Kenyatta', 1, '2024-11-19 09:27:44', '2024-11-19 09:27:44'),
(98, 10, 'Daniel arap Moi', 0, '2024-11-19 09:27:45', '2024-11-19 09:27:45'),
(99, 10, 'Mwai Kibaki', 0, '2024-11-19 09:27:45', '2024-11-19 09:27:45'),
(100, 10, 'Uhuru Kenyatta', 0, '2024-11-19 09:27:45', '2024-11-19 09:27:45');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `course_id` int(11) NOT NULL,
  `course_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`course_id`, `course_name`, `description`, `created_at`) VALUES
(1, 'Kenyan History 101', 'Introduction to the history of Kenya and its people', '2024-11-14 13:26:01'),
(2, 'Agricultural Economics', 'Study of agricultural economics in Kenya', '2024-11-14 13:26:01'),
(3, 'Environmental Studies', 'environmental solutions', '2024-11-14 13:26:01'),
(4, 'Computer Science 101', 'Introduction to computer science and programming', '2024-11-14 13:26:01'),
(5, 'Economics 101', 'Basic economics and its impact on Kenya', '2024-11-14 13:26:01'),
(6, 'Law and Governance', 'Overview of Kenyan law and governance structures', '2024-11-14 13:26:01'),
(7, 'Kenyan Geography', 'The physical and political geography of Kenya', '2024-11-14 13:26:01'),
(8, 'Literature in Kenya', 'Exploring Kenya’s literary history and works', '2024-11-14 13:26:01'),
(9, 'Public Health in Kenya', 'Study of public health systems in Kenya', '2024-11-14 13:26:01'),
(10, 'Kenyan Political Science', 'Understanding political systems in Kenya', '2024-11-14 13:26:01'),
(11, 'Software 101', 'Software Engineering', '2024-11-15 11:22:58'),
(13, 'AI and Machine Learning', 'Machine models', '2024-11-15 13:45:33'),
(14, 'Database Concepts', 'Database manipulation and dictionaries', '2024-11-15 13:51:05'),
(16, 'Swahili Language 101', 'Kiswahili sanifuu', '2024-11-16 08:07:41');

-- --------------------------------------------------------

--
-- Table structure for table `enrollments`
--

CREATE TABLE `enrollments` (
  `enrollment_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enrollments`
--

INSERT INTO `enrollments` (`enrollment_id`, `user_id`, `course_id`) VALUES
(1, 4, 1),
(2, 4, 2),
(3, 4, 3),
(4, 4, 4),
(5, 4, 5),
(6, 5, 6),
(7, 5, 7),
(8, 5, 8),
(9, 5, 9),
(10, 5, 10),
(11, 6, 1),
(12, 6, 2),
(13, 6, 3),
(14, 6, 4),
(15, 6, 5),
(16, 7, 6),
(17, 7, 7),
(18, 7, 8),
(19, 7, 9),
(20, 7, 10),
(21, 8, 1),
(22, 8, 2),
(23, 8, 3),
(24, 8, 4),
(25, 8, 5),
(26, 9, 6),
(27, 9, 7),
(28, 9, 8),
(29, 9, 9),
(30, 9, 10),
(36, 4, 6),
(38, 4, 10),
(42, 17, 3),
(43, 17, 5),
(44, 17, 1),
(45, 4, 13),
(46, 19, 1),
(47, 19, 5),
(48, 19, 10);

-- --------------------------------------------------------

--
-- Table structure for table `exams`
--

CREATE TABLE `exams` (
  `exam_id` int(11) NOT NULL,
  `exam_name` varchar(100) NOT NULL,
  `course_id` int(11) NOT NULL,
  `duration` int(11) NOT NULL,
  `exam_description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `exams`
--

INSERT INTO `exams` (`exam_id`, `exam_name`, `course_id`, `duration`, `exam_description`, `created_at`, `updated_at`) VALUES
(1, 'Kenyan History 101 - Midterm', 1, 120, 'Midterm exam for Kenyan History 101', '2024-11-14 13:26:01', '2024-11-18 06:00:10'),
(2, 'Agricultural Economics - Final Exam', 2, 90, 'Final exam for Agricultural Economics', '2024-11-14 13:26:01', '2024-11-14 13:26:01'),
(3, 'Environmental Studies - Final Exam', 3, 100, 'Final exam for Environmental Studies', '2024-11-14 13:26:01', '2024-11-14 13:26:01'),
(4, 'Computer Science 101 - Midterm', 4, 90, 'Midterm exam for Computer Science 101', '2024-11-14 13:26:01', '2024-11-14 13:26:01'),
(5, 'Economics 101 - Final Exam', 5, 120, 'Final exam for Economics 101', '2024-11-14 13:26:01', '2024-11-14 13:26:01'),
(6, 'Law and Governance - Midterm', 6, 90, 'Midterm exam for Law and Governance', '2024-11-14 13:26:01', '2024-11-14 13:26:01'),
(7, 'Kenyan Geography - Final Exam', 7, 120, 'Final exam for Kenyan Geography', '2024-11-14 13:26:01', '2024-11-14 13:26:01'),
(8, 'Literature in Kenya - Final Exam', 8, 100, 'Final exam for Literature in Kenya', '2024-11-14 13:26:01', '2024-11-14 13:26:01'),
(9, 'Public Health in Kenya - Midterm 1', 9, 90, 'Midterm exam for Public Health in Kenya', '2024-11-14 13:26:01', '2024-11-15 13:44:08'),
(10, 'Kenyan Political Science - Final Exam', 10, 120, 'Final exam for Kenyan Political Science', '2024-11-14 13:26:01', '2024-11-18 06:34:05'),
(11, 'Software engineering exam', 11, 16, 'software development principles', '2024-11-15 11:23:30', '2024-11-18 07:44:37'),
(15, 'Kenyan History 101 - Final Exam', 1, 120, 'Final exam for Kenyan History 101', '2024-11-19 10:46:49', '2024-11-19 10:46:49'),
(16, 'Kenyan History 101 - Quiz 1', 1, 30, 'Quiz 1 for Kenyan History 101', '2024-11-19 10:46:49', '2024-11-19 10:46:49'),
(17, 'Kenyan History 101 - Quiz 2', 1, 30, 'Quiz 2 for Kenyan History 101', '2024-11-19 10:46:49', '2024-11-19 10:46:49'),
(18, 'Agricultural Economics - Midterm', 2, 90, 'Midterm exam for Agricultural Economics', '2024-11-19 10:46:49', '2024-11-19 10:46:49'),
(19, 'Agricultural Economics - Quiz 1', 2, 30, 'Quiz 1 for Agricultural Economics', '2024-11-19 10:46:49', '2024-11-19 10:46:49'),
(20, 'Agricultural Economics - Quiz 2', 2, 30, 'Quiz 2 for Agricultural Economics', '2024-11-19 10:46:49', '2024-11-19 10:46:49'),
(21, 'Environmental Studies - Midterm', 3, 100, 'Midterm exam for Environmental Studies', '2024-11-19 10:46:49', '2024-11-19 10:46:49'),
(22, 'Environmental Studies - Quiz 1', 3, 30, 'Quiz 1 for Environmental Studies', '2024-11-19 10:46:49', '2024-11-19 10:46:49'),
(23, 'Environmental Studies - Quiz 2', 3, 30, 'Quiz 2 for Environmental Studies', '2024-11-19 10:46:49', '2024-11-19 10:46:49'),
(24, 'Computer Science 101 - Final Exam', 4, 120, 'Final exam for Computer Science 101', '2024-11-19 10:46:49', '2024-11-19 10:46:49'),
(25, 'Computer Science 101 - Quiz 1', 4, 30, 'Quiz 1 for Computer Science 101', '2024-11-19 10:46:49', '2024-11-19 10:46:49'),
(26, 'Computer Science 101 - Quiz 2', 4, 30, 'Quiz 2 for Computer Science 101', '2024-11-19 10:46:49', '2024-11-19 10:46:49'),
(27, 'Economics 101 - Midterm', 5, 120, 'Midterm exam for Economics 101', '2024-11-19 10:46:49', '2024-11-19 10:46:49'),
(28, 'Economics 101 - Quiz 1', 5, 30, 'Quiz 1 for Economics 101', '2024-11-19 10:46:49', '2024-11-19 10:46:49'),
(29, 'Economics 101 - Quiz 2', 5, 30, 'Quiz 2 for Economics 101', '2024-11-19 10:46:49', '2024-11-19 10:46:49'),
(30, 'Law and Governance - Final Exam', 6, 120, 'Final exam for Law and Governance', '2024-11-19 10:46:49', '2024-11-19 10:46:49'),
(31, 'Law and Governance - Quiz 1', 6, 30, 'Quiz 1 for Law and Governance', '2024-11-19 10:46:49', '2024-11-19 10:46:49'),
(32, 'Law and Governance - Quiz 2', 6, 30, 'Quiz 2 for Law and Governance', '2024-11-19 10:46:49', '2024-11-19 10:46:49'),
(33, 'Kenyan Geography - Midterm', 7, 120, 'Midterm exam for Kenyan Geography', '2024-11-19 10:46:49', '2024-11-19 10:46:49'),
(34, 'Kenyan Geography - Quiz 1', 7, 30, 'Quiz 1 for Kenyan Geography', '2024-11-19 10:46:49', '2024-11-19 10:46:49'),
(35, 'Kenyan Geography - Quiz 2', 7, 30, 'Quiz 2 for Kenyan Geography', '2024-11-19 10:46:49', '2024-11-19 10:46:49'),
(36, 'Literature in Kenya - Midterm', 8, 100, 'Midterm exam for Literature in Kenya', '2024-11-19 10:46:49', '2024-11-19 10:46:49'),
(37, 'Literature in Kenya - Quiz 1', 8, 30, 'Quiz 1 for Literature in Kenya', '2024-11-19 10:46:49', '2024-11-19 10:46:49'),
(39, 'Public Health in Kenya - Final Exam', 9, 120, 'Final exam for Public Health in Kenya', '2024-11-19 10:46:49', '2024-11-19 10:46:49'),
(42, 'Kenyan Political Science - Midterm', 10, 120, 'Midterm exam for Kenyan Political Science', '2024-11-19 10:46:49', '2024-11-19 10:46:49');

-- --------------------------------------------------------

--
-- Table structure for table `exam_grades`
--

CREATE TABLE `exam_grades` (
  `grade_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `exam_id` int(11) NOT NULL,
  `score` decimal(5,2) NOT NULL,
  `graded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `exam_grades`
--

INSERT INTO `exam_grades` (`grade_id`, `user_id`, `exam_id`, `score`, `graded_at`) VALUES
(1, 5, 2, 100.00, '2024-11-14 13:36:11'),
(2, 5, 5, 100.00, '2024-11-14 13:40:08'),
(3, 5, 6, 0.00, '2024-11-14 15:03:30'),
(4, 4, 1, 100.00, '2024-11-15 09:05:53'),
(5, 4, 8, 100.00, '2024-11-15 09:21:50'),
(6, 4, 2, 100.00, '2024-11-15 10:51:36'),
(7, 4, 7, 100.00, '2024-11-15 18:01:34'),
(8, 4, 5, 50.00, '2024-11-16 08:44:08'),
(10, 17, 3, 100.00, '2024-11-18 11:11:33'),
(11, 17, 1, 100.00, '2024-11-19 11:34:14'),
(12, 17, 5, 0.00, '2024-11-19 11:37:55'),
(13, 19, 1, 100.00, '2024-11-21 11:53:29'),
(14, 19, 5, 100.00, '2024-11-21 11:53:47'),
(15, 19, 10, 0.00, '2024-11-21 11:54:03');

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `question_id` int(11) NOT NULL,
  `exam_id` int(11) NOT NULL,
  `question_text` text NOT NULL,
  `question_type` enum('multiple_choice','true_false','short_answer') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`question_id`, `exam_id`, `question_text`, `question_type`, `created_at`, `updated_at`) VALUES
(1, 1, 'Who was the first president of Kenya??', 'multiple_choice', '2024-11-14 13:26:01', '2024-11-15 17:51:33'),
(2, 2, 'What is the most important crop grown in Kenya?', 'multiple_choice', '2024-11-14 13:26:01', '2024-11-14 13:26:01'),
(3, 3, 'Which of the following is a major environmental issue in Kenya?', 'multiple_choice', '2024-11-14 13:26:01', '2024-11-14 13:26:01'),
(4, 4, 'What does CPU stand for in computing?', 'multiple_choice', '2024-11-14 13:26:01', '2024-11-14 13:26:01'),
(5, 5, 'What is the national currency of Kenya?', 'multiple_choice', '2024-11-14 13:26:01', '2024-11-14 13:26:01'),
(6, 6, 'What year did Kenya gain independence?', 'multiple_choice', '2024-11-14 13:26:01', '2024-11-14 13:26:01'),
(7, 7, 'Which of the following is a major mountain in Kenya?', 'multiple_choice', '2024-11-14 13:26:01', '2024-11-14 13:26:01'),
(8, 8, 'Who is known as the father of Kenyan literature?', 'multiple_choice', '2024-11-14 13:26:01', '2024-11-14 13:26:01'),
(9, 9, 'What is the leading cause of death in Kenya?', 'multiple_choice', '2024-11-14 13:26:01', '2024-11-14 13:26:01'),
(10, 10, 'Who was the first president of the Republic of Kenya?', 'multiple_choice', '2024-11-14 13:26:01', '2024-11-14 13:26:01');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `report_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `exam_id` int(11) NOT NULL,
  `score` int(11) NOT NULL,
  `attempts` int(11) NOT NULL DEFAULT 1,
  `average_score` decimal(5,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`role_id`, `role_name`) VALUES
(1, 'Admin'),
(2, 'Instructor'),
(3, 'Student');

-- --------------------------------------------------------

--
-- Table structure for table `student_answers`
--

CREATE TABLE `student_answers` (
  `student_answer_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `exam_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `answer_id` int(11) DEFAULT NULL,
  `answer_text` text DEFAULT NULL,
  `selected_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_answers`
--

INSERT INTO `student_answers` (`student_answer_id`, `user_id`, `exam_id`, `question_id`, `answer_id`, `answer_text`, `selected_at`) VALUES
(1, 5, 2, 2, 5, NULL, '2024-11-14 13:33:52'),
(2, 5, 2, 2, 5, NULL, '2024-11-14 13:36:11'),
(3, 5, 5, 5, 17, NULL, '2024-11-14 13:40:08'),
(4, 5, 6, 6, 22, NULL, '2024-11-14 15:03:29'),
(5, 4, 1, 1, 1, NULL, '2024-11-15 09:05:53'),
(6, 4, 8, 8, 29, NULL, '2024-11-15 09:21:50'),
(7, 4, 2, 2, 5, NULL, '2024-11-15 10:51:36'),
(8, 4, 4, 4, 16, NULL, '2024-11-15 18:00:13'),
(9, 4, 7, 7, 25, NULL, '2024-11-15 18:01:34'),
(10, 4, 5, 5, 17, NULL, '2024-11-16 08:44:08'),
(13, 17, 3, 3, 9, NULL, '2024-11-18 11:11:33'),
(14, 17, 1, 1, 1, NULL, '2024-11-19 11:34:14'),
(15, 17, 5, 5, 18, NULL, '2024-11-19 11:37:55'),
(16, 19, 1, 1, 1, NULL, '2024-11-21 11:53:29'),
(17, 19, 5, 5, 17, NULL, '2024-11-21 11:53:47'),
(18, 19, 10, 10, 98, NULL, '2024-11-21 11:54:03');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `role_id`, `name`, `username`, `password_hash`, `email`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'John Wambua', 'johnw', '749f09bade8aca755660eeb17792da880218d4fbdc4e25fbec279d7fe9f65d70', 'johnw@admin.examease.ac.ke', 1, '2024-11-14 13:26:00', '2024-11-20 11:41:21'),
(2, 2, 'Mary Mwende', 'marymw', '5effd44dd78c1d34095a2d59f4101276a461955bae5faa30d55cdfa8312194d4', 'mary@instructor.examease.ac.ke', 1, '2024-11-14 13:26:00', '2024-11-20 11:49:17'),
(3, 2, 'Julius Kamau', 'juliusk', '52d38123990db422900996fede613fc8b0b3259aad5078a33c265b60a2dc414b', 'julius@instructor.com', 1, '2024-11-14 13:26:00', '2024-11-14 13:26:00'),
(4, 3, 'Nia Nyambura', 'nian', '703b0a3d6ad75b649a28adde7d83c6251da457549263bc7ff45ec709b0a8448b', 'nia@student.examease.ac.ke', 1, '2024-11-14 13:26:00', '2024-11-20 10:55:11'),
(5, 3, 'Joshua Mutua', 'joshuam', 'bd0e63deb1f401e699d86f33e249acccbea154c4483b09c9b59c9b5931ced7c1', 'joshua@student.com', 1, '2024-11-14 13:26:00', '2024-11-14 13:26:00'),
(6, 3, 'Faith Kibet', 'faithk', 'decd41c1b8965a3eb5957d09b08696a3e1b03100430a02a726317a2216567d5c', 'faith@student.com', 1, '2024-11-14 13:26:00', '2024-11-14 13:26:00'),
(7, 3, 'David Otieno', 'davido', '546e3bae809088dd179bd2be96604963056cd3bc58d5728248da6ca757c26cd3', 'david@student.com', 1, '2024-11-14 13:26:00', '2024-11-14 13:26:00'),
(8, 3, 'Wambui Kirimi', 'wambuik', 'a77c016f6fbeaf9c6c30373cc9a144af6881309bc661bece0bc6dc32f1440ab5', 'wambui@student.com', 1, '2024-11-14 13:26:00', '2024-11-14 13:26:00'),
(9, 3, 'Samson Kipkoech', 'samsonk', '8c7f11df82d3319965b4c1de2227e1b863f7590e693eb40f8e806f54422fef29', 'samson@student.com', 1, '2024-11-14 13:26:00', '2024-11-14 13:26:00'),
(12, 3, 'Jessica Golden', 'jGolden', '703b0a3d6ad75b649a28adde7d83c6251da457549263bc7ff45ec709b0a8448b', 'jessicagolden@student.examease.com', 1, '2024-11-14 13:31:36', '2024-11-14 13:31:36'),
(14, 3, 'Aaron Torres', 'aaront', '', 'aaron@student.examease.com', 1, '2024-11-17 17:59:54', '2024-11-17 17:59:54'),
(16, 1, 'Simon Barry', 'simonB', '749f09bade8aca755660eeb17792da880218d4fbdc4e25fbec279d7fe9f65d70', 'simon@admin.examease.ac.ke', 1, '2024-11-18 09:34:32', '2024-11-18 09:34:32'),
(17, 3, 'Lyle Atieno', 'Latieno', '68eaeeaef51a40035b5d3705c4e0ffd68036b6b821361765145f410b0f996e11', 'lyleatieno@student.examease.ac.ke', 1, '2024-11-18 11:10:49', '2024-11-18 11:10:49'),
(19, 3, 'Brian Githinji', 'Brian/EE001', '703b0a3d6ad75b649a28adde7d83c6251da457549263bc7ff45ec709b0a8448b', 'brianwanjohi@student.examease.ac.ke', 1, '2024-11-21 11:52:12', '2024-11-21 11:57:16'),
(20, 1, 'admin', 'admin', '749f09bade8aca755660eeb17792da880218d4fbdc4e25fbec279d7fe9f65d70', 'admin@examease.com', 1, '2024-12-06 10:26:50', '2024-12-06 10:26:50');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD KEY `fk_user_admin` (`user_id`);

--
-- Indexes for table `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`answer_id`),
  ADD KEY `fk_question_answer` (`question_id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`course_id`);

--
-- Indexes for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD PRIMARY KEY (`enrollment_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `exams`
--
ALTER TABLE `exams`
  ADD PRIMARY KEY (`exam_id`),
  ADD KEY `fk_course_exam` (`course_id`);

--
-- Indexes for table `exam_grades`
--
ALTER TABLE `exam_grades`
  ADD PRIMARY KEY (`grade_id`),
  ADD KEY `fk_user_exam_grade` (`user_id`),
  ADD KEY `fk_exam_exam_grade` (`exam_id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`question_id`),
  ADD KEY `fk_exam_question` (`exam_id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`report_id`),
  ADD KEY `fk_user_report` (`user_id`),
  ADD KEY `fk_exam_report` (`exam_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`),
  ADD UNIQUE KEY `role_name` (`role_name`);

--
-- Indexes for table `student_answers`
--
ALTER TABLE `student_answers`
  ADD PRIMARY KEY (`student_answer_id`),
  ADD KEY `fk_user_student_answer` (`user_id`),
  ADD KEY `fk_exam_student_answer` (`exam_id`),
  ADD KEY `fk_question_student_answer` (`question_id`),
  ADD KEY `fk_answer_student_answer` (`answer_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_role` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `answers`
--
ALTER TABLE `answers`
  MODIFY `answer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `course_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `enrollments`
--
ALTER TABLE `enrollments`
  MODIFY `enrollment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `exams`
--
ALTER TABLE `exams`
  MODIFY `exam_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `exam_grades`
--
ALTER TABLE `exam_grades`
  MODIFY `grade_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `question_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `student_answers`
--
ALTER TABLE `student_answers`
  MODIFY `student_answer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `fk_user_admin` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `answers`
--
ALTER TABLE `answers`
  ADD CONSTRAINT `fk_question_answer` FOREIGN KEY (`question_id`) REFERENCES `questions` (`question_id`) ON DELETE CASCADE;

--
-- Constraints for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD CONSTRAINT `enrollments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `enrollments_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`);

--
-- Constraints for table `exams`
--
ALTER TABLE `exams`
  ADD CONSTRAINT `fk_course_exam` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`) ON DELETE CASCADE;

--
-- Constraints for table `exam_grades`
--
ALTER TABLE `exam_grades`
  ADD CONSTRAINT `fk_exam_exam_grade` FOREIGN KEY (`exam_id`) REFERENCES `exams` (`exam_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_user_exam_grade` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `fk_exam_question` FOREIGN KEY (`exam_id`) REFERENCES `exams` (`exam_id`) ON DELETE CASCADE;

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `fk_exam_report` FOREIGN KEY (`exam_id`) REFERENCES `exams` (`exam_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_user_report` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `student_answers`
--
ALTER TABLE `student_answers`
  ADD CONSTRAINT `fk_answer_student_answer` FOREIGN KEY (`answer_id`) REFERENCES `answers` (`answer_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_exam_student_answer` FOREIGN KEY (`exam_id`) REFERENCES `exams` (`exam_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_question_student_answer` FOREIGN KEY (`question_id`) REFERENCES `questions` (`question_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_user_student_answer` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
