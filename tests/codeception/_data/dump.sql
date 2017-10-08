-- MySQL dump 10.13  Distrib 5.5.52, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: webio_test
-- ------------------------------------------------------
-- Server version	5.5.52-0ubuntu0.14.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `wb_accounts`
--

DROP TABLE IF EXISTS `wb_accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wb_accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status_id` int(11) DEFAULT NULL,
  `photo_id` int(11) DEFAULT NULL,
  `first_name` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `state` smallint(6) NOT NULL,
  `expiration_date` datetime DEFAULT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `token_time` datetime DEFAULT NULL,
  `registered_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_6B8DEDB3E7927C74` (`email`),
  UNIQUE KEY `UNIQ_6B8DEDB335C246D5` (`password`),
  UNIQUE KEY `UNIQ_6B8DEDB35F37A13B` (`token`),
  KEY `IDX_6B8DEDB36BF700BD` (`status_id`),
  KEY `IDX_6B8DEDB37E9E4C8C` (`photo_id`),
  CONSTRAINT `FK_6B8DEDB37E9E4C8C` FOREIGN KEY (`photo_id`) REFERENCES `wb_medias` (`id`) ON DELETE SET NULL,
  CONSTRAINT `FK_6B8DEDB36BF700BD` FOREIGN KEY (`status_id`) REFERENCES `wb_status` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=126 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wb_accounts`
--

LOCK TABLES `wb_accounts` WRITE;
/*!40000 ALTER TABLE `wb_accounts` DISABLE KEYS */;
INSERT INTO `wb_accounts` VALUES (1,1,1,'Sumugan','Sinnarasa','0147060814','sumugan.sinnarasa@desico.fr','$2y$10$Ne92Xv7GuGzqbr2J9BrK9eb7Tdp/I/dht4oMCNy0qazYvntYwX04q',1,NULL,NULL,NULL,'2017-01-05 11:19:53','2017-01-05 11:19:53'),(2,1,1,'Jean Francois','Roussel','0123456789','jean-francois.roussel@desico.fr','$2y$10$nNBS.mAW6kiTE.abBMyZYewYI6nn3TYpz1U1oOTu9YPhyE7uBU0Na',1,NULL,NULL,NULL,'2017-01-05 11:19:53','2017-01-05 11:19:53'),(3,3,1,'Renaud','Alquier','0123456789','renaud.alquier@desico.fr','$2y$10$.ywYA8.qrJlWgbYDO.vqcufN/0nlLgKuNPJslLmKBdTxUwWgHBXL.',1,NULL,NULL,NULL,'2017-01-05 11:19:53','2017-01-05 11:19:53'),(4,4,1,'David','Juminez','0123456780','david.jimenez@desico.fr','$2y$10$uc8dcjwXV6NFIzClXHg8VeTYzelXbA40nlygX1XkBVfTaWHZ7fqma',1,NULL,NULL,NULL,'2017-01-05 11:19:53','2017-01-05 11:19:53'),(5,2,1,'Philippe','Duquaire','0123456780','philippe.duquaire@desico.fr','$2y$10$pEmo/l5WgMjO8oQafNvGVejSoG0TqTcWGljXhf7eJxH7sLWsBLlAe',1,NULL,NULL,NULL,'2017-01-05 11:19:53','2017-01-05 11:19:53'),(6,3,1,'Jean Baptiste','Duquaire','0123456780','jean-baptiste.duquaire@desico.fr','$2y$10$JXNTSWsPLGXvvsVd/.XuceFqSh7V6cRBdKICYAoHKGMuVqeqWFreG',1,NULL,NULL,NULL,'2017-01-05 11:19:53','2017-01-05 11:19:53'),(7,5,1,'Luffy','Mugiwara','0123426489','luffy@onepiece.com','$2y$10$DM2gz2SKAPbhDNI8q8v9h.TjXQuHj/xe.xVamn9V7GC2XWyj5DYFG',1,'2017-12-20 10:00:00',NULL,NULL,'2017-01-05 11:19:53','2017-01-05 11:19:53'),(8,5,1,'Zoro','Roronoa','0113459789','zoro@onepiece.com','$2y$10$BeEuNkd9gceAk9G5y3F0JulUc5uRHUYd5f.34FRaRGhbRv2tiMbYG',1,'2017-12-20 10:00:00',NULL,NULL,'2017-01-05 11:19:53','2017-01-05 11:19:53'),(9,5,1,'Sanji','Vinsmoke','0123456780','sanji@onepiece.com','$2y$10$uiaGDTnOysT.u3MAyab4MeBoIqo0blq7X9Oz8O45BDu4HD8DObT9u',1,'2017-12-20 10:00:00',NULL,NULL,'2017-01-05 11:19:53','2017-01-05 11:19:53'),(10,5,1,'Chopper','Tony Tony','0123456780','chopper@onepiece.com','$2y$10$dYfH4/0F580ishMzVKOJx..kCbn8/W/I5R9rirYCXTyKiiTNftOJ6',1,'2017-12-20 10:00:00',NULL,NULL,'2017-01-05 11:19:54','2017-01-05 11:19:54'),(11,5,1,'Robin','Nico','0123456780','robin@onepiece.com','$2y$10$TMW1TGGaRx7bBWngx5jWOeOlWAxXKbiJ9Esb8fRWaszkcLuRCIGBu',1,'2017-12-20 10:00:00',NULL,NULL,'2017-01-05 11:19:54','2017-01-05 11:19:54');
/*!40000 ALTER TABLE `wb_accounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wb_addresses`
--

DROP TABLE IF EXISTS `wb_addresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wb_addresses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `society_id` int(11) DEFAULT NULL,
  `address` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `postal_code` int(11) NOT NULL,
  `country` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_E2633D90E6389D24` (`society_id`),
  CONSTRAINT `FK_E2633D90E6389D24` FOREIGN KEY (`society_id`) REFERENCES `wb_societies` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wb_addresses`
--

