-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  ven. 22 déc. 2017 à 22:06
-- Version du serveur :  5.7.19
-- Version de PHP :  5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `gestion_personnel`
--

-- --------------------------------------------------------

--
-- Structure de la table `avances`
--

DROP TABLE IF EXISTS `avances`;
CREATE TABLE IF NOT EXISTS `avances` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_PERSONNELS` int(11) DEFAULT NULL,
  `MONTANT` double DEFAULT NULL,
  `DATE_EMPREINTE` date DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `avances`
--

INSERT INTO `avances` (`ID`, `ID_PERSONNELS`, `MONTANT`, `DATE_EMPREINTE`) VALUES
(2, 1, 100, '2017-12-20'),
(3, 3, 300, '2017-12-20'),
(4, 1, 10, '2017-12-20'),
(5, 2, 200, '2017-12-20');

-- --------------------------------------------------------

--
-- Structure de la table `caution_definitive`
--

DROP TABLE IF EXISTS `caution_definitive`;
CREATE TABLE IF NOT EXISTS `caution_definitive` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `DATE_CAUTION_DEFINITIVE` date DEFAULT NULL,
  `N_CAUTION` int(11) DEFAULT NULL,
  `MONTANT` double DEFAULT NULL,
  `BANQUE` text,
  `ID_MARCHE` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `caution_definitive`
--

INSERT INTO `caution_definitive` (`ID`, `DATE_CAUTION_DEFINITIVE`, `N_CAUTION`, `MONTANT`, `BANQUE`, `ID_MARCHE`) VALUES
(1, '2017-12-01', 444, 40000, 'Populaire', 1),
(3, '2016-12-01', 4000, 4000, 'Populaire', 1);

-- --------------------------------------------------------

--
-- Structure de la table `caution_provisoire`
--

DROP TABLE IF EXISTS `caution_provisoire`;
CREATE TABLE IF NOT EXISTS `caution_provisoire` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `DATE_CAUTION_PROVISOIRE` date DEFAULT NULL,
  `N_CAUTION` int(11) DEFAULT NULL,
  `MONTANT` double DEFAULT NULL,
  `BANQUE` text,
  `ID_MARCHE` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `caution_provisoire`
--

INSERT INTO `caution_provisoire` (`ID`, `DATE_CAUTION_PROVISOIRE`, `N_CAUTION`, `MONTANT`, `BANQUE`, `ID_MARCHE`) VALUES
(1, '2017-12-31', 1444, 1000, 'Populaire', 1);

-- --------------------------------------------------------

--
-- Structure de la table `caution_retenue_garantie`
--

DROP TABLE IF EXISTS `caution_retenue_garantie`;
CREATE TABLE IF NOT EXISTS `caution_retenue_garantie` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `DATE_CAUTION_RETENUE_GARANTIE` date DEFAULT NULL,
  `N_CAUTION` int(11) DEFAULT NULL,
  `MONTANT` double DEFAULT NULL,
  `BANQUE` text,
  `ID_MARCHE` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `caution_retenue_garantie`
--

INSERT INTO `caution_retenue_garantie` (`ID`, `DATE_CAUTION_RETENUE_GARANTIE`, `N_CAUTION`, `MONTANT`, `BANQUE`, `ID_MARCHE`) VALUES
(1, '2016-12-31', 2000, 6666, 'BMCE', 1);

-- --------------------------------------------------------

--
-- Structure de la table `chantiers`
--

DROP TABLE IF EXISTS `chantiers`;
CREATE TABLE IF NOT EXISTS `chantiers` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_CHEF` int(11) DEFAULT NULL,
  `CODE` text,
  `ID_MARCHE` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `chantiers`
--

INSERT INTO `chantiers` (`ID`, `ID_CHEF`, `CODE`, `ID_MARCHE`) VALUES
(1, 1, 'Route Rass ain', 1),
(3, 1, 'route youssoufia', 1),
(5, 2, 'Route chamaaia', 1);

-- --------------------------------------------------------

--
-- Structure de la table `historique_salaire`
--

