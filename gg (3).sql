-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 26, 2025 at 11:34 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gg`
--

-- --------------------------------------------------------

--
-- Table structure for table `booths`
--

CREATE TABLE `booths` (
  `booth_id` int(11) NOT NULL,
  `booth_name` varchar(50) NOT NULL,
  `location` varchar(100) DEFAULT NULL,
  `capacity` int(11) DEFAULT NULL,
  `prices` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `booths`
--

INSERT INTO `booths` (`booth_id`, `booth_name`, `location`, `capacity`, `prices`) VALUES
(1, 'Arcade Corner', 'Ground Floor', 13, 15.00),
(2, 'VR Experience Booth', 'Second Floor', 9, 25.00),
(3, 'Retro Gaming Zone', 'First Floor', 13, 18.00),
(4, 'Multiplayer Station', 'Third Floor', 8, 12.50),
(5, 'Racing Simulator', 'Ground Floor', 5, 30.00),
(6, 'Kids Fun Area', 'Second Floor', 12, 10.00),
(7, 'GG', 'Dhaka', 0, 50.00);

-- --------------------------------------------------------

--
-- Table structure for table `booth_bookings`
--

CREATE TABLE `booth_bookings` (
  `booking_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `booth_id` int(11) NOT NULL,
  `booking_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `total_cost` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `booth_bookings`
--

INSERT INTO `booth_bookings` (`booking_id`, `user_id`, `booth_id`, `booking_date`, `start_time`, `end_time`, `total_cost`) VALUES
(9, 2, 7, '2025-01-31', '09:00:00', '10:00:00', 50.00),
(10, 4, 1, '2025-01-28', '09:00:00', '10:00:00', 15.00),
(11, 1, 1, '2025-01-27', '09:00:00', '10:00:00', 15.00),
(16, 3, 2, '2025-01-28', '13:00:00', '14:00:00', 0.00),
(17, 3, 5, '2025-01-28', '09:00:00', '10:00:00', 0.00),
(18, 3, 3, '2025-01-28', '13:00:00', '14:00:00', 18.00),
(19, 3, 1, '2025-01-30', '11:00:00', '12:00:00', 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `games`
--

CREATE TABLE `games` (
  `game_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `platform` varchar(50) NOT NULL,
  `genre` varchar(50) DEFAULT NULL,
  `price_per_day` decimal(10,2) NOT NULL,
  `stock_quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `games`
--

INSERT INTO `games` (`game_id`, `title`, `platform`, `genre`, `price_per_day`, `stock_quantity`) VALUES
(1, 'The Legend of Zelda: Breath of the Wild', 'Nintendo Switch', 'Adventure', 5.99, 4),
(2, 'Elden Ring', 'PlayStation 5', 'RPG', 7.99, 5),
(3, 'Halo Infinite', 'Xbox Series X', 'Shooter', 6.99, 4),
(4, 'Minecraft', 'PC', 'Sandbox', 4.99, 12),
(5, 'FIFA 24', 'PlayStation 5', 'Sports', 5.49, 9),
(6, 'Mario Kart 8 Deluxe', 'Nintendo Switch', 'Racing', 4.99, 9),
(7, 'Call of Duty: Modern Warfare II', 'Xbox Series X', 'Shooter', 6.49, 8),
(8, 'Hogwarts Legacy', 'PC', 'Action RPG', 7.49, 0);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`payment_id`, `user_id`, `amount`, `payment_date`, `description`) VALUES
(11, 3, 15.98, '2025-01-26 20:58:47', 'Game Rental Payment'),
(70, 3, 4.99, '2025-01-26 21:29:36', 'Game Rental Payment'),
(71, 3, 4.99, '2025-01-26 21:29:39', 'Game Rental Payment'),
(93, 3, 0.00, '2025-01-26 21:40:03', 'Game Rental Payment'),
(94, 3, 0.00, '2025-01-26 21:40:03', 'Game Rental Payment'),
(95, 3, 0.00, '2025-01-26 21:40:04', 'Game Rental Payment'),
(96, 3, 25.00, '2025-01-26 21:44:01', 'Booth Booking Payment'),
(97, 3, 30.00, '2025-01-26 21:47:11', 'Booth Booking Payment'),
(98, 3, 5.99, '2025-01-26 22:08:36', 'Game Rental Payment'),
(99, 3, 15.00, '2025-01-26 22:22:32', 'Booth Booking Payment');

-- --------------------------------------------------------

--
-- Table structure for table `rentals`
--

CREATE TABLE `rentals` (
  `rental_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `game_id` int(11) NOT NULL,
  `rental_date` date NOT NULL,
  `return_date` date NOT NULL,
  `total_cost` decimal(10,2) NOT NULL,
  `status` enum('Rented','Returned') DEFAULT 'Rented'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `rentals`
