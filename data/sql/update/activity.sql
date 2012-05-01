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
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:normal, 1:milestone',
  `private_flg` tinyint(1) NOT NULL DEFAULT '0',
  `sort` int(8) DEFAULT NULL,
  `del_flg` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
