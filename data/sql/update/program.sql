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
  `private_flg` tinyint(1) DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `key_name` (`key_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
