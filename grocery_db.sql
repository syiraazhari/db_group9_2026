-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 08, 2026 at 10:16 PM
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
-- Database: `grocery_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`, `description`, `image_url`, `status`) VALUES
(1, 'Vegetables', 'Fresh vegetables from local farms', 'https://images.unsplash.com/photo-1566385101042-1a0aa0c1268c?w=400', 'active'),
(2, 'Fruits', 'Sweet and fresh fruits', 'https://images.unsplash.com/photo-1619566636858-adf3ef46400b?w=400', 'active'),
(3, 'Dairy', 'Milk, cheese, and dairy products', 'https://images.unsplash.com/photo-1628088062854-d1870b4553da?w=400', 'active'),
(4, 'Meat & Poultry', 'Fresh meat and chicken', 'https://images.unsplash.com/photo-1607623814075-e51df1fed4f1?w=400', 'active'),
(5, 'Bakery', 'Fresh bread and pastries', 'https://images.unsplash.com/photo-1509440159596-0249088772ff?w=400', 'active'),
(6, 'Beverages', 'Drinks and beverages', 'https://images.unsplash.com/photo-1625772299848-391b6a87d7b3?w=400', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `feedback_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`feedback_id`, `user_id`, `order_id`, `rating`, `comment`, `created_at`) VALUES
(1, 1, 1, 5, 'Excellent service! Fresh products and easy pickup.', '2026-06-08 20:09:42'),
(2, 2, 3, 4, 'Good experience, will order again.', '2026-06-08 20:09:42'),
(3, 6, NULL, 5, 'Love the website design, very user friendly!', '2026-06-08 20:09:42');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_logs`
--

CREATE TABLE `inventory_logs` (
  `log_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `action_type` enum('restock','sold','damaged','returned') NOT NULL,
  `quantity_changed` int(11) NOT NULL,
  `previous_stock` int(11) NOT NULL,
  `new_stock` int(11) NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory_logs`
--

INSERT INTO `inventory_logs` (`log_id`, `product_id`, `staff_id`, `action_type`, `quantity_changed`, `previous_stock`, `new_stock`, `notes`, `created_at`) VALUES
(1, 1, 4, 'restock', 50, 50, 100, 'Morning restock - fresh tomatoes', '2026-06-08 20:10:33'),
(2, 14, 4, 'restock', 20, 20, 40, 'New chicken breast delivery', '2026-06-08 20:10:33');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `pickup_date` date NOT NULL,
  `pickup_time` time NOT NULL,
  `status` enum('pending','processing','ready','completed','cancelled') DEFAULT 'pending',
  `payment_status` enum('unpaid','paid') DEFAULT 'unpaid',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `total_amount`, `pickup_date`, `pickup_time`, `status`, `payment_status`, `notes`, `created_at`) VALUES
(1, 1, 25.50, '2026-06-10', '10:00:00', 'completed', 'paid', 'Please pack carefully', '2026-06-08 20:08:47'),
(2, 1, 42.00, '2026-06-12', '14:00:00', 'pending', 'unpaid', '', '2026-06-08 20:08:47'),
(3, 2, 18.50, '2026-06-11', '09:00:00', 'ready', 'unpaid', 'Call when ready', '2026-06-08 20:08:47'),
(4, 6, 35.00, '2026-06-13', '16:00:00', 'pending', 'unpaid', '', '2026-06-08 20:08:47');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_id`, `product_id`, `quantity`, `unit_price`, `subtotal`) VALUES
(1, 1, 1, 2, 3.50, 7.00),
(2, 1, 7, 1, 6.00, 6.00),
(3, 1, 11, 1, 7.00, 7.00),
(4, 1, 17, 2, 2.00, 4.00),
(5, 1, 3, 2, 2.50, 5.00),
(6, 2, 14, 1, 12.00, 12.00),
(7, 2, 15, 1, 25.00, 25.00),
(8, 2, 16, 1, 10.00, 10.00),
(9, 3, 2, 3, 2.00, 6.00),
(10, 3, 8, 2, 4.00, 8.00),
(11, 3, 20, 1, 2.00, 2.00),
(12, 3, 12, 1, 15.00, 15.00),
(13, 4, 1, 5, 3.50, 17.50),
(14, 4, 4, 2, 4.00, 8.00),
(15, 4, 9, 1, 12.00, 12.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `product_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock_quantity` int(11) DEFAULT 0,
  `unit` varchar(20) DEFAULT 'pcs',
  `image_url` varchar(255) DEFAULT NULL,
  `status` enum('available','out_of_stock','discontinued') DEFAULT 'available',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `category_id`, `product_name`, `description`, `price`, `stock_quantity`, `unit`, `image_url`, `status`, `created_at`) VALUES
(1, 1, 'Tomato', 'Fresh red tomatoes', 3.50, 100, 'kg', 'https://images.unsplash.com/photo-1592924357228-91a4daadcfea?w=400', 'available', '2026-06-08 20:08:18'),
(2, 1, 'Cucumber', 'Crispy green cucumbers', 2.00, 80, 'kg', 'https://images.unsplash.com/photo-1449300079323-02e209d9d3a6?w=400', 'available', '2026-06-08 20:08:18'),
(3, 1, 'Carrot', 'Orange carrots', 2.50, 120, 'kg', 'https://images.unsplash.com/photo-1598170845058-32b9d6a5da37?w=400', 'available', '2026-06-08 20:08:18'),
(4, 1, 'Spinach', 'Fresh spinach leaves', 4.00, 60, 'bunch', 'https://images.unsplash.com/photo-1576045057995-568f588f82fb?w=400', 'available', '2026-06-08 20:08:18'),
(5, 1, 'Chili', 'Spicy red chili', 5.00, 50, 'kg', 'https://images.unsplash.com/photo-1588252303782-cb80119abd6c?w=400', 'available', '2026-06-08 20:08:18'),
(6, 1, 'Onion', 'Yellow onions', 2.00, 150, 'kg', 'https://images.unsplash.com/photo-1618512496248-a07fe83aa8cb?w=400', 'available', '2026-06-08 20:08:18'),
(7, 2, 'Apple', 'Red apples', 6.00, 100, 'kg', 'https://images.unsplash.com/photo-1560806887-1e4cd0b6cbd6?w=400', 'available', '2026-06-08 20:08:18'),
(8, 2, 'Banana', 'Yellow bananas', 4.00, 80, 'kg', 'https://images.unsplash.com/photo-1571771894821-ce9b6c11b214?w=400', 'available', '2026-06-08 20:08:18'),
(9, 2, 'Orange', 'Juicy oranges', 5.00, 90, 'kg', 'https://images.unsplash.com/photo-1547514701-42782101795e?w=400', 'available', '2026-06-08 20:08:18'),
(10, 2, 'Watermelon', 'Sweet watermelon', 12.00, 30, 'pcs', 'https://images.unsplash.com/photo-1587049352846-4a222e784d38?w=400', 'available', '2026-06-08 20:08:18'),
(11, 3, 'Fresh Milk', 'Full cream milk 1L', 7.00, 50, 'bottle', 'https://images.unsplash.com/photo-1563636619-e9143da7973b?w=400', 'available', '2026-06-08 20:08:18'),
(12, 3, 'Cheese', 'Cheddar cheese', 15.00, 40, 'pack', 'https://images.unsplash.com/photo-1552767059-ce182ead6c1b?w=400', 'available', '2026-06-08 20:08:18'),
(13, 3, 'Yogurt', 'Natural yogurt', 5.00, 60, 'cup', 'https://images.unsplash.com/photo-1488477181946-6428a0291777?w=400', 'available', '2026-06-08 20:08:18'),
(14, 4, 'Chicken Breast', 'Boneless chicken breast', 12.00, 40, 'kg', 'https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?w=400', 'available', '2026-06-08 20:08:18'),
(15, 4, 'Beef', 'Fresh beef slices', 25.00, 30, 'kg', 'https://images.unsplash.com/photo-1603048297172-c92544798d5e?w=400', 'available', '2026-06-08 20:08:18'),
(16, 4, 'Eggs', 'Chicken eggs', 10.00, 100, 'tray', 'https://images.unsplash.com/photo-1582722872445-44dc5f7e3c8f?w=400', 'available', '2026-06-08 20:08:18'),
(17, 5, 'White Bread', 'Fresh white bread', 3.50, 50, 'loaf', 'https://images.unsplash.com/photo-1509440159596-0249088772ff?w=400', 'available', '2026-06-08 20:08:18'),
(18, 5, 'Croissant', 'Butter croissant', 4.00, 40, 'pcs', 'https://images.unsplash.com/photo-1555507036-ab1f4038808a?w=400', 'available', '2026-06-08 20:08:18'),
(19, 5, 'Cinnamon Roll', 'Sweet cinnamon roll', 5.00, 30, 'pcs', 'https://images.unsplash.com/photo-1509365390695-33aee754301f?w=400', 'available', '2026-06-08 20:08:18'),
(20, 6, 'Mineral Water', 'Drinking water 1.5L', 2.00, 200, 'bottle', 'https://images.unsplash.com/photo-1548839140-29a749e1cf4d?w=400', 'available', '2026-06-08 20:08:18'),
(21, 6, 'Orange Juice', 'Fresh orange juice', 8.00, 40, 'bottle', 'https://images.unsplash.com/photo-1600271886742-f049cd451bba?w=400', 'available', '2026-06-08 20:08:18'),
(22, 6, 'Green Tea', 'Green tea bottle', 4.00, 60, 'bottle', 'https://images.unsplash.com/photo-1627435601361-ec25f5b1d0e5?w=400', 'available', '2026-06-08 20:08:18');

-- --------------------------------------------------------

--
-- Table structure for table `sales_reports`
--

CREATE TABLE `sales_reports` (
  `report_id` int(11) NOT NULL,
  `manager_id` int(11) DEFAULT NULL,
  `report_type` enum('daily','weekly','monthly') NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `total_orders` int(11) DEFAULT 0,
  `total_revenue` decimal(12,2) DEFAULT 0.00,
  `total_products_sold` int(11) DEFAULT 0,
  `report_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`report_data`)),
  `generated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales_reports`
