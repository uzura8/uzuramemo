-- MySQL dump 10.13  Distrib 5.1.48, for apple-darwin10.4.0 (i386)
--
-- Host: localhost    Database: ci2_dev_webmemo_test
-- ------------------------------------------------------
-- Server version	5.1.48-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `memo`
--

DROP TABLE IF EXISTS `memo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `memo` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `memo_category_id` int(4) NOT NULL DEFAULT '0',
  `title` varchar(140) NOT NULL DEFAULT '',
  `explain` text,
  `body` text NOT NULL,
  `sort` int(6) NOT NULL DEFAULT '999',
  `del_flg` tinyint(1) NOT NULL DEFAULT '0',
  `format` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `private_flg` tinyint(1) NOT NULL DEFAULT '0',
  `quote_flg` tinyint(1) NOT NULL DEFAULT '0',
  `important_level` tinyint(1) NOT NULL DEFAULT '2',
  `keyword` text,
  PRIMARY KEY (`id`),
  KEY `del_flg_private_flg_updated_at` (`del_flg`,`private_flg`,`updated_at`),
  KEY `mc_id` (`memo_category_id`),
  KEY `important_level_sort` (`important_level`,`sort`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `memo_category`
--

DROP TABLE IF EXISTS `memo_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `memo_category` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `sub_id` int(4) NOT NULL DEFAULT '0',
  `explain` text,
  `sort` int(4) NOT NULL DEFAULT '0',
  `del_flg` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `key_name` varchar(20) DEFAULT NULL,
  `is_private` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `sub_id_del_flg_sort` (`sub_id`,`del_flg`,`sort`),
  KEY `key_name` (`key_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

CREATE TABLE admin_user (
  id int(11) NOT NULL auto_increment,
  username varchar(64) NOT NULL,
  password varchar(64) NOT NULL,
  primary key (id),
  UNIQUE key username (username)
) ENGINE=MYISAM DEFAULT CHARSET=UTF8;

INSERT INTO admin_user VALUES (NULL, 'admin', MD5('password'));

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2011-04-21  0:38:05
