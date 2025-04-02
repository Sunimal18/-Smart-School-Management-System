-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 01, 2025 at 09:57 AM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `smart_school`
--

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

DROP TABLE IF EXISTS `classes`;
CREATE TABLE IF NOT EXISTS `classes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`id`, `name`) VALUES
(1, 'Grade 10-A'),
(2, 'Grade 10-B'),
(3, 'Grade 11-A'),
(8, 'Grade 6-B'),
(5, 'Grade 11-B'),
(7, 'Grade 6-A'),
(9, 'Grade 7-A'),
(10, 'Grade 7-B'),
(11, 'Grade 8-A'),
(12, 'Grade 8-B'),
(13, 'Grade 9-A'),
(14, 'Grade 9-B');

-- --------------------------------------------------------

--
-- Table structure for table `marks`
--

DROP TABLE IF EXISTS `marks`;
CREATE TABLE IF NOT EXISTS `marks` (
  `id` int NOT NULL AUTO_INCREMENT,
  `student_id` int NOT NULL,
  `subject_id` int NOT NULL,
  `class_id` int NOT NULL,
  `term` int NOT NULL,
  `marks` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  KEY `subject_id` (`subject_id`)
) ;

--
-- Dumping data for table `marks`
--

INSERT INTO `marks` (`id`, `student_id`, `subject_id`, `class_id`, `term`, `marks`) VALUES
(1, 2, 1, 1, 1, 40),
(2, 2, 2, 1, 1, 45),
(3, 2, 3, 1, 1, 50),
(4, 2, 1, 1, 2, 70),
(5, 2, 2, 1, 2, 71),
(6, 2, 3, 1, 2, 90),
(7, 4, 1, 1, 1, 70),
(8, 4, 2, 1, 1, 72),
(9, 4, 3, 1, 1, 55),
(10, 4, 1, 1, 2, 77),
(11, 4, 2, 1, 2, 67),
(12, 4, 3, 1, 2, 38),
(13, 5, 1, 7, 1, 70),
(14, 5, 2, 7, 1, 65),
(15, 5, 3, 7, 1, 80),
(16, 5, 4, 7, 1, 37),
(17, 5, 5, 7, 1, 90),
(18, 5, 6, 7, 1, 57),
(19, 5, 7, 7, 1, 52),
(20, 5, 8, 7, 1, 70),
(21, 5, 9, 7, 1, 76),
(22, 5, 10, 7, 1, 34),
(23, 5, 11, 7, 1, 63),
(24, 5, 12, 7, 1, 88),
(25, 6, 1, 7, 1, 60),
(26, 6, 2, 7, 1, 75),
(27, 6, 3, 7, 1, 90),
(28, 6, 4, 7, 1, 64),
(29, 6, 5, 7, 1, 82),
(30, 6, 6, 7, 1, 84),
(31, 6, 7, 7, 1, 60),
(32, 6, 8, 7, 1, 77),
(33, 6, 9, 7, 1, 71),
(34, 6, 10, 7, 1, 48),
(35, 6, 11, 7, 1, 81),
(36, 6, 12, 7, 1, 69),
(37, 7, 1, 7, 1, 62),
(38, 7, 2, 7, 1, 67),
(39, 7, 3, 7, 1, 80),
(40, 7, 4, 7, 1, 30),
(41, 7, 5, 7, 1, 90),
(42, 7, 6, 7, 1, 65),
(43, 7, 7, 7, 1, 54),
(44, 7, 8, 7, 1, 86),
(45, 7, 9, 7, 1, 74),
(46, 7, 10, 7, 1, 46),
(47, 7, 11, 7, 1, 60),
(48, 7, 12, 7, 1, 70),
(49, 2, 4, 1, 1, 70),
(50, 2, 5, 1, 1, 75),
(51, 2, 6, 1, 1, 90),
(52, 2, 7, 1, 1, 69),
(53, 2, 8, 1, 1, 71),
(54, 2, 9, 1, 1, 87),
(55, 2, 4, 1, 2, 80),
(56, 2, 5, 1, 2, 75),
(57, 2, 6, 1, 2, 61),
(58, 2, 7, 1, 2, 37),
(59, 2, 8, 1, 2, 54),
(60, 2, 9, 1, 2, 67),
(61, 4, 4, 1, 1, 73),
(62, 4, 5, 1, 1, 82),
(63, 4, 6, 1, 1, 66),
(64, 4, 7, 1, 1, 57),
(65, 4, 8, 1, 1, 90),
(66, 4, 9, 1, 1, 49),
(67, 4, 4, 1, 2, 85),
(68, 4, 5, 1, 2, 67),
(69, 4, 6, 1, 2, 74),
(70, 4, 7, 1, 2, 81),
(71, 4, 8, 1, 2, 70),
(72, 4, 9, 1, 2, 51);

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

DROP TABLE IF EXISTS `students`;
CREATE TABLE IF NOT EXISTS `students` (
  `id` int NOT NULL AUTO_INCREMENT,
  `index_no` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `class_id` int DEFAULT NULL,
  `category_1_sub` varchar(50) NOT NULL,
  `category_2_sub` varchar(50) NOT NULL,
  `category_3_sub` varchar(50) NOT NULL,
  `religion` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `index_no` (`index_no`),
  KEY `class_id` (`class_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `index_no`, `name`, `class_id`, `category_1_sub`, `category_2_sub`, `category_3_sub`, `religion`) VALUES