--

INSERT INTO `sales_reports` (`report_id`, `manager_id`, `report_type`, `start_date`, `end_date`, `total_orders`, `total_revenue`, `total_products_sold`, `report_data`, `generated_at`) VALUES
(1, 5, 'daily', '2026-06-08', '2026-06-08', 3, 86.00, 12, NULL, '2026-06-08 20:10:49'),
(2, 5, 'weekly', '2026-06-01', '2026-06-08', 15, 420.50, 45, NULL, '2026-06-08 20:10:49');

-- --------------------------------------------------------

--
-- Table structure for table `staff_tasks`
--

CREATE TABLE `staff_tasks` (
  `task_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `task_description` text NOT NULL,
  `status` enum('pending','in_progress','completed') DEFAULT 'pending',
  `assigned_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `completed_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff_tasks`
--

INSERT INTO `staff_tasks` (`task_id`, `staff_id`, `order_id`, `task_description`, `status`, `assigned_at`, `completed_at`) VALUES
(1, 4, 2, 'Prepare order #2 for pickup', 'pending', '2026-06-08 20:10:01', NULL),
(2, 4, 3, 'Pack order #3 - call customer when ready', 'in_progress', '2026-06-08 20:10:01', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `address` text DEFAULT NULL,
  `role` enum('customer','admin','staff','manager') DEFAULT 'customer',
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `full_name`, `email`, `phone`, `password`, `address`, `role`, `status`, `created_at`) VALUES
(1, 'Ahmad bin Abdullah', 'ahmad@email.com', '0123456789', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'No 1, Jalan Mawar', 'customer', 'active', '2026-06-08 20:06:50'),
(2, 'Siti binti Rahman', 'siti@email.com', '0134567890', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'No 2, Jalan Melur', 'customer', 'active', '2026-06-08 20:06:50'),
(3, 'Admin FreshMart', 'admin@freshmart.com', '0145678901', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3llC/.og/at2.uheWG/igi', 'Store HQ', 'admin', 'active', '2026-06-08 20:06:50'),
(4, 'Staff Ali', 'staff@freshmart.com', '0156789012', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Store Staff Room', 'staff', 'active', '2026-06-08 20:06:50'),
(5, 'Manager Kumar', 'manager@freshmart.com', '0167890123', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Manager Office', 'manager', 'active', '2026-06-08 20:06:50'),
(6, 'Farhan Amry', 'farhan@email.com', '01114662126', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'No 11, Jalan 1C, Taman Suria Tropika', 'customer', 'active', '2026-06-08 20:06:50');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`feedback_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `inventory_logs`
--
ALTER TABLE `inventory_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `staff_id` (`staff_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `sales_reports`
--
ALTER TABLE `sales_reports`
  ADD PRIMARY KEY (`report_id`),
  ADD KEY `manager_id` (`manager_id`);

--
-- Indexes for table `staff_tasks`
--
ALTER TABLE `staff_tasks`
  ADD PRIMARY KEY (`task_id`),
  ADD KEY `staff_id` (`staff_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `feedback_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `inventory_logs`
--
ALTER TABLE `inventory_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `sales_reports`
--
ALTER TABLE `sales_reports`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `staff_tasks`
--
ALTER TABLE `staff_tasks`
  MODIFY `task_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `feedback_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE SET NULL;

--
-- Constraints for table `inventory_logs`
--
ALTER TABLE `inventory_logs`
  ADD CONSTRAINT `inventory_logs_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inventory_logs_ibfk_2` FOREIGN KEY (`staff_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE SET NULL;

--
-- Constraints for table `sales_reports`
--
ALTER TABLE `sales_reports`
  ADD CONSTRAINT `sales_reports_ibfk_1` FOREIGN KEY (`manager_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `staff_tasks`
--
ALTER TABLE `staff_tasks`
  ADD CONSTRAINT `staff_tasks_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `staff_tasks_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
