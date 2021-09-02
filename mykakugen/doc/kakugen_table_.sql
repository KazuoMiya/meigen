-- ---
-- Globals
-- ---

-- SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
-- SET FOREIGN_KEY_CHECKS=0;

-- ---
-- Table 'user'
--
-- ---

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` INTEGER NOT NULL AUTO_INCREMENT,
  `user_name` VARCHAR(30) NOT NULL,
  `user_password` VARCHAR(30) NOT NULL,
  `user_email` VARCHAR(30) NOT NULL,
  `delivery_hour` INTEGER NOT NULL,
  `created_at` TIMESTAMP NOT NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`)
);

-- ---
-- Table 'item'
--
-- ---

DROP TABLE IF EXISTS `item`;

CREATE TABLE `item` (
  `id` INTEGER NOT NULL AUTO_INCREMENT,
  `user_id` INTEGER NOT NULL COMMENT 'user_tableのid',
  `item_text` VARCHAR(200) NOT NULL,
  `created_at` TIMESTAMP NOT NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY ()
);

-- ---
-- Foreign Keys
-- ---


-- ---
-- Table Properties
-- ---

-- ALTER TABLE `user` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `item` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ---
-- Test Data
-- ---

-- INSERT INTO `user` (`id`,`user_name`,`user_password`,`user_email`,`delivery_hour`,`created_at`,`updated_at`) VALUES
-- ('','','','','','','');
-- INSERT INTO `item` (`id`,`user_id`,`item_text`,`created_at`,`updated_at`) VALUES
-- ('','','','','');
