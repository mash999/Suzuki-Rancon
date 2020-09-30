-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 31, 2018 at 10:47 PM
-- Server version: 10.1.30-MariaDB
-- PHP Version: 7.2.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `suzuki_new`
--

-- --------------------------------------------------------

--
-- Table structure for table `additional_delivery`
--

CREATE TABLE `additional_delivery` (
  `KEY_ID` int(11) NOT NULL,
  `DO_DATE` date DEFAULT NULL,
  `DELIVERY_DATE` date DEFAULT NULL,
  `SITE` text,
  `REFERENCE_DO_NUMBER` text,
  `REFERENCE_CO_NUMBER` text,
  `CUSTOMER_CODE` int(11) DEFAULT NULL,
  `SALES_CHANNEL` text,
  `ENTRY_TIME` int(11) DEFAULT NULL,
  `ENTERED_AT` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `additional_delivery`
--

INSERT INTO `additional_delivery` (`KEY_ID`, `DO_DATE`, `DELIVERY_DATE`, `SITE`, `REFERENCE_DO_NUMBER`, `REFERENCE_CO_NUMBER`, `CUSTOMER_CODE`, `SALES_CHANNEL`, `ENTRY_TIME`, `ENTERED_AT`) VALUES
(4, '2018-07-25', '2018-07-20', 'new site', '28198', '78271', 1, 'Retail', 1532477198, '2018-07-24 18:06:38'),
(5, '2018-07-27', '2018-07-13', 'warehouse', '34738', '982718', 1, 'Corporate', 1532480991, '2018-07-25 19:09:51');

-- --------------------------------------------------------

--
-- Table structure for table `backup_delivery`
--

CREATE TABLE `backup_delivery` (
  `KEY_ID` int(11) NOT NULL,
  `DELIVERY_DATE` date DEFAULT NULL,
  `SITE` text,
  `REQUESTER_NAME` text,
  `REQUESTER_DESIGNATION` text,
  `REQUESTER_DEPARTMENT` text,
  `REQUISITION_NUMBER` text,
  `REFERENCE_NUMBER` text,
  `ENTERED_AT` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `backup_delivery`
--

INSERT INTO `backup_delivery` (`KEY_ID`, `DELIVERY_DATE`, `SITE`, `REQUESTER_NAME`, `REQUESTER_DESIGNATION`, `REQUESTER_DEPARTMENT`, `REQUISITION_NUMBER`, `REFERENCE_NUMBER`, `ENTERED_AT`) VALUES
(1, '2018-07-26', 'warehouse', 'requestername', 'desg', 'dept', '37', '2', '2018-07-26 07:11:22');

-- --------------------------------------------------------

--
-- Table structure for table `claims`
--

CREATE TABLE `claims` (
  `KEY_ID` int(11) NOT NULL,
  `TYPE` text,
  `CLAIM_ISSUE_DATE` date DEFAULT NULL,
  `SITE` text,
  `CREATED_BY` text,
  `APPROVED_BY` text,
  `APD_NUMBER` text,
  `PPD_NUMBER` text,
  `CLAIM_REFERENCE_NUMBER` text,
  `MODEL` text,
  `INVOICE_NUMBER` text,
  `LC_NUMBER` text,
  `SHIPPING_MODE` text,
  `MONTH` varchar(10) DEFAULT NULL,
  `YEAR` int(11) DEFAULT NULL,
  `ENTERED_AT` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `claims`
--

INSERT INTO `claims` (`KEY_ID`, `TYPE`, `CLAIM_ISSUE_DATE`, `SITE`, `CREATED_BY`, `APPROVED_BY`, `APD_NUMBER`, `PPD_NUMBER`, `CLAIM_REFERENCE_NUMBER`, `MODEL`, `INVOICE_NUMBER`, `LC_NUMBER`, `SHIPPING_MODE`, `MONTH`, `YEAR`, `ENTERED_AT`) VALUES
(1, 'ckd-claim', '2018-08-31', 'new site', 'ruhul', 'Mr.Fahim', '37289', 'ppd8299', '23', 'model-1', 'inv93910929', '12', 'Air', 'Jan', 2018, '2018-08-31 17:15:12');

-- --------------------------------------------------------

--
-- Table structure for table `claims_parts`
--

CREATE TABLE `claims_parts` (
  `KEY_ID` int(11) NOT NULL,
  `ENTRY_REFERENCE_ID` int(11) DEFAULT NULL,
  `PART_NUMBER` text,
  `PART_NAME` text,
  `COLOR_CODE` text,
  `COLOR_NAME` text,
  `QUANTITY` int(11) DEFAULT NULL,
  `UNIT` text,
  `BOX_NUMBER` text,
  `CASE_NUMBER` text,
  `REFERENCE_NUMBER` text,
  `LOT_NUMBER` text,
  `CLAIM_TYPE` text,
  `CLAIM_CODE` text,
  `ACTION_CODE` text,
  `PROCESS_CODE` text,
  `DETAILS_OF_DEFECT` text,
  `DEFECT_FINDING_WAY` text,
  `PICTURE` text,
  `REMARKS` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `claims_parts`
--

INSERT INTO `claims_parts` (`KEY_ID`, `ENTRY_REFERENCE_ID`, `PART_NUMBER`, `PART_NAME`, `COLOR_CODE`, `COLOR_NAME`, `QUANTITY`, `UNIT`, `BOX_NUMBER`, `CASE_NUMBER`, `REFERENCE_NUMBER`, `LOT_NUMBER`, `CLAIM_TYPE`, `CLAIM_CODE`, `ACTION_CODE`, `PROCESS_CODE`, `DETAILS_OF_DEFECT`, `DEFECT_FINDING_WAY`, `PICTURE`, `REMARKS`) VALUES
(1, 1, 'part3920', 'part299', '333', 'black', 2, 'Litter', '12', '2', '23', '31', 'damaged', '43', '45', '123', 'some details', 'some way', '../img/5b8977a0dd7d0fb api.JPG', 'some remarks');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `CUSTOMER_ID` int(11) NOT NULL,
  `CUSTOMER_NAME` text,
  `CUSTOMER_ADDRESS` text,
  `CUSTOMER_CITY` text,
  `CUSTOMER_PHONE_OFFICE` text,
  `CUSTOMER_PHONE_OPTIONAL` text,
  `CUSTOMER_PHONE_MOBILE` text,
  `CUSTOMER_EMAIL` text,
  `CUSTOMER_FAX` text,
  `CUSTOMER_WEBSITE` text,
  `CUSTOMER_TYPE` text,
  `ENTERED_AT` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`CUSTOMER_ID`, `CUSTOMER_NAME`, `CUSTOMER_ADDRESS`, `CUSTOMER_CITY`, `CUSTOMER_PHONE_OFFICE`, `CUSTOMER_PHONE_OPTIONAL`, `CUSTOMER_PHONE_MOBILE`, `CUSTOMER_EMAIL`, `CUSTOMER_FAX`, `CUSTOMER_WEBSITE`, `CUSTOMER_TYPE`, `ENTERED_AT`) VALUES
(1, 'NAHAR ENTERPRISE', '', 'DHAKA', '3645', '', '3256346437', '', '', '', 'Dealer', '2018-07-22 03:32:43');

-- --------------------------------------------------------

--
-- Table structure for table `delivery`
--

CREATE TABLE `delivery` (
  `KEY_ID` int(11) NOT NULL,
  `DO_DATE` date DEFAULT NULL,
  `DELIVERY_DATE` date DEFAULT NULL,
  `SITE` text,
  `REFERENCE_DO_NUMBER` text,
  `REFERENCE_CO_NUMBER` text,
  `CUSTOMER_CODE` int(11) DEFAULT NULL,
  `TRANSPORT_NAME` text,
  `TRUCK_NUMBER` text,
  `DRIVER_NAME` text,
  `DRIVER_MOBILE_NUMBER` text,
  `SALES_CHANNEL` text,
  `ENTRY_TIME` int(11) DEFAULT NULL,
  `ENTERED_AT` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `delivery`
--

INSERT INTO `delivery` (`KEY_ID`, `DO_DATE`, `DELIVERY_DATE`, `SITE`, `REFERENCE_DO_NUMBER`, `REFERENCE_CO_NUMBER`, `CUSTOMER_CODE`, `TRANSPORT_NAME`, `TRUCK_NUMBER`, `DRIVER_NAME`, `DRIVER_MOBILE_NUMBER`, `SALES_CHANNEL`, `ENTRY_TIME`, `ENTERED_AT`) VALUES
(1, '2018-07-19', '2018-07-26', 'new site', '378', '787', 1, 'truck', '38', 'somename', '01838276279', 'Dealer', 1532566622, '2018-07-25 18:57:02'),
(2, '2018-07-27', '2018-07-28', 'new site', '4574899', '83919', 1, 'truck', '3891', 'some name', '0192438676', 'Dealer', 1532568974, '2018-07-26 19:36:14');

-- --------------------------------------------------------

--
-- Table structure for table `delivery_parts`
--

CREATE TABLE `delivery_parts` (
  `KEY_ID` int(11) NOT NULL,
  `TYPE` text,
  `ENTRY_REFERENCE_ID` int(11) DEFAULT NULL,
  `PART_NUMBER` text,
  `PART_NAME` text,
  `MODEL` text,
  `COLOR_CODE` text,
  `COLOR_NAME` text,
  `QUANTITY` int(11) DEFAULT NULL,
  `UNIT` text,
  `FRAME_NUMBER` text,
  `ENGINE_NUMBER` text,
  `KEY_RING_NUMBER` text,
  `BATTERY_NUMBER` text,
  `LC_NUMBER` text,
  `INVOICE_NUMBER` text,
  `REMARKS` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `delivery_parts`
--

INSERT INTO `delivery_parts` (`KEY_ID`, `TYPE`, `ENTRY_REFERENCE_ID`, `PART_NUMBER`, `PART_NAME`, `MODEL`, `COLOR_CODE`, `COLOR_NAME`, `QUANTITY`, `UNIT`, `FRAME_NUMBER`, `ENGINE_NUMBER`, `KEY_RING_NUMBER`, `BATTERY_NUMBER`, `LC_NUMBER`, `INVOICE_NUMBER`, `REMARKS`) VALUES
(1, 'normal', 1, '9281982', 'part5402', 'model-3', 'jsp', 'red', 3, 'Pieces', '', '', '', '', '', '', ''),
(2, 'normal', 1, '2209189', 'part3322', 'model-2', 'jsp', 'red', 2, 'Pieces', 'FRAME2321', 'ENG232819', '', '', '', '', ''),
(3, 'normal', 2, '4839381', 'part4930', 'model-1', 'jsp', 'red', 6, 'Pieces', '', '', '', '', '', '', ''),
(4, 'normal', 2, '9281980', 'part5402', 'model-3', 'jsp', 'red', 3, 'Pieces', '', '', '', '', '', '', ''),
(5, 'normal', 2, '2209182', 'part3322', 'model-2', 'jsp', 'red', 4, 'Pieces', '', '', '', '', '', '', ''),
(6, 'backup', 1, '9281982', 'part5402', 'model-3', 'jsp', 'red', 3, 'Pieces', NULL, NULL, NULL, NULL, NULL, NULL, ''),
(7, 'backup', 1, '4839381', 'part4930', 'model-1', 'jsp', 'red', 6, 'Pieces', NULL, NULL, NULL, NULL, NULL, NULL, ''),
(8, 'additional', 5, '3920192', 'part3782', 'model-1', 'kel', 'blue', 3, 'Pieces', NULL, NULL, NULL, NULL, NULL, NULL, '');

-- --------------------------------------------------------

--
-- Table structure for table `entries`
--

CREATE TABLE `entries` (
  `KEY_ID` int(11) NOT NULL,
  `REQUISITION_NUMBER` int(11) DEFAULT NULL,
  `TYPE` text,
  `ENTRY_DATE` date DEFAULT NULL,
  `SITE` text,
  `SUPPLIER_CODE` text,
  `INVOICE_NUMBER` text,
  `LC_NUMBER` text,
  `LOT_NUMBER` text,
  `PPD_NUMBER` text,
  `SUPPLIER_CHALLAN_NUMBER` text,
  `ENTERED_AT` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `entries`
--

INSERT INTO `entries` (`KEY_ID`, `REQUISITION_NUMBER`, `TYPE`, `ENTRY_DATE`, `SITE`, `SUPPLIER_CODE`, `INVOICE_NUMBER`, `LC_NUMBER`, `LOT_NUMBER`, `PPD_NUMBER`, `SUPPLIER_CHALLAN_NUMBER`, `ENTERED_AT`) VALUES
(1, 12, 'ckdbom', '2018-07-24', 'warehouse site', '1', '278178', '91271', '12', '', '', '2018-07-25 18:11:28'),
(2, 8, 'ckd', '2018-07-31', 'warehouse site', '1', '37287879', '1289', '21', '', '', '2018-07-25 18:11:58'),
(3, 23, 'cbu', '2018-07-26', 'warehouse', '1', '27187', '1238', '32', '', '', '2018-07-25 18:14:04'),
(4, 37, 'spare-parts', '2018-07-26', 'new site', '1', '37287', '8917', '12', '74878', '', '2018-07-26 07:09:40'),
(5, 1, 'additional-parts', '2018-09-01', 'new site', '1', '', '', '', '', '12', '2018-07-26 07:12:50'),
(6, 34, 'ckd', '2018-07-27', 'new site', '1', '5436inv4543', '231', '12', '', '', '2018-07-27 14:03:34'),
(7, 12, 'ckd', '2018-07-28', 'new site', '1', 'inv83919', '318981', '12', '', '', '2018-07-27 19:18:18');

-- --------------------------------------------------------

--
-- Table structure for table `issues`
--

CREATE TABLE `issues` (
  `KEY_ID` int(11) NOT NULL,
  `TYPE` text,
  `RECEIVED` tinyint(1) DEFAULT NULL,
  `REFERENCE_NUMBER` int(11) DEFAULT NULL,
  `ENTRY_DATE` date DEFAULT NULL,
  `SITE` text,
  `NAME` text,
  `DESIGNATION` text,
  `DEPARTMENT` text,
  `INVOICE_NUMBER` text,
  `LC_NUMBER` text,
  `LOT_NUMBER` text,
  `PPD_NUMBER` text,
  `ENTERED_AT` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `issues`
--

INSERT INTO `issues` (`KEY_ID`, `TYPE`, `RECEIVED`, `REFERENCE_NUMBER`, `ENTRY_DATE`, `SITE`, `NAME`, `DESIGNATION`, `DEPARTMENT`, `INVOICE_NUMBER`, `LC_NUMBER`, `LOT_NUMBER`, `PPD_NUMBER`, `ENTERED_AT`) VALUES
(1, 'ckd-issue', 2, NULL, '2018-07-26', 'warehouse', 'reqname', 'reqdesg', 'reqdept', '374838', '343728', '12', '', '2018-07-25 18:44:01'),
(2, 'ckd-bom-issue', 2, NULL, '2018-07-26', 'warehouse', 'reqname', 'reqdesg', 'reqdept', '473829', '82178', '12', '', '2018-07-25 18:44:42'),
(3, 'ckd-issue', 1, 1, '2018-07-26', 'warehouse', 'reqname', 'reqdesg', 'reqdept', '374838', '343728', '12', '', '2018-07-25 18:44:53'),
(4, 'ckd-bom-issue', 1, 2, '2018-07-26', 'warehouse', 'reqname', 'reqdesg', 'reqdept', '473829', '82178', '12', '', '2018-07-25 18:45:04'),
(5, 'manufacturing-issue', 1, NULL, '2018-07-27', 'new site', 'some sender', 'some desg', 'some dept', '437829', '2347389', '12', '4547849', '2018-07-27 15:19:01'),
(6, 'manufacturing-issue', 1, NULL, '2018-07-27', 'new site', 'name', 'desg', 'dept', '43889', '392091', '483920', '14', '2018-07-28 15:20:03'),
(7, 'ckd-issue', 2, NULL, '2018-07-27', 'new site', 'name', 'desg', 'dept', '21789inv90', '28198', '12', '', '2018-07-27 17:45:23'),
(8, 'ckd-issue', 1, 7, '2018-07-28', 'new site', 'name', 'desg', 'dept', '21789inv90', '28198', '12', '', '2018-07-27 17:45:50'),
(9, 'ckd-issue', 1, NULL, '2018-07-28', 'new site', 'name', 'desg', 'dept', '932849', '2198', '8', '', '2018-07-28 17:58:36');

-- --------------------------------------------------------

--
-- Table structure for table `issue_records`
--

CREATE TABLE `issue_records` (
  `KEY_ID` int(11) NOT NULL,
  `ENTRY_REFERENCE_ID` int(11) DEFAULT NULL,
  `PART_NUMBER` text,
  `PART_NAME` text,
  `MODEL` text,
  `COLOR_CODE` text,
  `COLOR_NAME` text,
  `QUANTITY` int(11) DEFAULT NULL,
  `UNIT` text,
  `FRAME_NUMBER` text,
  `ENGINE_NUMBER` text,
  `CRIPPLE_REASON` text,
  `REMARKS` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `issue_records`
--

INSERT INTO `issue_records` (`KEY_ID`, `ENTRY_REFERENCE_ID`, `PART_NUMBER`, `PART_NAME`, `MODEL`, `COLOR_CODE`, `COLOR_NAME`, `QUANTITY`, `UNIT`, `FRAME_NUMBER`, `ENGINE_NUMBER`, `CRIPPLE_REASON`, `REMARKS`) VALUES
(1, 1, '4839381', 'part4930', 'model-1', 'jsp', 'red', 3, 'Pieces', 'frame4839', 'eng0390', 'issue1', 'remarks1'),
(2, 1, '3920192', 'part3782', 'model-1', 'kel', 'blue', 3, 'Pieces', 'frame0192', 'eng3001', 'issue2', 'remarks2'),
(3, 1, '9102838', 'part0392', 'model-1', 'ker', 'red', 3, 'Pieces', 'frame3820', 'eng2839', 'issue3', 'remarks3'),
(4, 1, '2209189', 'part3322', 'model-2', 'jsp', 'red', 3, 'Pieces', 'frame9029', 'eng0549', 'issue4', 'remarks4'),
(5, 1, '5848900', 'part1111', 'model-2', 'kel', 'blue', 3, 'Pieces', 'framepo39', 'eng29mi', 'issue5', 'remarks5'),
(6, 1, '5930293', 'part4039', 'model-3', 'ker', 'black', 3, 'Pieces', 'frame9022', 'eng2111', 'issue6', 'remarks6'),
(7, 1, '9281982', 'part5402', 'model-3', 'jsp', 'red', 3, 'Pieces', 'frame3902', 'eng898', 'issue7', 'remarks7'),
(8, 2, '4839381', 'part4930', 'model-1', 'jsp', 'red', 3, 'Pieces', 'frame4839', 'eng0390', 'issue1', 'remarks1'),
(9, 2, '3920192', 'part3782', 'model-1', 'kel', 'blue', 3, 'Pieces', 'frame0192', 'eng3001', 'issue2', 'remarks2'),
(10, 2, '9102838', 'part0392', 'model-1', 'ker', 'red', 3, 'Pieces', 'frame3820', 'eng2839', 'issue3', 'remarks3'),
(11, 2, '2209189', 'part3322', 'model-2', 'jsp', 'red', 3, 'Pieces', 'frame9029', 'eng0549', 'issue4', 'remarks4'),
(12, 2, '5848900', 'part1111', 'model-2', 'kel', 'blue', 3, 'Pieces', 'framepo39', 'eng29mi', 'issue5', 'remarks5'),
(13, 2, '5930293', 'part4039', 'model-3', 'ker', 'black', 3, 'Pieces', 'frame9022', 'eng2111', 'issue6', 'remarks6'),
(14, 2, '9281982', 'part5402', 'model-3', 'jsp', 'red', 3, 'Pieces', 'frame3902', 'eng898', 'issue7', 'remarks7'),
(15, 3, '4839381', 'part4930', 'model-1', 'jsp', 'red', 3, 'Pieces', 'frame4839', 'eng0390', '', 'remarks1'),
(16, 3, '3920192', 'part3782', 'model-1', 'kel', 'blue', 3, 'Pieces', 'frame0192', 'eng3001', '', 'remarks2'),
(17, 3, '9102838', 'part0392', 'model-1', 'ker', 'red', 3, 'Pieces', 'frame3820', 'eng2839', '', 'remarks3'),
(18, 3, '2209189', 'part3322', 'model-2', 'jsp', 'red', 3, 'Pieces', 'frame9029', 'eng0549', '', 'remarks4'),
(19, 3, '5848900', 'part1111', 'model-2', 'kel', 'blue', 3, 'Pieces', 'framepo39', 'eng29mi', '', 'remarks5'),
(20, 3, '5930293', 'part4039', 'model-3', 'ker', 'black', 3, 'Pieces', 'frame9022', 'eng2111', '', 'remarks6'),
(21, 3, '9281982', 'part5402', 'model-3', 'jsp', 'red', 3, 'Pieces', 'frame3902', 'eng898', '', 'remarks7'),
(22, 4, '4839381', 'part4930', 'model-1', 'jsp', 'red', 3, 'Pieces', 'frame4839', 'eng0390', '', 'remarks1'),
(23, 4, '3920192', 'part3782', 'model-1', 'kel', 'blue', 3, 'Pieces', 'frame0192', 'eng3001', '', 'remarks2'),
(24, 4, '9102838', 'part0392', 'model-1', 'ker', 'red', 3, 'Pieces', 'frame3820', 'eng2839', '', 'remarks3'),
(25, 4, '2209189', 'part3322', 'model-2', 'jsp', 'red', 3, 'Pieces', 'frame9029', 'eng0549', '', 'remarks4'),
(26, 4, '5848900', 'part1111', 'model-2', 'kel', 'blue', 3, 'Pieces', 'framepo39', 'eng29mi', '', 'remarks5'),
(27, 4, '5930293', 'part4039', 'model-3', 'ker', 'black', 3, 'Pieces', 'frame9022', 'eng2111', '', 'remarks6'),
(28, 4, '9281982', 'part5402', 'model-3', 'jsp', 'red', 3, 'Pieces', 'frame3902', 'eng898', '', 'remarks7'),
(29, 5, '4839381', 'part4930', 'model-1', 'jsp', 'red', 3, 'Pieces', 'frame4839', 'eng0390', 'issue1', 'remarks1'),
(30, 5, '3920192', 'part3782', 'model-1', 'kel', 'blue', 3, 'Pieces', 'frame0192', 'eng3001', 'issue2', 'remarks2'),
(31, 5, '9102838', 'part0392', 'model-1', 'ker', 'red', 3, 'Pieces', 'frame3820', 'eng2839', 'issue3', 'remarks3'),
(32, 5, '2209189', 'part3322', 'model-2', 'jsp', 'red', 3, 'Pieces', 'frame9029', 'eng0549', 'issue4', 'remarks4'),
(33, 5, '5848900', 'part1111', 'model-2', 'kel', 'blue', 3, 'Pieces', 'framepo39', 'eng29mi', 'issue5', 'remarks5'),
(34, 5, '5930293', 'part4039', 'model-3', 'ker', 'black', 3, 'Pieces', 'frame9022', 'eng2111', 'issue6', 'remarks6'),
(35, 5, '9281982', 'part5402', 'model-3', 'jsp', 'red', 3, 'Pieces', 'frame3902', 'eng898', 'issue7', 'remarks7'),
(36, 6, '9281982', 'excel part', 'excel model', 'excel color code', 'excel color name', 3, 'piece', 'Return Reason', 'some remarks', '', ''),
(37, 6, '9281982', 'excel part', 'excel model', 'excel color code', 'excel color name', 3, 'piece', 'Return Reason', 'some remarks', '', ''),
(38, 7, '4839389', 'part4930', 'model-1', 'jsp', 'red', 3, 'Pieces', 'frame4839', 'eng0390', 'issue1', 'remarks1'),
(39, 7, '3920195', 'part3782', 'model-1', 'kel', 'blue', 3, 'Pieces', 'frame0192', 'eng3001', 'issue2', 'remarks2'),
(40, 7, '9102838', 'part0392', 'model-1', 'ker', 'red', 3, 'Pieces', 'frame3820', 'eng2839', 'issue3', 'remarks3'),
(41, 7, '2209189', 'part3322', 'model-2', 'jsp', 'red', 3, 'Pieces', 'frame9029', 'eng0549', 'issue4', 'remarks4'),
(42, 7, '5848900', 'part1111', 'model-2', 'kel', 'blue', 3, 'Pieces', 'framepo39', 'eng29mi', 'issue5', 'remarks5'),
(43, 7, '5930293', 'part4039', 'model-3', 'ker', 'black', 3, 'Pieces', 'frame9022', 'eng2111', 'issue6', 'remarks6'),
(44, 7, '9281982', 'part5402', 'model-3', 'jsp', 'red', 3, 'Pieces', 'frame3902', 'eng898', 'issue7', 'remarks7'),
(45, 8, '2209189', 'part3322', 'model-2', 'jsp', 'red', 3, 'Pieces', 'frame9029', 'eng0549', '', 'remarks4'),
(46, 8, '5848900', 'part1111', 'model-2', 'kel', 'blue', 3, 'Pieces', 'framepo39', 'eng29mi', '', 'remarks5'),
(47, 8, '9281982', 'part5402', 'model-3', 'jsp', 'red', 3, 'Pieces', 'frame3902', 'eng898', '', 'remarks7'),
(48, 9, '4839381', 'part4930', 'model-1', 'jsp', 'red', 3, 'Pieces', '', '', '', ''),
(49, 9, '3920192', 'part3782', 'model-1', 'kel', 'blue', 3, 'Pieces', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `parts`
--

CREATE TABLE `parts` (
  `KEY_ID` int(11) NOT NULL,
  `ENTRY_REFERENCE_ID` int(11) NOT NULL,
  `PART_NUMBER` text,
  `PART_NAME` text,
  `MODEL` text,
  `COLOR_CODE` text,
  `COLOR_NAME` text,
  `QUANTITY` int(11) DEFAULT NULL,
  `UNIT` text,
  `FRAME_NUMBER` text,
  `ENGINE_NUMBER` text,
  `REMARKS` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `parts`
--

INSERT INTO `parts` (`KEY_ID`, `ENTRY_REFERENCE_ID`, `PART_NUMBER`, `PART_NAME`, `MODEL`, `COLOR_CODE`, `COLOR_NAME`, `QUANTITY`, `UNIT`, `FRAME_NUMBER`, `ENGINE_NUMBER`, `REMARKS`) VALUES
(1, 1, '4839380', 'part4930', 'model-1', 'jsp', 'red', 3, 'Pieces', 'frame4839', 'eng0390', 'some remarks'),
(2, 1, '4839381', 'part4930', 'model-1', 'jsp', 'red', 3, 'Pieces', 'frame4839', 'eng0390', 'some remarks'),
(3, 1, '3920192', 'part3782', 'model-1', 'kel', 'blue', 3, 'Pieces', 'frame0192', 'eng3001', 'some remarks'),
(4, 1, '9102838', 'part0392', 'model-1', 'ker', 'red', 3, 'Pieces', 'frame3820', 'eng2839', 'some remarks'),
(5, 1, '2209189', 'part3322', 'model-2', 'jsp', 'red', 3, 'Pieces', 'frame9029', 'eng0549', 'some remarks'),
(6, 1, '5848900', 'part1111', 'model-2', 'kel', 'blue', 3, 'Pieces', 'framepo39', 'eng29mi', 'some remarks'),
(7, 1, '5930293', 'part4039', 'model-3', 'ker', 'black', 3, 'Pieces', 'frame9022', 'eng2111', 'some remarks'),
(8, 1, '9281982', 'part5402', 'model-3', 'jsp', 'red', 3, 'Pieces', 'frame3902', 'eng898', 'some remarks'),
(9, 2, '4839380', 'part4930', 'model-1', 'jsp', 'red', 3, 'Pieces', 'frame4839', 'eng0390', 'remarks1'),
(10, 2, '4839381', 'part4930', 'model-1', 'jsp', 'red', 3, 'Pieces', 'frame4839', 'eng0390', 'remarks1'),
(11, 2, '3920192', 'part3782', 'model-1', 'kel', 'blue', 3, 'Pieces', 'frame0192', 'eng3001', 'remarks2'),
(12, 2, '9102838', 'part0392', 'model-1', 'ker', 'red', 3, 'Pieces', 'frame3820', 'eng2839', 'remarks3'),
(13, 2, '2209189', 'part3322', 'model-2', 'jsp', 'red', 3, 'Pieces', 'frame9029', 'eng0549', 'remarks4'),
(14, 2, '5848900', 'part1111', 'model-2', 'kel', 'blue', 3, 'Pieces', 'framepo39', 'eng29mi', 'remarks5'),
(15, 2, '5930293', 'part4039', 'model-3', 'ker', 'black', 3, 'Pieces', 'frame9022', 'eng2111', 'remarks6'),
(16, 2, '9281982', 'part5402', 'model-3', 'jsp', 'red', 3, 'Pieces', 'frame3902', 'eng898', 'remarks7'),
(17, 3, '4839381', 'part4930', 'model-1', 'jsp', 'red', 3, 'Pieces', 'frame4839', 'eng0390', 'remarks1'),
(18, 3, '4839381', 'part4930', 'model-1', 'jsp', 'red', 3, 'Pieces', 'frame4839', 'eng0390', 'remarks1'),
(19, 3, '3920192', 'part3782', 'model-1', 'kel', 'blue', 3, 'Pieces', 'frame0192', 'eng3001', 'remarks2'),
(20, 3, '9102838', 'part0392', 'model-1', 'ker', 'red', 3, 'Pieces', 'frame3820', 'eng2839', 'remarks3'),
(21, 3, '2209189', 'part3322', 'model-2', 'jsp', 'red', 3, 'Pieces', 'frame9029', 'eng0549', 'remarks4'),
(22, 3, '5848900', 'part1111', 'model-2', 'kel', 'blue', 3, 'Pieces', 'framepo39', 'eng29mi', 'remarks5'),
(23, 3, '5930293', 'part4039', 'model-3', 'ker', 'black', 3, 'Pieces', 'frame9022', 'eng2111', 'remarks6'),
(24, 3, '9281982', 'part5402', 'model-3', 'jsp', 'red', 3, 'Pieces', 'frame3902', 'eng898', 'remarks7'),
(25, 4, '4839381', 'part4930', 'model-1', 'jsp', 'red', 3, 'Pieces', 'frame4839', 'eng0390', 'remarks1'),
(26, 4, '4839381', 'part4930', 'model-1', 'jsp', 'red', 3, 'Pieces', 'frame4839', 'eng0390', 'remarks1'),
(27, 4, '3920192', 'part3782', 'model-1', 'kel', 'blue', 3, 'Pieces', 'frame0192', 'eng3001', 'remarks2'),
(28, 4, '9102838', 'part0392', 'model-1', 'ker', 'red', 3, 'Pieces', 'frame3820', 'eng2839', 'remarks3'),
(29, 4, '2209189', 'part3322', 'model-2', 'jsp', 'red', 3, 'Pieces', 'frame9029', 'eng0549', 'remarks4'),
(30, 4, '5848900', 'part1111', 'model-2', 'kel', 'blue', 3, 'Pieces', 'framepo39', 'eng29mi', 'remarks5'),
(31, 4, '5930293', 'part4039', 'model-3', 'ker', 'black', 3, 'Pieces', 'frame9022', 'eng2111', 'remarks6'),
(32, 4, '9281982', 'part5402', 'model-3', 'jsp', 'red', 3, 'Pieces', 'frame3902', 'eng898', 'remarks7'),
(33, 5, '4839380', 'part4930', 'model-1', 'jsp', 'red', 3, 'Pieces', NULL, NULL, 'some remarks'),
(34, 5, '4839384', 'part4930', 'model-1', 'jsp', 'red', 3, 'Pieces', NULL, NULL, 'some remarks'),
(35, 5, '3920192', 'part3782', 'model-1', 'kel', 'blue', 3, 'Pieces', NULL, NULL, 'some remarks'),
(36, 5, '9102838', 'part0392', 'model-1', 'ker', 'red', 3, 'Pieces', NULL, NULL, 'some remarks'),
(37, 5, '2209189', 'part3322', 'model-2', 'jsp', 'red', 3, 'Pieces', NULL, NULL, 'some remarks'),
(38, 5, '5848900', 'part1111', 'model-2', 'kel', 'blue', 3, 'Pieces', NULL, NULL, 'some remarks'),
(39, 5, '5930293', 'part4039', 'model-3', 'ker', 'black', 3, 'Pieces', NULL, NULL, 'some remarks'),
(40, 5, '9281982', 'part5402', 'model-3', 'jsp', 'red', 3, 'Pieces', NULL, NULL, 'some remarks'),
(41, 6, '4839381', 'part4930', 'model-1', 'jsp', 'red', 3, 'Pieces', 'frame4839', 'eng0390', 'remarks1'),
(42, 6, '4839381', 'part4930', 'model-1', 'jsp', 'red', 3, 'Pieces', 'frame4839', 'eng0390', 'remarks1'),
(43, 6, '3920192', 'part3782', 'model-1', 'kel', 'blue', 3, 'Pieces', 'frame0192', 'eng3001', 'remarks2'),
(44, 6, '9102838', 'part0392', 'model-1', 'ker', 'red', 3, 'Pieces', 'frame3820', 'eng2839', 'remarks3'),
(45, 6, '2209189', 'part3322', 'model-2', 'jsp', 'red', 3, 'Pieces', 'frame9029', 'eng0549', 'remarks4'),
(46, 6, '5848900', 'part1111', 'model-2', 'kel', 'blue', 3, 'Pieces', 'framepo39', 'eng29mi', 'remarks5'),
(47, 6, '5930293', 'part4039', 'model-3', 'ker', 'black', 3, 'Pieces', 'frame9022', 'eng2111', 'remarks6'),
(48, 6, '9281982', 'part5402', 'model-3', 'jsp', 'red', 3, 'Pieces', 'frame3902', 'eng898', 'remarks7'),
(49, 7, '4839381', 'part4930', 'model-1', 'jsp', 'red', 3, 'Pieces', 'frame4839', 'eng0390', 'remarks1'),
(50, 7, '4839381', 'part4930', 'model-1', 'jsp', 'red', 3, 'Pieces', 'frame4839', 'eng0390', 'remarks1'),
(51, 7, '3920192', 'part3782', 'model-1', 'kel', 'blue', 3, 'Pieces', 'frame0192', 'eng3001', 'remarks2'),
(52, 7, '9102838', 'part0392', 'model-1', 'ker', 'red', 3, 'Pieces', 'frame3820', 'eng2839', 'remarks3'),
(53, 7, '2209189', 'part3322', 'model-2', 'jsp', 'red', 3, 'Pieces', 'frame9029', 'eng0549', 'remarks4'),
(54, 7, '5848900', 'part1111', 'model-2', 'kel', 'blue', 3, 'Pieces', 'framepo39', 'eng29mi', 'remarks5'),
(55, 7, '5930293', 'part4039', 'model-3', 'ker', 'black', 3, 'Pieces', 'frame9022', 'eng2111', 'remarks6'),
(56, 7, '9281982', 'part5402', 'model-3', 'jsp', 'red', 3, 'Pieces', 'frame3902', 'eng898', 'remarks7');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_requisitions`
--

CREATE TABLE `purchase_requisitions` (
  `KEY_ID` int(11) NOT NULL,
  `REQUISITION_DATE` date DEFAULT NULL,
  `SITE` text,
  `REQUESTER_NAME` text,
  `REQUESTER_DESIGNATION` text,
  `REQUESTER_DEPARTMENT` text,
  `APPROVED_BY` text,
  `SUPPLIER_CODE` text,
  `ENTERED_AT` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `purchase_requisitions`
--

INSERT INTO `purchase_requisitions` (`KEY_ID`, `REQUISITION_DATE`, `SITE`, `REQUESTER_NAME`, `REQUESTER_DESIGNATION`, `REQUESTER_DEPARTMENT`, `APPROVED_BY`, `SUPPLIER_CODE`, `ENTERED_AT`) VALUES
(1, '2018-07-23', 'warehouse', 'name', 'designation', 'dept', 'Mr.Fahim', '1', '2018-07-23 13:32:10'),
(2, '2018-09-01', 'new site', 'some name', 'somedesg', 'somedept', 'Mr.Anowar', '1', '2018-08-31 17:40:35');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_requisitions_parts`
--

CREATE TABLE `purchase_requisitions_parts` (
  `KEY_ID` int(11) NOT NULL,
  `ENTRY_REFERENCE_ID` int(11) NOT NULL,
  `PART_NUMBER` text,
  `PART_NAME` text,
  `MODEL` text,
  `COLOR_CODE` text,
  `COLOR_NAME` text,
  `QUANTITY` int(11) DEFAULT NULL,
  `UNIT` text,
  `REMARKS` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `purchase_requisitions_parts`
--

INSERT INTO `purchase_requisitions_parts` (`KEY_ID`, `ENTRY_REFERENCE_ID`, `PART_NUMBER`, `PART_NAME`, `MODEL`, `COLOR_CODE`, `COLOR_NAME`, `QUANTITY`, `UNIT`, `REMARKS`) VALUES
(1, 1, '4839380', 'part4932', 'model-1', 'jsp', 'red', 3, 'Pieces', 'some remarks'),
(2, 1, '4839389', 'part4930', 'model-1', 'jsp', 'red', 3, 'Pieces', 'some remarks'),
(3, 1, '3920192', 'part3782', 'model-1', 'kel', 'blue', 3, 'Pieces', 'some remarks'),
(4, 1, '9102838', 'part0392', 'model-1', 'ker', 'red', 3, 'Pieces', 'some remarks'),
(5, 1, '2209189', 'part3322', 'model-2', 'jsp', 'red', 3, 'Pieces', 'some remarks'),
(6, 1, '5848900', 'part1111', 'model-2', 'kel', 'blue', 3, 'Pieces', 'some remarks'),
(7, 1, '5930293', 'part4039', 'model-3', 'ker', 'black', 3, 'Pieces', 'some remarks'),
(8, 1, '9281982', 'part5402', 'model-3', 'jsp', 'red', 3, 'Pieces', 'some remarks'),
(9, 2, '83298', 'par039', 'model-1', '3333', 'black', 2, 'KG', 'some remarks');

-- --------------------------------------------------------

--
-- Table structure for table `returned_parts`
--

CREATE TABLE `returned_parts` (
  `KEY_ID` int(11) NOT NULL,
  `ENTRY_REFERENCE_ID` int(11) NOT NULL,
  `PART_NUMBER` text,
  `PART_NAME` text,
  `MODEL` text,
  `COLOR_CODE` text,
  `COLOR_NAME` text,
  `QUANTITY` int(11) DEFAULT NULL,
  `UNIT` text,
  `RETURN_REASON` text,
  `REMARKS` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `returned_parts`
--

INSERT INTO `returned_parts` (`KEY_ID`, `ENTRY_REFERENCE_ID`, `PART_NUMBER`, `PART_NAME`, `MODEL`, `COLOR_CODE`, `COLOR_NAME`, `QUANTITY`, `UNIT`, `RETURN_REASON`, `REMARKS`) VALUES
(1, 1, '2209189', 'part332', 'model-2', 'jsp', 'red', 4, 'Pieces', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `return_order`
--

CREATE TABLE `return_order` (
  `KEY_ID` int(11) NOT NULL,
  `RETURN_DATE` date DEFAULT NULL,
  `DELIVERY_CHALLAN_NUMBER` int(11) DEFAULT NULL,
  `CUSTOMER_CODE` int(11) DEFAULT NULL,
  `SALES_CHANNEL` text,
  `ENTERED_AT` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `return_order`
--

INSERT INTO `return_order` (`KEY_ID`, `RETURN_DATE`, `DELIVERY_CHALLAN_NUMBER`, `CUSTOMER_CODE`, `SALES_CHANNEL`, `ENTERED_AT`) VALUES
(1, '2018-07-25', 2, 1, 'Corporate', '2018-07-26 20:35:40');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `SUPPLIER_CODE` int(11) NOT NULL,
  `SUPPLIER_NAME` text,
  `SUPPLIER_ADDRESS` text,
  `SUPPLIER_CITY` text,
  `COUNTRY` text,
  `SUPPLIER_PHONE_OFFICE` text,
  `SUPPLIER_PHONE_MOBILE` text,
  `SUPPLIER_EMAIL` text,
  `SUPPLIER_FAX` text,
  `SUPPLIER_WEBSITE` text,
  `ENTERED_AT` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`SUPPLIER_CODE`, `SUPPLIER_NAME`, `SUPPLIER_ADDRESS`, `SUPPLIER_CITY`, `COUNTRY`, `SUPPLIER_PHONE_OFFICE`, `SUPPLIER_PHONE_MOBILE`, `SUPPLIER_EMAIL`, `SUPPLIER_FAX`, `SUPPLIER_WEBSITE`, `ENTERED_AT`) VALUES
(1, 'SMIPL', 'N/A', 'HARIYANA', 'India', '323', '43265775', '', '', '', '2018-07-22 03:31:58');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `USER_ID` int(11) NOT NULL,
  `USER_NAME` varchar(30) NOT NULL,
  `USER_PASSWORD` text NOT NULL,
  `USER_ACCESS_LEVEL` int(11) NOT NULL,
  `CREATED_BY` int(11) NOT NULL,
  `ENTERED_AT` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`USER_ID`, `USER_NAME`, `USER_PASSWORD`, `USER_ACCESS_LEVEL`, `CREATED_BY`, `ENTERED_AT`) VALUES
(1, 'super_admin', '9741ddc417cfa0e63dcdca43e56ef970bff74c6ae5356887712b4eec29247a1f4e0f278f79c91a5a61e50e73d3cebd6240f8d137f7df7f626e2309b16ea443b9', 3, 0, '2018-08-31 19:07:45');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `additional_delivery`
--
ALTER TABLE `additional_delivery`
  ADD PRIMARY KEY (`KEY_ID`);

--
-- Indexes for table `backup_delivery`
--
ALTER TABLE `backup_delivery`
  ADD PRIMARY KEY (`KEY_ID`);

--
-- Indexes for table `claims`
--
ALTER TABLE `claims`
  ADD PRIMARY KEY (`KEY_ID`);

--
-- Indexes for table `claims_parts`
--
ALTER TABLE `claims_parts`
  ADD PRIMARY KEY (`KEY_ID`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`CUSTOMER_ID`);

--
-- Indexes for table `delivery`
--
ALTER TABLE `delivery`
  ADD PRIMARY KEY (`KEY_ID`);

--
-- Indexes for table `delivery_parts`
--
ALTER TABLE `delivery_parts`
  ADD PRIMARY KEY (`KEY_ID`);

--
-- Indexes for table `entries`
--
ALTER TABLE `entries`
  ADD PRIMARY KEY (`KEY_ID`);

--
-- Indexes for table `issues`
--
ALTER TABLE `issues`
  ADD PRIMARY KEY (`KEY_ID`);

--
-- Indexes for table `issue_records`
--
ALTER TABLE `issue_records`
  ADD PRIMARY KEY (`KEY_ID`);

--
-- Indexes for table `parts`
--
ALTER TABLE `parts`
  ADD PRIMARY KEY (`KEY_ID`);

--
-- Indexes for table `purchase_requisitions`
--
ALTER TABLE `purchase_requisitions`
  ADD PRIMARY KEY (`KEY_ID`);

--
-- Indexes for table `purchase_requisitions_parts`
--
ALTER TABLE `purchase_requisitions_parts`
  ADD PRIMARY KEY (`KEY_ID`);

--
-- Indexes for table `returned_parts`
--
ALTER TABLE `returned_parts`
  ADD PRIMARY KEY (`KEY_ID`);

--
-- Indexes for table `return_order`
--
ALTER TABLE `return_order`
  ADD PRIMARY KEY (`KEY_ID`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`SUPPLIER_CODE`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`USER_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `additional_delivery`
--
ALTER TABLE `additional_delivery`
  MODIFY `KEY_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `backup_delivery`
--
ALTER TABLE `backup_delivery`
  MODIFY `KEY_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `claims`
--
ALTER TABLE `claims`
  MODIFY `KEY_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `claims_parts`
--
ALTER TABLE `claims_parts`
  MODIFY `KEY_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `CUSTOMER_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `delivery`
--
ALTER TABLE `delivery`
  MODIFY `KEY_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `delivery_parts`
--
ALTER TABLE `delivery_parts`
  MODIFY `KEY_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `entries`
--
ALTER TABLE `entries`
  MODIFY `KEY_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `issues`
--
ALTER TABLE `issues`
  MODIFY `KEY_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `issue_records`
--
ALTER TABLE `issue_records`
  MODIFY `KEY_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `parts`
--
ALTER TABLE `parts`
  MODIFY `KEY_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `purchase_requisitions`
--
ALTER TABLE `purchase_requisitions`
  MODIFY `KEY_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `purchase_requisitions_parts`
--
ALTER TABLE `purchase_requisitions_parts`
  MODIFY `KEY_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `returned_parts`
--
ALTER TABLE `returned_parts`
  MODIFY `KEY_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `return_order`
--
ALTER TABLE `return_order`
  MODIFY `KEY_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `SUPPLIER_CODE` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `USER_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
