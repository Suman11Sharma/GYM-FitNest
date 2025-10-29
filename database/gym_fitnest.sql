-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 29, 2025 at 12:30 PM
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
-- Database: `gym_fitnest`
--

-- --------------------------------------------------------

--
-- Table structure for table `about_us`
--

CREATE TABLE `about_us` (
  `about_id` int(11) NOT NULL,
  `main_title` varchar(255) NOT NULL,
  `quotes` text DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'inactive',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `about_us_points`
--

CREATE TABLE `about_us_points` (
  `point_id` int(11) NOT NULL,
  `about_id` int(11) NOT NULL,
  `description_point` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ads`
--

CREATE TABLE `ads` (
  `ad_id` int(11) NOT NULL,
  `ad_type` enum('gym','partner') NOT NULL,
  `gym_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `image_url` longblob NOT NULL,
  `link_url` varchar(255) DEFAULT NULL,
  `ads_name` varchar(100) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ad_plans`
--

CREATE TABLE `ad_plans` (
  `plan_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `duration_days` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_queries`
--

CREATE TABLE `contact_queries` (
  `query_id` int(11) NOT NULL,
  `gym_id` int(11) DEFAULT NULL,
  `name` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `contact` varchar(20) DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `status` enum('pending','replied','closed') DEFAULT 'pending',
  `reply` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `customer_id` int(11) NOT NULL,
  `gym_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `gender` enum('male','female','other') NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `password` varchar(255) NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `join_date` date NOT NULL DEFAULT curdate(),
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_plans`
--

CREATE TABLE `customer_plans` (
  `plan_id` int(11) NOT NULL,
  `gym_id` int(11) NOT NULL,
  `plan_name` varchar(50) NOT NULL,
  `duration_days` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_subscriptions`
--

CREATE TABLE `customer_subscriptions` (
  `subscription_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `gym_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_status` enum('pending','paid','failed') DEFAULT 'pending',
  `transaction_id` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gyms`
--

CREATE TABLE `gyms` (
  `gym_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `opening_time` time DEFAULT NULL,
  `closing_time` time DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gym_subscriptions`
--

CREATE TABLE `gym_subscriptions` (
  `subscription_id` int(11) NOT NULL,
  `gym_id` int(11) NOT NULL,
  `plan_name` varchar(100) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_status` enum('pending','paid','failed') DEFAULT 'pending',
  `transaction_id` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `paid_ads`
--

CREATE TABLE `paid_ads` (
  `ad_id` int(11) NOT NULL,
  `gym_id` int(11) NOT NULL,
  `ads_plan` varchar(255) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `link_url` varchar(255) DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` enum('esewa','cash') NOT NULL,
  `transaction_id` varchar(100) DEFAULT NULL,
  `approval_status` enum('pending','approved','rejected') DEFAULT 'pending',
  `status` enum('active','inactive','expired') DEFAULT 'inactive',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `saas_plans`
--

CREATE TABLE `saas_plans` (
  `plan_id` int(11) NOT NULL,
  `plan_name` varchar(50) NOT NULL,
  `features` text DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `duration_months` int(11) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trainers`
--

CREATE TABLE `trainers` (
  `trainer_id` int(11) NOT NULL,
  `gym_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `specialization` varchar(255) DEFAULT NULL,
  `rate_per_session` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trainer_availability`
--

CREATE TABLE `trainer_availability` (
  `availability_id` int(11) NOT NULL,
  `trainer_id` int(11) NOT NULL,
  `day_of_week` enum('Mon','Tue','Wed','Thu','Fri','Sat','Sun') NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trainer_bookings`
--

CREATE TABLE `trainer_bookings` (
  `booking_id` int(11) NOT NULL,
  `trainer_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `gym_id` int(11) NOT NULL,
  `session_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_status` enum('pending','paid','failed') DEFAULT 'pending',
  `status` enum('booked','completed','cancelled') DEFAULT 'booked',
  `transaction_id` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `gym_id` int(11) DEFAULT NULL,
  `name` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('superadmin','admin','trainer') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `videos`
--

CREATE TABLE `videos` (
  `video_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'inactive',
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `visitor_passes`
--

CREATE TABLE `visitor_passes` (
  `pass_id` int(11) NOT NULL,
  `gym_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `contact` varchar(20) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `time_from` datetime NOT NULL,
  `time_to` datetime NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` enum('pay_now','pay_at_visit') DEFAULT 'pay_at_visit',
  `payment_status` enum('pending','paid') DEFAULT 'pending',
  `transaction_id` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `visitor_plans`
--

CREATE TABLE `visitor_plans` (
  `fee_id` int(11) NOT NULL,
  `gym_id` int(11) NOT NULL,
  `visitor_fee` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `about_us`
--
ALTER TABLE `about_us`
  ADD PRIMARY KEY (`about_id`);

--
-- Indexes for table `about_us_points`
--
ALTER TABLE `about_us_points`
  ADD PRIMARY KEY (`point_id`),
  ADD KEY `about_id` (`about_id`);

--
-- Indexes for table `ads`
--
ALTER TABLE `ads`
  ADD PRIMARY KEY (`ad_id`),
  ADD KEY `gym_id` (`gym_id`);

--
-- Indexes for table `ad_plans`
--
ALTER TABLE `ad_plans`
  ADD PRIMARY KEY (`plan_id`);

--
-- Indexes for table `contact_queries`
--
ALTER TABLE `contact_queries`
  ADD PRIMARY KEY (`query_id`),
  ADD KEY `gym_id` (`gym_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`customer_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone` (`phone`),
  ADD KEY `fk_customers_gym` (`gym_id`);

--
-- Indexes for table `customer_plans`
--
ALTER TABLE `customer_plans`
  ADD PRIMARY KEY (`plan_id`),
  ADD KEY `gym_id` (`gym_id`);

--
-- Indexes for table `customer_subscriptions`
--
ALTER TABLE `customer_subscriptions`
  ADD PRIMARY KEY (`subscription_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `plan_id` (`plan_id`),
  ADD KEY `gym_id` (`gym_id`);

--
-- Indexes for table `gyms`
--
ALTER TABLE `gyms`
  ADD PRIMARY KEY (`gym_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `gym_subscriptions`
--
ALTER TABLE `gym_subscriptions`
  ADD PRIMARY KEY (`subscription_id`),
  ADD KEY `gym_id` (`gym_id`);

--
-- Indexes for table `paid_ads`
--
ALTER TABLE `paid_ads`
  ADD PRIMARY KEY (`ad_id`),
  ADD KEY `gym_id` (`gym_id`);

--
-- Indexes for table `saas_plans`
--
ALTER TABLE `saas_plans`
  ADD PRIMARY KEY (`plan_id`);

--
-- Indexes for table `trainers`
--
ALTER TABLE `trainers`
  ADD PRIMARY KEY (`trainer_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `gym_id` (`gym_id`);

--
-- Indexes for table `trainer_availability`
--
ALTER TABLE `trainer_availability`
  ADD PRIMARY KEY (`availability_id`),
  ADD KEY `trainer_id` (`trainer_id`);

--
-- Indexes for table `trainer_bookings`
--
ALTER TABLE `trainer_bookings`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `trainer_id` (`trainer_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `gym_id` (`gym_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `gym_id` (`gym_id`);

--
-- Indexes for table `videos`
--
ALTER TABLE `videos`
  ADD PRIMARY KEY (`video_id`);

--
-- Indexes for table `visitor_passes`
--
ALTER TABLE `visitor_passes`
  ADD PRIMARY KEY (`pass_id`),
  ADD KEY `gym_id` (`gym_id`);

--
-- Indexes for table `visitor_plans`
--
ALTER TABLE `visitor_plans`
  ADD PRIMARY KEY (`fee_id`),
  ADD KEY `gym_id` (`gym_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `about_us`
--
ALTER TABLE `about_us`
  MODIFY `about_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `about_us_points`
--
ALTER TABLE `about_us_points`
  MODIFY `point_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ads`
--
ALTER TABLE `ads`
  MODIFY `ad_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ad_plans`
--
ALTER TABLE `ad_plans`
  MODIFY `plan_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contact_queries`
--
ALTER TABLE `contact_queries`
  MODIFY `query_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_plans`
--
ALTER TABLE `customer_plans`
  MODIFY `plan_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_subscriptions`
--
ALTER TABLE `customer_subscriptions`
  MODIFY `subscription_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gyms`
--
ALTER TABLE `gyms`
  MODIFY `gym_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gym_subscriptions`
--
ALTER TABLE `gym_subscriptions`
  MODIFY `subscription_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `paid_ads`
--
ALTER TABLE `paid_ads`
  MODIFY `ad_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `saas_plans`
--
ALTER TABLE `saas_plans`
  MODIFY `plan_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trainers`
--
ALTER TABLE `trainers`
  MODIFY `trainer_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trainer_availability`
--
ALTER TABLE `trainer_availability`
  MODIFY `availability_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trainer_bookings`
--
ALTER TABLE `trainer_bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `videos`
--
ALTER TABLE `videos`
  MODIFY `video_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `visitor_passes`
--
ALTER TABLE `visitor_passes`
  MODIFY `pass_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `visitor_plans`
--
ALTER TABLE `visitor_plans`
  MODIFY `fee_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `about_us_points`
--
ALTER TABLE `about_us_points`
  ADD CONSTRAINT `about_us_points_ibfk_1` FOREIGN KEY (`about_id`) REFERENCES `about_us` (`about_id`) ON DELETE CASCADE;

--
-- Constraints for table `ads`
--
ALTER TABLE `ads`
  ADD CONSTRAINT `ads_ibfk_1` FOREIGN KEY (`gym_id`) REFERENCES `gyms` (`gym_id`) ON DELETE CASCADE;

--
-- Constraints for table `contact_queries`
--
ALTER TABLE `contact_queries`
  ADD CONSTRAINT `contact_queries_ibfk_1` FOREIGN KEY (`gym_id`) REFERENCES `gyms` (`gym_id`) ON DELETE SET NULL;

--
-- Constraints for table `customers`
--
ALTER TABLE `customers`
  ADD CONSTRAINT `fk_customers_gym` FOREIGN KEY (`gym_id`) REFERENCES `gyms` (`gym_id`) ON DELETE CASCADE;

--
-- Constraints for table `customer_plans`
--
ALTER TABLE `customer_plans`
  ADD CONSTRAINT `customer_plans_ibfk_1` FOREIGN KEY (`gym_id`) REFERENCES `gyms` (`gym_id`) ON DELETE CASCADE;

--
-- Constraints for table `customer_subscriptions`
--
ALTER TABLE `customer_subscriptions`
  ADD CONSTRAINT `customer_subscriptions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `customer_subscriptions_ibfk_2` FOREIGN KEY (`plan_id`) REFERENCES `customer_plans` (`plan_id`),
  ADD CONSTRAINT `customer_subscriptions_ibfk_3` FOREIGN KEY (`gym_id`) REFERENCES `gyms` (`gym_id`);

--
-- Constraints for table `gym_subscriptions`
--
ALTER TABLE `gym_subscriptions`
  ADD CONSTRAINT `gym_subscriptions_ibfk_1` FOREIGN KEY (`gym_id`) REFERENCES `gyms` (`gym_id`) ON DELETE CASCADE;

--
-- Constraints for table `paid_ads`
--
ALTER TABLE `paid_ads`
  ADD CONSTRAINT `paid_ads_ibfk_1` FOREIGN KEY (`gym_id`) REFERENCES `gyms` (`gym_id`) ON DELETE CASCADE;

--
-- Constraints for table `trainers`
--
ALTER TABLE `trainers`
  ADD CONSTRAINT `trainers_ibfk_1` FOREIGN KEY (`gym_id`) REFERENCES `gyms` (`gym_id`) ON DELETE CASCADE;

--
-- Constraints for table `trainer_availability`
--
ALTER TABLE `trainer_availability`
  ADD CONSTRAINT `trainer_availability_ibfk_1` FOREIGN KEY (`trainer_id`) REFERENCES `trainers` (`trainer_id`) ON DELETE CASCADE;

--
-- Constraints for table `trainer_bookings`
--
ALTER TABLE `trainer_bookings`
  ADD CONSTRAINT `trainer_bookings_ibfk_1` FOREIGN KEY (`trainer_id`) REFERENCES `trainers` (`trainer_id`),
  ADD CONSTRAINT `trainer_bookings_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `trainer_bookings_ibfk_3` FOREIGN KEY (`gym_id`) REFERENCES `gyms` (`gym_id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`gym_id`) REFERENCES `gyms` (`gym_id`) ON DELETE CASCADE;

--
-- Constraints for table `visitor_passes`
--
ALTER TABLE `visitor_passes`
  ADD CONSTRAINT `visitor_passes_ibfk_1` FOREIGN KEY (`gym_id`) REFERENCES `gyms` (`gym_id`) ON DELETE CASCADE;

--
-- Constraints for table `visitor_plans`
--
ALTER TABLE `visitor_plans`
  ADD CONSTRAINT `visitor_plans_ibfk_1` FOREIGN KEY (`gym_id`) REFERENCES `gyms` (`gym_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
