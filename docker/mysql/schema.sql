CREATE DATABASE IF NOT EXISTS `bets`;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+02:00";

use bets;

-- --------------------------------------------------------

--
-- Structure de la table `rwc2019__invitations`
--

CREATE TABLE IF NOT EXISTS `rwc2019__invitations` (
  `code` varchar(32) COLLATE utf8_general_ci NOT NULL,
  `senderID` int(9) unsigned NOT NULL,
  `userTeamID` int(9) unsigned NOT NULL,
  `email` varchar(255) COLLATE utf8_general_ci NOT NULL,
  `expiration` datetime NOT NULL,
  `status` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `rwc2019__matchs`
--

CREATE TABLE IF NOT EXISTS `rwc2019__matchs` (
  `matchID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `teamA` int(11) NOT NULL DEFAULT '0',
  `teamB` int(11) NOT NULL DEFAULT '0',
  `scoreA` int(11) DEFAULT NULL,
  `scoreB` int(11) DEFAULT NULL,
  `pnyA` int(5) DEFAULT NULL,
  `pnyB` int(5) DEFAULT NULL,
  `bonusA` int(1) unsigned NOT NULL DEFAULT '0',
  `bonusB` int(1) unsigned NOT NULL DEFAULT '0',
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `phaseID` int(10) unsigned NOT NULL DEFAULT '1',
  `status` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`matchID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `rwc2019__phases`
--

CREATE TABLE IF NOT EXISTS `rwc2019__phases` (
  `phaseID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `aller_retour` int(1) unsigned NOT NULL DEFAULT '0',
  `nb_matchs` int(6) unsigned NOT NULL DEFAULT '0',
  `nb_qualifies` int(3) NOT NULL DEFAULT '1',
  `phasePrecedente` int(10) unsigned DEFAULT NULL,
  `nbPointsRes` int(6) unsigned NOT NULL DEFAULT '0',
  `nbPointsQualifie` int(6) unsigned NOT NULL DEFAULT '0',
  `nbPointsScoreNiv1` int(6) unsigned NOT NULL DEFAULT '0',
  `nbPointsScoreNiv2` int(6) unsigned NOT NULL DEFAULT '0',
  `nbPointsEcartNiv1` int(6) unsigned NOT NULL DEFAULT '0',
  `nbPointsEcartNiv2` int(6) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`phaseID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `rwc2019__pools`
--

CREATE TABLE IF NOT EXISTS `rwc2019__pools` (
  `poolID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phaseID` int(10) unsigned DEFAULT NULL,
  `name` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`poolID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `rwc2019__pronos`
--

CREATE TABLE IF NOT EXISTS `rwc2019__pronos` (
  `userID` int(9) NOT NULL DEFAULT '0',
  `matchID` int(9) NOT NULL DEFAULT '0',
  `scoreA` int(2) DEFAULT NULL,
  `scoreB` int(2) DEFAULT NULL,
  `pnyA` int(5) DEFAULT NULL,
  `pnyB` int(5) DEFAULT NULL,
  `status` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`userID`,`matchID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `rwc2019__settings`
--

CREATE TABLE IF NOT EXISTS `rwc2019__settings` (
  `name` varchar(35) NOT NULL DEFAULT '',
  `value` varchar(35) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `status` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `rwc2019__tags`
--

CREATE TABLE IF NOT EXISTS `rwc2019__tags` (
  `tagID` int(5) NOT NULL AUTO_INCREMENT,
  `userID` int(5) NOT NULL DEFAULT '0',
  `userTeamID` int(6) NOT NULL DEFAULT '-1',
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `tag` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`tagID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `rwc2019__teams`
--

CREATE TABLE IF NOT EXISTS `rwc2019__teams` (
  `teamID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `poolID` int(10) unsigned NOT NULL DEFAULT '0',
  `status` int(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`teamID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `rwc2019__users`
--

CREATE TABLE IF NOT EXISTS `rwc2019__users` (
  `userID` int(9) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `login` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `password` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `points` int(9) NOT NULL DEFAULT '0',
  `nbresults` int(5) NOT NULL DEFAULT '0',
  `nbscores` int(5) NOT NULL DEFAULT '0',
  `diff` int(5) NOT NULL DEFAULT '0',
  `last_rank` int(3) unsigned NOT NULL DEFAULT '1',
  `userTeamID` int(11) unsigned NOT NULL DEFAULT '0',
  `status` int(9) NOT NULL DEFAULT '0',
  PRIMARY KEY (`userID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `rwc2019__user_teams`
--

CREATE TABLE IF NOT EXISTS `rwc2019__user_teams` (
  `userTeamID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `password` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `ownerID` int(9) NOT NULL,
  `avgPoints` int(10) unsigned NOT NULL DEFAULT '0',
  `totalPoints` int(6) unsigned NOT NULL DEFAULT '0',
  `maxPoints` int(6) unsigned NOT NULL DEFAULT '0',
  `lastRank` int(6) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`userTeamID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `rwc2019__tokens`
--

CREATE TABLE `rwc2019__tokens` (
  `userID` int(9) UNSIGNED NOT NULL,
  `device` VARCHAR(36) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `token` VARCHAR(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `bet4soccer__competitions`
--

CREATE TABLE `bet4soccer__competitions` (
  `id` mediumint(9) NOT NULL,
  `domain` varchar(20) NOT NULL DEFAULT 'Public',
  `name` varchar(255) COLLATE utf8_general_ci NOT NULL,
  `startDate` date NOT NULL,
  `endDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `bet4soccer__palmares`
--

CREATE TABLE `bet4soccer__palmares` (
  `id` mediumint(9) NOT NULL,
  `competitionId` mediumint(9) NOT NULL,
  `userName` varchar(255) COLLATE utf8_general_ci NOT NULL,
  `userPoints` smallint(6) NOT NULL,
  `userResults` smallint(6) NOT NULL,
  `userScores` smallint(6) NOT NULL,
  `userDiff` smallint(6) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

/* password is: passw0rd */
INSERT INTO `rwc2019__users` (`userID`, `name`, `login`, `password`, `email`, `status`) VALUES
  (1, 'John Foo', 'admin', 'f353043d44e0cc8e91c7beb3e6f7c29cc2d22344e775bd7235cba22c3c2016042fa77fdaafb6bfe112390fc2870acc21739ae41d51b334d29e74b59c6568fc05', 'admin@bet4rubgy.fr', 1);

INSERT INTO `rwc2019__settings` (`name`, `date`) VALUES
('DATE_DEBUT', '2023_09_08 21:15:00'),
('DATE_FIN', '2023_10_19 21:00:00'),
('LAST_GENERATE', '2023_09_08 21:15:00');

INSERT INTO `rwc2019__settings` (`name`, `value`) VALUES
('NB_MATCHS_PLAYED', 0),
('NB_POINTS_VICTOIRE', 4),
('NB_POINTS_NUL', 2);

INSERT INTO `rwc2019__teams` (`teamID`, `name`, `poolID`, `status`) VALUES
(1, 'Nouvelle-Zelande', 1, 0),
(2, 'France', 1, 0),
(3, 'Italie', 1, 0),
(4, 'Uruguay', 1, 0),
(5, 'Namibie', 1, 0),
(6, 'Afrique du Sud', 2, 0),
(7, 'Irlande', 2, 0),
(8, 'Ecosse', 2, 0),
(9, 'Tonga', 2, 0),
(10, 'Roumanie', 2, 0),
(11, 'Pays de Galles', 3, 0),
(12, 'Australie', 3, 0),
(13, 'Fidji', 3, 0),
(14, 'Georgie', 3, 0),
(15, 'Portugal', 3, 0),
(16, 'Angleterre', 4, 0),
(17, 'Japon', 4, 0),
(18, 'Argentine', 4, 0),
(19, 'Samoa', 4, 0),
(20, 'Chili', 4, 0);

INSERT INTO `rwc2019__phases` (`phaseID`, `name`, `aller_retour`, `nb_matchs`, `nb_qualifies`, `phasePrecedente`, `nbPointsRes`, `nbPointsQualifie`, `nbPointsScoreNiv1`, `nbPointsScoreNiv2`, `nbPointsEcartNiv1`, `nbPointsEcartNiv2`) VALUES
(1, 'Poules', 0, 40, 2, NULL, 10, 0, 3, 1, 3, 1),
(3, 'Quarts de Finale', 0, 4, 1, 1, 13, 0, 4, 2, 4, 2),
(4, 'Demi-Finales', 0, 2, 1, 3, 16, 0, 5, 2, 5, 2),
(5, 'Finale 3ème place', 0, 1, -1, 4, 16, 0, 5, 2, 5, 2),
(6, 'Finale', 0, 1, 1, 4, 20, 0, 6, 3, 6, 3);

INSERT INTO `rwc2019__pools` (`poolID`, `phaseID`, `name`) VALUES
(1, 1, 'A'),
(2, 1, 'B'),
(3, 1, 'C'),
(4, 1, 'D');

INSERT INTO `rwc2019__matchs` (`matchID`, `teamA`, `teamB`, `scoreA`, `scoreB`, `pnyA`, `pnyB`, `bonusA`, `bonusB`, `date`, `phaseID`, `status`) VALUES
(1, 2, 1, NULL, NULL, NULL, NULL, 0, 0, '2023-09-08 21:15:00', 1, 0);


CREATE USER 'bet4rugby'@'%' IDENTIFIED BY 'password';
GRANT ALL ON bets.* TO 'bet4rugby'@'%';
