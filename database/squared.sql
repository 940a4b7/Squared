-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 04, 2025 at 05:38 AM
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
-- Database: `squared`
--

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` varchar(15) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) NOT NULL,
  `suffix` varchar(10) DEFAULT NULL,
  `sex` enum('Male','Female') NOT NULL,
  `avatar` enum('JOY','SEVI','SAMANTHA','ZEKE') NOT NULL,
  `program` varchar(10) NOT NULL,
  `course` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `qr_code` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `first_name`, `middle_name`, `last_name`, `suffix`, `sex`, `avatar`, `program`, `course`, `email`, `password_hash`, `created_at`, `qr_code`) VALUES
('1162205240', 'Angel Pitch', 'Rebaja', 'Geronggay', '', 'Female', 'JOY', 'CELA', 'Bachelor of Secondary Education Major in Science', 'geronggayangelpitch@gmail.com', '$2y$10$CKmmdJXGpIEURdCcplEYfus4Meyrzb2Jk/MztOefdiz8BAJ3u2HbW', '2025-03-01 22:00:39', '../qrcodes/1162205240.png'),
('1162205280', 'Charles', 'Simblante', 'Evangelio', 'Jr.', 'Male', 'SEVI', 'ITE', 'Bachelor of Science in Information Technology', 'charles123evangelio@gmail.com', '$2y$10$9yPbCpCs5jLxo9aNSS9un.W8ZOpSDAM2ez.FZ8VxBp3UUziITqhUy', '2025-03-01 22:10:43', '1162205280.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`),
  ADD UNIQUE KEY `email` (`email`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
