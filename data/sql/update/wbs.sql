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
  `sort` int(8) DEFAULT NULL,
  `del_flg` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8