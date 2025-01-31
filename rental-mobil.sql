-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 31, 2025 at 12:06 PM
-- Server version: 8.0.30
-- PHP Version: 8.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rental-mobil`
--

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `description` text,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('available','rented') DEFAULT 'available',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `description`, `image`, `status`, `created_at`) VALUES
(12, 'Toyota Innova Reborn', '400000.00', 'tahun baru 2024', '1738324100_reborn.jpeg', 'rented', '2025-01-31 11:48:20'),
(13, 'calya', '300000.00', 'tahun 2024 fresh', '1738324134_calya.jpg', 'rented', '2025-01-31 11:48:54'),
(14, 'toyota veloz', '3500000.00', 'tahun 2024', '1738324173_veloz.jpg', 'available', '2025-01-31 11:49:33'),
(15, 'brio', '300000.00', 'tahun 2024', '1738324201_brio.jpeg', 'available', '2025-01-31 11:50:01'),
(16, 'pajero', '400000.00', 'tahun 2024', '1738324223_pajero.jpg', 'available', '2025-01-31 11:50:23'),
(18, 'fortuner', '400000.00', 'Baru 2024', '1738324898_fortuner.jpg', 'available', '2025-01-31 12:01:38'),
(19, 'Hyundai Palisade', '700000.00', 'baru 2024', '1738324926_ny.jpg', 'available', '2025-01-31 12:02:06');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `status` enum('pending','completed','cancelled') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `email` varchar(255) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `user_id`, `product_id`, `start_date`, `end_date`, `total_price`, `status`, `created_at`, `email`, `phone_number`, `name`) VALUES
(4, 3, 12, '2025-01-31', '2025-02-03', '1200000.00', 'pending', '2025-01-31 11:55:39', 'me@mydomain.com', '0823456787564', 'budi'),
(5, 3, 13, '2025-01-31', '2025-02-03', '900000.00', 'cancelled', '2025-01-31 11:56:17', 'me@mydomain.com', '0823456787564', 'budi');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `role` enum('ADMIN','USER') DEFAULT 'USER',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `token`, `role`, `created_at`) VALUES
(2, 'admin', 'admin@admin.com', '$2y$10$blvwRYG6tWbhxZ2pgH2FjeeOI664YnK8TZmdZiPZ5OCqoL35ZXWyK', '30f890bc8e5004d913f6b3b355781d9c0962ddcabc6ab1f437c69605549a8fc8', 'ADMIN', '2025-01-31 08:06:35'),
(3, 'budi', 'budi@gmail.com', '$2y$10$WgnGPvMNohztgDisUKfw.uG0vsw/uVVZOimXofJL1Q9t/ku2P7duG', NULL, 'USER', '2025-01-31 11:42:52');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `fk_product` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `fk_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
