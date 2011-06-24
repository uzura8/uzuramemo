DROP TABLE IF EXISTS `task`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `task` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `task_category_id` int(5) NOT NULL DEFAULT '0',
  `name` varchar(200) NOT NULL DEFAULT '',
  `body` text,
  `explanation` text,
  `sort` int(8) NOT NULL DEFAULT '999',
  `del_flg` tinyint(1) NOT NULL DEFAULT '0',
  `html_flg` tinyint(1) NOT NULL DEFAULT '0',
  `limitdate` date DEFAULT '0000-00-00' COMMENT '予定日',
  `startdate` date DEFAULT NULL COMMENT '開始日',
  `enddate` date DEFAULT NULL COMMENT '終了日',
  `private_flg` tinyint(1) DEFAULT '0',
  `priority` tinyint(1) DEFAULT '2',
  `work_time` float NOT NULL DEFAULT '0' COMMENT '作業時間',
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

DROP TABLE IF EXISTS `task_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `task_category` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `sub_id` int(4) NOT NULL DEFAULT '0',
  `explanation` text,
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
