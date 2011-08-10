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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO work_class VALUES (NULL, '実装', NULL, '#B8EEFF', '1', '0');
INSERT INTO work_class VALUES (NULL, 'レビュー', NULL, '#FFB083', '2', '0');
INSERT INTO work_class VALUES (NULL, 'テスト', NULL, '#FFFB82', '3', '0');
INSERT INTO work_class VALUES (NULL, '管理', NULL, '#FF9191', '4', '0');
INSERT INTO work_class VALUES (NULL, 'サーバ', NULL, '#B8BAFD', '5', '0');
INSERT INTO work_class VALUES (NULL, '事務', NULL, '#DEE2B6', '6', '0');
