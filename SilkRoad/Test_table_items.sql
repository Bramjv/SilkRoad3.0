-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Lis 13, 2023 at 10:59 AM
-- Wersja serwera: 10.4.28-MariaDB
-- Wersja PHP: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `aukcja`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `auction_items`
--

CREATE TABLE `auction_items` (
  `id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `image_path` varchar(255) DEFAULT 'uploads/noimh.jpg',
  `user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Dumping data for table `auction_items`
--

INSERT INTO `auction_items` (`id`, `product_name`, `price`, `quantity`, `description`, `image_path`, `user_id`, `created_at`) VALUES
(1, 'kot', 32.00, 503, 'chyba żyje', 'uploads/pies.jpg', 1, '2023-11-13 08:59:25'),
(2, '2x owieczka', 12.00, 40221, 'owoieczka zestaw ultra super mega giga owieczka', 'uploads/pudlo.jpg', 1, '2023-11-13 09:00:55'),
(3, 'kot', 123.00, 12, 'dsadwawdads', 'uploads/noimh.jpg', 1, '2023-11-13 09:11:53'),
(4, 'kot', 42.00, 32, 'kot kotowicz', 'uploads/pudlo.jpg', 1, '2023-11-13 09:44:07'),
(5, 'kot', 42.00, 32, 'kot kotowicz', 'uploads/pudlo.jpg', 1, '2023-11-13 09:44:09'),
(6, 'kot', 42.00, 32, 'kot kotowicz', 'uploads/pudlo.jpg', 1, '2023-11-13 09:44:11'),
(7, 'kot', 42.00, 32, 'kot kotowicz', 'uploads/pudlo.jpg', 1, '2023-11-13 09:44:12');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `auction_items`
--
ALTER TABLE `auction_items`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `auction_items`
--
ALTER TABLE `auction_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
