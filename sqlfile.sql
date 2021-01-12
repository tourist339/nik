-- phpMyAdmin SQL Dump
-- version 4.9.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Jan 12, 2021 at 09:02 PM
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
  MODIFY `id` int(9) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `id` int(4) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Properties`
--
ALTER TABLE `Properties`
  MODIFY `id` int(9) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `temp_properties`
--
ALTER TABLE `temp_properties`
  MODIFY `id` int(9) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
