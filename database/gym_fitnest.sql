-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 11, 2025 at 01:10 PM
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

--
-- Dumping data for table `about_us`
--

INSERT INTO `about_us` (`about_id`, `main_title`, `quotes`, `status`, `created_at`, `updated_at`) VALUES
(10, 'suman ', '\"hey whats up\"', 'inactive', '2025-09-08 07:03:03', '2025-09-11 08:50:49'),
(11, 'suman ', '\"hey whats up\"', 'inactive', '2025-09-08 07:03:46', '2025-09-11 08:48:10'),
(12, 'suman sh', 'uplopup', 'inactive', '2025-09-08 07:07:49', '2025-09-08 07:07:49'),
(13, 'Voluptatem est est ', 'Porro sit veniam re', 'inactive', '2025-09-08 07:30:05', '2025-09-08 07:30:05'),
(14, 'Omnis maiores conseq', 'Illo quibusdam ex se', 'inactive', '2025-09-08 07:30:14', '2025-09-08 07:30:14'),
(15, 'Cupidatat fuga Labo', 'Exercitation ipsam i', 'active', '2025-09-08 07:30:27', '2025-09-08 07:51:17'),
(16, 'Provident dolor por', 'Commodi eligendi deb', 'inactive', '2025-09-08 07:42:07', '2025-09-08 07:42:07'),
(17, 'Maiores ex voluptas ', 'Non saepe exercitati', 'inactive', '2025-09-08 07:43:19', '2025-09-08 07:43:19'),
(18, 'Incidunt fugit ips', 'Molestiae do esse cu', 'inactive', '2025-09-08 07:43:22', '2025-09-08 07:43:22'),
(19, 'Libero commodi elige', 'Laudantium voluptat', 'inactive', '2025-09-08 07:43:26', '2025-09-08 07:43:26'),
(20, 'Ab quia nisi iure do', 'Officia incididunt e', 'inactive', '2025-09-08 07:43:33', '2025-09-08 07:43:33'),
(21, 'Iusto nobis laborum ', 'Aut aut suscipit aut', 'inactive', '2025-09-08 07:43:38', '2025-09-08 07:43:38'),
(23, 'Ducimus sunt offic', 'Cumque optio saepe ', 'inactive', '2025-09-08 07:43:50', '2025-09-08 07:43:50'),
(24, 'Irure nobis quia vol', 'Ea aut reprehenderit', 'inactive', '2025-09-08 07:43:59', '2025-09-08 07:43:59'),
(26, 'Features We Provide :', '\"All-in-one gym control center.\"', 'active', '2025-09-11 08:47:46', '2025-09-11 08:48:17'),
(27, 'Why Choose Us?', '\"Simple. Scalable. Localized.\"', 'active', '2025-09-11 08:50:33', '2025-09-11 08:51:03');

-- --------------------------------------------------------

--
-- Table structure for table `about_us_points`
--

