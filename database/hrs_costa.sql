-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 09, 2024 at 01:44 PM
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

--
-- Dumping data for table `amenity`
--

INSERT INTO `amenity` (`Id`, `amenityName`, `roomId`) VALUES
(16, 'TV ', 38),
(17, '2 Beds', 38),
(18, 'Aircon', 38),
(19, 'CR', 38),
(20, 'TV ', 39),
(21, '3 Beds', 39),
(22, 'Aircon', 39),
(23, '1 CR', 39),
(24, 'TV', 40),
(25, '5 Beds', 40),
(26, 'CR', 40);

-- --------------------------------------------------------

--
-- Table structure for table `check_ins`
--

CREATE TABLE `check_ins` (
  `Id` bigint(10) NOT NULL,
  `roomId` bigint(10) NOT NULL,
  `checkInDate` date NOT NULL,
  `checkOutDate` date NOT NULL,
  `userId` bigint(10) NOT NULL,
  `queueDateTime` datetime NOT NULL,
  `status` varchar(255) NOT NULL,
  `checkInQuantity` bigint(10) NOT NULL,
  `paymentMethodId` bigint(10) NOT NULL,
  `totalAmount` float NOT NULL,
  `customerfullName` varchar(255) NOT NULL,
  `customerCompleteAddress` varchar(255) NOT NULL,
  `customerContactInfo` varchar(255) NOT NULL,
  `notificationStatus` varchar(255) NOT NULL DEFAULT 'Unread',
  `message` varchar(500) NOT NULL,
  `multiBookId` bigint(10) NOT NULL DEFAULT 0,
  `createdDate` date NOT NULL DEFAULT current_timestamp(),
  `latestModifiedDate` date NOT NULL DEFAULT current_timestamp(),
  `isPartial` tinyint(1) NOT NULL,
  `partialPayment` float NOT NULL
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
-- Table structure for table `multibook`
--

CREATE TABLE `multibook` (
  `id` bigint(10) NOT NULL,
  `totalAmount` float NOT NULL,
  `partialPayment` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `multibook`
--

INSERT INTO `multibook` (`id`, `totalAmount`, `partialPayment`) VALUES
(0, 0, 0);

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
(15, 'Flash Sale', 38, 2500),
(16, 'Lucky Sale', 39, 950);

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

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`Id`, `name`, `maximum`, `description`, `originalRate`, `quantity`) VALUES
(38, 'Test Room A', 3, 'This hotel room test description provides a detailed overview of the features and amenities available in the room. From the size and layout to the furnishings and decor, customers can gain a clear understanding of what to expect during their stay. The description also highlights any unique selling points or special touches that set this room apart from others in the hotel. Overall, this product description aims to inform and entice potential guests to book this particular room for their upcoming', 3500, 10),
(39, 'Test Room B', 3, 'This hotel room test description provides a detailed overview of the features and amenities available in the room. From the size and layout to the furnishings and decor, customers can gain a clear understanding of what to expect during their stay. The description also highlights any unique selling points or special touches that set this room apart from others in the hotel. Overall, this product description aims to inform and entice potential guests to book this particular room for their upcoming', 1500, 10),
(40, 'Test Room C', 5, 'This hotel room test description provides a detailed overview of the features and amenities available in the room. From the size and layout to the furnishings and decor, customers can gain a clear understanding of what to expect during their stay. The description also highlights any unique selling points or special touches that set this room apart from others in the hotel. Overall, this product description aims to inform and entice potential guests to book this particular room for their upcoming', 1500.65, 10);

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
(68, 'https://firebasestorage.googleapis.com/v0/b/hrs-costa.appspot.com/o/images%2F1669205776825_gallery~~638ec46771d51.jpg?alt=media&token=c6ac34b3-76b8-4286-8ff5-2fd15d5df141', 38),
(69, 'https://firebasestorage.googleapis.com/v0/b/hrs-costa.appspot.com/o/images%2F1.jpg?alt=media&token=d0afcd8d-5f66-41a8-975a-aa4db9c13dca', 38),
(70, 'https://firebasestorage.googleapis.com/v0/b/hrs-costa.appspot.com/o/images%2F1%20(4).jpg?alt=media&token=5330b0cb-52da-4ec6-8966-28fdbe00c3fe', 38),
(71, 'https://firebasestorage.googleapis.com/v0/b/hrs-costa.appspot.com/o/images%2F1%20(2).jpg?alt=media&token=494bb36a-c18d-487b-be39-abaa1558a5a5', 38),
(72, 'https://firebasestorage.googleapis.com/v0/b/hrs-costa.appspot.com/o/images%2F1%20(3).jpg?alt=media&token=831e01e2-c739-4535-a14e-7727f652ad67', 38),
(73, 'https://firebasestorage.googleapis.com/v0/b/hrs-costa.appspot.com/o/images%2F1%20(1).jpg?alt=media&token=8a88ae89-2fba-4f2a-a4b4-809535bb924b', 39),
(74, 'https://firebasestorage.googleapis.com/v0/b/hrs-costa.appspot.com/o/images%2F1%20(4).jpg?alt=media&token=ce917232-4ca6-4669-8a85-caa4c602dcfb', 39),
(75, 'https://firebasestorage.googleapis.com/v0/b/hrs-costa.appspot.com/o/images%2F1%20(2).jpg?alt=media&token=6be2cf85-3499-4115-afee-b94095dced5e', 39),
(76, 'https://firebasestorage.googleapis.com/v0/b/hrs-costa.appspot.com/o/images%2F1%20(3).jpg?alt=media&token=798c1596-0fad-42e7-9eb7-55d19627f000', 39),
(77, 'https://firebasestorage.googleapis.com/v0/b/hrs-costa.appspot.com/o/images%2F1.jpg?alt=media&token=6a8a1599-3844-402a-b465-fa206e65b379', 39),
(78, 'https://firebasestorage.googleapis.com/v0/b/hrs-costa.appspot.com/o/images%2F1669205776825_gallery~~638ec46771d51.jpg?alt=media&token=47a65483-808c-4685-bd75-32b76a39189e', 39),
(79, 'https://firebasestorage.googleapis.com/v0/b/hrs-costa.appspot.com/o/images%2F1.jpg?alt=media&token=25acd46a-c7fe-4df4-8496-4e9492f9bc70', 40),
(80, 'https://firebasestorage.googleapis.com/v0/b/hrs-costa.appspot.com/o/images%2F1%20(3).jpg?alt=media&token=80be6017-8e33-4241-ac7c-2c4e7c388fb1', 40),
(81, 'https://firebasestorage.googleapis.com/v0/b/hrs-costa.appspot.com/o/images%2F1%20(4).jpg?alt=media&token=a4328f9a-a666-4547-847d-0aad36e87bb7', 40);

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
(92, 'Test Admin', 'testadmin@test.com', 'V0lMeVZ5U2FzajlwcDJmZVcxV2JNdz09', 'customer', '2024-10-09', '2024-10-09');

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
  ADD KEY `paymentMethodId` (`paymentMethodId`),
  ADD KEY `multiBookId` (`multiBookId`);

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
-- Indexes for table `multibook`
--
ALTER TABLE `multibook`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `Id` bigint(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=165;

--
-- AUTO_INCREMENT for table `amenity`
--
ALTER TABLE `amenity`
  MODIFY `Id` bigint(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `check_ins`
--
ALTER TABLE `check_ins`
  MODIFY `Id` bigint(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=233;

--
-- AUTO_INCREMENT for table `created_from_facebook`
--
ALTER TABLE `created_from_facebook`
  MODIFY `Id` bigint(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT for table `created_from_google`
--
ALTER TABLE `created_from_google`
  MODIFY `Id` bigint(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `multibook`
--
ALTER TABLE `multibook`
  MODIFY `id` bigint(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `other_rate`
--
ALTER TABLE `other_rate`
  MODIFY `Id` bigint(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `payment_evidence`
--
ALTER TABLE `payment_evidence`
  MODIFY `Id` bigint(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=123;

--
-- AUTO_INCREMENT for table `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `Id` bigint(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `Id` bigint(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `room_image`
--
ALTER TABLE `room_image`
  MODIFY `Id` bigint(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` bigint(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

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
  ADD CONSTRAINT `checkedInCorrespondingMultiBookId` FOREIGN KEY (`multiBookId`) REFERENCES `multibook` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
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
