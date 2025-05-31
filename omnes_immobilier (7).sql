-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 31, 2025 at 04:28 PM
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
  `specialite_id` int DEFAULT NULL,
  PRIMARY KEY (`id_agent`),
  UNIQUE KEY `email` (`email`),
  KEY `fk_specialite_agent` (`specialite_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `agents`
--

INSERT INTO `agents` (`id_agent`, `nom`, `prenom`, `email`, `telephone`, `cv`, `photo`, `specialite_id`) VALUES
(1, 'Wipliez', 'Aaron', 'aaron.wipliez@omnesimmobilier.fr', '+33633786373', 'info_agents/CV_aaron.pdf', 'info_agents/aaron.jpg', 2),
(2, 'Grislain', 'Thomas', 'thomasgrislain@omnesimmobilier.fr', '+33 7 50 82 24 83', 'CV_agents/A4 - 34.pdf', 'info_agents/thomas.png', 2),
(3, 'Stephan', 'Francois', 'fstephan@omnesimmobilier.fr', '0612345678', '', 'info_agents/stephan.jpg', 3),
(6, 'Mbappe', 'Kylian', 'kmb@omnesimmobilier.fr', '0612345676', '<br />\r\n<font size=\'1\'><table class=\'xdebug-error xe-deprecated\' dir=\'ltr\' border=\'1\' cellspacing=\'0\' cellpadding=\'1\'>\r\n<tr><th align=\'left\' bgcolor=\'#f57900\' colspan=', 'info_agents/mbappe.jpg', 5);

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
  `vendu` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id_bien`),
  KEY `agent_id` (`agent_id`),
  KEY `fk_categorie_bien` (`categorie_id`)
) ENGINE=MyISAM AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `biens`
--

INSERT INTO `biens` (`id_bien`, `categorie`, `surface`, `pieces`, `chambres`, `prix`, `adresse`, `agent_id`, `photo`, `categorie_id`, `vendu`) VALUES
(1, 'Immobilier résidentiel', 100, 3, 2, 980000.00, '3 Rue Sextius Michel, 75015 Paris', NULL, 'Photos_biens/1.jpg', 1, 0),
(2, 'Immobilier résidentiel', 80, 2, 1, 1050000.00, '45 Avenue des Entrepreneurs, 75015 Paris', NULL, 'Photos_biens/2.jpg', 1, 0),
(3, 'Immobilier résidentiel', 46, 1, 1, 367000.00, '8 Boulevard Magenta, 75010 Paris', NULL, 'Photos_biens/3.jpg', 1, 1),
(4, 'Immobilier résidentiel', 52, 3, 1, 6750000.00, '8 Rue de Lappe, 75011 Paris', NULL, 'Photos_Biens/4.jpg', 1, 0),
(5, 'Immobilier résidentiel', 145, 5, 4, 2100000.00, '14 Avenue de Lowendal, 75007 Paris', NULL, 'Photos_Biens/5.jpg', 1, 0),
(6, 'Immobilier commercial', 368, NULL, NULL, 3450000.00, '4 Rue du Sentier, 75002 Paris', NULL, 'Photos_biens/6.jpg', 2, 0),
(7, 'Immobilier commercial', 920, NULL, NULL, 7396800.00, '11 Rue de Cambrai, 75019 Paris', NULL, 'Photos_biens/7.jpg', 2, 0),
(8, 'Immobilier commercial', 410, NULL, NULL, 3296400.00, '25 Rue des Frigos, 75013 Paris', NULL, 'Photos_biens/8.jpg', 2, 0),
(9, 'Immobilier commercial', 180, NULL, NULL, 1447200.00, '3 Avenue George V, 75008 Paris', NULL, 'Photos_biens/9.jpg', 2, 0),
(10, 'Immobilier commercial', 260, NULL, NULL, 2090400.00, '14 Rue de Courcelles, 75017 Paris', NULL, 'Photos_biens/10.jpg', 2, 0),
(11, 'Terrain', 1200, NULL, NULL, 360000.00, '12 Rue des Bois, 91000 Évry-Courcouronnes', NULL, 'Photos_biens/11.jpg', 3, 0),
(12, 'Terrain', 980, NULL, NULL, 315000.00, '7 Chemin des Vignes, 77100 Meaux', NULL, 'Photos_biens/12.jpg', 3, 0),
(13, 'Terrain', 1500, NULL, NULL, 450000.00, '23 Route de Gometz, 91400 Orsay', NULL, 'Photos_biens/13.jpg', 3, 0),
(14, 'Terrain', 800, NULL, NULL, 248000.00, '4 Rue du Général Leclerc, 78120 Rambouillet', NULL, 'Photos_biens/14.jpg', 3, 0),
(15, 'Terrain', 1100, NULL, NULL, 330000.00, '5 Allée du Château, 95500 Gonesse', NULL, 'Photos_biens/15.jpg', 3, 0),
(16, 'Appartement à louer', 45, 2, 1, 1450.00, '18 Rue Oberkampf, 75011 Paris', NULL, 'Photos_biens/16.jpg', 4, 0),
(17, 'Appartement à louer', 60, 3, 2, 2100.00, '30 Rue de Maubeuge, 75009 Paris', NULL, 'Photos_biens/17.jpg', 4, 0),
(18, 'Appartement à louer', 38, 2, 1, 1300.00, '5 Rue des Martyrs, 75009 Paris', NULL, 'Photos_biens/18.jpg', 4, 0),
(19, 'Appartement à louer', 75, 4, 3, 2700.00, '22 Rue du Faubourg Saint-Antoine, 75012 Paris', NULL, 'Photos_biens/19.jpg', 4, 0),
(20, 'Appartement à louer', 90, 4, 2, 3200.00, '41 Avenue Mozart, 75016 Paris', NULL, 'Photos_biens/20.jpg', 4, 0),
(21, 'Immobiliers en vente par enchère', 120, 5, 3, 1350000.00, '12 Rue du commerce, 75015 Paris', NULL, 'Photos_biens/21.jpg', 5, 0),
(22, 'Immobiliers en vente par enchère', 70, 3, 2, 640000.00, '19 Rue Bachaumont, 75002 Paris', NULL, 'Photos_biens/22.jpg', 5, 0),
(23, 'Immobiliers en vente par enchère', 95, 4, 2, 900000.00, '6 Rue du Louvre, 75001 Paris', NULL, 'Photos_biens/23.jpg', 5, 0),
(24, 'Immobiliers en vente par enchère', 75, 3, 2, 680000.00, '28 Rue Sedaine, 75011 Paris', NULL, 'Photos_biens/24.jpg', 5, 0),
(25, 'Immobiliers en vente par enchère', 85, 3, 2, 720000.00, '15 Rue Guénégaud, 75006 Paris', NULL, 'Photos_biens/25.jpg', 5, 0),
(27, 'Appartement à louer', 63, 2, 1, 1700.00, '24 Boulevard Murat', 6, 'Photos_biens/26.jpg', NULL, 0),
(35, 'Terrain', 567, 0, 0, 678900.00, '24 Boulevard Murat', 3, 'Photos_biens/27.jpg', 4, 1);

