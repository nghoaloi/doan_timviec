-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 06, 2024 at 04:27 AM
-- Server version: 8.2.0
-- PHP Version: 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@CaOLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u77312933_timviec`
--

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

DROP TABLE IF EXISTS `applications`;
CREATE TABLE IF NOT EXISTS `applications` (
  `ApplicationID` int NOT NULL AUTO_INCREMENT,
  `JobID` int NOT NULL,
  `UserID` int NOT NULL,
  `ResumeURL` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `CoverLetter` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `ApplicationStatus` enum('Pending','Reviewed','Accepted','Rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'Pending',
  `AppliedAt` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ApplicationID`),
  KEY `JobID` (`JobID`),
  KEY `UserID` (`UserID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`ApplicationID`, `JobID`, `UserID`, `ResumeURL`, `CoverLetter`, `ApplicationStatus`, `AppliedAt`) VALUES
(1, 1, 3, NULL, NULL, 'Pending', '2024-12-01 04:26:17');

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

DROP TABLE IF EXISTS `companies`;
CREATE TABLE IF NOT EXISTS `companies` (
  `CompanyID` int NOT NULL AUTO_INCREMENT,
  `UserID` int NOT NULL,
  `CompanyName` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Industry` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `WebsiteURL` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `LogoURL` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `CreatedAt` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`CompanyID`),
  KEY `UserID` (`UserID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`CompanyID`, `UserID`, `CompanyName`, `Industry`, `WebsiteURL`, `LogoURL`, `Location`, `Description`, `CreatedAt`) VALUES
(1, 2, 'trách nhiệm hữu hạn 2 thành viên ', NULL, NULL, NULL, NULL, NULL, '2024-12-01 03:18:43');

-- --------------------------------------------------------

--
-- Table structure for table `jobcategories`
--

DROP TABLE IF EXISTS `jobcategories`;
CREATE TABLE IF NOT EXISTS `jobcategories` (
  `CategoryID` int NOT NULL AUTO_INCREMENT,
  `CategoryName` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`CategoryID`),
  UNIQUE KEY `CategoryName` (`CategoryName`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jobcategories`
--

INSERT INTO `jobcategories` (`CategoryID`, `CategoryName`) VALUES
(3, 'Bán hàng & Tiếp thị'),
(6, 'công nghệ thông tin'),
(2, 'Thiết kế & Phát triển'),
(1, 'Thiết kế & Sáng tạo'),
(4, 'Ứng dụng di động'),
(5, 'Xây dựng');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE IF NOT EXISTS `jobs` (
  `JobID` int NOT NULL AUTO_INCREMENT,
  `CompanyID` int NOT NULL,
  `JobTitle` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `JobDescription` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Requirements` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `SalaryRange` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `EmploymentType` enum('Full-Time','Part-Time','Internship','Freelance') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `PostedDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `ExpiryDate` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`JobID`),
  KEY `CompanyID` (`CompanyID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`JobID`, `CompanyID`, `JobTitle`, `JobDescription`, `Requirements`, `SalaryRange`, `Location`, `EmploymentType`, `PostedDate`, `ExpiryDate`) VALUES
(1, 1, 'lập trình viên', 'lập trình web cho công ty\r\n', 'có kỹ năng với laravel \r\ncó bằng tiếng anh là điểm cộng\r\ncó kỹ năng làm việc nhóm', 'từ 7000000 đến 10000000', NULL, '', '2024-12-01 04:18:21', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `job_category_relationship`
--

DROP TABLE IF EXISTS `job_category_relationship`;
CREATE TABLE IF NOT EXISTS `job_category_relationship` (
  `JobID` int NOT NULL,
  `CategoryID` int NOT NULL,
  PRIMARY KEY (`JobID`,`CategoryID`),
  KEY `CategoryID` (`CategoryID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `job_category_relationship`
--

INSERT INTO `job_category_relationship` (`JobID`, `CategoryID`) VALUES
(1, 6);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
CREATE TABLE IF NOT EXISTS `reviews` (
  `ReviewID` int NOT NULL AUTO_INCREMENT,
  `CompanyID` int NOT NULL,
  `UserID` int NOT NULL,
  `Rating` int DEFAULT NULL,
  `ReviewText` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `CreatedAt` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ReviewID`),
  KEY `CompanyID` (`CompanyID`),
  KEY `UserID` (`UserID`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`ReviewID`, `CompanyID`, `UserID`, `Rating`, `ReviewText`, `CreatedAt`) VALUES
(1, 1, 3, NULL, NULL, '2024-12-01 04:26:43');

-- --------------------------------------------------------

--
-- Table structure for table `savedjobs`
--

DROP TABLE IF EXISTS `savedjobs`;
CREATE TABLE IF NOT EXISTS `savedjobs` (
  `SavedJobID` int NOT NULL AUTO_INCREMENT,
  `UserID` int NOT NULL,
  `JobID` int NOT NULL,
  `SavedAt` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`SavedJobID`),
  KEY `UserID` (`UserID`),
  KEY `JobID` (`JobID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `savedjobs`
--

INSERT INTO `savedjobs` (`SavedJobID`, `UserID`, `JobID`, `SavedAt`) VALUES
(1, 3, 1, '2024-12-01 04:26:32');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `UserID` int NOT NULL AUTO_INCREMENT,
  `FullName` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `PasswordHash` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `UserType` enum('Admin','Candidate','Employer') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Candidate',
  `PhoneNumber` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ProfilePictureURL` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `CreatedAt` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdatedAt` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `DateOfBirth` date DEFAULT NULL,
  `Address` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Gender` enum('Male','Female','Other') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `UserStatus` enum('Active','Inactive','Banned') COLLATE utf8mb4_general_ci DEFAULT 'Active',
  `LastLogin` timestamp NULL DEFAULT NULL,
  `Bio` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`UserID`),
  UNIQUE KEY `Email` (`Email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `FullName`, `Email`, `PasswordHash`, `UserType`, `PhoneNumber`, `ProfilePictureURL`, `CreatedAt`, `UpdatedAt`, `DateOfBirth`, `Address`, `Gender`, `UserStatus`, `LastLogin`, `Bio`) VALUES
(1, 'admin', 'admin@gmail.com', 'admin', 'Admin', NULL, NULL, '2024-12-01 02:52:13', '2024-12-01 02:52:13', NULL, NULL, NULL, 'Active', NULL, NULL),
(2, 'công ty tnhh 2 thành viên', 'congty@gmail.com', '123456789', 'Employer', NULL, NULL, '2024-12-01 03:12:56', '2024-12-01 03:12:56', NULL, NULL, NULL, 'Active', NULL, NULL),
(3, 'nguyễn hòa lợi', 'hoaloi@gmail.com', '123456789', 'Candidate', NULL, NULL, '2024-12-01 03:12:56', '2024-12-01 03:45:19', NULL, NULL, NULL, 'Active', NULL, NULL),
(4, '', 'user3@gmail.com', '$2y$10$nkfCLphofTFbkfoBgfyxo.8MW1Rfwem0p6uNSLNhOmJk8CzoTuABC', 'Employer', NULL, NULL, '2024-12-05 14:43:50', '2024-12-05 14:43:50', NULL, NULL, NULL, 'Active', NULL, NULL);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `applications_ibfk_1` FOREIGN KEY (`JobID`) REFERENCES `jobs` (`JobID`) ON DELETE CASCADE,
  ADD CONSTRAINT `applications_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE;

--
-- Constraints for table `companies`
--
ALTER TABLE `companies`
  ADD CONSTRAINT `companies_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE;

--
-- Constraints for table `jobs`
--
ALTER TABLE `jobs`
  ADD CONSTRAINT `jobs_ibfk_1` FOREIGN KEY (`CompanyID`) REFERENCES `companies` (`CompanyID`) ON DELETE CASCADE;

--
-- Constraints for table `job_category_relationship`
--
ALTER TABLE `job_category_relationship`
  ADD CONSTRAINT `job_category_relationship_ibfk_1` FOREIGN KEY (`JobID`) REFERENCES `jobs` (`JobID`) ON DELETE CASCADE,
  ADD CONSTRAINT `job_category_relationship_ibfk_2` FOREIGN KEY (`CategoryID`) REFERENCES `jobcategories` (`CategoryID`) ON DELETE CASCADE;

--
-- Constraints for table `savedjobs`
--
ALTER TABLE `savedjobs`
  ADD CONSTRAINT `savedjobs_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE,
  ADD CONSTRAINT `savedjobs_ibfk_2` FOREIGN KEY (`JobID`) REFERENCES `jobs` (`JobID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
