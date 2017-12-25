-- Generation time: Sat, 23 Dec 2017 11:31:44 +0000
-- Host: localhost
-- DB name: gestion_personnel
/*!40030 SET NAMES UTF8 */;
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

DROP TABLE IF EXISTS `avances`;
CREATE TABLE `avances` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_PERSONNELS` int(11) DEFAULT NULL,
  `MONTANT` double DEFAULT NULL,
  `DATE_EMPREINTE` date DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

INSERT INTO `avances` VALUES ('2','1','100','2017-12-20'),
('3','3','300','2017-12-20'),
('4','1','10','2017-12-20'),
('5','2','200','2017-12-20'); 


DROP TABLE IF EXISTS `caution_definitive`;
CREATE TABLE `caution_definitive` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `DATE_CAUTION_DEFINITIVE` date DEFAULT NULL,
  `N_CAUTION` int(11) DEFAULT NULL,
  `MONTANT` double DEFAULT NULL,
  `BANQUE` text,
  `ID_MARCHE` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

INSERT INTO `caution_definitive` VALUES ('1','2017-12-01','444','40000','Populaire','1'),
('3','2016-12-01','4000','4000','Populaire','1'),
('4','2017-12-31','5','5','','1'),
('5','2017-12-31','4000','777777','','1'); 


DROP TABLE IF EXISTS `caution_provisoire`;
CREATE TABLE `caution_provisoire` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `DATE_CAUTION_PROVISOIRE` date DEFAULT NULL,
  `N_CAUTION` int(11) DEFAULT NULL,
  `MONTANT` double DEFAULT NULL,
  `BANQUE` text,
  `ID_MARCHE` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

INSERT INTO `caution_provisoire` VALUES ('1','2017-12-31','1444','1000','Populaire','1'); 


DROP TABLE IF EXISTS `caution_retenue_garantie`;
CREATE TABLE `caution_retenue_garantie` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `DATE_CAUTION_RETENUE_GARANTIE` date DEFAULT NULL,
  `N_CAUTION` int(11) DEFAULT NULL,
  `MONTANT` double DEFAULT NULL,
  `BANQUE` text,
  `ID_MARCHE` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

INSERT INTO `caution_retenue_garantie` VALUES ('1','2016-12-31','2000','6666','BMCE','1'); 


DROP TABLE IF EXISTS `chantiers`;
CREATE TABLE `chantiers` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_CHEF` int(11) DEFAULT NULL,
  `CODE` text,
  `ID_MARCHE` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

INSERT INTO `chantiers` VALUES ('1','1','Route Rass ain','1'),
('3','1','route youssoufia','1'),
('5','2','Route chamaaia','1'); 


DROP TABLE IF EXISTS `historique_salaire`;
CREATE TABLE `historique_salaire` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_PERSONNEL` int(11) DEFAULT NULL,
  `NOUEAU_SALAIRE` double DEFAULT NULL,
  `DATE_CHANGEMENT` date DEFAULT NULL,
  KEY `AK_IDENTIFIANT_1` (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

INSERT INTO `historique_salaire` VALUES ('1','1','12000','2017-12-15'),
('2','1','11000','2017-12-15'),
('3','2','120','2017-12-18'),
('4','3','100','2017-12-18'); 


DROP TABLE IF EXISTS `marches`;
CREATE TABLE `marches` (
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

INSERT INTO `marches` VALUES ('1','C1','111000','2000','200000','20000','222','222000','20000','2017-10-01','2017-12-01','2016-12-02','2016-12-02','2017-12-02','2222 divers'); 


DROP TABLE IF EXISTS `ordre_arret`;
CREATE TABLE `ordre_arret` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `DATE_ORDRE_ARRET` date DEFAULT NULL,
  `N_ORDRE_ARRET` int(11) DEFAULT NULL,
  `JUSTIFICAION` text,
  `ID_MARCHE` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

INSERT INTO `ordre_arret` VALUES ('1','2016-12-31','20','pppp','1'); 


DROP TABLE IF EXISTS `ordre_reprise`;
CREATE TABLE `ordre_reprise` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `DATE_ORDRE_REPRISE` date DEFAULT NULL,
  `N_ORDRE_REPRISE` int(11) DEFAULT NULL,
  `JUSTIFICAION` text,
  `ID_MARCHE` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

INSERT INTO `ordre_reprise` VALUES ('1','2017-11-30','40','justification','1'); 


DROP TABLE IF EXISTS `paiements`;
CREATE TABLE `paiements` (
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

INSERT INTO `paiements` VALUES ('2','2','2017-12-19','9','9','2017-12-01','2017-12-31','160'),
('3','2','2017-12-19','9','9','2017-12-01','2017-12-31','160'),
('4','1','2017-12-19','22','22','2017-12-01','2017-12-31','11000'),
('5','1','2017-12-19','22','22','2017-12-01','2017-12-31','11000'),
('6','2','2017-12-19','9','9','2017-12-08','2017-12-31','160'),
('7','2','2017-12-19','9','9','2017-12-08','2017-12-31','160'); 


DROP TABLE IF EXISTS `personnels`;
CREATE TABLE `personnels` (
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

INSERT INTO `personnels` VALUES ('1','SALOUMI','ACHRAF','HA132587','0524645128','MARRAKECH','14785239','222124578963258','1985-08-07','Salarie','11000','0','TECH','1','1'),
('2','bouchbaat','noura','ee260968','05522114466','SAFI','88774455','2222222222222222','1985-08-07','Ouvrier','0','120','TECH','1','1'),
('3','ttt','tt','t','t','t','t','tt','2015-11-30','Ouvrier','0','100','YY','2','1'); 


DROP TABLE IF EXISTS `pointages`;
CREATE TABLE `pointages` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_PERSONNELS` int(11) NOT NULL,
  `DATE_POINTAGE` date NOT NULL,
  `HEUR_N` int(11) DEFAULT NULL,
  `HEUR_S` int(11) DEFAULT NULL,
  `ID_CHANTIER` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

INSERT INTO `pointages` VALUES ('2','1','2017-12-17','22','55','1'),
('3','2','2017-12-12','9','3','1'),
('4','3','2016-11-03','11','22','1'); 


DROP TABLE IF EXISTS `postes`;
CREATE TABLE `postes` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `POSTE` longtext,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

INSERT INTO `postes` VALUES ('1','Chef Chantier'),
('2','p2'),
('3','p3'),
('4','p4'),
('6','dd'); 


DROP TABLE IF EXISTS `remarque_personnels`;
CREATE TABLE `remarque_personnels` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_PERSONNELS` int(11) NOT NULL,
  `REMARQUE` text NOT NULL,
  `DATE_REMARQUE` date NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

INSERT INTO `remarque_personnels` VALUES ('1','1','test','2017-12-19'),
('2','1','test4','2017-12-19'); 


DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `LOGIN` text,
  `PASSWORD` text,
  `NOM` text,
  `PRENOM` text,
  `EMAIL` text,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

INSERT INTO `users` VALUES ('1','admin','21232f297a57a5a743894a0e4a801fc3','achraf','saloumi','a.mareshal@gmail.com'); 




/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