-- --------------------------------------------------------

--
-- Table structure for table `disponibilites`
--

DROP TABLE IF EXISTS `disponibilites`;
CREATE TABLE IF NOT EXISTS `disponibilites` (
  `id_dispo` int NOT NULL AUTO_INCREMENT,
  `agent_id` int NOT NULL,
  `jour` enum('lundi','mardi','mercredi','jeudi','vendredi','samedi','dimanche') DEFAULT NULL,
  `heure_debut` time NOT NULL,
  `heure_fin` time NOT NULL,
  PRIMARY KEY (`id_dispo`),
  KEY `agent_id` (`agent_id`)
) ENGINE=MyISAM AUTO_INCREMENT=72 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `disponibilites`
--

INSERT INTO `disponibilites` (`id_dispo`, `agent_id`, `jour`, `heure_debut`, `heure_fin`) VALUES
(71, 6, 'lundi', '10:00:00', '12:00:00'),
(70, 3, 'lundi', '10:00:00', '12:00:00'),
(69, 2, 'lundi', '10:00:00', '12:00:00'),
(68, 1, 'samedi', '08:30:00', '16:00:00'),
(67, 1, 'vendredi', '09:00:00', '16:00:00'),
(66, 1, 'jeudi', '08:00:00', '14:30:00'),
(65, 1, 'mercredi', '09:00:00', '16:00:00'),
(64, 1, 'mardi', '08:00:00', '13:30:00'),
(63, 1, 'lundi', '08:30:00', '11:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `evenements`
--

DROP TABLE IF EXISTS `evenements`;
CREATE TABLE IF NOT EXISTS `evenements` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) NOT NULL,
  `contenu` text NOT NULL,
  `image_article` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `evenements`
--

