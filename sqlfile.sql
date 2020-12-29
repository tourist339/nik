-- phpMyAdmin SQL Dump
-- version 4.9.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Dec 29, 2020 at 06:22 PM
-- Server version: 5.7.26
-- PHP Version: 7.4.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `system_d`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `id` int(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`username`, `password`, `id`) VALUES
('nikhil', '$2y$10$m4vrBcEoXF.SZiUgCqoXLebTGOyKef44keNKGsExmBjn3/Pg1TIt2', 1);

-- --------------------------------------------------------

--
-- Table structure for table `amenities`
--

CREATE TABLE `amenities` (
  `id` int(9) UNSIGNED NOT NULL,
  `prop_id` int(9) UNSIGNED NOT NULL,
  `wifi` int(1) NOT NULL,
  `breakfast` int(1) NOT NULL,
  `laundry` int(1) NOT NULL,
  `dinner` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `amenities`
--

INSERT INTO `amenities` (`id`, `prop_id`, `wifi`, `breakfast`, `laundry`, `dinner`) VALUES
(1, 2, 1, 1, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `id` int(4) UNSIGNED NOT NULL,
  `city` varchar(50) NOT NULL,
  `featured_props` varchar(1000) DEFAULT NULL,
  `min_rent` int(9) NOT NULL,
  `max_rent` int(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`id`, `city`, `featured_props`, `min_rent`, `max_rent`) VALUES
(1, 'Saskatoon', NULL, 2, 3500);

-- --------------------------------------------------------

--
-- Table structure for table `Properties`
--

CREATE TABLE `Properties` (
  `id` int(9) UNSIGNED NOT NULL,
  `ownerid` int(9) UNSIGNED DEFAULT NULL,
  `title` varchar(30) DEFAULT NULL,
  `description` varchar(4000) DEFAULT NULL,
  `city` varchar(40) DEFAULT NULL,
  `state` varchar(40) DEFAULT NULL,
  `aptno` varchar(255) DEFAULT NULL,
  `proptype` varchar(40) DEFAULT NULL,
  `sharingtype` varchar(40) DEFAULT NULL,
  `guests` int(2) UNSIGNED DEFAULT NULL,
  `bedrooms` int(2) UNSIGNED DEFAULT NULL,
  `bathrooms` int(2) UNSIGNED DEFAULT NULL,
  `kitchen` int(1) UNSIGNED DEFAULT NULL,
  `lyfly` int(1) UNSIGNED DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  `rent` int(8) UNSIGNED DEFAULT NULL,
  `amenities` varchar(200) DEFAULT NULL,
  `utilities` varchar(60) DEFAULT NULL,
  `houseRules` varchar(600) DEFAULT NULL,
  `agreementType` varchar(100) DEFAULT NULL,
  `dateAdded` varchar(100) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `images` varchar(2000) DEFAULT NULL,
  `admin` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Properties`
--

INSERT INTO `Properties` (`id`, `ownerid`, `title`, `description`, `city`, `state`, `aptno`, `proptype`, `sharingtype`, `guests`, `bedrooms`, `bathrooms`, `kitchen`, `lyfly`, `address`, `rent`, `amenities`, `utilities`, `houseRules`, `agreementType`, `dateAdded`, `gender`, `images`, `admin`) VALUES
(1, 1, '3 BEDROOM', '3 BEDROOM3 BEDROOM3 BEDROOM\r\n3 BEDROOM3 BEDROOM3 BEDROOM\r\n\r\n3 BEDROOM\r\n3 BEDROOM3 BEDROOM\r\n3 BEDROOM\r\n3 BEDROOM\r\n3 BEDROOM\r\n3 BEDROOM\r\nv3 BEDROOM', 'Saskatoon', 'Saskatchewan', '', 'Apartment', 'Shared Room', 2, 2, 3, 1, 0, '29 Clark Crescent', 3000, 'Wifi,Laundry,TV,Breakfast,Dinner', NULL, 'Smoking AllowedYes,Parties AllowedNoo,Pet FriendlyYes', 'monthly', NULL, 'Both', '1/696013hp-city6.jpg,1/943970hp-city5.jpg,1/862487hp-city4.jpg,1/903890hp-city3.jpg,1/231897hp-city2.jpg,1/897892hp-city1.jpg', 'nikhil'),
(2, 1, 'family suite', 'very big fking shit', 'Saskatoon', 'Saskatchewan', '', 'House', NULL, 1, 2, 3, 0, 0, '64 primrose drive', 2000, 'Air Conditioner,Refrigerator,Lunch,Dinner', NULL, 'Smoking AllowedYes,Parties AllowedNoo,Pet FriendlyNoo', 'halfyear', NULL, 'males', '1/428399img2.jpeg,1/975657img1.jpeg,1/716557img7.jpeg,1/253199img6.jpeg,1/203258img5.jpeg', 'nikhil'),
(3, 1, 'holy house', 'very big fking shit nice casdcds', 'Saskatoon', 'Saskatchewan', '', 'House', NULL, 1, 2, 3, 0, 0, '64 primrose drive', 3500, 'Air Conditioner,Refrigerator,Lunch,Dinner', NULL, 'Smoking AllowedYes,Parties AllowedNoo,Pet FriendlyNoo', 'halfyear', NULL, 'males', '1/266351img2.jpeg,1/237272img1.jpeg,1/629703img7.jpeg,1/442150img6.jpeg,1/458810img5.jpeg', 'nikhil'),
(4, 1, 'family suite', 'very big fking shit', 'Saskatoon', 'Saskatchewan', '', 'House', NULL, 1, 2, 3, 0, 0, '64 primrose drive', 2, 'Air Conditioner,Refrigerator,Lunch,Dinner', NULL, 'Smoking AllowedYes,Parties AllowedNoo,Pet FriendlyNoo', 'halfyear', NULL, 'males', '1/768554img2.jpeg,1/812505img1.jpeg,1/425883img7.jpeg,1/370495img6.jpeg,1/255663img5.jpeg', 'nikhil');

-- --------------------------------------------------------

--
-- Table structure for table `temp_properties`
--

CREATE TABLE `temp_properties` (
  `id` int(9) UNSIGNED NOT NULL,
  `ownerid` int(9) UNSIGNED DEFAULT NULL,
  `title` varchar(30) DEFAULT NULL,
  `description` varchar(4000) DEFAULT NULL,
  `city` varchar(40) DEFAULT NULL,
  `state` varchar(40) DEFAULT NULL,
  `aptno` varchar(255) DEFAULT NULL,
  `proptype` varchar(40) DEFAULT NULL,
  `sharingtype` varchar(40) DEFAULT NULL,
  `guests` int(2) UNSIGNED DEFAULT NULL,
  `bedrooms` int(2) UNSIGNED DEFAULT NULL,
  `bathrooms` int(2) UNSIGNED DEFAULT NULL,
  `kitchen` int(1) UNSIGNED DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  `rent` int(8) UNSIGNED DEFAULT NULL,
  `amenities` varchar(200) DEFAULT NULL,
  `utilities` varchar(60) DEFAULT NULL,
  `agreementType` varchar(100) DEFAULT NULL,
  `dateAdded` varchar(100) DEFAULT NULL,
  `houseRules` varchar(600) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `images` varchar(2000) DEFAULT NULL,
  `lyfly` int(1) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `temp_properties`
--

INSERT INTO `temp_properties` (`id`, `ownerid`, `title`, `description`, `city`, `state`, `aptno`, `proptype`, `sharingtype`, `guests`, `bedrooms`, `bathrooms`, `kitchen`, `address`, `rent`, `amenities`, `utilities`, `agreementType`, `dateAdded`, `houseRules`, `gender`, `images`, `lyfly`) VALUES
(5, 1, '2 befrfds', '323213', 'Saskatoon', 'Saskatchewan', '', NULL, NULL, 1, 2, 3, 0, '29 Clark Crescent', 600, 'Laundry,Refrigerator', NULL, 'halfyear', NULL, 'Smoking AllowedYes,Parties AllowedNoo,Pet FriendlyYes', 'males', '1/166276hp-city1.jpg,1/81423hp-city5.jpg,1/211491hp-city4.jpg,1/711956hp-city3.jpg,1/521291hp-city2.jpg,1/56736hp-city6.jpg', 0),
(6, 1, '2 befrfds', '323213', 'Saskatoon', 'Saskatchewan', '', NULL, NULL, 1, 2, 3, 0, '29 Clark Crescent', 600, 'Laundry,Refrigerator', NULL, 'halfyear', NULL, 'Smoking AllowedYes,Parties AllowedNoo,Pet FriendlyYes', 'males', '1/716834hp-city1.jpg,1/599891hp-city5.jpg,1/445613hp-city4.jpg,1/927445hp-city3.jpg,1/435199hp-city2.jpg,1/6337hp-city6.jpg', 0),
(7, 1, '2 befrfds', '323213', 'Saskatoon', 'Saskatchewan', '', NULL, NULL, 1, 2, 3, 0, '29 Clark Crescent', 600, 'Laundry,Refrigerator', NULL, 'halfyear', NULL, 'Smoking AllowedYes,Parties AllowedNoo,Pet FriendlyYes', 'males', '1/465424hp-city1.jpg,1/83700hp-city5.jpg,1/193018hp-city4.jpg,1/943940hp-city3.jpg,1/814692hp-city2.jpg,1/182991hp-city6.jpg', 0),
(8, 1, '2 befrfds', '323213', 'Saskatoon', 'Saskatchewan', '', NULL, NULL, 1, 2, 3, 0, '29 Clark Crescent', 600, 'Laundry,Refrigerator', NULL, 'halfyear', NULL, 'Smoking AllowedYes,Parties AllowedNoo,Pet FriendlyYes', 'males', '1/135731hp-city1.jpg,1/300705hp-city5.jpg,1/375359hp-city4.jpg,1/329214hp-city3.jpg,1/129100hp-city2.jpg,1/656743hp-city6.jpg', 0),
(9, 1, '2 befrfds', '323213', 'Saskatoon', 'Saskatchewan', '', NULL, NULL, 1, 2, 3, 0, '29 Clark Crescent', 600, 'Laundry,Refrigerator', NULL, 'halfyear', NULL, 'Smoking AllowedYes,Parties AllowedNoo,Pet FriendlyYes', 'males', '1/17672hp-city1.jpg,1/586314hp-city5.jpg,1/426132hp-city4.jpg,1/940755hp-city3.jpg,1/533664hp-city2.jpg,1/940394hp-city6.jpg', 0),
(10, 1, '2 befrfds', '323213', 'Saskatoon', 'Saskatchewan', '', NULL, NULL, 1, 2, 3, 0, '29 Clark Crescent', 600, 'Laundry,Refrigerator', NULL, 'halfyear', NULL, 'Smoking AllowedYes,Parties AllowedNoo,Pet FriendlyYes', 'males', '1/750813hp-city1.jpg,1/113567hp-city5.jpg,1/896032hp-city4.jpg,1/284888hp-city3.jpg,1/452211hp-city2.jpg,1/84094hp-city6.jpg', 0),
(11, 1, '2 befrfds', '323213', 'Saskatoon', 'Saskatchewan', '', NULL, NULL, 1, 2, 3, 0, '29 Clark Crescent', 600, 'Laundry,Refrigerator', NULL, 'halfyear', NULL, 'Smoking AllowedYes,Parties AllowedNoo,Pet FriendlyYes', 'males', '1/464048hp-city1.jpg,1/49122hp-city5.jpg,1/930625hp-city4.jpg,1/386880hp-city3.jpg,1/875569hp-city2.jpg,1/323205hp-city6.jpg', 0),
(12, 1, '23', '42', 'Saskatoon', 'Saskatchewan', '', NULL, NULL, 2, 3, 3, 0, '29 Clark Crescent', 2, 'Wifi,Air Conditioner,Refrigerator', NULL, 'monthly', NULL, NULL, 'Both', '1/938253screen shot 2020-07-02 at 4.01.20 pm.png,1/696157screen shot 2020-06-30 at 4.25.14 pm.png,1/761289screen shot 2020-06-30 at 4.25.09 pm.png,1/969201screen shot 2020-06-30 at 11.34.53 am.png,1/55229screen shot 2020-06-28 at 12.51.42 pm.png', 0),
(13, 1, '23', '42', 'Saskatoon', 'Saskatchewan', '', NULL, NULL, 2, 3, 3, 0, '29 Clark Crescent', 2, 'Wifi,Air Conditioner,Refrigerator', NULL, 'monthly', NULL, NULL, 'Both', '1/55436screen shot 2020-07-02 at 4.01.20 pm.png,1/99162screen shot 2020-06-30 at 4.25.14 pm.png,1/297874screen shot 2020-06-30 at 4.25.09 pm.png,1/196791screen shot 2020-06-30 at 11.34.53 am.png,1/192401screen shot 2020-06-28 at 12.51.42 pm.png', 0),
(14, 1, 'lyfly prop', '323213', 'Saskatoon', 'Saskatchewan', '', NULL, NULL, 1, 2, 3, 0, '29 Clark Crescent', 600, 'Laundry,Refrigerator', NULL, 'halfyear', NULL, 'Smoking AllowedYes,Parties AllowedNoo,Pet FriendlyYes', 'males', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) NOT NULL,
  `first_name` varchar(60) NOT NULL,
  `last_name` varchar(60) NOT NULL,
  `login_type` varchar(60) NOT NULL,
  `unapproved_properties` varchar(2000) DEFAULT NULL,
  `wishlist` varchar(1000) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `state` varchar(50) DEFAULT NULL,
  `phone_num` varchar(50) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(60) NOT NULL,
  `pic` varchar(500) DEFAULT NULL,
  `approved_properties` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `login_type`, `unapproved_properties`, `wishlist`, `address`, `city`, `state`, `phone_num`, `email`, `password`, `pic`, `approved_properties`) VALUES
(1, 'Kartik', 'Kapoor', 'google', '5,6,7,8,9,10,11,12,13,14', '1', '29 Clark Crescent', 'Saskatoon', 'Saskatchewan', '6399985807', 'kartikkapoor3390@gmail.com', '$2y$10$8uQhcp1CayZkuACTTPLvN.WOuMiertAt2.I/17gZ1T2DzEMtq9FBy', 'https://lh4.googleusercontent.com/-qUAmOnIm2l4/AAAAAAAAAAI/AAAAAAAAAbw/AMZuucnvywRndpl5kccsIh0VkOmw7PHgQw/s96-c/photo.jpg', '1,2,3,4');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `amenities`
--
ALTER TABLE `amenities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `location` (`city`);

--
-- Indexes for table `Properties`
--
ALTER TABLE `Properties`
  ADD PRIMARY KEY (`id`),
  ADD KEY `city` (`city`),
  ADD KEY `state` (`state`);

--
-- Indexes for table `temp_properties`
--
ALTER TABLE `temp_properties`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `amenities`
--
ALTER TABLE `amenities`
  MODIFY `id` int(9) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `id` int(4) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `Properties`
--
ALTER TABLE `Properties`
  MODIFY `id` int(9) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `temp_properties`
--
ALTER TABLE `temp_properties`
  MODIFY `id` int(9) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
