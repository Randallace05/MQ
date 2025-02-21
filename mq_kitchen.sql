-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 18, 2025 at 09:47 AM
-- Server version: 11.6.2-MariaDB
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
(1, 'uploadsC/Bagoong Pro Max.jpg', 'uploadsC/left.jpg', 'uploadsC/right.jpg'),
(2, 'uploadsC/Scan.jpg', NULL, NULL),
(3, 'uploadsC/226847_223714377638921_5457340_n.jpg', NULL, NULL);

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
  `total_price` decimal(10,2) NOT NULL,
  `batch_codename` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cart_id`, `product_id`, `tbl_user_id`, `name`, `price`, `image`, `quantity`, `total_price`, `batch_codename`) VALUES
(11, 1, 1, 'Chili Garlic Bagoong', '278', 'chili garlic bagoong.jpg', 1, 556.00, ''),
(19, 15, 79, 'Plain Alamang', '218', 'Plain Alamang.jpg', 1, 218.00, 'PA-2029-10-16-2');

-- --------------------------------------------------------

--
-- Table structure for table `checkout`
--

CREATE TABLE `checkout` (
  `checkout_id` int(11) UNSIGNED NOT NULL,
  `tbl_user_id` int(11) NOT NULL,
  `orders_id` int(11) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `middlename` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(50) NOT NULL,
  `zip_code` varchar(10) NOT NULL,
  `contact_number` varchar(15) NOT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `gcash_proof` varchar(255) DEFAULT NULL,
  `cart_items` text DEFAULT NULL,
  `batch_codename` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `checkout`
--

INSERT INTO `checkout` (`checkout_id`, `tbl_user_id`, `orders_id`, `firstname`, `middlename`, `lastname`, `address`, `city`, `zip_code`, `contact_number`, `payment_method`, `created_at`, `gcash_proof`, `cart_items`, `batch_codename`) VALUES
(2, 79, 2, 'Daryllkhryss', 'aweawe', 'Cadua', '399 Salcedo 1', 'Cavite', '4105', '09948669156', 'Cash on Delivery', '2025-01-30 13:04:17', NULL, 'Chicken Binagoongan (2x)', 'CB-2027-12-30-2'),
(3, 79, 3, 'Daryll', 'Capili', 'Cadua', 'Noveleta', 'Cavite', '4105', '09695234125', 'Cash on Delivery', '2025-01-30 13:08:50', NULL, 'Chili Garlic Bagoong (1x)', 'CGB-2029-12-31-2'),
(4, 79, 4, 'Daryll', 'Capili', 'Cadua', 'Noveleta', 'Cavite', '4105', '09695234125', 'Cash on Delivery', '2025-01-30 13:10:32', NULL, 'Plain Alamang (1x)', 'PA-2029-10-16-2'),
(5, 71, 0, 'zaed', 'jimenez', 'alvarico', 'san antonio 2 ', 'CAvite', '4105', '12345678901', NULL, '2025-02-13 16:42:15', NULL, NULL, ''),
(6, 71, 0, 'zaed', 'jimenez', 'alvarico', 'san antonio 2 ', 'CAvite', '4105', '12345678901', NULL, '2025-02-13 16:43:55', NULL, NULL, ''),
(7, 71, 0, 'zaed', 'jimenez', 'alvarico', 'san antonio 2 ', 'CAvite', '4105', '12345678901', NULL, '2025-02-13 16:49:12', NULL, NULL, ''),
(8, 71, 0, 'zaed', 'jimenez', 'alvarico', 'san antonio 2 ', 'CAvite', '4105', '12345678901', NULL, '2025-02-13 16:50:11', NULL, NULL, ''),
(9, 71, 0, 'zaed', 'jimenez', 'alvarico', 'san antonio 2 ', 'CAvite', '4105', '12345678901', NULL, '2025-02-13 16:50:50', NULL, NULL, ''),
(10, 71, 0, 'zaed', 'jimenez', 'alvarico', 'san antonio 2 ', 'CAvite', '4105', '12345678901', NULL, '2025-02-13 16:51:44', NULL, NULL, ''),
(11, 71, 0, 'zaed', 'jimenez', 'alvarico', 'san antonio 2 ', 'CAvite', '4105', '12345678901', NULL, '2025-02-13 16:52:07', NULL, NULL, ''),
(12, 71, 0, 'zaed', 'jimenez', 'alvarico', 'san antonio 2 ', 'CAvite', '4105', '12345678901', NULL, '2025-02-13 16:52:41', NULL, NULL, ''),
(13, 71, 0, 'zaed', 'jimenez', 'alvarico', 'san antonio 2 ', 'CAvite', '4105', '12345678901', NULL, '2025-02-13 16:53:11', NULL, NULL, ''),
(14, 71, 0, 'zaed', 'jimenez', 'alvarico', 'san antonio 2 ', 'CAvite', '4105', '12345678901', NULL, '2025-02-13 16:54:40', NULL, NULL, ''),
(15, 71, 0, 'zaed', 'jimenez', 'alvarico', 'san antonio 2 ', 'CAvite', '4105', '12345678901', NULL, '2025-02-13 16:56:23', NULL, NULL, ''),
(17, 71, 0, 'zaed', 'jimenez', 'alvarico', 'san antonio 2 ', 'CAvite', '4105', '12345678901', NULL, '2025-02-13 16:56:58', NULL, NULL, ''),
(19, 71, 0, 'zaed', 'jimenez', 'alvarico', 'san antonio 2 ', 'CAvite', '4105', '12345678901', NULL, '2025-02-13 16:57:14', NULL, NULL, ''),
(20, 71, 0, 'zaed', 'jimenez', 'alvarico', 'san antonio 2 ', 'CAvite', '4105', '12345678901', 'Cash on Delivery', '2025-02-13 16:57:14', NULL, NULL, ''),
(21, 71, 0, 'zaed', 'jim', 'alva', 'qwe', 'asd', '1234', '12345678900', 'Cash on Delivery', '2025-02-13 17:07:22', NULL, NULL, ''),
(22, 71, 0, 'lj', 'lj', 'lj', 'lj', 'lj', '1', '11111111111', 'Cash on Delivery', '2025-02-17 15:57:33', NULL, NULL, ''),
(23, 71, 0, 'lj', 'ljlj', 'lj', 'lj', 'lj', '12', '12121212121', 'Cash on Delivery', '2025-02-17 15:58:26', NULL, NULL, ''),
(24, 71, 5, 'z', 'z', 'z', 'z', 'z', '411', '11111111111', 'Cash on Delivery', '2025-02-17 16:16:34', NULL, 'Plain Alamang (3x)', 'PA-2029-10-16-2'),
(25, 71, 6, 'q', 'q', 'q', 'q', 'q', '1', '11111111111', 'Cash on Delivery', '2025-02-17 16:18:37', NULL, 'Chili Garlic Bagoong (5x)', 'CGB-2025-02-18-2'),
(26, 71, 7, 'q', 'q', 'q', 'q', 'q', 'q', '11111111111', 'Cash on Delivery', '2025-02-17 16:19:28', NULL, 'Salmon Binagoongan (7x)', 'SB-2030-12-31-2'),
(27, 71, 8, 'q', 'q', 'q', 'q', 'q', 'q', '11111111111', 'Cash on Delivery', '2025-02-17 16:21:03', NULL, 'Chicken Binagoongan (6x)', 'CB-2027-10-13-2'),
(28, 71, 9, 'q', 'q', 'q', 'q', 'q', 'q', '11111111111', 'Cash on Delivery', '2025-02-17 17:03:42', NULL, 'Bangus Belly Binagoongan (32x)', 'BBB-2027-12-30-2');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `msg_id` int(11) NOT NULL,
  `incoming_msg_id` int(255) NOT NULL,
  `outgoing_msg_id` int(255) NOT NULL,
  `msg` varchar(1000) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`msg_id`, `incoming_msg_id`, `outgoing_msg_id`, `msg`, `timestamp`) VALUES
(1, 6787965, 578381648, 'hi zaed', '2025-01-15 11:06:44'),
(2, 578381648, 6787965, 'hello admin ', '2025-01-15 11:06:49'),
(3, 578381648, 6787965, 'distributor to admin', '2025-01-16 18:54:23'),
(4, 578381648, 6787965, 'admin to distributor', '2025-01-16 18:54:36'),
(5, 6787965, 578381648, 'zaedzaed', '2025-01-16 18:56:47'),
(6, 6787965, 578381648, 'asdasdad', '2025-01-16 18:57:12'),
(7, 6787965, 578381648, 'admin', '2025-01-18 17:45:09'),
(8, 578381648, 6787965, 'zaed to admin', '2025-01-18 17:45:48'),
(9, 578381648, 679, 'hello', '2025-01-30 15:36:41'),
(10, 679, 578381648, 'hi', '2025-01-30 15:37:05'),
(11, 6787965, 578381648, 'hi', '2025-01-30 15:37:13');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `tbl_user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `tbl_user_id` int(11) NOT NULL,
  `order_date` datetime NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `shipping_address` varchar(255) NOT NULL,
  `payment_method` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL,
  `batch_codename` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `orders_id` int(255) NOT NULL,
  `product_id` int(255) NOT NULL,
  `quantity` int(255) NOT NULL,
  `price` int(255) NOT NULL,
  `batch_codename` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`orders_id`, `product_id`, `quantity`, `price`, `batch_codename`) VALUES
