-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 15 jan. 2024 à 12:12
-- Version du serveur :  10.4.16-MariaDB
-- Version de PHP : 7.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `cartemetier`
--

DELIMITER $$
--
-- Procédures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `CalculateTotalAmounts` ()  BEGIN
    DECLARE total_micro_mareyage DECIMAL(10, 2) DEFAULT 0;
    DECLARE total_1st_category DECIMAL(10, 2) DEFAULT 0;
    DECLARE total_2nd_category DECIMAL(10, 2) DEFAULT 0;
    
    SELECT SUM(
        CASE
            WHEN c.LibelleCategorie = 'Micro mareyage' THEN c.MontantCategorie
            ELSE 0
        END
    ) INTO total_micro_mareyage
    FROM cartemareyeur c;
    
    SELECT SUM(
        CASE
            WHEN c.LibelleCategorie = '1ère Catégorie' THEN c.MontantCategorie
            ELSE 0
        END
    ) INTO total_1st_category
    FROM cartemareyeur c;
    
    SELECT SUM(
        CASE
            WHEN c.LibelleCategorie = '2ème Catégorie' THEN c.MontantCategorie
            ELSE 0
        END
    ) INTO total_2nd_category
    FROM cartemareyeur c;
    
    SELECT 
        total_micro_mareyage AS MicroMareyageTotal,
        total_1st_category AS FirstCategoryTotal,
        total_2nd_category AS SecondCategoryTotal;
END$$

--
-- Fonctions
--
CREATE DEFINER=`root`@`localhost` FUNCTION `increment_alphanumeric` (`prefix` VARCHAR(10)) RETURNS VARCHAR(20) CHARSET utf8mb4 BEGIN
    DECLARE new_id INT;
    UPDATE NumeroCarte SET id = LAST_INSERT_ID(id + 1);
    SELECT LAST_INSERT_ID() INTO new_id;
    RETURN CONCAT(prefix, LPAD(new_id, 6, '0'));
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `autorisations`
--

