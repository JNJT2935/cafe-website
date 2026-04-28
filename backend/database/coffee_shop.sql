-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 28, 2026 at 08:48 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `coffee_shop_3`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `Product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE `contact` (
  `contact_id` int(11) NOT NULL,
  `firstname` varchar(60) NOT NULL,
  `lastname` varchar(60) NOT NULL,
  `email` varchar(150) NOT NULL,
  `message` varchar(1000) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact`
--

INSERT INTO `contact` (`contact_id`, `firstname`, `lastname`, `email`, `message`, `created_at`) VALUES
(1, 'O\'Brian', 'Peter-Both', 'obrien@example.com', 'Hello, I would like more information about your coffee products.', '2026-04-26 09:15:00'),
(2, 'Mary Jane', 'Smith', 'mary.jane@example.com', 'I really enjoy your online coffee shop and would like to know your delivery options.', '2026-04-26 09:20:00'),
(3, 'Jean Paul', 'Durand', 'jean.paul@example.com', 'Can you tell me whether you offer special discounts for bulk coffee orders?', '2026-04-26 09:25:00'),
(4, 'Anna-Marie', 'Lopez', 'anna.lopez@example.com', 'I would like to ask if your café has any seasonal coffee blends available.', '2026-04-26 09:30:00'),
(5, 'Kevin', 'Ramsamy', 'kevin.r@example.com', 'Please let me know if I can customize a gift order for a friend.', '2026-04-26 09:35:00');

-- --------------------------------------------------------

--
-- Table structure for table `delivery`
--

