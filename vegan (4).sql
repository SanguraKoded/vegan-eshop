-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 29, 2024 at 06:14 PM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vegan`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `Name` varchar(128) NOT NULL,
  `phone` varchar(128) DEFAULT NULL,
  `email` varchar(128) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `Name`, `phone`, `email`, `password`) VALUES
(19, 'Mwangii', NULL, 'devmwangi@gmail.com', '$2y$10$fdFYsuitra0RzRc8hvIiZ.CcrWAqsA3bE0hnXET6a9ULKlx1H7XCW');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cart_id`, `user_id`, `product_id`, `quantity`) VALUES
(26, 4, 2, 1),
(27, 4, 6, 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) NOT NULL DEFAULT 'OrderPlaced'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `product_id`, `quantity`, `order_date`, `status`) VALUES
(3, 3, 2, 1, '2024-02-26 12:16:29', 'OrderPlaced'),
(4, 3, 6, 1, '2024-02-26 12:16:29', 'Delivered'),
(8, 3, 2, 1, '2024-02-26 12:19:15', 'OrderPlaced'),
(9, 3, 6, 1, '2024-02-26 12:47:50', 'OrderPlaced'),
(10, 3, 2, 1, '2024-02-26 12:54:24', 'OrderPlaced'),
(11, 3, 2, 1, '2024-02-26 12:54:24', 'OrderPlaced'),
(13, 3, 3, 1, '2024-02-26 15:10:28', 'OrderPlaced'),
(14, 3, 4, 1, '2024-02-26 15:10:28', 'OrderPlaced'),
(16, 3, 7, 10, '2024-02-26 15:12:45', 'OrderPlaced'),
(17, 3, 3, 1, '2024-02-26 15:13:36', 'OrderCompleted'),
(18, 3, 4, 1, '2024-02-26 15:13:36', 'OrderCompleted'),
(20, 3, 2, 1, '2024-02-27 12:46:38', 'OrderPlaced'),
(21, 3, 2, 1, '2024-02-27 12:46:38', 'OrderPlaced'),
(23, 4, 3, 1, '2024-02-29 14:38:03', 'OrderPlaced'),
(24, 4, 3, 72, '2024-02-29 15:13:33', 'OrderPlaced'),
(25, 4, 4, 2, '2024-02-29 15:13:33', 'OrderPlaced'),
(27, 4, 2, 50, '2024-02-29 15:16:55', 'OrderPlaced');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `stock_quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `name`, `description`, `price`, `image_url`, `stock_quantity`) VALUES
(1, 'Vegan Protein Powder', 'Plant-based protein powder for muscle recovery and growth', '29.99', '1.jpg', 100),
(2, 'Organic Quinoa', 'Nutrient-rich ancient grain for salads, soups, and bowls', '9.99', '2.jpg', 0),
(3, 'Cruelty-Free Lipstick', 'Vibrant lipstick made from natural ingredients, no animal testing', '14.99', '3.jpg', 2),
(4, 'Non-Dairy Cheese', 'Creamy and delicious cheese alternative made from cashews', '12.99', '4.jpg', 28),
(5, 'Vegan Leather Backpack', 'Stylish and eco-friendly backpack made from vegan leather', '49.99', '5.jpg', 20),
(6, 'Organic Avocado Toast', 'A delicious and nutritious breakfast option made with organic whole grain bread, ripe avocado, and a sprinkle of sea salt.', '8.99', '6.jpg', 10),
(7, 'Vegan Protein Bars', 'High-protein bars made with natural ingredients like nuts, seeds, and dried fruits. Perfect for on-the-go snacking!', '14.99', '7.jpg', 15),
(9, 'Vegan Coconut Curry', 'Creamy and aromatic coconut curry loaded with vegetables and tofu, served over fragrant jasmine rice.', '16.99', '9.jpg', 25);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `Name` varchar(128) NOT NULL,
  `phone` varchar(128) NOT NULL,
  `email` varchar(128) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `Name`, `phone`, `email`, `password`) VALUES
(2, 'Nathan', '0734534523', 'nathanngetich5@gmail.com', '$2y$10$EdS2gIvcM58lmCsUIsYsxe79NAjIBxHcU3ssiWDFC4Vda5xH/5X2i'),
(3, 'James Wanyonyi', '0712345556', 'wanyonyi@gmail.com', '$2y$10$FZ/.ae8LFXyw8Vjx1bMGWu29bOpQN0JlDLsvP0xNTFTVKgp.BTgm6'),
(4, 'Mwangi', '123456', 'mwangiithedev@gmail.com', '$2y$10$SwZln3aFOrrFUkWdXM1PpeRQDnRKp3BlV70R6YiHhRpC6EORpIKy6');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

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
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
