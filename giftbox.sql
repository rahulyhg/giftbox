/*
Navicat MySQL Data Transfer

Source Server         : Developpement
Source Server Version : 50714
Source Host           : 127.0.0.1:3306
Source Database       : giftbox

Target Server Type    : MYSQL
Target Server Version : 50714
File Encoding         : 65001

Date: 2017-01-18 18:21:08
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for administrateurs
-- ----------------------------
DROP TABLE IF EXISTS `administrateurs`;
CREATE TABLE `administrateurs` (
  `id` int(11) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for cagnotte
-- ----------------------------
DROP TABLE IF EXISTS `cagnotte`;
CREATE TABLE `cagnotte` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `coffret_id` int(11) NOT NULL,
  `montant` int(11) NOT NULL,
  `urlContribution` text NOT NULL,
  `urlGestion` text NOT NULL,
  `cloture` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cagnotte_ibfk_1` (`coffret_id`),
  CONSTRAINT `cagnotte_ibfk_1` FOREIGN KEY (`coffret_id`) REFERENCES `coffret` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for categorie
-- ----------------------------
DROP TABLE IF EXISTS `categorie`;
CREATE TABLE `categorie` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for coffret
-- ----------------------------
DROP TABLE IF EXISTS `coffret`;
CREATE TABLE `coffret` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` text NOT NULL,
  `prenom` text NOT NULL,
  `email` text NOT NULL,
  `message` text NOT NULL,
  `password` text NOT NULL,
  `paiement` varchar(10) NOT NULL,
  `url` text NOT NULL,
  `urlGestion` text NOT NULL,
  `statut` text NOT NULL,
  `montant` int(11) NOT NULL,
  `destinataire` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for coffretcontenu
-- ----------------------------
DROP TABLE IF EXISTS `coffretcontenu`;
CREATE TABLE `coffretcontenu` (
  `coffret_id` int(11) NOT NULL,
  `prestation_id` int(11) NOT NULL,
  `qua` int(255) NOT NULL DEFAULT '0',
  KEY `prestation_id` (`prestation_id`),
  KEY `coffret_id` (`coffret_id`),
  CONSTRAINT `coffretcontenu_ibfk_1` FOREIGN KEY (`coffret_id`) REFERENCES `coffret` (`id`),
  CONSTRAINT `coffretcontenu_ibfk_2` FOREIGN KEY (`prestation_id`) REFERENCES `prestation` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for notes
-- ----------------------------
DROP TABLE IF EXISTS `notes`;
CREATE TABLE `notes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prestationId` int(11) DEFAULT NULL,
  `note` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `prestationId_fk` (`prestationId`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for prestation
-- ----------------------------
DROP TABLE IF EXISTS `prestation`;
CREATE TABLE `prestation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` text NOT NULL,
  `descr` text NOT NULL,
  `cat_id` int(11) NOT NULL,
  `img` text NOT NULL,
  `prix` decimal(5,2) NOT NULL,
  `votes` int(11) DEFAULT '0',
  `visible` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;