CREATE TABLE `delivery` (
  `Delivery_id` int(11) NOT NULL,
  `delivery_date` datetime NOT NULL,
  `delivery_address` varchar(255) DEFAULT NULL,
  `delivery_method` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `delivery`
--

INSERT INTO `delivery` (`Delivery_id`, `delivery_date`, `delivery_address`, `delivery_method`) VALUES
(7, '2026-04-28 11:20:00', 'royal road, Albion', 'delivery'),
(8, '2026-04-22 10:30:00', 'Port Louis', 'pickup'),
(9, '2026-04-28 22:30:00', 'Grand Baie', 'pickup'),
(10, '2026-04-18 09:45:00', 'Le Hochet, Terre Rogue', 'delivery');

-- --------------------------------------------------------

--
-- Table structure for table `manage_order`
--

CREATE TABLE `manage_order` (
  `user_id` int(11) NOT NULL,
  `Order_id` int(11) NOT NULL,
  `modification_status` varchar(6) NOT NULL,
  `Date/Time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `manage_product`
--

CREATE TABLE `manage_product` (
  `user_id` int(11) NOT NULL,
  `Product_id` int(11) NOT NULL,
  `modification_status` varchar(6) NOT NULL,
  `Date/Time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `manage_product`
--

INSERT INTO `manage_product` (`user_id`, `Product_id`, `modification_status`, `Date/Time`) VALUES
(1, 1, 'Added', '2025-12-03 09:00:00'),
(2, 2, 'Update', '2025-12-03 09:30:00'),
(1, 3, 'Delete', '2025-12-03 10:00:00'),
(3, 4, 'Update', '2025-12-03 10:30:00'),
(2, 5, 'Added', '2025-12-03 11:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `Order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `Payment_id` int(11) NOT NULL,
  `Delivery_id` int(11) NOT NULL,
  `Order_date` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `order_status` varchar(20) NOT NULL,
  `Total_paid` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `order`
--

INSERT INTO `order` (`Order_id`, `user_id`, `Payment_id`, `Delivery_id`, `Order_date`, `order_status`, `Total_paid`) VALUES
(7, 1, 7, 7, '2026-04-28 22:35:00', 'done', 759.50),
(8, 2, 8, 8, '2026-04-28 22:35:08', 'done', 630.00),
(9, 3, 9, 9, '2026-04-28 22:36:06', 'done', 400.00),
(10, 5, 10, 10, '2026-04-28 22:36:19', 'order In', 1025.00);

-- --------------------------------------------------------

--
-- Table structure for table `order_item`
--

CREATE TABLE `order_item` (
  `Order_id` int(11) NOT NULL,
  `Product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `order_item`
--

INSERT INTO `order_item` (`Order_id`, `Product_id`, `quantity`, `price`) VALUES
(7, 1, 2, 360),
(7, 4, 1, 250),
(8, 3, 2, 450),
(8, 1, 1, 180),
(9, 2, 2, 400),
(10, 3, 3, 675),
(10, 2, 1, 200);

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `Payment_id` int(11) NOT NULL,
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `payment_method` varchar(25) NOT NULL,
  `Transaction_id` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`Payment_id`, `payment_date`, `payment_method`, `Transaction_id`) VALUES
(7, '2026-04-28 18:17:47', 'cash', '893761624cb3c6e6'),
(8, '2026-04-28 18:24:18', 'scan', '19b62848f99a5740'),
(9, '2026-04-28 18:28:35', 'cash', 'e4b88789ca082507'),
(10, '2026-04-28 18:31:25', 'cash', '80a1d4c053852afd');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `Product_id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock_quantity` int(11) NOT NULL,
  `category` varchar(20) NOT NULL,
  `Visible_on_website` tinyint(1) NOT NULL DEFAULT 1,
  `image_source` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`Product_id`, `name`, `description`, `price`, `stock_quantity`, `category`, `Visible_on_website`, `image_source`) VALUES
(1, 'Espresso Coffee', 'Strong and bold espresso made from premium beans.', 180.00, 96, 'Beverage', 1, 'espresso.jpg'),
(2, 'Cappuccino', 'Creamy cappuccino topped with frothy milk.', 200.00, 77, 'Beverage', 1, 'cappuccino.jpg'),
(3, 'Blueberry Muffin', 'Freshly baked muffin with real blueberries.', 225.00, 44, 'Snack', 1, 'blueberry_muffin.jpg'),
(4, 'Chocolate Croissant', 'Flaky croissant filled with rich chocolate.', 249.50, 59, 'Snack', 1, 'chocolate_croissant.jpg'),
(5, 'Green Tea', 'Refreshing green tea with a soothing aroma.', 175.00, 70, 'Beverage', 1, 'green_tea.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE `review` (
  `Product_id` int(11) NOT NULL,
  `Order_id` int(11) NOT NULL,
  `Rating` set('1','2','3','4','5') NOT NULL,
  `Comment` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `review`
--

INSERT INTO `review` (`Product_id`, `Order_id`, `Rating`, `Comment`) VALUES
(1, 7, '4', 'Absolutely loved the Espresso! Strong and Bold, just how i like.'),
(2, 9, '4', 'Cappuccino was creamy and smooth, very enjoyable.'),
(3, 8, '5', 'Blueberry Muffin was Fresh and full of flavor, highly recommend.'),
(4, 7, '3', 'Chocolate Croissant was good, but a bit too sweet for me');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `email` varchar(30) NOT NULL,
  `password` varchar(25) NOT NULL,
  `salt` varchar(16) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `phone_number` int(11) DEFAULT NULL,
  `User_type` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `email`, `password`, `salt`, `name`, `phone_number`, `User_type`) VALUES
(1, 'bobbyS230@gmail.com', '924fca6d9b9807638d2794239', 'ej$QB1XwS+bNva-y', 'Bobby Smith ', 54812369, 'user'),
(2, 'linda.morris01@example.com', 'a5460d02341242f252fae194e', '1vj2&XvQPxCf@eF6', 'Linda Morris', 58721450, 'user'),
(3, 'karim.dev42@example.com', '815f737f640c48fa0811e4157', '*!om0OtEvGS*_cyG', 'Karim Dawood', 54298100, 'user'),
(4, 'emily.jones87@example.com', '48612d7718e305d822c90785a', 'y)TL9jO%YSaDCOT!', 'Emily Jones', 59674211, 'admin'),
(5, 'DaniellaR5@gmail.com', '3f5dd6e2be9c49388fac8d8c2', '2zo*yhxqA-PeS(L&', 'Daniella Rose', 52658591, 'user'),
(6, 'sara.lee09@example.com', 'fad90711ad34d5714a91a8487', 'a(R9xJaOraSPz*c$', 'Sara Lee', 56788909, 'admin'),
(7, 'nina.park21@example.com', '8b41b371619a61ae8522da871', '1QRvl9CbXix3c6D7', 'Nina Park', 59987122, 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `product_id` (`Product_id`) USING BTREE,
  ADD KEY `user_id` (`user_id`) USING BTREE;

--
-- Indexes for table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`contact_id`);

--
-- Indexes for table `delivery`
--
ALTER TABLE `delivery`
  ADD PRIMARY KEY (`Delivery_id`);

--
-- Indexes for table `manage_order`
--
ALTER TABLE `manage_order`
  ADD PRIMARY KEY (`user_id`,`Order_id`),
  ADD KEY `manageorderorder` (`Order_id`);

--
-- Indexes for table `manage_product`
--
ALTER TABLE `manage_product`
  ADD KEY `user_id` (`user_id`),
  ADD KEY `Product_id` (`Product_id`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`Order_id`),
  ADD KEY `Orderdelivery` (`Delivery_id`),
  ADD KEY `Orderpayment` (`Payment_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_item`
--
ALTER TABLE `order_item`
  ADD KEY `Order_id` (`Order_id`),
  ADD KEY `Product_id` (`Product_id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`Payment_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`Product_id`);

--
-- Indexes for table `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`Product_id`,`Order_id`),
  ADD KEY `Orderreview` (`Order_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `contact`
--
ALTER TABLE `contact`
  MODIFY `contact_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `delivery`
--
ALTER TABLE `delivery`
  MODIFY `Delivery_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `Order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `Payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `productcart` FOREIGN KEY (`Product_id`) REFERENCES `product` (`Product_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `usercart` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `manage_order`
--
ALTER TABLE `manage_order`
  ADD CONSTRAINT `manageorderorder` FOREIGN KEY (`Order_id`) REFERENCES `order` (`Order_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `manageorderuser` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `manage_product`
--
ALTER TABLE `manage_product`
  ADD CONSTRAINT `manageproductproduct` FOREIGN KEY (`Product_id`) REFERENCES `product` (`Product_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `manageproductuser` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `Orderdelivery` FOREIGN KEY (`Delivery_id`) REFERENCES `delivery` (`Delivery_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Orderpayment` FOREIGN KEY (`Payment_id`) REFERENCES `payment` (`Payment_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `UserOrder` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `order_item`
--
ALTER TABLE `order_item`
  ADD CONSTRAINT `Orderitemorder` FOREIGN KEY (`Order_id`) REFERENCES `order` (`Order_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Orderitemproduct` FOREIGN KEY (`Product_id`) REFERENCES `product` (`Product_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `Orderreview` FOREIGN KEY (`Order_id`) REFERENCES `order` (`Order_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Productreview` FOREIGN KEY (`Product_id`) REFERENCES `product` (`Product_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
