-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 05, 2026 at 04:46 AM
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
-- Database: `furcare`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `pet_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `pet_type` varchar(50) NOT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `status` enum('pending','confirmed','completed','declined','cancelled') DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_archived` tinyint(1) DEFAULT 0,
  `archived_at` datetime DEFAULT NULL,
  `pet_size` varchar(20) DEFAULT NULL,
  `decline_reason` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `user_id`, `service_id`, `pet_name`, `email`, `contact_number`, `pet_type`, `appointment_date`, `appointment_time`, `status`, `notes`, `created_at`, `updated_at`, `is_archived`, `archived_at`, `pet_size`, `decline_reason`) VALUES
(6, 4, 2, 'Final Testing', 'freshfuji123@gmail.com', '09518441342', 'Dog', '2025-05-21', '10:30:00', 'cancelled', 'Testing', '2025-05-20 15:23:13', '2025-11-19 03:47:13', 1, '2025-11-19 11:47:13', NULL, NULL),
(8, 4, 2, 'Test', 'freshfuji123@gmail.com', '09518441342', 'Cat', '2025-05-22', '09:00:00', 'completed', 'Test', '2025-05-21 12:36:19', '2025-11-19 03:47:13', 1, '2025-11-19 11:47:13', NULL, NULL),
(9, 4, 1, 'Rod', 'freshfuji123@gmail.com', '09518441342', 'Dog', '2025-05-22', '09:30:00', 'cancelled', 'I am disable person', '2025-05-22 12:33:33', '2025-11-19 03:47:13', 1, '2025-11-19 11:47:13', NULL, NULL),
(10, 4, 2, 'Test', 'rroduot@gmail.com', '09518441342', 'Dog', '2025-05-22', '10:00:00', 'cancelled', 'Test', '2025-05-22 12:44:38', '2025-11-19 03:47:13', 1, '2025-11-19 11:47:13', NULL, NULL),
(11, 4, 1, 'Rod', 'duotrodolinor@gmail.com', '09518441342', 'Cat', '2025-05-25', '13:00:00', 'completed', 'This is a test', '2025-05-25 04:53:53', '2025-11-19 03:47:13', 1, '2025-11-19 11:47:13', NULL, NULL),
(13, 4, 1, 'Rod', 'duotrodolinor@gmail.com', '09518441342', 'Cat', '2025-07-11', '16:30:00', 'completed', 'Test', '2025-07-11 15:40:28', '2025-11-19 03:47:13', 1, '2025-11-19 11:47:13', NULL, NULL),
(14, 4, 3, 'Test', 'duotrodolinor@gmail.com', '09518441342', 'Dog', '2025-07-12', '09:30:00', 'completed', 'Test', '2025-07-11 16:17:02', '2025-11-19 03:47:13', 1, '2025-11-19 11:47:13', NULL, NULL),
(15, 4, 4, 'Test', 'duotrodolinor@gmail.com', '09518441342', 'Cat', '2025-07-12', '10:00:00', 'completed', 'Test', '2025-07-11 16:18:47', '2025-11-19 03:47:13', 1, '2025-11-19 11:47:13', NULL, NULL),
(16, 4, 5, 'Test', 'duotrodolinor@gmail.com', '09518441342', 'Dog', '2025-07-12', '10:30:00', 'completed', 'Test', '2025-07-11 16:44:35', '2025-11-19 03:47:13', 1, '2025-11-19 11:47:13', NULL, NULL),
(17, 4, 6, 'Test', 'duotrodolinor@gmail.com', '09518441342', 'Dog', '2025-07-12', '12:00:00', 'completed', 'Test', '2025-07-11 16:45:56', '2025-11-19 03:47:13', 1, '2025-11-19 11:47:13', NULL, NULL),
(18, 4, 1, 'Test', 'duotrodolinor@gmail.com', '0951841342', 'Dog', '2025-07-13', '09:00:00', 'completed', 'Test', '2025-07-12 06:30:37', '2025-11-19 03:47:13', 1, '2025-11-19 11:47:13', NULL, NULL),
(27, 11, 2, 'Browny', 'fritzartiaga@gmail.com', '09519444911', 'Dog', '2025-10-27', '11:00:00', 'completed', '', '2025-10-24 05:37:06', '2025-11-19 03:46:43', 0, NULL, 'Medium', NULL),
(28, 11, 2, 'Browny', 'fritzartiaga@gmail.com', '09519444911', 'Dog', '2025-10-27', '13:30:00', 'declined', '', '2025-10-24 05:38:28', '2025-11-19 03:46:43', 0, NULL, 'Medium', NULL),
(29, 12, 1, 'Browny', 'villaber.gellou@dnsc.edu.ph', '09519444911', 'Dog', '2025-11-12', '09:00:00', 'cancelled', '', '2025-11-12 07:36:16', '2025-11-19 03:46:43', 0, NULL, 'Small', NULL),
(30, 12, 1, 'Browny', 'villaber.gellou@dnsc.edu.ph', '09519444911', 'Dog', '2025-11-13', '14:30:00', 'completed', '', '2025-11-12 07:37:29', '2025-11-19 06:36:11', 0, NULL, 'Small', NULL),
(31, 12, 1, 'Browny', 'villaber.gellou@dnsc.edu.ph', '09519444911', 'Dog', '2025-11-13', '16:00:00', 'declined', '', '2025-11-12 07:55:24', '2025-11-19 04:01:10', 0, NULL, 'Small', NULL),
(32, 4, 2, 'Test', 'rroduot@gmail.com', '091518441342', 'Dog', '2025-11-19', '14:00:00', 'declined', 'Test', '2025-11-19 04:06:57', '2025-11-19 04:07:13', 0, NULL, 'Medium', 'Test');

