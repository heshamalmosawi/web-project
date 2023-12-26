-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 26, 2023 at 03:34 AM
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
-- Database: `pollsdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `surveys`
--

CREATE TABLE `surveys` (
  `id` int(10) NOT NULL,
  `question` varchar(200) NOT NULL,
  `results` varchar(510) NOT NULL,
  `voters` text NOT NULL,
  `expireDate` date NOT NULL,
  `status` tinyint(1) NOT NULL,
  `creater` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `surveys`
--

INSERT INTO `surveys` (`id`, `question`, `results`, `voters`, `expireDate`, `status`, `creater`) VALUES
(2, 'What is your favorite food?', '{\"Vegetables\":3,\"Fast Food\":1}', '[\"hesham\",\"ahmed\",\"Ali\",\"loay\"]', '2023-12-27', 1, 'hesham'),
(3, 'what is your car', '{\"toyota\":4,\"nisan\":1}', '[\"Ali\",\"Ali\",\"Ali\",\"Ali\",\"ahmed\"]', '0000-00-00', 1, 'hesham'),
(4, 'hello', '{\"hello1\":1,\"hello2\":0}', '[\"ahmed\"]', '0000-00-00', 0, 'Ali'),
(5, 'what should i enter?', '{\"world\":1,\"123\":0}', '[\"ahmed\"]', '0000-00-00', 1, 'sayhusain'),
(6, 'what should i enter?', '{\"world\":0,\"123\":0}', '[]', '0000-00-00', 1, 'sayhusain'),
(7, 'Who is the best Instructor?', '{\"Dr. Mohammed Mazin\":14}', '[\"sayhusain\",\"ahmed\",\"Fatima\",\"Omar\",\"Aisha\",\"Ali\",\"Layla\",\"Mohammed\",\"Zahra\",\"Hassan\",\"Nour\",\"Abdullah\",\"Mariam\",\"Yousef\"]', '0000-00-00', 1, 'sayhusain'),
(10, 'Which is the best programming language?', '{\"Go\":0,\"C\":0,\"Javascript\":0,\"Java\":1,\"Python\":0}', '[\"ahmed\"]', '0000-00-00', 1, 'ahmed'),
(11, 'If you could have dinner with any historical figure, living or dead, who would it be and why?', '{\"Muhammad Ali\":0,\"Ali Bahar\":0}', '[]', '2023-12-26', 1, 'ahmed'),
(12, 'should i sleep or keep working on the project?', '{\"sleep\":0,\"work\":0}', '[]', '2023-12-26', 1, 'ahmed');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `Username` varchar(15) NOT NULL,
  `Email` varchar(35) NOT NULL,
  `password` varchar(255) NOT NULL,
  `pollsCreated` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`Username`, `Email`, `password`, `pollsCreated`) VALUES
('abcde', 'abcde@yahoo.com', '$2y$10$4lIBtnoH28slmnYFcA6.Jecy7SrXklRGCfkjVpdsS7atMN630X3.i', '[]'),
('ahmed', 'ahmedhameed@gmail.com', '$2y$10$aX7a1ORI2CRP94RFiS3HcOY7vxfC/FocJUfeY.AAz..vGnX6dZuWe', '3'),
('Ali', 'Ali@Ali.com', '$2y$10$tjneTOU8a3utjzr5RQ8fJeaNGY32Zdi3yjxlbja5B5ikBMo2ftmeW', '1'),
('hesham', 'hello@hello.com', '$2y$10$k1a8HuisxD4wmVq.dU3J1evz8L6DsR/CGvJ0msY7Nth4Xr4.KdTTO', '2'),
('sayhusain', '4abcde@yahoo.com', '$2y$10$F4kJK9D49zzdpgrT8LJgiul3pPwuJT42b8CXyCTbJcc2fmPXArhn6', '5');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `surveys`
--
ALTER TABLE `surveys`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`Username`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `surveys`
--
ALTER TABLE `surveys`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
