-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 21, 2024 at 05:15 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hrs_costa`
--

-- --------------------------------------------------------

--
-- Table structure for table `access_token`
--

CREATE TABLE `access_token` (
  `Id` bigint(10) NOT NULL,
  `token` varchar(225) NOT NULL,
  `userId` bigint(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `access_token`
--

INSERT INTO `access_token` (`Id`, `token`, `userId`) VALUES
(1, 'XmLUWctVYZ7eGgQYzIQ2avsZiILOlHH7PbrMY68hU8X7bmK4o4', 3);

-- --------------------------------------------------------

--
-- Table structure for table `created_from_facebook`
--

CREATE TABLE `created_from_facebook` (
  `Id` bigint(10) NOT NULL,
  `userId` bigint(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `created_from_google`
--

CREATE TABLE `created_from_google` (
  `Id` bigint(10) NOT NULL,
  `userId` bigint(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_information`
--

CREATE TABLE `personal_information` (
  `Id` bigint(10) NOT NULL,
  `firstName` varchar(225) NOT NULL,
  `lastName` varchar(225) NOT NULL,
  `userId` bigint(10) NOT NULL,
  `age` bigint(10) NOT NULL,
  `address` varchar(225) NOT NULL,
  `mobileNumber` char(12) NOT NULL,
  `dateOfBirth` date NOT NULL,
  `createdDate` date NOT NULL DEFAULT current_timestamp(),
  `lastModifiedDate` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` bigint(10) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(225) NOT NULL,
  `role` varchar(50) NOT NULL,
  `createdDate` date NOT NULL DEFAULT current_timestamp(),
  `lastModifiedDate` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `email`, `password`, `role`, `createdDate`, `lastModifiedDate`) VALUES
(3, 'Jeykson Maravillas', 'jeykson.maravillas@gmail.com', 'aUdscUhmS0FLekRIRzFrU0lGTFNQQT09', 'customer', '2024-04-21', '2024-04-21');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `access_token`
--
ALTER TABLE `access_token`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `tokken` (`token`),
  ADD UNIQUE KEY `userId` (`userId`);

--
-- Indexes for table `created_from_facebook`
--
ALTER TABLE `created_from_facebook`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `userId` (`userId`);

--
-- Indexes for table `created_from_google`
--
ALTER TABLE `created_from_google`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `userId` (`userId`);

--
-- Indexes for table `personal_information`
--
ALTER TABLE `personal_information`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `userId` (`userId`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `password` (`password`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `access_token`
--
ALTER TABLE `access_token`
  MODIFY `Id` bigint(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `created_from_facebook`
--
ALTER TABLE `created_from_facebook`
  MODIFY `Id` bigint(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `created_from_google`
--
ALTER TABLE `created_from_google`
  MODIFY `Id` bigint(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_information`
--
ALTER TABLE `personal_information`
  MODIFY `Id` bigint(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` bigint(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
