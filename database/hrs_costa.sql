-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 21, 2024 at 03:12 PM
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
  `userId` bigint(10) NOT NULL,
  `expirationDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `checkins`
--

CREATE TABLE `checkins` (
  `Id` bigint(10) NOT NULL,
  `roomId` bigint(10) NOT NULL,
  `checkInDate` date NOT NULL,
  `checkOutDate` date NOT NULL,
  `paidAmount` float NOT NULL,
  `userId` bigint(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `created_from_facebook`
--

CREATE TABLE `created_from_facebook` (
  `Id` bigint(10) NOT NULL,
  `userId` bigint(10) NOT NULL,
  `fbUserId` char(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `created_from_facebook`
--

INSERT INTO `created_from_facebook` (`Id`, `userId`, `fbUserId`) VALUES
(85, 49, '2152625611741250');

-- --------------------------------------------------------

--
-- Table structure for table `created_from_google`
--

CREATE TABLE `created_from_google` (
  `Id` bigint(10) NOT NULL,
  `userId` bigint(10) NOT NULL,
  `googleUserId` char(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `created_from_google`
--

INSERT INTO `created_from_google` (`Id`, `userId`, `googleUserId`) VALUES
(1, 50, '111101523218204530048');

-- --------------------------------------------------------

--
-- Table structure for table `on_queue_rooms`
--

CREATE TABLE `on_queue_rooms` (
  `Id` bigint(10) NOT NULL,
  `roomId` bigint(10) NOT NULL,
  `queueEndDateTime` datetime NOT NULL,
  `checkInDate` date NOT NULL,
  `checkOutDate` date NOT NULL,
  `initalPay` float NOT NULL,
  `userId` bigint(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `other_rate`
--

CREATE TABLE `other_rate` (
  `Id` bigint(10) NOT NULL,
  `type` varchar(225) NOT NULL,
  `roomId` bigint(10) NOT NULL,
  `amount` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `other_rate`
--

INSERT INTO `other_rate` (`Id`, `type`, `roomId`, `amount`) VALUES
(2, 'Valentines Sale', 18, 1500);

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
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `Id` bigint(10) NOT NULL,
  `name` varchar(225) NOT NULL,
  `maximum` int(10) NOT NULL,
  `description` longtext NOT NULL,
  `originalRate` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`Id`, `name`, `maximum`, `description`, `originalRate`) VALUES
(18, 'Delux', 3, 'Test Room', 3000);

-- --------------------------------------------------------

--
-- Table structure for table `room_image`
--

CREATE TABLE `room_image` (
  `Id` bigint(10) NOT NULL,
  `Link` varchar(255) NOT NULL,
  `roomId` bigint(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room_image`
--

INSERT INTO `room_image` (`Id`, `Link`, `roomId`) VALUES
(25, 'https://firebasestorage.googleapis.com/v0/b/hrs-costa.appspot.com/o/images%2F1%20(2).jpg?alt=media&token=7335cacf-6d4c-4651-aa8f-d170b2ff76e8', 18);

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
(49, 'Geykson Maravillas', 'matildogeykson@gmail.com', 'dHl1K0NwYXB2d3Z3QVZaQkhxSDhoWU1mSTdYSWd6VVVuWVZaVVhRcm1BMD0=', 'admin', '2024-04-28', '2024-04-28'),
(50, 'Geykson Maravillas', 'jeykson.maravillas@gmail.com', 'akhnVXpsTVI3MEVkMFVwK3V3amN5NEoybFJwZ2ZFSFVQTHhDQzlneDRvYz0=', 'customer', '2024-05-07', '2024-05-07'),
(64, 'Geykson', 'geykson@alphasys.com.au', 'Vld4TG5aclZkcWo5cmp1N3NuOUozdz09', 'customer', '2024-05-20', '2024-05-20'),
(65, 'Adornarenzmarion', 'adornarenzmarion@gmail.com', 'Vld4TG5aclZkcWo5cmp1N3NuOUozdz09', 'customer', '2024-05-21', '2024-05-21');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `access_token`
--
ALTER TABLE `access_token`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `tokken` (`token`),
  ADD KEY `userCorrespondingToken` (`userId`);

--
-- Indexes for table `checkins`
--
ALTER TABLE `checkins`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `roomId` (`roomId`),
  ADD KEY `userId` (`userId`);

--
-- Indexes for table `created_from_facebook`
--
ALTER TABLE `created_from_facebook`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `userId` (`userId`),
  ADD UNIQUE KEY `fbUserId` (`fbUserId`);

--
-- Indexes for table `created_from_google`
--
ALTER TABLE `created_from_google`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `userId` (`userId`);

--
-- Indexes for table `on_queue_rooms`
--
ALTER TABLE `on_queue_rooms`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `roomId` (`roomId`),
  ADD KEY `userId` (`userId`);

--
-- Indexes for table `other_rate`
--
ALTER TABLE `other_rate`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `roomId` (`roomId`);

--
-- Indexes for table `personal_information`
--
ALTER TABLE `personal_information`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `userId` (`userId`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `room_image`
--
ALTER TABLE `room_image`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `roomId` (`roomId`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `access_token`
--
ALTER TABLE `access_token`
  MODIFY `Id` bigint(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT for table `checkins`
--
ALTER TABLE `checkins`
  MODIFY `Id` bigint(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `created_from_facebook`
--
ALTER TABLE `created_from_facebook`
  MODIFY `Id` bigint(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `created_from_google`
--
ALTER TABLE `created_from_google`
  MODIFY `Id` bigint(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `on_queue_rooms`
--
ALTER TABLE `on_queue_rooms`
  MODIFY `Id` bigint(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `other_rate`
--
ALTER TABLE `other_rate`
  MODIFY `Id` bigint(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `personal_information`
--
ALTER TABLE `personal_information`
  MODIFY `Id` bigint(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `Id` bigint(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `room_image`
--
ALTER TABLE `room_image`
  MODIFY `Id` bigint(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` bigint(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `access_token`
--
ALTER TABLE `access_token`
  ADD CONSTRAINT `userCorrespondingToken` FOREIGN KEY (`userId`) REFERENCES `user` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `checkins`
--
ALTER TABLE `checkins`
  ADD CONSTRAINT `checkedInCorrespondingRoomId` FOREIGN KEY (`roomId`) REFERENCES `rooms` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `checkedInCorrespondingUserId` FOREIGN KEY (`userId`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `created_from_facebook`
--
ALTER TABLE `created_from_facebook`
  ADD CONSTRAINT `fbCorresponsingUserId` FOREIGN KEY (`userId`) REFERENCES `user` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `created_from_google`
--
ALTER TABLE `created_from_google`
  ADD CONSTRAINT `googleCorresponsingUserId` FOREIGN KEY (`userId`) REFERENCES `user` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `other_rate`
--
ALTER TABLE `other_rate`
  ADD CONSTRAINT `rateCorresponsingRoomId` FOREIGN KEY (`roomId`) REFERENCES `rooms` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `room_image`
--
ALTER TABLE `room_image`
  ADD CONSTRAINT `ImageCorresponsingRoomId` FOREIGN KEY (`roomId`) REFERENCES `rooms` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
