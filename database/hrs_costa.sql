-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 29, 2024 at 08:27 PM
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
-- Table structure for table `amenity`
--

CREATE TABLE `amenity` (
  `Id` bigint(10) NOT NULL,
  `amenityName` varchar(255) NOT NULL,
  `roomId` bigint(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `check_ins`
--

CREATE TABLE `check_ins` (
  `Id` bigint(10) NOT NULL,
  `roomId` bigint(10) NOT NULL,
  `checkInDate` date NOT NULL,
  `checkOutDate` date NOT NULL,
  `paidAmount` float NOT NULL,
  `userId` bigint(10) NOT NULL,
  `queueDateTime` datetime NOT NULL,
  `status` varchar(255) NOT NULL,
  `checkInQuantity` bigint(10) NOT NULL,
  `paymentMethodId` bigint(10) NOT NULL,
  `totalAmount` float NOT NULL,
  `customerfullName` varchar(255) NOT NULL,
  `customerCompleteAddress` varchar(255) NOT NULL,
  `customerContactInfo` varchar(255) NOT NULL
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

-- --------------------------------------------------------

--
-- Table structure for table `created_from_google`
--

CREATE TABLE `created_from_google` (
  `Id` bigint(10) NOT NULL,
  `userId` bigint(10) NOT NULL,
  `googleUserId` char(225) NOT NULL
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

-- --------------------------------------------------------

--
-- Table structure for table `payment_evidence`
--

CREATE TABLE `payment_evidence` (
  `Id` bigint(10) NOT NULL,
  `Link` varchar(255) NOT NULL,
  `checkInId` bigint(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

CREATE TABLE `payment_methods` (
  `Id` bigint(10) NOT NULL,
  `paymentMethodName` varchar(255) NOT NULL,
  `qrLink` varchar(255) NOT NULL,
  `paymentNumber` char(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment_methods`
--

INSERT INTO `payment_methods` (`Id`, `paymentMethodName`, `qrLink`, `paymentNumber`) VALUES
(1, 'Gcash', 'https://firebasestorage.googleapis.com/v0/b/hrs-costa.appspot.com/o/system%20images%2F1200px-QR_Code_Example.svg.png?alt=media&token=9030b11c-f9dc-405a-8ae0-d2e3070a4cf6', '+639542568956'),
(2, 'Palawan', 'https://firebasestorage.googleapis.com/v0/b/hrs-costa.appspot.com/o/system%20images%2FExample-QR-code.webp?alt=media&token=721e322f-a9ca-410a-9cf6-c793e081a278', '+639256455892'),
(3, 'Maya', 'https://firebasestorage.googleapis.com/v0/b/hrs-costa.appspot.com/o/system%20images%2FQR_code_for_mobile_English_Wikipedia.svg?alt=media&token=8f208533-a12c-4ccb-9414-66134f1294d0', '+639856265623'),
(4, 'Manual', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `Id` bigint(10) NOT NULL,
  `name` varchar(225) NOT NULL,
  `maximum` int(10) NOT NULL,
  `description` longtext NOT NULL,
  `originalRate` float NOT NULL,
  `quantity` bigint(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `room_image`
--

CREATE TABLE `room_image` (
  `Id` bigint(10) NOT NULL,
  `Link` varchar(255) NOT NULL,
  `roomId` bigint(10) NOT NULL
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
-- Indexes for table `amenity`
--
ALTER TABLE `amenity`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `roomId` (`roomId`);

--
-- Indexes for table `check_ins`
--
ALTER TABLE `check_ins`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `roomId` (`roomId`),
  ADD KEY `userId` (`userId`),
  ADD KEY `paymentMethodId` (`paymentMethodId`);

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
-- Indexes for table `other_rate`
--
ALTER TABLE `other_rate`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `roomId` (`roomId`);

--
-- Indexes for table `payment_evidence`
--
ALTER TABLE `payment_evidence`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `checkInId` (`checkInId`);

--
-- Indexes for table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `paymentMethodName` (`paymentMethodName`);

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
  MODIFY `Id` bigint(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=127;

--
-- AUTO_INCREMENT for table `amenity`
--
ALTER TABLE `amenity`
  MODIFY `Id` bigint(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `check_ins`
--
ALTER TABLE `check_ins`
  MODIFY `Id` bigint(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `created_from_facebook`
--
ALTER TABLE `created_from_facebook`
  MODIFY `Id` bigint(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT for table `created_from_google`
--
ALTER TABLE `created_from_google`
  MODIFY `Id` bigint(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `other_rate`
--
ALTER TABLE `other_rate`
  MODIFY `Id` bigint(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `payment_evidence`
--
ALTER TABLE `payment_evidence`
  MODIFY `Id` bigint(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `Id` bigint(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `Id` bigint(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `room_image`
--
ALTER TABLE `room_image`
  MODIFY `Id` bigint(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` bigint(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `access_token`
--
ALTER TABLE `access_token`
  ADD CONSTRAINT `userCorrespondingToken` FOREIGN KEY (`userId`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `amenity`
--
ALTER TABLE `amenity`
  ADD CONSTRAINT `amenityCorrespondingRoomId` FOREIGN KEY (`roomId`) REFERENCES `rooms` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `check_ins`
--
ALTER TABLE `check_ins`
  ADD CONSTRAINT `checkedInCorrespondingPaymentMethodId` FOREIGN KEY (`paymentMethodId`) REFERENCES `payment_methods` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `checkedInCorrespondingRoomId` FOREIGN KEY (`roomId`) REFERENCES `rooms` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `checkedInCorrespondingUserId` FOREIGN KEY (`userId`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `created_from_facebook`
--
ALTER TABLE `created_from_facebook`
  ADD CONSTRAINT `fbCorresponsingUserId` FOREIGN KEY (`userId`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `created_from_google`
--
ALTER TABLE `created_from_google`
  ADD CONSTRAINT `googleCorresponsingUserId` FOREIGN KEY (`userId`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `other_rate`
--
ALTER TABLE `other_rate`
  ADD CONSTRAINT `rateCorresponsingRoomId` FOREIGN KEY (`roomId`) REFERENCES `rooms` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payment_evidence`
--
ALTER TABLE `payment_evidence`
  ADD CONSTRAINT `paymentEvidenceCorrespondingChekInId` FOREIGN KEY (`checkInId`) REFERENCES `check_ins` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `room_image`
--
ALTER TABLE `room_image`
  ADD CONSTRAINT `ImageCorresponsingRoomId` FOREIGN KEY (`roomId`) REFERENCES `rooms` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
