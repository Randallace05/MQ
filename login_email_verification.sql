-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 06, 2025 at 08:34 PM
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
-- Database: `login_email_verification`
--

-- --------------------------------------------------------

--
-- Table structure for table `carousel_images`
--

CREATE TABLE `carousel_images` (
  `id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `left_image_path` varchar(255) DEFAULT NULL,
  `right_image_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `carousel_images`
--

INSERT INTO `carousel_images` (`id`, `image_path`, `left_image_path`, `right_image_path`) VALUES
(1, 'uploadsC/Bagoong Pro Max.jpg', 'uploadsC/left.jpg', 'uploadsC/right.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) UNSIGNED NOT NULL,
  `product_id` int(11) NOT NULL,
  `tbl_user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` varchar(100) NOT NULL,
  `image` varchar(100) NOT NULL,
  `quantity` int(100) NOT NULL,
  `total_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cart_id`, `product_id`, `tbl_user_id`, `name`, `price`, `image`, `quantity`, `total_price`) VALUES
(103, 15, 68, 'Plain Alamang', '218', 'Plain Alamang.jpg', 2, 436.00);

-- --------------------------------------------------------

--
-- Table structure for table `checkout`
--

CREATE TABLE `checkout` (
  `id` int(11) UNSIGNED NOT NULL,
  `tbl_user_id` int(11) NOT NULL,
  `cart` int(11) UNSIGNED NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `middlename` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(50) NOT NULL,
  `zip_code` varchar(10) NOT NULL,
  `contact_number` varchar(15) NOT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `grand_total` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(0, 677, 25, 'admin 1'),
(0, 677, 25, 'admin 2'),
(0, 677, 25, 'admin 3'),
(0, 167290355, 68, 'zaed1'),
(0, 578381648, 68, 'zaed1');

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
(18, 'Salmon Binagoongan', 328.00, 'salmon binagoongan.jpg', 'salmon', '[\"salmn1.jpg\",\"salmon2.jpg\"]', 999, 0),
(20, 'try ', 123.00, 'dariru.png', 'try', '[\"chloee.png\"]', 123, 1);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `tbl_user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `review_text` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`review_id`, `product_id`, `tbl_user_id`, `username`, `review_text`, `created_at`) VALUES
(2, 15, 68, 'zaed', 'asdasd', '2025-01-05 14:01:55'),
(3, 15, 68, 'zaed', 'qweqw2e12', '2025-01-05 14:02:00'),
(4, 15, 68, 'zaed', 'qweasdaxc', '2025-01-05 14:02:04');

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
  `user_role` enum('admin','customer','distributor') NOT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`tbl_user_id`, `unique_id`, `first_name`, `last_name`, `contact_number`, `email`, `username`, `password`, `verification_code`, `user_role`, `status`) VALUES
(25, 578381648, 'admin', 'admin', '1', 'zaedrickalvarico@gmail.com', 'admin', '$2y$10$6M9R7ZqbWrwvPOnbnUr.pey/y./.wxDxHSb2eZfAGVMktNnfnI9gS', 128065, 'admin', 'Offline now'),
(30, 167290355, 'randall', 'randall', '123123', 'randallace05@gmail.com', 'randall', '$2y$10$hRYUvfqMpxZi0KuFtMm1t.8Ci0/.6qAWzqQzLdApHm8cTdDDdilTC', 516985, 'admin', 'Offline now'),
(68, 677, 'zaed', 'zaed', '123', 'zaedalvarico@gmail.com', 'zaed', '$2y$10$I9jSAJexGeCypJkJiDQjpujTadbtL6dNsz2O5O.DAZiep0djfIR.G', 379910, 'admin', 'Offline now');

-- --------------------------------------------------------

--
-- Table structure for table `uploads`
--

CREATE TABLE `uploads` (
  `id` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `upload_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `wish_id` int(11) NOT NULL,
  `tbl_user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`wish_id`, `tbl_user_id`, `product_id`, `name`, `price`) VALUES
(0, 68, 15, '', 0.00),
(0, 68, 14, '', 0.00),
(0, 68, 1, '', 0.00);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `carousel_images`
--
ALTER TABLE `carousel_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `Test` (`tbl_user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `checkout`
--
ALTER TABLE `checkout`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cart` (`cart`),
  ADD KEY `tbl_user_id` (`tbl_user_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `wa` (`product_id`),
  ADD KEY `tbl_user_id` (`tbl_user_id`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`tbl_user_id`);

--
-- Indexes for table `uploads`
--
ALTER TABLE `uploads`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `carousel_images`
--
ALTER TABLE `carousel_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `tbl_user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `uploads`
--
ALTER TABLE `uploads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `Test` FOREIGN KEY (`tbl_user_id`) REFERENCES `tbl_user` (`tbl_user_id`),
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`tbl_user_id`) REFERENCES `tbl_user` (`tbl_user_id`),
  ADD CONSTRAINT `wa` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
