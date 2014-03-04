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
  `body` mediumtext NOT NULL,
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

--
-- Table structure for table `activity`
--

DROP TABLE IF EXISTS `activity`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `activity` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `wbs_id` int(8) NOT NULL DEFAULT '0',
  `name` varchar(200) NOT NULL DEFAULT '',
  `body` text,
  `explanation` text,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:実施待ち, 1:本日実施予定, 2:着手, 3:完了',
  `scheduled_date` date DEFAULT NULL COMMENT '実施予定日',
  `due_date` date DEFAULT NULL COMMENT '期日',
  `closed_date` date DEFAULT NULL COMMENT '終了日',
  `estimated_time` float DEFAULT NULL COMMENT '見積り工数:時',
  `spent_time` float DEFAULT NULL COMMENT '実工数:時',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:normal, 1:milestone',
  `private_flg` tinyint(1) NOT NULL DEFAULT '0',
  `importance` tinyint(1) NOT NULL DEFAULT '0',
  `sort` int(8) DEFAULT NULL,
  `del_flg` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `wbs_id_wbs_id` (`wbs_id`),
  CONSTRAINT `wbs_id_wbs_id` FOREIGN KEY (`wbs_id`) REFERENCES `wbs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `admin_user`
--

DROP TABLE IF EXISTS `admin_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(64) NOT NULL,
  `password` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `holiday`
--

DROP TABLE IF EXISTS `holiday`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `holiday` (
  `id` tinyint(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL DEFAULT '',
  `date` date NOT NULL,
  `explanation` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `date` (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
  `body` mediumtext NOT NULL,
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `program`
--

DROP TABLE IF EXISTS `program`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `program` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL DEFAULT '',
  `key_name` varchar(20) DEFAULT NULL,
  `body` text,
  `explanation` text,
  `customer` varchar(200) DEFAULT NULL,
  `sort` int(8) DEFAULT NULL,
  `del_flg` tinyint(1) NOT NULL DEFAULT '0',
  `html_flg` tinyint(1) NOT NULL DEFAULT '0',
  `private_flg` tinyint(1) NOT NULL DEFAULT '0',
  `color` varchar(7) DEFAULT NULL,
  `background_color` varchar(7) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `key_name` (`key_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `project`
--

DROP TABLE IF EXISTS `project`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `project` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `program_id` int(8) NOT NULL DEFAULT '0',
  `name` varchar(200) NOT NULL DEFAULT '',
  `key_name` varchar(20) DEFAULT NULL,
  `body` text,
  `explanation` text,
  `customer` varchar(200) DEFAULT NULL,
  `sort` int(8) DEFAULT NULL,
  `del_flg` tinyint(1) NOT NULL DEFAULT '0',
  `html_flg` tinyint(1) NOT NULL DEFAULT '0',
  `private_flg` tinyint(1) DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `due_date` date DEFAULT NULL COMMENT '期日',
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_name` (`key_name`),
  KEY `program_id_program_id` (`program_id`),
  CONSTRAINT `program_id_program_id` FOREIGN KEY (`program_id`) REFERENCES `program` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `wbs`
--

DROP TABLE IF EXISTS `wbs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wbs` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `project_id` int(8) NOT NULL DEFAULT '0',
  `parent_id` int(8) NOT NULL DEFAULT '0',
  `name` varchar(200) NOT NULL DEFAULT '',
  `body` text,
  `explanation` text,
  `work_class_id` tinyint(3) DEFAULT '0',
  `estimated_time` float DEFAULT '0' COMMENT '見積工数',
  `spent_time` float DEFAULT '0' COMMENT '実績工数',
  `percent_complete` tinyint(3) DEFAULT '0' COMMENT '進捗率',
  `start_date` date DEFAULT NULL COMMENT '開始日',
  `due_date` date DEFAULT NULL COMMENT '期日',
  `html_flg` tinyint(1) NOT NULL DEFAULT '0',
  `private_flg` tinyint(1) DEFAULT '0',
  `importance` tinyint(1) NOT NULL DEFAULT '0',
  `sort` int(8) DEFAULT NULL,
  `del_flg` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `project_id_project_id` (`project_id`),
  CONSTRAINT `project_id_project_id` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `work_class`
--

DROP TABLE IF EXISTS `work_class`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `work_class` (
  `id` tinyint(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL DEFAULT '',
  `body` text,
  `color` varchar(50) DEFAULT NULL,
  `sort` int(8) DEFAULT NULL,
  `del_flg` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `sort` (`sort`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;


LOCK TABLES `work_class` WRITE;
/*!40000 ALTER TABLE `work_class` DISABLE KEYS */;
INSERT INTO `work_class` VALUES (1,'実装',NULL,'#B8EEFF',1,0),(2,'レビュー',NULL,'#FFB083',2,0),(3,'テスト',NULL,'#FFFB82',3,0),(4,'管理',NULL,'#FF9191',4,0),(5,'サーバ',NULL,'#B8BAFD',5,0),(6,'事務',NULL,'#DEE2B6',6,0);
/*!40000 ALTER TABLE `work_class` ENABLE KEYS */;
UNLOCK TABLES;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
