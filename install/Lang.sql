DROP TABLE IF EXISTS `cms_tp6_lang`;
CREATE TABLE `cms_tp6_lang` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `lang` varchar(255) DEFAULT NULL COMMENT '语言代码',
  `name` varchar(255) DEFAULT NULL COMMENT '语言名称',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `cms_tp6_lang_dictionary`;
CREATE TABLE `cms_tp6_lang_dictionary` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lang` varchar(255) DEFAULT NULL COMMENT '语言代码',
  `key` varchar(255) DEFAULT NULL,
  `value` longtext,
  `type` tinyint(1) DEFAULT NULL COMMENT '类型 1常量 2变量',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='字典表';


DROP TABLE IF EXISTS `cms_tp6_lang_project`;
CREATE TABLE `cms_tp6_lang_project` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='项目，用于区分常量所属的项目';

DROP TABLE IF EXISTS `cms_tp6_lang_category`;
CREATE TABLE `cms_tp6_lang_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `key` varchar(255) NOT NULL,
  `project_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='分类，用于区分常量所属的分类';

DROP TABLE IF EXISTS `cms_tp6_lang_constant`;
CREATE TABLE `cms_tp6_lang_constant` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `key` varchar(512) NOT NULL DEFAULT '',
  `key_name` varchar(512) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `key` (`key`(255))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='常量表';

-- demo
CREATE TABLE `cms_tp6_lang_demo_car` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `model` varchar(255) DEFAULT NULL COMMENT '车型',
  `year` varchar(16) DEFAULT NULL COMMENT '年份',
  `transmission` tinyint(1) DEFAULT NULL COMMENT '变速箱类型',
  `vin` varchar(255) DEFAULT NULL COMMENT 'VIN 编码',
  `description` text,
  `input_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4;

INSERT INTO `cms_tp6_lang` (`lang`, `name`) VALUES ('zh_cn', '中文');
INSERT INTO `cms_tp6_lang` (`lang`, `name`) VALUES ('en', 'English');
INSERT INTO `cms_tp6_lang_dictionary` (`key`, `value`, `lang`, `type`) VALUES ('demo.model', 'Model', 'en', 2);
INSERT INTO `cms_tp6_lang_dictionary` (`key`, `value`, `lang`, `type`) VALUES ('demo.model', '车型', 'zh_cn', 2);
INSERT INTO `cms_tp6_lang_dictionary` (`key`, `value`, `lang`, `type`) VALUES('demo.year', 'Year', 'en', 2);
INSERT INTO `cms_tp6_lang_dictionary` (`key`, `value`, `lang`, `type`) VALUES('demo.year', '年份', 'zh_cn', 2);
INSERT INTO `cms_tp6_lang_dictionary` (`key`, `value`, `lang`, `type`) VALUES('demo.transmission', 'Transmission', 'en', 2);
INSERT INTO `cms_tp6_lang_dictionary` (`key`, `value`, `lang`, `type`) VALUES('demo.transmission', '变速箱', 'zh_cn', 2);
INSERT INTO `cms_tp6_lang_dictionary` (`key`, `value`, `lang`, `type`) VALUES('demo.transmission.not_limited', 'Not limited', 'en', 2);
INSERT INTO `cms_tp6_lang_dictionary` (`key`, `value`, `lang`, `type`) VALUES ('demo.transmission.not_limited', '不限制', 'zh_cn', 2);
INSERT INTO `cms_tp6_lang_dictionary` (`key`, `value`, `lang`, `type`) VALUES('demo.transmission.automatic', 'Automatic', 'en', 2);
INSERT INTO `cms_tp6_lang_dictionary` (`key`, `value`, `lang`, `type`) VALUES ( 'demo.transmission.automatic', '自动挡', 'zh_cn', 2);
INSERT INTO `cms_tp6_lang_dictionary` (`key`, `value`, `lang`, `type`) VALUES ( 'demo.transmission.manual', 'Manual', 'en', 2);
INSERT INTO `cms_tp6_lang_dictionary` (`key`, `value`, `lang`, `type`) VALUES ( 'demo.transmission.manual', '手动挡', 'zh_cn', 2);
