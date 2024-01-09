-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : sam. 30 déc. 2023 à 19:40
-- Version du serveur : 10.4.24-MariaDB
-- Version de PHP : 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `gestionnaireemargement`
--

-- --------------------------------------------------------

--
-- Structure de la table `etudiant`
--

CREATE TABLE `etudiant` (
  `Nbre_etu` int(11) NOT NULL,
  `Numero_etu` int(11) NOT NULL,
  `Nom_etu` varchar(255) NOT NULL,
  `Prenom_etu` varchar(255) NOT NULL,
  `Signature_etu` varchar(255) DEFAULT NULL,
  `Heure_arrivee_etu` varchar(255) DEFAULT NULL,
  `Observation_etu` varchar(255) DEFAULT NULL,
  `Promo_etu` varchar(255) NOT NULL CHECK (`Promo_etu` in ('3A FISA','3A FISE','4A FISA','4A FISE','5A INSI','5A REVA'))
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `etudiant`
--

INSERT INTO `etudiant` (`Nbre_etu`, `Numero_etu`, `Nom_etu`, `Prenom_etu`, `Signature_etu`, `Heure_arrivee_etu`, `Observation_etu`, `Promo_etu`) VALUES
(17, 13000602, 'MONTINI', 'Valentin', NULL, NULL, NULL, '3A FISA'),
(7, 19008093, 'DAOUD', 'Bichoy', NULL, NULL, NULL, '3A FISA'),
(1, 20003098, 'ARNIAUD', 'Alexandre', NULL, NULL, NULL, '3A FISA'),
(3, 20008336, 'BOREL', 'Nathan', NULL, NULL, NULL, '3A FISA'),
(14, 20016987, 'GRANGE', 'Logann', NULL, NULL, NULL, '3A FISA'),
(12, 20028424, 'EL AITA', 'Meriem', NULL, NULL, NULL, '3A FISA'),
(10, 20029733, 'DIANI', 'Lina', NULL, NULL, NULL, '3A FISA'),
(2, 20029900, 'BABA', 'Salma', NULL, NULL, NULL, '3A FISA'),
(5, 21214780, 'BOUTELDJA', 'Wassim', NULL, NULL, NULL, '3A FISA'),
(18, 21217744, 'SCANDEL', 'Jean', NULL, NULL, NULL, '3A FISA'),
(13, 22025753, 'FURNON', 'Clément', NULL, NULL, NULL, '3A FISA'),
(8, 23025476, 'DE LILLO', 'Lorenzo', NULL, NULL, NULL, '3A FISA'),
(9, 23026139, 'DESAUBLIAUX', 'Arthur', NULL, NULL, NULL, '3A FISA'),
(4, 23026204, 'BOUCHTITA', 'Achraf', NULL, NULL, NULL, '3A FISA'),
(6, 23026217, 'CONTI', 'Jérémy', NULL, NULL, NULL, '3A FISA'),
(11, 23026222, 'ECHARDOUR', 'Pollyanna-Eva', NULL, NULL, NULL, '3A FISA'),
(15, 23028257, 'GUILLEM', 'Hugo', NULL, NULL, NULL, '3A FISA'),
(16, 23028288, 'LAARABI', 'Hanif', NULL, NULL, NULL, '3A FISA');

-- --------------------------------------------------------

--
-- Structure de la table `secretaire`
--

CREATE TABLE `secretaire` (
  `Id_secretaire` varchar(255) NOT NULL,
  `Nom_secretaire` varchar(255) NOT NULL,
  `Prenom_secretaire` varchar(255) NOT NULL,
  `Adresse_mail` varchar(255) NOT NULL,
  `Mot_de_passe_hache` varchar(32) NOT NULL,
  `Departement_secretaire` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `secretaire`
--

INSERT INTO `secretaire` (`Id_secretaire`, `Nom_secretaire`, `Prenom_secretaire`, `Adresse_mail`, `Mot_de_passe_hache`, `Departement_secretaire`) VALUES
('MR001INFO', 'RECORD', 'Marie pierre', 'marie-pierre.record@univ-amu.fr', '0d2d17e23d85906a93e5836260fa1ff1', 'INFO');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `etudiant`
--
ALTER TABLE `etudiant`
  ADD PRIMARY KEY (`Numero_etu`),
  ADD UNIQUE KEY `Nbre_etu` (`Nbre_etu`);

--
-- Index pour la table `secretaire`
--
ALTER TABLE `secretaire`
  ADD PRIMARY KEY (`Id_secretaire`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `etudiant`
--
ALTER TABLE `etudiant`
  MODIFY `Nbre_etu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
