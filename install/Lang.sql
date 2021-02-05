CREATE TABLE `cms_tp6_lang` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `lang` varchar(255) DEFAULT NULL COMMENT '语言代码',
  `name` varchar(255) DEFAULT NULL COMMENT '语言名称',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `cms_tp6_lang_dictionary` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lang` varchar(255) DEFAULT NULL COMMENT '语言代码',
  `key` varchar(255) DEFAULT NULL,
  `value` longtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;