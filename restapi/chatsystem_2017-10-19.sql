# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.6.35)
# Database: chatsystem
# Generation Time: 2017-10-19 00:43:42 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_fullname` varchar(25) NOT NULL,
  `user_email` varchar(50) NOT NULL,
  `user_password` varchar(50) NOT NULL,
  `user_status` tinyint(1) NOT NULL DEFAULT '0',
  `user_type` int(1) DEFAULT '1',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;

INSERT INTO `users` (`user_id`, `user_fullname`, `user_email`, `user_password`, `user_status`, `user_type`)
VALUES
	(1,'test','test@gmail.com','1234',1,1),
	(2,'test2','test2@gmail.com','1234',1,1),
	(3,'test3','test3@gmail.com','1234',1,1),
	(4,'Group_service','g1@group.com','123456',1,2);

/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

# Dump of table conversation
# ------------------------------------------------------------

DROP TABLE IF EXISTS `conversation`;

CREATE TABLE `conversation` (
  `c_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_one` int(11) NOT NULL,
  `user_two` int(11) NOT NULL,
  `ipaddress` varchar(30) DEFAULT NULL,
  `time_chat` int(11) DEFAULT NULL,
  PRIMARY KEY (`c_id`),
  KEY `user_one` (`user_one`),
  KEY `user_two` (`user_two`),
  CONSTRAINT `conversation_ibfk_1` FOREIGN KEY (`user_one`) REFERENCES `users` (`user_id`),
  CONSTRAINT `conversation_ibfk_2` FOREIGN KEY (`user_two`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

LOCK TABLES `conversation` WRITE;
/*!40000 ALTER TABLE `conversation` DISABLE KEYS */;

INSERT INTO `conversation` (`c_id`, `user_one`, `user_two`, `ipaddress`, `time_chat`)
VALUES
	(1,1,2,NULL,NULL),
	(2,2,3,'127.0.0.1',1505257617),
	(5,4,1,NULL,NULL),
	(6,4,2,NULL,NULL),
	(7,4,3,NULL,NULL);

/*!40000 ALTER TABLE `conversation` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table conversation_reply_bk
# ------------------------------------------------------------

DROP TABLE IF EXISTS `conversation_reply_bk`;

CREATE TABLE `conversation_reply_bk` (
  `cr_id` int(11) NOT NULL AUTO_INCREMENT,
  `reply` text,
  `user_id_fk` int(11) NOT NULL,
  `ip` varchar(30) NOT NULL,
  `time_chat` int(11) NOT NULL,
  `c_id_fk` int(11) NOT NULL,
  `to_user_one` int(11) DEFAULT NULL,
  PRIMARY KEY (`cr_id`),
  KEY `user_id_fk` (`user_id_fk`),
  KEY `c_id_fk` (`c_id_fk`),
  CONSTRAINT `conversation_reply_bk_ibfk_1` FOREIGN KEY (`user_id_fk`) REFERENCES `users` (`user_id`),
  CONSTRAINT `conversation_reply_bk_ibfk_2` FOREIGN KEY (`c_id_fk`) REFERENCES `conversation` (`c_id`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8;

LOCK TABLES `conversation_reply_bk` WRITE;
/*!40000 ALTER TABLE `conversation_reply_bk` DISABLE KEYS */;

INSERT INTO `conversation_reply_bk` (`cr_id`, `reply`, `user_id_fk`, `ip`, `time_chat`, `c_id_fk`, `to_user_one`)
VALUES
	(1,'hi test2',1,'127.0.0.1',1505694487,1,NULL),
	(2,'hi test',2,'127.0.0.1',1505694491,1,NULL),
	(3,'lets go watch movie',1,'127.0.0.1',1505694507,1,NULL),
	(4,'sure, but what is the best movie?',2,'127.0.0.1',1505694520,1,NULL),
	(5,':p',1,'127.0.0.1',1505694527,1,NULL),
	(6,'how about now you see me 2 ?',2,'127.0.0.1',1505694547,1,NULL),
	(7,'I dun think the movie still showing in cinema',1,'127.0.0.1',1505694569,1,NULL),
	(8,'how about kingsman: the golden circle?',2,'127.0.0.1',1505694610,1,NULL),
	(9,'that one is cool',1,'127.0.0.1',1505694618,1,NULL),
	(10,'ok see ya @2pm today',2,'127.0.0.1',1505694640,1,NULL),
	(11,'ok',1,'127.0.0.1',1505694643,1,NULL),
	(12,'hi test3',2,'127.0.0.1',1505694801,2,NULL),
	(13,'test',2,'127.0.0.1',1505694856,2,NULL),
	(14,'ok',2,'127.0.0.1',1505694865,1,NULL),
	(15,'hello?',2,'127.0.0.1',1505694883,2,NULL),
	(16,'hello',2,'127.0.0.1',1505695131,2,NULL),
	(17,'hi test',2,'127.0.0.1',1505695141,1,NULL),
	(18,'hi',1,'127.0.0.1',1505695151,1,NULL),
	(19,'are u ok?',2,'127.0.0.1',1505695172,1,NULL),
	(20,'i am fine',1,'127.0.0.1',1505695212,1,NULL),
	(21,'hello',2,'127.0.0.1',1505695228,2,NULL),
	(22,'ok',2,'127.0.0.1',1505695235,1,NULL),
	(23,'glad to hear that',2,'127.0.0.1',1505695240,1,NULL),
	(24,'test3, would u like to go watch movie?',2,'127.0.0.1',1505695258,2,NULL),
	(25,'with us',2,'127.0.0.1',1505695264,2,NULL),
	(26,'sure',3,'127.0.0.1',1505695310,2,NULL),
	(27,'ok',3,'127.0.0.1',1505695317,2,NULL),
	(28,'test3?',2,'127.0.0.1',1505695366,2,NULL),
	(29,'hi',2,'127.0.0.1',1505705918,1,NULL),
	(30,'holla',2,'127.0.0.1',1505705924,1,NULL),
	(31,'test',2,'127.0.0.1',1505710948,1,NULL),
	(32,'hahaha',2,'127.0.0.1',1505711041,1,NULL),
	(33,'cuba',1,'127.0.0.1',1505711051,1,NULL),
	(34,'cuba jaya',1,'127.0.0.1',1505711062,1,NULL),
	(35,'test',1,'127.0.0.1',1505830087,1,NULL),
	(36,'cuba',1,'127.0.0.1',1505830099,1,NULL),
	(37,'test',2,'127.0.0.1',1505830142,1,NULL),
	(38,'hello, sy farid',1,'127.0.0.1',1505830150,1,NULL),
	(39,'hello',2,'127.0.0.1',1506612526,1,NULL),
	(40,'hello',2,'127.0.0.1',1506612964,1,NULL),
	(41,'test',1,'127.0.0.1',1508345050,5,4),
	(42,'testing group 1',1,'127.0.0.1',1508357518,5,4),
	(43,'hi',2,'127.0.0.1',1508358580,6,4),
	(44,'cuba',2,'127.0.0.1',1508358963,6,4),
	(45,'hello team',2,'127.0.0.1',1508358989,6,4),
	(46,'hello',2,'127.0.0.1',1508358993,6,4),
	(47,'try',2,'127.0.0.1',1508359126,6,4),
	(48,'hi',1,'127.0.0.1',1508359226,5,4),
	(49,'hello',1,'127.0.0.1',1508359265,5,4),
	(50,'hi',1,'127.0.0.1',1508359368,5,4),
	(51,'hi',1,'127.0.0.1',1508359389,5,4),
	(52,'hi group',1,'127.0.0.1',1508359401,5,4),
	(53,'hi',2,'127.0.0.1',1508359418,6,4),
	(54,'hi',1,'127.0.0.1',1508359427,5,4),
	(55,'hello',2,'127.0.0.1',1508359437,6,4),
	(56,'try one',1,'127.0.0.1',1508359455,5,4),
	(57,'',1,'127.0.0.1',1508359552,5,4),
	(58,'',1,'127.0.0.1',1508359559,5,4),
	(59,'hell geng',1,'127.0.0.1',1508359838,5,4),
	(60,'hello geng',1,'127.0.0.1',1508359846,5,4);

/*!40000 ALTER TABLE `conversation_reply_bk` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table conversation_reply
# ------------------------------------------------------------

DROP TABLE IF EXISTS `conversation_reply`;

CREATE TABLE `conversation_reply` (
  `cr_id` int(11) NOT NULL AUTO_INCREMENT,
  `reply` text,
  `user_id_fk` int(11) NOT NULL,
  `ip` varchar(30) NOT NULL,
  `time_chat` int(11) NOT NULL,
  `c_id_fk` int(11) NOT NULL,
  `to_user_one` int(11) DEFAULT NULL,
  PRIMARY KEY (`cr_id`),
  KEY `user_id_fk` (`user_id_fk`),
  KEY `c_id_fk` (`c_id_fk`),
  CONSTRAINT `conversation_reply_ibfk_1` FOREIGN KEY (`user_id_fk`) REFERENCES `users` (`user_id`),
  CONSTRAINT `conversation_reply_ibfk_2` FOREIGN KEY (`c_id_fk`) REFERENCES `conversation` (`c_id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

LOCK TABLES `conversation_reply` WRITE;
/*!40000 ALTER TABLE `conversation_reply` DISABLE KEYS */;

INSERT INTO `conversation_reply` (`cr_id`, `reply`, `user_id_fk`, `ip`, `time_chat`, `c_id_fk`, `to_user_one`)
VALUES
	(1,'hi group',1,'127.0.0.1',1508359941,5,4),
	(2,'hi test',1,'127.0.0.1',1508359959,5,4),
	(3,'hi group',2,'127.0.0.1',1508360220,6,4),
	(4,'hello',2,'127.0.0.1',1508360259,6,4),
	(5,'hi',2,'127.0.0.1',1508360359,1,1),
	(6,'hello',2,'127.0.0.1',1508360363,1,1),
	(7,'hello',2,'127.0.0.1',1508360367,1,1),
	(8,'hi',1,'127.0.0.1',1508360420,1,2),
	(9,'hello',1,'127.0.0.1',1508360427,1,2),
	(10,'hi group',1,'127.0.0.1',1508360507,5,4),
	(11,'hi',2,'127.0.0.1',1508361899,1,1),
	(12,'hello',1,'127.0.0.1',1508361921,5,4),
	(13,'hello test2',1,'127.0.0.1',1508361946,1,2),
	(14,'hello test',2,'127.0.0.1',1508361954,1,1),
	(15,'hi team',1,'127.0.0.1',1508361971,5,4),
	(16,'hi',2,'127.0.0.1',1508361977,6,4),
	(17,'try',1,'127.0.0.1',1508362072,1,2),
	(18,'error',2,'127.0.0.1',1508362075,1,1);

/*!40000 ALTER TABLE `conversation_reply` ENABLE KEYS */;
UNLOCK TABLES;






/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
