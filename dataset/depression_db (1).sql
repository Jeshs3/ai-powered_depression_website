-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 07, 2025 at 03:24 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `depression_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `dob` date NOT NULL,
  `email` varchar(100) NOT NULL,
  `gender` varchar(1) NOT NULL,
  `year` varchar(4) NOT NULL,
  `course` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `confirm_pass` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `middle_name`, `last_name`, `dob`, `email`, `gender`, `year`, `course`, `password`, `confirm_pass`) VALUES
(10, 'Margie', 'Narbay', 'Penton', '2004-09-04', 'margie.beloy@bisu.edu.ph', '0', '2nd', 'bscs', '$2y$10$9zD0x7mtswB4TW0uRD7vyu6i6l1IqbB7FEIj3X2t60lbNzqeS7RJ6', '0'),
(11, 'Dianne', 'Alibo', 'Duterte', '2024-12-30', 'dianne.jumao-as@bisu.edu.ph', 'F', '3rd', 'bscs', '$2y$10$.aJJudwS2Rj40OqFQS75d.UHri9SWjwWHGGyChEk2YXBDhJmI.KqC', '0'),
(12, 'Jacqueline', 'T', 'Josol', '2024-12-30', 'jacqueline.josol@bisu.edu.ph', 'F', '3rd', 'bscs', '$2y$10$M6QemANzb60EEiR2qi5lSuQaUaXLDPMVtk.bZAl7LLVUyXUPQCy0u', '0'),
(14, 'Gino', 'Estaniola', 'Gwapo', '2005-03-31', 'ginogwapo@gmail.com', 'M', '1st', 'bscs', '$2y$10$yBrV.JMLCv.52vHHvyzLSutBj.5wNeX0BW6LFbILDVox.mJL/AJdq', '0'),
(15, 'Janeth', 'Estaniola', 'Banol', '2024-12-30', 'janethestaniola@gmail.com', 'F', '3rd', 'bscs', '$2y$10$8B/ItKYBhdK0nn0b/sjLX.xDyq1N59x7ttPhPvkIlQdx6q4wC5JJm', '00000000'),
(26, 'Janeth', 'Estaniola', 'Banol', '2024-12-31', 'haitikok6@gmail.com', 'F', '3rd', 'bscs', '$2y$10$k1PthuOdA.Ll8BW/Zf3C3.mTzf3xM/JFRzhbuZtazcOZa3ievHdFG', ''),
(29, 'Gino', 'Estaniola', 'Banol', '2024-12-30', 'ginogwapo@gmail.com', 'M', '1st', 'bscs', '$2y$10$H.ndRQKtSbk4UYkkSDNtuO6x2yIt/J.yciQwLzYVOU8SRWKDayP3K', ''),
(30, 'Snow', 'Black', 'White', '2000-04-04', 'snow@gmail.com', 'F', '4th', 'bses', '$2y$10$VzYLqq9pHQsUoeHqfp792eV8ac2wsweyWv2LlnPFnRqB6wAihFoQq', ''),
(31, 'Gino ', 'Opaw', 'Cruz', '2022-06-09', 'gino@gmail.com', 'M', '1st', 'bsed_math', '$2y$10$CO4ZFSncm257r/C7yhrFburAotGdxhob6QPt90C1Sz3dPCrFpyJ26', ''),
(32, 'Kailas', 'H', 'Halder', '2000-12-04', 'halder@gmail.com', 'M', '4th', 'bscs', '$2y$10$gh.t6CtMI3Ngb1ac/06v1.5D1XlaUAdrg7M.iyeg4i9Tza1k.0XH.', ''),
(33, 'Snow', 'Girl', 'Nana', '2022-06-06', 'snowwhite@gmail.com', 'F', '3rd', 'bscs', '$2y$10$Grb.7aQW/ZbUWc2CGa7az.3lm.4488bK6R8uVCvZhl8Lkcyc/AWz6', '');

-- --------------------------------------------------------

--
-- Table structure for table `user_submissions`
--

CREATE TABLE `user_submissions` (
  `submission_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `answers` varchar(500) DEFAULT NULL,
  `score` int(11) DEFAULT NULL,
  `status` varchar(30) DEFAULT 'pending_analysis',
  `probability` float NOT NULL,
  `submission_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_submissions`
--

INSERT INTO `user_submissions` (`submission_id`, `user_id`, `answers`, `score`, `status`, `probability`, `submission_date`) VALUES
(15, 33, '[\"deeply_rested\",\"generally_fine\",\"distracted_often\",\"disinterested\",\"drained\",\"open_to_others\",\"indifferent\",\"irritable\",\"overeating\",\"accepting\",\"occasional_discomfort\",\"guilt_ridden\",\"occasionally_hesitant\",\"mostly_calm\",\"joyless\",\"emotionally_numb\",\"unpredictable\",\"distracted_sometimes\",\"unsure\",\"sometimes_numb\"]', 34, 'low', 0.0103, '2025-05-07 13:07:08'),
(16, 33, '[\"restless\",\"often_sad\",\"mentally_scattered\",\"emotionally_numb\",\"drained\",\"withdrawn\",\"hopeless\",\"volatile\",\"no_appetite\",\"worthless\",\"pain_often\",\"guilt_ridden\",\"overwhelmed\",\"constantly_anxious\",\"emotionally_flat\",\"emotionally_numb\",\"out_of_control\",\"foggy\",\"burdensome\",\"deep_despair\"]', 0, 'high', 0.79, '2025-05-07 13:18:36'),
(17, 33, '[\"deeply_rested\",\"uplifted\",\"laser_focused\",\"excited_about_life\",\"fully_energized\",\"socially_engaged\",\"optimistic\",\"calm_and_consistent\",\"gaining_weight\",\"self_compassionate\",\"physically_well\",\"empowered\",\"confident_decider\",\"totally_at_ease\",\"frequently_joyful\",\"deeply_engaged\",\"peacefully_balanced\",\"mentally_sharp\",\"valued_and_loved\",\"holding_hope\"]', 80, 'low', 0.0103, '2025-05-07 13:20:05'),
(18, 33, '[\"sometimes_rested\",\"neutral\",\"occasionally_distracted\",\"neutral\",\"sometimes_weary\",\"neutral\",\"indifferent\",\"occasionally_moody\",\"overeating\",\"indifferent\",\"neutral\",\"accepting_sometimes\",\"occasionally_hesitant\",\"sometimes_on_edge\",\"neutral\",\"indifferent\",\"occasionally_intense\",\"distracted_sometimes\",\"unsure\",\"sometimes_numb\"]', 40, 'low', 0.0103, '2025-05-07 13:21:16');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_submissions`
--
ALTER TABLE `user_submissions`
  ADD PRIMARY KEY (`submission_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `user_submissions`
--
ALTER TABLE `user_submissions`
  MODIFY `submission_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `user_submissions`
--
ALTER TABLE `user_submissions`
  ADD CONSTRAINT `user_submissions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