INSERT INTO `evenements` (`id`, `titre`, `contenu`, `image_article`) VALUES
(1, 'Evènement de la semaine : Portes Ouvertes', 'Cette semaine, Omnes Immobilier a l\'honneur de vous inviter à ses Portes Ouvertes, qui se tiendront du 1er au 3 juin, de 10h à 19h sans interruption. Située dans le 15e arrondissement de Paris, à deux pas de la station Bir-Hakeim (ligne 6) et à proximité du RER C – Champ de Mars Tour Eiffel, l’agence vous accueille dans ses locaux rue Sextius Michel pour vous faire découvrir l’ensemble de ses offres.\r\n\r\nSpécialisée dans l’immobilier résidentiel, l’immobilier commercial, les ventes aux enchères, ainsi que la location d’appartements et la vente de terrains, Omnes Immobilier vous propose un large choix de biens situés principalement à Paris et ses alentours.\r\n\r\nDurant ces trois journées exceptionnelles, nos agents seront présents pour vous accompagner, répondre à toutes vos questions, vous conseiller dans vos projets immobiliers, et vous faire visiter les biens qui vous intéressent. C’est l’occasion idéale pour concrétiser vos projets, qu’il s’agisse d’un investissement, d’un premier achat ou d’une location.\r\n\r\nNous vous attendons nombreux pour partager avec vous notre passion de l\'immobilier, dans une ambiance conviviale et professionnelle !', 'image_jpo.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `infos_bancaires`
--

DROP TABLE IF EXISTS `infos_bancaires`;
CREATE TABLE IF NOT EXISTS `infos_bancaires` (
  `id_bancaire` int NOT NULL AUTO_INCREMENT,
  `id_user` int DEFAULT NULL,
  `nom_titulaire` varchar(100) DEFAULT NULL,
  `numero_carte` varchar(20) DEFAULT NULL,
  `date_expiration` date DEFAULT NULL,
  `code_cvc` varchar(4) DEFAULT NULL,
  PRIMARY KEY (`id_bancaire`),
  KEY `id_user` (`id_user`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `infos_bancaires`
--

INSERT INTO `infos_bancaires` (`id_bancaire`, `id_user`, `nom_titulaire`, `numero_carte`, `date_expiration`, `code_cvc`) VALUES
(1, 11, 'Donald Trump ', '1234567812341234', '2034-01-23', '1234'),
(2, 6, 'Jorge Mendez', '1234567812345678', '2027-03-17', '123');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `id_message` int NOT NULL AUTO_INCREMENT,
  `client_id` int NOT NULL,
  `agent_id` int NOT NULL,
  `contenu` text NOT NULL,
  `date_envoi` datetime DEFAULT CURRENT_TIMESTAMP,
  `lu` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id_message`),
  KEY `client_id` (`client_id`),
  KEY `agent_id` (`agent_id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id_message`, `client_id`, `agent_id`, `contenu`, `date_envoi`, `lu`) VALUES
(1, 1, 2, 'jkk', '2025-05-28 12:33:01', 0),
(2, 1, 2, 'dzdzfz', '2025-05-28 12:33:07', 0),
(3, 1, 2, 'test1', '2025-05-28 12:34:34', 0),
(4, 1, 2, 'testxav', '2025-05-28 12:37:13', 0),
(5, 1, 2, 'fred', '2025-05-28 12:37:44', 0),
(6, 1, 1, 'test2', '2025-05-28 12:38:04', 0),
(7, 1, 1, 'test2', '2025-05-28 12:39:51', 0),
(8, 1, 2, 'tete', '2025-05-28 12:40:18', 0);

-- --------------------------------------------------------

--
-- Table structure for table `paiements`
--

DROP TABLE IF EXISTS `paiements`;
CREATE TABLE IF NOT EXISTS `paiements` (
  `id_paiement` int NOT NULL AUTO_INCREMENT,
  `id_client` int DEFAULT NULL,
  `id_bien` int DEFAULT NULL,
  `montant` decimal(12,2) DEFAULT NULL,
  `date_paiement` datetime DEFAULT CURRENT_TIMESTAMP,
  `statut` varchar(20) NOT NULL DEFAULT 'non payé',
  PRIMARY KEY (`id_paiement`),
  KEY `id_client` (`id_client`),
  KEY `id_bien` (`id_bien`)
) ;

--
-- Dumping data for table `paiements`
--

INSERT INTO `paiements` (`id_paiement`, `id_client`, `id_bien`, `montant`, `date_paiement`, `statut`) VALUES
(1, 6, 35, 678900.00, '2025-05-31 17:28:16', 'payé');

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
  KEY `agent_id` (`agent_id`),
  KEY `bien_id` (`bien_id`),
  KEY `fk_client` (`client_id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
(8, 1, 1, 1, '2025-05-10 17:24:00', 'annulé'),
(9, 1, 2, 20, '2025-05-28 10:30:00', 'annulé'),
(10, 1, 1, 1, '2025-05-28 10:34:00', 'annulé'),
(11, 1, 1, 1, '2025-05-28 10:39:00', 'annulé'),
(12, 1, 2, 1, '2025-05-23 12:07:00', 'annulé'),
(13, 1, 2, 1, '2025-05-23 12:07:00', 'annulé'),
(14, 1, 2, 1, '2025-05-23 12:07:00', 'annulé'),
(15, 1, 3, 16, '2025-06-05 17:27:00', 'annulé'),
(16, 1, 1, 15, '2025-05-22 17:55:00', 'annulé'),
(17, 1, 1, 1, '2025-05-31 18:15:00', 'annulé'),
(18, 1, 2, 15, '2025-05-28 18:16:00', 'en attente'),
(19, 1, 1, 1, '2025-05-28 18:18:00', 'en attente'),
(20, 11, 1, 3, '2025-05-28 18:22:00', 'en attente'),
(21, 6, 3, 1, '2025-05-31 18:22:00', 'annulé');

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
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `nom`, `prenom`, `email`, `mot_de_passe`, `role`) VALUES
(1, 'Wipliez', 'Aaron', 'aaronwipliez@gmail.com', 'azerty', 'agent'),
(2, 'Boutry', 'Xaviere', 'xaviereboutry@gmail.com', '$2y$10$us9FwiCPuJJu9S1D2XdNBefJixgw5Zx3jOGyeqCXHVhm0/IO.XbIe', 'client'),
(3, 'tung', 'sahur', 'tungsahur@gmail.com', '$2y$10$U16llOc1Ou8pcOdwWfdUVOS072n7gnbcqhS5GJb8lppF1gyNuzsp.', 'client'),
(5, 'Grislain', 'Thomas', 'thomasgrislain@omnesimmobilier.fr', '$2y$10$h/OdX.J5tjmPOaLZDsB/Ce3Ip1ACfFKNn3pYYE/5htHTUdljALLTK', 'agent'),
(6, 'perez', 'jorge', 'jorgemendez@gmail.com', '$2y$10$0nJpYXveenzAtquB2R/tpukHaPFjACST3Uj48QY2Zr2zah34ykuae', 'client'),
(8, 'Stephan', 'Francois', 'fstephan@omnesimmobilier.fr', '$2y$10$pQYqC7raRDMW2n9eUzDYROsa9zVfuJo76FPpU5WZe89XHXiXpI4MO', 'agent'),
(10, 'macron', 'manu', 'manumacron@omnesimmobilier', '$2y$10$Hwn/jPq2McICsM7.NlsyoOvs1O3XmTXK2PvEI/Hvdy12tF5y5qjam', 'agent'),
(11, 'trump', 'donald', 'dtrump@gmail.com', '$2y$10$DOVPf/.MTSWtyIb3Qv1ph.HmK6d08e2m3g1nTL.ZJRF06xDvIvVUy', 'client'),
(12, 'Mbappe', 'Kylian', 'kmb@omnesimmobilier.fr', '$2y$10$g1SJ2erhuqUbS64AUn25tOYF8b4WZSMjWwHqMtaWJ.VhVyDbyn1x2', 'agent'),
(14, 'Omnes', 'Admin', 'ao@omnesimmobilier.fr', '$2y$10$SWhyZqkIYyW8M5OnStY2N.GIqn9n6un9K/WuJfzBPB8rL226S.NxG', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `ventes`
--

DROP TABLE IF EXISTS `ventes`;
CREATE TABLE IF NOT EXISTS `ventes` (
  `id_vente` int NOT NULL AUTO_INCREMENT,
  `id_user` int NOT NULL,
  `id_bien` int NOT NULL,
  `date_vente` datetime NOT NULL,
  `montant` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id_vente`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `ventes`
--

INSERT INTO `ventes` (`id_vente`, `id_user`, `id_bien`, `date_vente`, `montant`) VALUES
(1, 6, 3, '2025-05-31 15:42:28', 367000.00),
(2, 6, 30, '2025-05-31 16:29:45', 220000.00),
(3, 6, 31, '2025-05-31 16:33:32', 2345600.00),
(4, 6, 32, '2025-05-31 16:38:15', 23456700.00),
(5, 6, 35, '2025-05-31 17:28:16', 678900.00);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