-- --------------------------------------------------------

--
-- Table structure for table `appointment_history`
--

CREATE TABLE `appointment_history` (
  `id` int(11) NOT NULL,
  `appointment_id` int(11) NOT NULL,
  `status` varchar(20) NOT NULL,
  `changed_by` int(11) NOT NULL,
  `notes` text DEFAULT NULL,
  `changed_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointment_history`
--

INSERT INTO `appointment_history` (`id`, `appointment_id`, `status`, `changed_by`, `notes`, `changed_at`) VALUES
(3, 8, 'completed', 8, 'Appointment marked as completed by admin', '2025-05-22 00:20:08'),
(6, 11, 'completed', 8, 'Appointment marked as completed by admin', '2025-05-27 21:27:52'),
(7, 13, 'completed', 8, 'Appointment marked as completed by admin', '2025-07-11 23:40:56'),
(8, 14, 'completed', 8, 'Appointment marked as completed by admin', '2025-07-12 00:17:36'),
(9, 15, 'completed', 8, 'Appointment marked as completed by admin', '2025-07-12 00:22:42'),
(10, 16, 'completed', 8, 'Appointment marked as completed by admin', '2025-07-12 00:44:56'),
(11, 17, 'completed', 8, 'Appointment marked as completed by admin', '2025-07-12 00:46:27'),
(12, 18, 'completed', 8, 'Appointment marked as completed by admin', '2025-07-12 14:42:16'),
(16, 27, 'completed', 8, 'Appointment marked as completed by admin', '2025-10-24 13:40:11'),
(17, 30, 'completed', 8, 'Appointment marked as completed by admin', '2025-11-12 15:45:53');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `title`, `message`, `is_read`, `created_at`) VALUES
(1, 4, 'Appointment Confirmed', 'Your appointment for Test on July 21, 2025 at 9:00 AM has been confirmed!', 1, '2025-07-20 19:13:35'),
(2, 4, 'Appointment Confirmed', 'Your appointment for Test on July 23, 2025 at 9:00 AM has been confirmed!', 1, '2025-07-20 19:19:59'),
(3, 4, 'Your FurCare Appointment is Complete', 'Your Basic Grooming appointment for Test July 21, 2025 at 9:00 AM has been completed', 1, '2025-07-20 19:34:17'),
(4, 4, 'Payment Confirmed for Order #00035', 'Your payment for Order #00035 has been confirmed.', 1, '2025-07-21 03:38:31'),
(5, 4, 'Payment Issue with Order #00033', 'Hello rod,<br><br>\r\n                        We encountered an issue with your payment for Order #00033.<br>\r\n                        Please check your payment details and submit again.<br><br>\r\n                        \r\n                        You can review and resubmit your payment here:<br>\r\n                        <a href=\"http://localhost/furr/public/payment-upload.php?id=33\" style=\"color: #2563eb; text-decoration: underline;\">\r\n                            Submit Payment Again\r\n                        </a><br><br>\r\n                        The Team', 1, '2025-07-21 03:41:18'),
(6, 4, 'Payment Issue with Order #00032', 'Your payment for Order #00032 was rejected. Please check your payment details.', 1, '2025-07-21 03:44:49'),
(7, 4, 'Payment Confirmed for Order #00031', 'Your payment for Order #00031 has been confirmed.We will now process your order for shipping.   Thank you for your purchase!', 1, '2025-07-21 03:44:58'),
(8, 4, 'Your FurCare Appointment is Complete', 'Your Full Grooming appointment for Test July 23, 2025 at 9:00 AM has been completed', 1, '2025-07-21 05:18:10'),
(9, 4, 'Payment Issue with Order #00040', 'Your payment for Order #00040 was rejected. Please check your payment details.', 1, '2025-07-21 05:36:36'),
(10, 4, 'Appointment Declined', 'Hello rod,<br><br>\r\n            We regret to inform you that your appointment for Test has been declined.July 29, 2025 at 9:00 AM has been declined!', 1, '2025-07-21 05:36:52'),
(11, 4, 'Payment Confirmed for Order #00043', 'Your payment for Order #00043 has been confirmed.We will now process your order for shipping.   Thank you for your purchase!', 1, '2025-07-22 14:45:51'),
(12, 4, 'Appointment Declined', 'Hello rod,<br><br>\r\n            We regret to inform you that your appointment for Test has been declined.July 25, 2025 at 9:30 AM has been declined!', 1, '2025-07-23 17:32:19'),
(13, 4, 'Appointment Confirmed', 'Your appointment for Test on October 14, 2025 at 9:00 AM has been confirmed!', 1, '2025-10-14 09:22:41'),
(14, 4, 'Your FurCare Appointment is Complete', 'Your Full Grooming appointment for Test October 14, 2025 at 9:00 AM has been completed', 1, '2025-10-14 09:22:45'),
(15, 4, 'Payment Confirmed for Order #00041', 'Your payment for Order #00041 has been confirmed.We will now process your order for shipping.   Thank you for your purchase!', 1, '2025-10-18 23:03:28'),
(16, 10, 'Appointment Declined', 'Hello fitzjerald,<br><br>\n            We regret to inform you that your appointment for Browny has been declined.October 22, 2025 at 1:30 PM has been declined!', 1, '2025-10-22 07:27:07'),
(17, 10, 'Payment Confirmed for Order #00044', 'Your payment for Order #00044 has been confirmed.We will now process your order for shipping.   Thank you for your purchase!', 1, '2025-10-22 07:27:43'),
(18, 11, 'Appointment Confirmed', 'Your appointment for Browny on October 27, 2025 at 11:00 AM has been confirmed!', 1, '2025-10-24 05:37:39'),
(19, 11, 'Appointment Declined', 'Hello stacey,<br><br>\n            We regret to inform you that your appointment for Browny has been declined.October 27, 2025 at 1:30 PM has been declined!', 1, '2025-10-24 05:38:52'),
(20, 11, 'Your FurCare Appointment is Complete', 'Your Full Grooming appointment for Browny October 27, 2025 at 11:00 AM has been completed', 0, '2025-10-24 05:40:15'),
(21, 12, 'Appointment Confirmed', 'Your appointment for Browny on November 13, 2025 at 2:30 PM has been confirmed!', 1, '2025-11-12 07:43:20'),
(22, 12, 'Your FurCare Appointment is Complete', 'Your Full Grooming appointment for Browny November 13, 2025 at 2:30 PM has been completed', 0, '2025-11-12 07:45:59'),
(23, 12, 'Payment Confirmed for Order #00045', 'Your payment for Order #00045 has been confirmed.We will now process your order for shipping.   Thank you for your purchase!', 0, '2025-11-12 07:46:32'),
(24, 12, 'Appointment Declined', 'Hello Fitzjerald,<br><br>\r\n            We regret to inform you that your appointment for Browny has been declined.November 13, 2025 at 4:00 PM has been declined!', 0, '2025-11-19 04:01:14'),
(25, 12, 'Appointment Declined', 'Hello Fitzjerald,<br><br>\r\n            We regret to inform you that your appointment for Browny has been declined.November 13, 2025 at 4:00 PM has been declined!', 0, '2025-11-19 04:01:18'),
(26, 4, 'Appointment Declined', 'Your appointment for Test (Full Grooming) on Nov 19, 2025 has been declined. Reason: Test', 1, '2025-11-19 04:07:13');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `total_amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `shipping_address` text DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `payment_reference` varchar(255) DEFAULT NULL,
  `payment_proof` varchar(255) DEFAULT NULL,
  `status` enum('pending','payment_review','confirmed','shipped','completed','cancelled') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `order_date`, `total_amount`, `payment_method`, `shipping_address`, `contact_number`, `payment_reference`, `payment_proof`, `status`) VALUES
(12, 4, '2025-05-22 19:08:54', 360.00, 'gcash', 'Test223232', '4353534343', '2423232323232', 'assets/payments/payment_12_1747940941_photo_2024-11-01_00-11-32.jpg', 'confirmed'),
(13, 4, '2025-05-22 19:17:15', 495.00, 'gcash', 'purok 1 brgy san vicente, panabo city', '09518441342', 'A343432DF', 'assets/payments/payment_13_1747941604_photo_2024-11-01_00-11-32.jpg', 'confirmed'),
(14, 4, '2025-05-22 19:23:46', 320.00, 'gcash', 'purok 1 brgy san vicente, panabo city', '09518441342', NULL, NULL, 'pending'),
(15, 4, '2025-05-22 19:30:55', 455.00, 'gcash', 'test', '09518441342', NULL, NULL, 'pending'),
(16, 4, '2025-05-22 19:37:00', 185.00, 'gcash', 'asadsdasad', '09518441342', '232323', 'assets/payments/payment_16_1747942638_photo_2024-09-01_23-34-11.jpg', 'confirmed'),
(17, 4, '2025-05-23 00:59:30', 185.00, 'gcash', 'Testing', '09518441342', NULL, NULL, 'pending'),
(18, 4, '2025-05-23 01:20:41', 185.00, 'gcash', 'test', '09518441342', NULL, NULL, 'pending'),
(19, 4, '2025-05-23 01:34:01', 560.00, 'gcash', 'purok 1 brgy san vicente, panabo city', '09518441342', NULL, NULL, 'pending'),
(20, 4, '2025-05-23 01:37:13', 445.00, 'gcash', 'purok 1 brgy san vicente, panabo city', '09518441342', NULL, NULL, 'pending'),
(21, 4, '2025-05-25 13:47:46', 135.00, 'gcash', 'purok 1 brgy san vicente, panabo city', '09518441342', NULL, NULL, 'pending'),
(22, 4, '2025-05-25 13:48:22', 220.00, 'gcash', 'purok 1 brgy san vicente, panabo city', '09518441342', '237273287827', 'assets/payments/payment_22_1748181168_Screenshot 2024-08-21 164109.png', 'confirmed'),
(23, 4, '2025-05-25 16:31:57', 185.00, 'gcash', 'purok 1 brgy san vicente, panabo city', '09518441342', NULL, NULL, 'pending'),
(24, 4, '2025-05-25 16:34:56', 320.00, 'gcash', 'purok 1 brgy san vicente, panabo city', '09518441342', NULL, NULL, 'pending'),
(25, 4, '2025-05-25 16:54:04', 185.00, 'gcash', 'purok 1 brgy san vicente, panabo city', '09518441342', NULL, NULL, 'pending'),
(26, 4, '2025-05-27 02:07:49', 946.00, 'gcash', 'purok 1 brgy san vicente, panabo city', '09518441342', NULL, NULL, 'pending'),
(27, 4, '2025-05-27 02:23:18', 946.00, 'gcash', 'purok 1 brgy san vicente, panabo city', '09518441342', NULL, NULL, 'pending'),
(28, 4, '2025-05-27 14:45:48', 417.00, 'gcash', 'purok 1 brgy san vicente, panabo city', '09518441342', NULL, NULL, 'pending'),
(29, 4, '2025-05-27 14:47:03', 417.00, 'gcash', 'purok 1 brgy san vicente, panabo city', '09518441342', 'sdsdsds', 'assets/payments/payment_29_1748357228_494812904_532306603150907_1526184631011529590_n.png', 'confirmed'),
(30, 4, '2025-05-27 16:24:14', 135.00, 'gcash', 'purok 1 brgy san vicente, panabo city', '09518441342', '2344343', 'assets/payments/payment_30_1748363077_Screenshot 2024-09-11 194145.png', 'confirmed'),
(31, 4, '2025-05-27 16:25:08', 90.00, 'gcash', 'purok 1 brgy san vicente, panabo city', '09518441342', '232323', 'assets/payments/payment_31_1748363114_Screenshot 2024-08-24 102740.png', 'confirmed'),
(32, 4, '2025-05-27 16:25:20', 90.00, 'gcash', 'purok 1 brgy san vicente, panabo city', '09518441342', NULL, NULL, 'pending'),
(33, 4, '2025-05-27 16:35:14', 247.00, 'gcash', 'purok 1 brgy san vicente, panabo city', '09518441342', NULL, NULL, 'pending'),
(34, 4, '2025-05-27 16:40:17', 247.00, 'gcash', 'purok 1 brgy san vicente, panabo city', '09518441342', NULL, NULL, 'pending'),
(35, 4, '2025-05-27 16:41:41', 247.00, 'gcash', 'purok 1 brgy san vicente, panabo city', '09518441342', '2323232', 'assets/payments/payment_35_1748364236_Screenshot 2024-08-24 101924.png', 'confirmed'),
(36, 4, '2025-05-27 16:44:08', 205.00, 'gcash', 'purok 1 brgy san vicente, panabo city', '09518441342', NULL, NULL, 'pending'),
(37, 4, '2025-07-11 17:26:52', 425.00, 'gcash', 'purok 1 brgy san vicente, panabo city', '09518441342', '1212112', 'assets/payments/payment_37_1752254831_wallhaven-3z7dd3.png', 'confirmed'),
(38, 4, '2025-07-20 17:06:01', 1710.00, 'gcash', 'purok 1 brgy san vicente, panabo city', '09518441342', '75755757', 'assets/payments/payment_38_1753031179_Logo Black.png', 'confirmed'),
(39, 4, '2025-07-21 03:32:46', 205.00, 'gcash', 'purok 1 brgy san vicente, panabo city', '09518441342', '1231231312312', 'assets/payments/payment_39_1753068779_Test-removebg-preview (1).png', 'confirmed'),
(40, 4, '2025-07-21 05:34:40', 135.00, 'gcash', 'purok 1 brgy san vicente, panabo city', '09518441342', NULL, NULL, 'pending'),
(41, 4, '2025-07-21 06:43:52', 185.00, 'gcash', 'purok 1 brgy san vicente, panabo city', '09518441342', '1123223', 'assets/payments/payment_41_1753080243_Test-removebg-preview.png', 'confirmed'),
(42, 4, '2025-07-21 14:45:35', 135.00, 'gcash', 'purok 1 brgy san vicente, panabo city', '09518441342', NULL, NULL, 'pending'),
(43, 4, '2025-07-21 14:46:10', 135.00, 'gcash', 'purok 1 brgy san vicente, panabo city', '09518441342', '2123232', 'assets/payments/payment_43_1753109183_Logo White.png', 'confirmed'),
(44, 10, '2025-10-22 07:22:12', 205.00, 'gcash', 'Purok 2, Manay, Panabo City', '09519444911', '12332221332', 'assets/payments/payment_44_1761117759_4th.jpg', 'confirmed'),
(45, 12, '2025-11-12 07:38:46', 560.00, 'gcash', 'Purok 2, Manay, Panabo City', '09519444911', '123123123', 'assets/payments/payment_45_1762933162_5th.jpg', 'confirmed'),
(46, 12, '2025-11-12 07:41:21', 205.00, 'gcash', 'Purok 2, Manay, Panabo City', '09519444911', NULL, NULL, 'pending'),
(47, 4, '2025-11-25 17:26:31', 205.00, 'gcash', 'purok 1 brgy san vicente, panabo city', '09518441342', NULL, NULL, 'pending'),
(48, 4, '2025-11-25 17:33:09', 135.00, 'gcash', 'purok 1 brgy san vicente, panabo city', '09518441342', NULL, NULL, 'pending'),
(49, 4, '2025-11-25 17:36:26', 85.00, 'gcash', 'purok 1 brgy san vicente, panabo city', '09518441342', NULL, NULL, 'pending'),
(50, 4, '2025-11-25 17:36:45', 500.00, 'gcash', 'purok 1 brgy san vicente, panabo city', '09518441342', NULL, NULL, 'pending'),
(51, 4, '2025-11-25 17:37:11', 900.00, 'gcash', 'purok 1 brgy san vicente, panabo city', '09518441342', NULL, NULL, 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(15, 12, 5, 2, 155.00),
(16, 13, 5, 2, 155.00),
(17, 13, 6, 1, 135.00),
(18, 14, 6, 2, 135.00),
(19, 15, 6, 3, 135.00),
(20, 16, 6, 1, 135.00),
(21, 17, 6, 1, 135.00),
(22, 18, 6, 1, 135.00),
(23, 19, 5, 1, 155.00),
(24, 19, 4, 1, 85.00),
(25, 19, 6, 2, 135.00),
(26, 20, 5, 2, 155.00),
(27, 20, 4, 1, 85.00),
(28, 21, 4, 1, 85.00),
(29, 22, 4, 2, 85.00),
(30, 23, 6, 1, 135.00),
(31, 24, 6, 2, 135.00),
(32, 25, 6, 1, 135.00),
(33, 26, 4, 2, 85.00),
(34, 26, 6, 1, 135.00),
(35, 26, 7, 3, 197.00),
(36, 27, 4, 2, 85.00),
(37, 27, 6, 1, 135.00),
(38, 27, 7, 3, 197.00),
(39, 28, 4, 2, 85.00),
(40, 28, 7, 1, 197.00),
(41, 29, 4, 2, 85.00),
(42, 29, 7, 1, 197.00),
(43, 30, 4, 1, 85.00),
(44, 31, 9, 1, 40.00),
(45, 32, 9, 1, 40.00),
(46, 33, 7, 1, 197.00),
(47, 34, 7, 1, 197.00),
(48, 35, 7, 1, 197.00),
(49, 36, 5, 1, 155.00),
(50, 37, 4, 1, 85.00),
(51, 37, 5, 1, 155.00),
(52, 37, 6, 1, 135.00),
(53, 38, 6, 1, 135.00),
(54, 38, 9, 1, 40.00),
(55, 38, 11, 1, 500.00),
(56, 38, 4, 1, 85.00),
(57, 38, 10, 1, 900.00),
(58, 39, 5, 1, 155.00),
(59, 40, 4, 1, 85.00),
(60, 41, 6, 1, 135.00),
(61, 42, 4, 1, 85.00),
(62, 43, 4, 1, 85.00),
(63, 44, 5, 1, 155.00),
(64, 45, 6, 2, 135.00),
(65, 45, 5, 1, 155.00),
(66, 45, 4, 1, 85.00),
(67, 46, 5, 1, 155.00),
(68, 47, 5, 1, 155.00),
(69, 48, 4, 1, 85.00),
(70, 49, 4, 1, 85.00),
(71, 50, 11, 1, 500.00),
(72, 51, 10, 1, 900.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `batchno` int(100) NOT NULL,
  `expiry` date DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `description`, `batchno`, `expiry`, `price`, `stock`, `image`, `created_at`, `updated_at`) VALUES
(4, 5, 'Nutri Chunks Milk Sticks 70g', 'Dog treatss', 3, '2026-10-22', 85.00, 7, '682de8c5bd53e.jpg', '2025-05-21 14:51:22', '2025-11-25 17:36:26'),
(5, 5, 'Dentalight Ocean Diner Salmon 108g', 'Dog Treats', 0, NULL, 155.00, 0, '682deaad3d627.jpg', '2025-05-21 15:01:01', '2025-11-25 17:26:31'),
(6, 1, 'WHISKAS Wet Cat Food 400g', 'Cat Foods', 0, NULL, 135.00, 10, '682dee5251faa.jpg', '2025-05-21 15:16:34', '2025-11-12 07:38:46'),
(7, 1, 'Kitekat Adult 1kg', 'Adult Cat Food', 0, NULL, 197.00, 11, '682def3deb8d0.jpg', '2025-05-21 15:20:29', '2025-05-27 16:52:13'),
(8, 5, 'Omegas+ Crunchy Salmon 60g', 'Cat Treats', 0, NULL, 110.00, 20, '682df058b1276.jpg', '2025-05-21 15:25:12', '2025-05-21 15:26:43'),
(9, 2, 'Head Cover Anti Bite', 'Cone E-Collar Dog/Cat', 0, NULL, 40.00, 17, '682df240dcc21.jpg', '2025-05-21 15:32:25', '2025-07-20 17:06:01'),
(10, 6, 'Diamond Plaid Cat Dog Sweater', 'Christmas Red and White Plaid Design Style', 0, NULL, 900.00, 8, '682df344be365.jpg', '2025-05-21 15:37:40', '2025-11-25 17:37:11'),
(11, 4, 'Doggie\'s Choice Tick and Flea Shampoo 1L', 'Dog Shampoo', 0, NULL, 500.00, 8, '682df413199b9.jpg', '2025-05-21 15:41:07', '2025-11-25 17:36:45'),
(14, 6, 'Test', 'Test', 2, '2030-01-01', 1000.00, 100, '68f887b364ab7.jpg', '2025-10-22 07:28:51', '2025-10-22 07:28:51'),
(16, 1, 'Nutri Chunks Milk Sticks 70g', 'Dog Food', 1, '2028-12-11', 10.00, 0, '69143be7d14f2.png', '2025-11-12 07:48:55', '2025-11-12 07:49:57');

-- --------------------------------------------------------

--
-- Table structure for table `product_categories`
--

CREATE TABLE `product_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_categories`
--

INSERT INTO `product_categories` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Pet Food', 'Nutritional food for pets', '2025-05-03 14:49:24', '2025-05-03 14:49:24'),
(2, 'Pet Accessories', 'Collars, leashes, and other accessories', '2025-05-03 14:49:24', '2025-05-03 14:49:24'),
(3, 'Pet Milk', 'Special milk formulas for pets', '2025-05-03 14:49:24', '2025-05-03 14:49:24'),
(4, 'Pet Shampoo', 'Grooming products for pets', '2025-05-03 14:49:24', '2025-05-03 14:49:24'),
(5, 'Pet Treats', 'Delicious snacks for pets', '2025-05-03 14:49:24', '2025-05-03 14:49:24'),
(6, 'Pet Apparels', 'Clothing and fashion items for pets', '2025-05-03 14:49:24', '2025-05-03 14:49:24');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `size` varchar(20) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `duration` int(11) NOT NULL COMMENT 'Duration in minutes',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `name`, `size`, `description`, `price`, `duration`, `created_at`, `updated_at`) VALUES
(1, 'Full Grooming', 'Small', 'Full grooming service for small pets', 450.00, 90, '2025-05-03 14:49:33', '2025-10-06 07:04:05'),
(2, 'Full Grooming', 'Medium', 'Full grooming service for medium pets', 500.00, 90, '2025-05-03 14:49:33', '2025-10-06 07:04:05'),
(3, 'Full Grooming', 'Semi Large', 'Full grooming service for semi-large pets', 550.00, 90, '2025-05-03 14:49:33', '2025-10-06 07:04:05'),
(4, 'Full Grooming', 'Large', 'Full grooming service for large pets', 600.00, 90, '2025-05-03 14:49:33', '2025-10-06 07:04:05'),
(5, 'Full Grooming', 'XL', 'Full grooming service for extra large pets', 700.00, 90, '2025-05-03 14:49:33', '2025-10-06 07:04:05'),
(6, 'Full Grooming', 'XXL', 'Full grooming service for giant pets', 750.00, 90, '2025-05-03 14:49:33', '2025-10-06 07:04:05');

-- --------------------------------------------------------

--
-- Table structure for table `store_closures`
--

CREATE TABLE `store_closures` (
  `id` int(11) NOT NULL,
  `closure_date` date NOT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `closure_type` enum('full_day','partial') DEFAULT 'full_day',
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `store_closures`
--

INSERT INTO `store_closures` (`id`, `closure_date`, `reason`, `created_at`, `closure_type`, `start_time`, `end_time`) VALUES
(1, '2025-07-14', 'Test', '2025-07-12 06:41:22', 'full_day', NULL, NULL),
(2, '2025-07-15', 'Test', '2025-07-12 08:04:53', 'full_day', NULL, NULL),
(3, '2025-06-30', '', '2025-07-21 03:06:50', 'full_day', NULL, NULL),
(4, '2025-07-31', '', '2025-07-21 03:08:10', 'full_day', NULL, NULL),
(5, '2025-11-01', '', '2025-10-24 05:30:00', 'full_day', NULL, NULL),
(6, '2025-11-30', '', '2025-11-19 02:20:31', 'partial', '08:00:00', '12:00:00'),
(7, '2025-12-01', '', '2025-11-19 02:21:47', 'partial', '08:00:00', '12:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `is_admin` tinyint(1) DEFAULT 0,
  `is_verified` tinyint(1) DEFAULT 0,
  `is_approved` tinyint(1) NOT NULL DEFAULT 0,
  `verification_code` varchar(100) DEFAULT NULL,
  `reset_token` varchar(100) DEFAULT NULL,
  `reset_token_expires` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `remember_token` varchar(100) DEFAULT NULL,
  `remember_token_expires` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password`, `phone`, `address`, `is_admin`, `is_verified`, `is_approved`, `verification_code`, `reset_token`, `reset_token_expires`, `created_at`, `updated_at`, `remember_token`, `remember_token_expires`) VALUES
