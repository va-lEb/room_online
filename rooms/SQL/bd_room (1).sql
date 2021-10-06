-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 06 oct. 2021 à 00:25
-- Version du serveur :  5.7.31
-- Version de PHP : 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `bd_room`
--

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

DROP TABLE IF EXISTS `commande`;
CREATE TABLE IF NOT EXISTS `commande` (
  `id_commande` int(3) NOT NULL AUTO_INCREMENT,
  `id_membre` int(3) NOT NULL,
  `id_produit` int(3) NOT NULL,
  `date_enregistrement` datetime NOT NULL,
  PRIMARY KEY (`id_commande`),
  KEY `id_membre` (`id_membre`),
  KEY `id_produit` (`id_produit`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `tab_avis`
--

DROP TABLE IF EXISTS `tab_avis`;
CREATE TABLE IF NOT EXISTS `tab_avis` (
  `id_avis` int(3) NOT NULL AUTO_INCREMENT,
  `id_membre` int(3) NOT NULL,
  `id_salle` int(3) NOT NULL,
  `commentaire` text NOT NULL,
  `note` int(2) NOT NULL,
  `date_enregistrement` datetime NOT NULL,
  PRIMARY KEY (`id_avis`),
  KEY `id_membre` (`id_membre`),
  KEY `id_salle` (`id_salle`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `tab_membre`
--

DROP TABLE IF EXISTS `tab_membre`;
CREATE TABLE IF NOT EXISTS `tab_membre` (
  `id_membre` int(3) NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(20) NOT NULL,
  `mdp` varchar(60) NOT NULL,
  `nom` varchar(20) NOT NULL,
  `prenom` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `civilite` enum('m','f') NOT NULL,
  `statut` int(1) NOT NULL,
  `date_enregistrement` datetime NOT NULL,
  PRIMARY KEY (`id_membre`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `tab_membre`
--

INSERT INTO `tab_membre` (`id_membre`, `pseudo`, `mdp`, `nom`, `prenom`, `email`, `civilite`, `statut`, `date_enregistrement`) VALUES
(1, 'admin', '$2y$10$op/EVH0S6/OiT8mNoydy1OImZoVALV6gawaJwFMFIdzdW8k5VQWaa', 'ebengue', 'val', 'valery@gmail.com', 'm', 1, '2021-09-13 12:17:04'),
(2, 'cywane', '$2y$10$iz/j31vkt2TfstuUYWjX1..pjYt.CP1x4dL0tbmkX47auluf7L5RS', 'Ebengué', 'Valéry', 'ebengue.valery@gmail.com', 'm', 1, '2021-09-13 14:14:03');

-- --------------------------------------------------------

--
-- Structure de la table `tab_produit`
--

DROP TABLE IF EXISTS `tab_produit`;
CREATE TABLE IF NOT EXISTS `tab_produit` (
  `id_produit` int(3) NOT NULL AUTO_INCREMENT,
  `id_salle` int(3) NOT NULL,
  `date_arrivee` datetime NOT NULL,
  `date_depart` datetime NOT NULL,
  `prix` int(3) NOT NULL,
  `etat` enum('libre','reservation') NOT NULL,
  PRIMARY KEY (`id_produit`),
  KEY `tab_produit_ibfk_1` (`id_salle`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `tab_salle`
--

DROP TABLE IF EXISTS `tab_salle`;
CREATE TABLE IF NOT EXISTS `tab_salle` (
  `id_salle` int(3) NOT NULL AUTO_INCREMENT,
  `titre` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `photo` varchar(200) NOT NULL,
  `pays` varchar(20) NOT NULL,
  `adresse` varchar(50) NOT NULL,
  `cp` int(5) NOT NULL,
  `capacite` int(3) NOT NULL,
  `categorie` enum('reunion','bureau','formation','') NOT NULL,
  PRIMARY KEY (`id_salle`,`titre`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `commande_ibfk_1` FOREIGN KEY (`id_membre`) REFERENCES `tab_membre` (`id_membre`),
  ADD CONSTRAINT `commande_ibfk_2` FOREIGN KEY (`id_produit`) REFERENCES `tab_produit` (`id_produit`);

--
-- Contraintes pour la table `tab_avis`
--
ALTER TABLE `tab_avis`
  ADD CONSTRAINT `tab_avis_ibfk_1` FOREIGN KEY (`id_membre`) REFERENCES `tab_membre` (`id_membre`),
  ADD CONSTRAINT `tab_avis_ibfk_2` FOREIGN KEY (`id_salle`) REFERENCES `tab_salle` (`id_salle`);

--
-- Contraintes pour la table `tab_produit`
--
ALTER TABLE `tab_produit`
  ADD CONSTRAINT `tab_produit_ibfk_1` FOREIGN KEY (`id_salle`) REFERENCES `tab_salle` (`id_salle`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
