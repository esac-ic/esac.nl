/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `agenda_item_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `agenda_item_categories` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `agenda_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `agenda_items` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `createdBy` int unsigned NOT NULL,
  `category` int unsigned NOT NULL,
  `startDate` datetime NOT NULL,
  `endDate` datetime NOT NULL,
  `subscription_endDate` datetime DEFAULT NULL,
  `image_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `application_form_id` int unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `climbing_activity` tinyint(1) NOT NULL DEFAULT '1',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `text` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `shortDescription` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `agenda_items_createdby_foreign` (`createdBy`),
  KEY `agenda_items_category_foreign` (`category`),
  KEY `agenda_items_application_form_id_foreign` (`application_form_id`),
  CONSTRAINT `agenda_items_application_form_id_foreign` FOREIGN KEY (`application_form_id`) REFERENCES `application_forms` (`id`),
  CONSTRAINT `agenda_items_category_foreign` FOREIGN KEY (`category`) REFERENCES `agenda_item_categories` (`id`),
  CONSTRAINT `agenda_items_createdby_foreign` FOREIGN KEY (`createdBy`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `application_form_row_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `application_form_row_options` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `value` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `application_form_row_id` int unsigned NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `application_form_row_options_application_form_row_id_foreign` (`application_form_row_id`),
  CONSTRAINT `application_form_row_options_application_form_row_id_foreign` FOREIGN KEY (`application_form_row_id`) REFERENCES `application_form_rows` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `application_form_rows`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `application_form_rows` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `application_form_id` int unsigned NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `required` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `application_form_rows_application_form_id_foreign` (`application_form_id`),
  CONSTRAINT `application_form_rows_application_form_id_foreign` FOREIGN KEY (`application_form_id`) REFERENCES `application_forms` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `application_forms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `application_forms` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `application_response_rows`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `application_response_rows` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `application_response_id` int unsigned NOT NULL,
  `application_form_row_id` int unsigned NOT NULL,
  `value` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `application_response_rows_application_form_row_id_foreign` (`application_form_row_id`),
  KEY `application_response_rows_application_response_id_foreign` (`application_response_id`),
  CONSTRAINT `application_response_rows_application_form_row_id_foreign` FOREIGN KEY (`application_form_row_id`) REFERENCES `application_form_rows` (`id`),
  CONSTRAINT `application_response_rows_application_response_id_foreign` FOREIGN KEY (`application_response_id`) REFERENCES `application_responses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `application_responses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `application_responses` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `agenda_id` int unsigned NOT NULL,
  `inschrijf_form_id` int unsigned NOT NULL,
  `user_id` int unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `application_responses_inschrijf_form_id_foreign` (`inschrijf_form_id`),
  KEY `application_responses_user_id_foreign` (`user_id`),
  KEY `application_responses_agenda_id_foreign` (`agenda_id`),
  CONSTRAINT `application_responses_agenda_id_foreign` FOREIGN KEY (`agenda_id`) REFERENCES `agenda_items` (`id`) ON DELETE CASCADE,
  CONSTRAINT `application_responses_inschrijf_form_id_foreign` FOREIGN KEY (`inschrijf_form_id`) REFERENCES `application_forms` (`id`),
  CONSTRAINT `application_responses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `books`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `books` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `year` int DEFAULT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `certificate_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `certificate_user` (
  `certificate_id` int unsigned NOT NULL,
  `user_id` int unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`certificate_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `certificates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `certificates` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `duration` int DEFAULT NULL,
  `abbreviation` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `menu_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `menu_items` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int unsigned DEFAULT NULL,
  `deletable` tinyint(1) NOT NULL,
  `editable` tinyint(1) NOT NULL,
  `after` int unsigned DEFAULT NULL,
  `login` tinyint(1) NOT NULL,
  `menuItem` tinyint(1) NOT NULL,
  `urlName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `news_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `news_items` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `image_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `author` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `thumbnail_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `text` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`),
  KEY `password_resets_token_index` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `rol_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rol_user` (
  `rol_id` int unsigned NOT NULL,
  `user_id` int unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `rols`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rols` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `telescope_entries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `telescope_entries` (
  `sequence` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch_id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `family_hash` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `should_display_on_index` tinyint(1) NOT NULL DEFAULT '1',
  `type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`sequence`),
  UNIQUE KEY `telescope_entries_uuid_unique` (`uuid`),
  KEY `telescope_entries_batch_id_index` (`batch_id`),
  KEY `telescope_entries_family_hash_index` (`family_hash`),
  KEY `telescope_entries_created_at_index` (`created_at`),
  KEY `telescope_entries_type_should_display_on_index_index` (`type`,`should_display_on_index`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `telescope_entries_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `telescope_entries_tags` (
  `entry_uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tag` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  KEY `telescope_entries_tags_entry_uuid_tag_index` (`entry_uuid`,`tag`),
  KEY `telescope_entries_tags_tag_index` (`tag`),
  CONSTRAINT `telescope_entries_tags_entry_uuid_foreign` FOREIGN KEY (`entry_uuid`) REFERENCES `telescope_entries` (`uuid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `telescope_monitoring`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `telescope_monitoring` (
  `tag` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `texts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `texts` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `EN_text` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `NL_text` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `firstname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `preposition` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lastname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `street` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `houseNumber` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zipcode` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phonenumber` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phonenumber_alt` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emergencyNumber` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emergencyHouseNumber` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emergencystreet` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emergencycity` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emergencyzipcode` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emergencycountry` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `birthDay` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kind_of_member` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `IBAN` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `BIC` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `incasso` tinyint(1) NOT NULL,
  `remark` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `lid_af` date DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `pending_user` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `zekerings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `zekerings` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `createdBy` int unsigned NOT NULL,
  `score` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `parent_id` int DEFAULT NULL,
  `has_parent` tinyint(1) NOT NULL DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `zekerings_createdby_foreign` (`createdBy`),
  KEY `zekerings_parent_id_index` (`parent_id`),
  CONSTRAINT `zekerings_createdby_foreign` FOREIGN KEY (`createdBy`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (1,'2011_01_11_194012_create_text_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (2,'2014_10_12_000000_create_users_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (3,'2014_10_12_100000_create_password_resets_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (4,'2017_01_03_154708_create_rol_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (5,'2017_01_04_144101_create_table_rol_user',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (6,'2017_01_15_185143_create_menuItem_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (7,'2017_03_18_195922_create_certificaat_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (8,'2017_04_01_093525_create_certificate_user_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (9,'2017_04_05_153935_create_agenda_item_categorie_tabel',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (10,'2017_04_10_154555_create_application_form_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (11,'2017_04_14_141452_create_application_form_row_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (12,'2017_05_02_191748_create_news_item_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (13,'2017_05_06_193146_create_agenda_item_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (14,'2017_05_25_103056_create_zekeringen_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (15,'2017_06_18_194914_create_response_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (16,'2017_06_24_093540_create_response_rows',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (17,'2017_08_08_194645_modify_zekeringen_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (18,'2017_08_22_212418_create_user_transform_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (19,'2017_09_16_133512_add_softdeletes',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (20,'2018_03_17_090436_add_content_to_menu_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (21,'2018_03_18_134533_move_page_content_from_file_to_db',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (22,'2018_05_19_091613_delete_photo_gallery_menuitem',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (23,'2018_05_19_100743_change_homepage_content',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (24,'2018_06_01_142105_add_pending_user_to_users',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (25,'2018_06_02_194011_remove_unnecessary_user_info',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (26,'2018_06_22_192226_make_user_fields_nullable',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (27,'2018_06_23_191310_create_books_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (28,'2018_06_30_183434_create_photo_album_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (29,'2018_06_30_183510_create_photo_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (30,'2018_08_08_100000_create_telescope_entries_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (31,'2018_11_25_131633_add_custom_author_field_to_news_items',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (32,'2018_11_25_131903_migrate_created_by_field_author_field',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (33,'2018_11_25_132053_remove_created_by_field_from_news_items_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (34,'2019_03_21_111809_add_thumbnail_to_news',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (35,'2019_04_20_080732_create_settings_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (36,'2019_04_20_083716_add_blocked_email_domain_setting',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (37,'2019_05_07_141337_add_killswitch_setting',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (38,'2019_05_14_183925_add_climbing_activity_to_agenda_item_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (39,'2019_05_15_195529_user_registration_info_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (40,'2019_05_15_200305_add_intro_info_setting',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (41,'2019_05_17_200654_remove_front_end_replacements_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (42,'2019_11_24_132959_create_intro_packages_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (43,'2019_11_24_151650_add_code_to_library',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (44,'2019_11_24_200556_add_softdelete',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (45,'2020_01_07_185322_create_application_form_row_options_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (46,'2020_01_20_155811_update_charset',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (47,'2020_03_04_135242_add_footer_phone_nr_setting',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (48,'2021_01_31_121948_add_cascade_on_delete_to_agenda_id_in_application_responses',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (49,'2021_01_31_122255_add_cascade_on_delete_to_application_response_id_in_application_response_rows',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (50,'2023_10_03_114910_add_index_to_parent_id_in_zekerings_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (51,'2023_10_03_133039_fix_self_referencing_zekering_records',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (52,'2023_10_08_095128_remove_gender_from_users_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (53,'2023_10_10_171243_remove_photo_albums',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (54,'2023_10_09_144100_set_english_text_default_agenda',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (55,'2023_10_09_151123_set_english_text_default_agenda_categories',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (56,'2023_10_09_223615_set_english_text_default_application_form',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (57,'2023_10_09_223615_set_english_text_default_application_form_row',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (58,'2023_10_09_223615_set_english_text_default_application_form_row_options',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (59,'2023_10_09_223615_set_english_text_default_certificates',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (60,'2023_10_09_223615_set_english_text_default_news',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (61,'2023_10_09_223615_set_english_text_default_page',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (62,'2023_10_09_223615_set_english_text_default_role',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (63,'2023_10_11_143010_remove_certificate_start_date',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (64,'2023_10_18_083938_create_sessions_table',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (65,'2023_10_18_164902_remove_intro_packages',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (66,'2023_10_18_172952_application_form_response_row_longtext',2);
