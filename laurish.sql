-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 24, 2024 at 07:05 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `laurish`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_buyers`
--

CREATE TABLE `tbl_buyers` (
  `id` int(11) NOT NULL,
  `buyer_name` varchar(100) NOT NULL,
  `company_name` varchar(100) NOT NULL,
  `company_address` text NOT NULL,
  `country` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `phno` varchar(100) NOT NULL,
  `alternate_phno` varchar(100) NOT NULL,
  `company_email` varchar(255) NOT NULL,
  `company_website` varchar(100) NOT NULL,
  `gstn` varchar(50) NOT NULL,
  `pollution_document` varchar(100) DEFAULT NULL,
  `bank_account_number` varchar(50) NOT NULL,
  `bank_name` varchar(100) NOT NULL,
  `ifsc` varchar(40) NOT NULL,
  `branch` varchar(50) NOT NULL,
  `contact_person_name` varchar(50) NOT NULL,
  `contact_person_number` varchar(50) NOT NULL,
  `contact_person_email` varchar(100) NOT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `created_by` int(11) NOT NULL,
  `created_on` datetime DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_buyers`
--

INSERT INTO `tbl_buyers` (`id`, `buyer_name`, `company_name`, `company_address`, `country`, `state`, `city`, `phno`, `alternate_phno`, `company_email`, `company_website`, `gstn`, `pollution_document`, `bank_account_number`, `bank_name`, `ifsc`, `branch`, `contact_person_name`, `contact_person_number`, `contact_person_email`, `status`, `created_by`, `created_on`, `updated_on`) VALUES
(1, 'Akhil sfsvsf sdf sfs ', 'slkjnvksn', 'kjfmvsm m s jkbs bksjh', 'ksdnvksbkj', 'ksbvkkj', 'kbskjbkj', '9829283923', '9893493498', 'skdb@gmail.com', 'kwrk', 'kjwkvkjk', 'uploads/buyers/2023-12-04-07-22-45267zfdb3t5.png', '8349343043903094030', 'ksrkfb', 'kdsfjvnksb', 'kdfsjnbvk', 'kjsdbvkk', '9384939839', 'skksj@gmil.com', 'Active', 0, '2023-12-04 22:23:00', '2023-12-04 22:26:06');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_categories`
--

CREATE TABLE `tbl_categories` (
  `id` int(11) NOT NULL,
  `category` varchar(100) NOT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `created_by` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_categories`
--

