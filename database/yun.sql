/*
Navicat MySQL Data Transfer

Source Server         : homestead
Source Server Version : 50619
Source Host           : localhost:33060
Source Database       : yun

Target Server Type    : MYSQL
Target Server Version : 50619
File Encoding         : 65001

Date: 2015-10-27 00:05:26
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
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of notify_log
-- ----------------------------
INSERT INTO `notify_log` VALUES ('1', '0', '1', '1', '1', '', '0', 's:9:\"userLogin\";', '2015-10-22 16:24:39', '2015-10-22 16:24:39');
INSERT INTO `notify_log` VALUES ('2', '0', '1', '2', '1', '', '0', 's:9:\"userLogin\";', '2015-10-22 16:24:39', '2015-10-22 16:24:39');
INSERT INTO `notify_log` VALUES ('3', '0', '1', '1', '1', '', '0', 's:9:\"userLogin\";', '2015-10-22 16:25:00', '2015-10-22 16:25:00');
INSERT INTO `notify_log` VALUES ('4', '0', '1', '2', '1', '', '0', 's:9:\"userLogin\";', '2015-10-22 16:25:00', '2015-10-22 16:25:00');
INSERT INTO `notify_log` VALUES ('5', '0', '1', '1', '1', '', '0', 's:9:\"userLogin\";', '2015-10-22 16:26:42', '2015-10-22 16:26:42');
INSERT INTO `notify_log` VALUES ('6', '0', '1', '1', '1', '', '0', 's:9:\"userLogin\";', '2015-10-22 16:28:44', '2015-10-22 16:28:44');
INSERT INTO `notify_log` VALUES ('7', '0', '1', '2', '1', '', '0', 's:9:\"userLogin\";', '2015-10-22 16:28:44', '2015-10-22 16:28:44');
INSERT INTO `notify_log` VALUES ('8', '0', '1', '1', '1', '', '0', 's:9:\"userLogin\";', '2015-10-22 16:28:59', '2015-10-22 16:28:59');
INSERT INTO `notify_log` VALUES ('9', '0', '1', '2', '1', '', '0', 's:9:\"userLogin\";', '2015-10-22 16:28:59', '2015-10-22 16:28:59');
INSERT INTO `notify_log` VALUES ('10', '0', '1', '1', '1', '', '0', 's:9:\"userLogin\";', '2015-10-22 16:37:11', '2015-10-22 16:37:11');
INSERT INTO `notify_log` VALUES ('11', '0', '1', '2', '1', '', '0', 's:9:\"userLogin\";', '2015-10-22 16:37:11', '2015-10-22 16:37:11');
INSERT INTO `notify_log` VALUES ('12', '0', '1', '1', '1', '', '0', 's:9:\"userLogin\";', '2015-10-22 16:38:30', '2015-10-22 16:38:30');
INSERT INTO `notify_log` VALUES ('13', '0', '1', '2', '1', '', '0', 's:9:\"userLogin\";', '2015-10-22 16:38:30', '2015-10-22 16:38:30');
INSERT INTO `notify_log` VALUES ('14', '0', '1', '1', '1', '', '0', 's:9:\"userLogin\";', '2015-10-25 14:02:43', '2015-10-25 14:02:43');
INSERT INTO `notify_log` VALUES ('15', '0', '1', '2', '1', '', '0', 's:9:\"userLogin\";', '2015-10-25 14:02:43', '2015-10-25 14:02:43');

-- ----------------------------
-- Table structure for `notify_rule`
-- ----------------------------
DROP TABLE IF EXISTS `notify_rule`;
CREATE TABLE `notify_rule` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `display_name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `description` text,
  `status` int(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of notify_rule
-- ----------------------------

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of notify_template
-- ----------------------------

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
  `id` int(10) DEFAULT NULL,
  `user_id` int(10) DEFAULT NULL,
  `rule_id` int(10) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of user_notify_setting
-- ----------------------------

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('1', 'nosun', 'nosun2008@126.com', 'fjlakdjauo2', '1800000000', '4a8046c88c35169d1a13d9a2655f9606', null, '2015-10-20 14:37:10', '2015-10-20 14:37:10');
