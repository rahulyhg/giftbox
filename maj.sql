/*
Navicat MySQL Data Transfer

Source Server         : Developpement
Source Server Version : 50714
Source Host           : 127.0.0.1:3306
Source Database       : giftbox

Target Server Type    : MYSQL
Target Server Version : 50714
File Encoding         : 65001

Date: 2017-01-10 10:07:59
*/

SET FOREIGN_KEY_CHECKS=0;

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
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of notes
-- ----------------------------
INSERT INTO `notes` VALUES ('4', '14', '5');
INSERT INTO `notes` VALUES ('5', '14', '5');
INSERT INTO `notes` VALUES ('6', '14', '4');
INSERT INTO `notes` VALUES ('7', '14', '4');
INSERT INTO `notes` VALUES ('1', '12', '3');
INSERT INTO `notes` VALUES ('2', '13', '4');
INSERT INTO `notes` VALUES ('3', '13', '3');
INSERT INTO `notes` VALUES ('8', '23', '5');
INSERT INTO `notes` VALUES ('9', '23', '5');
INSERT INTO `notes` VALUES ('10', '23', '2');

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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of prestation
-- ----------------------------
INSERT INTO `prestation` VALUES ('1', 'Champagne', 'Bouteille de champagne + flutes + jeux à gratter', '1', 'champagne.jpg', '20.00', '0');
INSERT INTO `prestation` VALUES ('2', 'Musique', 'Partitions de piano à 4 mains', '1', 'musique.jpg', '25.00', '0');
INSERT INTO `prestation` VALUES ('3', 'Exposition', 'Visite guidée de l’exposition ‘REGARDER’ à la galerie Poirel', '2', 'poirelregarder.jpg', '14.00', '0');
INSERT INTO `prestation` VALUES ('4', 'Goûter', 'Goûter au FIFNL', '3', 'gouter.jpg', '20.00', '0');
INSERT INTO `prestation` VALUES ('5', 'Projection', 'Projection courts-métrages au FIFNL', '2', 'film.jpg', '10.00', '0');
INSERT INTO `prestation` VALUES ('6', 'Bouquet', 'Bouquet de roses et Mots de Marion Renaud', '1', 'rose.jpg', '16.00', '0');
INSERT INTO `prestation` VALUES ('7', 'Diner Stanislas', 'Diner à La Table du Bon Roi Stanislas (Apéritif /Entrée / Plat / Vin / Dessert / Café / Digestif)', '3', 'bonroi.jpg', '60.00', '0');
INSERT INTO `prestation` VALUES ('8', 'Origami', 'Baguettes magiques en Origami en buvant un thé', '3', 'origami.jpg', '12.00', '0');
INSERT INTO `prestation` VALUES ('9', 'Livres', 'Livre bricolage avec petits-enfants + Roman', '1', 'bricolage.jpg', '24.00', '0');
INSERT INTO `prestation` VALUES ('10', 'Diner  Grand Rue ', 'Diner au Grand’Ru(e) (Apéritif / Entrée / Plat / Vin / Dessert / Café)', '3', 'grandrue.jpg', '59.00', '0');
INSERT INTO `prestation` VALUES ('11', 'Visite guidée', 'Visite guidée personnalisée de Saint-Epvre jusqu’à Stanislas', '2', 'place.jpg', '11.00', '0');
INSERT INTO `prestation` VALUES ('12', 'Bijoux', 'Bijoux de manteau + Sous-verre pochette de disque + Lait après-soleil', '1', 'bijoux.jpg', '29.00', '1');
INSERT INTO `prestation` VALUES ('13', 'Opéra', 'Concert commenté à l’Opéra', '2', 'opera.jpg', '15.00', '2');
INSERT INTO `prestation` VALUES ('14', 'Thé Hotel de la reine', 'Thé de debriefing au bar de l’Hotel de la reine', '3', 'hotelreine.gif', '5.00', '4');
INSERT INTO `prestation` VALUES ('15', 'Jeu connaissance', 'Jeu pour faire connaissance', '2', 'connaissance.jpg', '6.00', '0');
INSERT INTO `prestation` VALUES ('16', 'Diner', 'Diner (Apéritif / Plat / Vin / Dessert / Café)', '3', 'diner.jpg', '40.00', '0');
INSERT INTO `prestation` VALUES ('17', 'Cadeaux individuels', 'Cadeaux individuels sur le thème de la soirée', '1', 'cadeaux.jpg', '13.00', '0');
INSERT INTO `prestation` VALUES ('18', 'Animation', 'Activité animée par un intervenant extérieur', '2', 'animateur.jpg', '9.00', '0');
INSERT INTO `prestation` VALUES ('19', 'Jeu contacts', 'Jeu pour échange de contacts', '2', 'contact.png', '5.00', '0');
INSERT INTO `prestation` VALUES ('20', 'Cocktail', 'Cocktail de fin de soirée', '3', 'cocktail.jpg', '12.00', '0');
INSERT INTO `prestation` VALUES ('21', 'Star Wars', 'Star Wars - Le Réveil de la Force. Séance cinéma 3D', '2', 'starwars.jpg', '12.00', '0');
INSERT INTO `prestation` VALUES ('22', 'Concert', 'Un concert à Nancy', '2', 'concert.jpg', '17.00', '0');
INSERT INTO `prestation` VALUES ('23', 'Appart Hotel', 'Appart’hôtel Coeur de Ville, en plein centre-ville', '4', 'apparthotel.jpg', '56.00', '3');
INSERT INTO `prestation` VALUES ('24', 'Hôtel d\'Haussonville', 'Hôtel d\'Haussonville, au coeur de la Vieille ville à deux pas de la place Stanislas', '4', 'hotel_haussonville_logo.jpg', '169.00', '0');
INSERT INTO `prestation` VALUES ('25', 'Boite de nuit', 'Discothèque, Boîte tendance avec des soirées à thème & DJ invités', '2', 'boitedenuit.jpg', '32.00', '0');
INSERT INTO `prestation` VALUES ('26', 'Planètes Laser', 'Laser game : Gilet électronique et pistolet laser comme matériel, vous voilà équipé.', '2', 'laser.jpg', '15.00', '0');
INSERT INTO `prestation` VALUES ('27', 'Fort Aventure', 'Découvrez Fort Aventure à Bainville-sur-Madon, un site Accropierre unique en Lorraine ! Des Parcours Acrobatiques pour petits et grands, Jeu Mission Aventure, Crypte de Crapahute, Tyrolienne, Saut à l\'élastique inversé, Toboggan géant... et bien plus encore.', '2', 'fort.jpg', '25.00', '0');