DROP TABLE IF EXISTS `historique_salaire`;
CREATE TABLE IF NOT EXISTS `historique_salaire` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_PERSONNEL` int(11) DEFAULT NULL,
  `NOUEAU_SALAIRE` double DEFAULT NULL,
  `DATE_CHANGEMENT` date DEFAULT NULL,
  KEY `AK_IDENTIFIANT_1` (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `historique_salaire`
--

INSERT INTO `historique_salaire` (`ID`, `ID_PERSONNEL`, `NOUEAU_SALAIRE`, `DATE_CHANGEMENT`) VALUES
(1, 1, 12000, '2017-12-15'),
(2, 1, 11000, '2017-12-15'),
(3, 2, 120, '2017-12-18'),
(4, 3, 100, '2017-12-18');

-- --------------------------------------------------------

--
-- Structure de la table `marches`
--

DROP TABLE IF EXISTS `marches`;
CREATE TABLE IF NOT EXISTS `marches` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CODE` text NOT NULL,
  `NUM_APPEL_OFFRE` int(11) DEFAULT NULL,
  `NUM_MARCHE` int(11) DEFAULT NULL,
  `OBJET` text,
  `MAITRE_OUVRAGE` text,
  `MONTANT_MARCHE` double DEFAULT NULL,
  `DELAI_MARCHE` text,
  `CAHIER_SPECIAL` text,
  `DATE_NOTIFICATION` date DEFAULT NULL,
  `DATE_ENREGISTREMENT` date DEFAULT NULL,
  `DATE_COMMENCEMENT` date DEFAULT NULL,
  `DATE_RECEPTION_PROVISOIRE` date DEFAULT NULL,
  `DATE_RECEPTION_DEFINITIVE` date DEFAULT NULL,
  `DIVERS` text,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `marches`
--

INSERT INTO `marches` (`ID`, `CODE`, `NUM_APPEL_OFFRE`, `NUM_MARCHE`, `OBJET`, `MAITRE_OUVRAGE`, `MONTANT_MARCHE`, `DELAI_MARCHE`, `CAHIER_SPECIAL`, `DATE_NOTIFICATION`, `DATE_ENREGISTREMENT`, `DATE_COMMENCEMENT`, `DATE_RECEPTION_PROVISOIRE`, `DATE_RECEPTION_DEFINITIVE`, `DIVERS`) VALUES
(1, 'C1', 111000, 2000, '200000', '20000', 222, '222000', '20000', '2017-10-01', '2017-12-01', '2016-12-02', '2016-12-02', '2017-12-02', '2222 divers');

-- --------------------------------------------------------

--
-- Structure de la table `ordre_arret`
--

DROP TABLE IF EXISTS `ordre_arret`;
CREATE TABLE IF NOT EXISTS `ordre_arret` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `DATE_ORDRE_ARRET` date DEFAULT NULL,
  `N_ORDRE_ARRET` int(11) DEFAULT NULL,
  `JUSTIFICAION` text,
  `ID_MARCHE` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `ordre_arret`
--

INSERT INTO `ordre_arret` (`ID`, `DATE_ORDRE_ARRET`, `N_ORDRE_ARRET`, `JUSTIFICAION`, `ID_MARCHE`) VALUES
(1, '2016-12-31', 20, 'pppp', 1);

-- --------------------------------------------------------

--
-- Structure de la table `ordre_reprise`
--

DROP TABLE IF EXISTS `ordre_reprise`;
CREATE TABLE IF NOT EXISTS `ordre_reprise` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `DATE_ORDRE_REPRISE` date DEFAULT NULL,
  `N_ORDRE_REPRISE` int(11) DEFAULT NULL,
  `JUSTIFICAION` text,
  `ID_MARCHE` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `ordre_reprise`
--

INSERT INTO `ordre_reprise` (`ID`, `DATE_ORDRE_REPRISE`, `N_ORDRE_REPRISE`, `JUSTIFICAION`, `ID_MARCHE`) VALUES
(1, '2017-11-30', 40, 'justification', 1);

-- --------------------------------------------------------

--
-- Structure de la table `paiements`
--

