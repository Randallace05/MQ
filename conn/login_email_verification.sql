-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 08, 2024 at 04:37 PM
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
-- Database: `login_email_verification`
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

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `other_images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`other_images`)),
  `stock` int(11) NOT NULL,
  `is_disabled` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `image`, `description`, `other_images`, `stock`, `is_disabled`) VALUES
(1, 'Chili Garlic Bagoong', 278.00, 'chili garlic bagoong.jpg', 'Chili garlic bagoong is a spicy Filipino condiment made from fermented shrimp paste, chili, and garlic. It\'s savory, salty, and spicy, perfect for enhancing dishes like grilled meats and rice.  \r\n\r\n🌶️FDA Certified  \r\n\r\n🌶️HALAL Certified\r\n', '[\"chiliGarlic.jpg\",\"chiliGarlic2.jpg\",\"chiliGarlic3.jpg\"]', 112, 0),
(14, 'Chicken Binagoongan', 278.00, 'chicken binagoongan.jpg', 'Chicken description lalagay d2', '[\"chicken1.jpg\",\"chicken2.jpg\",\"chicken3.jpg\",\"chicken4.jpg\"]', 999, 0),
(15, 'Plain Alamang', 218.00, 'Plain Alamang.jpg', 'Plain Alamang', '[\"plain1.jpg\",\"plain2.jpg\"]', 999, 0),
(16, 'Bangus Belly Binagoongan', 328.00, 'bangus belly binagoongan.jpg', 'Bangus', '[\"bangus1.jpg\",\"bangus2.jpg\"]', 999, 0),
(18, 'Salmon Binagoongan', 328.00, 'salmon binagoongan.jpg', 'salmon', '[\"salmn1.jpg\",\"salmon2.jpg\"]', 999, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `tbl_user_id` int(11) NOT NULL,
  `unique_id` int(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `contact_number` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `verification_code` int(6) NOT NULL,
  `user_role` enum('admin','customer','distributor') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`tbl_user_id`, `unique_id`, `first_name`, `last_name`, `contact_number`, `email`, `username`, `password`, `verification_code`, `user_role`) VALUES
(25, 578381648, 'admin', 'admin', '1', 'zaedrickalvarico@gmail.com', 'admin', '$2y$10$6M9R7ZqbWrwvPOnbnUr.pey/y./.wxDxHSb2eZfAGVMktNnfnI9gS', 128065, 'admin'),
(28, 634632955, 'rick', 'rick', '123123', 'zaedrick.alvarico@cvsu.edu.ph', 'rick', '$2y$10$xFM3tftPVNHWRJq4brcRn.sEb3ELyDEBvGrK703QE/CHYfxsTykuG', 880859, 'customer'),
(29, 616748332, 'zaed', 'zaed', '234234', 'zaedalvarico@gmail.com', 'zaed', '$2y$10$d7Bf5ZARfdXyjs1UY6kcOu8vc7gEcCwPbQdocxtbRF6Bhd6r9qMia', 839819, 'distributor'),
(30, 167290355, 'randall', 'randall', '123123', 'randallace05@gmail.com', 'randall', '$2y$10$hRYUvfqMpxZi0KuFtMm1t.8Ci0/.6qAWzqQzLdApHm8cTdDDdilTC', 516985, 'customer');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`tbl_user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `tbl_user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
COMMIT;




/*For saving the file in ref.php*/

CREATE TABLE uploads (
    id INT AUTO_INCREMENT PRIMARY KEY,
    file_name VARCHAR(255) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    upload_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
