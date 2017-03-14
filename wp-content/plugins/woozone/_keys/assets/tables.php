<?php die(); ?>

CREATE TABLE `wp_amz_keys` (
	`id` MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
	`access_key` VARCHAR(100) NOT NULL,
	`secret_key` VARCHAR(100) NOT NULL,
	`keys_code` VARCHAR(32) NOT NULL,
	`publish` ENUM('Y','N') NOT NULL DEFAULT 'Y',
	`locked` CHAR(1) NOT NULL DEFAULT 'N',
	`lock_time` TIMESTAMP NULL DEFAULT NULL,
	PRIMARY KEY (`id`),
	UNIQUE INDEX `keys_code` (`keys_code`),
	INDEX `publish_locked_lock_time` (`publish`, `locked`, `lock_time`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;

CREATE TABLE `wp_amz_keys_req` (
	`id` BIGINT(15) UNSIGNED NOT NULL AUTO_INCREMENT,
	`plugin_alias` VARCHAR(20) NOT NULL DEFAULT 'woozone',
	`id_amz_keys` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
	`request_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`request_params` TEXT NOT NULL,
	`country` VARCHAR(50) NOT NULL,
	`from_file` VARCHAR(50) NOT NULL,
	`from_func` VARCHAR(50) NOT NULL,
	`client_ip` VARCHAR(30) NOT NULL,
	`client_website` VARCHAR(255) NOT NULL,
	`status` VARCHAR(50) NOT NULL,
	`status_msg` TEXT NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `id_amz_keys` (`id_amz_keys`),
	INDEX `country` (`country`),
	INDEX `from_file` (`from_file`),
	INDEX `from_func` (`from_func`),
	INDEX `status` (`status`),
	INDEX `client_ip` (`client_ip`),
	INDEX `client_website` (`client_website`),
	INDEX `plugin_alias` (`plugin_alias`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;