--

INSERT INTO `rentals` (`rental_id`, `user_id`, `game_id`, `rental_date`, `return_date`, `total_cost`, `status`) VALUES
(2, 1, 5, '2025-01-24', '2025-01-27', 16.47, ''),
(3, 1, 2, '2025-01-25', '2025-01-30', 39.95, ''),
(4, 1, 3, '2025-01-25', '2025-01-31', 41.94, ''),
(5, 1, 1, '2025-01-25', '2025-01-30', 29.95, ''),
(6, 1, 1, '2025-01-25', '2025-01-30', 29.95, ''),
(7, 1, 1, '2025-01-25', '2025-01-27', 11.98, ''),
(8, 1, 1, '2025-01-25', '2025-01-27', 11.98, ''),
(9, 1, 1, '2025-01-25', '2025-01-30', 29.95, ''),
(10, 1, 1, '2025-01-25', '2025-01-30', 29.95, ''),
(11, 1, 1, '2025-01-27', '2025-01-29', 11.98, ''),
(12, 1, 1, '2025-01-27', '2025-01-29', 11.98, ''),
(13, 1, 3, '2025-01-27', '2025-01-31', 27.96, ''),
(14, 1, 3, '2025-01-27', '2025-01-31', 27.96, ''),
(15, 1, 8, '2025-01-27', '2025-01-28', 7.49, ''),
(16, 1, 8, '2025-01-27', '2025-01-28', 7.49, ''),
(17, 2, 3, '2025-01-28', '2025-01-23', 34.95, ''),
(18, 2, 3, '2025-01-26', '2025-01-31', 34.95, ''),
(29, 3, 1, '2025-01-29', '2025-01-30', 0.00, ''),
(30, 3, 3, '2025-01-28', '2025-01-31', 20.97, ''),
(31, 3, 3, '2025-01-29', '2025-01-30', 6.99, ''),
(32, 3, 3, '2025-01-27', '2025-01-29', 13.98, ''),
(33, 3, 3, '2025-01-27', '2025-01-29', 13.98, ''),
(34, 3, 3, '2025-01-27', '2025-01-29', 13.98, '');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `review_text` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `booking_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`review_id`, `user_id`, `rating`, `review_text`, `created_at`, `booking_id`) VALUES
(4, 2, 5, 'gg', '2025-01-25 23:20:26', 9),
(5, 2, 5, 'Marattok', '2025-01-26 14:59:08', 9);

-- --------------------------------------------------------

--
-- Table structure for table `tournaments`
--

CREATE TABLE `tournaments` (
  `tournament_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `game_title` varchar(100) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `prize_pool` decimal(10,2) DEFAULT NULL,
  `status` enum('Ongoing','Upcoming','Completed') DEFAULT 'Upcoming'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tournaments`
--

INSERT INTO `tournaments` (`tournament_id`, `name`, `game_title`, `start_date`, `end_date`, `prize_pool`, `status`) VALUES
(1, 'Champions League', 'FIFA 24', '2025-02-01', '2025-02-05', 5000.00, ''),
(2, 'Battle Royale Showdown', 'PUBG', '2025-02-10', '2025-02-15', 10000.00, ''),
(3, 'Warzone Warfare', 'Call of Duty: Warzone', '2025-02-20', '2025-02-22', 7500.00, ''),
(4, 'Galaxy Race', 'Mario Kart 8', '2025-03-01', '2025-03-03', 3000.00, ''),
(5, 'Mystic Realms', 'Dota 2', '2025-03-10', '2025-03-15', 15000.00, ''),
(6, 'Street Fighter Faceoff', 'Street Fighter V', '2025-03-20', '2025-03-22', 4000.00, ''),
(7, 'Valorant Masters', 'Valorant', '2025-03-25', '2025-03-30', 20000.00, ''),
(8, 'Knights Clash', 'Clash Royale', '2025-04-01', '2025-04-03', 2500.00, '');

-- --------------------------------------------------------

--
-- Table structure for table `tournament_participants`
--

CREATE TABLE `tournament_participants` (
  `participant_id` int(11) NOT NULL,
  `tournament_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `registration_date` date NOT NULL,
  `team_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tournament_participants`
--

