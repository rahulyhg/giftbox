/*
Navicat MySQL Data Transfer

Source Server         : Developpement
Source Server Version : 50714
Source Host           : 127.0.0.1:3306
Source Database       : giftbox

Target Server Type    : MYSQL
Target Server Version : 50714
File Encoding         : 65001

Date: 2017-01-17 08:04:41
*/

SET FOREIGN_KEY_CHECKS=0;

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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of coffret
-- ----------------------------
