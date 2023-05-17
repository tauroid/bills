-- MariaDB dump 10.19  Distrib 10.6.12-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: bills
-- ------------------------------------------------------
-- Server version	10.6.12-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `amount`
--

DROP TABLE IF EXISTS `amount`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `amount` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `currency` enum('gbp','usd','percentage','share') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=328 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `amount`
--

LOCK TABLES `amount` WRITE;
/*!40000 ALTER TABLE `amount` DISABLE KEYS */;
INSERT INTO `amount` VALUES (2,'gbp'),(3,'gbp'),(4,'gbp'),(5,'gbp'),(6,'gbp'),(7,'gbp'),(8,'gbp'),(24,'gbp'),(25,'share'),(26,'share'),(28,'gbp'),(29,'share'),(30,'share'),(31,'gbp'),(32,'share'),(33,'share'),(34,'gbp'),(35,'share'),(36,'share'),(37,'gbp'),(38,'share'),(39,'share'),(40,'gbp'),(41,'share'),(42,'share'),(43,'gbp'),(44,'share'),(45,'share'),(46,'share'),(47,'share'),(48,'share'),(49,'gbp'),(50,'share'),(51,'share'),(52,'share'),(53,'share'),(54,'share'),(55,'gbp'),(56,'share'),(57,'share'),(59,'gbp'),(60,'share'),(61,'share'),(62,'gbp'),(63,'percentage'),(64,'share'),(65,'gbp'),(66,'percentage'),(67,'share'),(68,'share'),(69,'percentage'),(70,'percentage'),(71,'percentage'),(73,'gbp'),(74,'percentage'),(75,'percentage'),(76,'share'),(77,'share'),(78,'gbp'),(79,'share'),(80,'share'),(81,'share'),(82,'gbp'),(83,'share'),(84,'share'),(85,'share'),(86,'share'),(87,'share'),(88,'share'),(89,'gbp'),(90,'gbp'),(91,'share'),(92,'gbp'),(93,'gbp'),(94,'share'),(95,'gbp'),(96,'gbp'),(97,'gbp'),(98,'share'),(99,'share'),(100,'gbp'),(101,'share'),(102,'share'),(103,'gbp'),(104,'share'),(105,'share'),(106,'gbp'),(107,'share'),(108,'share'),(109,'gbp'),(110,'share'),(111,'share'),(112,'gbp'),(113,'share'),(114,'share'),(115,'share'),(116,'share'),(117,'gbp'),(118,'share'),(119,'share'),(120,'share'),(121,'share'),(122,'share'),(123,'share'),(124,'share'),(125,'gbp'),(126,'share'),(127,'share'),(128,'share'),(129,'share'),(130,'share'),(131,'share'),(132,'share'),(133,'share'),(134,'share'),(135,'share'),(136,'share'),(137,'gbp'),(138,'gbp'),(139,'share'),(140,'share'),(141,'share'),(142,'share'),(143,'share'),(144,'share'),(145,'gbp'),(146,'share'),(147,'gbp'),(148,'share'),(149,'share'),(150,'percentage'),(151,'percentage'),(152,'gbp'),(153,'share'),(154,'share'),(155,'share'),(156,'share'),(157,'share'),(158,'share'),(159,'gbp'),(160,'share'),(161,'gbp'),(162,'share'),(163,'gbp'),(164,'gbp'),(165,'gbp'),(166,'share'),(167,'share'),(168,'share'),(169,'gbp'),(170,'share'),(171,'share'),(172,'share'),(173,'share'),(174,'share'),(175,'share'),(176,'share'),(177,'gbp'),(178,'share'),(179,'share'),(180,'share'),(181,'share'),(182,'gbp'),(183,'gbp'),(184,'share'),(185,'share'),(186,'gbp'),(187,'share'),(188,'share'),(189,'gbp'),(190,'gbp'),(191,'gbp'),(192,'share'),(193,'share'),(194,'share'),(195,'gbp'),(196,'share'),(197,'share'),(198,'share'),(199,'share'),(200,'gbp'),(201,'gbp'),(202,'share'),(203,'share'),(204,'gbp'),(205,'share'),(206,'share'),(207,'share'),(208,'gbp'),(209,'share'),(210,'share'),(211,'gbp'),(212,'share'),(213,'share'),(214,'share'),(215,'gbp'),(216,'share'),(217,'share'),(218,'gbp'),(219,'share'),(220,'share'),(221,'gbp'),(222,'share'),(223,'share'),(224,'gbp'),(225,'share'),(226,'share'),(227,'gbp'),(228,'percentage'),(229,'share'),(230,'gbp'),(231,'gbp'),(232,'share'),(233,'share'),(234,'percentage'),(235,'gbp'),(236,'gbp'),(237,'share'),(238,'share'),(239,'gbp'),(240,'share'),(241,'share'),(242,'gbp'),(243,'share'),(244,'share'),(245,'share'),(246,'gbp'),(247,'share'),(248,'share'),(249,'share'),(250,'share'),(251,'gbp'),(252,'share'),(253,'share'),(254,'gbp'),(255,'share'),(256,'gbp'),(257,'gbp'),(258,'gbp'),(259,'gbp'),(260,'gbp'),(261,'gbp'),(262,'share'),(263,'share'),(264,'gbp'),(265,'share'),(266,'share'),(267,'share'),(268,'share'),(269,'share'),(270,'share'),(271,'gbp'),(272,'gbp'),(273,'gbp'),(274,'share'),(275,'gbp'),(276,'share'),(277,'share'),(278,'gbp'),(279,'share'),(280,'share'),(281,'gbp'),(282,'share'),(283,'share'),(284,'share'),(285,'share'),(286,'share'),(287,'gbp'),(288,'share'),(289,'share'),(290,'share'),(291,'share'),(292,'gbp'),(293,'share'),(294,'share'),(295,'gbp'),(296,'share'),(297,'share'),(298,'share'),(299,'share'),(300,'gbp'),(301,'share'),(302,'share'),(303,'gbp'),(304,'share'),(305,'share'),(306,'share'),(307,'share'),(308,'gbp'),(309,'share'),(310,'share'),(311,'share'),(312,'gbp'),(313,'gbp'),(314,'gbp'),(315,'gbp'),(316,'gbp'),(317,'gbp'),(318,'gbp'),(319,'gbp'),(320,'share'),(321,'share'),(322,'gbp'),(323,'gbp'),(324,'gbp'),(325,'gbp'),(326,'gbp'),(327,'gbp');
/*!40000 ALTER TABLE `amount` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `amount_gbp`
--

DROP TABLE IF EXISTS `amount_gbp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `amount_gbp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pounds` int(11) NOT NULL,
  `pence` int(11) NOT NULL DEFAULT 0,
  `amount` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `amount_gbp_UN` (`amount`),
  KEY `amount_gbp_FK` (`amount`),
  CONSTRAINT `amount_gbp_FK` FOREIGN KEY (`amount`) REFERENCES `amount` (`id`),
  CONSTRAINT `amount_gbp_CHECK` CHECK (`pounds` >= 0 and `pence` >= 0 and `pence` < 100)
) ENGINE=InnoDB AUTO_INCREMENT=119 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `amount_gbp`
--

LOCK TABLES `amount_gbp` WRITE;
/*!40000 ALTER TABLE `amount_gbp` DISABLE KEYS */;
INSERT INTO `amount_gbp` VALUES (1,100,0,2),(2,50,0,3),(3,100,0,4),(4,50,0,5),(5,50,0,6),(6,50,0,7),(7,50,0,8),(18,0,0,24),(20,0,0,28),(21,10,0,31),(22,0,0,34),(23,0,0,37),(24,100,0,40),(25,0,0,43),(26,100,0,49),(27,2000,0,55),(28,0,0,59),(29,5,0,62),(30,10,0,65),(31,50,0,73),(32,50,0,78),(33,0,0,82),(34,5,0,89),(35,100,0,90),(36,100,0,92),(37,1000,0,93),(38,500,0,95),(39,50,0,96),(40,0,0,97),(41,0,0,100),(42,0,0,103),(43,0,0,106),(44,0,0,109),(45,0,0,112),(46,0,0,117),(47,0,0,125),(48,100,0,137),(49,1000,0,138),(50,1,0,145),(51,0,0,147),(52,0,0,152),(53,100,0,159),(54,100,0,161),(55,0,0,163),(56,250,0,164),(57,50,0,165),(58,100,0,169),(59,0,0,177),(60,10,0,182),(61,0,0,183),(62,0,0,186),(63,100,0,189),(64,1,0,190),(65,0,0,191),(66,100,0,195),(67,120,0,200),(68,10,0,201),(69,20,0,204),(70,20,0,208),(71,11,0,211),(72,0,0,215),(73,100,0,218),(74,35,0,221),(75,35,0,224),(76,100,0,227),(77,15,0,230),(78,15,0,231),(79,50,0,235),(80,10,0,236),(81,20,0,239),(82,10,0,242),(83,50,0,246),(84,50,0,251),(85,60,0,254),(86,12,0,256),(87,9,0,257),(88,11,0,258),(89,15,0,259),(90,14,0,260),(91,60,0,261),(92,23,0,264),(93,23,0,271),(94,15,0,272),(95,8,0,273),(96,17,0,275),(97,17,0,278),(98,48,0,281),(99,144,43,287),(100,144,43,292),(101,144,43,295),(102,144,43,300),(103,144,43,303),(104,288,86,308),(105,96,28,312),(106,144,43,313),(107,143,43,314),(108,144,44,315),(109,145,44,316),(110,143,43,317),(111,144,43,318),(112,0,0,319),(113,1,0,322),(114,33,0,323),(115,22,0,324),(116,13,0,325),(117,11,0,326),(118,33,0,327);
/*!40000 ALTER TABLE `amount_gbp` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `amount_percentage`
--

DROP TABLE IF EXISTS `amount_percentage`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `amount_percentage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `amount` int(11) NOT NULL,
  `percentage` double NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `amount_percentage_UN` (`amount`),
  CONSTRAINT `amount_percentage_FK` FOREIGN KEY (`amount`) REFERENCES `amount` (`id`),
  CONSTRAINT `amount_percentage_CHECK` CHECK (`percentage` >= 0 and `percentage` <= 100)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `amount_percentage`
--

LOCK TABLES `amount_percentage` WRITE;
/*!40000 ALTER TABLE `amount_percentage` DISABLE KEYS */;
INSERT INTO `amount_percentage` VALUES (1,63,50),(2,66,100),(3,69,75),(4,70,69),(5,71,60),(6,74,65),(7,75,80),(8,150,0),(9,151,0),(10,228,50),(11,234,50);
/*!40000 ALTER TABLE `amount_percentage` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `amount_share`
--

DROP TABLE IF EXISTS `amount_share`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `amount_share` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `amount` int(11) NOT NULL,
  `share` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `amount_share_UN` (`amount`),
  CONSTRAINT `amount_share_FK` FOREIGN KEY (`amount`) REFERENCES `amount` (`id`),
  CONSTRAINT `amount_share_CHECK` CHECK (`share` >= 0)
) ENGINE=InnoDB AUTO_INCREMENT=195 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `amount_share`
--

LOCK TABLES `amount_share` WRITE;
/*!40000 ALTER TABLE `amount_share` DISABLE KEYS */;
INSERT INTO `amount_share` VALUES (5,25,1),(6,26,1),(7,29,1),(8,30,1),(9,32,1),(10,33,1),(11,35,1),(12,36,1),(13,38,1),(14,39,1),(15,41,1),(16,42,1),(17,44,1),(18,45,1),(19,46,1),(20,47,1),(21,48,1),(22,50,1),(23,51,1),(24,52,1),(25,53,1),(26,54,1),(27,56,1),(28,57,1),(29,60,1),(30,61,1),(31,64,1),(32,67,1),(33,68,12),(34,76,1),(35,77,1),(36,79,1),(37,80,1),(38,81,1),(39,83,1),(40,84,1),(41,85,1),(42,86,1),(43,87,1),(44,88,1),(45,91,1),(46,94,1),(47,98,1),(48,99,1),(49,101,1),(50,102,1),(51,104,1),(52,105,1),(53,107,1),(54,108,1),(55,110,1),(56,111,1),(57,113,1),(58,114,1),(59,115,1),(60,116,1),(61,118,1),(62,119,1),(63,120,1),(64,121,1),(65,122,1),(66,123,1),(67,124,1),(68,126,1),(69,127,1),(70,128,1),(71,129,1),(72,130,1),(73,131,1),(74,132,1),(75,133,1),(76,134,1),(77,135,1),(78,136,1),(79,139,1),(80,140,1),(81,141,1),(82,142,2),(83,143,1),(84,144,1),(85,146,1),(86,148,1),(87,149,1),(88,153,1),(89,154,1),(90,155,1),(91,156,1),(92,157,1),(93,158,1),(94,160,1),(95,162,1),(96,166,12),(97,167,12),(98,168,1),(99,170,1),(100,171,1),(101,172,1),(102,173,1),(103,174,1),(104,175,1),(105,176,1),(106,178,1),(107,179,1),(108,180,2),(109,181,1),(110,184,1),(111,185,1),(112,187,1),(113,188,1),(114,192,1),(115,193,1),(116,194,1),(117,196,1),(118,197,1),(119,198,1),(120,199,1),(121,202,1),(122,203,1),(123,205,1),(124,206,1),(125,207,1),(126,209,1),(127,210,1),(128,212,1),(129,213,1),(130,214,1),(131,216,1),(132,217,1),(133,219,1),(134,220,1),(135,222,1),(136,223,1),(137,225,1),(138,226,1),(139,229,1),(140,232,1),(141,233,1),(142,237,11),(143,238,1),(144,240,1),(145,241,1),(146,243,1),(147,244,1),(148,245,1),(149,247,1),(150,248,1),(151,249,1),(152,250,1),(153,252,1),(154,253,1),(155,255,1),(156,262,1),(157,263,1),(158,265,1),(159,266,1),(160,267,1),(161,268,1),(162,269,1),(163,270,1),(164,274,1),(165,276,1),(166,277,1),(167,279,1),(168,280,1),(169,282,1),(170,283,1),(171,284,1),(172,285,1),(173,286,1),(174,288,1),(175,289,1),(176,290,1),(177,291,1),(178,293,1),(179,294,1),(180,296,1),(181,297,1),(182,298,1),(183,299,1),(184,301,1),(185,302,1),(186,304,1),(187,305,1),(188,306,1),(189,307,1),(190,309,1),(191,310,1),(192,311,1),(193,320,1),(194,321,1);
/*!40000 ALTER TABLE `amount_share` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `amount_usd`
--

DROP TABLE IF EXISTS `amount_usd`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `amount_usd` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dollars` int(11) NOT NULL,
  `cents` int(11) NOT NULL DEFAULT 0,
  `amount` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `amount_gbp_UN` (`amount`),
  KEY `amount_gbp_FK` (`amount`) USING BTREE,
  CONSTRAINT `amount_gbp_FK_copy` FOREIGN KEY (`amount`) REFERENCES `amount` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `amount_usd`
--

LOCK TABLES `amount_usd` WRITE;
/*!40000 ALTER TABLE `amount_usd` DISABLE KEYS */;
/*!40000 ALTER TABLE `amount_usd` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `currency_preference`
--

DROP TABLE IF EXISTS `currency_preference`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `currency_preference` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `currency` enum('gbp') NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `currency_preference_UN` (`user_id`),
  CONSTRAINT `currency_preference_FK` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `currency_preference`
--

LOCK TABLES `currency_preference` WRITE;
/*!40000 ALTER TABLE `currency_preference` DISABLE KEYS */;
/*!40000 ALTER TABLE `currency_preference` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dummy_entity`
--

DROP TABLE IF EXISTS `dummy_entity`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dummy_entity` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entity_id` int(11) NOT NULL,
  `owner_user_id` bigint(20) unsigned NOT NULL,
  `real_entity_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `dummy_entity_UN` (`entity_id`),
  KEY `dummy_entity_FK_1` (`owner_user_id`),
  KEY `dummy_entity_real_entity_id` (`real_entity_id`),
  CONSTRAINT `dummy_entity_FK` FOREIGN KEY (`entity_id`) REFERENCES `entity` (`id`),
  CONSTRAINT `dummy_entity_FK_1` FOREIGN KEY (`owner_user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `dummy_entity_real_entity_id` FOREIGN KEY (`real_entity_id`) REFERENCES `entity` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=87 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dummy_entity`
--

LOCK TABLES `dummy_entity` WRITE;
/*!40000 ALTER TABLE `dummy_entity` DISABLE KEYS */;
INSERT INTO `dummy_entity` VALUES (35,38,2,NULL),(62,70,5,NULL),(63,71,5,42),(64,72,5,NULL),(74,87,1,NULL),(75,88,1,NULL),(80,97,14,NULL),(81,98,14,NULL),(83,102,14,NULL),(84,103,14,NULL),(85,104,14,NULL),(86,105,14,NULL);
/*!40000 ALTER TABLE `dummy_entity` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `entity`
--

DROP TABLE IF EXISTS `entity`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `entity` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=106 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `entity`
--

LOCK TABLES `entity` WRITE;
/*!40000 ALTER TABLE `entity` DISABLE KEYS */;
INSERT INTO `entity` VALUES (1),(2),(3),(4),(5),(6),(7),(8),(9),(10),(11),(12),(13),(14),(15),(16),(17),(18),(19),(20),(21),(22),(23),(24),(25),(26),(27),(28),(29),(30),(31),(32),(33),(34),(35),(36),(38),(41),(42),(43),(44),(45),(46),(47),(48),(49),(50),(51),(52),(53),(54),(55),(56),(57),(58),(59),(60),(61),(62),(63),(64),(65),(66),(67),(68),(69),(70),(71),(72),(73),(74),(75),(76),(77),(78),(79),(80),(81),(82),(83),(84),(85),(86),(87),(88),(89),(90),(91),(92),(93),(94),(95),(96),(97),(98),(99),(100),(101),(102),(103),(104),(105);
/*!40000 ALTER TABLE `entity` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `entity_name`
--

DROP TABLE IF EXISTS `entity_name`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `entity_name` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` int(11) NOT NULL,
  `entity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `entity_name_FK` (`name`),
  KEY `entity_name_FK_1` (`entity`),
  CONSTRAINT `entity_name_FK` FOREIGN KEY (`name`) REFERENCES `name` (`id`),
  CONSTRAINT `entity_name_FK_1` FOREIGN KEY (`entity`) REFERENCES `entity` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=104 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `entity_name`
--

LOCK TABLES `entity_name` WRITE;
/*!40000 ALTER TABLE `entity_name` DISABLE KEYS */;
INSERT INTO `entity_name` VALUES (1,1,1),(2,2,2),(3,3,3),(4,4,4),(5,5,5),(6,6,6),(7,7,7),(8,8,8),(9,9,9),(10,10,10),(11,11,11),(12,12,12),(13,13,13),(14,14,14),(15,15,15),(16,16,16),(17,17,17),(18,18,18),(19,19,19),(20,20,20),(21,21,21),(22,22,22),(23,23,23),(24,24,24),(25,25,25),(26,26,26),(27,27,27),(28,28,28),(29,29,29),(30,30,30),(31,31,31),(32,32,32),(33,33,33),(34,34,34),(35,35,35),(36,36,36),(38,38,38),(39,40,41),(40,1,42),(41,3,43),(42,41,44),(43,42,45),(44,43,46),(45,44,47),(46,45,48),(47,46,49),(48,47,50),(49,48,51),(50,49,52),(51,50,53),(52,51,54),(53,52,55),(54,53,56),(55,54,57),(56,55,58),(57,56,59),(58,57,60),(59,58,61),(60,59,62),(61,60,63),(62,61,64),(63,62,65),(64,63,66),(65,64,67),(66,65,68),(67,66,69),(68,67,70),(69,68,71),(70,69,72),(71,70,73),(72,71,74),(73,72,75),(74,73,76),(75,74,77),(76,75,78),(77,76,79),(78,77,80),(79,78,81),(80,79,82),(81,80,83),(82,81,84),(83,82,85),(84,83,86),(85,84,87),(86,85,88),(87,86,89),(88,87,90),(89,88,91),(90,89,92),(91,90,93),(92,91,94),(93,92,95),(94,93,96),(95,94,97),(96,95,98),(97,96,99),(98,97,100),(99,98,101),(100,99,102),(101,100,103),(102,101,104),(103,102,105);
/*!40000 ALTER TABLE `entity_name` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `linked_users`
--

DROP TABLE IF EXISTS `linked_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `linked_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_a` bigint(20) unsigned NOT NULL,
  `user_b` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `linked_users_user_a` (`user_a`),
  KEY `linked_users_user_b` (`user_b`),
  CONSTRAINT `linked_users_user_a` FOREIGN KEY (`user_a`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `linked_users_user_b` FOREIGN KEY (`user_b`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `linked_users`
--

LOCK TABLES `linked_users` WRITE;
/*!40000 ALTER TABLE `linked_users` DISABLE KEYS */;
INSERT INTO `linked_users` VALUES (26,'2023-05-15 18:00:04',2,1),(27,'2023-05-16 11:02:20',14,15),(28,'2023-05-16 11:03:34',14,16);
/*!40000 ALTER TABLE `linked_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `linking_uris`
--

DROP TABLE IF EXISTS `linking_uris`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `linking_uris` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `expires` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `target_user` bigint(20) unsigned NOT NULL,
  `uri` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `linking_uris_target_user_unique` (`target_user`),
  CONSTRAINT `linking_uris_target_user` FOREIGN KEY (`target_user`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `linking_uris`
--

LOCK TABLES `linking_uris` WRITE;
/*!40000 ALTER TABLE `linking_uris` DISABLE KEYS */;
/*!40000 ALTER TABLE `linking_uris` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_resets_table',1),(3,'2014_10_12_200000_add_two_factor_columns_to_users_table',1),(4,'2019_08_19_000000_create_failed_jobs_table',1),(5,'2019_12_14_000001_create_personal_access_tokens_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `name`
--

DROP TABLE IF EXISTS `name`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `name` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=103 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `name`
--

LOCK TABLES `name` WRITE;
/*!40000 ALTER TABLE `name` DISABLE KEYS */;
INSERT INTO `name` VALUES (1,'Adam'),(2,'Elena'),(3,'Tim'),(4,'Beef'),(5,'Oh yes'),(6,'Boof'),(7,'I\'mm cluttering up my db'),(8,'Ok good going'),(9,'Come on man'),(10,'what'),(11,'um'),(12,'lkjlkj'),(13,'lkjlkj'),(14,'lkjlkj'),(15,'iuh'),(16,'what'),(17,'fuckn'),(18,'god'),(19,'yeah'),(20,'boo'),(21,'dsasdas'),(22,'iwuh'),(23,'asdasd'),(24,'asdasd'),(25,'asdasd'),(26,'asd'),(27,'asdasd'),(28,'eiquey'),(29,'ok cool'),(30,'why slow'),(31,'why so serious'),(32,'oh wait is it client?'),(33,'oh wait is it client?'),(34,'no'),(35,'it is still slow poo'),(36,'boo'),(38,'yeah'),(40,'Bob'),(41,'thingy'),(42,'horse'),(43,'fuckin ace'),(44,'what'),(45,'whattt'),(46,'whattt'),(47,'boyu'),(48,'boyu'),(49,'boyu'),(50,'boyu'),(51,'thing'),(52,'ok'),(53,'wut'),(54,'fuckin ace'),(55,'yeah'),(56,'cool beans'),(57,'i thought fillable stopped this but whatever'),(58,'ok is it fast now'),(59,'moderately so'),(60,'horse'),(61,'Poppy'),(62,'banana'),(63,'banana'),(64,'banana'),(65,'boo'),(66,'horse'),(67,'banana'),(68,'strawberry'),(69,'raisin'),(70,'Kim'),(71,'thing'),(72,'asd'),(73,'boobs'),(74,'hoof'),(75,'Peter'),(76,'tina'),(77,'water company'),(78,'ariel'),(79,'E'),(80,'e'),(81,'1'),(82,'Phil'),(83,'Test Friend'),(84,'blah'),(85,'Cat'),(86,'Catherine'),(87,''),(88,'Tina'),(89,'Phone company'),(90,'Water company'),(91,'Sam Lai'),(92,'Test'),(93,'Demo'),(94,'Vladimir'),(95,'Estragon'),(96,'Ibid'),(97,'Anon'),(98,'Petrol static'),(99,'Petrol station'),(100,'Tesco'),(101,'Pub'),(102,'Water company');
/*!40000 ALTER TABLE `name` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settlement`
--

DROP TABLE IF EXISTS `settlement`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settlement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `owner_id` bigint(20) unsigned NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `settlement_FK` (`owner_id`),
  CONSTRAINT `settlement_FK` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settlement`
--

LOCK TABLES `settlement` WRITE;
/*!40000 ALTER TABLE `settlement` DISABLE KEYS */;
INSERT INTO `settlement` VALUES (1,'Water','2023-05-14 14:16:36','2023-05-14 14:16:36',1,NULL),(2,'yes ples','2023-04-23 09:53:31','2023-04-23 09:53:31',1,'2023-04-23 09:53:31'),(3,'yes ples','2023-04-23 09:53:23','2023-04-23 09:53:23',1,'2023-04-23 09:53:23'),(4,'sure?','2023-04-23 08:21:19','2023-04-23 08:21:19',1,'2023-04-23 08:21:19'),(5,'fuck','2023-04-23 09:53:44','2023-04-23 09:53:44',1,'2023-04-23 09:53:44'),(6,'uyguyg','2023-04-23 10:32:26','2023-04-23 10:32:26',1,'2023-04-23 10:32:26'),(7,'yey','2023-04-24 20:05:51','2023-04-24 20:05:51',1,'2023-04-24 20:05:51'),(8,'fish','2023-05-12 13:13:23','2023-05-12 13:13:23',1,'2023-05-12 13:13:23'),(9,'adma','2023-05-08 18:49:58','2023-05-08 18:49:58',6,'2023-05-08 18:49:58'),(10,'beef','2023-05-12 12:31:48','2023-05-12 12:31:48',1,NULL),(11,'pls','2023-05-14 17:44:11','2023-05-14 17:44:11',6,'2023-05-14 17:44:11'),(12,'thing','2023-05-14 15:57:45','2023-05-14 15:57:45',2,NULL),(16,'Hamilton','2023-05-15 14:36:40','2023-05-15 14:36:40',1,NULL),(25,'Walk in the Brecons','2023-05-16 11:27:05','2023-05-16 11:27:05',14,NULL),(26,'Water','2023-05-16 14:41:28','2023-05-16 14:41:28',15,NULL);
/*!40000 ALTER TABLE `settlement` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settlement_admins`
--

DROP TABLE IF EXISTS `settlement_admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settlement_admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `settlement_id` int(11) NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `settlement_admins_FK` (`settlement_id`),
  KEY `settlement_admins_FK_1` (`user_id`),
  CONSTRAINT `settlement_admins_FK` FOREIGN KEY (`settlement_id`) REFERENCES `settlement` (`id`),
  CONSTRAINT `settlement_admins_FK_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=117 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settlement_admins`
--

LOCK TABLES `settlement_admins` WRITE;
/*!40000 ALTER TABLE `settlement_admins` DISABLE KEYS */;
INSERT INTO `settlement_admins` VALUES (58,3,1),(59,4,1),(60,5,1),(62,6,1),(64,7,1),(85,8,1),(88,9,6),(89,10,1),(96,11,6),(100,1,1),(101,1,5),(102,12,2),(106,16,1),(115,25,14),(116,26,14);
/*!40000 ALTER TABLE `settlement_admins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settlement_participant`
--

DROP TABLE IF EXISTS `settlement_participant`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settlement_participant` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `settlement_id` int(11) NOT NULL,
  `entity_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settlement_participant_UN` (`settlement_id`,`entity_id`),
  KEY `settlement_participant_FK` (`settlement_id`),
  KEY `settlement_participant_FK_1` (`entity_id`),
  CONSTRAINT `settlement_participant_FK` FOREIGN KEY (`settlement_id`) REFERENCES `settlement` (`id`),
  CONSTRAINT `settlement_participant_FK_1` FOREIGN KEY (`entity_id`) REFERENCES `entity` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settlement_participant`
--

LOCK TABLES `settlement_participant` WRITE;
/*!40000 ALTER TABLE `settlement_participant` DISABLE KEYS */;
INSERT INTO `settlement_participant` VALUES (1,1,41),(3,1,42),(2,1,43);
/*!40000 ALTER TABLE `settlement_participant` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settlement_transactions`
--

DROP TABLE IF EXISTS `settlement_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settlement_transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `settlement_id` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settlement_transactions_UN` (`transaction_id`),
  KEY `settlement_transactions_FK` (`settlement_id`),
  CONSTRAINT `settlement_transactions_FK` FOREIGN KEY (`settlement_id`) REFERENCES `settlement` (`id`),
  CONSTRAINT `settlement_transactions_FK_1` FOREIGN KEY (`transaction_id`) REFERENCES `transaction` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settlement_transactions`
--

LOCK TABLES `settlement_transactions` WRITE;
/*!40000 ALTER TABLE `settlement_transactions` DISABLE KEYS */;
INSERT INTO `settlement_transactions` VALUES (1,1,2),(2,1,3),(4,1,10),(5,1,11),(6,1,12),(7,1,13),(8,1,14),(9,1,15),(10,1,16),(11,1,18),(12,1,19),(13,1,20),(14,1,21),(15,1,22),(16,1,23),(17,1,24),(18,1,25),(19,1,26),(20,1,27),(21,1,28),(22,1,29),(23,1,30),(24,1,31),(25,11,32),(26,11,33),(27,11,34),(28,12,35),(30,1,37),(33,1,40),(34,1,41),(35,16,42),(36,16,43),(42,25,49),(43,25,50),(44,25,51),(45,25,52),(46,25,53),(47,25,54),(48,25,55),(49,25,56),(50,25,57),(51,26,58),(52,26,59),(53,26,60),(54,26,61),(55,26,62),(56,26,63),(57,26,64);
/*!40000 ALTER TABLE `settlement_transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaction`
--

DROP TABLE IF EXISTS `transaction`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transaction` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `evidence_link` varchar(100) DEFAULT NULL,
  `type` enum('payment','service') NOT NULL,
  `amount_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `outlay` tinyint(1) NOT NULL DEFAULT 0,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `owner_id` bigint(20) unsigned NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `description` varchar(10000) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `transaction_FK` (`amount_id`),
  KEY `transaction_FK_1` (`owner_id`),
  CONSTRAINT `transaction_FK` FOREIGN KEY (`amount_id`) REFERENCES `amount` (`id`),
  CONSTRAINT `transaction_FK_1` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaction`
--

LOCK TABLES `transaction` WRITE;
/*!40000 ALTER TABLE `transaction` DISABLE KEYS */;
INSERT INTO `transaction` VALUES (2,NULL,'service',2,'2023-04-23 15:20:30',1,'2023-05-06 22:17:18',1,NULL,''),(3,NULL,'payment',96,'2023-04-23 15:21:32',0,'2023-05-12 10:28:37',1,NULL,''),(7,NULL,'payment',24,'2023-05-07 13:54:33',0,'2023-05-07 13:54:33',1,NULL,''),(9,NULL,'payment',28,'2023-05-07 13:59:57',0,'2023-05-07 13:59:57',1,NULL,''),(10,NULL,'payment',31,'2023-05-07 16:56:15',0,'2023-05-12 10:04:33',1,'2023-05-12 10:04:33',''),(11,NULL,'payment',34,'2023-05-08 13:45:20',0,'2023-05-12 10:04:19',1,'2023-05-12 10:04:19',''),(12,NULL,'payment',37,'2023-05-08 13:51:07',0,'2023-05-12 10:04:16',1,'2023-05-12 10:04:16',''),(13,NULL,'service',40,'2023-05-08 17:18:21',0,'2023-05-12 10:17:45',1,'2023-05-12 10:17:45',''),(14,NULL,'service',43,'2023-05-08 17:21:00',0,'2023-05-12 10:17:54',1,'2023-05-12 10:17:54',''),(15,NULL,'payment',49,'2023-05-08 17:57:02',0,'2023-05-12 10:18:03',1,'2023-05-12 10:18:03',''),(16,NULL,'service',55,'2023-05-08 18:32:17',0,'2023-05-12 10:04:11',1,'2023-05-12 10:04:11',''),(18,NULL,'payment',59,'2023-05-10 12:06:42',0,'2023-05-12 10:03:11',1,'2023-05-12 10:03:11',''),(19,NULL,'service',73,'2023-05-10 12:08:31',0,'2023-05-12 10:18:06',1,'2023-05-12 10:18:06',''),(20,NULL,'payment',82,'2023-05-11 20:23:14',0,'2023-05-12 10:02:29',1,'2023-05-12 10:02:29',''),(21,NULL,'service',93,'2023-05-12 10:20:24',0,'2023-05-12 10:23:18',1,'2023-05-12 10:23:18',''),(22,NULL,'payment',97,'2023-05-12 11:29:28',0,'2023-05-12 11:29:28',1,NULL,''),(23,NULL,'payment',100,'2023-05-12 13:08:49',0,'2023-05-12 13:12:33',1,'2023-05-12 13:12:33',''),(24,NULL,'payment',103,'2023-05-12 13:10:20',0,'2023-05-12 13:12:30',1,'2023-05-12 13:12:30',''),(25,NULL,'payment',106,'2023-05-12 13:11:58',0,'2023-05-12 13:12:27',1,'2023-05-12 13:12:27',''),(26,NULL,'payment',109,'2023-05-12 13:13:11',0,'2023-05-12 13:13:15',1,'2023-05-12 13:13:15',''),(27,NULL,'payment',112,'2023-05-12 13:13:55',0,'2023-05-12 13:13:55',1,NULL,''),(28,NULL,'payment',189,'2023-05-12 13:15:18',0,'2023-05-15 10:38:12',1,NULL,'ddddd'),(29,NULL,'payment',169,'2023-05-12 13:15:47',0,'2023-05-14 14:27:18',1,'2023-05-14 14:27:18',''),(30,NULL,'payment',147,'2023-05-13 11:00:02',0,'2023-05-13 11:26:55',5,'2023-05-13 11:26:55',''),(31,NULL,'service',152,'2023-05-13 11:43:22',0,'2023-05-13 11:43:35',5,'2023-05-13 11:43:35',''),(32,NULL,'payment',177,'2023-05-13 16:00:42',0,'2023-05-14 12:50:23',6,NULL,''),(33,NULL,'service',183,'2023-05-14 13:08:33',0,'2023-05-14 13:08:33',1,NULL,''),(34,NULL,'payment',186,'2023-05-14 13:09:05',0,'2023-05-14 13:09:05',1,NULL,''),(35,NULL,'payment',191,'2023-05-14 16:35:30',0,'2023-05-14 16:35:30',2,NULL,''),(37,NULL,'payment',201,'2023-05-14 18:13:01',0,'2023-05-14 18:13:01',1,NULL,''),(40,NULL,'service',215,'2023-05-15 10:38:27',0,'2023-05-15 10:40:48',1,'2023-05-15 10:40:48','water'),(41,NULL,'service',218,'2023-05-15 13:13:00',0,'2023-05-15 13:13:16',1,NULL,'pants wash'),(42,NULL,'service',221,'2023-05-15 14:39:31',0,'2023-05-15 14:39:31',1,NULL,'Buying the ticket'),(43,NULL,'payment',224,'2023-05-15 14:39:52',0,'2023-05-15 14:39:52',1,NULL,'Me paying back'),(49,NULL,'service',246,'2023-05-16 11:30:41',0,'2023-05-16 11:30:41',14,NULL,'Petrol for trip'),(50,NULL,'payment',251,'2023-05-16 11:31:54',0,'2023-05-16 11:31:54',14,NULL,'Paying for petrol'),(51,NULL,'service',254,'2023-05-16 11:37:38',0,'2023-05-16 11:37:38',14,NULL,'Lunch'),(52,NULL,'payment',261,'2023-05-16 11:43:36',0,'2023-05-16 11:43:36',14,NULL,'Paying for lunch'),(53,NULL,'service',327,'2023-05-16 11:47:58',0,'2023-05-16 14:37:44',14,NULL,'Round + chips'),(54,NULL,'payment',323,'2023-05-16 11:50:00',0,'2023-05-16 14:36:49',14,NULL,'Paying for round + chips'),(55,NULL,'payment',275,'2023-05-16 11:55:14',0,'2023-05-16 11:55:14',14,NULL,'Paying back for petrol'),(56,NULL,'payment',278,'2023-05-16 11:55:47',0,'2023-05-16 11:55:47',14,NULL,'Paying back for petrol'),(57,NULL,'payment',281,'2023-05-16 11:58:37',0,'2023-05-16 11:58:37',14,NULL,'Paying back for lunch'),(58,NULL,'service',287,'2023-05-16 12:08:54',0,'2023-05-16 12:08:54',14,NULL,'Water'),(59,NULL,'payment',292,'2023-05-16 12:09:19',0,'2023-05-16 12:09:19',14,NULL,'Standing order'),(60,NULL,'service',295,'2023-05-16 12:10:15',0,'2023-05-16 12:10:15',14,NULL,'Water'),(61,NULL,'payment',300,'2023-05-16 12:10:35',0,'2023-05-16 12:10:35',14,NULL,'Standing order'),(62,NULL,'service',303,'2023-05-16 12:11:15',0,'2023-05-16 12:11:15',14,NULL,'Water'),(63,NULL,'payment',318,'2023-05-16 12:12:00',0,'2023-05-16 12:48:51',14,NULL,''),(64,NULL,'service',322,'2023-05-16 14:16:59',0,'2023-05-16 14:42:03',14,'2023-05-16 14:42:03','blah');
/*!40000 ALTER TABLE `transaction` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaction_allocation`
--

DROP TABLE IF EXISTS `transaction_allocation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transaction_allocation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transaction` int(11) NOT NULL,
  `settlement` int(11) DEFAULT NULL,
  `amount` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `transaction_allocation_FK` (`transaction`),
  KEY `transaction_allocation_FK_1` (`settlement`),
  KEY `transaction_allocation_FK_2` (`amount`),
  CONSTRAINT `transaction_allocation_FK` FOREIGN KEY (`transaction`) REFERENCES `transaction` (`id`),
  CONSTRAINT `transaction_allocation_FK_1` FOREIGN KEY (`settlement`) REFERENCES `settlement` (`id`),
  CONSTRAINT `transaction_allocation_FK_2` FOREIGN KEY (`amount`) REFERENCES `amount` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaction_allocation`
--

LOCK TABLES `transaction_allocation` WRITE;
/*!40000 ALTER TABLE `transaction_allocation` DISABLE KEYS */;
/*!40000 ALTER TABLE `transaction_allocation` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`192.168.178.%`*/ /*!50003 TRIGGER participants_in_settlement
BEFORE INSERT
ON transaction_allocation FOR EACH ROW
BEGIN 
	IF NOT EXISTS (
	SELECT * FROM settlement_participant as A join settlement_participant as B 
	on A.settlement = NEW.settlement and B.settlement = NEW.settlement
	join `transaction` as T on NEW.`transaction` = T.id
	where A.entity = T.`from` and B.entity = T.`to`
	) THEN SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Part of transaction allocated to settlement which doesn't involve both parties to the transaction.";
end if;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `transaction_from`
--

DROP TABLE IF EXISTS `transaction_from`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transaction_from` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entity_id` int(11) NOT NULL,
  `amount_id` int(11) NOT NULL DEFAULT 1,
  `transaction_id` int(11) NOT NULL,
  `deleted_at` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transaction_from_FK` (`entity_id`),
  KEY `transaction_from_FK_1` (`amount_id`),
  KEY `transaction_from_FK_2` (`transaction_id`),
  CONSTRAINT `transaction_from_FK` FOREIGN KEY (`entity_id`) REFERENCES `entity` (`id`),
  CONSTRAINT `transaction_from_FK_1` FOREIGN KEY (`amount_id`) REFERENCES `amount` (`id`),
  CONSTRAINT `transaction_from_FK_2` FOREIGN KEY (`transaction_id`) REFERENCES `transaction` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaction_from`
--

LOCK TABLES `transaction_from` WRITE;
/*!40000 ALTER TABLE `transaction_from` DISABLE KEYS */;
INSERT INTO `transaction_from` VALUES (1,41,4,2,NULL),(2,43,5,3,NULL),(6,42,25,7,NULL),(7,41,29,9,NULL),(8,42,32,10,NULL),(9,43,35,11,NULL),(10,43,38,12,NULL),(11,43,41,13,NULL),(12,42,44,14,NULL),(13,41,45,14,NULL),(14,42,50,15,NULL),(15,43,51,15,NULL),(16,42,56,16,NULL),(17,42,60,18,NULL),(18,41,74,19,NULL),(19,42,68,19,NULL),(20,43,65,19,NULL),(21,41,79,19,'2023-05-11 20:17:27'),(22,42,80,19,'2023-05-11 20:17:44'),(23,42,89,20,NULL),(24,43,91,21,NULL),(25,42,98,22,NULL),(26,43,101,23,NULL),(27,42,104,24,NULL),(28,43,107,25,NULL),(29,42,110,26,NULL),(30,41,113,27,NULL),(31,42,114,27,NULL),(32,43,115,27,NULL),(33,42,118,28,NULL),(34,41,119,28,NULL),(35,42,120,28,NULL),(36,41,121,28,NULL),(37,43,122,28,NULL),(38,41,123,28,NULL),(39,41,146,29,NULL),(40,42,127,29,NULL),(41,43,128,29,'2023-05-12 19:52:43'),(42,42,129,29,'2023-05-12 19:52:10'),(43,42,130,29,'2023-05-13 09:25:21'),(44,43,131,29,'2023-05-12 18:07:22'),(45,42,132,29,NULL),(46,41,133,29,'2023-05-13 09:59:37'),(47,42,134,29,'2023-05-12 19:52:22'),(48,43,135,29,'2023-05-12 18:07:22'),(49,58,174,29,NULL),(50,31,170,29,NULL),(51,59,173,29,NULL),(52,43,176,29,NULL),(53,41,151,30,NULL),(54,42,153,31,NULL),(55,41,155,31,NULL),(56,41,158,29,NULL),(57,73,178,32,NULL),(58,73,184,33,NULL),(59,42,187,34,NULL),(60,75,194,35,NULL),(62,78,202,37,NULL),(63,78,204,37,NULL),(64,78,205,37,NULL),(65,31,206,37,'2023-05-14 20:43:37'),(66,65,207,37,'2023-05-14 20:43:56'),(69,43,216,40,NULL),(70,41,219,41,NULL),(71,88,222,42,NULL),(72,42,225,43,NULL),(79,43,245,37,'2023-05-15 18:11:31'),(80,102,247,49,NULL),(81,96,252,50,NULL),(82,103,255,51,NULL),(83,97,262,52,NULL),(84,104,265,53,NULL),(85,99,324,54,NULL),(86,97,326,54,NULL),(87,99,276,55,NULL),(88,100,279,56,NULL),(89,96,282,57,NULL),(90,99,283,57,NULL),(91,100,284,57,NULL),(92,98,285,57,NULL),(93,105,288,58,NULL),(94,99,293,59,NULL),(95,105,296,60,NULL),(96,99,301,61,NULL),(97,105,304,62,NULL),(98,100,309,63,NULL),(99,97,320,64,NULL);
/*!40000 ALTER TABLE `transaction_from` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaction_to`
--

DROP TABLE IF EXISTS `transaction_to`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transaction_to` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entity_id` int(11) NOT NULL,
  `amount_id` int(11) NOT NULL DEFAULT 1,
  `transaction_id` int(11) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transaction_to_FK` (`entity_id`),
  KEY `transaction_to_FK_1` (`amount_id`),
  KEY `transaction_to_FK_2` (`transaction_id`),
  CONSTRAINT `transaction_to_FK` FOREIGN KEY (`entity_id`) REFERENCES `entity` (`id`),
  CONSTRAINT `transaction_to_FK_1` FOREIGN KEY (`amount_id`) REFERENCES `amount` (`id`),
  CONSTRAINT `transaction_to_FK_2` FOREIGN KEY (`transaction_id`) REFERENCES `transaction` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=89 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaction_to`
--

LOCK TABLES `transaction_to` WRITE;
/*!40000 ALTER TABLE `transaction_to` DISABLE KEYS */;
INSERT INTO `transaction_to` VALUES (1,42,6,2,NULL),(2,43,7,2,NULL),(3,41,8,3,NULL),(5,41,26,7,NULL),(6,43,30,9,NULL),(7,41,33,10,NULL),(8,41,36,11,NULL),(9,41,39,12,NULL),(10,43,42,13,NULL),(11,41,46,14,NULL),(12,42,47,14,NULL),(13,43,48,14,NULL),(14,41,52,15,NULL),(15,42,53,15,NULL),(16,43,54,15,NULL),(17,41,57,16,NULL),(18,43,61,18,NULL),(19,42,75,19,NULL),(20,41,76,19,NULL),(21,43,77,19,'2023-05-11 20:16:53'),(22,43,78,19,NULL),(23,42,81,19,'2023-05-11 20:17:44'),(24,43,84,20,NULL),(25,42,94,21,NULL),(26,43,99,22,NULL),(27,41,102,23,NULL),(28,43,105,24,NULL),(29,41,108,25,NULL),(30,43,111,26,NULL),(31,41,116,27,NULL),(32,43,190,28,NULL),(33,42,163,29,NULL),(34,43,149,30,NULL),(35,43,154,31,NULL),(36,42,182,32,NULL),(37,73,185,33,NULL),(38,42,188,34,NULL),(39,43,193,35,NULL),(43,41,203,37,NULL),(47,42,217,40,NULL),(48,78,220,41,NULL),(49,42,223,42,NULL),(50,88,226,43,NULL),(57,96,248,49,NULL),(58,99,249,49,NULL),(59,100,250,49,NULL),(60,102,253,50,NULL),(61,96,256,51,NULL),(62,99,257,51,NULL),(63,100,258,51,NULL),(64,97,259,51,NULL),(65,98,260,51,NULL),(66,103,263,52,NULL),(67,99,266,53,NULL),(68,100,267,53,NULL),(69,97,268,53,NULL),(70,98,269,53,NULL),(71,96,270,53,NULL),(72,104,274,54,NULL),(73,96,277,55,NULL),(74,96,280,56,NULL),(75,97,286,57,NULL),(76,96,289,58,NULL),(77,99,290,58,NULL),(78,100,291,58,NULL),(79,105,294,59,NULL),(80,99,297,60,NULL),(81,96,298,60,NULL),(82,100,299,60,NULL),(83,105,302,61,NULL),(84,96,305,62,NULL),(85,99,306,62,NULL),(86,100,307,62,NULL),(87,99,311,63,NULL),(88,99,321,64,NULL);
/*!40000 ALTER TABLE `transaction_to` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_entity`
--

DROP TABLE IF EXISTS `user_entity`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_entity` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` bigint(20) unsigned NOT NULL,
  `entity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_entity_UN` (`user`),
  UNIQUE KEY `user_entity_UN1` (`entity`),
  CONSTRAINT `user_entity_FK` FOREIGN KEY (`user`) REFERENCES `users` (`id`),
  CONSTRAINT `user_entity_FK_1` FOREIGN KEY (`entity`) REFERENCES `entity` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_entity`
--

LOCK TABLES `user_entity` WRITE;
/*!40000 ALTER TABLE `user_entity` DISABLE KEYS */;
INSERT INTO `user_entity` VALUES (3,5,41),(4,1,42),(5,2,43),(6,6,73),(14,14,96),(15,15,99),(16,16,100);
/*!40000 ALTER TABLE `user_entity` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `two_factor_secret` text DEFAULT NULL,
  `two_factor_recovery_codes` text DEFAULT NULL,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Adam','adamjacobs173@googlemail.com',NULL,'$2y$10$iZJYyk/79JorsENJh3VpUOpnSsAoWcd/oJxFnoZp/OkH2Uu3Z6ksi',NULL,NULL,NULL,NULL,'2023-02-26 13:23:14','2023-02-26 13:23:14'),(2,'Tim','tim@nowhere.com',NULL,'$2y$10$SfNVNq.n2idQpjtmIpt9xOhJ9nwyYLkSfJ6/.zHF5Tzf82ET8yp..',NULL,NULL,NULL,NULL,'2023-03-31 13:14:23','2023-03-31 13:14:23'),(5,'Bob','bob@wiuh.net',NULL,'$2y$10$fGdzmOLkHIhjesBEk3Ze1uSxCnt/UzlRzYMZ2R36OdbrIG..bbc32',NULL,NULL,NULL,NULL,'2023-04-02 20:50:50','2023-04-02 20:50:50'),(6,'Kim','kim@nothing.com',NULL,'$2y$10$3Ux3XzDgD6ieigttLnQyD.rHkvR8YmofAIzM0/B2Xb3wZFihUZ3AK',NULL,NULL,NULL,NULL,'2023-05-08 18:47:58','2023-05-08 18:47:58'),(14,'Demo','demo@nstrat.ion',NULL,'$2y$10$pOq4j5DFl4ilHSpqB1LOTe0KN2zg1pjThcqQUH/KkZhocjxygKf6e',NULL,NULL,NULL,NULL,'2023-05-16 10:44:52','2023-05-16 10:44:52'),(15,'Ibid','ibid@example.com',NULL,'$2y$10$H2qtqh2M0xeyeBBkWMSJg.66U/yReLx69BdGdjKSwrsGvnS0eKVnu',NULL,NULL,NULL,NULL,'2023-05-16 11:01:45','2023-05-16 11:01:45'),(16,'Anon','anon@example.com',NULL,'$2y$10$Sqohk740DYsVcR27SD4RmuYr5IRLOx0Xrt50qr82uAEWOIdE6GYNW',NULL,NULL,NULL,NULL,'2023-05-16 11:03:19','2023-05-16 11:03:19');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