(1, 1, 1, 278, 'CGB-2029-12-31-2'),
(1, 16, 3, 328, 'BBB-2027-12-30-2'),
(2, 14, 2, 278, 'CB-2027-12-30-2'),
(3, 1, 1, 278, 'CGB-2029-12-31-2'),
(4, 15, 1, 218, 'PA-2029-10-16-2'),
(20, 1, 5, 278, 'CGB-2025-02-18-2'),
(21, 1, 5, 278, 'CGB-2025-02-18-2'),
(22, 15, 1, 218, 'PA-2029-10-16-2'),
(22, 18, 13, 328, 'SB-2030-12-31-2'),
(23, 14, 14, 278, 'CB-2028-10-17-2'),
(5, 15, 3, 218, 'PA-2029-10-16-2'),
(6, 1, 5, 278, 'CGB-2025-02-18-2'),
(7, 18, 7, 328, 'SB-2030-12-31-2'),
(8, 14, 6, 278, 'CB-2027-10-13-2'),
(9, 16, 32, 328, 'BBB-2027-12-30-2');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `codename` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `expiration_date` date NOT NULL,
  `image` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `other_images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`other_images`)),
  `stock` int(11) NOT NULL,
  `is_disabled` tinyint(1) DEFAULT 0,
  `updateBatchStatus` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `codename`, `price`, `expiration_date`, `image`, `description`, `other_images`, `stock`, `is_disabled`, `updateBatchStatus`) VALUES
(1, 'Chili Garlic Bagoong', 'CGB', 278.00, '2027-05-23', 'chili garlic bagoong.jpg', 'Chili garlic bagoong is a spicy Filipino condiment made from fermented shrimp paste, chili, and garlic. It\'s savory, salty, and spicy, perfect for enhancing dishes like grilled meats and rice.  \r\n\r\n🌶️FDA Certified  \r\n\r\n🌶️HALAL Certified\r\n', '[\"chiliGarlic.jpg\",\"chiliGarlic2.jpg\",\"chiliGarlic3.jpg\"]', 113, 0, 0),
(14, 'Chicken Binagoongan', 'CB', 278.00, '2026-05-18', 'chicken binagoongan.jpg', 'Chicken description lalagay d2', '[\"chicken1.jpg\",\"chicken2.jpg\",\"chicken3.jpg\",\"chicken4.jpg\"]', 958, 0, 0),
(15, 'Plain Alamang', 'PA', 218.00, '2026-05-18', 'Plain Alamang.jpg', 'Plain Alamang', '[\"plain1.jpg\",\"plain2.jpg\"]', 956, 0, 0),
(16, 'Bangus Belly Binagoongan', 'BBB', 328.00, '2026-05-18', 'bangus belly binagoongan.jpg', 'Bangus', '[\"bangus1.jpg\",\"bangus2.jpg\"]', 967, 0, 0),
(18, 'Salmon Binagoongan', 'SB', 328.00, '2026-05-18', 'salmon binagoongan.jpg', 'salmon', '[\"salmn1.jpg\",\"salmon2.jpg\"]', 937, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `product_batches`
--

CREATE TABLE `product_batches` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `stock` int(11) NOT NULL,
  `expiration_date` date DEFAULT NULL,
  `batch_number` int(11) NOT NULL,
  `batch_codename` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` tinyint(1) NOT NULL,
  `is_active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_batches`
