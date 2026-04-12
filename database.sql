-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 12, 2026 at 04:09 AM
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
(1, 'NCIA', 'NCIA', 'Company list', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18'),
(2, 'NCIA1', 'NCIA 1', 'Company list', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18'),
(3, 'NCIA2', 'NCIA 2', 'Company list', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18'),
(4, 'NCIALIFE', 'NCIA Life', 'Company list', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18');

-- --------------------------------------------------------

--
-- Table structure for table `custodians`
--

CREATE TABLE `custodians` (
  `custodian_id` bigint(20) UNSIGNED NOT NULL,
  `custodian_name` varchar(150) NOT NULL,
  `custodian_type` enum('EMPLOYEE','PLACE','OTHER') NOT NULL DEFAULT 'EMPLOYEE',
  `employee_code` varchar(50) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `mobile_no` varchar(50) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `custodians`
--

INSERT INTO `custodians` (`custodian_id`, `custodian_name`, `custodian_type`, `employee_code`, `email`, `mobile_no`, `notes`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'TBD', 'OTHER', NULL, NULL, NULL, 'Placeholder custodian', 1, '2026-04-12 00:35:18', '2026-04-12 00:35:18');

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
(6, 'Borrowed', 'Borrowed item', 6, 1, '2026-04-12 01:43:26', '2026-04-12 01:43:26');

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
  `custodian_id` bigint(20) UNSIGNED DEFAULT NULL,
  `department_id` bigint(20) UNSIGNED DEFAULT NULL,
  `mac_address` varchar(50) DEFAULT NULL,
  `device_name` varchar(150) DEFAULT NULL,
  `current_os` varchar(100) DEFAULT NULL,
  `age_status_id` bigint(20) UNSIGNED DEFAULT NULL,
  `inventory_status_id` bigint(20) UNSIGNED NOT NULL,
  `deployment_status_id` bigint(20) UNSIGNED DEFAULT NULL,
  `deployed_date` date DEFAULT NULL,
  `returned_date` date DEFAULT NULL,
  `purchase_date` date DEFAULT NULL,
  `purchase_month` varchar(20) DEFAULT NULL,
  `purchase_year` year(4) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`inventory_id`, `inventory_no`, `company_id`, `category_id`, `sub_category_id`, `brand_id`, `model`, `item_description`, `serial_number`, `custodian_id`, `department_id`, `mac_address`, `device_name`, `current_os`, `age_status_id`, `inventory_status_id`, `deployment_status_id`, `deployed_date`, `returned_date`, `purchase_date`, `purchase_month`, `purchase_year`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 'NCIA-0001', 1, 2, 1, 1, 'INSPIRON 15 3000', 'Processor: i3 7th Gen\r\nRAM: 12 GB\r\nStorage HDD: 1 TB\r\nStorage SDD: 250 GB\r\nw+ charger', 'J1JF5P2', 1, 2, '20-BD-1D-83-D6-B4', 'LAPTOP-PC-FJRABAD', 'WINDOW 11', 1, 1, NULL, NULL, NULL, '2026-02-06', 'February', '2026', 'This is originally from ms Paula but was swapped\r\nPREVIOUS USER: JOSIE RUANES', '2026-04-12 00:35:18', '2026-04-12 01:43:26');

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
-- Table structure for table `inventory_statuses`
--

CREATE TABLE `inventory_statuses` (
  `inventory_status_id` bigint(20) UNSIGNED NOT NULL,
  `status_name` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inventory_statuses`
--

INSERT INTO `inventory_statuses` (`inventory_status_id`, `status_name`, `description`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'ACTIVE', 'Currently in use', 1, 1, '2026-04-12 01:43:26', '2026-04-12 01:43:26'),
(2, 'INACTIVE', 'Not currently in use', 2, 1, '2026-04-12 01:43:26', '2026-04-12 01:43:26'),
(3, 'RETIRED', 'Retired from service', 3, 1, '2026-04-12 01:43:26', '2026-04-12 01:43:26'),
(4, 'DISPOSED', 'Disposed item', 4, 1, '2026-04-12 01:43:26', '2026-04-12 01:43:26'),
(5, 'LOST', 'Lost item', 5, 1, '2026-04-12 01:43:26', '2026-04-12 01:43:26'),
(6, 'DAMAGED', 'Damaged item', 6, 1, '2026-04-12 01:43:26', '2026-04-12 01:43:26'),
(7, 'Stolen', 'Item was stolen', 7, 1, '2026-04-12 01:43:26', '2026-04-12 01:43:26'),
(8, 'Missing', 'Item is missing', 8, 1, '2026-04-12 01:43:26', '2026-04-12 01:43:26'),
(9, 'Spare', 'Spare / standby unit', 9, 1, '2026-04-12 01:43:26', '2026-04-12 01:43:26'),
(10, 'Available', 'Available for deployment or use', 10, 1, '2026-04-12 01:43:26', '2026-04-12 01:43:26');

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

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_inventory_details`
-- (See below for the actual view)
--
CREATE TABLE `vw_inventory_details` (
`inventory_id` bigint(20) unsigned
,`inventory_no` varchar(30)
,`company_name` varchar(100)
,`category_name` varchar(100)
,`sub_category_name` varchar(150)
,`brand_name` varchar(150)
,`model` varchar(150)
,`item_description` text
,`serial_number` varchar(150)
,`assigned_to` varchar(150)
,`department_name` varchar(150)
,`mac_address` varchar(50)
,`device_name` varchar(150)
,`current_os` varchar(100)
,`age_status` varchar(50)
,`inventory_status` varchar(50)
,`deployment_status` varchar(50)
,`deployed_date` date
,`returned_date` date
,`purchase_date` date
,`purchase_month` varchar(20)
,`purchase_year` year(4)
,`remarks` text
,`created_at` timestamp
,`updated_at` timestamp
);

-- --------------------------------------------------------

--
-- Structure for view `vw_inventory_details`
--
DROP TABLE IF EXISTS `vw_inventory_details`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_inventory_details`  AS SELECT `i`.`inventory_id` AS `inventory_id`, `i`.`inventory_no` AS `inventory_no`, `c`.`company_name` AS `company_name`, `cat`.`category_name` AS `category_name`, `sc`.`sub_category_name` AS `sub_category_name`, `b`.`brand_name` AS `brand_name`, `i`.`model` AS `model`, `i`.`item_description` AS `item_description`, `i`.`serial_number` AS `serial_number`, `cu`.`custodian_name` AS `assigned_to`, `d`.`department_name` AS `department_name`, `i`.`mac_address` AS `mac_address`, `i`.`device_name` AS `device_name`, `i`.`current_os` AS `current_os`, `ag`.`status_name` AS `age_status`, `ist`.`status_name` AS `inventory_status`, `dst`.`status_name` AS `deployment_status`, `i`.`deployed_date` AS `deployed_date`, `i`.`returned_date` AS `returned_date`, `i`.`purchase_date` AS `purchase_date`, `i`.`purchase_month` AS `purchase_month`, `i`.`purchase_year` AS `purchase_year`, `i`.`remarks` AS `remarks`, `i`.`created_at` AS `created_at`, `i`.`updated_at` AS `updated_at` FROM (((((((((`inventory` `i` join `companies` `c` on(`i`.`company_id` = `c`.`company_id`)) join `categories` `cat` on(`i`.`category_id` = `cat`.`category_id`)) join `sub_categories` `sc` on(`i`.`sub_category_id` = `sc`.`sub_category_id`)) join `brands` `b` on(`i`.`brand_id` = `b`.`brand_id`)) left join `custodians` `cu` on(`i`.`custodian_id` = `cu`.`custodian_id`)) left join `departments` `d` on(`i`.`department_id` = `d`.`department_id`)) left join `age_statuses` `ag` on(`i`.`age_status_id` = `ag`.`age_status_id`)) left join `inventory_statuses` `ist` on(`i`.`inventory_status_id` = `ist`.`inventory_status_id`)) left join `deployment_statuses` `dst` on(`i`.`deployment_status_id` = `dst`.`deployment_status_id`)) ;

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
-- Indexes for table `custodians`
--
ALTER TABLE `custodians`
  ADD PRIMARY KEY (`custodian_id`),
  ADD UNIQUE KEY `uq_custodians_name` (`custodian_name`),
  ADD UNIQUE KEY `uq_custodians_employee_code` (`employee_code`),
  ADD UNIQUE KEY `uq_custodians_email` (`email`);

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
  ADD UNIQUE KEY `uq_inventory_serial_number` (`serial_number`),
  ADD KEY `idx_inventory_company` (`company_id`),
  ADD KEY `idx_inventory_category` (`category_id`),
  ADD KEY `idx_inventory_sub_category` (`sub_category_id`),
  ADD KEY `idx_inventory_brand` (`brand_id`),
  ADD KEY `idx_inventory_custodian` (`custodian_id`),
  ADD KEY `idx_inventory_department` (`department_id`),
  ADD KEY `idx_inventory_age_status` (`age_status_id`),
  ADD KEY `idx_inventory_purchase_date` (`purchase_date`),
  ADD KEY `idx_inventory_device_name` (`device_name`),
  ADD KEY `idx_inventory_model` (`model`),
  ADD KEY `idx_inventory_inventory_status_id` (`inventory_status_id`),
  ADD KEY `idx_inventory_deployment_status_id` (`deployment_status_id`),
  ADD KEY `idx_inventory_deployed_date` (`deployed_date`),
  ADD KEY `idx_inventory_returned_date` (`returned_date`);

--
-- Indexes for table `inventory_statuses`
--
ALTER TABLE `inventory_statuses`
  ADD PRIMARY KEY (`inventory_status_id`),
  ADD UNIQUE KEY `uq_inventory_statuses_name` (`status_name`);

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
  MODIFY `age_status_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `brand_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `company_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `custodians`
--
ALTER TABLE `custodians`
  MODIFY `custodian_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
  MODIFY `deployment_status_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `inventory_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_statuses`
--
ALTER TABLE `inventory_statuses`
  MODIFY `inventory_status_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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
  ADD CONSTRAINT `fk_inventory_custodian` FOREIGN KEY (`custodian_id`) REFERENCES `custodians` (`custodian_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_inventory_department` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_inventory_deployment_status` FOREIGN KEY (`deployment_status_id`) REFERENCES `deployment_statuses` (`deployment_status_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_inventory_inventory_status` FOREIGN KEY (`inventory_status_id`) REFERENCES `inventory_statuses` (`inventory_status_id`) ON UPDATE CASCADE,
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
