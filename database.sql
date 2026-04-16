-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 16, 2026 at 07:12 AM
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
-- Database: `inventory_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `age_statuses`
--

CREATE TABLE `age_statuses` (
  `age_status_id` bigint(20) UNSIGNED NOT NULL,
  `status_name` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `age_statuses`
--

INSERT INTO `age_statuses` (`age_status_id`, `status_name`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'NEW', 'Age status of item', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18'),
(2, 'OLD', 'Age status of item', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18');

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `brand_id` bigint(20) UNSIGNED NOT NULL,
  `brand_name` varchar(150) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`brand_id`, `brand_name`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'DELL', 'Item brand', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18'),
(2, 'LENOVO', 'Item brand', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18'),
(3, 'HUAWEI', 'Item brand', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18'),
(4, 'HP', 'Item brand', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18'),
(5, 'ACER', 'Item brand', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18'),
(6, 'ORICO', 'Item brand', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18'),
(7, 'SanDisk', 'Item brand', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18'),
(8, 'Transcend', 'Item brand', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18'),
(9, 'Red Mesh', 'Item brand', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18'),
(10, 'A4 Tech', 'Item brand', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18'),
(11, 'Logitech', 'Item brand', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18'),
(12, 'MegaBox', 'Item brand', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18'),
(13, 'MSI', 'Item brand', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18'),
(14, 'Networking Tools', 'Item brand', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18'),
(15, 'HAVIT', 'Item brand', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18'),
(16, 'ARMAK', 'Item brand', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18'),
(17, 'DeepCoot', 'Item brand', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18'),
(18, 'Secure', 'Item brand', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18'),
(19, 'SAMSUNG', 'Item brand', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18'),
(20, 'STANDARD', 'Item brand', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18'),
(21, 'WEIBO', 'Item brand', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18'),
(22, 'RAMAXEL', 'Item brand', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18'),
(23, 'WALRAM', 'Item brand', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18'),
(24, 'ROYU', 'Item brand', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18'),
(25, 'Ugreen', 'Item brand', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18'),
(26, 'CYGNETT', 'Item brand', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18'),
(27, 'FEELTEK', 'Item brand', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18'),
(28, 'OPPO', 'Item brand', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18'),
(29, 'HONOR', 'Item brand', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18'),
(30, 'IPHONE', 'Item brand', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18'),
(31, 'ASUS', 'Item brand', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18'),
(32, 'MAC', 'Item brand', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18'),
(33, 'MOTIVO', 'Item brand', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18'),
(34, 'WD Green', 'Item brand', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `category_name` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'IT ACCESSORY', 'Item category', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18'),
(2, 'HARDWARE', 'Item category', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18');

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `company_code` varchar(20) NOT NULL,
  `company_name` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`company_id`, `company_code`, `company_name`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Lionhill Hodlings In', 'Lionhill Hodlings Inc.', 'Company list', 1, '2026-04-12 00:35:18', '2026-04-13 06:38:20'),
(2, 'NCIA1', 'New Canaan Insurance Agency Inc.', 'Company list', 1, '2026-04-12 00:35:18', '2026-04-13 06:39:42'),
(3, 'NCIA2', 'NCIA Non-Life Insurance Services Agency Inc.', 'Company list', 1, '2026-04-12 00:35:18', '2026-04-13 06:40:09'),
(4, 'NCIALIFE', 'NCIA Life & Benefits Company', 'Company list', 1, '2026-04-12 00:35:18', '2026-04-13 06:40:25'),
(5, 'N/A', 'N/A', 'Company list\r\n', 1, '2026-04-13 06:42:16', '2026-04-13 06:42:16');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `department_id` bigint(20) UNSIGNED NOT NULL,
  `department_code` varchar(20) DEFAULT NULL,
  `department_name` varchar(150) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`department_id`, `department_code`, `department_name`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'IFG', 'IFG (Individual & Family Group)', 'Company department', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18'),
(2, 'IT', 'I.T (Information Technology)', 'Company department', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18'),
(3, 'FIN', 'Finance', 'Company department', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18'),
(4, 'MKT', 'Marketing', 'Company department', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18'),
(5, 'ADM', 'Admin', 'Company department', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18'),
(6, 'HR', 'HR (Human Resources)', 'Company department', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18'),
(7, 'EB', 'EB (Employee Benefits)', 'Company department', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18'),
(8, 'PC', 'P&C (Property & Casualty)', 'Company department', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18'),
(9, 'BCD', 'Bacolod Branch', 'Company department', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18'),
(10, 'CLM', 'Claims', 'Company department', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18'),
(11, 'EXE', 'Executive', 'Company department', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18'),
(12, 'HD', 'Helpdesk', 'Company department', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18');

-- --------------------------------------------------------

--
-- Table structure for table `deployment_logs`
--

CREATE TABLE `deployment_logs` (
  `deployment_log_id` bigint(20) UNSIGNED NOT NULL,
  `inventory_no` varchar(30) NOT NULL,
  `company_id` bigint(20) UNSIGNED DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sub_category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `brand_id` bigint(20) UNSIGNED DEFAULT NULL,
  `model` varchar(150) DEFAULT NULL,
  `serial_number` varchar(150) DEFAULT NULL,
  `deployed_to` varchar(150) DEFAULT NULL,
  `department_id` bigint(20) UNSIGNED DEFAULT NULL,
  `deployment_status_id` bigint(20) UNSIGNED NOT NULL,
  `date_deployed` date DEFAULT NULL,
  `returned_date` date DEFAULT NULL,
  `deployed_by_user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ;

