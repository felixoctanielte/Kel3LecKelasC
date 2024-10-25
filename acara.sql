-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 25, 2024 at 09:15 AM
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
-- Database: `acara`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `event_name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `event_date` date NOT NULL,
  `location` varchar(255) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `event_status` enum('open','closed','canceled') DEFAULT 'open',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `max_participants` int(11) NOT NULL,
  `event_time` time NOT NULL,
  `image` varchar(255) NOT NULL,
  `banner` varchar(255) NOT NULL,
  `status` enum('open','closed','canceled') DEFAULT 'open',
  `image_url` varchar(255) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `event_image` varchar(255) DEFAULT NULL,
  `event_banner` varchar(255) DEFAULT NULL,
  `capacity` int(11) NOT NULL DEFAULT 0,
  `current_registrants` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `event_name`, `description`, `event_date`, `location`, `category`, `event_status`, `created_at`, `max_participants`, `event_time`, `image`, `banner`, `status`, `image_url`, `user_id`, `event_image`, `event_banner`, `capacity`, `current_registrants`) VALUES
(30, 'Spooky Night', 'Halloween, yang dirayakan pada tanggal 30 Oktober, adalah perayaan yang penuh dengan suasana misteri dan keceriaan. Awalnya berasal dari festival kuno Celtic, Samhain, Halloween kini menjadi acara yang dinanti-nanti di berbagai belahan dunia. Saat malam tiba, suasana menjadi semakin seram dengan berbagai hiasan, kostum, dan tradisi unik yang menjadikan perayaan ini sangat spesial.', '2024-10-30', 'Function Hall, UMN', NULL, 'open', '2024-10-24 18:28:08', 100, '20:00:00', '', '', 'open', NULL, 17, '40745c32671efabcbadcc59af3dd8af9.jpg', '40745c32671efabcbadcc59af3dd8af9.jpg', 0, 0),
(31, 'Trick Or Treat Party', 'Salah satu tradisi terpenting dalam Halloween adalah mengenakan kostum. Orang-orang dari berbagai usia berdandan dengan berbagai tema, mulai dari yang menakutkan seperti zombie dan hantu, hingga yang lucu dan kreatif seperti karakter film atau binatang. Perayaan ini sering kali diisi dengan pesta kostum, di mana para peserta dapat menunjukkan kreativitas mereka dan berkompetisi untuk menjadi yang terbaik.', '2024-11-09', 'lecture Hall, UMN', NULL, 'open', '2024-10-24 18:34:24', 80, '20:00:00', '', '', 'open', NULL, 15, '44d73edfe428e03ea0b8550eb5e9c169.jpg', '44d73edfe428e03ea0b8550eb5e9c169.jpg', 0, 0),
(32, 'Haloween coswalk', 'Tradisi &amp;quot;trick or treat&amp;quot; adalah salah satu momen paling dinanti oleh anak-anak. Mereka berkeliling dari rumah ke rumah, mengenakan kostum, dan meminta permen. Pemilik rumah biasanya siap dengan permen dan camilan untuk dibagikan. Tradisi ini tidak hanya menyenangkan bagi anak-anak, tetapi juga mempererat hubungan antar tetangga di lingkungan sekitar.', '2024-11-16', 'BSD', NULL, 'open', '2024-10-24 18:35:41', 50, '20:30:00', '', '', 'open', NULL, 15, '70d9b4da4f7d5104caf4ec5293821e48.jpg', '70d9b4da4f7d5104caf4ec5293821e48.jpg', 0, 0),
(33, 'Eternal Fright Night', 'Halloween adalah waktu yang sempurna untuk merayakan kreativitas, keberanian, dan kebersamaan. Baik dalam suasana menakutkan maupun dalam kegembiraan pesta, perayaan ini menghadirkan kesempatan bagi semua orang untuk bersenang-senang dan menciptakan kenangan tak terlupakan. Dengan setiap kostum yang dikenakan dan setiap permen yang dibagikan, Halloween terus menjadi tradisi yang dihargai dan dinantikan setiap tahun.', '2024-11-23', 'lecture Hall, UMN', NULL, 'open', '2024-10-24 18:38:46', 50, '18:00:00', '', '', 'open', NULL, 15, 'b9bd2807e5c376783233e68cd30d95aa.jpg', 'b9bd2807e5c376783233e68cd30d95aa.jpg', 0, 0),
(34, 'Carnival of Chills', 'Selain pesta kostum dan &amp;quot;trick or treat&amp;quot;, banyak acara Halloween lainnya yang diadakan, seperti festival labu, pertunjukan film horor, dan tur hantu. Aktivitas-aktivitas ini memberikan kesempatan untuk merasakan ketegangan dan keseruan yang ditawarkan Halloween. Banyak komunitas juga mengadakan kompetisi mengukir labu, di mana peserta dapat menunjukkan keterampilan seni mereka.', '2024-11-16', 'Function Hall, UMN', NULL, 'open', '2024-10-24 18:39:47', 50, '20:00:00', '', '', 'open', NULL, 15, '1c223233b9c5195d480f48ee456960b1.jpg', '1c223233b9c5195d480f48ee456960b1.jpg', 0, 0),
(35, 'News Hallowen', 'Halloween adalah waktu yang sempurna untuk merayakan kreativitas, keberanian, dan kebersamaan. Baik dalam suasana menakutkan maupun dalam kegembiraan pesta, perayaan ini menghadirkan kesempatan bagi semua orang untuk bersenang-senang dan menciptakan kenangan tak terlupakan. Dengan setiap kostum yang dikenakan dan setiap permen yang dibagikan, Halloween terus menjadi tradisi yang dihargai dan dinantikan setiap tahun.', '2024-11-23', 'BSD', NULL, 'open', '2024-10-24 18:42:04', 50, '19:00:00', '', '', 'open', NULL, 15, '0b57d03af7dff3aaa3598e446f1f26b0.jpg', '0b57d03af7dff3aaa3598e446f1f26b0.jpg', 0, 0),
(36, 'Pumpkin Pals: A Halloween Adventure', 'Join us for a delightful Halloween celebration featuring our adorable little pumpkin pal! Bring your family and friends for a magical night filled with fun activities, Halloween treats, and spooky surprises. Dress up in your favorite costumes and enjoy the seasonal charm of autumn with carving pumpkins, trick-or-treating, and heartwarming moments. This event is perfect for all ages who love a mix of cute and creepy vibes!', '2024-11-23', 'BSD', NULL, 'open', '2024-10-25 04:18:38', 50, '20:00:00', '', '', 'open', NULL, 15, 'a12daa04213f728753496df99f8cea8f.jpg', 'a12daa04213f728753496df99f8cea8f.jpg', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `registrations`
--

CREATE TABLE `registrations` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `registration_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `registrations`
--

