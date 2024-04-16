-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 23, 2023 at 08:10 PM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `svlelectricals`
--

-- --------------------------------------------------------

--
-- Table structure for table `bill`
--

CREATE TABLE `bill` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `customerid` int(11) NOT NULL,
  `details` text NOT NULL,
  `total` double NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL,
  `createdby` int(11) NOT NULL,
  `lastUpdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `bill`
--

INSERT INTO `bill` (`id`, `date`, `customerid`, `details`, `total`, `status`, `createdby`, `lastUpdate`) VALUES
(1, '2023-01-04', 1, 'Demo detail', 0, 1, 1, '2023-01-15 19:16:18'),
(2, '2023-01-26', 1, 'ggg', 0, 1, 1, '2023-01-27 06:50:00'),
(3, '2023-03-01', 2, '', 0, 0, 1, '2023-03-07 19:50:17'),
(4, '2023-03-02', 2, 'd', 0, 0, 1, '2023-03-07 19:55:26'),
(5, '2023-03-04', 2, '', 0, 0, 1, '2023-03-07 19:54:58'),
(6, '2023-03-03', 2, '', 0, 0, 1, '2023-03-07 19:55:01'),
(7, '2023-03-03', 2, '', 0, 0, 1, '2023-03-07 19:55:04'),
(8, '2023-03-03', 2, '', 0, 0, 1, '2023-03-07 19:55:07'),
(9, '2023-03-03', 2, '', 0, 0, 1, '2023-03-07 19:55:21'),
(10, '2023-03-01', 2, '', 0, 0, 1, '2023-03-07 19:55:39'),
(11, '2023-03-02', 2, '', 0, 1, 1, '2023-03-07 19:55:54'),
(12, '2023-03-01', 2, 'att', 0, 1, 1, '2023-03-07 19:58:04');

-- --------------------------------------------------------

--
-- Table structure for table `bill_details`
--

CREATE TABLE `bill_details` (
  `id` int(11) NOT NULL,
  `billid` int(11) NOT NULL,
  `productid` int(11) NOT NULL,
  `quantity` double NOT NULL,
  `rate` double NOT NULL,
  `discount` double NOT NULL,
  `saleprice` double NOT NULL,
  `gst` double NOT NULL DEFAULT 0,
  `subtotal` double NOT NULL,
  `gstamount` double NOT NULL,
  `total` double NOT NULL,
  `status` int(11) NOT NULL,
  `updatedby` int(11) NOT NULL,
  `lastUpdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `bill_details`
--

INSERT INTO `bill_details` (`id`, `billid`, `productid`, `quantity`, `rate`, `discount`, `saleprice`, `gst`, `subtotal`, `gstamount`, `total`, `status`, `updatedby`, `lastUpdate`) VALUES
(1, 1, 4, 1, 1000, 0, 1400, 0, 1400, 0, 1400, 0, 0, '2023-01-26 20:23:04'),
(2, 1, 5, 10, 100, 100, 300, 10, 2000, 200, 2200, 1, 0, '2023-01-26 20:15:53'),
(3, 1, 4, 5, 1000, 0, 1400, 0, 7000, 0, 7000, 1, 0, '2023-01-26 20:16:09'),
(4, 1, 4, 15, 1000, 0, 1400, 0, 21000, 0, 21000, 0, 0, '2023-01-26 20:19:53'),
(5, 2, 4, 5, 1000, 100, 1500, 5, 7000, 350, 7350, 1, 0, '2023-01-27 06:51:02'),
(6, 2, 5, 50, 100, 0, 150, 0, 7500, 0, 7500, 1, 0, '2023-01-27 06:51:25'),
(7, 1, 6, 1, 100, 0, 150, 0, 150, 0, 150, 1, 0, '2023-01-27 08:51:45'),
(8, 2, 6, 1, 100, 0, 150, 0, 150, 0, 150, 0, 0, '2023-01-28 10:57:12'),
(9, 11, 4, 1, 1000, 0, 1400, 0, 1400, 0, 1400, 1, 0, '2023-03-07 20:01:00');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `gst` varchar(50) DEFAULT NULL,
  `mobile` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `email` varchar(100) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id`, `name`, `gst`, `mobile`, `address`, `email`, `status`) VALUES
(1, 'Sujit D', NULL, '8888763564', 'Kolhapur', 'shd@gmail.com', 1),
(2, 'Amit Punekar', '125489', '8888763589', 'kop', 'punekaramit4@gmail.com', 1);

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`id`, `name`, `username`, `password`, `email`, `status`) VALUES
(1, 'Shital Dinde', 'shd', '7f125dd8c17fc02ab20338fbcf27abfe', 'shd@gmail.com', 1),
(2, 'Demo User', 'demo', 'fe01ce2a7fbac8fafaed7c982a04e229', 'demo@shd.com', 1);

-- --------------------------------------------------------

--
-- Table structure for table `payment_received`
--

CREATE TABLE `payment_received` (
  `id` int(11) NOT NULL,
  `customerid` int(11) NOT NULL,
  `date` date NOT NULL,
  `amount` double NOT NULL,
  `details` text NOT NULL,
  `mode` varchar(20) NOT NULL,
  `updateby` int(11) NOT NULL,
  `lastupdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `payment_received`
