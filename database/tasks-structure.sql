
/*Table structure for table `status` */

DROP TABLE IF EXISTS `status`;
CREATE TABLE `status` (
  `status_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`status_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `tasks` */

DROP TABLE IF EXISTS `tasks`;
CREATE TABLE `tasks` (
  `task_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(600) COLLATE utf8_unicode_ci DEFAULT NULL,
  `start` date DEFAULT NULL,
  `finish` date DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`task_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