INSERT INTO `registrations` (`id`, `user_id`, `event_id`, `registration_date`, `user_name`) VALUES
(12, 17, 30, '2024-10-24 18:50:14', NULL),
(14, 18, 34, '2024-10-24 19:59:06', NULL),
(15, 18, 35, '2024-10-24 19:59:38', NULL),
(16, 18, 33, '2024-10-24 19:59:42', NULL),
(17, 18, 32, '2024-10-24 19:59:46', NULL),
(18, 19, 34, '2024-10-24 22:36:00', NULL),
(23, 20, 32, '2024-10-25 03:57:30', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` enum('admin','user') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `profile_image`, `password`, `created_at`, `role`) VALUES
(15, 'Felix Octaniel', 'felix@admin.com', NULL, '$2y$10$S4rT0vxuA/N8lWnN.F0wq.K2xQZTBwAy0gsgLMHyF0NTwev5RgmWC', '2024-10-24 04:05:13', 'admin'),
(16, 'denito', 'denito@gmail.com', NULL, '$2y$10$o7WYAhkq52inLO2j1XugLuePy.fObQ5bDf5yL0KOOm1p7WraeHrrm', '2024-10-24 10:53:13', 'user'),
(17, 'Denita cantik', 'johan@gmail.com', 'gambar 2.jpg', '$2y$10$/JkPh1FUbQ.p62LPQtgopO1lxAvX8u0AdcpySSnchinkp//bkLBKm', '2024-10-24 13:59:43', 'user'),
(18, 'Farrel Nayaka', 'farell@gmail.com', 'WhatsApp Image 2024-10-08 at 10.59.18_acb4eedd.jpg', '$2y$10$.q2OkO3EJHF2AGXYFOlSxeiKhkWWFqcnliSs8Q4X7YdUvZtFTUeHW', '2024-10-24 19:58:49', 'user'),
(19, 'Felix Octaniel', 'felixtel@gmail.com', 'gambar 2.jpg', '$2y$10$UmezmoXUSDv2kYf.2g.2qujDYesB7Cec0gezcZ2O0fbQmdgLaZ9ey', '2024-10-24 21:37:31', 'user'),
(20, 'Parsaulian', 'lian@gmail.com', 'gambar 1.jpg', '$2y$10$ES84TuxnvtwjBKPh/uoiveDZeoOka1kmTDzKqLbN2PpsbrVCHqbnO', '2024-10-25 03:48:25', 'user'),
(21, 'Admin', 'admin123@gmail.com', NULL, '$2y$10$0wfC9U8y6jytdtb.SHGYneIkPE/bMBuzqFF7ikgv6LnnGUDCAn1bC', '2024-10-25 07:08:56', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`event_name`,`event_date`);

--
-- Indexes for table `registrations`
--
ALTER TABLE `registrations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `registrations`
--
ALTER TABLE `registrations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `registrations`
--
ALTER TABLE `registrations`
  ADD CONSTRAINT `registrations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `registrations_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
