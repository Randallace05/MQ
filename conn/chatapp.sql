-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 26, 2024 at 03:32 AM
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
-- Database: `chatapp`
--

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `msg_id` int(11) NOT NULL,
  `incoming_msg_id` int(255) NOT NULL,
  `outgoing_msg_id` int(255) NOT NULL,
  `msg` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`msg_id`, `incoming_msg_id`, `outgoing_msg_id`, `msg`) VALUES
(1, 1016790390, 938308958, 'hello dar'),
(2, 938308958, 151209975, '1'),
(3, 938308958, 1016790390, 'hello'),
(4, 1016790390, 151209975, '1'),
(5, 151209975, 1016790390, '2'),
(6, 938308958, 151209975, 'a'),
(7, 151209975, 938308958, 'oy'),
(8, 151209975, 1016790390, 'randall ace'),
(9, 1016790390, 151209975, 'two orders of bagoogn'),
(10, 1016790390, 151209975, 'test'),
(11, 151209975, 1016790390, '123'),
(12, 151209975, 1016790390, 'zaedrick'),
(13, 1016790390, 151209975, 'alvarico'),
(14, 151209975, 1016790390, 'qwe'),
(15, 1016790390, 151209975, 'asd'),
(16, 1016790390, 151209975, 'qweqwe'),
(17, 151209975, 1016790390, 'qdasdasd'),
(18, 151209975, 1016790390, 'asd '),
(19, 151209975, 1016790390, 'asd'),
(20, 151209975, 1016790390, 'asd'),
(21, 151209975, 1016790390, 'asd'),
(22, 1016790390, 151209975, 'qwe al;skdj'),
(23, 151209975, 1016790390, 'hello '),
(24, 151209975, 1016790390, 'outgoihn'),
(25, 1016790390, 151209975, 'zaed');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `unique_id` int(255) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `img` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `unique_id`, `fname`, `lname`, `email`, `password`, `img`, `status`) VALUES
(1, 1016790390, 'zaed', 'rick', 'zaed@gmail.com', 'a09b8c48c4879fceda89a86e8caec97a', '172328164189596e9661173856087314ba3d6d84b8.jpg', 'Offline now'),
(2, 938308958, 'dar', 'dar', 'dar@gmail.com', 'ac638a13498ffe51c65a6ae0bf7089fd', '17232816768024466-removebg-preview.png', 'Offline now'),
(3, 151209975, 'ran', 'dall', 'ran@gmail.com', '0420d605d97eb746182ce4101970b03a', '1723281703images-removebg-preview (1).png', 'Active now');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`msg_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `msg_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