--

INSERT INTO `payment_received` (`id`, `customerid`, `date`, `amount`, `details`, `mode`, `updateby`, `lastupdate`, `status`) VALUES
(1, 1, '2023-01-06', 500, 'demo', 'Online', 1, '2023-01-14 17:44:00', 0),
(2, 1, '2023-01-03', 1000, '1000', 'Cheque', 1, '2023-01-14 17:46:27', 1),
(3, 2, '2023-03-07', 200, 'demo', 'UPI', 1, '2023-03-07 19:27:01', 1);

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `short_name` varchar(255) NOT NULL,
  `hsn` varchar(200) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `rate` double NOT NULL,
  `saleprice` double NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `name`, `short_name`, `hsn`, `details`, `rate`, `saleprice`, `status`) VALUES
(1, 'Tube', 'Tube', NULL, '20W siska bulb', 50, 140, 0),
(2, 'Demo', 'bulb', NULL, 'demo', 100, 150, 0),
(4, 'Bulb1', 'bulb1', NULL, 'demo', 1000, 1400, 1),
(5, 'Demo2', 'Dem2', NULL, 'Demo details', 100, 300, 1),
(6, 'Demo4', 'd', '123456', '', 100, 150, 1),
(10, 'a', 'a', 'a', 'a', 1, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `quotation`
--

CREATE TABLE `quotation` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `customerid` int(11) NOT NULL,
  `details` text NOT NULL,
  `total` double NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL,
  `createdby` int(11) NOT NULL,
  `lastUpdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `quotation`
--

INSERT INTO `quotation` (`id`, `date`, `customerid`, `details`, `total`, `status`, `createdby`, `lastUpdate`) VALUES
(1, '2023-01-21', 1, 'demo', 0, 1, 1, '2023-01-28 10:40:25'),
(2, '2023-01-27', 1, 'demo', 0, 1, 1, '2023-01-28 10:26:35'),
(3, '2023-01-19', 1, 'demo', 0, 0, 1, '2023-01-28 10:21:09'),
(4, '2023-03-03', 2, '', 0, 1, 1, '2023-03-07 20:17:51');

-- --------------------------------------------------------

--
-- Table structure for table `quotation_details`
--

CREATE TABLE `quotation_details` (
  `id` int(11) NOT NULL,
  `quotationid` int(11) NOT NULL,
  `productid` int(11) NOT NULL,
  `quantity` double NOT NULL,
  `rate` double NOT NULL,
  `discount` double NOT NULL,
  `saleprice` double NOT NULL,
  `gst` double NOT NULL DEFAULT 0,
  `subtotal` double NOT NULL,
  `gstamount` double NOT NULL,
  `total` double NOT NULL,
  `status` int(11) NOT NULL,
  `updatedby` int(11) NOT NULL,
  `lastUpdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `quotation_details`
--

INSERT INTO `quotation_details` (`id`, `quotationid`, `productid`, `quantity`, `rate`, `discount`, `saleprice`, `gst`, `subtotal`, `gstamount`, `total`, `status`, `updatedby`, `lastUpdate`) VALUES
(1, 2, 4, 5, 1000, 0, 1400, 0, 7000, 0, 7000, 1, 0, '2023-01-28 10:50:06'),
(2, 2, 6, 50, 100, 0, 150, 18, 7500, 1350, 8850, 0, 0, '2023-01-28 10:54:12'),
(3, 2, 4, 1, 1000, 0, 1400, 0, 1400, 0, 1400, 0, 0, '2023-01-28 10:54:02'),
(4, 4, 6, 10, 100, 0, 150, 0, 1500, 0, 1500, 1, 0, '2023-03-07 20:18:11'),
(5, 4, 4, 1, 1000, 0, 1400, 5, 1400, 70, 1470, 1, 0, '2023-03-07 20:18:32');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bill`
--
ALTER TABLE `bill`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bill_details`
--
ALTER TABLE `bill_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `payment_received`
--
ALTER TABLE `payment_received`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short_name` (`short_name`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `quotation`
--
ALTER TABLE `quotation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `quotation_details`
--
ALTER TABLE `quotation_details`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bill`
--
ALTER TABLE `bill`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `bill_details`
--
ALTER TABLE `bill_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `payment_received`
--
ALTER TABLE `payment_received`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `quotation`
--
ALTER TABLE `quotation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `quotation_details`
--
ALTER TABLE `quotation_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
