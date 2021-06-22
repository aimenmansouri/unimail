-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 22, 2021 at 06:59 PM
-- Server version: 10.4.19-MariaDB
-- PHP Version: 8.0.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `unimaildb`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `post` int(11) NOT NULL,
  `owner` int(11) NOT NULL,
  `contexts` text CHARACTER SET utf8 NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  `approved` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `post`, `owner`, `contexts`, `date`, `approved`) VALUES
(54, 85, 3, 'solution :\r\nHe knew how to hold the cone just right so that the soft server ice-cream fell into it at the precise angle to form a perfect cone each and every time', '2021-06-22 17:06:39', 1),
(55, 85, 3, 'He knew how to hold the cone just right so that the soft server ice-cream fell into it at the precise angle to form a perfect cone each and every time', '2021-06-22 17:06:51', 0),
(56, 85, 3, 'fully understand the beauty of this accomplishment except for the new worker.', '2021-06-22 17:07:06', 0),
(57, 86, 3, 'He was an expert but not in a discipline that anyone could fully appreciate. He knew how to hold the cone just right so that the soft server ice-cream fell into it at the precise angle to form a perfect cone each and every time. ', '2021-06-22 17:08:32', 0);

-- --------------------------------------------------------

--
-- Table structure for table `des_posts`
--

CREATE TABLE `des_posts` (
  `id` int(11) NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 NOT NULL,
  `Contexts` text CHARACTER SET utf8 NOT NULL,
  `image` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '<!--no image-->',
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  `owner` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `des_posts`
--

INSERT INTO `des_posts` (`id`, `title`, `Contexts`, `image`, `date`, `owner`) VALUES
(85, 'Need help to solve this problem (solved post)', 'error contexts (random words) :\r\nHe was an expert but not in a discipline that anyone could fully appreciate. He knew how to hold the cone just right so that the soft server ice-cream fell into it at the precise angle to form a perfect cone each and every time. It had taken years to perfect and he could now do it without even putting any thought behind it. Nobody seemed to fully understand the beauty of this accomplishment except for the new worker who watched in amazement.', '<img style =\"max-height: 800px;\" src=\"disc_posts_imgs/85.png\" class=\"card-img-top\" alt=\"Post image.\">', '2021-06-22 17:04:34', 3),
(86, 'Need help to solve this problem (still unsolved post)', 'fully understand the beauty of this accomplishment except for the new worker who watched in amazement.', '<!--no image-->', '2021-06-22 17:07:42', 3);

-- --------------------------------------------------------

--
-- Table structure for table `memb`
--

CREATE TABLE `memb` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `type` tinyint(4) NOT NULL,
  `groupe` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `memb`
--

INSERT INTO `memb` (`id`, `name`, `surname`, `email`, `pass`, `type`, `groupe`) VALUES
(3, 'aimen', 'admin', 'admin@gmail.com', 'e37ff8f4feee74d7b0a280364cc940218adffce4', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `Contexts` text NOT NULL,
  `fixed` tinyint(4) NOT NULL,
  `groupe` tinyint(4) NOT NULL,
  `class` tinyint(4) NOT NULL,
  `owner` int(11) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `title`, `Contexts`, `fixed`, `groupe`, `class`, `owner`, `date`) VALUES
(51, 'Fixed post title', 'The words hadn\'t flowed from his fingers for the past few weeks. He never imagined he\'d find himself with writer\'s block, but here he sat with a blank screen in front of him. \r\nlink : <a href=\"#\">link example</a> \r\nThat blank screen taunting him day after day had started to play with his mind.', 1, 0, 0, 3, '2021-06-22 17:16:20'),
(52, 'post for groupe 7 , develop mobile classe', 'His parents continued to question him. He didn\'t know what to say to them since they refused to believe the truth <a href=\"#\">link example</a> .\r\nHe explained again and again, and they dismissed his explanation as a figment of his imagination.', 0, 7, 1, 3, '2021-06-22 17:19:56'),
(56, 'post for groupe 1 , intelligence artificielle classe', 'His parents continued to question him. He didn\'t know what to say to them since they refused to believe the truth', 0, 1, 4, 3, '2021-06-22 17:57:27');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `des_posts`
--
ALTER TABLE `des_posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `memb`
--
ALTER TABLE `memb`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `des_posts`
--
ALTER TABLE `des_posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT for table `memb`
--
ALTER TABLE `memb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