(2, 'ST001', 'uditha rangana', 1, 'Business and Accounting Studies', 'Art', 'ICT', 'Buddhism'),
(3, 'ST002', 'Rangana', 2, '', '', '', ''),
(4, 'ST003', 'isuru', 1, 'Citizenship Education', 'Music', 'Agri', 'Catholic'),
(5, 'ST004', 'kasun', 7, '', '', '', ''),
(6, 'ST005', 'Tharushi', 7, '', '', '', ''),
(7, 'ST006', 'Nethmini', 7, '', '', '', ''),
(8, 'ST007', 'heshani', 1, 'Geography', 'Dance', 'Home Economics', ''),
(9, 'ST008', 'kasun', 1, 'Citizenship Education', 'Music', 'ICT', 'Catholic');

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

DROP TABLE IF EXISTS `subjects`;
CREATE TABLE IF NOT EXISTS `subjects` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `name`) VALUES
(1, 'Mathematics'),
(2, 'Science'),
(3, 'English'),
(4, 'Sinhala'),
(5, 'Religion'),
(6, 'History'),
(7, 'Category Subject-1'),
(8, 'Category Subject-2'),
(9, 'Category Subject-3');

-- --------------------------------------------------------

--
-- Table structure for table `subject_6to9`
--

DROP TABLE IF EXISTS `subject_6to9`;
CREATE TABLE IF NOT EXISTS `subject_6to9` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `subject_6to9`
--

INSERT INTO `subject_6to9` (`id`, `name`) VALUES
(1, 'Mathematic'),
(2, 'Science'),
(3, 'Sinhala'),
(4, 'English'),
(5, 'Religion'),
(6, 'History'),
(7, 'Geography'),
(8, 'Citizenship Education'),
(9, 'Health & Physical Education'),
(10, 'Tamil (Second Language)'),
(11, 'Information and communication technology'),
(12, 'Category Subject');

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

DROP TABLE IF EXISTS `teachers`;
CREATE TABLE IF NOT EXISTS `teachers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `class_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `class_id` (`class_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`id`, `name`, `email`, `password`, `class_id`) VALUES
(2, 'Ravindu', 'ravindu@gmail.com', '12345', 9);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('teacher','principal') NOT NULL,
  `class_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `class_id` (`class_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `class_id`) VALUES
(1, 'teacher1', 'abc123', 'teacher', 1),
(2, 'principal1', '1234', 'principal', NULL),
(3, 'teacher_g6', 'grade6', 'teacher', 7),
(4, 'ravindu@gmail.com', '12345', 'teacher', 9);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
