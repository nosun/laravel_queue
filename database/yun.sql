/*
Navicat MySQL Data Transfer

Source Server         : homestead
Source Server Version : 50619
Source Host           : localhost:33060
Source Database       : yun

Target Server Type    : MYSQL
Target Server Version : 50619
File Encoding         : 65001

Date: 2015-10-31 22:20:17
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `event`
-- ----------------------------
DROP TABLE IF EXISTS `event`;
CREATE TABLE `event` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` tinyint(4) NOT NULL COMMENT '1:提醒;2:待办',
  `level` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1:nomal;2:important;3:emergency',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of event
-- ----------------------------
INSERT INTO `event` VALUES ('1', 'UserLoggedIn', '登录', '用户登录', '1', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- ----------------------------
-- Table structure for `failed_jobs`
-- ----------------------------
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `connection` text COLLATE utf8_unicode_ci NOT NULL,
  `queue` text COLLATE utf8_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of failed_jobs
-- ----------------------------

-- ----------------------------
-- Table structure for `message`
-- ----------------------------
DROP TABLE IF EXISTS `message`;
CREATE TABLE `message` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` int(4) DEFAULT NULL,
  `level` int(4) DEFAULT NULL,
  `user_id` int(10) DEFAULT NULL,
  `status` int(4) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `content` text,
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of message
-- ----------------------------

-- ----------------------------
-- Table structure for `migrations`
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES ('2014_10_12_000000_create_users_table', '1');
INSERT INTO `migrations` VALUES ('2014_10_12_100000_create_password_resets_table', '1');
INSERT INTO `migrations` VALUES ('2015_10_18_030119_create_event_table', '1');
INSERT INTO `migrations` VALUES ('2015_10_18_030211_create_failed_jobs_table', '1');
INSERT INTO `migrations` VALUES ('2015_10_18_030249_create_notify_table', '1');

-- ----------------------------
-- Table structure for `notify`
-- ----------------------------
DROP TABLE IF EXISTS `notify`;
CREATE TABLE `notify` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `event_id` int(10) unsigned NOT NULL,
  `channel_id` int(10) unsigned NOT NULL,
  `template_id` int(10) unsigned NOT NULL,
  `level` tinyint(4) DEFAULT '0',
  `status` int(1) unsigned zerofill DEFAULT '0' COMMENT '开关',
  PRIMARY KEY (`id`),
  UNIQUE KEY `event_channel_id` (`event_id`,`channel_id`) USING BTREE,
  KEY `event_channel_channel_id_foreign` (`channel_id`),
  CONSTRAINT `event_channel_channel_id_foreign` FOREIGN KEY (`channel_id`) REFERENCES `notify_channel` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `event_channel_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of notify
-- ----------------------------
INSERT INTO `notify` VALUES ('1', '1', '1', '1', '1', '1');
INSERT INTO `notify` VALUES ('2', '1', '2', '2', '1', '1');
INSERT INTO `notify` VALUES ('3', '1', '3', '3', '1', '0');
INSERT INTO `notify` VALUES ('4', '1', '4', '4', '1', '0');

-- ----------------------------
-- Table structure for `notify_channel`
-- ----------------------------
DROP TABLE IF EXISTS `notify_channel`;
CREATE TABLE `notify_channel` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of notify_channel
-- ----------------------------
INSERT INTO `notify_channel` VALUES ('1', 'email', '电子邮件', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `notify_channel` VALUES ('2', 'wechat', '微信', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `notify_channel` VALUES ('3', 'sms', '短信', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `notify_channel` VALUES ('4', 'siteMsg', '站内信', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- ----------------------------
-- Table structure for `notify_log`
-- ----------------------------
DROP TABLE IF EXISTS `notify_log`;
CREATE TABLE `notify_log` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` int(4) NOT NULL COMMENT '通知:1;待办:2',
  `level` int(11) NOT NULL COMMENT '普通:1;重要:2;紧急:3',
  `channel_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `event_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `job` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'queue:job,not queue:null',
  `status` int(11) NOT NULL COMMENT '发送成功:1;发送失败:0',
  `payload` longtext COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of notify_log
-- ----------------------------
INSERT INTO `notify_log` VALUES ('21', '0', '1', '1', '1', '', '404', 'a:5:{s:5:\"title\";s:10:\"user login\";s:7:\"content\";s:15:\"you are welcome\";s:3:\"url\";s:18:\"http://www.xzx.com\";s:4:\"date\";s:10:\"2015-10-31\";s:7:\"user_id\";i:1;}', '2015-10-31 13:04:36', '2015-10-31 13:04:36');
INSERT INTO `notify_log` VALUES ('22', '0', '1', '1', '1', 'vt261Z87cqptcMKWNb7Rm5X1SlN7mLyW', '200', 'a:5:{s:5:\"title\";s:10:\"user login\";s:7:\"content\";s:15:\"you are welcome\";s:3:\"url\";s:18:\"http://www.xzx.com\";s:4:\"date\";s:10:\"2015-10-31\";s:7:\"user_id\";i:1;}', '2015-10-31 13:05:55', '2015-10-31 13:05:55');
INSERT INTO `notify_log` VALUES ('23', '0', '1', '1', '1', 'xENHS4OTAQGNErtyPjbDH3jMxLfpxpYH', '200', 'a:4:{s:7:\"subject\";s:10:\"user login\";s:7:\"content\";s:15:\"you are welcome\";s:3:\"url\";s:18:\"http://www.xzx.com\";s:7:\"user_id\";i:1;}', '2015-10-31 13:13:22', '2015-10-31 13:13:22');
INSERT INTO `notify_log` VALUES ('24', '0', '1', '1', '1', 'xFNoLmhEBVGKjPFWwze33MCi7XJDka3c', '200', 'a:4:{s:7:\"subject\";s:10:\"user login\";s:7:\"content\";s:15:\"you are welcome\";s:3:\"url\";s:18:\"http://www.xzx.com\";s:7:\"user_id\";i:1;}', '2015-10-31 13:15:09', '2015-10-31 13:15:09');
INSERT INTO `notify_log` VALUES ('25', '0', '1', '1', '1', 'ZvAItwlF0tB3KYp5qCmnnKAcmzseucRp', '200', 'a:4:{s:7:\"subject\";s:10:\"user login\";s:7:\"content\";s:15:\"you are welcome\";s:3:\"url\";s:18:\"http://www.xzx.com\";s:7:\"user_id\";i:1;}', '2015-10-31 13:16:11', '2015-10-31 13:16:11');
INSERT INTO `notify_log` VALUES ('26', '0', '1', '1', '1', 'xVgK9aCAa1Tl74J766ZKEKBUh3PeOJ6V', '200', 'a:4:{s:7:\"subject\";s:10:\"user login\";s:7:\"content\";s:15:\"you are welcome\";s:3:\"url\";s:18:\"http://www.xzx.com\";s:7:\"user_id\";i:1;}', '2015-10-31 13:17:41', '2015-10-31 13:17:41');
INSERT INTO `notify_log` VALUES ('27', '0', '1', '1', '1', 'l6nzYM7wCPqODZWPFAcLX2cw7r8EXBnA', '200', 'a:4:{s:7:\"subject\";s:10:\"user login\";s:7:\"content\";s:15:\"you are welcome\";s:3:\"url\";s:18:\"http://www.xzx.com\";s:7:\"user_id\";i:1;}', '2015-10-31 13:18:48', '2015-10-31 13:18:48');
INSERT INTO `notify_log` VALUES ('28', '0', '1', '1', '1', 'sgqxXMAadHfDg31MTIKo7ER6GmrteFT2', '200', 'a:4:{s:7:\"subject\";s:10:\"user login\";s:7:\"content\";s:15:\"you are welcome\";s:3:\"url\";s:18:\"http://www.xzx.com\";s:7:\"user_id\";i:1;}', '2015-10-31 13:19:12', '2015-10-31 13:19:12');
INSERT INTO `notify_log` VALUES ('29', '0', '1', '1', '1', '1I2AL5Rwbrlv1OIk04TJWyxJdgVNFiDB', '200', 'a:4:{s:7:\"subject\";s:10:\"user login\";s:7:\"content\";s:15:\"you are welcome\";s:3:\"url\";s:18:\"http://www.xzx.com\";s:7:\"user_id\";i:1;}', '2015-10-31 13:19:41', '2015-10-31 13:19:41');
INSERT INTO `notify_log` VALUES ('30', '0', '1', '1', '1', 'd8x0vIRTlAYMvV2h64DG5KPvOq88b7Ek', '200', 'a:4:{s:7:\"subject\";s:10:\"user login\";s:7:\"content\";s:15:\"you are welcome\";s:3:\"url\";s:18:\"http://www.xzx.com\";s:7:\"user_id\";i:1;}', '2015-10-31 13:20:30', '2015-10-31 13:20:30');
INSERT INTO `notify_log` VALUES ('31', '0', '1', '1', '1', 'UR9kucqxqThfwfIeo3DO2ZlivBpqDKJR', '200', 'a:4:{s:7:\"subject\";s:10:\"user login\";s:7:\"content\";s:15:\"you are welcome\";s:3:\"url\";s:18:\"http://www.xzx.com\";s:7:\"user_id\";i:1;}', '2015-10-31 13:24:38', '2015-10-31 13:24:38');
INSERT INTO `notify_log` VALUES ('32', '0', '1', '1', '1', '27gVe7JaqilMp3z9Z14hEQ4SdNuzSB3T', '200', 'a:4:{s:7:\"subject\";s:10:\"user login\";s:7:\"content\";s:15:\"you are welcome\";s:3:\"url\";s:18:\"http://www.xzx.com\";s:7:\"user_id\";i:1;}', '2015-10-31 13:42:38', '2015-10-31 13:42:38');
INSERT INTO `notify_log` VALUES ('33', '0', '1', '1', '1', 'Le16SNztCTK4a7QM5omv2exOHWygNQc2', '200', 'a:4:{s:7:\"subject\";s:10:\"user login\";s:7:\"content\";s:15:\"you are welcome\";s:3:\"url\";s:18:\"http://www.xzx.com\";s:7:\"user_id\";i:1;}', '2015-10-31 13:43:14', '2015-10-31 13:43:14');
INSERT INTO `notify_log` VALUES ('34', '0', '1', '1', '1', 'bXgnatgtLlncNt50sYagBsn1VDQXEl9g', '200', 'a:4:{s:7:\"subject\";s:10:\"user login\";s:7:\"content\";s:15:\"you are welcome\";s:3:\"url\";s:18:\"http://www.xzx.com\";s:7:\"user_id\";i:1;}', '2015-10-31 13:44:57', '2015-10-31 13:44:57');
INSERT INTO `notify_log` VALUES ('35', '0', '1', '1', '1', 'kZD9KDRgU5SY5Z8paJL0umdOXgmtOKNC', '200', 'a:4:{s:7:\"subject\";s:10:\"user login\";s:7:\"content\";s:15:\"you are welcome\";s:3:\"url\";s:18:\"http://www.xzx.com\";s:7:\"user_id\";i:1;}', '2015-10-31 13:48:25', '2015-10-31 13:48:25');
INSERT INTO `notify_log` VALUES ('36', '0', '1', '1', '1', '8bjDREyixIzDE0fV7EIqRTW2I4NV7ZPg', '200', 'a:4:{s:7:\"subject\";s:10:\"user login\";s:7:\"content\";s:15:\"you are welcome\";s:3:\"url\";s:18:\"http://www.xzx.com\";s:7:\"user_id\";i:1;}', '2015-10-31 13:50:54', '2015-10-31 13:50:54');
INSERT INTO `notify_log` VALUES ('37', '0', '1', '1', '1', 'JkSl0lv0nCj48eDFrC3XDvRnj2WJFQOc', '200', 'a:4:{s:7:\"subject\";s:10:\"user login\";s:7:\"content\";s:15:\"you are welcome\";s:3:\"url\";s:18:\"http://www.xzx.com\";s:7:\"user_id\";i:1;}', '2015-10-31 14:00:42', '2015-10-31 14:00:42');
INSERT INTO `notify_log` VALUES ('38', '0', '1', '1', '1', 'asElgGhDK7aRfRxoQ0BrKXKHJVHD9C63', '200', 'a:4:{s:7:\"subject\";s:10:\"user login\";s:7:\"content\";s:15:\"you are welcome\";s:3:\"url\";s:18:\"http://www.xzx.com\";s:7:\"user_id\";i:1;}', '2015-10-31 14:01:54', '2015-10-31 14:01:54');
INSERT INTO `notify_log` VALUES ('39', '0', '1', '1', '1', 'wxMsfpGvIgE3gte6sxZ417d9bP5hWbYf', '200', 'a:4:{s:7:\"subject\";s:10:\"user login\";s:7:\"content\";s:15:\"you are welcome\";s:3:\"url\";s:18:\"http://www.xzx.com\";s:7:\"user_id\";i:1;}', '2015-10-31 14:03:13', '2015-10-31 14:03:13');

-- ----------------------------
-- Table structure for `notify_rule`
-- ----------------------------
DROP TABLE IF EXISTS `notify_rule`;
CREATE TABLE `notify_rule` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `display_name` varchar(255) CHARACTER SET utf8 DEFAULT '',
  `description` text CHARACTER SET utf8,
  `status` int(4) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of notify_rule
-- ----------------------------
INSERT INTO `notify_rule` VALUES ('1', 'one', '第一规则', '不发短信', '1');
INSERT INTO `notify_rule` VALUES ('2', 'two', '第二规则', '不发邮件', '1');
INSERT INTO `notify_rule` VALUES ('3', 'three', '第三规则', '不发微信', '1');
INSERT INTO `notify_rule` VALUES ('4', 'four', '第四规则', '都不发', '1');

-- ----------------------------
-- Table structure for `notify_template`
-- ----------------------------
DROP TABLE IF EXISTS `notify_template`;
CREATE TABLE `notify_template` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `event_id` int(10) unsigned NOT NULL,
  `channel_id` int(10) unsigned NOT NULL,
  `path` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of notify_template
-- ----------------------------
INSERT INTO `notify_template` VALUES ('1', '用户登录-电子邮件', '1', '1', 'email.userLogin', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- ----------------------------
-- Table structure for `password_resets`
-- ----------------------------
DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  KEY `password_resets_email_index` (`email`),
  KEY `password_resets_token_index` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of password_resets
-- ----------------------------

-- ----------------------------
-- Table structure for `user_notify_setting`
-- ----------------------------
DROP TABLE IF EXISTS `user_notify_setting`;
CREATE TABLE `user_notify_setting` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) DEFAULT NULL,
  `rule_id` int(10) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of user_notify_setting
-- ----------------------------
INSERT INTO `user_notify_setting` VALUES ('1', '1', '3', '1');
INSERT INTO `user_notify_setting` VALUES ('2', '1', '2', '1');
INSERT INTO `user_notify_setting` VALUES ('3', '2', '3', '1');
INSERT INTO `user_notify_setting` VALUES ('4', '2', '2', '1');

-- ----------------------------
-- Table structure for `users`
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `wechat` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('1', 'nosun', 'nosun2008@126.com', 'fjlakdjauo2', '1800000000', '4a8046c88c35169d1a13d9a2655f9606', null, '2015-10-20 14:37:10', '2015-10-20 14:37:10');
INSERT INTO `users` VALUES ('2', 'nosun1', '18600364250@126.com', 'fjlakdjauo2', '1800000000', '4a8046c88c35169d1a13d9a2655f9606', null, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
