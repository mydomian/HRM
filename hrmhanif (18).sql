-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 19, 2022 at 08:59 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.0.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hrmhanif`
--

-- --------------------------------------------------------

--
-- Table structure for table `acc_areas`
--

CREATE TABLE `acc_areas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `package_buy_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','deactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `view_id` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `acc_categories`
--

CREATE TABLE `acc_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `package_buy_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','deactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `view_id` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `acc_customer_suppliers`
--

CREATE TABLE `acc_customer_suppliers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `package_buy_id` bigint(20) UNSIGNED NOT NULL,
  `acc_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `acc_no` bigint(20) NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` bigint(20) NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `word` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `acc_area` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `acc_opening_balance` bigint(20) NOT NULL,
  `acc_hold_balance` bigint(20) NOT NULL,
  `profile_image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nid_image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `status` enum('active','deactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `acc_customer_suppliers`
--

INSERT INTO `acc_customer_suppliers` (`id`, `package_buy_id`, `acc_name`, `acc_no`, `email`, `phone`, `address`, `word`, `acc_area`, `acc_opening_balance`, `acc_hold_balance`, `profile_image`, `nid_image`, `date`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Testing Account Name', 768833248427329, 'testing3@gmail.com', 17000000, 'Chad Uddan', '01', 'Mohammadpur', 50000, 10000, '127.0.0.1:8000/public/images/profile_image/1667825869.png', '127.0.0.1:8000/public/images/nid_image/1667825869.png', '2022-11-07', 'active', '2022-11-07 06:57:49', '2022-11-07 06:57:49');

-- --------------------------------------------------------

--
-- Table structure for table `acc_types`
--

CREATE TABLE `acc_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `package_buy_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','deactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `view_id` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_as` enum('Admin','Moderator') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Moderator',
  `activation` enum('Enable','Disable') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Enable',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `phone`, `password`, `role_as`, `activation`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@gmail.com', '01700000000', '$2y$10$V9nAUbaFHO3D2VlBhzUFzea/zDh2Ua1J2C0cstc8sdgt9/3w1DJUy', 'Admin', 'Enable', '2022-11-07 12:54:12', '2022-11-07 12:54:12');

-- --------------------------------------------------------

--
-- Table structure for table `banks`
--

CREATE TABLE `banks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `package_buy_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','deactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `view_id` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bank_acc_categories`
--

CREATE TABLE `bank_acc_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `package_buy_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','deactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `view_id` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bank_acc_types`
--

CREATE TABLE `bank_acc_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `package_buy_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','deactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `view_id` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bank_branches`
--

CREATE TABLE `bank_branches` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `package_buy_id` bigint(20) UNSIGNED NOT NULL,
  `bank_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','deactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `view_id` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `package_buy_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','deactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `view_id` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `package_buy_id`, `name`, `status`, `view_id`, `created_at`, `updated_at`) VALUES
(1, 1, 'Testing One Brand', 'active', NULL, '2022-11-07 06:56:46', '2022-11-07 06:56:46');

-- --------------------------------------------------------

--
-- Table structure for table `cash_counters`
--

CREATE TABLE `cash_counters` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `package_buy_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','deactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `view_id` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `package_buy_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','deactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `view_id` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `package_buy_id`, `name`, `status`, `view_id`, `created_at`, `updated_at`) VALUES
(1, 1, 'Testing One Category', 'active', NULL, '2022-11-07 06:56:54', '2022-11-07 06:56:54');

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `package_buy_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','deactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `view_id` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`id`, `package_buy_id`, `name`, `status`, `view_id`, `created_at`, `updated_at`) VALUES
(1, 1, 'Testing', 'active', NULL, '2022-11-07 06:58:14', '2022-11-07 06:58:14');

-- --------------------------------------------------------

--
-- Table structure for table `deliveries`
--

CREATE TABLE `deliveries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `package_buy_id` bigint(20) UNSIGNED NOT NULL,
  `sale_id` bigint(20) UNSIGNED NOT NULL,
  `delivery_invoice_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ware_house_id` bigint(20) UNSIGNED NOT NULL,
  `vehicale_id` bigint(20) UNSIGNED NOT NULL,
  `delivery_details` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_qty` bigint(20) NOT NULL,
  `total_receipt` bigint(20) NOT NULL,
  `total_pending` bigint(20) NOT NULL,
  `delivery_date` date NOT NULL,
  `status` enum('pending','accept','delivered') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `deliveries`
--

INSERT INTO `deliveries` (`id`, `package_buy_id`, `sale_id`, `delivery_invoice_no`, `ware_house_id`, `vehicale_id`, `delivery_details`, `total_qty`, `total_receipt`, `total_pending`, `delivery_date`, `status`, `created_at`, `updated_at`) VALUES
(10, 1, 9, '2211172527', 1, 1, 'Testing', 8, 4, 4, '2022-11-17', 'pending', '2022-11-17 04:25:27', '2022-11-17 04:25:27');

-- --------------------------------------------------------

--
-- Table structure for table `delivery_challans`
--

CREATE TABLE `delivery_challans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `package_buy_id` bigint(20) UNSIGNED NOT NULL,
  `sale_invoice_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `delivery_invoice_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `delivery_challan_invoice_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `vehicale_id` bigint(20) UNSIGNED NOT NULL,
  `challan_details` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_qty` bigint(20) NOT NULL,
  `total_receipt` bigint(20) NOT NULL,
  `total_pending` bigint(20) NOT NULL,
  `challan_date` date NOT NULL,
  `document` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','accept') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `delivery_challans`
--

INSERT INTO `delivery_challans` (`id`, `package_buy_id`, `sale_invoice_no`, `delivery_invoice_no`, `delivery_challan_invoice_no`, `vehicale_id`, `challan_details`, `total_qty`, `total_receipt`, `total_pending`, `challan_date`, `document`, `status`, `created_at`, `updated_at`) VALUES
(4, 1, '2211091707', '2211134520', '2211131846', 1, 'Testing Challan Details', 8, 5, 3, '2022-11-13', '127.0.0.1:8000/public/images/delivery_challan/1668334726.png', 'pending', '2022-11-13 04:18:46', '2022-11-13 04:18:46'),
(5, 1, '2211153626', '2211153959', '2211154224', 1, 'Testing Challan Details', 8, 4, 4, '2022-11-15', '127.0.0.1:8000/public/images/delivery_challan/1668512544.png', 'pending', '2022-11-15 05:42:24', '2022-11-15 05:42:24'),
(6, 1, '2211173926', '2211174749', '2211171601', 1, 'Testing Challan Details', 8, 4, 4, '2022-11-17', '127.0.0.1:8000/public/images/delivery_challan/1668669361.png', 'pending', '2022-11-17 01:16:01', '2022-11-17 01:16:01'),
(7, 1, '2211173926', '2211174749', '2211172459', 1, 'Testing Challan Details', 8, 4, 4, '2022-11-17', '127.0.0.1:8000/public/images/delivery_challan/1668669899.png', 'pending', '2022-11-17 01:24:59', '2022-11-17 01:24:59'),
(8, 1, '2211173926', '2211174749', '2211173149', 1, 'Testing Challan Details', 8, 4, 4, '2022-11-17', '127.0.0.1:8000/public/images/delivery_challan/1668670309.png', 'pending', '2022-11-17 01:31:49', '2022-11-17 01:31:49'),
(9, 1, '2211171125', '2211172527', '2211172957', 1, 'Testing Challan Details', 8, 4, 4, '2022-11-17', '127.0.0.1:8000/public/images/delivery_challan/1668680997.png', 'pending', '2022-11-17 04:29:57', '2022-11-17 04:29:57'),
(10, 1, '2211171125', '2211172527', '2211173545', 1, 'Testing Challan Details', 8, 4, 4, '2022-11-17', '127.0.0.1:8000/public/images/delivery_challan/1668681345.png', 'pending', '2022-11-17 04:35:45', '2022-11-17 04:35:45');

-- --------------------------------------------------------

--
-- Table structure for table `delivery_challan_items`
--

CREATE TABLE `delivery_challan_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `package_buy_id` bigint(20) UNSIGNED NOT NULL,
  `sale_invoice_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `delivery_invoice_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `delivery_challan_id` bigint(20) UNSIGNED NOT NULL,
  `delivery_challan_invoice_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `delivery_item_id` bigint(20) UNSIGNED NOT NULL,
  `unit_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `avg_qty` bigint(20) NOT NULL,
  `bag` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `qty` bigint(20) NOT NULL,
  `rate` bigint(20) NOT NULL,
  `amount` bigint(20) NOT NULL,
  `order` bigint(20) NOT NULL,
  `receipt` bigint(20) NOT NULL,
  `pending` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `delivery_challan_items`
--

INSERT INTO `delivery_challan_items` (`id`, `package_buy_id`, `sale_invoice_no`, `delivery_invoice_no`, `delivery_challan_id`, `delivery_challan_invoice_no`, `delivery_item_id`, `unit_id`, `product_id`, `avg_qty`, `bag`, `qty`, `rate`, `amount`, `order`, `receipt`, `pending`, `created_at`, `updated_at`) VALUES
(13, 1, '2211171125', '2211172527', 9, '2211172957', 18, 1, 13, 1, '20', 3, 200, 1000, 5, 2, 3, '2022-11-17 04:29:57', '2022-11-17 04:29:57'),
(14, 1, '2211171125', '2211172527', 10, '2211173545', 18, 1, 13, 1, '20', 3, 200, 1000, 5, 2, 3, '2022-11-17 04:35:45', '2022-11-17 04:35:45'),
(15, 1, '2211171125', '2211172527', 10, '2211173545', 19, 1, 14, 1, '15', 2, 250, 750, 3, 2, 1, '2022-11-17 04:35:45', '2022-11-17 04:35:45');

-- --------------------------------------------------------

--
-- Table structure for table `delivery_histories`
--

CREATE TABLE `delivery_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `package_buy_id` bigint(20) UNSIGNED NOT NULL,
  `sale_id` bigint(20) UNSIGNED NOT NULL,
  `delivery_id` bigint(20) UNSIGNED NOT NULL,
  `delivery_item_id` bigint(20) UNSIGNED NOT NULL,
  `sale_item_id` bigint(20) UNSIGNED NOT NULL,
  `order` bigint(20) NOT NULL,
  `receipt` bigint(20) NOT NULL,
  `pending` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `delivery_histories`
--

INSERT INTO `delivery_histories` (`id`, `package_buy_id`, `sale_id`, `delivery_id`, `delivery_item_id`, `sale_item_id`, `order`, `receipt`, `pending`, `created_at`, `updated_at`) VALUES
(13, 1, 9, 10, 18, 13, 5, 2, 3, '2022-11-17 04:25:27', '2022-11-17 04:25:27'),
(14, 1, 9, 10, 19, 14, 3, 2, 1, '2022-11-17 04:25:27', '2022-11-17 04:25:27');

-- --------------------------------------------------------

--
-- Table structure for table `delivery_items`
--

CREATE TABLE `delivery_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `package_buy_id` bigint(20) UNSIGNED NOT NULL,
  `sale_id` bigint(20) UNSIGNED NOT NULL,
  `delivery_id` bigint(20) UNSIGNED NOT NULL,
  `delivery_invoice_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sale_item_id` bigint(20) UNSIGNED NOT NULL,
  `order` bigint(20) NOT NULL,
  `receipt` bigint(20) NOT NULL,
  `pending` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `delivery_items`
--