CREATE TABLE `autorisations` (
  `idAutorisations` int(11) NOT NULL,
  `idRoles` int(11) DEFAULT NULL,
  `NomPage` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `cartemareyeur`
--

CREATE TABLE `cartemareyeur` (
  `idCarteMareyeur` int(11) NOT NULL,
  `NumeroCarte` varchar(30) NOT NULL,
  `idEntreprise` int(11) DEFAULT NULL,
  `LibelleCategorie` varchar(85) NOT NULL,
  `MontantCategorie` int(11) NOT NULL,
  `NumQuitance` varchar(20) NOT NULL,
  `DateQuitance` date NOT NULL,
  `DateDebutValid` date NOT NULL,
  `DateFinValid` date NOT NULL,
  `Siege` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `cartemareyeur`
--

INSERT INTO `cartemareyeur` (`idCarteMareyeur`, `NumeroCarte`, `idEntreprise`, `LibelleCategorie`, `MontantCategorie`, `NumQuitance`, `DateQuitance`, `DateDebutValid`, `DateFinValid`, `Siege`) VALUES
(3, 'DPM000003', 3, '1ère Catégorie', 20000, '002025045', '2023-01-17', '2023-01-17', '2023-12-31', 'Patte d\'oie'),
(4, 'DPM000004', 4, '1ère Catégorie', 20000, '6666HJK0n105', '2023-01-04', '2023-02-04', '2023-12-31', 'Médina'),
(5, 'DPM000005', 5, 'Micro mareyage', 10000, '00H785045', '2023-05-12', '2023-05-13', '2023-12-31', 'Gossas'),
(6, 'DPM000006', 6, 'Micro mareyage', 10000, 'KJD00147100', '2023-01-15', '2023-01-15', '2023-12-31', 'Joal'),
(7, 'DPM000007', 7, '1ère Catégorie', 20000, '01450QH1100', '2023-01-20', '2023-01-21', '2023-12-31', 'Fatick'),
(8, 'DPM000008', 8, '2ème Catégorie', 30000, 'KJD045DS00', '2023-02-15', '2023-02-15', '2023-12-31', 'Mariste'),
(9, 'DPM000009', 9, 'Micro mareyage', 10000, '00D124XDFG00', '2023-06-05', '2023-06-10', '2023-12-31', 'Grand Yoff'),
(10, 'DPM000010', 10, '2ème Catégorie', 30000, '1QS00147100', '2023-05-16', '2023-05-16', '2023-12-31', 'Gamboul'),
(11, 'DPM000011', 11, 'Micro mareyage', 10000, '55550147100', '2023-01-15', '2023-01-15', '2023-12-31', 'Saint louis'),
(12, 'DPM000012', 12, 'Micro mareyage', 10000, 'KJD10047100', '2023-02-05', '2023-02-05', '2023-12-31', 'Colobane'),
(13, 'DPM000013', 14, 'Micro mareyage', 10000, 'KJ44147100', '2023-04-15', '2023-04-15', '2023-12-31', 'Colobane'),
(14, 'DPM000014', 16, 'Micro mareyage', 10000, 'K001SQH1100', '2023-04-15', '2023-04-16', '2023-12-31', 'Gueule tapée'),
(15, 'DPM000019', 17, '1ère Catégorie', 20000, '54D47E4A4Q', '2023-08-15', '2023-01-15', '2023-12-31', 'Colobane'),
(16, 'DPM000016', 18, '2ème Catégorie', 30000, 'QD87D54Q', '2023-08-15', '2023-08-15', '2023-12-31', 'Médina'),
(17, 'DPM000017', 19, 'Micro mareyage', 10000, '001N4S45', '2023-02-01', '2023-02-05', '2023-12-31', 'Dakar'),
(18, 'DPM000018', 21, '1ère Catégorie', 20000, '021450045', '2023-02-16', '2023-02-16', '2023-12-31', 'Gossas'),
(23, 'DPM000020', 31, '1ère Catégorie', 20000, 'KJDSSS4450', '2023-02-11', '2023-02-11', '2023-12-31', 'Patte d\'oie'),
(24, 'DPM000021', 32, '1ère Catégorie', 20000, 'KJDPPH1100', '2023-01-23', '2023-01-23', '2023-12-31', 'Joal Fadioute'),
(26, 'DPM000022', 34, '2ème Catégorie', 30000, 'PPPSQH1100', '2023-02-12', '2023-02-12', '2023-12-31', 'Colobane'),
(29, 'DPM000023', 3, '1ère Catégorie', 20000, '777JK0n105', '2023-01-18', '2023-01-18', '2023-12-31', 'Colobane'),
(30, 'DPM000024', 7, '2ème Catégorie', 30000, 'FF200045', '2023-01-11', '2023-01-11', '2023-12-31', 'Mariste'),
(31, 'DPM000025', 34, '2ème Catégorie', 30000, 'MM485PP', '2023-01-06', '2023-01-06', '2023-12-31', 'Ziguinchor'),
(32, 'DPM000026', 8, 'Micro mareyage', 10000, 'HJK0045', '2023-01-15', '2023-01-15', '2023-12-31', 'Grand Yoff'),
(33, 'DPM000027', 12, '2ème Catégorie', 30000, 'PP158045', '2023-01-09', '2023-01-09', '2023-12-31', 'Grand Yoff'),
(34, 'DPM000028', 4, '1ère Catégorie', 20000, '58283282', '2023-01-13', '2023-01-13', '2023-12-31', 'Grand Yoff'),
(35, 'DPM000029', 35, 'Micro mareyeur', 10000, '', '2023-01-23', '2023-01-23', '2023-12-31', 'Mariste'),
(36, 'DPM000030', 36, 'Micro mareyeur', 10000, '787254S45', '2023-08-31', '2023-09-08', '2023-12-31', 'Thiaroye'),
(37, 'DPM000031', 3, '2ème Catégorie', 30000, '007870045', '2023-09-14', '2023-09-14', '2023-12-31', 'Thiaroye');

-- --------------------------------------------------------

--
-- Structure de la table `delivrance`
--

CREATE TABLE `delivrance` (
  `idDelivrance` int(11) NOT NULL,
  `DateDelivrance` date NOT NULL,
  `TypeDelivrance` enum('Original','Duplicata') NOT NULL,
  `idCarteMareyeur` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `departement`
--

CREATE TABLE `departement` (
  `idDepartement` int(11) NOT NULL,
  `LibelleDepartement` varchar(105) NOT NULL,
  `idRegion` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `departement`
--

INSERT INTO `departement` (`idDepartement`, `LibelleDepartement`, `idRegion`) VALUES
(1, 'Dakar', 1),
(2, 'Guédiawaye', 1),
(3, 'Pikine', 1),
(4, 'Rufisque', 1),
(5, 'Bambey', 2),
(6, 'Diourbel', 2),
(7, 'Mbacké', 2),
(8, 'Fatick', 3),
(9, 'Foundiougne', 3),
(10, 'Gossas', 3),
(11, 'Kaolack', 4),
(12, 'Guinguinéo', 4),
(13, 'Nioro du Rip', 4),
(14, 'Kolda', 5),
(15, 'Vélingara', 5),
(16, 'Médina Yoro Foulah', 5),
(17, 'Kébémer', 6),
(18, 'Linguère', 6),
(19, 'Louga', 6),
(20, 'Kanel', 7),
(21, 'Matam', 7),
(22, 'Ranérou', 7),
(23, 'Dagana', 8),
(24, 'Podor', 8),
(25, 'Saint-Louis', 8),
(26, 'Bakel', 9),
(27, 'Koumpentoum', 9),
(28, 'Tambacounda', 9),
(29, 'Goudiry', 9),
(30, 'M\'bour', 10),
(31, 'Thiès', 10),
(32, 'Tivaouane', 10),
(33, 'Bignona', 11),
(34, 'Oussouye', 11),
(35, 'Ziguinchor', 11),
(36, 'Birkilane', 12),
(37, 'Kaffrine', 12),
(38, 'Malem-Hodar', 12),
(39, 'Kounghel', 12),
(40, 'Kédougou', 13),
(41, 'Salemata', 13),
(42, 'Saraya', 13),
(43, 'Bounkiling', 14),
(44, 'Goudomp', 14),
(45, 'Sédhiou', 14);

-- --------------------------------------------------------

--
-- Structure de la table `entreprise`
--

CREATE TABLE `entreprise` (
  `idEntreprise` int(11) NOT NULL,
  `Denomination` varchar(105) NOT NULL,
  `AdresseEntreprise` varchar(105) NOT NULL,
  `RegionEntreprise` varchar(105) NOT NULL,
  `DepartementEntreprise` varchar(105) NOT NULL,
  `Activite` varchar(105) NOT NULL,
  `PhoneEntreprise` varchar(45) NOT NULL,
  `NumRegistreCom` varchar(45) NOT NULL COMMENT 'Numero Registre de Commerce',
  `idPersonne` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `entreprise`
--

INSERT INTO `entreprise` (`idEntreprise`, `Denomination`, `AdresseEntreprise`, `RegionEntreprise`, `DepartementEntreprise`, `Activite`, `PhoneEntreprise`, `NumRegistreCom`, `idPersonne`) VALUES
(3, 'Acoma', 'Patte d\'oie', 'Dakar', 'Dakar', 'informatique', '338645587', '45001S55SZ', 176),
(4, 'QUARIER BLEU', 'Dalifor', 'Dakar', 'Pikine', 'Commerce', '338979879', '54454sdfsd', 177),
(5, 'Kahore SA', 'Gossas', 'Fatick', 'Gossas', 'Commerrce', '3399874521', '45ZE0DD45', 178),
(6, 'Sauri SA', 'Joal', 'Fatick', 'Mbour', 'Commerce', '3398745214', 'DF0210SDE0', 179),
(7, 'Quartier Vert SA', 'Sangalkam', 'Fatick', 'Niakhar', 'Commerce', '338754216', '450S001AZ', 180),
(8, 'SOUKO SA', 'Mariste', 'Dakar', 'Dakar', 'Commerce', '338754215', 'S0254001AZ', 181),
(9, 'Youpi SA', 'Hann', 'Fatick', 'Niakhar', 'Enseignement', '338745214', 'SQDF545000DS', 182),
(10, 'Maray SUARL', 'Kaolack', 'Kaolack', 'Gandiaye', 'Mareyage', '339875421', 'MMDF00DFE14R5', 183),
(11, 'Culti SA', 'Louga', 'Louga', 'Louga', 'Culture', '3398745987', '45044SDFS', 184),
(12, 'Info Vert SA', 'Parcelles assainies U15', 'Fatick', 'Louga', 'Informatique', '339874521', '45DFHGF12', 185),
(14, 'SECK & FRERES', 'Colobane', 'Dakar', 'Louga', 'Commerce', '338965247', 'SDF100101SDQF', 187),
(16, 'DIARRA & FRERES', 'Gueule tapée', 'Dakar', 'Dakar', 'Commerce', '338745214', 'DF01FSFD01', 189),
(17, 'SECK & FRERES', 'Colobane', 'Dakar', 'Dakar', 'Commerce', '339874521', '1254F00DFE14R5', 190),
(18, 'Sarcozi SA', 'Médina', 'Dakar', 'Dakar', 'Commerce', '338745874', 'QSD000Q1SD4Q', 191),
(19, 'Acoma', 'Patte d\'oie', 'Kaolack', 'Kaolack', 'informatique', '336987450', '4590055S', 192),
(21, 'Quarto Suarl', 'Gossas', 'Diourbel', 'Bambey', 'Mareyage', '3398653521', '4587444SDD45', 194),
(31, 'DIOP & FRERES', 'Patte d\'oie hlm', 'Matam', 'Matam', 'Commerce', '789789789', 'S00465464', 204),
(32, 'Maray SUARL', 'Patte d\'oie hlm', 'Tambacounda', 'Koumpentoum', 'Commerce', '78874588', '4508501AZ', 205),
(34, 'ThIAM & FRERES', 'Médina', 'Fatick', 'Foundiougne', 'Commerce', '779874521', '4774400101SDQF', 207),
(35, '', 'Patte d\'oie', 'Diourbel', 'Diourbel', 'Mareyage', '3398700521', '454MMS55SZ', 208),
(36, 'Acoma', 'Mariste 3', 'Fatick', 'Foundiougne', 'Mareyage', '3399874521', '45ZE44Sh55SZ', 209);

-- --------------------------------------------------------

--
-- Structure de la table `personne`
--

CREATE TABLE `personne` (
  `idPersonne` int(11) NOT NULL,
  `NomPersonne` varchar(85) NOT NULL,
  `PrenomPersonne` varchar(85) NOT NULL,
  `DateNaissance` date NOT NULL,
  `LieuNaissance` varchar(85) NOT NULL,
  `CNI` varchar(20) NOT NULL,
  `DateDelivranceCNI` date NOT NULL,
  `Genre` enum('Homme','Femme') NOT NULL,
  `PhonePersonne` varchar(20) NOT NULL,
  `AdressePersonne` varchar(85) NOT NULL,
  `Profession` varchar(85) NOT NULL,
  `Photo` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `personne`
--

INSERT INTO `personne` (`idPersonne`, `NomPersonne`, `PrenomPersonne`, `DateNaissance`, `LieuNaissance`, `CNI`, `DateDelivranceCNI`, `Genre`, `PhonePersonne`, `AdressePersonne`, `Profession`, `Photo`) VALUES
(176, 'DIOP', 'Moustapha', '1987-12-26', 'Kaolack', '1452836545', '2018-05-20', 'Homme', '778548547', 'HLM Patte d\'oie villa 215', 'Informaticien', 0x50686f746f2d434e492d6d6f757374617068615f322e6a7067),
(177, 'Diallo', 'Mariama', '1995-06-04', 'Kaffrine', '2589654123', '2018-08-25', 'Femme', '774122114', 'Guédiawaye', 'Enseignante', 0x50484f544f2d434e492e6a7067),
(178, 'Fassa', 'Ibrahima', '1965-06-15', 'Gossas', '1478965236', '2019-08-25', 'Homme', '787865410', 'Touba Darou salam', 'Cultivateur', 0x50484f544f2d434e492d4d6f757374617068612d6a756e696f722e6a7067),
(179, 'Badji', 'Lamine', '2023-06-04', 'Kabrousse', '1587452145', '2019-09-15', 'Homme', '789652301', 'Joal', 'Cultivateur', 0x50484f544f2d434e492d322e6a7067),
(180, 'Diaw', 'Karim', '1978-08-25', 'Sangalkam', '1478523698', '2018-02-15', 'Homme', '778541263', 'Sangalkam', 'Professeur', ''),
(181, 'Sarr', 'Soukayna', '1990-05-12', 'Dakar', '24587452114', '2016-02-21', 'Femme', '778965412', 'Mariste', 'Commerçante', 0x50686f746f2d636e692d4d617279616d2e6a7067),
(182, 'Diouf', 'Fatou', '1988-08-15', 'Hann', '2654785221', '2018-05-12', 'Femme', '778521452', 'Hann', 'Enseignante', 0x50686f746f2d434e492d466174696d612e6a7067),
(183, 'Diop', 'Mame Diarra', '1985-05-12', 'Kaolack', '25896321452', '2017-05-12', 'Femme', '778963214', 'Kaolack', 'Mareyage', 0x4169737361746f752d64696f702e6a7067),
(184, 'Sambou', 'Alioune', '1970-03-12', 'Saint louis', '15987456321', '2023-02-12', 'Homme', '778965489', 'Louga', 'Cultivateur', 0x50686f746f2d434e492d6d6f757374617068612e6a7067),
(185, 'Faye', 'Penda', '1988-08-04', 'Colobane', '258745874569', '2018-05-12', 'Femme', '778965488', 'Colobane', 'Informaticien', 0x50686f746f2d636e692d4d617279616d2e6a7067),
(187, 'Seck', 'Gorgui', '1989-07-14', 'Médina', '1587458741', '2018-08-25', 'Homme', '7789654789', 'Médina', 'Commerçant', 0x4164616d612e6a7067),
(189, 'Diarra', 'Samba', '1972-08-15', 'Dakar', '1452587456', '2018-05-12', 'Homme', '778965412', 'Gueule tapée', 'Homme d\'affaire', 0x50686f746f2d434e492d6d6f757374617068612e6a7067),
(190, 'Faye', 'Penda', '1985-02-15', 'Joal Fadioute', '25887458741', '2015-12-30', 'Femme', '774587521', 'Colobane', 'Enseignante', 0x50686f746f2d636e692d4d617279616d2e6a7067),
(191, 'Ndiaye', 'Souleymane', '1969-08-15', 'Médina', '15874587458', '2016-08-25', 'Homme', '7785412541', 'Médina', 'Agent administratif', 0x50686f746f2d434e492d6d6f757374617068612e6a7067),
(192, 'DIOP', 'Moustapha', '1987-12-25', 'Kaolack', '14895632541', '2015-08-25', 'Homme', '774588965', 'HLM Patte d\'oie villa 215', 'Informaticien', 0x70686f746f2d636e692d6d6f7573746166612e6a7067),
(194, 'Samb', 'Fatima', '1990-08-26', 'Mbour', '2258744698547', '2022-08-13', 'Femme', '7775154241', 'HLM Patte d\'oie', 'Mareyeur', 0x4169737361746f752d64696f702e6a7067),
(204, 'Diop', 'Oumou Kalsoum', '1960-08-11', 'Dakar', '24580052114', '2019-08-16', 'Femme', '789789789', 'Patte d\'oie hlm', 'Commerçante', 0x50686f746f2d636e692d4d617279616d2e6a7067),
(205, 'Thiam', 'Kalsoum', '1965-08-11', 'Médina', '2414785255', '2023-01-16', 'Femme', '78458720', 'Patte d\'oie hlm', 'Commerçante', 0x50686f746f2d434e492d466174696d612e6a7067),
(207, 'Thiam', 'Oumou Kalsoum', '1985-08-18', 'Médina', '22548452145', '2018-08-18', 'Femme', '774789789', 'Patte d\'oie', 'Mareyeuse', 0x50686f746f2d636e692d4d617279616d2e6a7067),
(208, 'Dieng', 'Mbaye', '1988-09-14', 'Kaolack', '19548975145252', '2020-09-08', 'Homme', '7725986465', 'Mariste', 'Informaticien', 0x50484f544f2d434e492e6a7067),
(209, 'DIOP', 'Moustapha', '2023-09-13', 'Kaolack', '2587469854754657', '2023-09-15', 'Homme', '7745896541', 'HLM Patte d\'oie villa 215', 'Enseignante', 0x416e6369656e6e652d50484f544f2d434e492e6a7067);

-- --------------------------------------------------------

--
-- Structure de la table `region`
--

CREATE TABLE `region` (
  `idRegion` int(11) NOT NULL,
  `LibelleRegion` varchar(85) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `region`
--

INSERT INTO `region` (`idRegion`, `LibelleRegion`) VALUES
(1, 'Dakar'),
(2, 'Diourbel'),
(3, 'Fatick'),
(4, 'Kaolack'),
(5, 'Kolda'),
(6, 'Louga'),
(7, 'Matam'),
(8, 'St Louis'),
(9, 'Tambacounda'),
(10, 'Thiès'),
(11, 'Ziguinchor'),
(12, 'Kaffrine'),
(13, 'Kédougou'),
(14, 'Sédhiou');

-- --------------------------------------------------------

--
-- Structure de la table `renouvellement`
--

CREATE TABLE `renouvellement` (
  `idRenouvellement` int(11) NOT NULL,
  `idCarteMareyeur` int(11) NOT NULL,
  `MontantRenouv` int(11) NOT NULL,
  `NumQuitance` varchar(20) NOT NULL,
  `DateQuitance` date NOT NULL,
  `DateDebutRenouv` date NOT NULL,
  `DateFinRenouv` date NOT NULL,
  `RenewalYear` year(4) GENERATED ALWAYS AS (year(`DateDebutRenouv`)) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `renouvellement`
--

INSERT INTO `renouvellement` (`idRenouvellement`, `idCarteMareyeur`, `MontantRenouv`, `NumQuitance`, `DateQuitance`, `DateDebutRenouv`, `DateFinRenouv`) VALUES
(6, 4, 0, '6666HJK0n105', '2025-02-04', '2025-02-04', '2025-12-31'),
(10, 5, 0, '7890045', '2024-03-14', '2024-03-14', '2024-12-31'),
(13, 14, 0, 'DSF475SD', '2024-01-04', '2024-02-04', '2024-12-31'),
(14, 10, 0, 'g485kh545', '2024-08-10', '2024-08-10', '2024-12-31'),
(15, 17, 10000, '44440147100', '2024-02-03', '2024-02-03', '2024-12-31'),
(17, 9, 0, 'KJD00100', '2024-01-11', '2024-01-11', '2024-12-31'),
(18, 13, 0, 'KJDSQH55555', '2024-02-03', '2024-02-03', '2024-12-31'),
(19, 16, 0, '455470n105', '2024-02-16', '2024-02-16', '2024-12-31'),
(21, 6, 0, '444HJSSSS', '2025-02-02', '2025-02-02', '2023-12-31'),
(23, 11, 0, '999SQ4450', '2025-02-10', '2025-02-10', '2023-12-31'),
(24, 8, 0, '252525200DFG4', '2025-02-04', '2025-02-04', '2023-12-31'),
(25, 23, 0, '3030300DFG4', '2024-02-10', '2024-02-10', '2023-12-31'),
(26, 16, 0, '858585SQ4450', '2025-02-12', '2025-02-12', '2023-12-31'),
(28, 13, 10000, '121212HJK0n105', '2025-01-09', '2025-01-09', '2023-12-31'),
(29, 23, 20000, '9890254S45', '2023-09-22', '2023-09-21', '2023-12-31');

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

CREATE TABLE `roles` (
  `idRoles` int(11) NOT NULL,
  `NomRoles` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `idUtilisateur` int(11) NOT NULL,
  `NomUtilisateur` varchar(255) NOT NULL,
  `PrenomUtilisateur` varchar(255) NOT NULL,
  `AdresseUtilisateur` varchar(255) NOT NULL,
  `FonctionUtilisateur` varchar(255) NOT NULL,
  `EmailUtilisateur` varchar(255) NOT NULL,
  `LoginUtilisateur` varchar(255) NOT NULL,
  `MDPUtilisateur` varchar(255) NOT NULL,
  `PhotoUtilisateur` blob NOT NULL,
  `encrypted_password` varchar(255) DEFAULT NULL,
  `reset_password_tok` varchar(255) DEFAULT NULL,
  `reset_password_sent_at` datetime DEFAULT NULL,
  `remember_created_at` datetime DEFAULT NULL,
  `sign_in_count` int(11) DEFAULT NULL,
  `current_sign_in_at` datetime DEFAULT NULL,
  `last_sign_in_at` datetime DEFAULT NULL,
  `current_sign_in_ip` varchar(255) DEFAULT NULL,
  `last_sign_in_ip` varchar(255) DEFAULT NULL,
  `idRoles` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`idUtilisateur`, `NomUtilisateur`, `PrenomUtilisateur`, `AdresseUtilisateur`, `FonctionUtilisateur`, `EmailUtilisateur`, `LoginUtilisateur`, `MDPUtilisateur`, `PhotoUtilisateur`, `encrypted_password`, `reset_password_tok`, `reset_password_sent_at`, `remember_created_at`, `sign_in_count`, `current_sign_in_at`, `last_sign_in_at`, `current_sign_in_ip`, `last_sign_in_ip`, `idRoles`) VALUES
(1, 'DIOP', 'Moustapha', 'Grand Mbao Baobab', 'Administrateur', 'moustapha.diop@mpem.gouv.sn', 'fassadiop', 'passer', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 'SY', 'Penda', 'Nord Foire', 'Utilisateur Fonctionnel', 'penda.sy@mpem.gouv.sn', 'contapenda', 'P@sser123', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 'NZALLE', 'Julie', 'Zac Mbao', 'Utilisateur Fonctionnel', 'julie.nzalle@mpem.gouv.sn', 'julienzalle', 'P@sser123', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, 'LY', 'Abdou Aziz', 'Joal', 'Utilisateur Regional', 'aziz.ly@mpem.gouv.sn', 'lyaziz', 'P@sser123', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(5, 'DIOP', 'Alioune', 'Tambacounda', 'Utilisateur Regional', 'alioune.diop@mpem.gouv.sn', 'diopalioune', 'P@sser123', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(6, 'DIOP', 'Moustapha', 'Grand Mbao Baobab', 'Administrateur', 'moustapha.diop@mpem.gouv.sn', 'UserAdmin', 'passer', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `autorisations`
--
ALTER TABLE `autorisations`
  ADD PRIMARY KEY (`idAutorisations`),
  ADD KEY `FKRolesAutorisations` (`idRoles`);

--
-- Index pour la table `cartemareyeur`
--
ALTER TABLE `cartemareyeur`
  ADD PRIMARY KEY (`idCarteMareyeur`),
  ADD UNIQUE KEY `NumeroCarte_UNIQUE` (`NumeroCarte`),
  ADD UNIQUE KEY `NumQuitance_UNIQUE` (`NumQuitance`),
  ADD KEY `FKCarteEntreprise_idx` (`idEntreprise`);

--
-- Index pour la table `delivrance`
--
ALTER TABLE `delivrance`
  ADD PRIMARY KEY (`idDelivrance`),
  ADD KEY `FKDelivrerCarte_idx` (`idCarteMareyeur`);

--
-- Index pour la table `departement`
--
ALTER TABLE `departement`
  ADD PRIMARY KEY (`idDepartement`),
  ADD KEY `FKServRegionDepart_idx` (`idRegion`);

--
-- Index pour la table `entreprise`
--
ALTER TABLE `entreprise`
  ADD PRIMARY KEY (`idEntreprise`),
  ADD KEY `FKPersEntreprise_idx` (`idPersonne`);

--
-- Index pour la table `personne`
--
ALTER TABLE `personne`
  ADD PRIMARY KEY (`idPersonne`),
  ADD UNIQUE KEY `CNI_UNIQUE` (`CNI`);

--
-- Index pour la table `region`
--
ALTER TABLE `region`
  ADD PRIMARY KEY (`idRegion`);

--
-- Index pour la table `renouvellement`
--
ALTER TABLE `renouvellement`
  ADD PRIMARY KEY (`idRenouvellement`),
  ADD UNIQUE KEY `NumQuitance_UNIQUE` (`NumQuitance`),
  ADD UNIQUE KEY `UC_Renewal_Per_Year` (`idCarteMareyeur`,`RenewalYear`);

--
-- Index pour la table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`idRoles`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`idUtilisateur`),
  ADD KEY `FKRolesUtilisateur` (`idRoles`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `autorisations`
--
ALTER TABLE `autorisations`
  MODIFY `idAutorisations` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `cartemareyeur`
--
ALTER TABLE `cartemareyeur`
  MODIFY `idCarteMareyeur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT pour la table `delivrance`
--
ALTER TABLE `delivrance`
  MODIFY `idDelivrance` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `departement`
--
ALTER TABLE `departement`
  MODIFY `idDepartement` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT pour la table `entreprise`
--
ALTER TABLE `entreprise`
  MODIFY `idEntreprise` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT pour la table `personne`
--
ALTER TABLE `personne`
  MODIFY `idPersonne` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=210;

--
-- AUTO_INCREMENT pour la table `region`
--
ALTER TABLE `region`
  MODIFY `idRegion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `renouvellement`
--
ALTER TABLE `renouvellement`
  MODIFY `idRenouvellement` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT pour la table `roles`
--
ALTER TABLE `roles`
  MODIFY `idRoles` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `idUtilisateur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `autorisations`
--
ALTER TABLE `autorisations`
  ADD CONSTRAINT `FKRolesAutorisations` FOREIGN KEY (`idRoles`) REFERENCES `roles` (`idRoles`);

--
-- Contraintes pour la table `cartemareyeur`
--
ALTER TABLE `cartemareyeur`
  ADD CONSTRAINT `FKCarteEntreprise` FOREIGN KEY (`idEntreprise`) REFERENCES `entreprise` (`idEntreprise`);

--
-- Contraintes pour la table `delivrance`
--
ALTER TABLE `delivrance`
  ADD CONSTRAINT `FKDelivrerCarte` FOREIGN KEY (`idCarteMareyeur`) REFERENCES `cartemareyeur` (`idCarteMareyeur`);

--
-- Contraintes pour la table `departement`
--
ALTER TABLE `departement`
  ADD CONSTRAINT `FKServRegionDepart` FOREIGN KEY (`idRegion`) REFERENCES `region` (`idRegion`);

--
-- Contraintes pour la table `entreprise`
--
ALTER TABLE `entreprise`
  ADD CONSTRAINT `FKPersEntreprise` FOREIGN KEY (`idPersonne`) REFERENCES `personne` (`idPersonne`);

--
-- Contraintes pour la table `renouvellement`
--
ALTER TABLE `renouvellement`
  ADD CONSTRAINT `FKRenouvCarte` FOREIGN KEY (`idCarteMareyeur`) REFERENCES `cartemareyeur` (`idCarteMareyeur`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
