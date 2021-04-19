-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2.1
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Mer 24 Mars 2021 à 11:37
-- Version du serveur :  5.7.33-0ubuntu0.16.04.1
-- Version de PHP :  7.0.33-0ubuntu0.16.04.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `gsubrena2021`
--

-- --------------------------------------------------------

--
-- Structure de la table `im2021_utilisateurs`
--

CREATE TABLE `im2021_utilisateurs` (
  `pk` int(11) NOT NULL,
  `identifiant` varchar(30) NOT NULL COMMENT 'sert de login (doit être unique)',
  `motdepasse` varchar(64) NOT NULL COMMENT 'mot de passe crypté : il faut une taille assez grande pour ne pas le tronquer',
  `nom` varchar(30) DEFAULT NULL,
  `prenom` varchar(30) DEFAULT NULL,
  `anniversaire` date DEFAULT NULL,
  `isadmin` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'type booléen'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table des utilisateurs du site';

--
-- Contenu de la table `im2021_utilisateurs`
--

INSERT INTO `im2021_utilisateurs` (`pk`, `identifiant`, `motdepasse`, `nom`, `prenom`, `anniversaire`, `isadmin`) VALUES
(1, 'admin', 'a4cbb2f3933c5016da7e83fd135ab8a48b67bf61', NULL, NULL, NULL, 1),
(2, 'gilles', 'ab9240da95937a0d51b41773eafc8ccb8e7d58b5', 'Subrenat', 'Gilles', '2000-01-01', 0),
(3, 'rita', '1811ed39aa69fa4da3c457bdf096c1f10cf81a9b', 'Zrour', 'Rita', '2001-01-02', 0);

--
-- Index pour les tables exportées
--

--
-- Index pour la table `im2021_utilisateurs`
--
ALTER TABLE `im2021_utilisateurs`
  ADD PRIMARY KEY (`pk`),
  ADD UNIQUE KEY `identifiant` (`identifiant`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `im2021_utilisateurs`
--
ALTER TABLE `im2021_utilisateurs`
  MODIFY `pk` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