INSERT INTO `tournament_participants` (`participant_id`, `tournament_id`, `user_id`, `registration_date`, `team_name`) VALUES
(1, 1, 5, '2025-01-25', 'Sentinal'),
(2, 2, 4, '2025-01-25', 'fsfsf'),
(3, 2, 1, '2025-01-25', 'fafafa'),
(4, 3, 2, '2025-01-25', 'sentinal'),
(5, 3, 3, '2025-01-25', 'gg'),
(6, 2, 3, '2025-01-26', 'DRX');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone_number` varchar(15) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_card_photo` varchar(250) NOT NULL,
  `balance` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `phone_number`, `address`, `created_at`, `id_card_photo`, `balance`) VALUES
(1, 'Abu Affan', 'maffan222290@bscse.uiu.ac.bd', '$2y$10$98j7LO5bdOmrQ3SVQ3OUieDy1u8cRM176Lh0EBHiscEnQjPCK0SU2', '01796651373', 'Dhaka,Bangladesh', '2025-01-23 17:01:58', 'uploads/DALLÂ·E 2025-01-11 20.23.47 - A futuristic cyberpunk-style avatar inspired by Ready Player One. The avatar has dark curly hair, a light beard, and wears a sleek red and black jacke.webp', 0.00),
(2, 'tahmid', 'tahmid@gmail.com', '$2y$10$l67C33x3vtpv2ptRidlbs.a8r9ljY3T/Zc8x9O.esEsjhHgtdTGGS', '01404309382', 'Dhaka', '2025-01-24 11:27:15', 'uploads/wallpaperflare.com_wallpaper (6).jpg', 0.00),
(3, 'ornab', 'ornab@gmail.com', '$2y$10$FLvlj3SLoWZnLlfqIxe.VeH6tpHcyc3KPddwZjbLsOOzou.XkNycG', '0123654789', 'Dhaka', '2025-01-25 11:49:30', 'uploads/a2297448-ffab-480b-ae8d-152eeff1458d.jpeg', 219.02),
(4, 'taky', 'taky@gmail.com', '$2y$10$49d5.2bLwRGuhDMgTGXtkuWVqutp43ErrbgNW4KRLLKjKDVUCBUsS', '01236547', 'Dhaka', '2025-01-25 17:46:46', 'uploads/tahmidlogo-01.png', 0.00),
(5, 'araf', 'aa@gmail.com', '$2y$10$lSUGnqGpE.v4osbQNnNzbOyVwG6uOCW0rcU5QGFg84.1T6Cj1bp86', '0156987', 'Dhaka', '2025-01-25 18:06:24', 'uploads/Screenshot_2024-12-11_032620.png', 0.00);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `booths`
--
ALTER TABLE `booths`
  ADD PRIMARY KEY (`booth_id`);

--
-- Indexes for table `booth_bookings`
--
ALTER TABLE `booth_bookings`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `booth_id` (`booth_id`);

--
-- Indexes for table `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`game_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `rentals`
--
ALTER TABLE `rentals`
  ADD PRIMARY KEY (`rental_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `game_id` (`game_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `fk_booking_id` (`booking_id`);

--
-- Indexes for table `tournaments`
--
ALTER TABLE `tournaments`
  ADD PRIMARY KEY (`tournament_id`);

--
-- Indexes for table `tournament_participants`
--
ALTER TABLE `tournament_participants`
  ADD PRIMARY KEY (`participant_id`),
  ADD KEY `tournament_id` (`tournament_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `email_2` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `booths`
--
ALTER TABLE `booths`
  MODIFY `booth_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `booth_bookings`
--
ALTER TABLE `booth_bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `games`
--
ALTER TABLE `games`
  MODIFY `game_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;

--
-- AUTO_INCREMENT for table `rentals`
--
ALTER TABLE `rentals`
  MODIFY `rental_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tournaments`
--
ALTER TABLE `tournaments`
  MODIFY `tournament_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tournament_participants`
--
ALTER TABLE `tournament_participants`
  MODIFY `participant_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `booth_bookings`
--
ALTER TABLE `booth_bookings`
  ADD CONSTRAINT `booth_bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `booth_bookings_ibfk_2` FOREIGN KEY (`booth_id`) REFERENCES `booths` (`booth_id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `rentals`
--
ALTER TABLE `rentals`
  ADD CONSTRAINT `rentals_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rentals_ibfk_2` FOREIGN KEY (`game_id`) REFERENCES `games` (`game_id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `fk_booking_id` FOREIGN KEY (`booking_id`) REFERENCES `booth_bookings` (`booking_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `tournament_participants`
--
ALTER TABLE `tournament_participants`
  ADD CONSTRAINT `tournament_participants_ibfk_1` FOREIGN KEY (`tournament_id`) REFERENCES `tournaments` (`tournament_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tournament_participants_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
