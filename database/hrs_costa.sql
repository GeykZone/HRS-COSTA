-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 28, 2024 at 05:40 PM
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

--
-- Dumping data for table `access_token`
--

INSERT INTO `access_token` (`Id`, `token`, `userId`, `expirationDate`) VALUES
(118, '8IeJGnh5fTV7WMNwR8pYJdxH6GiF1ovkqDSrJ2iqbpZGdoE20E', 71, '2024-06-26');

-- --------------------------------------------------------

--
-- Table structure for table `amenity`
--

CREATE TABLE `amenity` (
  `Id` bigint(10) NOT NULL,
  `amenityName` varchar(255) NOT NULL,
  `roomId` bigint(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `amenity`
--

INSERT INTO `amenity` (`Id`, `amenityName`, `roomId`) VALUES
(1, 'Comfort Room', 26),
(2, 'Flat Screen Tv', 26),
(3, 'Single Bed', 27),
(4, 'Bed', 28),
(5, 'Aircon', 28),
(6, 'TV', 29),
(7, 'Aircon', 29),
(8, '3 Beds', 29),
(9, '3 Beds', 30),
(10, 'TV', 30),
(11, '2 CR', 30),
(12, 'AIrcon', 31),
(13, '5 Beds', 31),
(14, '2 CR', 31);

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
  `totalAmount` float NOT NULL
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
(87, 70, '2152625611741250');

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
(4, 71, '111101523218204530048');

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
(4, 'Promo Rate', 26, 1500),
(5, 'Promo Rate', 27, 999),
(6, 'Promo Rate', 28, 2500),
(7, 'Holiday Rate', 28, 1500),
(8, 'Promo Rate', 30, 1150),
(9, 'Flash Rate', 30, 1000),
(10, 'Flash Sale', 31, 4000),
(11, 'Holiday Sale', 31, 3500);

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
  `originalRate` float NOT NULL,
  `quantity` bigint(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`Id`, `name`, `maximum`, `description`, `originalRate`, `quantity`) VALUES
(26, 'Test Room A', 3, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 2500, 2),
(27, 'Test Room B', 1, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 1650, 1),
(28, 'Test Room C', 4, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 3500, 1),
(29, 'Test Room D', 3, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 2300, 3),
(30, 'Test Room E', 3, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 1299, 5),
(31, 'Test Room SSS', 5, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 4500, 1);

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
(35, 'https://firebasestorage.googleapis.com/v0/b/hrs-costa.appspot.com/o/images%2F1%20(2).jpg?alt=media&token=59829482-380c-4165-8de7-fea4d934887f', 26),
(36, 'https://firebasestorage.googleapis.com/v0/b/hrs-costa.appspot.com/o/images%2F1%20(3).jpg?alt=media&token=a6d514e8-5df9-4263-8611-c21a34bd2ee4', 26),
(37, 'https://firebasestorage.googleapis.com/v0/b/hrs-costa.appspot.com/o/images%2F1%20(1).jpg?alt=media&token=ecf41c92-bb06-4ccb-be82-ca95f158ddaf', 27),
(38, 'https://firebasestorage.googleapis.com/v0/b/hrs-costa.appspot.com/o/images%2F1.jpg?alt=media&token=193230e3-8080-4247-9e87-2562a0c74ef5', 28),
(39, 'https://firebasestorage.googleapis.com/v0/b/hrs-costa.appspot.com/o/images%2F1%20(2).jpg?alt=media&token=22f5a9ff-e679-4091-9719-35ddee3b5a2f', 29),
(40, 'https://firebasestorage.googleapis.com/v0/b/hrs-costa.appspot.com/o/images%2F1%20(1).jpg?alt=media&token=a5e33d1a-5161-4ab0-ad0b-8cb6eb1a6f5b', 29),
(41, 'https://firebasestorage.googleapis.com/v0/b/hrs-costa.appspot.com/o/images%2F1.jpg?alt=media&token=ab5dc577-ac91-45de-b350-7403e78bb412', 29),
(42, 'https://firebasestorage.googleapis.com/v0/b/hrs-costa.appspot.com/o/images%2F1%20(3).jpg?alt=media&token=8e3550ee-5a55-43a8-bacc-b83f0a30a0c9', 29),
(43, 'https://firebasestorage.googleapis.com/v0/b/hrs-costa.appspot.com/o/images%2F1%20(4).jpg?alt=media&token=94391a32-96de-446b-9287-5ea308036e84', 29),
(44, 'https://firebasestorage.googleapis.com/v0/b/hrs-costa.appspot.com/o/images%2F1669205776825_gallery~~638ec46771d51.jpg?alt=media&token=9dd5e730-2b95-4338-9de4-79b066547ce0', 29),
(45, 'https://firebasestorage.googleapis.com/v0/b/hrs-costa.appspot.com/o/images%2F1%20(3).jpg?alt=media&token=1d0c707d-f066-4e31-a4e0-4b76607ae3bd', 30),
(46, 'https://firebasestorage.googleapis.com/v0/b/hrs-costa.appspot.com/o/images%2F1%20(4).jpg?alt=media&token=b592abe1-3e20-4b2c-9941-072a6f4ff065', 30),
(47, 'https://firebasestorage.googleapis.com/v0/b/hrs-costa.appspot.com/o/images%2F1669205776825_gallery~~638ec46771d51.jpg?alt=media&token=88c862fe-6c8f-48c5-bdd4-1f9b47f1b07f', 30),
(48, 'https://firebasestorage.googleapis.com/v0/b/hrs-costa.appspot.com/o/images%2F1%20(2).jpg?alt=media&token=c30c6284-ce1a-41fd-af14-737f532e91f8', 31),
(49, 'https://firebasestorage.googleapis.com/v0/b/hrs-costa.appspot.com/o/images%2F1.jpg?alt=media&token=d999cfc9-a259-4c6b-a7c8-b4202d2c4c58', 31),
(50, 'https://firebasestorage.googleapis.com/v0/b/hrs-costa.appspot.com/o/images%2F1%20(3).jpg?alt=media&token=f74aa48d-e224-462c-be56-36c415934534', 31),
(51, 'https://firebasestorage.googleapis.com/v0/b/hrs-costa.appspot.com/o/images%2F1%20(4).jpg?alt=media&token=38daa827-93ae-44e6-ace8-e37581b35e18', 31),
(52, 'https://firebasestorage.googleapis.com/v0/b/hrs-costa.appspot.com/o/images%2F1669205776825_gallery~~638ec46771d51.jpg?alt=media&token=d85a8b6d-d6c4-4ef5-b291-a24939555368', 31),
(53, 'https://firebasestorage.googleapis.com/v0/b/hrs-costa.appspot.com/o/images%2F1%20(1).jpg?alt=media&token=e2671d03-7765-4ab7-91cc-7df45b6c806c', 31);

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
(70, 'Geykson Maravillas', 'matildogeykson@gmail.com', 'WDQyaGxXdTZTUU5vT1VjeDk4bkd5VStoRm9UVmx1bHRkemFZc2FvZ1lKST0=', 'admin', '2024-05-22', '2024-05-22'),
(71, 'Geykson Maravillas', 'jeykson.maravillas@gmail.com', 'bjlnRGVzM0V0NkRDR0xaQUhMcVkwNndyVVpCT0llYVJTclkvODkyQkdIQT0=', 'customer', '2024-05-26', '2024-05-26');

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
  MODIFY `Id` bigint(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=119;

--
-- AUTO_INCREMENT for table `amenity`
--
ALTER TABLE `amenity`
  MODIFY `Id` bigint(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `check_ins`
--
ALTER TABLE `check_ins`
  MODIFY `Id` bigint(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `created_from_facebook`
--
ALTER TABLE `created_from_facebook`
  MODIFY `Id` bigint(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT for table `created_from_google`
--
ALTER TABLE `created_from_google`
  MODIFY `Id` bigint(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `other_rate`
--
ALTER TABLE `other_rate`
  MODIFY `Id` bigint(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `payment_evidence`
--
ALTER TABLE `payment_evidence`
  MODIFY `Id` bigint(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `Id` bigint(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `personal_information`
--
ALTER TABLE `personal_information`
  MODIFY `Id` bigint(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `Id` bigint(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `room_image`
--
ALTER TABLE `room_image`
  MODIFY `Id` bigint(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` bigint(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `access_token`
--
ALTER TABLE `access_token`
  ADD CONSTRAINT `userCorrespondingToken` FOREIGN KEY (`userId`) REFERENCES `user` (`id`) ON UPDATE CASCADE;

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
-- Constraints for table `payment_evidence`
--
ALTER TABLE `payment_evidence`
  ADD CONSTRAINT `paymentEvidenceCorrespondingChekInId` FOREIGN KEY (`checkInId`) REFERENCES `amenity` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `room_image`
--
ALTER TABLE `room_image`
  ADD CONSTRAINT `ImageCorresponsingRoomId` FOREIGN KEY (`roomId`) REFERENCES `rooms` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