DROP TABLE IF EXISTS `paiements`;
CREATE TABLE IF NOT EXISTS `paiements` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_PERSONNELS` int(11) DEFAULT NULL,
  `DATE_PAIEMENT` date DEFAULT NULL,
  `SOMME_HEUR_N` int(11) NOT NULL,
  `SOMME_HEUR_S` int(11) NOT NULL,
  `DATE_POINTAGE_START` date NOT NULL,
  `DATE_POINTAGE_END` date NOT NULL,
  `MONTANT` double DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `paiements`
--

INSERT INTO `paiements` (`ID`, `ID_PERSONNELS`, `DATE_PAIEMENT`, `SOMME_HEUR_N`, `SOMME_HEUR_S`, `DATE_POINTAGE_START`, `DATE_POINTAGE_END`, `MONTANT`) VALUES
(2, 2, '2017-12-19', 9, 9, '2017-12-01', '2017-12-31', 160),
(3, 2, '2017-12-19', 9, 9, '2017-12-01', '2017-12-31', 160),
(4, 1, '2017-12-19', 22, 22, '2017-12-01', '2017-12-31', 11000),
(5, 1, '2017-12-19', 22, 22, '2017-12-01', '2017-12-31', 11000),
(6, 2, '2017-12-19', 9, 9, '2017-12-08', '2017-12-31', 160),
(7, 2, '2017-12-19', 9, 9, '2017-12-08', '2017-12-31', 160);

-- --------------------------------------------------------

--
-- Structure de la table `personnels`
--

DROP TABLE IF EXISTS `personnels`;
CREATE TABLE IF NOT EXISTS `personnels` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NOM` longtext NOT NULL,
  `PRENOM` longtext NOT NULL,
  `CIN` char(20) DEFAULT NULL,
  `TELEPHONE` longtext,
  `ADRESSE` text,
  `CNSS` longtext,
  `RIB` longtext,
  `DATE_EMBAUCHE` date DEFAULT NULL,
  `TYPE` longtext NOT NULL,
  `SALAIRE_MENSUELLE` double DEFAULT NULL,
  `TARIF_JOURNALIERS` double DEFAULT NULL,
  `CODE` longtext,
  `ID_POSTES` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `personnels`
--

INSERT INTO `personnels` (`ID`, `NOM`, `PRENOM`, `CIN`, `TELEPHONE`, `ADRESSE`, `CNSS`, `RIB`, `DATE_EMBAUCHE`, `TYPE`, `SALAIRE_MENSUELLE`, `TARIF_JOURNALIERS`, `CODE`, `ID_POSTES`, `status`) VALUES
(1, 'SALOUMI', 'ACHRAF', 'HA132587', '0524645128', 'MARRAKECH', '14785239', '222124578963258', '1985-08-07', 'Salarie', 11000, 0, 'TECH', 1, 1),
(2, 'bouchbaat', 'noura', 'ee260968', '05522114466', 'SAFI', '88774455', '2222222222222222', '1985-08-07', 'Ouvrier', 0, 120, 'TECH', 1, 1),
(3, 'ttt', 'tt', 't', 't', 't', 't', 'tt', '2015-11-30', 'Ouvrier', 0, 100, 'YY', 2, 1);

-- --------------------------------------------------------

--
-- Structure de la table `pointages`
--

DROP TABLE IF EXISTS `pointages`;
CREATE TABLE IF NOT EXISTS `pointages` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_PERSONNELS` int(11) NOT NULL,
  `DATE_POINTAGE` date NOT NULL,
  `HEUR_N` int(11) DEFAULT NULL,
  `HEUR_S` int(11) DEFAULT NULL,
  `ID_CHANTIER` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `pointages`
--

INSERT INTO `pointages` (`ID`, `ID_PERSONNELS`, `DATE_POINTAGE`, `HEUR_N`, `HEUR_S`, `ID_CHANTIER`) VALUES
(2, 1, '2017-12-17', 22, 55, 1),
(3, 2, '2017-12-12', 9, 3, 1),
(4, 3, '2016-11-03', 11, 22, 1);

-- --------------------------------------------------------

--
-- Structure de la table `postes`
--

DROP TABLE IF EXISTS `postes`;
CREATE TABLE IF NOT EXISTS `postes` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `POSTE` longtext,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `postes`
--

INSERT INTO `postes` (`ID`, `POSTE`) VALUES
(1, 'Chef Chantier'),
(2, 'p2'),
(3, 'p3'),
(4, 'p4'),
(6, 'dd');

-- --------------------------------------------------------

--
-- Structure de la table `remarque_personnels`
--

DROP TABLE IF EXISTS `remarque_personnels`;
CREATE TABLE IF NOT EXISTS `remarque_personnels` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_PERSONNELS` int(11) NOT NULL,
  `REMARQUE` text NOT NULL,
  `DATE_REMARQUE` date NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `remarque_personnels`
--

INSERT INTO `remarque_personnels` (`ID`, `ID_PERSONNELS`, `REMARQUE`, `DATE_REMARQUE`) VALUES
(1, 1, 'test', '2017-12-19'),
(2, 1, 'test4', '2017-12-19');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `LOGIN` text,
  `PASSWORD` text,
  `NOM` text,
  `PRENOM` text,
  `EMAIL` text,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`ID`, `LOGIN`, `PASSWORD`, `NOM`, `PRENOM`, `EMAIL`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'achraf', 'saloumi', 'a.mareshal@gmail.com');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
