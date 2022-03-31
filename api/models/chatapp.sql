-- phpMyAdmin SQL Dump
-- version 4.5.4.1
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Mar 29 Mars 2022 à 16:27
-- Version du serveur :  5.7.11
-- Version de PHP :  7.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `chatapp`
--

-- --------------------------------------------------------

--
-- Structure de la table `groups`
--

CREATE TABLE `groups` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Contenu de la table `groups`
--

INSERT INTO `groups` (`id`, `name`) VALUES
(669168003, 'DUT');

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `incoming_msg_id` int(11) NOT NULL,
  `outgoing_msg_id` int(11) NOT NULL,
  `msg` text COLLATE utf8_bin NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Contenu de la table `messages`
--

INSERT INTO `messages` (`id`, `incoming_msg_id`, `outgoing_msg_id`, `msg`, `createdAt`) VALUES
(1, 1614710884, 599922676, 'You are the wrost', '2022-03-24 02:11:48'),
(2, 1614710884, 599922676, 'Come on bro', '2022-03-24 02:12:20'),
(3, 1614710884, 599922676, 'test message...', '2022-03-24 07:48:38'),
(4, 599922676, 1614710884, 'bla bla car', '2022-03-24 07:57:12'),
(5, 599922676, 1614710884, 'Say hello!', '2022-03-24 08:31:06'),
(6, 25578136, 1614710884, 'You are the wrost...', '2022-03-24 08:32:22'),
(7, 1614710884, 25578136, 'Next bro...', '2022-03-24 08:45:33'),
(8, 1581509392, 1614710884, 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Debitis, amet?', '2022-03-24 10:02:02'),
(9, 1563882763, 1614710884, 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Debitis, amet?', '2022-03-24 10:11:22'),
(10, 1563882763, 1614710884, 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Debitis, amet?', '2022-03-24 10:13:03'),
(11, 1563882763, 1614710884, 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Debitis, amet?', '2022-03-24 10:15:29'),
(12, 1563882763, 1614710884, 'Debitis, amet?', '2022-03-24 10:15:50'),
(13, 1563882763, 1614710884, 'Debitis, amet?', '2022-03-24 11:41:48'),
(14, 1563882763, 1614710884, 'Debitis, amet?', '2022-03-24 11:42:32'),
(15, 1563882763, 1614710884, 'Debitis, amet?', '2022-03-24 11:51:13'),
(16, 1563882763, 1614710884, 'Debitis, amet?', '2022-03-24 11:52:18'),
(17, 1563882763, 1614710884, 'Debitis, amet?', '2022-03-24 11:53:12'),
(18, 1614710884, 599922676, 'Debitis, amet?', '2022-03-24 11:54:20'),
(19, 1614710884, 599922676, 'Debitis, amet?', '2022-03-24 12:02:19'),
(20, 1614710884, 599922676, 'Debitis, amet?', '2022-03-24 12:03:48'),
(21, 1614710884, 599922676, 'Debitis, amet?', '2022-03-24 12:05:19'),
(22, 1614710884, 599922676, 'Debitis, amet?', '2022-03-24 12:13:55'),
(23, 1614710884, 599922676, 'Debitis, amet?', '2022-03-24 12:19:26'),
(24, 1614710884, 599922676, 'Debitis, amet?', '2022-03-24 13:03:24'),
(25, 1614710884, 599922676, 'Debitis, amet?', '2022-03-24 13:04:49'),
(26, 1614710884, 599922676, 'Debitis, amet?', '2022-03-24 13:05:39'),
(27, 1614710884, 599922676, 'Debitis, amet?', '2022-03-24 13:06:40'),
(28, 1614710884, 599922676, 'Debitis, amet?', '2022-03-24 13:17:18'),
(29, 1614710884, 599922676, 'Debitis, amet?', '2022-03-24 14:04:31'),
(30, 25578136, 1614710884, 'Debitis, amet?', '2022-03-24 21:22:10'),
(31, 25578136, 1614710884, 'Others?!&gt;?', '2022-03-24 21:46:24'),
(32, 1614710884, 25578136, 'Others?!&gt;?', '2022-03-24 21:48:16'),
(33, 1614710884, 25578136, 'hey', '2022-03-24 22:33:36'),
(34, 1614710884, 25578136, 'what\'s up', '2022-03-24 22:33:53'),
(35, 25578136, 1614710884, 'I\'m fine, Thanks!!', '2022-03-24 22:41:15'),
(36, 1614710884, 25578136, 'cool!!', '2022-03-24 22:41:27'),
(37, 25578136, 1614710884, 'I\'m fine, Thanks!!', '2022-03-24 22:42:43'),
(38, 25578136, 1614710884, 'I\'m fine, Thanks!!', '2022-03-24 22:43:26'),
(39, 25578136, 1614710884, 'I\'m fine, Thanks!!', '2022-03-24 22:43:38'),
(40, 25578136, 1614710884, 'I\'m fine, Thanks!!hk', '2022-03-24 22:43:42'),
(41, 25578136, 1614710884, 'I\'m fine, Thanks!!hk', '2022-03-24 22:45:54'),
(42, 25578136, 1614710884, 'I\'m fine, Thanks!!hk', '2022-03-24 22:47:17'),
(43, 25578136, 1614710884, 'I\'m fine, Thanks!!hk', '2022-03-24 22:47:21'),
(44, 25578136, 1614710884, 'jukgyftdrwtdxfhcgjvhk', '2022-03-24 22:47:28'),
(45, 25578136, 1614710884, 'jukgyftdrwtdxfhcgjvhk', '2022-03-24 22:49:19'),
(46, 1614710884, 25578136, 'woooh!!!!!!!', '2022-03-24 22:49:40'),
(47, 25578136, 1614710884, 'fcufjvh', '2022-03-24 22:50:21'),
(48, 1614710884, 1563882763, 'hey', '2022-03-25 13:30:11'),
(49, 1614710884, 25578136, 'oohee', '2022-03-25 13:33:08'),
(50, 599922676, 1614710884, 'yo', '2022-03-25 15:06:51'),
(51, 599922676, 1614710884, 'what\'s up Bro?', '2022-03-25 15:07:11'),
(52, 599922676, 1614710884, 'hi dude!', '2022-03-25 15:07:47'),
(53, 599922676, 1614710884, 'Come on answer me!', '2022-03-25 15:08:07'),
(54, 1614710884, 599922676, 'shut up joe', '2022-03-25 15:08:41'),
(55, 1614710884, 599922676, 'go to hell!', '2022-03-25 15:08:59'),
(56, 599922676, 1614710884, 'No!!!!!!!!!!!!', '2022-03-25 15:09:15'),
(57, 1563882763, 25578136, 'Hey', '2022-03-26 02:02:35'),
(58, 1563882763, 1581509392, 'Hey girl!.', '2022-03-26 02:14:27'),
(59, 1563882763, 1581509392, 'ohi', '2022-03-26 02:14:34'),
(60, 1563882763, 1581509392, 'h', '2022-03-26 02:21:36'),
(61, 1563882763, 599922676, 'tyuiop', '2022-03-26 02:22:16'),
(62, 1563882763, 599922676, '"fhxhc"', '2022-03-26 02:22:21'),
(63, 1563882763, 1614710884, 'gjfuyjyivkk', '2022-03-26 02:28:12'),
(64, 1563882763, 1614710884, 'bobo', '2022-03-26 03:10:40');

-- --------------------------------------------------------

--
-- Structure de la table `usergroup`
--

CREATE TABLE `usergroup` (
  `groupId` int(11) NOT NULL,
  `userId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Contenu de la table `usergroup`
--

INSERT INTO `usergroup` (`groupId`, `userId`) VALUES
(669168003, 1),
(669168003, 3);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(30) COLLATE utf8_bin NOT NULL,
  `email` varchar(255) COLLATE utf8_bin NOT NULL,
  `password` varchar(255) COLLATE utf8_bin NOT NULL,
  `img` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Contenu de la table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `img`, `status`) VALUES
(25578136, 'albert', 'albert@gmail.com', '$2y$10$eLcMCHMMRaDycjasnaAEzO6Ab940GBbre3JYxe0wzypAAioGHHxFW', NULL, 0),
(599922676, 'jonny', 'jonny@gmail.com', '$2y$10$euF5mhDE5YYjZxKolsHTq.jneZ/9z8zqQhwSNhuc9EFEXUr70mBI2', NULL, 0),
(1563882763, 'Narberal Gamma', 'narberal.gamma@gmail.com', '$2y$10$Ejvswnqz1fupDcHyX6h89O9obr9M1zmDvsLnFBxqPzqIJQDmuFxNW', NULL, 1),
(1581509392, 'Youri Alpha', 'youri.alpha@gmail.com', '$2y$10$XqVSesYTsU7Pbc1/YF6/N.erEMqcSLPjxRcptb0.4za17EQmBVQum', NULL, 0),
(1614710884, 'boby', 'boby@gmail.com', '$2y$10$uilTYXh3zms6A5texX.is.Q5DuuUhy3GYwIry9WHQoSwKkQe2vdIq', NULL, 1);

--
-- Index pour les tables exportées
--

--
-- Index pour la table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Index pour la table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `usergroup`
--
ALTER TABLE `usergroup`
  ADD PRIMARY KEY (`groupId`,`userId`),
  ADD KEY `userId` (`userId`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
