-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : lun. 15 juin 2026 à 12:43
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
-- Base de données : `portfolio`
--

-- --------------------------------------------------------

--
-- Structure de la table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `id` int NOT NULL AUTO_INCREMENT,
  `login` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `admin`
--

INSERT INTO `admin` (`id`, `login`, `password`) VALUES
(1, 'admin', '$2y$10$tJKd5u9SLHglYvLsjKWVmuurt/mZhx28fFTj8Zw9IsLYpq6.BGCbq');

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id`, `name`, `image`, `description`) VALUES
(11, 'InDesign', '6a2c09e69306a-Adobe-InDesign-CC-icon.svg', NULL),
(10, 'Illustrator', '6a2c09d92e8fb-Adobe-Illustrator-CC-icon.svg', NULL),
(12, 'VS Code', '6a2c09fa9eb16-Visual-Studio-Code-1.35-icon.svg', NULL),
(13, 'Figma', '6a2c0a090d884-Figma-logo.svg', NULL),
(14, 'Photoshop', '6a2c0a1800d54-Adobe-Photoshop-CC-icon.svg', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `contact`
--

DROP TABLE IF EXISTS `contact`;
CREATE TABLE IF NOT EXISTS `contact` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `date` datetime NOT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `contact`
--

INSERT INTO `contact` (`id`, `nom`, `email`, `date`, `message`) VALUES
(1, 'berti Jordan', 'berti@myepse.be', '2025-12-11 10:34:48', 'hello');

-- --------------------------------------------------------

--
-- Structure de la table `images`
--

DROP TABLE IF EXISTS `images`;
CREATE TABLE IF NOT EXISTS `images` (
  `id` int NOT NULL AUTO_INCREMENT,
  `fichier` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `id_product` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_product` (`id_product`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `date` date NOT NULL,
  `category` int NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `cover` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `products`
--

INSERT INTO `products` (`id`, `name`, `date`, `category`, `description`, `cover`) VALUES
(1, 'Ombre numérique', '2025-03-10', 14, 'Création Photoshop', '6a2c25a995e16-ombre-numerique.png'),
(2, 'Caméléon', '2025-03-11', 14, 'Création Photoshop', '6a2c258bab261-cameleon.png'),
(3, 'Doberman', '2025-03-12', 14, 'Création Photoshop', '6a2c2579ac326-doberman.png'),
(4, 'La solidité du marbre', '2025-03-13', 14, 'Création Photoshop', '6a2c25666ec7e-solidite-marbre.png'),
(5, 'Mangue', '2025-04-01', 10, 'Création Illustrator', '6a2c217f06ee0-mangue.png'),
(6, 'Cactus', '2025-04-02', 10, 'Création Illustrator', '6a2c21d1f019a-cactus.png'),
(7, 'Nature morte', '2025-04-03', 10, 'Création Illustrator', '6a2c21681ad2f-nature-morte-fleurs.png'),
(8, 'Authentage', '2025-05-01', 11, 'Création InDesign', '6a2c20d1b517c-authentage.png'),
(9, 'Tourisme en Hainaut', '2025-05-02', 11, 'Création InDesign', '6a2c210a317f6-tourisme-hainaut.png'),
(10, 'Zio', '2025-05-03', 11, 'Création InDesign', '6a2c212e36ed1-zio-menu.png'),
(11, 'Leonor INK', '2025-06-01', 12, 'Création web', '6a2c260203405-leonor-ink.png'),
(12, 'Portfolio', '2025-06-02', 12, 'Création web', '6a2c2681e9ca4-portfolio-accueil.png'),
(13, 'Himalaya', '2025-07-01', 13, 'Création Figma', '6a2c252844858-himalaya.png'),
(14, 'Couleurs 2026', '2025-07-02', 13, 'Création Figma', '6a2c22cfe0fdc-couleurs-2026.png'),
(15, 'Festival Liège', '2025-07-03', 13, 'Création Figma', '6a2c231cc239c-festival-liege.png');

-- --------------------------------------------------------

--
-- Structure de la table `skills`
--

DROP TABLE IF EXISTS `skills`;
CREATE TABLE IF NOT EXISTS `skills` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `skills`
--

INSERT INTO `skills` (`id`, `nom`, `image`) VALUES
(3, 'Photoshop', '6a2c0d368c289-Adobe-Photoshop-CC-icon.svg'),
(4, 'Illustrator', '6a2c0d49b4454-6a2c09d92e8fb-Adobe-Illustrator-CC-icon.svg'),
(5, 'InDesign', '6a2c0d610c98d-Adobe-InDesign-CC-icon.svg'),
(6, 'VS Code', '6a2c0d72c234c-Visual-Studio-Code-1.35-icon.svg'),
(7, 'PHP', '6a2c0d7cf1067-Php-logo.png'),
(8, 'ProCreate', '6a2c0d8f10c4b-Procreate.jpeg'),
(9, 'ProCreate Dreams', '6a2c0d99ca4f2-Procreate-Dreams.jpeg'),
(10, 'Figma', '6a2c0da5d7b3d-Figma-logo.svg');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
