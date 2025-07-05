-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 30, 2025 at 04:23 PM
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
-- Database: `mystystem`
--

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int(11) NOT NULL,
  `phone_n` varchar(11) NOT NULL,
  `gmail` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `phone_n`, `gmail`) VALUES
(1, '07508507224', 'maryajabar63@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `cvs`
--

CREATE TABLE `cvs` (
  `id` int(11) NOT NULL,
  `cv_path` varchar(300) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cvs`
--

INSERT INTO `cvs` (`id`, `cv_path`, `uploaded_at`) VALUES
(1, 'uploads/1742223646_Marya_Jabar_cv.pdf', '2025-03-17 15:00:46'),
(2, 'uploads/1742249719_resume.pdf', '2025-03-17 22:15:19'),
(3, '../uploads1742249840_resume.pdf', '2025-03-17 22:17:20'),
(4, 'uploads1742249917_resume.pdf', '2025-03-17 22:18:37'),
(5, 'uploads/1742249956_resume.pdf', '2025-03-17 22:19:16'),
(6, 'uploads/1742250031_resume.pdf', '2025-03-17 22:20:31'),
(7, '../uploads/1742250229_resume.pdf', '2025-03-17 22:23:49'),
(8, 'uploads/1742250317_resume.pdf', '2025-03-17 22:25:17'),
(9, 'uploads/1742250509_resume.pdf', '2025-03-17 22:28:29'),
(10, 'uploads/1742250585_Marya_Jabar_cv.pdf', '2025-03-17 22:29:45'),
(11, 'uploads/1742250714_cv_marya_jabar.pdf', '2025-03-17 22:31:54'),
(12, 'uploads/1742340721_resume.pdf', '2025-03-18 23:32:01'),
(13, 'uploads/1742340801_cv_marya_jabar.pdf', '2025-03-18 23:33:21'),
(14, 'uploads/1742611408_cv_marya_jabar.pdf', '2025-03-22 02:43:28');

-- --------------------------------------------------------

--
-- Table structure for table `experience`
--

CREATE TABLE `experience` (
  `id` int(11) NOT NULL,
  `date_start_work` date NOT NULL DEFAULT current_timestamp(),
  `date_last_work` date NOT NULL DEFAULT current_timestamp(),
  `title_of_work` varchar(100) NOT NULL,
  `name_place_work` varchar(100) NOT NULL,
  `experiences` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT 'what i did ' CHECK (json_valid(`experiences`)),
  `tools` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT 'what tools i used' CHECK (json_valid(`tools`)),
  `type_job` varchar(50) NOT NULL COMMENT 'online, office, online +offic, freelance'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `experience`
--

INSERT INTO `experience` (`id`, `date_start_work`, `date_last_work`, `title_of_work`, `name_place_work`, `experiences`, `tools`, `type_job`) VALUES
(2, '2023-08-01', '2024-05-01', 'Social Media Manager', 'KarsazyShadman', '[\"Designed engaging social media posts and using Adobe Photoshop.\",\"Interacted with customers and provided customer service to address inquiries and solve problems. \",\"Responded promptly to messages, fostering community engagement. \"]', '[\"Adobe Photoshop\"]', 'remote+office'),
(3, '2023-04-27', '2024-03-01', 'Social Media Manager', 'Healthy Kids', '[\"Designed engaging social media posts and edited captivating videos using Adobe Photoshop and Capcut.\",\"Responded promptly to messages, fostering community engagement. \",\"Managed sponsored content partnerships, enhancing brand visibility and reach.\"]', '[\"Adobe photoshop \",\"Adobe illustrator\"]', 'remote');

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` int(11) NOT NULL,
  `name_project` varchar(100) NOT NULL,
  `description` varchar(250) NOT NULL,
  `image` varchar(250) NOT NULL,
  `video` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `name_project`, `description`, `image`, `video`) VALUES
(29, 'Accept Payment Design', 'Html, Css , Bootrsap', 'project_img_681fbb0923e1a4.98059481.png', 'project_vid_681fbb09241752.79543326.mp4'),
(36, 'Online Management System', 'Html, Css, Bootstrap, Laravel, Js', 'img_686291a907b098.41102833.png', 'vid_686291a907da02.71468082.mp4');

-- --------------------------------------------------------

--
-- Table structure for table `skills`
--

CREATE TABLE `skills` (
  `id` int(11) NOT NULL,
  `skill` varchar(100) NOT NULL,
  `about_skill` varchar(250) NOT NULL,
  `image` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `skills`
--

INSERT INTO `skills` (`id`, `skill`, `about_skill`, `image`) VALUES
(28, 'Html', 'Know all tags &amp; structure', 'skill_681fb4c0e95735.75998415.png'),
(29, 'CSS', 'Can style full pages &amp; Responsive style', 'skill_681fb4e9ee5c63.16055350.png'),
(30, 'Java Script', 'Know basics &amp; events', 'skill_681fb50b15af35.03496089.png'),
(31, 'Bootstrap', 'Use it to build fast', 'skill_681fb529f08a94.46448497.png'),
(32, 'PHP', 'Build full backend logic', 'skill_681fb564936b58.77149133.png'),
(33, 'Laravel', 'Know DB, routes, MVC', 'skill_681fb5a7856308.12584696.png'),
(34, 'AJax', 'fetch data', 'skill_681fb61f2165c4.14170671.png'),
(35, 'C#', 'Know OOP &amp; Windows Forms', 'skill_681fb658af58c2.49658302.png');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `gmail_u` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `gmail_u`, `password`) VALUES
(16, 'marya@gmail.com', '$2y$10$bGi18p7QZgLh6G03XQs0..GmUMtDDhkj6qIWbsb239P/IrgQpvNhG'),
(17, 'admin@gmail.com', '$2y$10$n2rkCEetx6gePX8damBTZeu2MFJ33SZSUdEHsO0NrM8My.TZKs.x6'),
(18, 'user369@gmail.com', '$2y$10$xUfqFfNK2H8lEqwKE/i7qef9qZSr57p4qHk.JLWntbfn2QsHtSuOm');

-- --------------------------------------------------------

--
-- Table structure for table `visit_counter`
--

CREATE TABLE `visit_counter` (
  `id` int(11) NOT NULL,
  `visit_count` varchar(50) NOT NULL,
  `visit_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `visit_counter`
--

INSERT INTO `visit_counter` (`id`, `visit_count`, `visit_time`) VALUES
(4, '4', '2025-03-17 23:24:27');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cvs`
--
ALTER TABLE `cvs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `experience`
--
ALTER TABLE `experience`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `visit_counter`
--
ALTER TABLE `visit_counter`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cvs`
--
ALTER TABLE `cvs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `experience`
--
ALTER TABLE `experience`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `skills`
--
ALTER TABLE `skills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `visit_counter`
--
ALTER TABLE `visit_counter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
