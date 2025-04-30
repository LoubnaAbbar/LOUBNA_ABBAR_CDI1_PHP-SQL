-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mar. 29 avr. 2025 à 20:10
-- Version du serveur : 9.1.0
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `musee_orsay`
--

-- --------------------------------------------------------

--
-- Création de la table 'artwork', ce sont des oeuvres présentes dans 'mes collections.php'
--

DROP TABLE IF EXISTS `artwork`; -- Je supprime la table si elle existe déjà (utile en cas de reset pendant les tests)

CREATE TABLE IF NOT EXISTS `artwork` (
  `id_artwork` int NOT NULL AUTO_INCREMENT,         -- ID auto-incrémenté pour chaque œuvre
  `title` varchar(255) NOT NULL,                    -- Titre de l’œuvre (obligatoire)
  `artist` varchar(255) NOT NULL,                   -- Nom de l’artiste (obligatoire)
  `creation_date` date DEFAULT NULL,                -- Date de création de l’œuvre (optionnelle)
  `movement` varchar(100) DEFAULT NULL,             -- Mouvement artistique (Renaissance, Cubisme, etc.)
  `on_display` tinyint(1) DEFAULT '1',              -- Statut d’exposition (1 = visible au public, 0 = en réserve)
  PRIMARY KEY (`id_artwork`),                       -- Clé primaire
  KEY `movement_id` (`movement`)                    -- Index pour optimiser les recherches par mouvement
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Je pré-remplis la table avec quelques œuvres emblématiques

INSERT INTO `artwork` (`id_artwork`, `title`, `artist`, `creation_date`, `movement`, `on_display`) VALUES
(1, 'La Joconde', 'Léonard de Vinci', '1503-01-01', 'Renaissance', 1),
(2, 'La Nuit étoilée', 'Vincent van Gogh', '1889-06-01', 'Impressionnisme', 1),
(3, 'Les Demoiselles d\'Avignon', 'Pablo Picasso', '1907-01-01', 'Cubisme', 1),
(4, 'La Persistance de la mémoire', 'Salvador Dalí', '1931-01-01', 'Surréalisme', 1),
(5, 'La Jeune Fille à la perle', 'Johannes Vermeer', '1665-01-01', 'Baroque', 0); -- Celle-ci est actuellement en réserve

-- 
-- Création de la table `users` (utilisateurs inscrits)
-- 
DROP TABLE IF EXISTS `users`;

CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,                 -- Identifiant unique de l’utilisateur
  `email` varchar(191) NOT NULL,                    -- Adresse mail (doit être unique)
  `password` varchar(255) NOT NULL,                 -- Mot de passe hashé (je gère la sécurité côté PHP)
  `favorite_artwork` varchar(50) DEFAULT NULL,      -- L’œuvre préférée de l’utilisateur 
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP, -- Date et heure d’inscription
  `first_name` varchar(255) NOT NULL,               -- Prénom
  `last_name` varchar(255) NOT NULL,                -- Nom de famille
  `birth_date` date NOT NULL,                       -- Date de naissance
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)                      -- Pour éviter les doublons d'inscription
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Je m’ajoute en tant qu’utilisatrice test (avec mon mot de passe bien sécurisé en hash)

INSERT INTO `users` (`id`, `email`, `password`, `favorite_artwork`, `created_at`, `first_name`, `last_name`, `birth_date`) VALUES
(6, 'loubnaab3@gmail.com', '$2y$10$TqAKMeGJFBjppha/7oQHRuOhY9R5m3rcqW0amO9QgEEMfvqEZeo3C', 'Intérieur bleu', '2025-04-29 20:03:21', 'Loubna', 'Abbar', '2003-09-18');


-- Je termine la transaction proprement pour valider toutes les opérations

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
