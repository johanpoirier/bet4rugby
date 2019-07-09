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

INSERT INTO `rwc2019__teams` (`teamID`, `name`, `poolID`, `status`) VALUES
(1, 'Japon', 1, 0),
(21, 'Irlande', 1, 0),
(22, 'Ecosse', 1, 0),
(23, 'Russie', 1, 0),
(24, 'Samoa', 1, 0),
(25, 'Nouvelle-Zelande', 2, 0),
(26, 'Afrique du Sud', 2, 0),
(27, 'Italie', 2, 0),
(28, 'Namibie', 2, 0),
(29, 'Canada', 2, 0),
(30, 'Angleterre', 3, 0),
(31, 'France', 3, 0),
(32, 'Argentine', 3, 0),
(33, 'Etats-Unis', 3, 0),
(34, 'Tonga', 3, 0),
(35, 'Australie', 4, 0),
(36, 'Pays de Galles', 4, 0),
(37, 'Georgie', 4, 0),
(38, 'Fidji', 4, 0),
(39, 'Uruguay', 4, 0);

INSERT INTO `rwc2019__phases` (`phaseID`, `name`, `aller_retour`, `nb_matchs`, `nb_qualifies`, `phasePrecedente`, `nbPointsRes`, `nbPointsQualifie`, `nbPointsScoreNiv1`, `nbPointsScoreNiv2`, `nbPointsEcartNiv1`, `nbPointsEcartNiv2`) VALUES
(1, 'Poules', 0, 40, 2, NULL, 10, 0, 3, 1, 3, 1);

INSERT INTO `rwc2019__pools` (`poolID`, `phaseID`, `name`) VALUES
(1, 1, 'A'),
(2, 1, 'B'),
(3, 1, 'C'),
(4, 1, 'D');

INSERT INTO `rwc2019__matchs` (`matchID`, `teamA`, `teamB`, `scoreA`, `scoreB`, `pnyA`, `pnyB`, `bonusA`, `bonusB`, `date`, `phaseID`, `status`) VALUES
(51, 1, 23, NULL, NULL, NULL, NULL, 0, 0, '2015-09-20 12:45:00', 1, 0),
(52, 21, 22, NULL, NULL, NULL, NULL, 0, 0, '2015-09-22 09:45:00', 1, 0),
(53, 23, 24, NULL, NULL, NULL, NULL, 0, 0, '2015-09-24 12:15:00', 1, 0),
(54, 1, 21, NULL, NULL, NULL, NULL, 0, 0, '2015-09-28 09:15:00', 1, 0),
(55, 22, 24, NULL, NULL, NULL, NULL, 0, 0, '2015-09-30 12:15:00', 1, 0),
(56, 21, 23, NULL, NULL, NULL, NULL, 0, 0, '2015-10-03 12:15:00', 1, 0),
(57, 1, 24, NULL, NULL, NULL, NULL, 0, 0, '2015-10-05 12:30:00', 1, 0),
(58, 22, 23, NULL, NULL, NULL, NULL, 0, 0, '2015-10-09 09:15:00', 1, 0),
(59, 21, 24, NULL, NULL, NULL, NULL, 0, 0, '2015-10-12 12:45:00', 1, 0),
(60, 1, 22, NULL, NULL, NULL, NULL, 0, 0, '2015-10-13 19:45:00', 1, 0),
(61, 25, 26, NULL, NULL, NULL, NULL, 0, 0, '2015-09-21 11:45:00', 1, 0),
(62, 27, 28, NULL, NULL, NULL, NULL, 0, 0, '2015-09-22 07:15:00', 1, 0),
(63, 27, 29, NULL, NULL, NULL, NULL, 0, 0, '2015-09-26 12:45:00', 1, 0),
(64, 26, 28, NULL, NULL, NULL, NULL, 0, 0, '2015-09-28 11:45:00', 1, 0),
(65, 25, 29, NULL, NULL, NULL, NULL, 0, 0, '2015-10-02 12:15:00', 1, 0),
(66, 26, 27, NULL, NULL, NULL, NULL, 0, 0, '2015-10-04 11:45:00', 1, 0),
(67, 25, 28, NULL, NULL, NULL, NULL, 0, 0, '2015-10-06 12:45:00', 1, 0),
(68, 26, 29, NULL, NULL, NULL, NULL, 0, 0, '2015-10-08 12:15:00', 1, 0),
(69, 25, 27, NULL, NULL, NULL, NULL, 0, 0, '2015-10-12 06:45:00', 1, 0),
(70, 28, 29, NULL, NULL, NULL, NULL, 0, 0, '2015-10-13 05:15:00', 1, 0),
(71, 31, 32, NULL, NULL, NULL, NULL, 0, 0, '2015-09-21 09:15:00', 1, 0),
(72, 30, 34, NULL, NULL, NULL, NULL, 0, 0, '2015-09-22 12:15:00', 1, 0),
(73, 30, 33, NULL, NULL, NULL, NULL, 0, 0, '2015-09-26 12:45:00', 1, 0),
(74, 32, 34, NULL, NULL, NULL, NULL, 0, 0, '2015-09-28 06:45:00', 1, 0),
(75, 31, 33, NULL, NULL, NULL, NULL, 0, 0, '2015-10-02 09:45:00', 1, 0),
(76, 30, 32, NULL, NULL, NULL, NULL, 0, 0, '2015-10-05 10:00:00', 1, 0),
(77, 31, 34, NULL, NULL, NULL, NULL, 0, 0, '2015-10-06 09:45:00', 1, 0),
(78, 32, 33, NULL, NULL, NULL, NULL, 0, 0, '2015-10-09 06:45:00', 1, 0),
(79, 30, 31, NULL, NULL, NULL, NULL, 0, 0, '2015-10-12 10:15:00', 1, 0),
(80, 33, 34, NULL, NULL, NULL, NULL, 0, 0, '2015-10-13 07:45:00', 1, 0),
(81, 35, 38, NULL, NULL, NULL, NULL, 0, 0, '2015-09-21 06:45:00', 1, 0),
(82, 36, 37, NULL, NULL, NULL, NULL, 0, 0, '2015-09-23 12:15:00', 1, 0),
(83, 38, 39, NULL, NULL, NULL, NULL, 0, 0, '2015-09-25 07:15:00', 1, 0),
(84, 37, 39, NULL, NULL, NULL, NULL, 0, 0, '2015-09-29 07:15:00', 1, 0),
(85, 35, 36, NULL, NULL, NULL, NULL, 0, 0, '2015-09-29 09:45:00', 1, 0),
(86, 37, 38, NULL, NULL, NULL, NULL, 0, 0, '2015-10-03 07:15:00', 1, 0),
(87, 35, 39, NULL, NULL, NULL, NULL, 0, 0, '2015-10-05 07:15:00', 1, 0),
(88, 36, 38, NULL, NULL, NULL, NULL, 0, 0, '2015-10-09 11:45:00', 1, 0),
(89, 35, 37, NULL, NULL, NULL, NULL, 0, 0, '2015-10-11 12:15:00', 1, 0),
(90, 36, 39, NULL, NULL, NULL, NULL, 0, 0, '2015-10-13 10:15:00', 1, 0);


CREATE USER 'bet4rugby'@'%' IDENTIFIED BY 'password';
GRANT ALL ON bets.* TO 'bet4rugby'@'%';
