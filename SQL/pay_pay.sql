-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 24, 2024 at 03:43 PM
-- Server version: 10.11.8-MariaDB-ubu2204
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pay_pay`
--

-- --------------------------------------------------------

--
-- Table structure for table `Categories`
--

CREATE TABLE `Categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `net` varchar(255) DEFAULT NULL,
  `creation_at` datetime NOT NULL DEFAULT current_timestamp(),
  `is_pin` tinyint(1) NOT NULL DEFAULT 0,
  `status` varchar(50) NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `Categories`
--

INSERT INTO `Categories` (`category_id`, `category_name`, `net`, `creation_at`, `is_pin`, `status`) VALUES
(5, 'سبأفون', '71', '2024-05-27 04:49:15', 1, 'active'),
(6, 'يو', '73', '2024-05-27 04:49:29', 0, 'active'),
(7, 'يمن موبايل', '77', '2024-05-27 04:49:45', 0, 'active'),
(8, 'واي', '70', '2024-05-27 04:50:26', 0, 'active'),
(9, 'يمن فور جي', '10', '2024-05-27 04:50:39', 0, 'active');

-- --------------------------------------------------------

--
-- Table structure for table `Orders`
--

CREATE TABLE `Orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `order_date` datetime NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` varchar(50) NOT NULL,
  `payment_status` varchar(20) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `Orders`
--

INSERT INTO `Orders` (`order_id`, `user_id`, `product_id`, `order_date`, `total_amount`, `status`, `payment_status`) VALUES
(33, 1, 8, '2024-05-27 12:16:11', 12.00, '2', '0'),
(34, 1, 8, '2024-06-24 06:40:44', 12.00, '1', '1'),
(35, 1, 5, '2024-06-24 06:42:05', 22.95, '1', '1');

-- --------------------------------------------------------

--
-- Table structure for table `OrderStatus`
--

CREATE TABLE `OrderStatus` (
  `status_id` int(11) NOT NULL,
  `status_name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `OrderStatus`
--

INSERT INTO `OrderStatus` (`status_id`, `status_name`) VALUES
(0, 'قيد المعالجة'),
(1, 'مكتمل'),
(2, 'ملغى'),
(3, 'غير مكتمل');

-- --------------------------------------------------------

--
-- Table structure for table `Products`
--

CREATE TABLE `Products` (
  `product_id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `product_name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `creation_at` datetime NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) NOT NULL DEFAULT 'available',
  `fee` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `Products`
--

INSERT INTO `Products` (`product_id`, `category_id`, `product_name`, `price`, `description`, `creation_at`, `status`, `fee`) VALUES
(5, 7, 'باقة 6 جيجا انترنت فور جي', 22.95, 'باقة مميزه خاصه بمستخدمي الفور جي ', '2024-05-27 04:52:04', 'available', 1.00),
(6, 9, 'منتج تجريبي', 3.00, '<p>اهلا وسهلا&nbsp;<strong>مرحبا</strong></p>', '2024-05-27 09:35:51', 'متوفر', 1.00),
(7, 9, 'منتج تجريبي', 3.00, '<p>اهلا وسهلا&nbsp;<strong>مرحبا</strong></p>', '2024-05-27 09:36:07', 'متوفر', 1.00),
(8, 7, 'اهلا وسهلا', 12.00, '<p>مرحبا مرحبا&nbsp;<strong>كيف حالك</strong></p>', '2024-05-27 09:36:33', 'متوفر', 1.00);

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE `Users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `pincode` varchar(6) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `status` tinyint(4) DEFAULT 0,
  `date` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `Users`
--

INSERT INTO `Users` (`user_id`, `username`, `password`, `pincode`, `full_name`, `ip`, `status`, `date`) VALUES
(1, 'aljailane', '$2y$10$wpwey3dYeCKt8XWMgOXskeucXv3lcd2qk6.7w7GZjTBx/.q/kTwG.', '899800', 'MUHAMMED YAHYA2', '2001:16a4:23a:c8d6:9568:c5c2:e804:edd1', 0, '2024-05-26 20:57:19'),
(5, 'محمد عابد', '$2y$10$A.usKYTL9zz9B1DXC5kaZ.MikDZWyvzt1D2J6DOw6ewIf3GaJjB8K', '629385', 'محمد يحي', '2001:16a4:23a:c8d6:9568:c5c2:e804:edd1', 0, '2024-05-27 04:30:07');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Categories`
--
ALTER TABLE `Categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `Orders`
--
ALTER TABLE `Orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `OrderStatus`
--
ALTER TABLE `OrderStatus`
  ADD PRIMARY KEY (`status_id`);

--
-- Indexes for table `Products`
--
ALTER TABLE `Products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Categories`
--
ALTER TABLE `Categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `Orders`
--
ALTER TABLE `Orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `Products`
--
ALTER TABLE `Products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `Users`
--
ALTER TABLE `Users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Orders`
--
ALTER TABLE `Orders`
  ADD CONSTRAINT `Orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`),
  ADD CONSTRAINT `Orders_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `Products` (`product_id`);

--
-- Constraints for table `Products`
--
ALTER TABLE `Products`
  ADD CONSTRAINT `Products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `Categories` (`category_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