--

INSERT INTO `product_batches` (`id`, `product_id`, `stock`, `expiration_date`, `batch_number`, `batch_codename`, `created_at`, `status`, `is_active`) VALUES
(5, 1, 1, '2027-10-31', 0, 'CGB-2027-10-31-2', '2025-01-27 10:56:58', 0, 0),
(6, 1, 0, '2029-12-31', 0, 'CGB-2029-12-31-2', '2025-01-27 10:57:21', 0, 0),
(8, 1, -1, '2029-12-31', 0, 'CGB-2029-12-31-2', '2025-01-27 16:20:52', 0, 0),
(9, 15, 5, '2029-10-16', 0, 'PA-2029-10-16-2', '2025-01-27 18:27:54', 1, 0),
(10, 14, 23, '2027-12-30', 0, 'CB-2027-12-30-2', '2025-01-30 12:49:46', 0, 0),
(11, 16, 0, '2027-12-30', 0, 'BBB-2027-12-30-2', '2025-01-30 12:50:16', 1, 0),
(12, 18, 35, '2027-12-30', 0, 'SB-2027-12-30-2', '2025-01-30 12:50:34', 0, 0),
(13, 14, 0, '2028-10-17', 0, 'CB-2028-10-17-2', '2025-02-09 12:15:25', 0, 0),
(14, 1, 35, '2025-02-18', 0, 'CGB-2025-02-18-2', '2025-02-09 12:28:08', 1, 0),
(15, 1, 26, '2028-10-18', 0, 'CGB-2028-10-18-2', '2025-02-13 14:16:34', 0, 0),
(16, 1, 26, '2028-10-18', 0, 'CGB-2028-10-18-2', '2025-02-13 14:16:34', 0, 0),
(17, 18, 10, '2030-12-31', 0, 'SB-2030-12-31-2', '2025-02-13 14:17:10', 1, 0),
(18, 14, 63, '2027-10-13', 0, 'CB-2027-10-13-2', '2025-02-17 16:20:16', 1, 0);

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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `rating` int(11) NOT NULL DEFAULT 0,
  `is_anonymous` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`review_id`, `product_id`, `tbl_user_id`, `username`, `review_text`, `created_at`, `rating`, `is_anonymous`) VALUES