INSERT INTO `tbl_categories` (`id`, `category`, `status`, `created_by`, `created_on`, `updated_on`) VALUES
(1, 'Fuel', 'Active', 1, '2024-01-06 10:41:22', '2024-01-06 10:41:36'),
(2, 'Electricity', 'Active', 1, '2024-01-06 10:44:16', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_dispatch`
--

CREATE TABLE `tbl_dispatch` (
  `id` int(11) NOT NULL,
  `dispatch_number` varchar(100) NOT NULL,
  `buyer_id` int(11) NOT NULL,
  `notes` text NOT NULL,
  `dispatch_date` date NOT NULL,
  `total_gross` decimal(25,2) DEFAULT NULL,
  `total_net` decimal(25,2) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_dispatch`
--

INSERT INTO `tbl_dispatch` (`id`, `dispatch_number`, `buyer_id`, `notes`, `dispatch_date`, `total_gross`, `total_net`, `created_by`, `created_on`) VALUES
(1, 'D1703615692', 1, 'SKJ SKSK S SS S', '2023-12-27', 2500.00, 2250.00, 1, '2023-12-26 22:34:52'),
(2, 'D1704299978', 1, 'dnod d dd ', '2024-01-03', 273.00, 251.00, 1, '2024-01-03 20:39:38');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_dispatch_items`
--

CREATE TABLE `tbl_dispatch_items` (
  `id` int(11) NOT NULL,
  `dispatch_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `gross` decimal(25,2) NOT NULL,
  `tare` decimal(25,2) NOT NULL,
  `net` decimal(25,2) NOT NULL,
  `skid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_dispatch_items`
--

INSERT INTO `tbl_dispatch_items` (`id`, `dispatch_id`, `product_id`, `gross`, `tare`, `net`, `skid`) VALUES
(1, 1, 1, 1200.00, 120.00, 1080.00, 0),
(2, 1, 2, 1300.00, 130.00, 1170.00, 0),
(3, 2, 4, 123.00, 12.00, 111.00, 0),
(4, 2, 3, 150.00, 10.00, 140.00, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_emailtemplates`
--

CREATE TABLE `tbl_emailtemplates` (
  `id` int(11) NOT NULL,
  `template_type` enum('Customer','Others','Order') NOT NULL,
  `template_name` varchar(255) NOT NULL,
  `template_subject` varchar(255) NOT NULL,
  `template_otheremails` varchar(255) NOT NULL,
  `template_body` text NOT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `created_by` int(11) NOT NULL,
  `created_on` datetime DEFAULT NULL,
  `updated_by` int(11) NOT NULL,
  `updated_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_emailtemplates`
--

INSERT INTO `tbl_emailtemplates` (`id`, `template_type`, `template_name`, `template_subject`, `template_otheremails`, `template_body`, `status`, `created_by`, `created_on`, `updated_by`, `updated_on`) VALUES
(1, 'Customer', 'Registration', 'Registration Details - Larush', 'webartise@gmail.com', '<p>Dear <strong>##NAME##,</strong></p>\r\n\r\n<p>Welcome to <a href=\"##SITEURL##\">##SITENAME##</a>.&nbsp;Thank you for registering with us.</p>\r\n\r\n<p>Your login information is as follows:</p>\r\n\r\n<p><strong><strong>Email</strong><strong>:&nbsp;</strong>##EMAIL##</strong><br />\r\n<strong>Password: <strong>##PASSWORD##</strong></strong></p>\r\n\r\n<p><br />\r\nTo log in, please access&nbsp;<a href=\"##SITEURL##\">##SITEURL##</a> and type the username and password given above.<br />\r\nWe request you to make note of this information for future reference<br />\r\n<br />\r\n<br />\r\nWarm Regards,&nbsp;<br />\r\n<strong><a href=\"##SITEURL##\">##SITENAME##</a>&nbsp; Team</strong></p>\r\n\r\n<p><br />\r\n<strong>Note:&nbsp;</strong>You can change your password by login into&nbsp;<a href=\"##SITEURL##\">##SITEURL##</a>&nbsp; and clicking on changing password link in the&nbsp;<strong>My Account</strong>&nbsp;section.</p>\r\n', 'Active', 0, NULL, 0, NULL),
(2, 'Customer', 'Forgot Password', 'OTP for Reset Password - Larush', '', '<h2 class=\"heading_black\"><strong>Dear&nbsp;##NAME##&nbsp;</strong>,</h2>\r\n<p><strong>You recently requested a password reset for your account at&nbsp;<strong><a href=\"##SITEURL##\" target=\"_blank\">##SITENAME##</a></strong>.</strong></p>\r\n<p>Please click below&nbsp;to access the Password Reset page, and use the following one-time access code to reset your password:</p>\r\n<p>One-Time Access Code:&nbsp;<strong>##OTP##</strong></p>\r\n<p>Url :&nbsp;<strong>##RESETURL##<a href=\"##SITEURL##\" target=\"_blank\"><br /></a></strong></p>\r\n<p>If the above link is not clickable, please copy and paste the following into your browser\'s address bar:&nbsp;<strong>##RESETURL##</strong></p>\r\n<p>If you didn&rsquo;t make this change or if you believe an unauthorized person has accessed your account, go to&nbsp;<strong>##LOGINURL##</strong>&nbsp;to reset your password immediately<br /><br /><br /></p>\r\n<p>Regards,</p>\r\n<p><strong><a href=\"##SITEURL##\" target=\"_blank\">##SITENAME##</a>&nbsp;</strong></p>\r\n<p><strong>Team</strong></p>\r\n<p>&nbsp;</p>', 'Active', 0, NULL, 0, NULL),
(3, 'Customer', 'Client Registration', 'Registration Details - Larush', 'webartise@gmail.com', '<p>Dear <strong>##NAME##,</strong></p>\r\n\r\n<p>Welcome to <a href=\"##SITEURL##\">##SITENAME##</a>.&nbsp;Thank you for registering with us.</p>\r\n\r\n<p>Your login information is as follows:</p>\r\n\r\n<p><strong><strong>Email</strong><strong>:&nbsp;</strong>##EMAIL##</strong><br />\r\n<strong>Password: <strong>##PASSWORD##</strong></strong></p>\r\n\r\n<p>Please download the app using the below links</p>\r\n\r\n<p>Android : <strong>##PLAYSTORE##</strong></p>\r\n\r\n<p>IOS :&nbsp;<strong>##APPSSTORE##</strong><br />\r\n<br />\r\nhttps://www.sarbjitenterpriseinc.com</p>\r\n\r\n<p><br />\r\nWarm Regards,&nbsp;<br />\r\n<strong><a href=\"##SITEURL##\">##SITENAME##</a>&nbsp; Team</strong></p>\r\n\r\n<p><br />\r\n<strong>Note:&nbsp;</strong>You can change your password by login into the application&nbsp;and going to the profile you can change password.</p>\r\n', 'Active', 0, NULL, 0, NULL),
(4, 'Customer', 'Client Forgot Password', 'OTP for Reset Password - Larush', 'ranbirsidhu1947@gmail.com', '<h2><strong>Dear&nbsp;##NAME##&nbsp;</strong>,</h2>\r\n\r\n<p><strong>You recently requested a password reset for your account at&nbsp;<strong><a href=\"##SITEURL##\" target=\"_blank\">##SITENAME##</a></strong>.</strong></p>\r\n\r\n<p>Please use the following one-time access code to reset your password:</p>\r\n\r\n<p>One-Time Access Code:&nbsp;<strong>##OTP##</strong></p>\r\n\r\n<p>https://www.sarbjitenterpriseinc.com</p>\r\n\r\n<p>Regards,</p>\r\n\r\n<p><strong><a href=\"##SITEURL##\" target=\"_blank\">##SITENAME##</a>&nbsp;</strong></p>\r\n\r\n<p><strong>Team</strong></p>\r\n\r\n<p>&nbsp;</p>\r\n', 'Active', 1, '2022-10-08 09:58:32', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_expenses`
--

CREATE TABLE `tbl_expenses` (
  `id` int(11) NOT NULL,
  `expense_category` int(11) NOT NULL,
  `expense_purpose` varchar(100) NOT NULL,
  `amount` decimal(25,2) NOT NULL,
  `expense_date` date NOT NULL,
  `expense_receipt` varchar(100) NOT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `created_by` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_expenses`
--

INSERT INTO `tbl_expenses` (`id`, `expense_category`, `expense_purpose`, `amount`, `expense_date`, `expense_receipt`, `status`, `created_by`, `created_on`, `updated_on`) VALUES
(1, 1, 'Truck petrol', 1300.00, '2024-01-06', 'uploads/expenses/2024-01-06-03-27-03sv4kbgeamd.jpg', 'Active', 1, '2024-01-06 18:28:24', '0000-00-00 00:00:00'),
(2, 2, 'Bill', 1800.00, '2024-01-01', 'uploads/expenses/2024-01-08-05-37-121ucndbjg8y.png', 'Active', 1, '2024-01-08 20:37:14', '0000-00-00 00:00:00'),
(3, 1, 'PO', 560.00, '2024-01-10', 'uploads/expenses/2024-01-10-05-52-482myx4gs6cb.jpg', 'Active', 3, '2024-01-10 20:52:51', '0000-00-00 00:00:00'),
(4, 1, 'Fuel refill', 100.00, '2024-01-12', 'uploads/expenses/2024-01-12-07-46-58i5mtq7xjp0.jpg', 'Active', 3, '2024-01-12 07:47:02', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_notifications`
--

CREATE TABLE `tbl_notifications` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `notif_description` text NOT NULL,
  `status` enum('Not Seen','Seen') NOT NULL DEFAULT 'Not Seen',
  `created_by` int(11) NOT NULL,
  `created_on` datetime DEFAULT NULL,
  `updated_by` int(11) NOT NULL,
  `updated_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_notifications`
--

INSERT INTO `tbl_notifications` (`id`, `employee_id`, `title`, `notif_description`, `status`, `created_by`, `created_on`, `updated_by`, `updated_on`) VALUES
(1, 3, 'Test Notification', '<p>dkjb dkvkd idjjojo oj ogojd&nbsp;</p>\r\n', 'Not Seen', 1, '2024-01-24 09:14:06', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_products`
--

CREATE TABLE `tbl_products` (
  `id` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `units` int(11) NOT NULL,
  `is_catalytic` enum('Yes','No') NOT NULL DEFAULT 'No',
  `is_ferrous` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `buyer_price` decimal(25,2) DEFAULT NULL,
  `tier_price` decimal(25,2) DEFAULT NULL,
  `main_image` varchar(100) NOT NULL DEFAULT 'no-image.jpg',
  `wide_image` varchar(100) NOT NULL DEFAULT 'no-image.jpg',
  `zoom_image` varchar(100) NOT NULL DEFAULT 'no-image.jpg',
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `created_by` int(11) NOT NULL,
  `created_on` datetime DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_products`
--

INSERT INTO `tbl_products` (`id`, `product_name`, `units`, `is_catalytic`, `is_ferrous`, `buyer_price`, `tier_price`, `main_image`, `wide_image`, `zoom_image`, `status`, `created_by`, `created_on`, `updated_on`) VALUES
(1, 'B+S Cu', 1, 'No', 'No', 12.00, 22.00, 'uploads/products/2023-12-02-07-49-024vxtjg6um7.jpeg', 'uploads/products/2023-12-02-07-49-10kza48j07yv.jpg', 'uploads/products/2023-12-02-07-49-06m0g3xb8k2q.jpg', 'Active', 1, '2023-12-02 10:50:01', '2024-01-12 07:14:14'),
(2, '#1 Cu', 1, 'No', 'Yes', 32.00, 50.00, 'uploads/products/2023-12-02-08-12-55v1t8o0pc9z.jpg', '', '', 'Active', 1, '2023-12-02 11:12:57', '2024-01-12 07:14:31'),
(3, 'Brass Dirty', 1, 'No', 'No', 12.00, 17.00, '', '', '', 'Active', 1, '2024-01-03 20:34:27', '2024-01-12 07:14:49'),
(4, 'Aluminiun (heavy dirty)', 1, 'No', 'No', 12.00, 10.00, '', '', '', 'Active', 1, '2024-01-03 20:36:18', '2024-01-12 07:13:43'),
(5, 'Catalytic Converter 1', 2, 'Yes', 'No', 0.00, 0.00, '', '', '', 'Active', 1, '2024-01-12 06:47:49', '2024-01-12 07:15:15'),
(6, 'Catalytic Conveter 2', 2, 'Yes', 'No', 0.00, 0.00, '', '', '', 'Active', 1, '2024-01-12 07:15:28', NULL),
(7, 'Dirty Rims', 1, 'No', 'No', 12.00, 15.00, '', '', '', 'Active', 1, '2024-01-12 07:15:55', NULL),
(8, 'Clean Cu Rads', 1, 'No', 'No', 20.00, 25.00, '', '', '', 'Active', 1, '2024-01-12 07:16:18', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_purchases`
--

CREATE TABLE `tbl_purchases` (
  `id` int(11) NOT NULL,
  `receipt_number` varchar(100) NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `receipt_date` date NOT NULL,
  `sub_total` decimal(25,2) NOT NULL,
  `gst` decimal(25,2) NOT NULL,
  `pst` decimal(25,2) NOT NULL,
  `grand_total` decimal(25,2) DEFAULT NULL,
  `final_amount` decimal(25,2) NOT NULL,
  `notes` text NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_purchases`
--

INSERT INTO `tbl_purchases` (`id`, `receipt_number`, `warehouse_id`, `supplier_id`, `receipt_date`, `sub_total`, `gst`, `pst`, `grand_total`, `final_amount`, `notes`, `created_by`, `created_on`) VALUES
(1, 'R1704907001', 1, 1, '2024-01-10', 6796.00, 339.80, 0.00, 7135.80, 7140.00, 'PO 9557', 3, '2024-01-10 21:16:41'),
(2, 'R1705070967', 1, 1, '2024-01-12', 9730.00, 486.50, 0.00, 10216.50, 10220.00, 'sfvs', 1, '2024-01-12 06:49:27'),
(3, 'R1705073907', 1, 1, '2024-01-12', 6275.00, 313.75, 0.00, 6588.75, 6590.00, 'Invoice no 12234', 3, '2024-01-12 07:38:27');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_purchase_items`
--

CREATE TABLE `tbl_purchase_items` (
  `id` int(11) NOT NULL,
  `purchase_id` int(11) NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` decimal(25,2) NOT NULL,
  `price` decimal(25,2) NOT NULL,
  `total` decimal(25,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_purchase_items`
--

INSERT INTO `tbl_purchase_items` (`id`, `purchase_id`, `warehouse_id`, `product_id`, `quantity`, `price`, `total`) VALUES
(1, 1, 1, 1, 123.00, 22.00, 2706.00),
(2, 1, 1, 3, 100.00, 17.00, 1700.00),
(3, 1, 1, 4, 99.00, 10.00, 990.00),
(4, 1, 1, 2, 7899.00, 0.00, 1400.00),
(5, 2, 1, 5, 123.00, 0.00, 1230.00),
(6, 2, 1, 3, 500.00, 17.00, 8500.00),
(7, 3, 1, 1, 100.00, 23.00, 2300.00),
(8, 3, 1, 3, 75.00, 17.00, 1275.00),
(9, 3, 1, 6, 100.00, 0.00, 1500.00),
(10, 3, 1, 6, 120.00, 0.00, 1200.00);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_settings`
--

CREATE TABLE `tbl_settings` (
  `id` int(11) NOT NULL,
  `key` varchar(100) NOT NULL,
  `value` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_settings`
--

INSERT INTO `tbl_settings` (`id`, `key`, `value`) VALUES
(1, 'name', 'Larush Business Solutions Inc.'),
(2, 'website', 'www.larush.ca'),
(3, 'phone_number', '604-701-1838'),
(4, 'address', '23-1970 breaview pl. Kamloops, BC V1S OA2'),
(5, 'invoice_logo', 'uploads/settings/2023-12-12-04-26-35li4to65q7c.png'),
(6, 'logo', 'uploads/settings/2023-12-12-04-25-08sx2tdolqrc.png');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_stock_entries`
--

CREATE TABLE `tbl_stock_entries` (
  `id` int(11) NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` decimal(25,2) NOT NULL,
  `type` enum('purchase','sale','transferred','recieved') NOT NULL,
  `created_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_stock_entries`
--

INSERT INTO `tbl_stock_entries` (`id`, `warehouse_id`, `product_id`, `quantity`, `type`, `created_on`) VALUES
(1, 1, 1, 123.00, 'purchase', '2024-01-10 21:16:41'),
(2, 1, 3, 100.00, 'purchase', '2024-01-10 21:16:41'),
(3, 1, 4, 99.00, 'purchase', '2024-01-10 21:16:41'),
(4, 1, 2, 7899.00, 'purchase', '2024-01-10 21:16:41'),
(5, 1, 5, 123.00, 'purchase', '2024-01-12 06:49:27'),
(6, 1, 3, 500.00, 'purchase', '2024-01-12 06:49:27'),
(7, 1, 1, 100.00, 'purchase', '2024-01-12 07:38:27'),
(8, 1, 3, 75.00, 'purchase', '2024-01-12 07:38:27'),
(9, 1, 6, 100.00, 'purchase', '2024-01-12 07:38:27'),
(10, 1, 6, 120.00, 'purchase', '2024-01-12 07:38:27');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_suppliers`
--

CREATE TABLE `tbl_suppliers` (
  `id` int(11) NOT NULL,
  `supplier_name` varchar(100) NOT NULL,
  `company_name` varchar(100) NOT NULL,
  `company_address` text NOT NULL,
  `country` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `phno` varchar(100) NOT NULL,
  `alternate_phno` varchar(100) NOT NULL,
  `company_email` varchar(255) NOT NULL,
  `company_website` varchar(100) NOT NULL,
  `gstn` varchar(50) NOT NULL,
  `bank_account_number` varchar(50) NOT NULL,
  `bank_name` varchar(100) NOT NULL,
  `ifsc` varchar(40) NOT NULL,
  `branch` varchar(50) NOT NULL,
  `contact_person_name` varchar(50) NOT NULL,
  `contact_person_number` varchar(50) NOT NULL,
  `contact_person_email` varchar(100) NOT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `created_by` int(11) NOT NULL,
  `created_on` datetime DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_suppliers`
--

INSERT INTO `tbl_suppliers` (`id`, `supplier_name`, `company_name`, `company_address`, `country`, `state`, `city`, `phno`, `alternate_phno`, `company_email`, `company_website`, `gstn`, `bank_account_number`, `bank_name`, `ifsc`, `branch`, `contact_person_name`, `contact_person_number`, `contact_person_email`, `status`, `created_by`, `created_on`, `updated_on`) VALUES
(1, 'Central Salvage Ltd', 'Central Salvage Ltd', '717 Carrier St, Kamloops, BC V2H 1G1, Canada\r\nd d db fdd bd', 'Canada', 'British Columbia', 'ksjvksbkv', '2738292328', '2839232932', 'dvkjbdkfjdb@gmail.com', 'd d d df df d df df d', '90923727323823', '20939293293023020300', 'sdckvsbj jhbhj', 'dkjvnksnvjk', 'kjdfnkdsjkvk', 'ksjenknsk', '9348922392', 'ksjkskkj@gmail.com', 'Active', 0, '2023-12-02 12:02:31', '2024-01-12 07:09:26'),
(3, 'Motive Energy Systems', 'Motive Energy Systems', '2188 Mason St #110, Abbotsford, BC V2T 0J8, Canada', 'Canada', 'British Columbia', 'Vancover', '9839489899', '9839439483', 'MotiveEnergySystems@gmail.com', '', '273y278238y288', '9283978273978797', 'Test', 'Test', 'Test', 'Test', '8273827389', 'test@gmaiol.com', 'Active', 1, '2024-01-12 07:10:54', NULL),
(4, 'Davis Trading & Supply Ltd.', 'Davis Trading & Supply Ltd.', '1100 Grant St, Vancouver, BC V6A 2J6, Canada', 'Canada', 'British Columbia', 'Vancover', '9384983979', '9839483874', 'ytest@gmail.com', '', '38743947937439', '9347937749279798', 'Test', 'test', 'test', 'skfjk', '2873972789', 'KJHJDE@GMAIL.COM', 'Active', 1, '2024-01-12 07:11:50', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_tasks`
--

CREATE TABLE `tbl_tasks` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `task_title` varchar(100) NOT NULL,
  `task_description` text NOT NULL,
  `status` enum('Pending','Completed') NOT NULL DEFAULT 'Pending',
  `completed_on` datetime DEFAULT NULL,
  `priority` int(11) DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `updated_on` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_tasks`
--

INSERT INTO `tbl_tasks` (`id`, `employee_id`, `task_title`, `task_description`, `status`, `completed_on`, `priority`, `created_on`, `created_by`, `updated_on`, `updated_by`) VALUES
(1, 3, 'Test Task 12132', 'Kksfvnd df jdkf d dd d f', 'Pending', '2024-01-23 10:21:44', 2, '2024-01-23 09:51:29', 1, NULL, NULL),
(2, 3, 'Test Sample task', '<p><strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n', 'Pending', NULL, 3, '2024-01-23 10:27:14', 1, NULL, NULL),
(3, 3, 'Test Task', '<p>dkjfbjdjdf k dldf knkdfjndnk ndfkln d&nbsp;</p>\r\n', 'Pending', NULL, 1, '2024-01-23 10:39:28', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_units`
--

CREATE TABLE `tbl_units` (
  `id` int(11) NOT NULL,
  `unit_name` varchar(100) NOT NULL,
  `unit_code` varchar(100) NOT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `created_by` int(11) NOT NULL,
  `created_on` datetime DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_units`
--

INSERT INTO `tbl_units` (`id`, `unit_name`, `unit_code`, `status`, `created_by`, `created_on`, `updated_on`) VALUES
(1, 'LBS', 'LBS', 'Active', 1, '2023-12-02 11:47:32', NULL),
(2, 'Units', 'Units', 'Active', 1, '2023-12-02 11:47:32', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `username` varchar(100) NOT NULL,
  `email_id` varchar(100) NOT NULL,
  `phno` varchar(100) NOT NULL,
  `password` varchar(200) NOT NULL,
  `user_type` enum('Admin','Accountant','Employee') DEFAULT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `warehouse_id` int(11) DEFAULT NULL,
  `device_token` varchar(200) DEFAULT NULL,
  `last_logged_on` datetime DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  `password_reset_token` varchar(100) DEFAULT NULL,
  `password_reset_created` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`id`, `first_name`, `last_name`, `username`, `email_id`, `phno`, `password`, `user_type`, `status`, `warehouse_id`, `device_token`, `last_logged_on`, `created_on`, `password_reset_token`, `password_reset_created`) VALUES
(1, 'Akhil', 'Kumar', 'admin@larush.com', 'admin@larush.com', '9885800328', 'c23fb1a3c1c53a1f7f8633771e4a2cd6', 'Admin', 'Active', NULL, NULL, '2024-01-24 08:54:32', '2023-11-27 23:41:19', NULL, NULL),
(3, 'Akhil', 'Kumar', 'akhil.srikakolapu@gmail.com', 'akhil.srikakolapu@gmail.com', '9885800328', 'e10adc3949ba59abbe56e057f20f883e ', 'Employee', 'Active', NULL, NULL, '2024-01-24 09:53:56', '2023-11-28 22:10:34', '59sj81mnlh', '2023-12-05 22:23:39');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user_sessions`
--

CREATE TABLE `tbl_user_sessions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(200) NOT NULL,
  `created_on` datetime DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_user_sessions`
--

INSERT INTO `tbl_user_sessions` (`id`, `user_id`, `token`, `created_on`, `updated_on`) VALUES
(1, 1, 'qz07ycmsd5', '2023-11-27 22:15:06', '2024-01-24 08:54:32'),
(2, 3, 'dzai7wuhec', '2024-01-03 21:29:06', '2024-01-24 09:53:56');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_warehouses`
--

CREATE TABLE `tbl_warehouses` (
  `id` int(11) NOT NULL,
  `warehouse_name` varchar(100) DEFAULT NULL,
  `address` varchar(255) NOT NULL,
  `contact_name` varchar(100) NOT NULL,
  `contact_number` varchar(100) NOT NULL,
  `gst` decimal(25,1) DEFAULT NULL,
  `pst` decimal(25,1) DEFAULT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `created_by` int(11) DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_warehouses`
--

INSERT INTO `tbl_warehouses` (`id`, `warehouse_name`, `address`, `contact_name`, `contact_number`, `gst`, `pst`, `status`, `created_by`, `created_on`, `updated_on`) VALUES
(1, 'Test Warehouse 1', '', 'Akhil Kumar', '9885800328', 4.0, 12.0, 'Active', 1, '2023-12-05 21:40:24', NULL),
(2, 'Test Warehouse 2', 'jbsjh bv\r\nbjsh v', 'Akhil Kumar', '9885800328', 2.0, 3.0, 'Active', 1, '2023-12-05 21:40:59', '2023-12-05 21:43:47');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_buyers`
--
ALTER TABLE `tbl_buyers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_categories`
--
ALTER TABLE `tbl_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_dispatch`
--
ALTER TABLE `tbl_dispatch`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_dispatch_items`
--
ALTER TABLE `tbl_dispatch_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_emailtemplates`
--
ALTER TABLE `tbl_emailtemplates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_expenses`
--
ALTER TABLE `tbl_expenses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_notifications`
--
ALTER TABLE `tbl_notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_products`
--
ALTER TABLE `tbl_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_purchases`
--
ALTER TABLE `tbl_purchases`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_purchase_items`
--
ALTER TABLE `tbl_purchase_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_settings`
--
ALTER TABLE `tbl_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_stock_entries`
--
ALTER TABLE `tbl_stock_entries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_suppliers`
--
ALTER TABLE `tbl_suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_tasks`
--
ALTER TABLE `tbl_tasks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_units`
--
ALTER TABLE `tbl_units`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_user_sessions`
--
ALTER TABLE `tbl_user_sessions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_warehouses`
--
ALTER TABLE `tbl_warehouses`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_buyers`
--
ALTER TABLE `tbl_buyers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_categories`
--
ALTER TABLE `tbl_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_dispatch`
--
ALTER TABLE `tbl_dispatch`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_dispatch_items`
--
ALTER TABLE `tbl_dispatch_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_emailtemplates`
--
ALTER TABLE `tbl_emailtemplates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tbl_expenses`
--
ALTER TABLE `tbl_expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_notifications`
--
ALTER TABLE `tbl_notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_products`
--
ALTER TABLE `tbl_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tbl_purchases`
--
ALTER TABLE `tbl_purchases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_purchase_items`
--
ALTER TABLE `tbl_purchase_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tbl_settings`
--
ALTER TABLE `tbl_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_stock_entries`
--
ALTER TABLE `tbl_stock_entries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tbl_suppliers`
--
ALTER TABLE `tbl_suppliers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_tasks`
--
ALTER TABLE `tbl_tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_units`
--
ALTER TABLE `tbl_units`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_user_sessions`
--
ALTER TABLE `tbl_user_sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_warehouses`
--
ALTER TABLE `tbl_warehouses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
