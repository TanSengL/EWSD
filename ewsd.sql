-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 24, 2024 at 11:49 AM
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
-- Database: `ewsd`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `age` int(11) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `age`, `email`, `username`, `password`) VALUES
(1, 'Admin User', 30, 'admin@example.com', 'admin', 'admin123');

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `tutor_id` int(11) DEFAULT NULL,
  `appointment_date` date DEFAULT NULL,
  `appointment_time` time DEFAULT NULL,
  `appointment_type` varchar(20) DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `status` enum('Pending','Accepted','Declined') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `student_id`, `tutor_id`, `appointment_date`, `appointment_time`, `appointment_type`, `reason`, `status`) VALUES
(1, 1, 1, '2024-04-16', '08:00:00', 'real', 's', 'Accepted'),
(2, 1, 1, '2024-04-16', '08:00:00', 'virtual', 'z', 'Declined'),
(3, 1, 1, '2024-04-16', '08:00:00', 'virtual', 'z', 'Declined'),
(4, 2, 2, '2024-04-17', '12:00:00', 'real', '222', 'Declined'),
(5, 2, 2, '2024-04-17', '00:00:00', 'real', 'aaa', 'Pending'),
(6, 2, 2, '2024-04-17', '11:00:00', 'real', 'aaa', 'Pending'),
(7, 2, 2, '2024-04-17', '09:00:00', 'virtual', 'aaa', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `coursework`
--

CREATE TABLE `coursework` (
  `id` int(11) NOT NULL,
  `tutor_id` int(11) DEFAULT NULL,
  `coursework_name` varchar(255) DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `upload_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `coursework`
--

INSERT INTO `coursework` (`id`, `tutor_id`, `coursework_name`, `due_date`, `file_path`, `upload_date`) VALUES
(1, 1, 'test', '2024-04-15', 'uploads/Case_National_Packaging_Waste_Database.pdf', '2024-04-14 19:17:46'),
(2, 1, 'test', '2024-04-15', 'uploads/Case_National_Packaging_Waste_Database.pdf', '2024-04-14 19:20:55'),
(3, 1, 'ss', '2024-04-15', 'uploads/Case_National_Packaging_Waste_Database.pdf', '2024-04-14 19:21:09'),
(4, 1, 'xxx', '2024-04-03', 'uploads/Case_National_Packaging_Waste_Database.pdf', '2024-04-14 19:21:30'),
(5, 1, 'xxx', '2024-04-03', 'uploads/Case_National_Packaging_Waste_Database.pdf', '2024-04-14 19:23:02'),
(6, 1, 'ssss', '2024-04-15', 'uploads/Case_National_Packaging_Waste_Database.pdf', '2024-04-14 19:23:08'),
(7, 1, 'dfdd', '2024-04-15', 'uploads/Case_National_Packaging_Waste_Database.pdf', '2024-04-14 19:25:33'),
(8, 1, 'dfdd', '2024-04-15', 'uploads/Case_National_Packaging_Waste_Database.pdf', '2024-04-14 19:30:00'),
(9, 1, 'dfdd', '2024-04-15', 'uploads/Case_National_Packaging_Waste_Database.pdf', '2024-04-14 19:30:40'),
(10, 1, 'dfdd', '2024-04-15', 'uploads/Case_National_Packaging_Waste_Database.pdf', '2024-04-14 19:31:02'),
(11, 1, 'dfdd', '2024-04-15', 'uploads/Case_National_Packaging_Waste_Database.pdf', '2024-04-14 19:32:17'),
(12, 1, 'dfdd', '2024-04-15', 'uploads/Case_National_Packaging_Waste_Database.pdf', '2024-04-14 19:32:38'),
(13, 1, 'dfdd', '2024-04-15', 'uploads/Case_National_Packaging_Waste_Database.pdf', '2024-04-14 19:32:44'),
(14, 1, 'dfdd', '2024-04-15', 'uploads/Case_National_Packaging_Waste_Database.pdf', '2024-04-14 19:33:03'),
(15, 1, 'dfdd', '2024-04-15', 'uploads/Case_National_Packaging_Waste_Database.pdf', '2024-04-14 19:33:14'),
(16, 1, 'dfdd', '2024-04-15', 'uploads/Case_National_Packaging_Waste_Database.pdf', '2024-04-14 19:33:30'),
(17, 1, 'dfdd', '2024-04-15', 'uploads/Case_National_Packaging_Waste_Database.pdf', '2024-04-14 19:33:38'),
(18, 1, 'dfdd', '2024-04-15', 'uploads/Case_National_Packaging_Waste_Database.pdf', '2024-04-14 19:33:50'),
(19, 1, 'dfdd', '2024-04-15', 'uploads/Case_National_Packaging_Waste_Database.pdf', '2024-04-14 19:34:31'),
(20, 1, 'dfdd', '2024-04-15', 'uploads/Case_National_Packaging_Waste_Database.pdf', '2024-04-14 19:34:50'),
(21, 1, 'dfdd', '2024-04-15', 'uploads/Case_National_Packaging_Waste_Database.pdf', '2024-04-14 19:35:23'),
(22, 1, 'sddd', '2024-04-15', 'uploads/Case_National_Packaging_Waste_Database.pdf', '2024-04-14 19:35:36'),
(23, 1, 'aaaa', '2024-04-15', 'uploads/Case_National_Packaging_Waste_Database.pdf', '2024-04-14 19:36:19'),
(24, 1, 'aaaa', '2024-04-15', 'uploads/Case_National_Packaging_Waste_Database.pdf', '2024-04-14 19:37:02'),
(25, 1, 'aaaa', '2024-04-15', 'uploads/Case_National_Packaging_Waste_Database.pdf', '2024-04-14 19:37:28'),
(26, 1, 'aaaa', '2024-04-15', 'uploads/Case_National_Packaging_Waste_Database.pdf', '2024-04-14 19:38:04'),
(27, 1, 'aaaa', '2024-04-15', 'uploads/Case_National_Packaging_Waste_Database.pdf', '2024-04-14 19:38:11'),
(28, 1, 'ss', '2024-04-15', 'uploads/Case_National_Packaging_Waste_Database.pdf', '2024-04-14 19:38:21'),
(29, 1, 'ss', '2024-04-15', 'uploads/Case_National_Packaging_Waste_Database.pdf', '2024-04-14 19:39:31'),
(30, 1, 'ss', '2024-04-15', 'uploads/Case_National_Packaging_Waste_Database.pdf', '2024-04-14 19:39:33'),
(31, 1, 'aa', '2024-04-15', 'uploads/Case_National_Packaging_Waste_Database.pdf', '2024-04-14 19:39:45'),
(32, 1, 'aa', '2024-04-15', 'uploads/Case_National_Packaging_Waste_Database.pdf', '2024-04-14 19:40:34'),
(33, 1, 'aa', '2024-04-15', 'uploads/Case_National_Packaging_Waste_Database.pdf', '2024-04-14 19:41:47'),
(36, 2, 'saa', '2024-04-15', 'uploads/Case_National_Packaging_Waste_Database.pdf', '2024-04-14 20:22:51'),
(38, 2, 'ttt', '2024-04-15', 'uploads/Case_National_Packaging_Waste_Database.pdf', '2024-04-15 13:37:27'),
(39, 2, '111', '2024-04-15', 'uploads/Case_National_Packaging_Waste_Database.pdf', '2024-04-15 13:44:10'),
(41, 1, 'aa', '2024-04-23', 'uploads/TestPDFfile (3).pdf', '2024-04-23 08:41:32');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `age` int(11) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `assigned_tutor_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `name`, `age`, `email`, `username`, `password`, `assigned_tutor_id`) VALUES
(1, 'John Doe', 20, 'john@example.com', 'student', 'student123', 1),
(2, 'test', 30, 'test@gmail.com', 'test', '1234', 2);

-- --------------------------------------------------------

--
-- Table structure for table `studentscourseworks`
--

CREATE TABLE `studentscourseworks` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `coursework_name` varchar(255) NOT NULL,
  `comment` text NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `studentscourseworks`
--

INSERT INTO `studentscourseworks` (`id`, `student_id`, `coursework_name`, `comment`, `file_path`, `uploaded_at`) VALUES
(1, 2, 'abc', 'abcd', 'student_uploads/Case_National_Packaging_Waste_Database.pdf', '2024-04-17 14:09:31'),
(2, 2, 'abc', 'abcd', 'student_uploads/Case_National_Packaging_Waste_Database.pdf', '2024-04-17 14:09:35'),
(3, 2, 'abc', 'abcd', 'student_uploads/Case_National_Packaging_Waste_Database.pdf', '2024-04-17 14:14:02'),
(4, 2, 'abc', 'abcd', 'student_uploads/Case_National_Packaging_Waste_Database.pdf', '2024-04-17 14:14:47'),
(5, 2, 'abc', 'abcd', 'student_uploads/Case_National_Packaging_Waste_Database.pdf', '2024-04-17 14:15:12'),
(6, 2, 'abc', 'abcd', 'student_uploads/Case_National_Packaging_Waste_Database.pdf', '2024-04-17 14:16:18'),
(7, 2, 'abc', 'abcd', 'student_uploads/Case_National_Packaging_Waste_Database.pdf', '2024-04-17 14:16:55'),
(8, 2, 'abc', 'abcd', 'student_uploads/Case_National_Packaging_Waste_Database.pdf', '2024-04-17 14:17:54'),
(9, 2, 'abc', 'abcd', 'student_uploads/Case_National_Packaging_Waste_Database.pdf', '2024-04-17 14:20:54'),
(10, 2, 'abc', 'abcd', 'student_uploads/Case_National_Packaging_Waste_Database.pdf', '2024-04-17 14:22:01'),
(11, 2, 'abc', 'abcd', 'student_uploads/Case_National_Packaging_Waste_Database.pdf', '2024-04-17 14:22:03'),
(12, 2, 'abc', 'abcd', 'student_uploads/Case_National_Packaging_Waste_Database.pdf', '2024-04-17 14:22:54'),
(13, 2, 'abc', 'abcd', 'student_uploads/Case_National_Packaging_Waste_Database.pdf', '2024-04-17 14:23:16'),
(14, 2, 'abc', 'abcd', 'student_uploads/Case_National_Packaging_Waste_Database.pdf', '2024-04-17 14:23:51'),
(15, 1, 'sxdx', 'xxx', 'student_uploads/Case_National_Packaging_Waste_Database.pdf', '2024-04-17 14:24:10'),
(16, 1, 'sxdx', 'xxx', 'student_uploads/Case_National_Packaging_Waste_Database.pdf', '2024-04-17 14:24:13'),
(17, 1, 'sxdx', 'xxx', 'student_uploads/Case_National_Packaging_Waste_Database.pdf', '2024-04-17 14:24:24'),
(19, 1, 'sxdx', 'xxx', 'student_uploads/Case_National_Packaging_Waste_Database.pdf', '2024-04-17 14:29:39'),
(23, 1, 'sxdx', 'xxx', 'student_uploads/Case_National_Packaging_Waste_Database.pdf', '2024-04-17 14:39:42'),
(25, 1, 'sxdx', 'xxx', 'student_uploads/Case_National_Packaging_Waste_Database.pdf', '2024-04-17 14:40:29'),
(26, 1, 'sxdx', 'xxx', 'student_uploads/Case_National_Packaging_Waste_Database.pdf', '2024-04-17 14:40:43'),
(27, 1, 'sxdx', 'xxx', 'student_uploads/Case_National_Packaging_Waste_Database.pdf', '2024-04-17 14:41:00'),
(28, 1, 'sxdx', 'xxx', 'student_uploads/Case_National_Packaging_Waste_Database.pdf', '2024-04-17 14:42:56');

-- --------------------------------------------------------

--
-- Table structure for table `student_messages`
--

CREATE TABLE `student_messages` (
  `message_id` int(11) NOT NULL,
  `sender_id` int(11) DEFAULT NULL,
  `receiver_id` int(11) DEFAULT NULL,
  `message_content` text DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `tutor_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_messages`
--

INSERT INTO `student_messages` (`message_id`, `sender_id`, `receiver_id`, `message_content`, `timestamp`, `tutor_id`) VALUES
(4, 2, 2, 'dssds', '2024-04-23 07:47:00', 2),
(5, 1, 1, 'efghi', '2024-04-23 07:51:42', 1),
(6, 1, 1, 'hello', '2024-04-23 07:59:30', 1),
(7, 1, 1, 'huuuuu', '2024-04-23 08:00:10', 1),
(8, 1, 1, 'hello john', '2024-04-23 08:42:34', 1),
(9, 1, 1, 'hi john', '2024-04-23 09:02:25', 1);

-- --------------------------------------------------------

--
-- Table structure for table `student_notification`
--

CREATE TABLE `student_notification` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `seen` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_notification`
--

INSERT INTO `student_notification` (`id`, `student_id`, `message`, `seen`, `created_at`) VALUES
(1, 1, 'You have been allocated a new tutor.', 1, '2024-04-23 05:42:15'),
(2, 1, 'You have been allocated a new tutor.', 1, '2024-04-23 05:47:26'),
(3, 1, 'You have been allocated a new tutor.', 1, '2024-04-23 05:50:17'),
(4, 1, 'You have been allocated a new tutor.', 1, '2024-04-23 05:51:31'),
(5, 1, 'You have been allocated a new tutor.', 1, '2024-04-23 05:53:02'),
(6, 1, 'You have been allocated a new tutor.', 1, '2024-04-23 06:01:43'),
(7, 1, 'You have been allocated a new tutor.', 1, '2024-04-23 06:06:49'),
(8, 1, 'You have been allocated a new tutor.', 1, '2024-04-23 06:06:56'),
(9, 1, 'You have been allocated a new tutor.', 1, '2024-04-23 06:09:14'),
(10, 1, 'You have been allocated a new tutor.', 1, '2024-04-23 06:09:34'),
(11, 1, 'You have been allocated a new tutor.', 1, '2024-04-23 06:10:18'),
(12, 1, 'You have been allocated a new tutor.', 1, '2024-04-23 06:13:02'),
(13, 1, 'You have been allocated a new tutor.', 1, '2024-04-23 06:13:48'),
(14, 2, 'You have been allocated a new tutor.', 1, '2024-04-23 06:14:14'),
(15, 1, 'You have been allocated a new tutor.', 1, '2024-04-23 08:00:51'),
(16, 1, 'You have been allocated a new tutor.', 1, '2024-04-23 08:50:05'),
(17, 1, 'You have been allocated a new tutor.', 1, '2024-04-23 08:50:20'),
(18, 1, 'You have been allocated a new tutor.', 1, '2024-04-23 12:22:20'),
(19, 1, 'You have been allocated a new tutor.', 1, '2024-04-23 12:25:12'),
(20, 1, 'You have been allocated a new tutor.', 1, '2024-04-23 12:28:25'),
(21, 1, 'You have been allocated a new tutor.', 1, '2024-04-23 12:29:09'),
(22, 1, 'You have been allocated a new tutor.', 1, '2024-04-23 12:30:05'),
(23, 1, 'You have been allocated a new tutor.', 1, '2024-04-23 12:30:14'),
(24, 1, 'You have been allocated a new tutor.', 1, '2024-04-23 12:33:13');

-- --------------------------------------------------------

--
-- Table structure for table `tutors`
--

CREATE TABLE `tutors` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `age` int(11) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `assigned_student_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tutors`
--

INSERT INTO `tutors` (`id`, `name`, `age`, `email`, `username`, `password`, `assigned_student_id`) VALUES
(1, 'Tutor Person', 25, 'tutor@example.com', 'tutor', 'tutor123', 1),
(2, 'test', 11, '11@gmail.com', 'test11', '1234', 2),
(3, 'Bee / math', 30, 'test@gmail.com', 'bee', 'bee1234', NULL),
(4, 'amin', 23, 'amin@yahoo.com', 'amin123', 'amin123', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tutor_messages`
--

CREATE TABLE `tutor_messages` (
  `message_id` int(11) NOT NULL,
  `sender_id` int(11) DEFAULT NULL,
  `receiver_id` int(11) DEFAULT NULL,
  `message_content` text DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `student_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tutor_messages`
--

INSERT INTO `tutor_messages` (`message_id`, `sender_id`, `receiver_id`, `message_content`, `timestamp`, `student_id`) VALUES
(8, 2, 2, 'aaaaaa', '2024-04-23 07:47:13', 2),
(9, 1, 1, 'abcde', '2024-04-23 07:51:33', 1),
(10, 1, 1, 'hi', '2024-04-23 07:59:19', 1),
(11, 1, 1, 'uuuuuu', '2024-04-23 08:00:01', 1),
(12, 1, 1, 'hello sir', '2024-04-23 08:42:21', 1),
(13, 1, 1, 'hi student\r\n', '2024-04-23 09:02:06', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tutor_notification`
--

CREATE TABLE `tutor_notification` (
  `id` int(11) NOT NULL,
  `tutor_id` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `seen` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tutor_notification`
--

INSERT INTO `tutor_notification` (`id`, `tutor_id`, `message`, `seen`, `created_at`) VALUES
(1, 1, 'You have been allocated a new student.', 1, '2024-04-23 05:42:15'),
(2, 1, 'You have been allocated a new student.', 1, '2024-04-23 05:47:26'),
(3, 1, 'You have been allocated a new student.', 1, '2024-04-23 05:50:17'),
(4, 1, 'You have been allocated a new student.', 1, '2024-04-23 05:51:31'),
(5, 1, 'You have been allocated a new student.', 1, '2024-04-23 05:53:02'),
(6, 1, 'You have been allocated a new student.', 1, '2024-04-23 06:01:43'),
(7, 1, 'You have been allocated a new student.', 1, '2024-04-23 06:06:49'),
(8, 1, 'You have been allocated a new student.', 1, '2024-04-23 06:06:57'),
(9, 1, 'You have been allocated a new student.', 1, '2024-04-23 06:09:14'),
(10, 1, 'You have been allocated a new student.', 1, '2024-04-23 06:09:34'),
(11, 1, 'You have been allocated a new student.', 1, '2024-04-23 06:10:18'),
(12, 1, 'You have been allocated a new student.', 1, '2024-04-23 06:13:02'),
(13, 1, 'You have been allocated a new student.', 1, '2024-04-23 06:13:48'),
(14, 2, 'You have been allocated a new student.', 1, '2024-04-23 06:14:14'),
(15, 1, 'You have been allocated a new student.', 1, '2024-04-23 08:00:51'),
(16, 1, 'You have been allocated a new student.', 1, '2024-04-23 08:50:05'),
(17, 1, 'You have been allocated a new student.', 1, '2024-04-23 08:50:20'),
(18, 1, 'You have been allocated a new student.', 1, '2024-04-23 12:22:20'),
(19, 1, 'You have been allocated a new student.', 1, '2024-04-23 12:25:12'),
(20, 1, 'You have been allocated a new student.', 1, '2024-04-23 12:28:25'),
(21, 1, 'You have been allocated a new student.', 1, '2024-04-23 12:29:09'),
(22, 1, 'You have been allocated a new student.', 1, '2024-04-23 12:30:05'),
(23, 1, 'You have been allocated a new student.', 1, '2024-04-23 12:30:14'),
(24, 1, 'You have been allocated a new student.', 1, '2024-04-23 12:33:13');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coursework`
--
ALTER TABLE `coursework`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `studentscourseworks`
--
ALTER TABLE `studentscourseworks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `student_messages`
--
ALTER TABLE `student_messages`
  ADD PRIMARY KEY (`message_id`);

--
-- Indexes for table `student_notification`
--
ALTER TABLE `student_notification`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `tutors`
--
ALTER TABLE `tutors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tutor_messages`
--
ALTER TABLE `tutor_messages`
  ADD PRIMARY KEY (`message_id`);

--
-- Indexes for table `tutor_notification`
--
ALTER TABLE `tutor_notification`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tutor_id` (`tutor_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `coursework`
--
ALTER TABLE `coursework`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `studentscourseworks`
--
ALTER TABLE `studentscourseworks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `student_messages`
--
ALTER TABLE `student_messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `student_notification`
--
ALTER TABLE `student_notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `tutors`
--
ALTER TABLE `tutors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tutor_messages`
--
ALTER TABLE `tutor_messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tutor_notification`
--
ALTER TABLE `tutor_notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `studentscourseworks`
--
ALTER TABLE `studentscourseworks`
  ADD CONSTRAINT `studentscourseworks_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `student_notification`
--
ALTER TABLE `student_notification`
  ADD CONSTRAINT `student_notification_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`);

--
-- Constraints for table `tutor_notification`
--
ALTER TABLE `tutor_notification`
  ADD CONSTRAINT `tutor_notification_ibfk_1` FOREIGN KEY (`tutor_id`) REFERENCES `tutors` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
