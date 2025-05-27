-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 27, 2025 at 05:06 PM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `omnes_immobilier`
--

-- --------------------------------------------------------

--
-- Table structure for table `agents`
--

DROP TABLE IF EXISTS `agents`;
CREATE TABLE IF NOT EXISTS `agents` (
  `id_agent` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) DEFAULT NULL,
  `prenom` varchar(100) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `cv` text,
  `photo` varchar(255) NOT NULL,
  `disponibilite` text,
  `specialite_id` int DEFAULT NULL,
  PRIMARY KEY (`id_agent`),
  UNIQUE KEY `email` (`email`),
  KEY `fk_specialite_agent` (`specialite_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `agents`
--

INSERT INTO `agents` (`id_agent`, `nom`, `prenom`, `email`, `telephone`, `cv`, `photo`, `disponibilite`, `specialite_id`) VALUES
(1, 'Wipliez', 'Aaron', 'aaron.wipliez@omnesimmobilier.fr', '+33633786373', 'info_agents/CV_aaron.pdf', 'info_agents/aaron.jpg', '{\r\n  \"lundi\": [\"09:00-12:00\", \"14:00-18:00\"],\r\n  \"mardi\": [\"09:00-12:00\", \"14:00-18:00\"],\r\n  \"mercredi\": [\"09:00-12:00\", \"14:00-18:00\"],\r\n  \"jeudi\": [\"09:00-12:00\", \"14:00-18:00\"],\r\n  \"vendredi\": [\"09:00-12:00\", \"14:00-18:00\"],\r\n  \"samedi\": [\"09:00-12:00\"],\r\n  \"dimanche\": []\r\n}', 1),
(2, 'Grislain', 'Thomas', 'thomasgrislain@omnesimmobilier.fr', '+33 7 50 82 24 83', NULL, 'info_agents/thomas.png', '', 2);

-- --------------------------------------------------------

--
-- Table structure for table `biens`
--

DROP TABLE IF EXISTS `biens`;
CREATE TABLE IF NOT EXISTS `biens` (
  `id_bien` int NOT NULL AUTO_INCREMENT,
  `categorie` enum('Immobilier résidentiel','Immobilier commercial','Terrain','Appartement à louer','Immobiliers en vente par enchère') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `surface` int DEFAULT NULL,
  `pieces` int DEFAULT NULL,
  `chambres` int DEFAULT NULL,
  `prix` decimal(12,2) DEFAULT NULL,
  `adresse` text,
  `agent_id` int DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `categorie_id` int DEFAULT NULL,
  PRIMARY KEY (`id_bien`),
  KEY `agent_id` (`agent_id`),
  KEY `fk_categorie_bien` (`categorie_id`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `biens`
--

INSERT INTO `biens` (`id_bien`, `categorie`, `surface`, `pieces`, `chambres`, `prix`, `adresse`, `agent_id`, `photo`, `categorie_id`) VALUES
(1, 'Immobilier résidentiel', 100, 3, 2, 980000.00, '3 Rue Sextius Michel, 75015 Paris', NULL, 'Photos_biens/1.jpg', 1),
(2, 'Immobilier résidentiel', 80, 2, 1, 1050000.00, '45 Avenue des Entrepreneurs, 75015 Paris', NULL, 'Photos_biens/2.jpg', 1),
(3, 'Immobilier résidentiel', 46, 1, 1, 367000.00, '8 Boulevard Magenta, 75010 Paris', NULL, 'Photos_biens/3.jpg', 1),
(4, 'Immobilier résidentiel', 52, 3, 1, 6750000.00, '8 Rue de Lappe, 75011 Paris', NULL, 'Photos_Biens/4.jpg', 1),
(5, 'Immobilier résidentiel', 145, 5, 4, 2100000.00, '14 Avenue de Lowendal, 75007 Paris', NULL, 'Photos_Biens/5.jpg', 1),
(6, 'Immobilier commercial', 368, NULL, NULL, 3450000.00, '4 Rue du Sentier, 75002 Paris', NULL, 'Photos_biens/6.jpg', 2),
(7, 'Immobilier commercial', 920, NULL, NULL, 7396800.00, '11 Rue de Cambrai, 75019 Paris', NULL, 'Photos_biens/7.jpg', 2),
(8, 'Immobilier commercial', 410, NULL, NULL, 3296400.00, '25 Rue des Frigos, 75013 Paris', NULL, 'Photos_biens/8.jpg', 2),
(9, 'Immobilier commercial', 180, NULL, NULL, 1447200.00, '3 Avenue George V, 75008 Paris', NULL, 'Photos_biens/9.jpg', 2),
(10, 'Immobilier commercial', 260, NULL, NULL, 2090400.00, '14 Rue de Courcelles, 75017 Paris', NULL, 'Photos_biens/10.jpg', 2),
(11, 'Terrain', 1200, NULL, NULL, 360000.00, '12 Rue des Bois, 91000 Évry-Courcouronnes', NULL, 'Photos_biens/11.jpg', 3),
(12, 'Terrain', 980, NULL, NULL, 315000.00, '7 Chemin des Vignes, 77100 Meaux', NULL, 'Photos_biens/12.jpg', 3),
(13, 'Terrain', 1500, NULL, NULL, 450000.00, '23 Route de Gometz, 91400 Orsay', NULL, 'Photos_biens/13.jpg', 3),
(14, 'Terrain', 800, NULL, NULL, 248000.00, '4 Rue du Général Leclerc, 78120 Rambouillet', NULL, 'Photos_biens/14.jpg', 3),
(15, 'Terrain', 1100, NULL, NULL, 330000.00, '5 Allée du Château, 95500 Gonesse', NULL, 'Photos_biens/15.jpg', 3),
(16, 'Appartement à louer', 45, 2, 1, 1450.00, '18 Rue Oberkampf, 75011 Paris', NULL, 'Photos_biens/16.jpg', 4),
(17, 'Appartement à louer', 60, 3, 2, 2100.00, '30 Rue de Maubeuge, 75009 Paris', NULL, 'Photos_biens/17.jpg', 4),
(18, 'Appartement à louer', 38, 2, 1, 1300.00, '5 Rue des Martyrs, 75009 Paris', NULL, 'Photos_biens/18.jpg', 4),
(19, 'Appartement à louer', 75, 4, 3, 2700.00, '22 Rue du Faubourg Saint-Antoine, 75012 Paris', NULL, 'Photos_biens/19.jpg', 4),
(20, 'Appartement à louer', 90, 4, 2, 3200.00, '41 Avenue Mozart, 75016 Paris', NULL, 'Photos_biens/20.jpg', 4),
(21, 'Immobiliers en vente par enchère', 120, 5, 3, 1350000.00, '12 Rue du commerce, 75015 Paris', NULL, 'Photos_biens/21.jpg', 5),
(22, 'Immobiliers en vente par enchère', 70, 3, 2, 640000.00, '19 Rue Bachaumont, 75002 Paris', NULL, 'Photos_biens/22.jpg', 5),
(23, 'Immobiliers en vente par enchère', 95, 4, 2, 900000.00, '6 Rue du Louvre, 75001 Paris', NULL, 'Photos_biens/23.jpg', 5),
(24, 'Immobiliers en vente par enchère', 75, 3, 2, 680000.00, '28 Rue Sedaine, 75011 Paris', NULL, 'Photos_biens/24.jpg', 5),
(25, 'Immobiliers en vente par enchère', 85, 3, 2, 720000.00, '15 Rue Guénégaud, 75006 Paris', NULL, 'Photos_biens/25.jpg', 5);

-- --------------------------------------------------------

--
-- Table structure for table `rdvs`
--

DROP TABLE IF EXISTS `rdvs`;
CREATE TABLE IF NOT EXISTS `rdvs` (
  `id_rdv` int NOT NULL AUTO_INCREMENT,
  `client_id` int DEFAULT NULL,
  `agent_id` int DEFAULT NULL,
  `bien_id` int DEFAULT NULL,
  `date_rdv` datetime DEFAULT NULL,
  `statut` enum('confirmé','annulé','en attente') DEFAULT NULL,
  PRIMARY KEY (`id_rdv`),
  KEY `client_id` (`client_id`),
  KEY `agent_id` (`agent_id`),
  KEY `bien_id` (`bien_id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `rdvs`
--

INSERT INTO `rdvs` (`id_rdv`, `client_id`, `agent_id`, `bien_id`, `date_rdv`, `statut`) VALUES
(1, 1, 1, 1, '2025-05-16 12:08:00', 'annulé'),
(2, 1, 1, 12, '2025-05-27 16:01:00', 'annulé'),
(3, 1, 1, 21, '2025-05-17 18:03:00', 'annulé'),
(4, 1, 1, 13, '2025-05-28 20:28:00', 'annulé'),
(5, 1, 2, 1, '2025-05-17 16:37:00', 'annulé'),
(6, 1, 1, 1, '2025-05-29 16:37:00', 'annulé'),
(7, 1, 1, 10, '2025-05-08 17:23:00', 'annulé'),
(8, 1, 1, 1, '2025-05-10 17:24:00', 'en attente');

-- --------------------------------------------------------

--
-- Table structure for table `specialites`
--

DROP TABLE IF EXISTS `specialites`;
CREATE TABLE IF NOT EXISTS `specialites` (
  `id_specialite` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  PRIMARY KEY (`id_specialite`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `specialites`
--

INSERT INTO `specialites` (`id_specialite`, `nom`) VALUES
(1, 'Immobilier résidentiel'),
(2, 'Immobilier commercial'),
(3, 'Terrains'),
(4, 'Biens en location'),
(5, 'Biens par enchère');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id_user` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) DEFAULT NULL,
  `prenom` varchar(100) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `mot_de_passe` varchar(255) DEFAULT NULL,
  `role` enum('client','agent','admin') DEFAULT NULL,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `nom`, `prenom`, `email`, `mot_de_passe`, `role`) VALUES
(1, 'Wipliez', 'Aaron', 'aaronwipliez@gmail.com', '$2y$10$yfB3Ehb/zYc1MvOebEF.zOhR.ZYh08czYpYU21i3Y3Q7uEwks2/ZS', ''),
(2, 'Boutry', 'Xaviere', 'xaviereboutry@gmail.com', '$2y$10$us9FwiCPuJJu9S1D2XdNBefJixgw5Zx3jOGyeqCXHVhm0/IO.XbIe', '');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