CREATE TABLE `about_us_points` (
  `point_id` int(11) NOT NULL,
  `about_id` int(11) NOT NULL,
  `description_point` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `about_us_points`
--

INSERT INTO `about_us_points` (`point_id`, `about_id`, `description_point`) VALUES
(16, 12, 'poin'),
(17, 12, 'ui;poj'),
(18, 12, 'uloupu'),
(19, 13, 'Distinctio Ut tempo'),
(20, 13, 'Eveniet hic ut id i'),
(21, 13, 'Esse est et qui cons'),
(22, 14, 'Eu molestiae necessi'),
(23, 14, 'Non accusamus corrup'),
(24, 14, 'Ut facilis nesciunt'),
(29, 16, 'Voluptatem qui aut s'),
(30, 16, 'Quia unde veritatis '),
(31, 16, 'Commodi sint dolor f'),
(32, 17, 'Pariatur Duis volup'),
(33, 18, 'Id qui aut voluptate'),
(34, 19, 'Distinctio Quis eni'),
(35, 20, 'Eos mollitia qui qui'),
(36, 21, 'Amet nostrum ut lab'),
(38, 23, 'Molestias aliquam ni'),
(39, 24, 'Qui esse obcaecati '),
(41, 15, 'Natus iure explicabo'),
(42, 15, 'Impedit ex neque su'),
(43, 15, 'Eveniet voluptatem'),
(44, 15, 'Recusandae Ut et un'),
(50, 11, 'point 1'),
(51, 11, '63'),
(52, 11, '4848'),
(53, 26, 'Membership Tracking'),
(54, 26, 'Real-time Attendance'),
(55, 26, 'Class Scheduling'),
(56, 26, 'Billing & Invoices'),
(57, 26, 'Staff Management'),
(63, 10, 'point 1'),
(64, 10, 'point 1'),
(65, 10, 'point 1'),
(66, 27, 'Easy to Use'),
(67, 27, 'Cloud-Based Access'),
(68, 27, 'Customizable Plans'),
(69, 27, '24/7 Support'),
(70, 27, 'Built for Nepali Gyms');

-- --------------------------------------------------------

--
-- Table structure for table `ads`
--

CREATE TABLE `ads` (
  `ad_id` int(11) NOT NULL,
  `ad_type` enum('gym','partner') NOT NULL,
  `gym_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `link_url` varchar(255) DEFAULT NULL,
  `ads_name` varchar(100) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ads`
--

INSERT INTO `ads` (`ad_id`, `ad_type`, `gym_id`, `title`, `image_url`, `link_url`, `ads_name`, `start_date`, `end_date`, `status`, `created_at`, `updated_at`) VALUES
(11, 'partner', NULL, 'dasfd', 'uploads/ads_images/1757584305_gym.jpg', 'https://example.com', '7 days plan', '2025-09-11', '2025-09-18', 'active', '2025-09-11 09:18:13', '2025-09-11 06:06:45'),
(13, 'partner', NULL, ';lkl', 'uploads/ads_images/1757584333_gym-ad-3.jpg', 'https://example.com', '14days plan', '2025-09-11', '2025-09-25', 'active', '2025-09-11 09:52:13', '2025-09-11 09:52:13'),
(14, 'gym', 42, 'fdg', 'uploads/ads_images/1757584906_gym-ads.png', 'https://example.com', '7 days plan', '2025-09-11', '2025-09-18', 'active', '2025-09-11 10:01:46', '2025-09-11 10:01:46'),
(15, 'partner', NULL, 'ds', 'uploads/ads_images/1757585994_gym-ads (1).png', 'https://example.com', '7 days plan', '2025-09-11', '2025-09-18', 'active', '2025-09-11 10:19:54', '2025-09-11 10:19:54');

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

--
-- Dumping data for table `ad_plans`
--

INSERT INTO `ad_plans` (`plan_id`, `name`, `duration_days`, `price`, `description`, `status`, `created_at`, `updated_at`) VALUES
(17, '7 days plan', 7, 500.00, 'sfcgf', 'active', '2025-09-07 11:28:05', '2025-09-07 11:28:05'),
(18, '14days plan', 14, 1000.00, '1erere', 'active', '2025-09-07 11:28:23', '2025-09-07 11:28:23'),
(19, '30 days', 30, 55555.00, 'fhbfh', 'active', '2025-09-07 12:19:16', '2025-09-07 12:19:16');

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

--
-- Dumping data for table `contact_queries`
--

INSERT INTO `contact_queries` (`query_id`, `gym_id`, `name`, `email`, `contact`, `subject`, `message`, `status`, `reply`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Suman Poudel', 'suman@gmail.com', '9825160781', 'Inquiry', 'efefefeefefe', 'pending', NULL, '2025-09-07 13:08:24', '2025-09-07 13:08:24');

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
  `image_url` varchar(255) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gyms`
--

INSERT INTO `gyms` (`gym_id`, `name`, `email`, `phone`, `address`, `image_url`, `latitude`, `longitude`, `created_at`, `updated_at`) VALUES
(42, 'Malepatan gym', 'katrinabasnetofficial@gmail.com', '9825160783', 'Kushma-3,Parbat', 'uploads/gyms_images/1757582739_gym.jpg', 28.20960000, 83.98560000, '2025-09-11 09:25:39', '2025-09-11 10:15:41');

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

--
-- Dumping data for table `saas_plans`
--

INSERT INTO `saas_plans` (`plan_id`, `plan_name`, `features`, `amount`, `duration_months`, `status`, `created_at`, `updated_at`) VALUES
(2, '6months pack', 'ass', 5555.00, 6, 'active', '2025-09-08 03:51:00', '2025-09-08 03:51:00'),
(3, '3months pack', 'seg', 10000.00, 3, 'active', '2025-09-08 06:14:07', '2025-09-08 06:14:07');

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
  `role` enum('superadmin','admin','trainer','customer') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
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
  MODIFY `about_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `about_us_points`
--
ALTER TABLE `about_us_points`
  MODIFY `point_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `ads`
--
ALTER TABLE `ads`
  MODIFY `ad_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `ad_plans`
--
ALTER TABLE `ad_plans`
  MODIFY `plan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `contact_queries`
--
ALTER TABLE `contact_queries`
  MODIFY `query_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
  MODIFY `gym_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

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
  MODIFY `plan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
