<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_auth_foundation extends CI_Migration {

	public function up()
	{
		$this->db->query("CREATE TABLE IF NOT EXISTS `users` (
			`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
			`username` VARCHAR(100) NOT NULL,
			`email` VARCHAR(191) DEFAULT NULL,
			`full_name` VARCHAR(191) NOT NULL,
			`password_hash` VARCHAR(255) NOT NULL,
			`is_active` TINYINT(1) NOT NULL DEFAULT 1,
			`last_login_at` DATETIME DEFAULT NULL,
			`created_at` DATETIME NOT NULL,
			`updated_at` DATETIME NOT NULL,
			PRIMARY KEY (`id`),
			UNIQUE KEY `users_username_unique` (`username`),
			UNIQUE KEY `users_email_unique` (`email`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

		$this->db->query("CREATE TABLE IF NOT EXISTS `roles` (
			`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
			`role_code` VARCHAR(100) NOT NULL,
			`role_name` VARCHAR(191) NOT NULL,
			`description` TEXT DEFAULT NULL,
			`is_active` TINYINT(1) NOT NULL DEFAULT 1,
			`created_at` DATETIME NOT NULL,
			`updated_at` DATETIME NOT NULL,
			PRIMARY KEY (`id`),
			UNIQUE KEY `roles_role_code_unique` (`role_code`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

		$this->db->query("CREATE TABLE IF NOT EXISTS `permissions` (
			`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
			`permission_code` VARCHAR(150) NOT NULL,
			`permission_name` VARCHAR(191) NOT NULL,
			`module_name` VARCHAR(100) DEFAULT NULL,
			`description` TEXT DEFAULT NULL,
			`is_active` TINYINT(1) NOT NULL DEFAULT 1,
			`created_at` DATETIME NOT NULL,
			`updated_at` DATETIME NOT NULL,
			PRIMARY KEY (`id`),
			UNIQUE KEY `permissions_permission_code_unique` (`permission_code`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

		$this->db->query("CREATE TABLE IF NOT EXISTS `user_roles` (
			`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
			`user_id` INT UNSIGNED NOT NULL,
			`role_id` INT UNSIGNED NOT NULL,
			`created_at` DATETIME NOT NULL,
			PRIMARY KEY (`id`),
			UNIQUE KEY `user_roles_user_role_unique` (`user_id`, `role_id`),
			CONSTRAINT `user_roles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
			CONSTRAINT `user_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

		$this->db->query("CREATE TABLE IF NOT EXISTS `role_permissions` (
			`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
			`role_id` INT UNSIGNED NOT NULL,
			`permission_id` INT UNSIGNED NOT NULL,
			`created_at` DATETIME NOT NULL,
			PRIMARY KEY (`id`),
			UNIQUE KEY `role_permissions_role_permission_unique` (`role_id`, `permission_id`),
			CONSTRAINT `role_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
			CONSTRAINT `role_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
	}

	public function down()
	{
		$this->db->query('DROP TABLE IF EXISTS `role_permissions`');
		$this->db->query('DROP TABLE IF EXISTS `user_roles`');
		$this->db->query('DROP TABLE IF EXISTS `permissions`');
		$this->db->query('DROP TABLE IF EXISTS `roles`');
		$this->db->query('DROP TABLE IF EXISTS `users`');
	}
}