(4, 'rod', 'du-ot', 'freshfuji123@gmail.com', '$2y$10$IY827qvNk7Zjyg4e04lDCORIppgiJqInICaOmqJ4eS71ZKLEq7tOK', '09518441342', 'purok 1 brgy san vicente, panabo city', 0, 1, 1, NULL, NULL, NULL, '2025-05-07 01:48:11', '2025-11-24 11:11:12', '95f5a028bef134dfea3ff9630aa3c967ad8c683efa7c85691eb46585aa902840', '2025-11-13 17:19:35'),
(8, 'FurCare', 'Admin', 'furcaremis@gmail.com', '$2y$10$wlMf60RFUdxVDzvzJ2igteJo3zIkmZK0svQrskUNnau8KrTJDKmpm', NULL, NULL, 1, 1, 1, NULL, NULL, NULL, '2025-05-21 12:47:44', '2025-11-24 11:11:12', NULL, NULL),
(9, 'Test', 'Test', 'rroduot@gmail.com', '$2y$10$be5Sb6I4gB/fi.fXl/uccOV9lUMBsCACLvmRKbHUI567DKRr/6dki', '09518441342', 'Test', 0, 0, 0, '9a2e1ea2cd06e42fe1b006ac91172fe9', NULL, NULL, '2025-05-22 12:18:35', '2025-05-22 12:18:35', NULL, NULL),
(10, 'fitzjerald', 'artiaga', 'fitzartiaga123@gmail.com', '$2y$10$iIwKogdML9X2jATc7bwZn.LUjycpTLU3RswyKClRZ4VI3fT8PCr2O', '09519444911', 'Purok 2, Manay, Panabo City', 0, 1, 1, NULL, NULL, NULL, '2025-10-22 07:19:58', '2025-11-24 11:11:12', '54f85343f2f6aca9468da4d6817d7c885fd4d851340a8afc9a3eab80cb91b70f', '2025-11-21 15:21:21'),
(11, 'stacey', 'monta', 'fritzartiaga@gmail.com', '$2y$10$40dWgBYL0IF04Pvxbw0OVuREknSO8NKanRxn7EmhUZIjksPalZIAW', '09519444911', 'Sto. Tomas', 0, 1, 1, NULL, NULL, NULL, '2025-10-24 05:25:13', '2025-11-24 11:11:12', NULL, NULL),
(12, 'Fitzjerald', 'Artiaga', 'villaber.gellou@dnsc.edu.ph', '$2y$10$fXNkF7SR93d4tmwli6HKUeuApTGV07m0t1fOQsIRCGlMz18GMmi/.', '09519444911', 'Purok 2, Manay, Panabo City', 0, 1, 1, NULL, NULL, NULL, '2025-11-12 07:30:44', '2025-11-24 11:11:12', NULL, NULL),
(14, 'Rod', 'Test', 'du-ot.rodolinor@dnsc.edu.ph', '$2y$10$iRcJkEH0KXOca6fXbvTynO3X3e4BaIIu9WI145.eJKVvleBaH1FMO', '09518441342', 'Test', 0, 1, 1, NULL, NULL, NULL, '2025-11-26 00:53:43', '2025-11-26 00:54:31', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `appointment_history`
--
ALTER TABLE `appointment_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `appointment_id` (`appointment_id`),
  ADD KEY `changed_by` (`changed_by`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `product_categories`
--
ALTER TABLE `product_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `store_closures`
--
ALTER TABLE `store_closures`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `closure_date` (`closure_date`);

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
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `appointment_history`
--
ALTER TABLE `appointment_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `product_categories`
--
ALTER TABLE `product_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `store_closures`
--
ALTER TABLE `store_closures`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `appointment_history`
--
ALTER TABLE `appointment_history`
  ADD CONSTRAINT `appointment_history_ibfk_1` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`),
  ADD CONSTRAINT `appointment_history_ibfk_2` FOREIGN KEY (`changed_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `product_categories` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