INSERT INTO `delivery_items` (`id`, `package_buy_id`, `sale_id`, `delivery_id`, `delivery_invoice_no`, `sale_item_id`, `order`, `receipt`, `pending`, `created_at`, `updated_at`) VALUES
(18, 1, 9, 10, '2211172527', 13, 5, 2, 3, '2022-11-17 04:25:27', '2022-11-17 04:25:27'),
(19, 1, 9, 10, '2211172527', 14, 3, 2, 1, '2022-11-17 04:25:27', '2022-11-17 04:25:27');

-- --------------------------------------------------------

--
-- Table structure for table `designations`
--

CREATE TABLE `designations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `package_buy_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','deactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `view_id` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `districts`
--

CREATE TABLE `districts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `package_buy_id` bigint(20) UNSIGNED NOT NULL,
  `city_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','deactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `view_id` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `districts`
--

INSERT INTO `districts` (`id`, `package_buy_id`, `city_id`, `name`, `status`, `view_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Testing District', 'active', NULL, '2022-11-07 06:58:21', '2022-11-07 06:58:21');

-- --------------------------------------------------------

--
-- Table structure for table `drivers`
--

CREATE TABLE `drivers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `package_buy_id` bigint(20) UNSIGNED NOT NULL,
  `vehicle_id` bigint(20) UNSIGNED NOT NULL,
  `city_id` bigint(20) UNSIGNED NOT NULL,
  `district_id` bigint(20) UNSIGNED NOT NULL,
  `thana_id` bigint(20) UNSIGNED NOT NULL,
  `union_id` bigint(20) UNSIGNED NOT NULL,
  `driver_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `driver_phone` bigint(20) NOT NULL,
  `father_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `driver_post_office` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `driver_village` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `drivers`
--

INSERT INTO `drivers` (`id`, `package_buy_id`, `vehicle_id`, `city_id`, `district_id`, `thana_id`, `union_id`, `driver_name`, `driver_phone`, `father_name`, `driver_post_office`, `driver_village`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 1, 1, 1, 'Testing driver Name', 136598525, 'Testing Father Name', '12015', 'dfnfnddsferf', '2022-11-07 06:59:07', '2022-11-07 06:59:07');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `income_expense_acc_types`
--

CREATE TABLE `income_expense_acc_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `package_buy_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','deactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `view_id` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `income_expense_payment_method_types`
--

CREATE TABLE `income_expense_payment_method_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `package_buy_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','deactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `view_id` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lot_gallaries`
--

CREATE TABLE `lot_gallaries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `package_buy_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','deactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `view_id` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lot_gallaries`
--

INSERT INTO `lot_gallaries` (`id`, `package_buy_id`, `name`, `status`, `view_id`, `created_at`, `updated_at`) VALUES
(1, 1, 'Testing One Lot Gallary', 'active', NULL, '2022-11-07 06:57:34', '2022-11-07 06:57:34');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2016_06_01_000001_create_oauth_auth_codes_table', 1),
(4, '2016_06_01_000002_create_oauth_access_tokens_table', 1),
(5, '2016_06_01_000003_create_oauth_refresh_tokens_table', 1),
(6, '2016_06_01_000004_create_oauth_clients_table', 1),
(7, '2016_06_01_000005_create_oauth_personal_access_clients_table', 1),
(8, '2019_08_19_000000_create_failed_jobs_table', 1),
(9, '2022_07_26_052339_create_admins_table', 1),
(10, '2022_07_27_054929_create_packages_table', 1),
(11, '2022_10_06_090149_create_brands_table', 1),
(12, '2022_10_06_090340_create_categories_table', 1),
(13, '2022_10_06_090457_create_lot_gallaries_table', 1),
(14, '2022_10_06_090958_create_units_table', 1),
(15, '2022_10_06_121417_create_production_types_table', 1),
(16, '2022_10_06_122129_create_acc_categories_table', 1),
(17, '2022_10_06_122332_create_acc_types_table', 1),
(18, '2022_10_06_122716_create_acc_areas_table', 1),
(19, '2022_10_06_122732_create_payment_methods_table', 1),
(20, '2022_10_06_122754_create_cash_counters_table', 1),
(21, '2022_10_06_122816_create_bank_acc_categories_table', 1),
(22, '2022_10_06_122842_create_income_expense_acc_types_table', 1),
(23, '2022_10_06_122911_create_income_expense_payment_method_types_table', 1),
(24, '2022_10_06_122935_create_ware_houses_table', 1),
(25, '2022_10_06_124150_create_cities_table', 1),
(26, '2022_10_06_124207_create_districts_table', 1),
(27, '2022_10_06_124222_create_thanas_table', 1),
(28, '2022_10_06_124238_create_unions_table', 1),
(29, '2022_10_07_085900_create_vehicales_table', 1),
(30, '2022_10_10_042624_create_package_buys_table', 1),
(31, '2022_10_10_052258_create_drivers_table', 1),
(32, '2022_10_12_054926_create_acc_customer_suppliers_table', 1),
(33, '2022_10_12_121520_add_columns_to_users_table', 1),
(34, '2022_10_12_121942_create_products_table', 1),
(35, '2022_10_20_063038_create_vehicale_types_table', 1),
(36, '2022_10_20_090621_create_banks_table', 1),
(37, '2022_10_20_091422_create_bank_branches_table', 1),
(38, '2022_10_20_091612_create_designations_table', 1),
(39, '2022_10_20_091752_create_bank_acc_types_table', 1),
(40, '2022_10_24_055830_create_sale_quotations_table', 1),
(41, '2022_10_24_064653_create_sale_quotation_items_table', 1),
(42, '2022_10_24_112640_create_purchase_quotations_table', 1),
(43, '2022_10_24_112959_create_purchase_quotation_items_table', 1),
(44, '2022_10_25_045741_create_purchases_table', 1),
(45, '2022_10_25_062450_create_purchase_items_table', 1),
(46, '2022_10_25_072707_create_product_order_bies_table', 1),
(47, '2022_10_25_111036_create_sales_table', 1),
(48, '2022_10_25_111422_create_sale_items_table', 1),
(49, '2022_10_26_053604_create_receipts_table', 1),
(50, '2022_10_26_062009_create_receipt_items_table', 1),
(51, '2022_10_31_051757_create_deliveries_table', 1),
(52, '2022_10_31_053004_create_delivery_items_table', 1),
(53, '2022_11_02_112509_create_sale_challans_table', 1),
(54, '2022_11_02_115510_create_sale_challan_items_table', 1),
(55, '2022_11_03_065535_create_receipt_histories_table', 1),
(69, '2022_11_06_070353_create_receipt_challans_table', 8),
(72, '2022_11_06_070916_create_receipt_challan_items_table', 9),
(73, '2022_11_06_092332_create_delivery_challans_table', 10),
(75, '2022_11_06_092642_create_delivery_challan_items_table', 11),
(76, '2022_11_13_092215_create_delivery_histories_table', 12),
(80, '2022_11_15_090428_create_stock_transfers_table', 16),
(81, '2022_11_15_091718_create_stock_transfer_items_table', 17),
(86, '2022_11_14_055940_create_stocks_table', 18),
(87, '2022_11_14_061700_create_stock_histories_table', 19);

-- --------------------------------------------------------

--
-- Table structure for table `oauth_access_tokens`
--

CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_access_tokens`
--

INSERT INTO `oauth_access_tokens` (`id`, `user_id`, `client_id`, `name`, `scopes`, `revoked`, `created_at`, `updated_at`, `expires_at`) VALUES
('1f0822329c2f9e55aff651737b168569daf251d0aac4289a97b43c72cebb1f080da7c2b6c9bb201a', 1, 1, 'testingcompany@gmail.com', '[]', 0, '2022-11-12 22:52:26', '2022-11-12 22:52:26', '2023-11-13 04:52:26'),
('202974b5a1566183904a39241965f10a2883091f0af44b66fcafc88e1e1554280cfbb1923794e2ab', 1, 1, 'testingcompany@gmail.com', '[]', 0, '2022-11-09 23:26:09', '2022-11-09 23:26:09', '2023-11-10 05:26:09'),
('289dfcbfa596edfbcb876c12a6aac7abbe3980ba2f9450b5fffc3d88e119086c3d3f1daa8378e0dc', 1, 1, 'testingcompany@gmail.com', '[]', 0, '2022-11-08 23:02:58', '2022-11-08 23:02:58', '2023-11-09 05:02:58'),
('599b29e4c56d14ea9e04a8ae2ab29681d21e5723bf6feb0cb9cc9ec11ef82e330d258e96b1f14157', 1, 1, 'testingcompany@gmail.com', '[]', 0, '2022-11-13 04:18:08', '2022-11-13 04:18:08', '2023-11-13 10:18:08'),
('764eec611da72cec5110bc8edae52792640be640dbaeb86fc95f53fe9efdd5e997d47df37ed62425', 1, 1, 'testingcompany@gmail.com', '[]', 0, '2022-11-16 23:06:37', '2022-11-16 23:06:37', '2023-11-17 05:06:37'),
('7793dc954e55030278312e665458e684f4d7d541ea9bb7223ca21043e996a557bb4a09cdf715c14c', 1, 1, 'testingcompany@gmail.com', '[]', 0, '2022-11-08 22:17:55', '2022-11-08 22:17:55', '2023-11-09 04:17:55'),
('84d9b194da8572fa6316f11fe139283fa3e81a68a31ebecb962e05402c7cd74414b580cedbd6ecad', 1, 1, 'testingcompany@gmail.com', '[]', 0, '2022-11-10 03:43:40', '2022-11-10 03:43:40', '2023-11-10 09:43:40'),
('a9a674945912c5f6122a7160902c9e65728feb772b7f109a72f0da37a6cb103499672d876fee9fe1', 1, 1, 'testingcompany@gmail.com', '[]', 0, '2022-11-13 03:16:08', '2022-11-13 03:16:08', '2023-11-13 09:16:08'),
('b703d9751367c7c78c448d3cf424d7e9f8e65aeb3ee409c4a5399a85ead69f9c6cc74e27ef386443', 1, 1, 'testingcompany@gmail.com', '[]', 0, '2022-11-18 22:33:36', '2022-11-18 22:33:36', '2023-11-19 04:33:36'),
('d05b9fcf791abffbdbcffae88888af18074cb59cabc6d05a796220dd8e865e4f69ca2e69b55ceed3', 1, 1, 'testingcompany@gmail.com', '[]', 0, '2022-11-14 23:31:22', '2022-11-14 23:31:22', '2023-11-15 05:31:22'),
('d0781871c8e8124149882b048507d949267d6d821a7286c273dc3620a8d302e766f3260c8a6534a6', 1, 1, 'testingcompany@gmail.com', '[]', 0, '2022-11-07 06:56:37', '2022-11-07 06:56:37', '2023-11-07 12:56:37'),
('d2ed556ad47a12d25aaa9c27354421393f47c81181ab088074820c186c92f5e791fe49915796f431', 1, 1, 'testingcompany@gmail.com', '[]', 0, '2022-11-09 06:36:38', '2022-11-09 06:36:38', '2023-11-09 12:36:38'),
('e20d9af76694d387598ee591a264bf52ccfd962ce2d75fedd4f9dbc73ff1621239f28de4510f076c', 1, 1, 'testingcompany@gmail.com', '[]', 0, '2022-11-15 04:04:01', '2022-11-15 04:04:01', '2023-11-15 10:04:01'),
('ec3220cca7025ae7d6c609290bb1e7455c5da8d3b947668a90353162bc21cf914d8f72a609487f7a', 1, 1, 'testingcompany@gmail.com', '[]', 0, '2022-11-09 01:16:13', '2022-11-09 01:16:13', '2023-11-09 07:16:13'),
('f3fbc299a5ae3202f36ae58956ed1096356214e4adb8fdedd34eb8aca92ff7a372272ce96eea3540', 1, 1, 'testingcompany@gmail.com', '[]', 0, '2022-11-14 00:46:34', '2022-11-14 00:46:34', '2023-11-14 06:46:34');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_auth_codes`
--

CREATE TABLE `oauth_auth_codes` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_clients`
--

CREATE TABLE `oauth_clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `redirect` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_clients`
--

INSERT INTO `oauth_clients` (`id`, `user_id`, `name`, `secret`, `provider`, `redirect`, `personal_access_client`, `password_client`, `revoked`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Laravel Personal Access Client', 'TFcV5ZBif43prFLOPkHpIJ5z9fs4Lm16dA5IUryk', NULL, 'http://localhost', 1, 0, 0, '2022-11-07 06:55:09', '2022-11-07 06:55:09'),
(2, NULL, 'Laravel Password Grant Client', 'BXTDnqE7Px0rmkDoWAc5aOpfp0dAQQ7X1u8kjh4D', 'users', 'http://localhost', 0, 1, 0, '2022-11-07 06:55:09', '2022-11-07 06:55:09');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_personal_access_clients`
--

CREATE TABLE `oauth_personal_access_clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_personal_access_clients`
--

INSERT INTO `oauth_personal_access_clients` (`id`, `client_id`, `created_at`, `updated_at`) VALUES
(1, 1, '2022-11-07 06:55:09', '2022-11-07 06:55:09');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_refresh_tokens`
--

CREATE TABLE `oauth_refresh_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_token_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE `packages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `package_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `package_price` int(11) NOT NULL,
  `package_feature` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `duration_days` bigint(20) NOT NULL,
  `status` enum('active','deactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `packages`
--

INSERT INTO `packages` (`id`, `package_name`, `package_price`, `package_feature`, `duration_days`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Package', 2500, 'Package + Ecommerce', 2500, 'active', '2022-11-07 06:55:41', '2022-11-07 06:55:41');

-- --------------------------------------------------------

--
-- Table structure for table `package_buys`
--

CREATE TABLE `package_buys` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `package_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_no` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaction_id` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `duration` bigint(20) NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `database_name` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','active','deactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `package_buys`
--

INSERT INTO `package_buys` (`id`, `package_id`, `name`, `email`, `company_name`, `payment_type`, `account_no`, `transaction_id`, `duration`, `start_date`, `end_date`, `database_name`, `password`, `status`, `date`, `created_at`, `updated_at`) VALUES
(1, 1, 'Testingnin', 'testingcompany@gmail.com', 'Testing Company', 'Moble Banking', '64984654698', 'sdfshhwehriuewhrihe', 2500, '2022-11-07', '2029-09-11', 'TestingCompany', 'j6J41vhMO8n5cU1', 'active', '2022-11-07', '2022-11-07 06:55:49', '2022-11-07 06:55:55');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

CREATE TABLE `payment_methods` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `package_buy_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','deactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `view_id` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_methods`
--

INSERT INTO `payment_methods` (`id`, `package_buy_id`, `name`, `status`, `view_id`, `created_at`, `updated_at`) VALUES
(1, 1, 'Testing Payment Method', 'active', NULL, '2022-11-08 22:24:43', '2022-11-08 22:24:43');

-- --------------------------------------------------------

--
-- Table structure for table `production_types`
--

CREATE TABLE `production_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `package_buy_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','deactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `view_id` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `package_buy_id` bigint(20) UNSIGNED NOT NULL,
  `acc_cus_sup_id` bigint(20) UNSIGNED NOT NULL,
  `brand_id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `unit_id` bigint(20) UNSIGNED NOT NULL,
  `lot_gallary_id` bigint(20) UNSIGNED NOT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_model` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_qty` bigint(20) NOT NULL DEFAULT 0,
  `batch_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `serial_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `khat_acc_id` bigint(20) DEFAULT NULL,
  `supplier_price` bigint(20) NOT NULL,
  `our_price` bigint(20) NOT NULL,
  `status` enum('active','deactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `view_id` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `package_buy_id`, `acc_cus_sup_id`, `brand_id`, `category_id`, `unit_id`, `lot_gallary_id`, `product_name`, `product_model`, `product_image`, `product_qty`, `batch_no`, `serial_no`, `khat_acc_id`, `supplier_price`, `our_price`, `status`, `view_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 1, 1, 1, 'Testing Seven Product Name', 'Testing Model', '127.0.0.1:8000/public/images/product_image/1667825886.jpg', 0, '476396141591805', '1', NULL, 5000, 5500, 'active', NULL, '2022-11-07 06:58:06', '2022-11-07 06:58:06'),
(2, 1, 1, 1, 1, 1, 1, 'Testing Seven Product Name', 'Testing Model', '127.0.0.1:8000/public/images/product_image/1667967839.jpg', 0, '322177332978633', '1', NULL, 5000, 5500, 'active', NULL, '2022-11-08 22:23:59', '2022-11-08 22:23:59'),
(3, 1, 1, 1, 1, 1, 1, 'Testing Eight Product Name', 'Testing Model', '127.0.0.1:8000/public/images/product_image/1668408453.jpg', 0, '190985618656545', '1', NULL, 5000, 5500, 'active', NULL, '2022-11-14 00:47:33', '2022-11-14 00:47:33'),
(4, 1, 1, 1, 1, 1, 1, 'Testing Nine Product Name', 'Testing Model', '127.0.0.1:8000/public/images/product_image/1668409566.jpg', 0, '683046397486379', '1', NULL, 5000, 5500, 'active', NULL, '2022-11-14 01:06:06', '2022-11-14 01:06:06'),
(5, 1, 1, 1, 1, 1, 1, 'Testing Ten Product Name', 'Testing Model', '127.0.0.1:8000/public/images/product_image/1668414724.jpg', 0, '330637423540561', '1', NULL, 5000, 5500, 'active', NULL, '2022-11-14 02:32:04', '2022-11-14 02:32:04'),
(6, 1, 1, 1, 1, 1, 1, 'Testing Eleven Product Name', 'Testing Model', '127.0.0.1:8000/public/images/product_image/1668414731.jpg', 0, '823134485337552', '1', NULL, 5000, 5500, 'active', NULL, '2022-11-14 02:32:11', '2022-11-14 02:32:11'),
(7, 1, 1, 1, 1, 1, 1, 'Testing 12 Product Name', 'Testing Model', '127.0.0.1:8000/public/images/product_image/1668415964.jpg', 0, '551220020780167', '1', NULL, 5000, 5500, 'active', NULL, '2022-11-14 02:52:44', '2022-11-14 02:52:44'),
(8, 1, 1, 1, 1, 1, 1, 'Testing 13 Product Name', 'Testing Model', '127.0.0.1:8000/public/images/product_image/1668416024.jpg', 0, '180290115544846', '1', NULL, 5000, 5500, 'active', NULL, '2022-11-14 02:53:44', '2022-11-14 02:53:44'),
(9, 1, 1, 1, 1, 1, 1, 'Testing 14 Product Name', 'Testing Model', '127.0.0.1:8000/public/images/product_image/1668420829.jpg', 0, '994741864934689', '1', NULL, 5000, 5500, 'active', NULL, '2022-11-14 04:13:49', '2022-11-14 04:13:49'),
(10, 1, 1, 1, 1, 1, 1, 'Testing 15 Product Name', 'Testing Model', '127.0.0.1:8000/public/images/product_image/1668420835.jpg', 0, '999149934033116', '1', NULL, 5000, 5500, 'active', NULL, '2022-11-14 04:13:55', '2022-11-14 04:13:55'),
(11, 1, 1, 1, 1, 1, 1, 'Testing 16 Product Name', 'Testing Model', '127.0.0.1:8000/public/images/product_image/1668511152.jpg', 0, '108484466749223', '1', NULL, 5000, 5500, 'active', NULL, '2022-11-15 05:19:12', '2022-11-15 05:19:12'),
(12, 1, 1, 1, 1, 1, 1, 'Testing 16 Product Name', 'Testing Model', '127.0.0.1:8000/public/images/product_image/1668511210.jpg', 0, '125116133542782', '1', NULL, 5000, 5500, 'active', NULL, '2022-11-15 05:20:10', '2022-11-15 05:20:10'),
(13, 1, 1, 1, 1, 1, 1, 'Testing 16 Product Name', 'Testing Model', '127.0.0.1:8000/public/images/product_image/1668511297.jpg', 0, '691123449441850', '1', NULL, 5000, 5500, 'active', NULL, '2022-11-15 05:21:37', '2022-11-15 05:21:37'),
(14, 1, 1, 1, 1, 1, 1, 'Testing 17 Product Name', 'Testing Model', '127.0.0.1:8000/public/images/product_image/1668511303.jpg', 0, '276095475283833', '1', NULL, 5000, 5500, 'active', NULL, '2022-11-15 05:21:43', '2022-11-15 05:21:43');

-- --------------------------------------------------------

--
-- Table structure for table `product_order_bies`
--

CREATE TABLE `product_order_bies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `package_buy_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','deactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `view_id` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_order_bies`
--

INSERT INTO `product_order_bies` (`id`, `package_buy_id`, `name`, `status`, `view_id`, `created_at`, `updated_at`) VALUES
(1, 1, 'Testing One Product Order By', 'active', NULL, '2022-11-07 06:57:40', '2022-11-07 06:57:40');

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE `purchases` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `package_buy_id` bigint(20) UNSIGNED NOT NULL,
  `acc_cus_sup_id` bigint(20) UNSIGNED NOT NULL,
  `purchase_invoice_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_order_by_id` bigint(20) NOT NULL,
  `receipt_invoice_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `challan_invoice_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `purchase_date` date NOT NULL,
  `purchase_details` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_qty` bigint(20) NOT NULL,
  `total_purchase_amount` bigint(20) NOT NULL,
  `tax_amount` bigint(20) NOT NULL,
  `total_tax_amount` bigint(20) NOT NULL,
  `service_charge` bigint(20) NOT NULL,
  `shipping_cost` bigint(20) NOT NULL,
  `grand_total` bigint(20) NOT NULL,
  `paid_amount` bigint(20) NOT NULL,
  `due_amount` bigint(20) NOT NULL,
  `payment_method_id` bigint(20) UNSIGNED NOT NULL,
  `document` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchases`
--

INSERT INTO `purchases` (`id`, `package_buy_id`, `acc_cus_sup_id`, `purchase_invoice_no`, `product_order_by_id`, `receipt_invoice_no`, `challan_invoice_no`, `purchase_date`, `purchase_details`, `total_qty`, `total_purchase_amount`, `tax_amount`, `total_tax_amount`, `service_charge`, `shipping_cost`, `grand_total`, `paid_amount`, `due_amount`, `payment_method_id`, `document`, `created_at`, `updated_at`) VALUES
(17, 1, 1, '2211175357', 1, '2211175550', '2211175739', '2022-11-17', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s,', 18, 1750, 5, 88, 100, 80, 2018, 1700, 318, 1, '[\"127.0.0.1:8000\\/public\\/images\\/purchase\\/1046277721.jpg\",\"127.0.0.1:8000\\/public\\/images\\/purchase\\/955664954.jpg\"]', '2022-11-17 03:53:57', '2022-11-17 03:57:39');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_items`
--

CREATE TABLE `purchase_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `package_buy_id` bigint(20) UNSIGNED NOT NULL,
  `purchase_id` bigint(20) UNSIGNED NOT NULL,
  `unit_id` bigint(20) UNSIGNED NOT NULL,
  `purchase_invoice_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `avg_qty` bigint(20) NOT NULL,
  `bag` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `qty` bigint(20) NOT NULL,
  `rate` bigint(20) NOT NULL,
  `amount` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_items`
--

INSERT INTO `purchase_items` (`id`, `package_buy_id`, `purchase_id`, `unit_id`, `purchase_invoice_no`, `product_id`, `avg_qty`, `bag`, `qty`, `rate`, `amount`, `created_at`, `updated_at`) VALUES
(32, 1, 17, 1, '2211175357', 13, 1, '20', 10, 200, 1000, '2022-11-17 03:53:57', '2022-11-17 03:53:57'),
(33, 1, 17, 1, '2211175357', 14, 1, '15', 8, 250, 750, '2022-11-17 03:53:57', '2022-11-17 03:53:57');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_quotations`
--

CREATE TABLE `purchase_quotations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `package_buy_id` bigint(20) UNSIGNED NOT NULL,
  `acc_cus_sup_id` bigint(20) UNSIGNED NOT NULL,
  `quotation_invoice_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_order_by_id` bigint(20) NOT NULL,
  `quotation_date` date NOT NULL,
  `quotation_purchase_details` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_qty` bigint(20) NOT NULL,
  `total_purchase_amount` bigint(20) NOT NULL,
  `total_tax_amount` bigint(20) NOT NULL,
  `service_charge` bigint(20) NOT NULL,
  `shipping_cost` bigint(20) NOT NULL,
  `grand_total` bigint(20) NOT NULL,
  `paid_amount` bigint(20) NOT NULL,
  `due_amount` bigint(20) NOT NULL,
  `document` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_quotation_items`
--

CREATE TABLE `purchase_quotation_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `package_buy_id` bigint(20) UNSIGNED NOT NULL,
  `purchase_quotation_id` bigint(20) UNSIGNED NOT NULL,
  `unit_id` bigint(20) UNSIGNED NOT NULL,
  `quotation_invoice_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `avg_qty` bigint(20) NOT NULL,
  `bag` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `qty` bigint(20) NOT NULL,
  `rate` bigint(20) NOT NULL,
  `amount` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `receipts`
--

CREATE TABLE `receipts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `package_buy_id` bigint(20) UNSIGNED NOT NULL,
  `purchase_id` bigint(20) UNSIGNED NOT NULL,
  `receipt_invoice_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ware_house_id` bigint(20) UNSIGNED NOT NULL,
  `vehicale_id` bigint(20) UNSIGNED NOT NULL,
  `receipt_details` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_qty` bigint(20) NOT NULL,
  `total_receipt` bigint(20) NOT NULL,
  `total_pending` bigint(20) NOT NULL,
  `receipt_date` date NOT NULL,
  `status` enum('pending','accept') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `receipts`
--

INSERT INTO `receipts` (`id`, `package_buy_id`, `purchase_id`, `receipt_invoice_no`, `ware_house_id`, `vehicale_id`, `receipt_details`, `total_qty`, `total_receipt`, `total_pending`, `receipt_date`, `status`, `created_at`, `updated_at`) VALUES
(8, 1, 17, '2211175550', 2, 1, 'Testing receipt details', 18, 3, 15, '2022-11-17', 'pending', '2022-11-17 03:55:50', '2022-11-17 03:55:50');

-- --------------------------------------------------------

--
-- Table structure for table `receipt_challans`
--

CREATE TABLE `receipt_challans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `package_buy_id` bigint(20) UNSIGNED NOT NULL,
  `purchase_invoice_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `receipt_invoice_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `receipt_challan_invoice_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `vehicale_id` bigint(20) UNSIGNED NOT NULL,
  `challan_details` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_qty` bigint(20) NOT NULL,
  `total_receipt` bigint(20) NOT NULL,
  `total_pending` bigint(20) NOT NULL,
  `challan_date` date NOT NULL,
  `document` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','accept') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `receipt_challans`
--

INSERT INTO `receipt_challans` (`id`, `package_buy_id`, `purchase_invoice_no`, `receipt_invoice_no`, `receipt_challan_invoice_no`, `vehicale_id`, `challan_details`, `total_qty`, `total_receipt`, `total_pending`, `challan_date`, `document`, `status`, `created_at`, `updated_at`) VALUES
(15, 1, '2211175357', '2211175550', '2211175739', 1, 'Testing receipt details', 18, 3, 15, '2022-11-17', '127.0.0.1:8000/public/images/receipt_challan/1668679059.png', 'pending', '2022-11-17 03:57:39', '2022-11-17 03:57:39');

-- --------------------------------------------------------

--
-- Table structure for table `receipt_challan_items`
--

CREATE TABLE `receipt_challan_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `package_buy_id` bigint(20) UNSIGNED NOT NULL,
  `purchase_invoice_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `receipt_invoice_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `receipt_challan_id` bigint(20) UNSIGNED NOT NULL,
  `receipt_challan_invoice_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `receipt_item_id` bigint(20) UNSIGNED NOT NULL,
  `unit_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `avg_qty` bigint(20) NOT NULL,
  `bag` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `qty` bigint(20) NOT NULL,
  `rate` bigint(20) NOT NULL,
  `amount` bigint(20) NOT NULL,
  `order` bigint(20) NOT NULL,
  `receipt` bigint(20) NOT NULL,
  `pending` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `receipt_challan_items`
--

INSERT INTO `receipt_challan_items` (`id`, `package_buy_id`, `purchase_invoice_no`, `receipt_invoice_no`, `receipt_challan_id`, `receipt_challan_invoice_no`, `receipt_item_id`, `unit_id`, `product_id`, `avg_qty`, `bag`, `qty`, `rate`, `amount`, `order`, `receipt`, `pending`, `created_at`, `updated_at`) VALUES
(17, 1, '2211175357', '2211175550', 15, '2211175739', 15, 1, 13, 1, '20', 10, 200, 1000, 10, 1, 9, '2022-11-17 03:57:39', '2022-11-17 03:57:39'),
(18, 1, '2211175357', '2211175550', 15, '2211175739', 16, 1, 14, 1, '15', 8, 250, 750, 8, 2, 6, '2022-11-17 03:57:39', '2022-11-17 03:57:39');

-- --------------------------------------------------------

--
-- Table structure for table `receipt_histories`
--

CREATE TABLE `receipt_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `package_buy_id` bigint(20) UNSIGNED NOT NULL,
  `purchase_id` bigint(20) UNSIGNED NOT NULL,
  `receipt_id` bigint(20) UNSIGNED NOT NULL,
  `receipt_item_id` bigint(20) UNSIGNED NOT NULL,
  `purchase_item_id` bigint(20) UNSIGNED NOT NULL,
  `order` bigint(20) NOT NULL,
  `receipt` bigint(20) NOT NULL,
  `pending` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `receipt_histories`
--

INSERT INTO `receipt_histories` (`id`, `package_buy_id`, `purchase_id`, `receipt_id`, `receipt_item_id`, `purchase_item_id`, `order`, `receipt`, `pending`, `created_at`, `updated_at`) VALUES
(23, 1, 17, 8, 15, 32, 10, 1, 9, '2022-11-17 03:55:50', '2022-11-17 03:55:50'),
(24, 1, 17, 8, 16, 33, 8, 2, 6, '2022-11-17 03:55:50', '2022-11-17 03:55:50');

-- --------------------------------------------------------

--
-- Table structure for table `receipt_items`
--

CREATE TABLE `receipt_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `package_buy_id` bigint(20) UNSIGNED NOT NULL,
  `purchase_id` bigint(20) UNSIGNED NOT NULL,
  `receipt_id` bigint(20) UNSIGNED NOT NULL,
  `receipt_invoice_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `purchase_item_id` bigint(20) UNSIGNED NOT NULL,
  `order` bigint(20) NOT NULL,
  `receipt` bigint(20) NOT NULL,
  `pending` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `receipt_items`
--

INSERT INTO `receipt_items` (`id`, `package_buy_id`, `purchase_id`, `receipt_id`, `receipt_invoice_no`, `purchase_item_id`, `order`, `receipt`, `pending`, `created_at`, `updated_at`) VALUES
(15, 1, 17, 8, '2211175550', 32, 10, 1, 9, '2022-11-17 03:55:50', '2022-11-17 03:55:50'),
(16, 1, 17, 8, '2211175550', 33, 8, 2, 6, '2022-11-17 03:55:50', '2022-11-17 03:55:50');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `package_buy_id` bigint(20) UNSIGNED NOT NULL,
  `acc_cus_sup_id` bigint(20) UNSIGNED NOT NULL,
  `sale_invoice_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `delivery_invoice_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `challan_invoice_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_order_by_id` bigint(20) NOT NULL,
  `sale_date` date NOT NULL,
  `sale_details` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_qty` bigint(20) NOT NULL,
  `total_sale_amount` bigint(20) NOT NULL,
  `tax_amount` bigint(20) NOT NULL,
  `total_tax_amount` bigint(20) NOT NULL,
  `service_charge` bigint(20) NOT NULL,
  `shipping_cost` bigint(20) NOT NULL,
  `grand_total` bigint(20) NOT NULL,
  `paid_amount` bigint(20) NOT NULL,
  `due_amount` bigint(20) NOT NULL,
  `payment_method_id` bigint(20) UNSIGNED NOT NULL,
  `document` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `package_buy_id`, `acc_cus_sup_id`, `sale_invoice_no`, `delivery_invoice_no`, `challan_invoice_no`, `product_order_by_id`, `sale_date`, `sale_details`, `total_qty`, `total_sale_amount`, `tax_amount`, `total_tax_amount`, `service_charge`, `shipping_cost`, `grand_total`, `paid_amount`, `due_amount`, `payment_method_id`, `document`, `created_at`, `updated_at`) VALUES
(9, 1, 1, '2211171125', '2211172527', '2211173545', 1, '2022-11-17', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s,', 5, 1750, 5, 88, 100, 80, 2018, 1700, 318, 1, '[\"127.0.0.1:8000\\/public\\/images\\/sale\\/1894859063.jpg\",\"127.0.0.1:8000\\/public\\/images\\/sale\\/211290171.jpg\"]', '2022-11-17 04:11:25', '2022-11-17 04:35:45');

-- --------------------------------------------------------

--
-- Table structure for table `sale_challans`
--

CREATE TABLE `sale_challans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `package_buy_id` bigint(20) UNSIGNED NOT NULL,
  `sale_id` bigint(20) UNSIGNED NOT NULL,
  `sale_challan_invoice_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `vehicale_id` bigint(20) UNSIGNED NOT NULL,
  `challan_details` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `challan_date` date NOT NULL,
  `document` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','accept') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_challan_items`
--

CREATE TABLE `sale_challan_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `package_buy_id` bigint(20) UNSIGNED NOT NULL,
  `sale_id` bigint(20) UNSIGNED NOT NULL,
  `sale_challan_id` bigint(20) UNSIGNED NOT NULL,
  `sale_item_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_items`
--

CREATE TABLE `sale_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `package_buy_id` bigint(20) UNSIGNED NOT NULL,
  `sale_id` bigint(20) UNSIGNED NOT NULL,
  `unit_id` bigint(20) UNSIGNED NOT NULL,
  `sale_invoice_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `avg_qty` bigint(20) NOT NULL,
  `bag` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `qty` bigint(20) NOT NULL,
  `rate` bigint(20) NOT NULL,
  `amount` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sale_items`
--

INSERT INTO `sale_items` (`id`, `package_buy_id`, `sale_id`, `unit_id`, `sale_invoice_no`, `product_id`, `avg_qty`, `bag`, `qty`, `rate`, `amount`, `created_at`, `updated_at`) VALUES
(13, 1, 9, 1, '2211171125', 13, 1, '20', 3, 200, 1000, '2022-11-17 04:11:25', '2022-11-17 04:11:25'),
(14, 1, 9, 1, '2211171125', 14, 1, '15', 2, 250, 750, '2022-11-17 04:11:25', '2022-11-17 04:11:25');

-- --------------------------------------------------------

--
-- Table structure for table `sale_quotations`
--

CREATE TABLE `sale_quotations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `package_buy_id` bigint(20) UNSIGNED NOT NULL,
  `acc_cus_sup_id` bigint(20) UNSIGNED NOT NULL,
  `quotation_invoice_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_order_by_id` bigint(20) NOT NULL,
  `quotation_date` date NOT NULL,
  `quotation_sale_details` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_qty` bigint(20) NOT NULL,
  `total_sale_amount` bigint(20) NOT NULL,
  `total_tax_amount` bigint(20) NOT NULL,
  `service_charge` bigint(20) NOT NULL,
  `shipping_cost` bigint(20) NOT NULL,
  `grand_total` bigint(20) NOT NULL,
  `paid_amount` bigint(20) NOT NULL,
  `due_amount` bigint(20) NOT NULL,
  `document` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_quotation_items`
--

CREATE TABLE `sale_quotation_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `package_buy_id` bigint(20) UNSIGNED NOT NULL,
  `sale_quotation_id` bigint(20) UNSIGNED NOT NULL,
  `unit_id` bigint(20) UNSIGNED NOT NULL,
  `quotation_invoice_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `avg_qty` bigint(20) NOT NULL,
  `bag` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `qty` bigint(20) NOT NULL,
  `rate` bigint(20) NOT NULL,
  `amount` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stocks`
--

CREATE TABLE `stocks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `package_buy_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `warehouse_id` bigint(20) UNSIGNED DEFAULT NULL,
  `stock_now` bigint(20) DEFAULT NULL,
  `stock_old` bigint(20) DEFAULT NULL,
  `sale_price` bigint(20) DEFAULT NULL,
  `purchase_price` bigint(20) DEFAULT NULL,
  `production_price` bigint(20) DEFAULT NULL,
  `production_qty` bigint(20) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stocks`
--

INSERT INTO `stocks` (`id`, `package_buy_id`, `product_id`, `warehouse_id`, `stock_now`, `stock_old`, `sale_price`, `purchase_price`, `production_price`, `production_qty`, `date`, `created_at`, `updated_at`) VALUES
(1, 1, 13, 1, 10, 12, 5500, 5000, NULL, NULL, '2022-11-17', '2022-11-17 03:57:39', '2022-11-17 05:39:18'),
(2, 1, 14, 1, 9, 11, 5500, 5000, NULL, NULL, '2022-11-17', '2022-11-17 03:57:39', '2022-11-17 05:39:18'),
(3, 1, 13, 2, 5, 3, 5500, 5000, NULL, NULL, '2022-11-17', '2022-11-17 03:57:39', '2022-11-17 05:39:18'),
(4, 1, 14, 2, 4, 2, 5500, 5000, NULL, NULL, '2022-11-17', '2022-11-17 03:57:39', '2022-11-17 05:39:18');

-- --------------------------------------------------------

--
-- Table structure for table `stock_histories`
--

CREATE TABLE `stock_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `package_buy_id` bigint(20) UNSIGNED NOT NULL,
  `stock_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `stock` bigint(20) NOT NULL,
  `stock_type` enum('in','out','transfer') COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock_histories`
--

INSERT INTO `stock_histories` (`id`, `package_buy_id`, `stock_id`, `product_id`, `stock`, `stock_type`, `date`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 13, 2, 'transfer', '2022-11-17', '2022-11-17 05:39:18', '2022-11-17 05:39:18'),
(2, 1, 1, 14, 2, 'transfer', '2022-11-17', '2022-11-17 05:39:18', '2022-11-17 05:39:18');

-- --------------------------------------------------------

--
-- Table structure for table `stock_transfers`
--

CREATE TABLE `stock_transfers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `package_buy_id` bigint(20) UNSIGNED NOT NULL,
  `from_warehouse_id` bigint(20) UNSIGNED NOT NULL,
  `to_warehouse_id` bigint(20) UNSIGNED NOT NULL,
  `total_item` bigint(20) NOT NULL,
  `date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock_transfers`
--

INSERT INTO `stock_transfers` (`id`, `package_buy_id`, `from_warehouse_id`, `to_warehouse_id`, `total_item`, `date`, `created_at`, `updated_at`) VALUES
(4, 1, 1, 2, 2, '2022-11-17', '2022-11-17 05:39:18', '2022-11-17 05:39:18');

-- --------------------------------------------------------

--
-- Table structure for table `stock_transfer_items`
--

CREATE TABLE `stock_transfer_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `package_buy_id` bigint(20) UNSIGNED NOT NULL,
  `stock_transfer_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `qty` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock_transfer_items`
--

INSERT INTO `stock_transfer_items` (`id`, `package_buy_id`, `stock_transfer_id`, `product_id`, `qty`, `created_at`, `updated_at`) VALUES
(3, 1, 4, 13, 2, '2022-11-17 05:39:18', '2022-11-17 05:39:18'),
(4, 1, 4, 14, 2, '2022-11-17 05:39:18', '2022-11-17 05:39:18');

-- --------------------------------------------------------

--
-- Table structure for table `thanas`
--

CREATE TABLE `thanas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `package_buy_id` bigint(20) UNSIGNED NOT NULL,
  `city_id` bigint(20) UNSIGNED NOT NULL,
  `district_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','deactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `view_id` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `thanas`
--

INSERT INTO `thanas` (`id`, `package_buy_id`, `city_id`, `district_id`, `name`, `status`, `view_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 'Testing Thana', 'active', NULL, '2022-11-07 06:58:29', '2022-11-07 06:58:29');

-- --------------------------------------------------------

--
-- Table structure for table `unions`
--

CREATE TABLE `unions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `package_buy_id` bigint(20) UNSIGNED NOT NULL,
  `city_id` bigint(20) UNSIGNED NOT NULL,
  `district_id` bigint(20) UNSIGNED NOT NULL,
  `thana_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','deactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `view_id` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `unions`
--

INSERT INTO `unions` (`id`, `package_buy_id`, `city_id`, `district_id`, `thana_id`, `name`, `status`, `view_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 1, 'Testing Union', 'active', NULL, '2022-11-07 06:58:36', '2022-11-07 06:58:36');

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE `units` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `package_buy_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','deactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `view_id` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `units`
--

INSERT INTO `units` (`id`, `package_buy_id`, `name`, `status`, `view_id`, `created_at`, `updated_at`) VALUES
(1, 1, 'Testing Unit Name', 'active', NULL, '2022-11-07 06:57:29', '2022-11-07 06:57:29');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `package_buy_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_as` enum('admin','subadmin','moderator') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'moderator',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rememberToken` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `package_buy_id`, `name`, `email`, `role_as`, `email_verified_at`, `password`, `rememberToken`, `created_at`, `updated_at`) VALUES
(1, 1, 'Testing', 'testingcompany@gmail.com', 'admin', NULL, '$2y$10$bcwocPqfsSWNiGpVKK.9Lev.yVjyuDChoHgRF9cqpKVHzkShNyiI6', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiYjcwM2Q5NzUxMzY3YzdjNzhjNDQ4ZDNjZjQyNGQ3ZTlmOGU2NWFlYjNlZTQwOWM0YTUzOTlhODVlYWQ2OWY5YzZjYzc0ZTI3ZWYzODY0NDMiLCJpYXQiOjE2Njg4MzI0MTYuNzAyMjg2LCJuYmYiOjE2Njg4MzI0MTYuNzAyMjkzLCJleHAiOjE3MDAzNjg0MTYuNTYwNjU5LCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.x8Bz6RkKkXcvhiJrsIFrQT-NZXvfqNSS6ONqZxn6cqIM6dZGR4OgsVfm33I_i3IRBbg70iLcJm1MDfzIHfQupEUnp1oqKfF_cqggH17FDojrTUjeLjc2vr4a9yppZ9BzvvE700UID2GBaBHCjczhcbBaiKN2fc8ubxchdaPmaZwMP-RrE5ga8lQgYaeATsUltr4D2ALA_BhWH9DEdiT1-YOm3cSddJMYVT9qhYDrh1mvUAWHr-NH9MMw6riBg5q8D4sHbUyy8-Xa6yjgYaCiqFrAQyvUVtFLmiR54hM49BM0kh3hEbZ_QBwZdoCAJOhcmNNONOz2VcbKiC8PtLcNXyEOvq4nrQbWxRQibcJhgkgLeG7ObyFSGugbPITdym12tCUIaUHMK-8cuv9lCaMxuUetWaKXB5G9G8eJ1rnUgMI1qz4UOFTGzA_b2WSkzxDvI1Gpqr0TC7Jhs8s0eKBbQo6_eQxICjx_WuEZWHtxUWxXA7Fy0i9nfXMA0lirPn6u3JfbrqSL--YivoBOIZPMrKDxE100IRKdb0Z-ZQxItzptUHOa6zWal9UJtaQv5eErNVQnpzf5KtUIc8zPqvzx01lZjl-rZTRgY7-QQU7P_lkCC4uuokLG8F5UNUJvWI8ti4wk_slltPKsGTA6vOV4pnm_G7QHKdVMheTjxzLJlLc', '2022-11-07 06:55:55', '2022-11-18 22:33:36');

-- --------------------------------------------------------

--
-- Table structure for table `vehicales`
--

CREATE TABLE `vehicales` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `package_buy_id` bigint(20) UNSIGNED NOT NULL,
  `city_id` bigint(20) UNSIGNED NOT NULL,
  `district_id` bigint(20) UNSIGNED NOT NULL,
  `thana_id` bigint(20) UNSIGNED NOT NULL,
  `union_id` bigint(20) UNSIGNED NOT NULL,
  `vehicle_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `vehicle_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `vehicle_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `vehicle_reg_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `father_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner_phone` bigint(20) NOT NULL,
  `owner_post_office` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner_village` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','deactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vehicales`
--

INSERT INTO `vehicales` (`id`, `package_buy_id`, `city_id`, `district_id`, `thana_id`, `union_id`, `vehicle_name`, `vehicle_type`, `vehicle_no`, `vehicle_reg_no`, `owner_name`, `father_name`, `owner_phone`, `owner_post_office`, `owner_village`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 1, 1, 'Testing Vehicale Name', 'Cargu', '648412', 'UIU648EEKkk', 'Testing Owner name', 'Testing Father Name', 1700000000, '12015', 'Testing Village', 'active', '2022-11-07 06:59:00', '2022-11-07 06:59:00');

-- --------------------------------------------------------

--
-- Table structure for table `vehicale_types`
--

CREATE TABLE `vehicale_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `package_buy_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','deactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `view_id` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vehicale_types`
--

INSERT INTO `vehicale_types` (`id`, `package_buy_id`, `name`, `status`, `view_id`, `created_at`, `updated_at`) VALUES
(1, 1, 'Testing One Cash Counter', 'active', NULL, '2022-11-07 06:57:12', '2022-11-07 06:57:12');

-- --------------------------------------------------------

--
-- Table structure for table `ware_houses`
--

CREATE TABLE `ware_houses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `package_buy_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','deactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `view_id` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ware_houses`
--

INSERT INTO `ware_houses` (`id`, `package_buy_id`, `name`, `status`, `view_id`, `created_at`, `updated_at`) VALUES
(1, 1, 'Testing WareHouse', 'active', NULL, '2022-11-07 06:57:58', '2022-11-07 06:57:58'),
(2, 1, 'Testing WareHouse', 'active', NULL, '2022-11-15 04:13:16', '2022-11-15 04:13:16');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `acc_areas`
--
ALTER TABLE `acc_areas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `acc_areas_package_buy_id_foreign` (`package_buy_id`);

--
-- Indexes for table `acc_categories`
--
ALTER TABLE `acc_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `acc_categories_package_buy_id_foreign` (`package_buy_id`);

--
-- Indexes for table `acc_customer_suppliers`
--
ALTER TABLE `acc_customer_suppliers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `acc_customer_suppliers_acc_no_unique` (`acc_no`),
  ADD KEY `acc_customer_suppliers_package_buy_id_foreign` (`package_buy_id`);

--
-- Indexes for table `acc_types`
--
ALTER TABLE `acc_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `acc_types_package_buy_id_foreign` (`package_buy_id`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admins_email_unique` (`email`),
  ADD UNIQUE KEY `admins_phone_unique` (`phone`);

--
-- Indexes for table `banks`
--
ALTER TABLE `banks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `banks_package_buy_id_foreign` (`package_buy_id`);

--
-- Indexes for table `bank_acc_categories`
--
ALTER TABLE `bank_acc_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bank_acc_categories_package_buy_id_foreign` (`package_buy_id`);

--
-- Indexes for table `bank_acc_types`
--
ALTER TABLE `bank_acc_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bank_acc_types_package_buy_id_foreign` (`package_buy_id`);

--
-- Indexes for table `bank_branches`
--
ALTER TABLE `bank_branches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bank_branches_package_buy_id_foreign` (`package_buy_id`),
  ADD KEY `bank_branches_bank_id_foreign` (`bank_id`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`),
  ADD KEY `brands_package_buy_id_foreign` (`package_buy_id`);

--
-- Indexes for table `cash_counters`
--
ALTER TABLE `cash_counters`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cash_counters_package_buy_id_foreign` (`package_buy_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categories_package_buy_id_foreign` (`package_buy_id`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cities_package_buy_id_foreign` (`package_buy_id`);

--
-- Indexes for table `deliveries`
--
ALTER TABLE `deliveries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `deliveries_package_buy_id_foreign` (`package_buy_id`),
  ADD KEY `deliveries_sale_id_foreign` (`sale_id`),
  ADD KEY `deliveries_ware_house_id_foreign` (`ware_house_id`),
  ADD KEY `deliveries_vehicale_id_foreign` (`vehicale_id`);

--
-- Indexes for table `delivery_challans`
--
ALTER TABLE `delivery_challans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `delivery_challans_package_buy_id_foreign` (`package_buy_id`),
  ADD KEY `delivery_challans_vehicale_id_foreign` (`vehicale_id`);

--
-- Indexes for table `delivery_challan_items`
--
ALTER TABLE `delivery_challan_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `delivery_challan_items_package_buy_id_foreign` (`package_buy_id`),
  ADD KEY `delivery_challan_items_delivery_challan_id_foreign` (`delivery_challan_id`),
  ADD KEY `delivery_challan_items_delivery_item_id_foreign` (`delivery_item_id`),
  ADD KEY `delivery_challan_items_product_id_foreign` (`product_id`),
  ADD KEY `delivery_challan_items_unit_id_foreign` (`unit_id`);

--
-- Indexes for table `delivery_histories`
--
ALTER TABLE `delivery_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `delivery_histories_package_buy_id_foreign` (`package_buy_id`),
  ADD KEY `delivery_histories_sale_id_foreign` (`sale_id`),
  ADD KEY `delivery_histories_delivery_id_foreign` (`delivery_id`),
  ADD KEY `delivery_histories_delivery_item_id_foreign` (`delivery_item_id`),
  ADD KEY `delivery_histories_sale_item_id_foreign` (`sale_item_id`);

--
-- Indexes for table `delivery_items`
--
ALTER TABLE `delivery_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `delivery_items_package_buy_id_foreign` (`package_buy_id`),
  ADD KEY `delivery_items_sale_id_foreign` (`sale_id`),
  ADD KEY `delivery_items_delivery_id_foreign` (`delivery_id`),
  ADD KEY `delivery_items_sale_item_id_foreign` (`sale_item_id`);

--
-- Indexes for table `designations`
--
ALTER TABLE `designations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `designations_package_buy_id_foreign` (`package_buy_id`);

--
-- Indexes for table `districts`
--
ALTER TABLE `districts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `districts_package_buy_id_foreign` (`package_buy_id`),
  ADD KEY `districts_city_id_foreign` (`city_id`);

--
-- Indexes for table `drivers`
--
ALTER TABLE `drivers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `drivers_driver_phone_unique` (`driver_phone`),
  ADD KEY `drivers_package_buy_id_foreign` (`package_buy_id`),
  ADD KEY `drivers_vehicle_id_foreign` (`vehicle_id`),
  ADD KEY `drivers_city_id_foreign` (`city_id`),
  ADD KEY `drivers_district_id_foreign` (`district_id`),
  ADD KEY `drivers_thana_id_foreign` (`thana_id`),
  ADD KEY `drivers_union_id_foreign` (`union_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `income_expense_acc_types`
--
ALTER TABLE `income_expense_acc_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `income_expense_acc_types_package_buy_id_foreign` (`package_buy_id`);

--
-- Indexes for table `income_expense_payment_method_types`
--
ALTER TABLE `income_expense_payment_method_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `income_expense_payment_method_types_package_buy_id_foreign` (`package_buy_id`);

--
-- Indexes for table `lot_gallaries`
--
ALTER TABLE `lot_gallaries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lot_gallaries_package_buy_id_foreign` (`package_buy_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_access_tokens`
--
ALTER TABLE `oauth_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_access_tokens_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_auth_codes`
--
ALTER TABLE `oauth_auth_codes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_auth_codes_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_clients_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_refresh_tokens`
--
ALTER TABLE `oauth_refresh_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`);

--
-- Indexes for table `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `package_buys`
--
ALTER TABLE `package_buys`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `package_buys_email_unique` (`email`),
  ADD UNIQUE KEY `package_buys_company_name_unique` (`company_name`),
  ADD KEY `package_buys_package_id_foreign` (`package_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payment_methods_package_buy_id_foreign` (`package_buy_id`);

--
-- Indexes for table `production_types`
--
ALTER TABLE `production_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `production_types_package_buy_id_foreign` (`package_buy_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_package_buy_id_foreign` (`package_buy_id`),
  ADD KEY `products_acc_cus_sup_id_foreign` (`acc_cus_sup_id`),
  ADD KEY `products_brand_id_foreign` (`brand_id`),
  ADD KEY `products_category_id_foreign` (`category_id`),
  ADD KEY `products_unit_id_foreign` (`unit_id`),
  ADD KEY `products_lot_gallary_id_foreign` (`lot_gallary_id`);

--
-- Indexes for table `product_order_bies`
--
ALTER TABLE `product_order_bies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_order_bies_package_buy_id_foreign` (`package_buy_id`);

--
-- Indexes for table `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchases_package_buy_id_foreign` (`package_buy_id`),
  ADD KEY `purchases_acc_cus_sup_id_foreign` (`acc_cus_sup_id`),
  ADD KEY `purchases_payment_method_id_foreign` (`payment_method_id`);

--
-- Indexes for table `purchase_items`
--
ALTER TABLE `purchase_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_items_package_buy_id_foreign` (`package_buy_id`),
  ADD KEY `purchase_items_purchase_id_foreign` (`purchase_id`),
  ADD KEY `purchase_items_product_id_foreign` (`product_id`),
  ADD KEY `purchase_items_unit_id_foreign` (`unit_id`);

--
-- Indexes for table `purchase_quotations`
--
ALTER TABLE `purchase_quotations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_quotations_package_buy_id_foreign` (`package_buy_id`),
  ADD KEY `purchase_quotations_acc_cus_sup_id_foreign` (`acc_cus_sup_id`);

--
-- Indexes for table `purchase_quotation_items`
--
ALTER TABLE `purchase_quotation_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_quotation_items_package_buy_id_foreign` (`package_buy_id`),
  ADD KEY `purchase_quotation_items_purchase_quotation_id_foreign` (`purchase_quotation_id`),
  ADD KEY `purchase_quotation_items_product_id_foreign` (`product_id`),
  ADD KEY `purchase_quotation_items_unit_id_foreign` (`unit_id`);

--
-- Indexes for table `receipts`
--
ALTER TABLE `receipts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `receipts_package_buy_id_foreign` (`package_buy_id`),
  ADD KEY `receipts_purchase_id_foreign` (`purchase_id`),
  ADD KEY `receipts_ware_house_id_foreign` (`ware_house_id`),
  ADD KEY `receipts_vehicale_id_foreign` (`vehicale_id`);

--
-- Indexes for table `receipt_challans`
--
ALTER TABLE `receipt_challans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `receipt_challans_package_buy_id_foreign` (`package_buy_id`),
  ADD KEY `receipt_challans_vehicale_id_foreign` (`vehicale_id`);

--
-- Indexes for table `receipt_challan_items`
--
ALTER TABLE `receipt_challan_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `receipt_challan_items_package_buy_id_foreign` (`package_buy_id`),
  ADD KEY `receipt_challan_items_receipt_challan_id_foreign` (`receipt_challan_id`),
  ADD KEY `receipt_challan_items_receipt_item_id_foreign` (`receipt_item_id`),
  ADD KEY `receipt_challan_items_product_id_foreign` (`product_id`),
  ADD KEY `receipt_challan_items_unit_id_foreign` (`unit_id`);

--
-- Indexes for table `receipt_histories`
--
ALTER TABLE `receipt_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `receipt_histories_package_buy_id_foreign` (`package_buy_id`),
  ADD KEY `receipt_histories_purchase_id_foreign` (`purchase_id`),
  ADD KEY `receipt_histories_receipt_id_foreign` (`receipt_id`),
  ADD KEY `receipt_histories_receipt_item_id_foreign` (`receipt_item_id`),
  ADD KEY `receipt_histories_purchase_item_id_foreign` (`purchase_item_id`);

--
-- Indexes for table `receipt_items`
--
ALTER TABLE `receipt_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `receipt_items_package_buy_id_foreign` (`package_buy_id`),
  ADD KEY `receipt_items_purchase_id_foreign` (`purchase_id`),
  ADD KEY `receipt_items_receipt_id_foreign` (`receipt_id`),
  ADD KEY `receipt_items_purchase_item_id_foreign` (`purchase_item_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sales_package_buy_id_foreign` (`package_buy_id`),
  ADD KEY `sales_acc_cus_sup_id_foreign` (`acc_cus_sup_id`),
  ADD KEY `sales_payment_method_id_foreign` (`payment_method_id`);

--
-- Indexes for table `sale_challans`
--
ALTER TABLE `sale_challans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_challans_package_buy_id_foreign` (`package_buy_id`),
  ADD KEY `sale_challans_sale_id_foreign` (`sale_id`),
  ADD KEY `sale_challans_vehicale_id_foreign` (`vehicale_id`);

--
-- Indexes for table `sale_challan_items`
--
ALTER TABLE `sale_challan_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_challan_items_package_buy_id_foreign` (`package_buy_id`),
  ADD KEY `sale_challan_items_sale_id_foreign` (`sale_id`),
  ADD KEY `sale_challan_items_sale_challan_id_foreign` (`sale_challan_id`),
  ADD KEY `sale_challan_items_sale_item_id_foreign` (`sale_item_id`);

--
-- Indexes for table `sale_items`
--
ALTER TABLE `sale_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_items_package_buy_id_foreign` (`package_buy_id`),
  ADD KEY `sale_items_sale_id_foreign` (`sale_id`),
  ADD KEY `sale_items_product_id_foreign` (`product_id`),
  ADD KEY `sale_items_unit_id_foreign` (`unit_id`);

--
-- Indexes for table `sale_quotations`
--
ALTER TABLE `sale_quotations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_quotations_package_buy_id_foreign` (`package_buy_id`),
  ADD KEY `sale_quotations_acc_cus_sup_id_foreign` (`acc_cus_sup_id`);

--
-- Indexes for table `sale_quotation_items`
--
ALTER TABLE `sale_quotation_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_quotation_items_package_buy_id_foreign` (`package_buy_id`),
  ADD KEY `sale_quotation_items_sale_quotation_id_foreign` (`sale_quotation_id`),
  ADD KEY `sale_quotation_items_product_id_foreign` (`product_id`),
  ADD KEY `sale_quotation_items_unit_id_foreign` (`unit_id`);

--
-- Indexes for table `stocks`
--
ALTER TABLE `stocks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stocks_package_buy_id_foreign` (`package_buy_id`),
  ADD KEY `stocks_product_id_foreign` (`product_id`),
  ADD KEY `stocks_warehouse_id_foreign` (`warehouse_id`);

--
-- Indexes for table `stock_histories`
--
ALTER TABLE `stock_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_histories_package_buy_id_foreign` (`package_buy_id`),
  ADD KEY `stock_histories_stock_id_foreign` (`stock_id`),
  ADD KEY `stock_histories_product_id_foreign` (`product_id`);

--
-- Indexes for table `stock_transfers`
--
ALTER TABLE `stock_transfers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_transfers_package_buy_id_foreign` (`package_buy_id`);

--
-- Indexes for table `stock_transfer_items`
--
ALTER TABLE `stock_transfer_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_transfer_items_package_buy_id_foreign` (`package_buy_id`),
  ADD KEY `stock_transfer_items_stock_transfer_id_foreign` (`stock_transfer_id`),
  ADD KEY `stock_transfer_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `thanas`
--
ALTER TABLE `thanas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `thanas_package_buy_id_foreign` (`package_buy_id`),
  ADD KEY `thanas_city_id_foreign` (`city_id`),
  ADD KEY `thanas_district_id_foreign` (`district_id`);

--
-- Indexes for table `unions`
--
ALTER TABLE `unions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `unions_package_buy_id_foreign` (`package_buy_id`),
  ADD KEY `unions_city_id_foreign` (`city_id`),
  ADD KEY `unions_district_id_foreign` (`district_id`),
  ADD KEY `unions_thana_id_foreign` (`thana_id`);

--
-- Indexes for table `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`id`),
  ADD KEY `units_package_buy_id_foreign` (`package_buy_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_package_buy_id_foreign` (`package_buy_id`);

--
-- Indexes for table `vehicales`
--
ALTER TABLE `vehicales`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vehicales_vehicle_no_unique` (`vehicle_no`),
  ADD UNIQUE KEY `vehicales_vehicle_reg_no_unique` (`vehicle_reg_no`),
  ADD UNIQUE KEY `vehicales_owner_phone_unique` (`owner_phone`),
  ADD KEY `vehicales_package_buy_id_foreign` (`package_buy_id`),
  ADD KEY `vehicales_city_id_foreign` (`city_id`),
  ADD KEY `vehicales_district_id_foreign` (`district_id`),
  ADD KEY `vehicales_thana_id_foreign` (`thana_id`),
  ADD KEY `vehicales_union_id_foreign` (`union_id`);

--
-- Indexes for table `vehicale_types`
--
ALTER TABLE `vehicale_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vehicale_types_package_buy_id_foreign` (`package_buy_id`);

--
-- Indexes for table `ware_houses`
--
ALTER TABLE `ware_houses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ware_houses_package_buy_id_foreign` (`package_buy_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `acc_areas`
--
ALTER TABLE `acc_areas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `acc_categories`
--
ALTER TABLE `acc_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `acc_customer_suppliers`
--
ALTER TABLE `acc_customer_suppliers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `acc_types`
--
ALTER TABLE `acc_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `banks`
--
ALTER TABLE `banks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bank_acc_categories`
--
ALTER TABLE `bank_acc_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bank_acc_types`
--
ALTER TABLE `bank_acc_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bank_branches`
--
ALTER TABLE `bank_branches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cash_counters`
--
ALTER TABLE `cash_counters`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `deliveries`
--
ALTER TABLE `deliveries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `delivery_challans`
--
ALTER TABLE `delivery_challans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `delivery_challan_items`
--
ALTER TABLE `delivery_challan_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `delivery_histories`
--
ALTER TABLE `delivery_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `delivery_items`
--
ALTER TABLE `delivery_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `designations`
--
ALTER TABLE `designations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `districts`
--
ALTER TABLE `districts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `drivers`
--
ALTER TABLE `drivers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `income_expense_acc_types`
--
ALTER TABLE `income_expense_acc_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `income_expense_payment_method_types`
--
ALTER TABLE `income_expense_payment_method_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lot_gallaries`
--
ALTER TABLE `lot_gallaries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `package_buys`
--
ALTER TABLE `package_buys`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `production_types`
--
ALTER TABLE `production_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `product_order_bies`
--
ALTER TABLE `product_order_bies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `purchase_items`
--
ALTER TABLE `purchase_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `purchase_quotations`
--
ALTER TABLE `purchase_quotations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_quotation_items`
--
ALTER TABLE `purchase_quotation_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `receipts`
--
ALTER TABLE `receipts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `receipt_challans`
--
ALTER TABLE `receipt_challans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `receipt_challan_items`
--
ALTER TABLE `receipt_challan_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `receipt_histories`
--
ALTER TABLE `receipt_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `receipt_items`
--
ALTER TABLE `receipt_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `sale_challans`
--
ALTER TABLE `sale_challans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_challan_items`
--
ALTER TABLE `sale_challan_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_items`
--
ALTER TABLE `sale_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `sale_quotations`
--
ALTER TABLE `sale_quotations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_quotation_items`
--
ALTER TABLE `sale_quotation_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stocks`
--
ALTER TABLE `stocks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `stock_histories`
--
ALTER TABLE `stock_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `stock_transfers`
--
ALTER TABLE `stock_transfers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `stock_transfer_items`
--
ALTER TABLE `stock_transfer_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `thanas`
--
ALTER TABLE `thanas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `unions`
--
ALTER TABLE `unions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `vehicales`
--
ALTER TABLE `vehicales`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `vehicale_types`
--
ALTER TABLE `vehicale_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ware_houses`
--
ALTER TABLE `ware_houses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `acc_areas`
--
ALTER TABLE `acc_areas`
  ADD CONSTRAINT `acc_areas_package_buy_id_foreign` FOREIGN KEY (`package_buy_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `acc_categories`
--
ALTER TABLE `acc_categories`
  ADD CONSTRAINT `acc_categories_package_buy_id_foreign` FOREIGN KEY (`package_buy_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `acc_customer_suppliers`
--
ALTER TABLE `acc_customer_suppliers`
  ADD CONSTRAINT `acc_customer_suppliers_package_buy_id_foreign` FOREIGN KEY (`package_buy_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `acc_types`
--
ALTER TABLE `acc_types`
  ADD CONSTRAINT `acc_types_package_buy_id_foreign` FOREIGN KEY (`package_buy_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `banks`
--
ALTER TABLE `banks`
  ADD CONSTRAINT `banks_package_buy_id_foreign` FOREIGN KEY (`package_buy_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `bank_acc_categories`
--
ALTER TABLE `bank_acc_categories`
  ADD CONSTRAINT `bank_acc_categories_package_buy_id_foreign` FOREIGN KEY (`package_buy_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `bank_acc_types`
--
ALTER TABLE `bank_acc_types`
  ADD CONSTRAINT `bank_acc_types_package_buy_id_foreign` FOREIGN KEY (`package_buy_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `bank_branches`
--
ALTER TABLE `bank_branches`
  ADD CONSTRAINT `bank_branches_bank_id_foreign` FOREIGN KEY (`bank_id`) REFERENCES `banks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `bank_branches_package_buy_id_foreign` FOREIGN KEY (`package_buy_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `brands`
--
ALTER TABLE `brands`
  ADD CONSTRAINT `brands_package_buy_id_foreign` FOREIGN KEY (`package_buy_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cash_counters`
--
ALTER TABLE `cash_counters`
  ADD CONSTRAINT `cash_counters_package_buy_id_foreign` FOREIGN KEY (`package_buy_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_package_buy_id_foreign` FOREIGN KEY (`package_buy_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cities`
--
ALTER TABLE `cities`
  ADD CONSTRAINT `cities_package_buy_id_foreign` FOREIGN KEY (`package_buy_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `deliveries`
--
ALTER TABLE `deliveries`
  ADD CONSTRAINT `deliveries_package_buy_id_foreign` FOREIGN KEY (`package_buy_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `deliveries_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `deliveries_vehicale_id_foreign` FOREIGN KEY (`vehicale_id`) REFERENCES `vehicales` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `deliveries_ware_house_id_foreign` FOREIGN KEY (`ware_house_id`) REFERENCES `ware_houses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `delivery_challans`
--
ALTER TABLE `delivery_challans`
  ADD CONSTRAINT `delivery_challans_package_buy_id_foreign` FOREIGN KEY (`package_buy_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `delivery_challans_vehicale_id_foreign` FOREIGN KEY (`vehicale_id`) REFERENCES `vehicales` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `delivery_challan_items`
--
ALTER TABLE `delivery_challan_items`
  ADD CONSTRAINT `delivery_challan_items_delivery_challan_id_foreign` FOREIGN KEY (`delivery_challan_id`) REFERENCES `delivery_challans` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `delivery_challan_items_delivery_item_id_foreign` FOREIGN KEY (`delivery_item_id`) REFERENCES `delivery_items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `delivery_challan_items_package_buy_id_foreign` FOREIGN KEY (`package_buy_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `delivery_challan_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `delivery_challan_items_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `delivery_histories`
--
ALTER TABLE `delivery_histories`
  ADD CONSTRAINT `delivery_histories_delivery_id_foreign` FOREIGN KEY (`delivery_id`) REFERENCES `deliveries` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `delivery_histories_delivery_item_id_foreign` FOREIGN KEY (`delivery_item_id`) REFERENCES `delivery_items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `delivery_histories_package_buy_id_foreign` FOREIGN KEY (`package_buy_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `delivery_histories_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `delivery_histories_sale_item_id_foreign` FOREIGN KEY (`sale_item_id`) REFERENCES `sale_items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `delivery_items`
--
ALTER TABLE `delivery_items`
  ADD CONSTRAINT `delivery_items_delivery_id_foreign` FOREIGN KEY (`delivery_id`) REFERENCES `deliveries` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `delivery_items_package_buy_id_foreign` FOREIGN KEY (`package_buy_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `delivery_items_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `delivery_items_sale_item_id_foreign` FOREIGN KEY (`sale_item_id`) REFERENCES `sale_items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `designations`
--
ALTER TABLE `designations`
  ADD CONSTRAINT `designations_package_buy_id_foreign` FOREIGN KEY (`package_buy_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `districts`
--
ALTER TABLE `districts`
  ADD CONSTRAINT `districts_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `districts_package_buy_id_foreign` FOREIGN KEY (`package_buy_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `drivers`
--
ALTER TABLE `drivers`
  ADD CONSTRAINT `drivers_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `drivers_district_id_foreign` FOREIGN KEY (`district_id`) REFERENCES `districts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `drivers_package_buy_id_foreign` FOREIGN KEY (`package_buy_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `drivers_thana_id_foreign` FOREIGN KEY (`thana_id`) REFERENCES `thanas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `drivers_union_id_foreign` FOREIGN KEY (`union_id`) REFERENCES `unions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `drivers_vehicle_id_foreign` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicales` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `income_expense_acc_types`
--
ALTER TABLE `income_expense_acc_types`
  ADD CONSTRAINT `income_expense_acc_types_package_buy_id_foreign` FOREIGN KEY (`package_buy_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `income_expense_payment_method_types`
--
ALTER TABLE `income_expense_payment_method_types`
  ADD CONSTRAINT `income_expense_payment_method_types_package_buy_id_foreign` FOREIGN KEY (`package_buy_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `lot_gallaries`
--
ALTER TABLE `lot_gallaries`
  ADD CONSTRAINT `lot_gallaries_package_buy_id_foreign` FOREIGN KEY (`package_buy_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `package_buys`
--
ALTER TABLE `package_buys`
  ADD CONSTRAINT `package_buys_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD CONSTRAINT `payment_methods_package_buy_id_foreign` FOREIGN KEY (`package_buy_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `production_types`
--
ALTER TABLE `production_types`
  ADD CONSTRAINT `production_types_package_buy_id_foreign` FOREIGN KEY (`package_buy_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_acc_cus_sup_id_foreign` FOREIGN KEY (`acc_cus_sup_id`) REFERENCES `acc_customer_suppliers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `products_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `products_lot_gallary_id_foreign` FOREIGN KEY (`lot_gallary_id`) REFERENCES `lot_gallaries` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `products_package_buy_id_foreign` FOREIGN KEY (`package_buy_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `products_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `product_order_bies`
--
ALTER TABLE `product_order_bies`
  ADD CONSTRAINT `product_order_bies_package_buy_id_foreign` FOREIGN KEY (`package_buy_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `purchases`
--
ALTER TABLE `purchases`
  ADD CONSTRAINT `purchases_acc_cus_sup_id_foreign` FOREIGN KEY (`acc_cus_sup_id`) REFERENCES `acc_customer_suppliers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `purchases_package_buy_id_foreign` FOREIGN KEY (`package_buy_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `purchases_payment_method_id_foreign` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `purchase_items`
--
ALTER TABLE `purchase_items`
  ADD CONSTRAINT `purchase_items_package_buy_id_foreign` FOREIGN KEY (`package_buy_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `purchase_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `purchase_items_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `purchase_items_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `purchase_quotations`
--
ALTER TABLE `purchase_quotations`
  ADD CONSTRAINT `purchase_quotations_acc_cus_sup_id_foreign` FOREIGN KEY (`acc_cus_sup_id`) REFERENCES `acc_customer_suppliers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `purchase_quotations_package_buy_id_foreign` FOREIGN KEY (`package_buy_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `purchase_quotation_items`
--
ALTER TABLE `purchase_quotation_items`
  ADD CONSTRAINT `purchase_quotation_items_package_buy_id_foreign` FOREIGN KEY (`package_buy_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `purchase_quotation_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `purchase_quotation_items_purchase_quotation_id_foreign` FOREIGN KEY (`purchase_quotation_id`) REFERENCES `purchase_quotations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `purchase_quotation_items_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `receipts`
--
ALTER TABLE `receipts`
  ADD CONSTRAINT `receipts_package_buy_id_foreign` FOREIGN KEY (`package_buy_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `receipts_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `receipts_vehicale_id_foreign` FOREIGN KEY (`vehicale_id`) REFERENCES `vehicales` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `receipts_ware_house_id_foreign` FOREIGN KEY (`ware_house_id`) REFERENCES `ware_houses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `receipt_challans`
--
ALTER TABLE `receipt_challans`
  ADD CONSTRAINT `receipt_challans_package_buy_id_foreign` FOREIGN KEY (`package_buy_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `receipt_challans_vehicale_id_foreign` FOREIGN KEY (`vehicale_id`) REFERENCES `vehicales` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `receipt_challan_items`
--
ALTER TABLE `receipt_challan_items`
  ADD CONSTRAINT `receipt_challan_items_package_buy_id_foreign` FOREIGN KEY (`package_buy_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `receipt_challan_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `receipt_challan_items_receipt_challan_id_foreign` FOREIGN KEY (`receipt_challan_id`) REFERENCES `receipt_challans` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `receipt_challan_items_receipt_item_id_foreign` FOREIGN KEY (`receipt_item_id`) REFERENCES `receipt_items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `receipt_challan_items_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `receipt_histories`
--
ALTER TABLE `receipt_histories`
  ADD CONSTRAINT `receipt_histories_package_buy_id_foreign` FOREIGN KEY (`package_buy_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `receipt_histories_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `receipt_histories_purchase_item_id_foreign` FOREIGN KEY (`purchase_item_id`) REFERENCES `purchase_items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `receipt_histories_receipt_id_foreign` FOREIGN KEY (`receipt_id`) REFERENCES `receipts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `receipt_histories_receipt_item_id_foreign` FOREIGN KEY (`receipt_item_id`) REFERENCES `receipt_items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `receipt_items`
--
ALTER TABLE `receipt_items`
  ADD CONSTRAINT `receipt_items_package_buy_id_foreign` FOREIGN KEY (`package_buy_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `receipt_items_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `receipt_items_purchase_item_id_foreign` FOREIGN KEY (`purchase_item_id`) REFERENCES `purchase_items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `receipt_items_receipt_id_foreign` FOREIGN KEY (`receipt_id`) REFERENCES `receipts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_acc_cus_sup_id_foreign` FOREIGN KEY (`acc_cus_sup_id`) REFERENCES `acc_customer_suppliers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sales_package_buy_id_foreign` FOREIGN KEY (`package_buy_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sales_payment_method_id_foreign` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sale_challans`
--
ALTER TABLE `sale_challans`
  ADD CONSTRAINT `sale_challans_package_buy_id_foreign` FOREIGN KEY (`package_buy_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sale_challans_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sale_challans_vehicale_id_foreign` FOREIGN KEY (`vehicale_id`) REFERENCES `vehicales` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sale_challan_items`
--
ALTER TABLE `sale_challan_items`
  ADD CONSTRAINT `sale_challan_items_package_buy_id_foreign` FOREIGN KEY (`package_buy_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sale_challan_items_sale_challan_id_foreign` FOREIGN KEY (`sale_challan_id`) REFERENCES `sale_challans` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sale_challan_items_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sale_challan_items_sale_item_id_foreign` FOREIGN KEY (`sale_item_id`) REFERENCES `sale_items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sale_items`
--
ALTER TABLE `sale_items`
  ADD CONSTRAINT `sale_items_package_buy_id_foreign` FOREIGN KEY (`package_buy_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sale_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sale_items_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sale_items_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sale_quotations`
--
ALTER TABLE `sale_quotations`
  ADD CONSTRAINT `sale_quotations_acc_cus_sup_id_foreign` FOREIGN KEY (`acc_cus_sup_id`) REFERENCES `acc_customer_suppliers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sale_quotations_package_buy_id_foreign` FOREIGN KEY (`package_buy_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sale_quotation_items`
--
ALTER TABLE `sale_quotation_items`
  ADD CONSTRAINT `sale_quotation_items_package_buy_id_foreign` FOREIGN KEY (`package_buy_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sale_quotation_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sale_quotation_items_sale_quotation_id_foreign` FOREIGN KEY (`sale_quotation_id`) REFERENCES `sale_quotations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sale_quotation_items_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `stocks`
--
ALTER TABLE `stocks`
  ADD CONSTRAINT `stocks_package_buy_id_foreign` FOREIGN KEY (`package_buy_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `stocks_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `stocks_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `ware_houses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `stock_histories`
--
ALTER TABLE `stock_histories`
  ADD CONSTRAINT `stock_histories_package_buy_id_foreign` FOREIGN KEY (`package_buy_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `stock_histories_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `stock_histories_stock_id_foreign` FOREIGN KEY (`stock_id`) REFERENCES `stocks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `stock_transfers`
--
ALTER TABLE `stock_transfers`
  ADD CONSTRAINT `stock_transfers_package_buy_id_foreign` FOREIGN KEY (`package_buy_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `stock_transfer_items`
--
ALTER TABLE `stock_transfer_items`
  ADD CONSTRAINT `stock_transfer_items_package_buy_id_foreign` FOREIGN KEY (`package_buy_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `stock_transfer_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `stock_transfer_items_stock_transfer_id_foreign` FOREIGN KEY (`stock_transfer_id`) REFERENCES `stock_transfers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `thanas`
--
ALTER TABLE `thanas`
  ADD CONSTRAINT `thanas_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `thanas_district_id_foreign` FOREIGN KEY (`district_id`) REFERENCES `districts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `thanas_package_buy_id_foreign` FOREIGN KEY (`package_buy_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `unions`
--
ALTER TABLE `unions`
  ADD CONSTRAINT `unions_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `unions_district_id_foreign` FOREIGN KEY (`district_id`) REFERENCES `districts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `unions_package_buy_id_foreign` FOREIGN KEY (`package_buy_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `unions_thana_id_foreign` FOREIGN KEY (`thana_id`) REFERENCES `thanas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `units`
--
ALTER TABLE `units`
  ADD CONSTRAINT `units_package_buy_id_foreign` FOREIGN KEY (`package_buy_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_package_buy_id_foreign` FOREIGN KEY (`package_buy_id`) REFERENCES `package_buys` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `vehicales`
--
ALTER TABLE `vehicales`
  ADD CONSTRAINT `vehicales_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `vehicales_district_id_foreign` FOREIGN KEY (`district_id`) REFERENCES `districts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `vehicales_package_buy_id_foreign` FOREIGN KEY (`package_buy_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `vehicales_thana_id_foreign` FOREIGN KEY (`thana_id`) REFERENCES `thanas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `vehicales_union_id_foreign` FOREIGN KEY (`union_id`) REFERENCES `unions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `vehicale_types`
--
ALTER TABLE `vehicale_types`
  ADD CONSTRAINT `vehicale_types_package_buy_id_foreign` FOREIGN KEY (`package_buy_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ware_houses`
--
ALTER TABLE `ware_houses`
  ADD CONSTRAINT `ware_houses_package_buy_id_foreign` FOREIGN KEY (`package_buy_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
