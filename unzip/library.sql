-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mar. 29 avr. 2025 à 21:12
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
-- Base de données : `library`
--

-- --------------------------------------------------------

--
-- Structure de la table `book`
--
-- Suppression de la table si elle existe déjà pour éviter les bugs

DROP TABLE IF EXISTS `book`;

-- Création de la table `book` pour stocker les livres de la bibliothèque

CREATE TABLE IF NOT EXISTS `book` (
  `idbook` int NOT NULL AUTO_INCREMENT,
  `title` varchar(75) COLLATE utf8mb4_unicode_ci NOT NULL,
  `author` varchar(75) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_publication` date NOT NULL,
  `category_idcategory` int NOT NULL,
  `disponible` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`idbook`),
  KEY `fk_book_category` (`category_idcategory`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `book`
--
-- Insertion de quelques livres connus avec leurs données associées

INSERT INTO `book` (`idbook`, `title`, `author`, `date_publication`, `category_idcategory`, `disponible`) VALUES
(1, 'Le Petit Prince', 'Antoine de Saint-Exupéry', '1943-04-06', 2, 1),
(2, '1984', 'George Orwell', '1949-06-08', 1, 0),
(3, 'Dune', 'Frank Herbert', '1965-08-01', 3, 1),
(4, 'Le Rouge et le Noir', 'Stendhal', '1830-01-01', 1, 1),
(5, 'Astérix le Gaulois', 'René Goscinny', '1959-10-29', 4, 0),
(6, 'Da Vinci Code', 'Dan Brown', '2003-03-18', 5, 1),
(8, 'Les Misérables ', 'Victor Hugo', '1862-09-16', 1, 1);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `books_with_categories`
--
-- Création de la table temporaire pour définir la structure de la vue `books_with_categories`

DROP VIEW IF EXISTS `books_with_categories`;
CREATE TABLE IF NOT EXISTS `books_with_categories` (
`idbook` int
,`title` varchar(75)
,`author` varchar(75)
,`date_publication` date
,`category_name` varchar(45)
);

-- --------------------------------------------------------

--
-- Structure de la table `category`
--
-- Création de la table `category` qui contient les genres littéraires

DROP TABLE IF EXISTS `category`;
CREATE TABLE IF NOT EXISTS `category` (
  `idcategory` int NOT NULL AUTO_INCREMENT,
  `name` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`idcategory`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `category`
--
-- Insertion de quelques catégories de livres

INSERT INTO `category` (`idcategory`, `name`) VALUES
(1, 'Littérature'),
(2, 'Jeunesse'),
(3, 'Science-Fiction'),
(4, 'Bande Dessinée'),
(5, 'Roman Policier');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--
-- Table `user` pour stocker les comptes utilisateurs

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `iduser` int NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`iduser`)
) ENGINE=MyISAM AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


--
-- Insertion d’utilisateurs (mot de passe déjà sécurisé avec password_hash en PHP)

INSERT INTO `user` (`iduser`, `email`, `password`) VALUES
(28, 'loubna.abbar@edu.devinci.fr', '$2y$10$/6KaLNbfwGm7EWEnQRnjF.YJQnn.vuj9a6j/TFZqBQU04UgT9iWfC'),
(39, 'sophiaasty@gmail.com', '$2y$10$GXP0S9YNg16SWFJ/CGr/vui5KBZtvxFofzxPMSbt/jV7Iu9JPZjgW'),
(38, 'loubnaab3@gmail.com', '$2y$10$ht4SZKUN3TcN9ZI/piqoZOn8FtUHYTd38HzztxKZlLrZAE2tJWDhO');

-- 
-- Création de la vue qui joint les livres à leur catégorie pour simplifier les affichages


DROP VIEW IF EXISTS `books_with_categories`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `books_with_categories`  AS SELECT `b`.`idbook` AS `idbook`, `b`.`title` AS `title`, `b`.`author` AS `author`, `b`.`date_publication` AS `date_publication`, `c`.`name` AS `category_name` FROM (`book` `b` join `category` `c` on((`b`.`category_idcategory` = `c`.`idcategory`))) ;

--
-- Ajout de la contrainte de clé étrangère pour relier `book` à `category`
--

ALTER TABLE `book`
  ADD CONSTRAINT `fk_book_category` FOREIGN KEY (`category_idcategory`) REFERENCES `category` (`idcategory`) ON DELETE RESTRICT ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
