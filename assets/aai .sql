-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 18, 2024 at 09:48 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `aai`
--

-- --------------------------------------------------------

--
-- Table structure for table `approval_level`
--

CREATE TABLE `approval_level` (
  `application_id` int(11) DEFAULT NULL,
  `contractor_id` int(11) DEFAULT NULL,
  `contractor_approve_time` datetime DEFAULT NULL,
  `manager_id` int(11) DEFAULT NULL,
  `manager_approve_time` datetime DEFAULT NULL,
  `clerk_id` int(11) DEFAULT NULL,
  `clerk_approve_time` datetime DEFAULT NULL,
  `incharge_id` int(11) DEFAULT NULL,
  `incharge_approve_time` datetime DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `rejected_by_role` varchar(50) NOT NULL,
  `rejected_by_id` int(10) NOT NULL,
  `rejected_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `approval_level`
--

-- --------------------------------------------------------

--
-- Table structure for table `areas`
--

CREATE TABLE `areas` (
  `area_id` int(11) NOT NULL,
  `area_name` varchar(255) NOT NULL,
  `reg_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `areas`
--

INSERT INTO `areas` (`area_id`, `area_name`, `reg_time`) VALUES
(1, 'Arrival Hall', '2024-05-12 08:48:13'),
(2, 'ATC Tower', '2024-05-12 08:48:19'),
(3, 'Apron Area', '2024-05-12 08:48:26'),
(4, 'Air Traffic Control expect ATC Tower', '2024-05-12 08:48:32'),
(5, 'Baggage Handling Area', '2024-05-12 08:48:40'),
(6, 'Boarding Gates to Immigration/ Baggage Claim area', '2024-05-12 11:43:11'),
(7, 'Cargo Terminal without terminal Cargo SHA- Domestic &amp; International', '2024-05-12 11:43:18'),
(8, 'Cargo Terminal without terminal Cargo SHA- Domestic', '2024-05-12 11:43:25'),
(9, 'Cargo Terminal without terminal Cargo SHA- Intl', '2024-05-12 11:43:31'),
(10, 'Cargo SHA Pertaining to C or Cd or Ci', '2024-05-12 11:43:36'),
(11, 'Departure Hall', '2024-05-12 11:43:41'),
(12, 'Terminal Building other then Security Hold, Customs and immigration but including Baggage Claim area of Domestic Terminal', '2024-05-12 11:43:47'),
(13, 'Terminal Building Security Hall Hold Area', '2024-05-12 11:43:55');

-- --------------------------------------------------------

--
-- Table structure for table `contractors`
--

CREATE TABLE `contractors` (
  `contractor_id` int(11) NOT NULL,
  `contract_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `reg_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contractors`
--

INSERT INTO `contractors` (`contractor_id`, `contract_id`, `name`, `email`, `password`, `reg_time`) VALUES
(0, 101, 'test contractor', 'testcontractor@gmail.com', 'test', '2024-05-13 20:54:56'),
(1011, 101, 'raj sonawane', 'raj@gmail.com', 'raj', '2024-05-13 20:09:37'),
(1012, 101, 'rahul ahire', 'prasad.dhobale@mitaoe.ac.in', 'rahul', '2024-05-13 20:11:20'),
(1021, 102, 'Shrikant', 'shri@gmail.com', 'shri', '2024-05-13 20:12:12');

-- --------------------------------------------------------

--
-- Table structure for table `contracts`
--

CREATE TABLE `contracts` (
  `contract_id` int(11) NOT NULL,
  `contract_name` varchar(255) NOT NULL,
  `reg_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contracts`
--

INSERT INTO `contracts` (`contract_id`, `contract_name`, `reg_time`) VALUES
(101, 'Evolve Technologies', '2024-05-13 17:11:29'),
(102, 'Linkcode Worlds', '2024-05-13 17:11:29'),
(103, 'CompTech Solutions Pvt Ltd', '2024-05-13 17:23:05'),
(104, 'Spyinte Services Pvt Ltd', '2024-05-13 17:23:05');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `department_id` int(11) NOT NULL,
  `department_name` varchar(255) NOT NULL,
  `reg_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`department_id`, `department_name`, `reg_time`) VALUES
(1001, 'HR', '2024-05-12 05:44:39'),
(1002, 'CNS', '2024-05-13 17:23:38'),
(1004, 'Clerk', '2024-05-18 19:17:44');

-- --------------------------------------------------------

--
-- Table structure for table `managers`
--

CREATE TABLE `managers` (
  `manager_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `dept_id` int(11) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `reg_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `managers`
--

INSERT INTO `managers` (`manager_id`, `first_name`, `last_name`, `phone`, `dept_id`, `email`, `password`, `reg_time`) VALUES
(0, 'test', 'username', '9309583043', 1001, 'testmanager@gmail.com', 'manager', '2024-05-13 18:54:39'),
(10011, 'Swapnil', 'J', '9845738477', 1001, 'swapnil@gmail.com', 'swap', '2024-05-13 19:31:18'),
(10012, 'Akshay', 'M', '7347238232', 1001, 'akshay@gmail.com', 'akshay', '2024-05-13 19:31:55'),
(10013, 'Abhijeet', 'Vyavhare', '9067404012', 1004, 'abhi@gmail.com', 'abhi', '2024-05-18 19:19:25');

-- --------------------------------------------------------

--
-- Table structure for table `pass_applications`
--

CREATE TABLE `pass_applications` (
  `application_id` int(11) NOT NULL,
  `pass_type` enum('new','renew') NOT NULL,
  `pass_fees` enum('fees','noFees') NOT NULL,
  `name` varchar(100) NOT NULL,
  `sdw` varchar(100) NOT NULL,
  `designation` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `company_id` varchar(100) DEFAULT NULL,
  `identity` varchar(20) NOT NULL,
  `upload_id` mediumblob NOT NULL,
  `purpose_of_visit` text NOT NULL,
  `from_timestamp` datetime NOT NULL,
  `to_timestamp` datetime NOT NULL,
  `police_clearance` enum('yes','no') NOT NULL,
  `upload_clearance` mediumblob DEFAULT NULL,
  `document_number` varchar(25) DEFAULT NULL,
  `issue_date` date DEFAULT NULL,
  `contract_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `areaOfVisit` varchar(50) DEFAULT NULL,
  `apply_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pass_applications`
--


--
-- Indexes for dumped tables
--

--
-- Indexes for table `approval_level`
--
ALTER TABLE `approval_level`
  ADD KEY `application_id` (`application_id`),
  ADD KEY `contractor_id` (`contractor_id`),
  ADD KEY `manager_id` (`manager_id`),
  ADD KEY `clerk_id` (`clerk_id`),
  ADD KEY `incharge_id` (`incharge_id`);

--
-- Indexes for table `areas`
--
ALTER TABLE `areas`
  ADD PRIMARY KEY (`area_id`);

--
-- Indexes for table `contractors`
--
ALTER TABLE `contractors`
  ADD PRIMARY KEY (`contractor_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_contract_id` (`contract_id`);

--
-- Indexes for table `contracts`
--
ALTER TABLE `contracts`
  ADD PRIMARY KEY (`contract_id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`department_id`);

--
-- Indexes for table `managers`
--
ALTER TABLE `managers`
  ADD PRIMARY KEY (`manager_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `dept_id` (`dept_id`);

--
-- Indexes for table `pass_applications`
--
ALTER TABLE `pass_applications`
  ADD PRIMARY KEY (`application_id`),
  ADD KEY `contract_id` (`contract_id`),
  ADD KEY `department_id` (`department_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `areas`
--
ALTER TABLE `areas`
  MODIFY `area_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `contractors`
--
ALTER TABLE `contractors`
  MODIFY `contractor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1022;

--
-- AUTO_INCREMENT for table `contracts`
--
ALTER TABLE `contracts`
  MODIFY `contract_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1005;

--
-- AUTO_INCREMENT for table `managers`
--
ALTER TABLE `managers`
  MODIFY `manager_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10014;

--
-- AUTO_INCREMENT for table `pass_applications`
--
ALTER TABLE `pass_applications`
  MODIFY `application_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8931;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `approval_level`
--
ALTER TABLE `approval_level`
  ADD CONSTRAINT `approval_level_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `pass_applications` (`application_id`),
  ADD CONSTRAINT `approval_level_ibfk_2` FOREIGN KEY (`contractor_id`) REFERENCES `contractors` (`contractor_id`),
  ADD CONSTRAINT `approval_level_ibfk_3` FOREIGN KEY (`manager_id`) REFERENCES `managers` (`manager_id`),
  ADD CONSTRAINT `approval_level_ibfk_4` FOREIGN KEY (`clerk_id`) REFERENCES `managers` (`manager_id`),
  ADD CONSTRAINT `approval_level_ibfk_5` FOREIGN KEY (`incharge_id`) REFERENCES `managers` (`manager_id`);

--
-- Constraints for table `contractors`
--
ALTER TABLE `contractors`
  ADD CONSTRAINT `fk_contract_id` FOREIGN KEY (`contract_id`) REFERENCES `contracts` (`contract_id`);

--
-- Constraints for table `managers`
--
ALTER TABLE `managers`
  ADD CONSTRAINT `managers_ibfk_1` FOREIGN KEY (`dept_id`) REFERENCES `departments` (`department_id`);

--
-- Constraints for table `pass_applications`
--
ALTER TABLE `pass_applications`
  ADD CONSTRAINT `pass_applications_ibfk_1` FOREIGN KEY (`contract_id`) REFERENCES `contracts` (`contract_id`),
  ADD CONSTRAINT `pass_applications_ibfk_2` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