(1, 14, 1, 'admin', 'qweesad', '2025-01-27 15:12:02', 4, 0),
(2, 1, 1, 'admin', 'try', '2025-01-27 15:27:31', 5, 0),
(4, 1, 71, 'lj', 'qqq', '2025-01-27 22:44:59', 3, 0),
(5, 14, 79, 'Anonymous', 'hello', '2025-01-30 17:08:16', 5, 1),
(6, 1, 74, 'xaed', 'qwe', '2025-02-09 08:35:32', 3, 0),
(7, 1, 74, 'Anonymous', 'zaed', '2025-02-09 09:01:34', 3, 1);

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
  `img` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `verification_code` int(6) NOT NULL,
  `user_role` enum('admin','customer','distributor') NOT NULL,
  `status` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `verified` tinyint(1) NOT NULL,
  `block_expiry_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `block_reason` varchar(255) NOT NULL,
  `profile_picture` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`tbl_user_id`, `unique_id`, `first_name`, `last_name`, `contact_number`, `email`, `img`, `username`, `password`, `verification_code`, `user_role`, `status`, `is_active`, `verified`, `block_expiry_date`, `block_reason`, `profile_picture`) VALUES
(1, 578381648, 'admin', 'zxc', '1', 'zaedrickalvarico@gmail.com', '', 'admin', '$2y$10$6M9R7ZqbWrwvPOnbnUr.pey/y./.wxDxHSb2eZfAGVMktNnfnI9gS', 128065, 'admin', 'Active now', 1, 0, '2025-02-17 15:53:58', '', ''),
(71, 677, 'lj', 'lj', '12', 'ljae.aeaeae@gmail.com', '../uploads/default.png', 'lj', '$2y$10$DL8okiMUY8YpBt9tWC9Bru71I7zUct32o8mWDaSS7sI6lfrI6zm0K', 524409, 'customer', 'Active now', 1, 0, '2025-02-17 15:52:38', '', '78d1cb1a-b022-4ada-a233-94fd1994992c.png'),
(74, 6787965, 'xaed', 'alva', '12345678911', 'zaedalvarico@gmail.com', '../uploads/default.png', 'xaed', '$2y$10$037YWMb5zctD/wrdRc/fCOZ1nqSlKVvyV44ro.B0v9CSyQZ6fEWbi', 0, 'customer', 'Active now', 1, 0, '2025-02-09 11:18:21', '', '89596e9661173856087314ba3d6d84b8.jpg'),
(79, 679, 'Daryllkhryss', 'Cadua', '9948669156', 'mackucadua@gmail.com', '../uploads/default.png', 'macku', '$2y$10$kkJ3jt5P1Tfv2qMmKBjzM..bp9jM3hU8J.0txwfEk4pGmemD5AWmy', 388923, 'distributor', 'Active now', 1, 1, '2025-01-30 15:25:31', '', 'lion.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `transaction_history`
--

CREATE TABLE `transaction_history` (
  `order_id` int(11) NOT NULL,
  `id` varchar(255) NOT NULL,
  `tbl_user_id` int(11) NOT NULL,
  `order_date` datetime NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `shipping_address` text NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `cart_items` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `notification_sent` tinyint(1) DEFAULT 0,
  `status` varchar(255) NOT NULL,
  `review_status` tinyint(1) DEFAULT 0,
  `product_id` int(11) NOT NULL,
  `batch_codename` varchar(255) NOT NULL,
  `review_requested` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaction_history`