LOCK TABLES `wb_addresses` WRITE;
/*!40000 ALTER TABLE `wb_addresses` DISABLE KEYS */;
INSERT INTO `wb_addresses` VALUES (1,1,'17 Rue Portefoin','Paris 3',75003,'FRANCE',48.8639241,2.3592936),(2,2,'11 Rue Ave Maria','Paris',75004,'FRANCE',48.8528981,2.3599729),(3,3,'44 Boulevard Richard Lenoir','Paris',75011,'FRANCE',48.8582294,2.3719139),(4,4,'45 Rue Alexandre Fourny','Champigny Sur Marne',94500,'FRANCE',48.8191166,2.5187347),(5,5,'201 Rue Saint-Martin','Paris',75003,'FRANCE',48.8633418,2.3523817);
/*!40000 ALTER TABLE `wb_addresses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wb_admin_custom_fields`
--

DROP TABLE IF EXISTS `wb_admin_custom_fields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wb_admin_custom_fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `custom_field_id` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `position` int(11) NOT NULL,
  `required` tinyint(1) NOT NULL,
  `data` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:json)',
  `content` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:json)',
  PRIMARY KEY (`id`),
  KEY `IDX_46184727727ACA70` (`parent_id`),
  KEY `IDX_46184727A1E5E0D4` (`custom_field_id`),
  CONSTRAINT `FK_46184727A1E5E0D4` FOREIGN KEY (`custom_field_id`) REFERENCES `wb_custom_fields` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_46184727727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `wb_admin_custom_fields` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wb_admin_custom_fields`
--

LOCK TABLES `wb_admin_custom_fields` WRITE;
/*!40000 ALTER TABLE `wb_admin_custom_fields` DISABLE KEYS */;
INSERT INTO `wb_admin_custom_fields` VALUES (1,NULL,1,'Image du loader','loading_media',NULL,'media',0,0,'{\"media_render_type\":\"object\"}','{\"value\":{\"id\":\"8\",\"path\":\"\\/sites\\/1\\/logo-intro.png\",\"getPublicPath\":\"\\/public\\/media\\/sites\\/1\\/logo-intro.png\",\"alt\":\"Loading logo aster\",\"title\":\"Loading logo theme aster\"}}'),(2,NULL,1,'Sous titre','header_subtitle',NULL,'string',1,0,'[]','{\"value\":\"Services of the utmost quality\"}'),(3,NULL,1,'Image du slider','header_background',NULL,'media',2,0,'{\"media_render_type\":\"object\"}','{\"value\":{\"id\":\"9\",\"path\":\"\\/sites\\/1\\/hero.jpg\",\"getPublicPath\":\"\\/public\\/media\\/sites\\/1\\/hero.jpg\",\"alt\":\"Header background image\",\"title\":\"Theme aster header image\"}}'),(4,NULL,1,'Horaires d\'ouverture','opening_hours',NULL,'wysiwyg',3,0,'[]','{\"value\":\"Lundi au vendredi de 9h \\u00e0 18h\"}'),(5,NULL,2,'Titre de bienvenue','welcome_title',NULL,'string',0,0,'[]','{\"page@1\":\"BIENVENUE SUR ASTER SOCIETY\"}'),(6,NULL,2,'Texte de bienvenue','welcome_text',NULL,'wysiwyg',1,0,'[]','{\"page@1\":\"Established in 1995, the salon always was a place, where people with sense for current trends found a stylist who understood to turn their vision into reality, and who was able to create a look that ephasized their individuality.\\u2028\\n\\nAenean lacinia bibendum nulla sed consectetur. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur blandit tempus porttitor. Lorem ipsum dolor sit amet, consectetur adipiscing elit.\\n\\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas faucibus mollis interdum. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Aenean lacinia bibendum nulla sed consectetur.\"}'),(7,NULL,2,'Carrousel','carrousel',NULL,'repeater',2,0,'{\"min_row\":\"3\",\"max_row\":\"3\"}','{\"type\":\"repeater\",\"rows@page@1\":[0,1,2]}'),(8,7,2,'Image','image',NULL,'media',0,0,'{\"media_render_type\":\"object\"}','{\"page@1\":[{\"id\":\"10\",\"path\":\"\\/sites\\/1\\/slide-1.jpg\",\"getPublicPath\":\"\\/public\\/media\\/sites\\/1\\/slide-1.jpg\",\"alt\":\"Theme aster home page slide 1\",\"title\":\"Theme aster home page slide 1\"},{\"id\":\"11\",\"path\":\"\\/sites\\/1\\/slide-2.jpg\",\"getPublicPath\":\"\\/public\\/media\\/sites\\/1\\/slide-2.jpg\",\"alt\":\"Theme aster home page slide 2\",\"title\":\"Theme aster home page slide 2\"},{\"id\":\"12\",\"path\":\"\\/sites\\/1\\/slide-3.jpg\",\"getPublicPath\":\"\\/public\\/media\\/sites\\/1\\/slide-3.jpg\",\"alt\":\"Theme aster home page slide 3\",\"title\":\"Theme aster home page slide 3\"}]}'),(9,NULL,2,'Titre du bloc 2','second_bloc_title',NULL,'string',0,0,'[]','{\"page@1\":\"HAVE A LOOK AROUND\"}'),(10,NULL,2,'Image du bloc 2','second_bloc_image',NULL,'media',0,0,'{\"media_render_type\":\"object\"}','{\"page@1\":{\"id\":\"13\",\"path\":\"\\/sites\\/1\\/panorama.jpg\",\"getPublicPath\":\"\\/public\\/media\\/sites\\/1\\/panorama.jpg\",\"alt\":\"Theme aster home page panorama image\",\"title\":\"Theme aster home page panorama image\"}}');
/*!40000 ALTER TABLE `wb_admin_custom_fields` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wb_contents`
--

DROP TABLE IF EXISTS `wb_contents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wb_contents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_id` int(11) DEFAULT NULL,
  `website_id` int(11) DEFAULT NULL,
  `module_id` int(11) DEFAULT NULL,
  `template_id` int(11) DEFAULT NULL,
  `section_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `block` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `data` longtext COLLATE utf8_unicode_ci COMMENT '(DC2Type:json)',
  PRIMARY KEY (`id`),
  KEY `IDX_15BF6268C4663E4` (`page_id`),
  KEY `IDX_15BF626818F45C82` (`website_id`),
  KEY `IDX_15BF6268AFC2B591` (`module_id`),
  KEY `IDX_15BF62685DA0FB8` (`template_id`),
  KEY `IDX_15BF6268D823E37A` (`section_id`),
  CONSTRAINT `FK_15BF6268D823E37A` FOREIGN KEY (`section_id`) REFERENCES `wb_sections` (`id`) ON DELETE SET NULL,
  CONSTRAINT `FK_15BF626818F45C82` FOREIGN KEY (`website_id`) REFERENCES `wb_websites` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_15BF62685DA0FB8` FOREIGN KEY (`template_id`) REFERENCES `wb_templates` (`id`) ON DELETE SET NULL,
  CONSTRAINT `FK_15BF6268AFC2B591` FOREIGN KEY (`module_id`) REFERENCES `wb_modules` (`id`) ON DELETE SET NULL,
  CONSTRAINT `FK_15BF6268C4663E4` FOREIGN KEY (`page_id`) REFERENCES `wb_pages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=246 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wb_contents`
--

LOCK TABLES `wb_contents` WRITE;
/*!40000 ALTER TABLE `wb_contents` DISABLE KEYS */;
INSERT INTO `wb_contents` VALUES (1,1,1,1,20,1,'Bienvenue','home_content','{\"class\":\"col-md-6\",\"db\":[{\"alias\":\"p\",\"type\":\"static\",\"column\":\"id\",\"value\":\"1\",\"route\":\"\",\"value_id\":\"1\"}]}'),(2,1,1,2,19,NULL,'Services','list_home_post','{\"class\":\"col-md-6\",\"route_name\":\"module:post.type:dynamic.action:read\",\"total_row\":3,\"db\":[{\"alias\":\"c\",\"type\":\"static\",\"column\":\"slug\",\"value\":[\"service\"],\"route\":\"\",\"value_id\":[\"1\"]}],\"link\":[{\"alias\":\"p\",\"type\":\"dynamic\",\"route\":\"slug\",\"column\":\"slug\",\"value\":\"\",\"value_id\":\"\"}]}'),(3,2,1,2,19,NULL,'Articles statique','list_post','{\"class\":\"col-md-12\",\"route_name\":\"module:post.type:dynamic.action:read\",\"db\":[],\"link\":[{\"alias\":\"p\",\"type\":\"static\",\"column\":\"slug\",\"route\":\"slug\",\"value\":\"\",\"value_id\":\"\"}]}'),(4,3,1,2,19,1,'Articles','list_post','{\"class\":\"col-md-12\",\"route_name\":\"module:post.type:dynamic.action:read\",\"db\":[{\"alias\":\"c\",\"type\":\"dynamic\",\"column\":\"slug\",\"route\":\"slug\",\"value\":[],\"value_id\":[]}],\"link\":[{\"alias\":\"p\",\"type\":\"dynamic\",\"route\":\"slug\",\"column\":\"slug\",\"value\":\"\",\"value_id\":\"\"}]}'),(5,4,1,1,20,1,'Article','single_post','{\"class\":\"col-md-12\",\"db\":[{\"alias\":\"p\",\"type\":\"dynamic\",\"column\":\"slug\",\"route\":\"slug\",\"value\":\"\",\"value_id\":\"\"}]}'),(6,5,2,1,15,1,'Bienvenue','home_content','{\"class\":\"col-md-6\",\"db\":[{\"alias\":\"p\",\"type\":\"static\",\"column\":\"id\",\"value\":\"5\",\"route\":\"\",\"value_id\":\"\"}]}'),(7,5,2,2,18,NULL,'Services','list_home_post','{\"class\":\"col-md-6\",\"route_name\":\"module:post.type:dynamic.action:read\",\"total_row\":3,\"db\":[{\"alias\":\"c\",\"type\":\"static\",\"column\":\"slug\",\"value\":[\"service\"],\"route\":\"\",\"value_id\":[]}],\"link\":[{\"alias\":\"p\",\"type\":\"dynamic\",\"route\":\"slug\",\"column\":\"slug\",\"value\":\"\",\"value_id\":\"\"}]}'),(8,6,2,2,18,NULL,'Articles statique','list_post','{\"class\":\"col-md-12\",\"route_name\":\"module:post.type:dynamic.action:read\",\"db\":[],\"link\":[{\"alias\":\"p\",\"type\":\"static\",\"route\":\"slug\",\"column\":\"slug\",\"value\":\"\",\"value_id\":\"\"}]}'),(9,7,2,2,18,1,'Articles','list_post','{\"class\":\"col-md-12\",\"db\":[{\"alias\":\"c\",\"type\":\"dynamic\",\"column\":\"slug\",\"route\":\"slug\",\"value\":[],\"value_id\":[]}],\"route_name\":\"module:post.type:dynamic.action:read\",\"link\":[{\"alias\":\"p\",\"type\":\"dynamic\",\"route\":\"slug\",\"column\":\"slug\",\"value\":\"\",\"value_id\":\"\"}]}'),(10,8,2,1,15,1,'Article','single_post','{\"class\":\"col-md-12\",\"db\":[{\"alias\":\"p\",\"type\":\"dynamic\",\"column\":\"slug\",\"route\":\"slug\",\"value\":[],\"value_id\":[]}]}'),(11,NULL,1,3,21,NULL,'Menu','navigation','{\"class\":\"\",\"navigation\":\"1\"}');
/*!40000 ALTER TABLE `wb_contents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wb_custom_field_rules`
--

DROP TABLE IF EXISTS `wb_custom_field_rules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wb_custom_field_rules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `callback` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `replace_table` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_B2B8B2B5E237E06` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wb_custom_field_rules`
--

LOCK TABLES `wb_custom_field_rules` WRITE;
/*!40000 ALTER TABLE `wb_custom_field_rules` DISABLE KEYS */;
INSERT INTO `wb_custom_field_rules` VALUES (1,'Global','global',NULL,'global',NULL),(2,'Partout','everywhere',NULL,'global',NULL),(3,'Type de publication','publication_type','/website/list-rule-value','global',NULL),(4,'Rôle de l\'utilisateur','user_role','/status/list-rule-value','single','accounts'),(5,'Page','page','/page/list-rule-value','single','pages'),(6,'Modèle de page','model','/template/list-rule-value','single','templates'),(7,'Type de page','page_type','/page/list-type-rule-value','global',NULL),(8,'Article','post','/module/post/list-rule-value','single','posts'),(9,'Catégorie d\'article','post_category','/module/post-category/list-rule-value','single','post_categories');
/*!40000 ALTER TABLE `wb_custom_field_rules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wb_custom_fields`
--

DROP TABLE IF EXISTS `wb_custom_fields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wb_custom_fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rule_id` int(11) DEFAULT NULL,
  `website_id` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `operation` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  `value` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_103FFA2E744E0351` (`rule_id`),
  KEY `IDX_103FFA2E18F45C82` (`website_id`),
  CONSTRAINT `FK_103FFA2E18F45C82` FOREIGN KEY (`website_id`) REFERENCES `wb_websites` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_103FFA2E744E0351` FOREIGN KEY (`rule_id`) REFERENCES `wb_custom_field_rules` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wb_custom_fields`
--

LOCK TABLES `wb_custom_fields` WRITE;
/*!40000 ALTER TABLE `wb_custom_fields` DISABLE KEYS */;
INSERT INTO `wb_custom_fields` VALUES (1,1,1,'Global','=',NULL),(2,5,1,'Page d\'accueil','=','1');
/*!40000 ALTER TABLE `wb_custom_fields` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wb_libraries`
--

DROP TABLE IF EXISTS `wb_libraries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wb_libraries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `path` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `category` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wb_libraries`
--

LOCK TABLES `wb_libraries` WRITE;
/*!40000 ALTER TABLE `wb_libraries` DISABLE KEYS */;
INSERT INTO `wb_libraries` VALUES (1,'Jquery','libs/jquery/jquery.min.js','file','js','2017-01-05 11:19:53','2017-01-05 11:19:53'),(2,'Bootstrap Js','libs/bootstrap/bootstrap.min.js','file','js','2017-01-05 11:19:53','2017-01-05 11:19:53'),(3,'Bootstrap Css','libs/bootstrap/bootstrap.min.css','file','css','2017-01-05 11:19:53','2017-01-05 11:19:53'),(4,'Material Js','libs/material/materialize.min.js','file','js','2017-01-05 11:19:53','2017-01-05 11:19:53'),(5,'Material Css','libs/material/materialize.min.css','file','css','2017-01-05 11:19:53','2017-01-05 11:19:53'),(6,'Font Awesome','//maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css','cdn','css','2017-01-05 11:19:53','2017-01-05 11:19:53'),(7,'Owl Carousel Js','libs/owl/owl.carousel.min.js','file','js','2017-01-05 11:19:53','2017-01-05 11:19:53'),(8,'Owl Carousel Css','libs/owl/owl.carousel.css','file','css','2017-01-05 11:19:53','2017-01-05 11:19:53');
/*!40000 ALTER TABLE `wb_libraries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wb_medias`
--

DROP TABLE IF EXISTS `wb_medias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wb_medias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `website_id` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `path` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `size` double NOT NULL,
  `access_level` smallint(6) NOT NULL,
  `alt` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_96888A08B548B0F` (`path`),
  KEY `IDX_96888A0818F45C82` (`website_id`),
  CONSTRAINT `FK_96888A0818F45C82` FOREIGN KEY (`website_id`) REFERENCES `wb_websites` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wb_medias`
--

LOCK TABLES `wb_medias` WRITE;
/*!40000 ALTER TABLE `wb_medias` DISABLE KEYS */;
INSERT INTO `wb_medias` VALUES (1,NULL,'Default account photo','/user/default-photo.png','image/png',16392,1,'Default account photo','2017-01-05 11:19:53','2017-01-05 11:19:53'),(2,NULL,'Theme aster thumbnail','/thumbnail/theme-aster-thumbnail.png','image/png',137577,1,'Theme aster thumbnail','2017-01-05 11:19:53','2017-01-05 11:19:53'),(3,NULL,'Theme balsamine thumbnail','/thumbnail/theme-balsamine-thumbnail.png','image/png',284330,1,'Theme balsamine thumbnail','2017-01-05 11:19:53','2017-01-05 11:19:53'),(4,NULL,'Theme heliotrope thumbnail','/thumbnail/theme-heliotrope-thumbnail.png','image/png',330670,1,'Theme heliotrope thumbnail','2017-01-05 11:19:53','2017-01-05 11:19:53'),(5,NULL,'Theme pivoine thumbnail','/thumbnail/theme-pivoine-thumbnail.png','image/png',366569,1,'Theme pivoine thumbnail','2017-01-05 11:19:53','2017-01-05 11:19:53'),(6,NULL,'Theme rose thumbnail','/thumbnail/theme-rose-thumbnail.png','image/png',404299,1,'Theme rose thumbnail','2017-01-05 11:19:53','2017-01-05 11:19:53'),(7,1,'Logo theme aster','/sites/1/logo.png','image/png',404299,1,'Logo aster','2017-01-05 11:19:53','2017-01-05 11:19:54'),(8,1,'Loading logo theme aster','/sites/1/logo-intro.png','image/png',404299,1,'Loading logo aster','2017-01-05 11:19:53','2017-01-05 11:19:54'),(9,1,'Theme aster header image','/sites/1/hero.jpg','image/jpg',404299,1,'Header background image','2017-01-05 11:19:53','2017-01-05 11:19:54'),(10,1,'Theme aster home page slide 1','/sites/1/slide-1.jpg','image/jpg',404299,1,'Theme aster home page slide 1','2017-01-05 11:19:53','2017-01-05 11:19:54'),(11,1,'Theme aster home page slide 2','/sites/1/slide-2.jpg','image/jpg',404299,1,'Theme aster home page slide 2','2017-01-05 11:19:53','2017-01-05 11:19:54'),(12,1,'Theme aster home page slide 3','/sites/1/slide-3.jpg','image/jpg',404299,1,'Theme aster home page slide 3','2017-01-05 11:19:53','2017-01-05 11:19:54'),(13,1,'Theme aster home page panorama image','/sites/1/panorama.jpg','image/jpg',404299,1,'Theme aster home page panorama image','2017-01-05 11:19:53','2017-01-05 11:19:54'),(14,NULL,'Article 1','/article-1.jpg','image/jpg',10.86,4,'Article 1','2017-01-05 11:19:55','2017-01-05 11:19:55'),(15,NULL,'Article 2','/article-2.jpg','image/jpg',2577,4,'Article 2','2017-01-05 11:19:55','2017-01-05 11:19:55'),(16,NULL,'Article 3','/article-3.jpg','image/jpg',2577,4,'Article 3','2017-01-05 11:19:55','2017-01-05 11:19:55'),(17,NULL,'Article 4','/article-4.jpg','image/jpg',2577,4,'Article 4','2017-01-05 11:19:55','2017-01-05 11:19:55'),(18,NULL,'Article 5','/article-5.jpg','image/jpg',2577,4,'Article 5','2017-01-05 11:19:55','2017-01-05 11:19:55');
/*!40000 ALTER TABLE `wb_medias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wb_module_categories`
--

DROP TABLE IF EXISTS `wb_module_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wb_module_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `icon` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `author` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `version` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `update_available` tinyint(1) NOT NULL,
  `access_level` smallint(6) NOT NULL,
  `nav` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_3AC3EEB35E237E06` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wb_module_categories`
--

LOCK TABLES `wb_module_categories` WRITE;
/*!40000 ALTER TABLE `wb_module_categories` DISABLE KEYS */;
INSERT INTO `wb_module_categories` VALUES (1,'Post','Article','post','fa fa-newspaper-o','Module pour afficher des articles','S.Sumugan','0.1',0,4,1,'2017-01-05 11:19:55','2017-01-05 11:19:55'),(2,'Navigation','Menu','navigation','fa fa-bars','Module pour le menu','S.Sumugan','0.1',0,2,1,'2017-01-05 11:19:55','2017-01-05 11:19:55'),(3,'Grid Editor','Contenu wysiwyg','grid-editor','fa fa-code','Module pour afficher des contenus','S.Sumugan','0.1',0,4,0,'2017-01-05 11:19:55','2017-01-05 11:19:55');
/*!40000 ALTER TABLE `wb_module_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wb_modules`
--

DROP TABLE IF EXISTS `wb_modules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wb_modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `callback` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `access_level` smallint(6) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_BA57227612469DE2` (`category_id`),
  CONSTRAINT `FK_BA57227612469DE2` FOREIGN KEY (`category_id`) REFERENCES `wb_module_categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wb_modules`
--

LOCK TABLES `wb_modules` WRITE;
/*!40000 ALTER TABLE `wb_modules` DISABLE KEYS */;
INSERT INTO `wb_modules` VALUES (1,1,'Article','single-post','Jet\\Modules\\Post\\Controllers\\FrontPostController@read','Affiche un seul article',4),(2,1,'Liste d\'articles','list-post','Jet\\Modules\\Post\\Controllers\\FrontPostController@all','Liste d\'articles par catégorie',4),(3,2,'Menu simple','navigation','Jet\\Modules\\Navigation\\Controllers\\FrontNavigationController@show','Affiche un menu simple',4),(4,3,'Editeur de contenu','grid-editor','Jet\\Modules\\GridEditor\\Controllers\\FrontGridEditorController@show','Affiche un contenu wysiwyg',4);
/*!40000 ALTER TABLE `wb_modules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wb_modules_templates`
--

DROP TABLE IF EXISTS `wb_modules_templates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wb_modules_templates` (
  `module_id` int(11) NOT NULL,
  `template_id` int(11) NOT NULL,
  PRIMARY KEY (`module_id`,`template_id`),
  KEY `IDX_AE026958AFC2B591` (`module_id`),
  KEY `IDX_AE0269585DA0FB8` (`template_id`),
  CONSTRAINT `FK_AE0269585DA0FB8` FOREIGN KEY (`template_id`) REFERENCES `wb_templates` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_AE026958AFC2B591` FOREIGN KEY (`module_id`) REFERENCES `wb_modules` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wb_modules_templates`
--

LOCK TABLES `wb_modules_templates` WRITE;
/*!40000 ALTER TABLE `wb_modules_templates` DISABLE KEYS */;
INSERT INTO `wb_modules_templates` VALUES (1,15),(1,16),(2,17);
/*!40000 ALTER TABLE `wb_modules_templates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wb_navigation_items`
--

DROP TABLE IF EXISTS `wb_navigation_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wb_navigation_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `navigation_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `route_id` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type_id` int(11) DEFAULT NULL,
  `position` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_35EBBD4039F79D6D` (`navigation_id`),
  KEY `IDX_35EBBD40727ACA70` (`parent_id`),
  KEY `IDX_35EBBD4034ECB4E6` (`route_id`),
  CONSTRAINT `FK_35EBBD4034ECB4E6` FOREIGN KEY (`route_id`) REFERENCES `wb_routes` (`id`) ON DELETE SET NULL,
  CONSTRAINT `FK_35EBBD4039F79D6D` FOREIGN KEY (`navigation_id`) REFERENCES `wb_navigations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_35EBBD40727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `wb_navigation_items` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wb_navigation_items`
--

LOCK TABLES `wb_navigation_items` WRITE;
/*!40000 ALTER TABLE `wb_navigation_items` DISABLE KEYS */;
INSERT INTO `wb_navigation_items` VALUES (1,1,NULL,NULL,'Accueil','/','page',1,0),(2,1,NULL,5,'Services','/articles/service','post_category',1,1),(3,1,NULL,2,'Tarifs','/tarifs','page',5,2),(4,1,NULL,3,'Contact','/contact','page',6,3);
/*!40000 ALTER TABLE `wb_navigation_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wb_navigations`
--

DROP TABLE IF EXISTS `wb_navigations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wb_navigations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `website_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_B8FFA33518F45C82` (`website_id`),
  CONSTRAINT `FK_B8FFA33518F45C82` FOREIGN KEY (`website_id`) REFERENCES `wb_websites` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wb_navigations`
--

LOCK TABLES `wb_navigations` WRITE;
/*!40000 ALTER TABLE `wb_navigations` DISABLE KEYS */;
INSERT INTO `wb_navigations` VALUES (1,1,'Menu','2017-01-05 11:19:55','2017-01-05 11:19:55');
/*!40000 ALTER TABLE `wb_navigations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wb_pages`
--

DROP TABLE IF EXISTS `wb_pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wb_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `route_id` int(11) DEFAULT NULL,
  `website_id` int(11) DEFAULT NULL,
  `layout_id` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `builder` tinyint(1) NOT NULL,
  `published` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_C410B26634ECB4E6` (`route_id`),
  KEY `IDX_C410B26618F45C82` (`website_id`),
  KEY `IDX_C410B2668C22AA1A` (`layout_id`),
  CONSTRAINT `FK_C410B2668C22AA1A` FOREIGN KEY (`layout_id`) REFERENCES `wb_templates` (`id`) ON DELETE SET NULL,
  CONSTRAINT `FK_C410B26618F45C82` FOREIGN KEY (`website_id`) REFERENCES `wb_websites` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_C410B26634ECB4E6` FOREIGN KEY (`route_id`) REFERENCES `wb_routes` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wb_pages`
--

LOCK TABLES `wb_pages` WRITE;
/*!40000 ALTER TABLE `wb_pages` DISABLE KEYS */;
INSERT INTO `wb_pages` VALUES (1,1,1,6,'Accueil','static',0,1,'2017-01-05 11:19:54','2017-01-05 11:19:54'),(2,4,1,5,'Articles','static',0,1,'2017-01-05 11:19:54','2017-01-05 11:19:54'),(3,5,1,5,'Articles dynamique','dynamic',0,1,'2017-01-05 11:19:54','2017-01-05 11:19:54'),(4,6,1,5,'Article','dynamic',0,1,'2017-01-05 11:19:54','2017-01-05 11:19:54'),(5,2,1,5,'Tarifs','static',0,1,'2017-01-05 11:19:54','2017-01-05 11:19:54'),(6,3,1,5,'Contact','static',0,1,'2017-01-05 11:19:54','2017-01-05 11:19:54'),(7,1,2,11,'Accueil','static',0,1,'2017-01-05 11:19:54','2017-01-05 11:19:54'),(8,4,2,11,'Articles','static',0,1,'2017-01-05 11:19:54','2017-01-05 11:19:54'),(9,5,2,11,'Articles dynamique','dynamic',0,1,'2017-01-05 11:19:54','2017-01-05 11:19:54'),(10,6,2,11,'Article','dynamic',0,1,'2017-01-05 11:19:54','2017-01-05 11:19:54');
/*!40000 ALTER TABLE `wb_pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wb_pages_libraries`
--

DROP TABLE IF EXISTS `wb_pages_libraries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wb_pages_libraries` (
  `page_id` int(11) NOT NULL,
  `library_id` int(11) NOT NULL,
  PRIMARY KEY (`page_id`,`library_id`),
  KEY `IDX_89C3E22BC4663E4` (`page_id`),
  KEY `IDX_89C3E22BFE2541D7` (`library_id`),
  CONSTRAINT `FK_89C3E22BFE2541D7` FOREIGN KEY (`library_id`) REFERENCES `wb_libraries` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_89C3E22BC4663E4` FOREIGN KEY (`page_id`) REFERENCES `wb_pages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wb_pages_libraries`
--

LOCK TABLES `wb_pages_libraries` WRITE;
/*!40000 ALTER TABLE `wb_pages_libraries` DISABLE KEYS */;
/*!40000 ALTER TABLE `wb_pages_libraries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wb_pages_stylesheets`
--

DROP TABLE IF EXISTS `wb_pages_stylesheets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wb_pages_stylesheets` (
  `page_id` int(11) NOT NULL,
  `template_id` int(11) NOT NULL,
  PRIMARY KEY (`page_id`,`template_id`),
  KEY `IDX_49D0BD51C4663E4` (`page_id`),
  KEY `IDX_49D0BD515DA0FB8` (`template_id`),
  CONSTRAINT `FK_49D0BD515DA0FB8` FOREIGN KEY (`template_id`) REFERENCES `wb_templates` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_49D0BD51C4663E4` FOREIGN KEY (`page_id`) REFERENCES `wb_pages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wb_pages_stylesheets`
--

LOCK TABLES `wb_pages_stylesheets` WRITE;
/*!40000 ALTER TABLE `wb_pages_stylesheets` DISABLE KEYS */;
/*!40000 ALTER TABLE `wb_pages_stylesheets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wb_post_categories`
--

DROP TABLE IF EXISTS `wb_post_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wb_post_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `website_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_606F75B818F45C82` (`website_id`),
  CONSTRAINT `FK_606F75B818F45C82` FOREIGN KEY (`website_id`) REFERENCES `wb_websites` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wb_post_categories`
--

LOCK TABLES `wb_post_categories` WRITE;
/*!40000 ALTER TABLE `wb_post_categories` DISABLE KEYS */;
INSERT INTO `wb_post_categories` VALUES (1,1,'Service','service'),(2,1,'Actualité','actualite');
/*!40000 ALTER TABLE `wb_post_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wb_posts`
--

DROP TABLE IF EXISTS `wb_posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wb_posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `thumbnail_id` int(11) DEFAULT NULL,
  `website_id` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci,
  `content` longtext COLLATE utf8_unicode_ci,
  `published` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_6C39EDE9FDFF2E92` (`thumbnail_id`),
  KEY `IDX_6C39EDE918F45C82` (`website_id`),
  CONSTRAINT `FK_6C39EDE918F45C82` FOREIGN KEY (`website_id`) REFERENCES `wb_websites` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_6C39EDE9FDFF2E92` FOREIGN KEY (`thumbnail_id`) REFERENCES `wb_medias` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wb_posts`
--

LOCK TABLES `wb_posts` WRITE;
/*!40000 ALTER TABLE `wb_posts` DISABLE KEYS */;
INSERT INTO `wb_posts` VALUES (1,14,1,'Mariage','mariage','Parce que le mariage est un moment unique, Aster Society propose de nombreux services pour satisfaire toutes les envies des mariés.','Parce que le mariage est un moment unique, Aster Society propose de nombreux services pour satisfaire toutes les envies des mariés.\n\nEn plus des coiffures traditionnelles, nos spécialistes vous conseillent (à l\'aide de photos modèles) afin de réaliser la coiffure dont vous rêvez. N\'hésitez pas à venir en discuter avec notre équipe.',1,'2017-01-05 11:19:55','2017-01-05 11:19:55'),(2,15,1,'Intervention en entreprise','intervention-en-entreprise','De plus en plus d\'entreprises organisent des journées ou demi-journées sur le thème de la coiffure pour leurs employés. Conviviaux et fédérateurs, ces moments sont des plus agréables. D\'autant que les employés sont coiffés sans frais !','De plus en plus d\'entreprises organisent des journées ou demi-journées sur le thème de la coiffure pour leurs employés. Conviviaux et fédérateurs, ces moments sont des plus agréables. D\'autant que les employés sont coiffés sans frais !\n\nAster Society propose d\'intervenir dans ce cadre. Contactez-nous pour plus d\'informations. ',1,'2017-01-05 11:19:55','2017-01-05 11:19:55'),(3,16,1,'Couleur','couleur','Esthétique Coiffure Manuela bénéficie d\'une large gamme de produits qui permet aux coiffeurs de proposer de nombreuses teintes sous trois formes principales : les colorations intégrales, les mèches et les balayages.','Esthétique Coiffure Manuela bénéficie d\'une large gamme de produits qui permet aux coiffeurs de proposer de nombreuses teintes sous trois formes principales : les colorations intégrales, les mèches et les balayages.\n\nNos coiffeurs sont à votre écoute pour comprendre vos envies et trouver la couleur dont vous rêvez.',1,'2017-01-05 11:19:55','2017-01-05 11:19:55'),(4,17,1,'Offrir des cadeaux','offrir-des-cadeaux','Aster Society vous propose un large panel de cadeaux à offrir aux personnes de votre choix','Aster Society vous propose un large panel de cadeaux à offrir aux personnes de votre choix :\n\n- Offre anniversaire : sous forme d\'un chèque cadeau au montant que vous définissez. \n\n- Offre découverte : permet de faire découvrir les coupes et services originaux que nous proposons. \n\n- Offre parrainage : faites découvrir notre salon à vos amis et réalisez des économies à chaque coupe.',1,'2017-01-05 11:19:55','2017-01-05 11:19:55'),(5,18,1,'Prestations coiffure','prestations-coiffure','Aster Society propose les services \"classiques\" de la coiffure','Aster Society propose les services \"classiques\" de la coiffure, pour hommes, femmes et enfants : shampoing, coupe, brushing, ... Vous trouverez un résumé plus détaillé de nos prestations dans la partie Tarifs de notre site.',1,'2017-01-05 11:19:55','2017-01-05 11:19:55'),(6,14,2,'Balsamine Service 1','service-1','Service 1 de Aster','Lorem ipsum dolor sit amet, consectetur adipisicing elit. Qui, cupiditate, at! Minus rem aut, culpa aspernatur cumque enim blanditiis sunt!',1,'2017-01-05 11:19:55','2017-01-05 11:19:55'),(7,15,2,'Balsamine Service 2','service-2','Service 2 de Aster','Lorem ipsum dolor sit amet, consectetur adipisicing elit. Qui, cupiditate, at! Minus rem aut, culpa aspernatur cumque enim blanditiis sunt!',1,'2017-01-05 11:19:55','2017-01-05 11:19:55'),(8,16,2,'Balsamine Service 3','service-3','Service 3 de Aster','Lorem ipsum dolor sit amet, consectetur adipisicing elit. Qui, cupiditate, at! Minus rem aut, culpa aspernatur cumque enim blanditiis sunt!',1,'2017-01-05 11:19:55','2017-01-05 11:19:55'),(9,14,6,'Luffy Service 1','luffy-service-1','Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.','Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Qui, cupiditate, at! Minus rem aut, culpa aspernatur cumque enim blanditiis sunt!',1,'2017-01-05 11:19:55','2017-01-05 11:19:55');
/*!40000 ALTER TABLE `wb_posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wb_posts_categories`
--

DROP TABLE IF EXISTS `wb_posts_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wb_posts_categories` (
  `post_id` int(11) NOT NULL,
  `postcategory_id` int(11) NOT NULL,
  PRIMARY KEY (`post_id`,`postcategory_id`),
  KEY `IDX_C20A6E8E4B89032C` (`post_id`),
  KEY `IDX_C20A6E8E5275645B` (`postcategory_id`),
  CONSTRAINT `FK_C20A6E8E5275645B` FOREIGN KEY (`postcategory_id`) REFERENCES `wb_post_categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_C20A6E8E4B89032C` FOREIGN KEY (`post_id`) REFERENCES `wb_posts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wb_posts_categories`
--

LOCK TABLES `wb_posts_categories` WRITE;
/*!40000 ALTER TABLE `wb_posts_categories` DISABLE KEYS */;
INSERT INTO `wb_posts_categories` VALUES (1,1),(2,1),(3,1),(4,1),(5,1),(6,1),(7,1),(8,1),(9,1);
/*!40000 ALTER TABLE `wb_posts_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wb_professions`
--

DROP TABLE IF EXISTS `wb_professions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wb_professions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `icon` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wb_professions`
--

LOCK TABLES `wb_professions` WRITE;
/*!40000 ALTER TABLE `wb_professions` DISABLE KEYS */;
INSERT INTO `wb_professions` VALUES (1,'Coiffure / Institut de beauté','barber','fa fa-scissors');
/*!40000 ALTER TABLE `wb_professions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wb_routes`
--

DROP TABLE IF EXISTS `wb_routes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wb_routes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `website_id` int(11) DEFAULT NULL,
  `url` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `method` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:json)',
  `argument` longtext COLLATE utf8_unicode_ci COMMENT '(DC2Type:json)',
  `middleware` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `subdomain` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `position` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_B68FE73A18F45C82` (`website_id`),
  CONSTRAINT `FK_B68FE73A18F45C82` FOREIGN KEY (`website_id`) REFERENCES `wb_websites` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wb_routes`
--

LOCK TABLES `wb_routes` WRITE;
/*!40000 ALTER TABLE `wb_routes` DISABLE KEYS */;
INSERT INTO `wb_routes` VALUES (1,NULL,'/','home','[\"GET\"]',NULL,NULL,NULL,0),(2,NULL,'/tarifs','module:price.type:static.action:all','[\"GET\"]',NULL,NULL,NULL,0),(3,NULL,'/contact','module:contact.type:static.action:show','[\"GET\"]',NULL,NULL,NULL,0),(4,NULL,'/articles','module:post.type:static.action:all','[\"GET\"]',NULL,NULL,NULL,0),(5,NULL,'/articles/:slug','module:post.type:dynamic.action:all','[\"GET\"]','{\"slug\":\"([a-z-_]+)\"}',NULL,NULL,0),(6,NULL,'/article/:slug','module:post.type:dynamic.action:read','[\"GET\"]',NULL,NULL,NULL,0);
/*!40000 ALTER TABLE `wb_routes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wb_sections`
--

DROP TABLE IF EXISTS `wb_sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wb_sections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `section_id` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `section_class` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `style` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `container` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `position` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wb_sections`
--

LOCK TABLES `wb_sections` WRITE;
/*!40000 ALTER TABLE `wb_sections` DISABLE KEYS */;
INSERT INTO `wb_sections` VALUES (1,NULL,'row','padding: 10px 0;','container',NULL);
/*!40000 ALTER TABLE `wb_sections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wb_societies`
--

DROP TABLE IF EXISTS `wb_societies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wb_societies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_1DEF99F85E237E06` (`name`),
  KEY `IDX_1DEF99F89B6B5FBA` (`account_id`),
  CONSTRAINT `FK_1DEF99F89B6B5FBA` FOREIGN KEY (`account_id`) REFERENCES `wb_accounts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wb_societies`
--

LOCK TABLES `wb_societies` WRITE;
/*!40000 ALTER TABLE `wb_societies` DISABLE KEYS */;
INSERT INTO `wb_societies` VALUES (1,1,'Aster Society','2017-01-05 11:19:54','2017-01-05 11:19:54'),(2,1,'Balsamine Society','2017-01-05 11:19:54','2017-01-05 11:19:54'),(3,1,'Heliotrope Society','2017-01-05 11:19:54','2017-01-05 11:19:54'),(4,1,'Pivoine Society','2017-01-05 11:19:54','2017-01-05 11:19:54'),(5,1,'Rose Society','2017-01-05 11:19:54','2017-01-05 11:19:54'),(6,7,'Luffy Society','2017-01-05 11:19:54','2017-01-05 11:19:54'),(7,8,'Zoro Society','2017-01-05 11:19:54','2017-01-05 11:19:54'),(8,9,'Sanji Society','2017-01-05 11:19:54','2017-01-05 11:19:54'),(9,10,'Chopper Society','2017-01-05 11:19:54','2017-01-05 11:19:54'),(10,11,'Robin Society','2017-01-05 11:19:54','2017-01-05 11:19:54');
/*!40000 ALTER TABLE `wb_societies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wb_status`
--

DROP TABLE IF EXISTS `wb_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wb_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `level` smallint(6) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_FF5A409557698A6A` (`role`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wb_status`
--

LOCK TABLES `wb_status` WRITE;
/*!40000 ALTER TABLE `wb_status` DISABLE KEYS */;
INSERT INTO `wb_status` VALUES (1,'super_admin',1),(2,'admin',2),(3,'commercial',2),(4,'editor',3),(5,'user',4);
/*!40000 ALTER TABLE `wb_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wb_templates`
--

DROP TABLE IF EXISTS `wb_templates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wb_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `website_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8_unicode_ci NOT NULL,
  `category` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `scope` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_E28135085E237E06` (`name`),
  KEY `IDX_E281350818F45C82` (`website_id`),
  CONSTRAINT `FK_E281350818F45C82` FOREIGN KEY (`website_id`) REFERENCES `wb_websites` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wb_templates`
--

LOCK TABLES `wb_templates` WRITE;
/*!40000 ALTER TABLE `wb_templates` DISABLE KEYS */;
INSERT INTO `wb_templates` VALUES (1,NULL,'DefaultGlobalFileLayout','Template de base','index','layout','global','file','2017-01-05 11:19:53','2017-01-05 11:19:53'),(2,NULL,'DefaultGlobalFileWebsiteLayout','Template de base html','default_layout','layout','global','file','2017-01-05 11:19:53','2017-01-05 11:19:53'),(3,NULL,'DefaultGlobalFileWebsiteVueLayout','Template de base vue','default_vue_layout','layout','global','file','2017-01-05 11:19:53','2017-01-05 11:19:53'),(4,1,'ThemeAsterFileLayout','Theme Aster Template','Themes/Aster/aster_layout','layout','specified','file','2017-01-05 11:19:53','2017-01-05 11:19:54'),(5,1,'ThemeAsterPageFileLayout','Theme Aster Page Template','Themes/Aster/page','layout','specified','file','2017-01-05 11:19:53','2017-01-05 11:19:54'),(6,1,'ThemeAsterHomePageFileLayout','Theme Aster Home Page Template','Themes/Aster/index','layout','specified','file','2017-01-05 11:19:53','2017-01-05 11:19:54'),(7,NULL,'ThemeAsterNavigationFilePartial','Theme Aster Navigation Template','Themes/Aster/navigation','partial','specified','file','2017-01-05 11:19:53','2017-01-05 11:19:53'),(8,1,'ThemeAsterPriceFilePartial','Theme Aster Price Template','Themes/Aster/price_list','partial','specified','file','2017-01-05 11:19:53','2017-01-05 11:19:54'),(9,1,'ThemeAsterFileJsLayout','Theme Aster Template Js','Themes/Aster/index.js','layout','specified','file','2017-01-05 11:19:53','2017-01-05 11:19:54'),(10,2,'ThemeBalsamineFileLayout','Theme Balsamine Template','Themes/Balsamine/index','layout','specified','file','2017-01-05 11:19:53','2017-01-05 11:19:54'),(11,2,'ThemeBalsamineFileLayoutJs','Theme Balsamine Template Js','Themes/Balsamine/index.js','layout','specified','file','2017-01-05 11:19:53','2017-01-05 11:19:54'),(12,3,'ThemeHeliotropeFileLayout','Theme Heliotrope Template','Themes/Heliotrope/index','layout','specified','file','2017-01-05 11:19:53','2017-01-05 11:19:54'),(13,4,'ThemePivoineFileLayout','Theme Pivoine Template','Themes/Pivoine/index','layout','specified','file','2017-01-05 11:19:53','2017-01-05 11:19:54'),(14,5,'ThemeRoseFileLayout','Theme Rose Template','Themes/Rose/index','layout','specified','file','2017-01-05 11:19:53','2017-01-05 11:19:54'),(15,NULL,'ModulePostPartialWholeContent','Article en entier','post','partial','global','file','2017-01-05 11:19:55','2017-01-05 11:19:55'),(16,NULL,'ModulePostPartialOnlyBody','Corps uniquement','post_body','partial','global','file','2017-01-05 11:19:55','2017-01-05 11:19:55'),(17,NULL,'ModulePostPartialBasicList','Liste basique','post_basic_list','partial','global','file','2017-01-05 11:19:55','2017-01-05 11:19:55'),(18,NULL,'ModulePostPartialBasicListJs','Liste basique Js','post_basic_list_js','partial','global','file','2017-01-05 11:19:55','2017-01-05 11:19:55'),(19,1,'ThemeAsterPostListFilePartial','Theme Aster Post List Template','Themes/Aster/post_list','partial','specified','file','2017-01-05 11:19:55','2017-01-05 11:19:55'),(20,1,'ThemeAsterPostFilePartial','Theme Aster Post Template','Themes/Aster/post','partial','specified','file','2017-01-05 11:19:55','2017-01-05 11:19:55'),(21,NULL,'ModuleNavigationPartialSimple','Menu simple','navigation','partial','global','file','2017-01-05 11:19:55','2017-01-05 11:19:55');
/*!40000 ALTER TABLE `wb_templates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wb_themes`
--

DROP TABLE IF EXISTS `wb_themes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wb_themes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `thumbnail_id` int(11) DEFAULT NULL,
  `website_id` int(11) DEFAULT NULL,
  `profession_id` int(11) DEFAULT NULL,
  `name` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `state` smallint(6) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_91181757FDFF2E92` (`thumbnail_id`),
  UNIQUE KEY `UNIQ_9118175718F45C82` (`website_id`),
  KEY `IDX_91181757FDEF8996` (`profession_id`),
  CONSTRAINT `FK_91181757FDEF8996` FOREIGN KEY (`profession_id`) REFERENCES `wb_professions` (`id`) ON DELETE SET NULL,
  CONSTRAINT `FK_9118175718F45C82` FOREIGN KEY (`website_id`) REFERENCES `wb_websites` (`id`) ON DELETE SET NULL,
  CONSTRAINT `FK_91181757FDFF2E92` FOREIGN KEY (`thumbnail_id`) REFERENCES `wb_medias` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wb_themes`
--

LOCK TABLES `wb_themes` WRITE;
/*!40000 ALTER TABLE `wb_themes` DISABLE KEYS */;
INSERT INTO `wb_themes` VALUES (1,2,1,1,'Aster',1,'2017-01-05 11:19:54','2017-01-05 11:19:54'),(2,3,2,1,'Balsamine',1,'2017-01-05 11:19:54','2017-01-05 11:19:54'),(3,4,3,1,'Heliotrope',1,'2017-01-05 11:19:54','2017-01-05 11:19:54'),(4,5,4,1,'Pivoine',1,'2017-01-05 11:19:54','2017-01-05 11:19:54'),(5,6,5,1,'Rose',1,'2017-01-05 11:19:54','2017-01-05 11:19:54');
/*!40000 ALTER TABLE `wb_themes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wb_websites`
--

DROP TABLE IF EXISTS `wb_websites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wb_websites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `society_id` int(11) DEFAULT NULL,
  `theme_id` int(11) DEFAULT NULL,
  `layout_id` int(11) DEFAULT NULL,
  `logo_id` int(11) DEFAULT NULL,
  `domain` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `modules` longtext COLLATE utf8_unicode_ci COMMENT '(DC2Type:array)',
  `state` smallint(6) NOT NULL,
  `render_system` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `data` longtext COLLATE utf8_unicode_ci COMMENT '(DC2Type:json)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8462A492A7A91E0B` (`domain`),
  UNIQUE KEY `UNIQ_8462A492E6389D24` (`society_id`),
  KEY `IDX_8462A49259027487` (`theme_id`),
  KEY `IDX_8462A4928C22AA1A` (`layout_id`),
  KEY `IDX_8462A492F98F144A` (`logo_id`),
  CONSTRAINT `FK_8462A492F98F144A` FOREIGN KEY (`logo_id`) REFERENCES `wb_medias` (`id`) ON DELETE SET NULL,
  CONSTRAINT `FK_8462A49259027487` FOREIGN KEY (`theme_id`) REFERENCES `wb_themes` (`id`) ON DELETE SET NULL,
  CONSTRAINT `FK_8462A4928C22AA1A` FOREIGN KEY (`layout_id`) REFERENCES `wb_templates` (`id`) ON DELETE SET NULL,
  CONSTRAINT `FK_8462A492E6389D24` FOREIGN KEY (`society_id`) REFERENCES `wb_societies` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wb_websites`
--

LOCK TABLES `wb_websites` WRITE;
/*!40000 ALTER TABLE `wb_websites` DISABLE KEYS */;
INSERT INTO `wb_websites` VALUES (1,1,1,4,7,'aster-society','a:4:{i:0;i:2;i:1;i:1;i:2;i:3;i:3;i:4;}',1,'php','{\"parent_exclude\":[],\"parent_replace\":[]}'),(2,2,2,3,NULL,'balsamine-society','a:4:{i:0;i:2;i:1;i:1;i:2;i:3;i:3;i:4;}',1,'js','{\"parent_exclude\":[],\"parent_replace\":[]}'),(3,3,3,2,NULL,'heliotrope-society','a:4:{i:0;i:2;i:1;i:1;i:2;i:3;i:3;i:4;}',1,'php','{\"parent_exclude\":[],\"parent_replace\":[]}'),(4,4,4,2,NULL,'pivoine-society','a:4:{i:0;i:2;i:1;i:1;i:2;i:3;i:3;i:4;}',1,'php','{\"parent_exclude\":[],\"parent_replace\":[]}'),(5,5,5,2,NULL,'rose-society','a:4:{i:0;i:2;i:1;i:1;i:2;i:3;i:3;i:4;}',1,'php','{\"parent_exclude\":[],\"parent_replace\":[]}'),(6,6,1,4,7,'luffy-society','a:4:{i:0;i:2;i:1;i:1;i:2;i:3;i:3;i:4;}',1,'php','{\"parent_exclude\":{\"posts\":[4]},\"parent_replace\":[]}'),(7,7,1,4,NULL,'zoro-society','a:4:{i:0;i:2;i:1;i:1;i:2;i:3;i:3;i:4;}',1,'php','{\"parent_exclude\":[],\"parent_replace\":[]}'),(8,8,3,2,NULL,'sanji-society','a:4:{i:0;i:2;i:1;i:1;i:2;i:3;i:3;i:4;}',1,'php','{\"parent_exclude\":[],\"parent_replace\":[]}'),(9,9,4,2,NULL,'chopper-society','a:4:{i:0;i:2;i:1;i:1;i:2;i:3;i:3;i:4;}',1,'php','{\"parent_exclude\":[],\"parent_replace\":[]}'),(10,10,5,2,NULL,'robin-society','a:4:{i:0;i:2;i:1;i:1;i:2;i:3;i:3;i:4;}',1,'php','{\"parent_exclude\":[],\"parent_replace\":[]}');
/*!40000 ALTER TABLE `wb_websites` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-01-06 12:13:58
