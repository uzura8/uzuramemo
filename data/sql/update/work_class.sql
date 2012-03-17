SET NAMES utf8;
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

INSERT INTO work_class VALUES (NULL, '����', NULL, '#B8EEFF', '1', '0');
INSERT INTO work_class VALUES (NULL, '���r���[', NULL, '#FFB083', '2', '0');
INSERT INTO work_class VALUES (NULL, '�e�X�g', NULL, '#FFFB82', '3', '0');
INSERT INTO work_class VALUES (NULL, '�Ǘ�', NULL, '#FF9191', '4', '0');
INSERT INTO work_class VALUES (NULL, '�T�[�o', NULL, '#B8BAFD', '5', '0');
INSERT INTO work_class VALUES (NULL, '����', NULL, '#DEE2B6', '6', '0');
