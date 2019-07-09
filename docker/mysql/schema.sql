CREATE DATABASE IF NOT EXISTS `bets`;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+02:00";

use bets;

--
-- Base de données :  `bets`
--

-- --------------------------------------------------------

-- phpMyAdmin SQL Dump
-- version 4.1.9
-- http://www.phpmyadmin.net
--
-- Client :  nirgal.mysql.db
-- Généré le :  Jeu 18 Juin 2015 à 09:20
-- Version du serveur :  5.1.73-2+squeeze+build1+1-log
-- Version de PHP :  5.3.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `nirgal`
--

-- --------------------------------------------------------

--
-- Structure de la table `rwc2019__invitations`
--

CREATE TABLE IF NOT EXISTS `rwc2019__invitations` (
  `code` varchar(32) COLLATE utf8_bin NOT NULL,
  `senderID` int(9) unsigned NOT NULL,
  `userTeamID` int(9) unsigned NOT NULL,
  `email` varchar(255) COLLATE utf8_bin NOT NULL,
  `expiration` datetime NOT NULL,
  `status` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=51 ;

-- --------------------------------------------------------

--
-- Structure de la table `rwc2019__phases`
--

CREATE TABLE IF NOT EXISTS `rwc2019__phases` (
  `phaseID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Structure de la table `rwc2019__pools`
--

CREATE TABLE IF NOT EXISTS `rwc2019__pools` (
  `poolID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phaseID` int(10) unsigned DEFAULT NULL,
  `name` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  PRIMARY KEY (`poolID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=9 ;

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `rwc2019__tags`
--

CREATE TABLE IF NOT EXISTS `rwc2019__tags` (
  `tagID` int(5) NOT NULL AUTO_INCREMENT,
  `userID` int(5) NOT NULL DEFAULT '0',
  `userTeamID` int(6) NOT NULL DEFAULT '-1',
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `tag` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`tagID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=204 ;

-- --------------------------------------------------------

--
-- Structure de la table `rwc2019__teams`
--

CREATE TABLE IF NOT EXISTS `rwc2019__teams` (
  `teamID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  `poolID` int(10) unsigned NOT NULL DEFAULT '0',
  `status` int(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`teamID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

-- --------------------------------------------------------

--
-- Structure de la table `rwc2019__users`
--

CREATE TABLE IF NOT EXISTS `rwc2019__users` (
  `userID` int(9) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  `login` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  `password` varchar(128) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `points` int(9) NOT NULL DEFAULT '0',
  `nbresults` int(5) NOT NULL DEFAULT '0',
  `nbscores` int(5) NOT NULL DEFAULT '0',
  `diff` int(5) NOT NULL DEFAULT '0',
  `last_rank` int(3) unsigned NOT NULL DEFAULT '1',
  `userTeamID` int(11) unsigned NOT NULL DEFAULT '0',
  `status` int(9) NOT NULL DEFAULT '0',
  PRIMARY KEY (`userID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=228 ;

-- --------------------------------------------------------

--
-- Structure de la table `rwc2019__user_teams`
--

CREATE TABLE IF NOT EXISTS `rwc2019__user_teams` (
  `userTeamID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  `password` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `ownerID` int(9) NOT NULL,
  `avgPoints` int(10) unsigned NOT NULL DEFAULT '0',
  `totalPoints` int(6) unsigned NOT NULL DEFAULT '0',
  `maxPoints` int(6) unsigned NOT NULL DEFAULT '0',
  `lastRank` int(6) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`userTeamID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=12 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

/* password is: passw0rd */
INSERT INTO `rwc2019__users` (`userID`, `name`, `login`, `password`, `email`, `status`) VALUES
  (1, 'John Foo', 'admin', 'f353043d44e0cc8e91c7beb3e6f7c29cc2d22344e775bd7235cba22c3c2016042fa77fdaafb6bfe112390fc2870acc21739ae41d51b334d29e74b59c6568fc05', 'admin@bet4rubgy.fr', 1);


CREATE USER 'bet4rugby'@'%' IDENTIFIED BY 'password';
GRANT ALL ON bets.* TO 'bet4rugby'@'%';