--

INSERT INTO `transaction_history` (`order_id`, `id`, `tbl_user_id`, `order_date`, `total_amount`, `shipping_address`, `payment_method`, `cart_items`, `created_at`, `notification_sent`, `status`, `review_status`, `product_id`, `batch_codename`, `review_requested`) VALUES
(4, '', 79, '2025-01-30 21:10:32', 218.00, 'Noveleta', 'Cash on Delivery', 'Plain Alamang (1x)', '2025-02-17 15:54:18', 0, 'Order Shipped', 0, 0, 'PA-2029-10-16-2', 0),
(3, '', 79, '2025-01-30 21:08:50', 278.00, 'Noveleta', 'Cash on Delivery', 'Chili Garlic Bagoong (1x)', '2025-02-17 15:54:51', 0, 'Order Shipped', 0, 0, 'CGB-2029-12-31-2', 0),
(2, '', 79, '2025-01-30 21:04:17', 556.00, '399 Salcedo 1', 'Cash on Delivery', 'Chicken Binagoongan (2x)', '2025-02-17 15:54:53', 0, 'Delivered', 0, 0, 'CB-2027-12-30-2', 0),
(5, '', 71, '2025-02-18 00:16:34', 654.00, 'z', 'Cash on Delivery', 'Plain Alamang (3x)', '2025-02-17 16:16:42', 0, 'Order Shipped', 0, 0, 'PA-2029-10-16-2', 0),
(6, '', 71, '2025-02-18 00:18:37', 1390.00, 'q', 'Cash on Delivery', 'Chili Garlic Bagoong (5x)', '2025-02-17 16:18:42', 0, 'Order Shipped', 0, 0, 'CGB-2025-02-18-2', 0),
(7, '', 71, '2025-02-18 00:19:28', 2296.00, 'q', 'Cash on Delivery', 'Salmon Binagoongan (7x)', '2025-02-17 16:19:33', 0, 'Order Shipped', 0, 0, 'SB-2030-12-31-2', 0),
(8, '', 71, '2025-02-18 00:21:03', 1668.00, 'q', 'Cash on Delivery', 'Chicken Binagoongan (6x)', '2025-02-17 16:21:09', 0, 'Order Shipped', 0, 0, 'CB-2027-10-13-2', 0),
(9, '', 71, '2025-02-18 01:03:42', 10496.00, 'q', 'Cash on Delivery', 'Bangus Belly Binagoongan (32x)', '2025-02-17 17:03:53', 0, 'Order Shipped', 0, 0, 'BBB-2027-12-30-2', 0);

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
(2, 6787965, 14, '', 0.00),
(4, 6787965, 14, '', 0.00),
(5, 6787965, 15, '', 0.00),
(6, 6787965, 16, '', 0.00),
(10, 6787965, 1, '', 0.00);

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
  ADD PRIMARY KEY (`checkout_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`msg_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tbl_user_id` (`tbl_user_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tbl_user_id` (`tbl_user_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_batches`
--
ALTER TABLE `product_batches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

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
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`wish_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `carousel_images`
--
ALTER TABLE `carousel_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `checkout`
--
ALTER TABLE `checkout`
  MODIFY `checkout_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `msg_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `product_batches`
--
ALTER TABLE `product_batches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `tbl_user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `uploads`
--
ALTER TABLE `uploads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `wish_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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
-- Constraints for table `product_batches`
--
ALTER TABLE `product_batches`
  ADD CONSTRAINT `product_batches_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `fk_reviews_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`tbl_user_id`) REFERENCES `tbl_user` (`tbl_user_id`),
  ADD CONSTRAINT `wa` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