-- --------------------------------------------------------

--
-- Table structure for table `deployment_statuses`
--

CREATE TABLE `deployment_statuses` (
  `deployment_status_id` bigint(20) UNSIGNED NOT NULL,
  `status_name` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `deployment_statuses`
--

INSERT INTO `deployment_statuses` (`deployment_status_id`, `status_name`, `description`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Returned', 'Item has been returned', 1, 1, '2026-04-12 01:43:26', '2026-04-12 01:43:26'),
(2, 'Returned with issue/s', 'Item returned with defects/issues', 2, 1, '2026-04-12 01:43:26', '2026-04-12 01:43:26'),
(3, 'Temporary', 'Temporary deployment', 3, 1, '2026-04-12 01:43:26', '2026-04-12 01:43:26'),
(4, 'Deployed', 'Currently deployed', 4, 1, '2026-04-12 01:43:26', '2026-04-12 01:43:26'),
(5, 'Transfer', 'Transferred to another custodian/department/company', 5, 1, '2026-04-12 01:43:26', '2026-04-12 01:43:26'),
(6, 'Borrowed', 'Borrowed item', 6, 1, '2026-04-12 01:43:26', '2026-04-12 01:43:26'),
(7, 'Available', 'Deployment Statuses option', 7, 1, '2026-04-16 03:03:59', '2026-04-16 03:03:59');

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `inventory_id` bigint(20) UNSIGNED NOT NULL,
  `inventory_no` varchar(30) NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `sub_category_id` bigint(20) UNSIGNED NOT NULL,
  `brand_id` bigint(20) UNSIGNED NOT NULL,
  `model` varchar(150) DEFAULT NULL,
  `item_description` text DEFAULT NULL,
  `serial_number` varchar(150) DEFAULT NULL,
  `assigned_to` varchar(150) DEFAULT NULL,
  `department_id` bigint(20) UNSIGNED DEFAULT NULL,
  `mac_address` varchar(50) DEFAULT NULL,
  `device_name` varchar(150) DEFAULT NULL,
  `current_os` varchar(100) DEFAULT NULL,
  `age_status_id` bigint(20) UNSIGNED DEFAULT NULL,
  `deployment_status_id` bigint(20) UNSIGNED DEFAULT NULL,
  `deployed_date` date DEFAULT NULL,
  `returned_date` date DEFAULT NULL,
  `purchase_date` date DEFAULT NULL,
  `purchase_month` varchar(20) DEFAULT NULL,
  `purchase_year` year(4) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`inventory_id`, `inventory_no`, `company_id`, `category_id`, `sub_category_id`, `brand_id`, `model`, `item_description`, `serial_number`, `assigned_to`, `department_id`, `mac_address`, `device_name`, `current_os`, `age_status_id`, `deployment_status_id`, `deployed_date`, `returned_date`, `purchase_date`, `purchase_month`, `purchase_year`, `remarks`, `created_at`, `updated_at`) VALUES
(10, 'NCIA-0001', 1, 2, 2, 10, NULL, 'sdfs', 'sdfsd', NULL, NULL, 'sdfsf', 'sfddsf', 'sdfsfs', 1, 7, NULL, NULL, '2026-04-10', 'April', '2026', 'sdfsdfsf', '2026-04-16 01:37:11', '2026-04-16 03:18:20');

--
-- Triggers `inventory`
--
DELIMITER $$
CREATE TRIGGER `trg_inventory_before_insert` BEFORE INSERT ON `inventory` FOR EACH ROW BEGIN
    IF NEW.purchase_date IS NOT NULL THEN
        SET NEW.purchase_month = MONTHNAME(NEW.purchase_date);
        SET NEW.purchase_year  = YEAR(NEW.purchase_date);
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_inventory_before_update` BEFORE UPDATE ON `inventory` FOR EACH ROW BEGIN
    IF NEW.purchase_date IS NOT NULL THEN
        SET NEW.purchase_month = MONTHNAME(NEW.purchase_date);
        SET NEW.purchase_year  = YEAR(NEW.purchase_date);
    ELSE
        SET NEW.purchase_month = NULL;
        SET NEW.purchase_year  = NULL;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_update_logs`
--

CREATE TABLE `inventory_update_logs` (
  `update_log_id` bigint(20) UNSIGNED NOT NULL,
  `inventory_no` varchar(30) NOT NULL,
  `company_id` bigint(20) UNSIGNED DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sub_category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `brand_id` bigint(20) UNSIGNED DEFAULT NULL,
  `model` varchar(150) DEFAULT NULL,
  `serial_number` varchar(150) DEFAULT NULL,
  `column_updated` varchar(100) NOT NULL,
  `old_data` text DEFAULT NULL,
  `new_data` text DEFAULT NULL,
  `updated_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_by_user_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sub_categories`
--

CREATE TABLE `sub_categories` (
  `sub_category_id` bigint(20) UNSIGNED NOT NULL,
  `sub_category_name` varchar(150) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sub_categories`
--

INSERT INTO `sub_categories` (`sub_category_id`, `sub_category_name`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'LAPTOP', 'Item sub category', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18'),
(2, 'Bag', 'Item sub category', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18'),
(3, 'Laptop Charger', 'Item sub category', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18'),
(4, 'SATA enclosure', 'Item sub category', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18'),
(5, 'CCTV camera', 'Item sub category', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18'),
(6, 'FlashDrive', 'Item sub category', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18'),
(7, 'HDMI', 'Item sub category', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18'),
(8, 'Keyboard', 'Item sub category', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18'),
(9, 'Mouse', 'Item sub category', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18'),
(10, 'Network', 'Item sub category', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18'),
(11, 'Tools', 'Item sub category', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18'),
(12, 'Monitor', 'Item sub category', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18'),
(13, 'Laser Presenter', 'Item sub category', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18'),
(14, 'Cable', 'Item sub category', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18'),
(15, 'PSU', 'Item sub category', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18'),
(16, 'RAM', 'Item sub category', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18'),
(17, 'Power Cord', 'Item sub category', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18'),
(18, 'Headphones', 'Item sub category', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18'),
(19, 'USB HUB', 'Item sub category', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18'),
(20, 'RFID Scanner', 'Item sub category', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18'),
(21, 'WiFi Adapter', 'Item sub category', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18'),
(22, 'Phone', 'Item sub category', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18'),
(23, 'Phone Charger', 'Item sub category', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18'),
(24, 'Desktop', 'Item sub category', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18'),
(25, 'Digital Ballpen', 'Item sub category', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18'),
(26, 'Nvm e ssd', 'Item sub category', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `role` enum('USER','VIEWER') NOT NULL DEFAULT 'VIEWER',
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone_number` varchar(50) DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `role`, `first_name`, `last_name`, `username`, `email`, `phone_number`, `password_hash`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'USER', 'System', 'Admin', 'admin', 'admin@example.com', NULL, '$2y$10$exampleexampleexampleexampleexampleexampleexampleexample12', 1, '2026-04-12 02:08:58', '2026-04-12 02:08:58');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `age_statuses`
--
ALTER TABLE `age_statuses`
  ADD PRIMARY KEY (`age_status_id`),
  ADD UNIQUE KEY `uq_age_statuses_name` (`status_name`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`brand_id`),
  ADD UNIQUE KEY `uq_brands_name` (`brand_name`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `uq_categories_name` (`category_name`);

--
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`company_id`),
  ADD UNIQUE KEY `uq_companies_code` (`company_code`),
  ADD UNIQUE KEY `uq_companies_name` (`company_name`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`department_id`),
  ADD UNIQUE KEY `uq_departments_name` (`department_name`),
  ADD UNIQUE KEY `uq_departments_code` (`department_code`);

--
-- Indexes for table `deployment_logs`
--
ALTER TABLE `deployment_logs`
  ADD PRIMARY KEY (`deployment_log_id`),
  ADD KEY `idx_deployment_logs_inventory_no` (`inventory_no`),
  ADD KEY `idx_deployment_logs_company_id` (`company_id`),
  ADD KEY `idx_deployment_logs_category_id` (`category_id`),
  ADD KEY `idx_deployment_logs_sub_category_id` (`sub_category_id`),
  ADD KEY `idx_deployment_logs_brand_id` (`brand_id`),
  ADD KEY `idx_deployment_logs_department_id` (`department_id`),
  ADD KEY `idx_deployment_logs_status_id` (`deployment_status_id`),
  ADD KEY `idx_deployment_logs_date_deployed` (`date_deployed`),
  ADD KEY `idx_deployment_logs_returned_date` (`returned_date`),
  ADD KEY `idx_deployment_logs_deployed_to` (`deployed_to`),
  ADD KEY `idx_deployment_logs_deployed_by_user_id` (`deployed_by_user_id`);

--
-- Indexes for table `deployment_statuses`
--
ALTER TABLE `deployment_statuses`
  ADD PRIMARY KEY (`deployment_status_id`),
  ADD UNIQUE KEY `uq_deployment_statuses_name` (`status_name`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`inventory_id`),
  ADD UNIQUE KEY `uq_inventory_no` (`inventory_no`),
  ADD KEY `idx_inventory_company` (`company_id`),
  ADD KEY `idx_inventory_category` (`category_id`),
  ADD KEY `idx_inventory_sub_category` (`sub_category_id`),
  ADD KEY `idx_inventory_brand` (`brand_id`),
  ADD KEY `idx_inventory_department` (`department_id`),
  ADD KEY `idx_inventory_age_status` (`age_status_id`),
  ADD KEY `idx_inventory_purchase_date` (`purchase_date`),
  ADD KEY `idx_inventory_device_name` (`device_name`),
  ADD KEY `idx_inventory_model` (`model`),
  ADD KEY `idx_inventory_deployment_status_id` (`deployment_status_id`),
  ADD KEY `idx_inventory_deployed_date` (`deployed_date`),
  ADD KEY `idx_inventory_returned_date` (`returned_date`),
  ADD KEY `idx_inventory_serial_number` (`serial_number`);

--
-- Indexes for table `inventory_update_logs`
--
ALTER TABLE `inventory_update_logs`
  ADD PRIMARY KEY (`update_log_id`),
  ADD KEY `idx_inventory_update_logs_inventory_no` (`inventory_no`),
  ADD KEY `idx_inventory_update_logs_company_id` (`company_id`),
  ADD KEY `idx_inventory_update_logs_category_id` (`category_id`),
  ADD KEY `idx_inventory_update_logs_sub_category_id` (`sub_category_id`),
  ADD KEY `idx_inventory_update_logs_brand_id` (`brand_id`),
  ADD KEY `idx_inventory_update_logs_column_updated` (`column_updated`),
  ADD KEY `idx_inventory_update_logs_updated_date` (`updated_date`),
  ADD KEY `idx_inventory_update_logs_updated_by_user_id` (`updated_by_user_id`);

--
-- Indexes for table `sub_categories`
--
ALTER TABLE `sub_categories`
  ADD PRIMARY KEY (`sub_category_id`),
  ADD UNIQUE KEY `uq_sub_categories_name` (`sub_category_name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `uq_users_username` (`username`),
  ADD UNIQUE KEY `uq_users_email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `age_statuses`
--
ALTER TABLE `age_statuses`
  MODIFY `age_status_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `brand_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `company_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `department_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `deployment_logs`
--
ALTER TABLE `deployment_logs`
  MODIFY `deployment_log_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `deployment_statuses`
--
ALTER TABLE `deployment_statuses`
  MODIFY `deployment_status_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `inventory_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `inventory_update_logs`
--
ALTER TABLE `inventory_update_logs`
  MODIFY `update_log_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sub_categories`
--
ALTER TABLE `sub_categories`
  MODIFY `sub_category_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `deployment_logs`
--
ALTER TABLE `deployment_logs`
  ADD CONSTRAINT `fk_deployment_logs_brand` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`brand_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_deployment_logs_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_deployment_logs_company` FOREIGN KEY (`company_id`) REFERENCES `companies` (`company_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_deployment_logs_department` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_deployment_logs_inventory_no` FOREIGN KEY (`inventory_no`) REFERENCES `inventory` (`inventory_no`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_deployment_logs_status` FOREIGN KEY (`deployment_status_id`) REFERENCES `deployment_statuses` (`deployment_status_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_deployment_logs_sub_category` FOREIGN KEY (`sub_category_id`) REFERENCES `sub_categories` (`sub_category_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_deployment_logs_user` FOREIGN KEY (`deployed_by_user_id`) REFERENCES `users` (`user_id`) ON UPDATE CASCADE;

--
-- Constraints for table `inventory`
--
ALTER TABLE `inventory`
  ADD CONSTRAINT `fk_inventory_age_status` FOREIGN KEY (`age_status_id`) REFERENCES `age_statuses` (`age_status_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_inventory_brand` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`brand_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_inventory_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_inventory_company` FOREIGN KEY (`company_id`) REFERENCES `companies` (`company_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_inventory_department` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_inventory_deployment_status` FOREIGN KEY (`deployment_status_id`) REFERENCES `deployment_statuses` (`deployment_status_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_inventory_sub_category` FOREIGN KEY (`sub_category_id`) REFERENCES `sub_categories` (`sub_category_id`) ON UPDATE CASCADE;

--
-- Constraints for table `inventory_update_logs`
--
ALTER TABLE `inventory_update_logs`
  ADD CONSTRAINT `fk_inventory_update_logs_brand` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`brand_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_inventory_update_logs_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_inventory_update_logs_company` FOREIGN KEY (`company_id`) REFERENCES `companies` (`company_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_inventory_update_logs_inventory_no` FOREIGN KEY (`inventory_no`) REFERENCES `inventory` (`inventory_no`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_inventory_update_logs_sub_category` FOREIGN KEY (`sub_category_id`) REFERENCES `sub_categories` (`sub_category_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_inventory_update_logs_user` FOREIGN KEY (`updated_by_user_id`) REFERENCES `users` (`user_id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;