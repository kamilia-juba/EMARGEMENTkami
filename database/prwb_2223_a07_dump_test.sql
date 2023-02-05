-- MariaDB dump 10.19  Distrib 10.4.24-MariaDB, for Win64 (AMD64)
--
-- Host: 127.0.0.1    Database: prwb_2223_xyy
-- ------------------------------------------------------
-- Server version	10.4.24-MariaDB

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
-- Table structure for table `operations`
--

DROP TABLE IF EXISTS `operations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `operations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `tricount` int(11) NOT NULL,
  `amount` double NOT NULL,
  `operation_date` date NOT NULL,
  `initiator` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `Initiator` (`initiator`),
  KEY `Tricount` (`tricount`),
  CONSTRAINT `operations_ibfk_1` FOREIGN KEY (`initiator`) REFERENCES `users` (`id`),
  CONSTRAINT `operations_ibfk_2` FOREIGN KEY (`tricount`) REFERENCES `tricounts` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `operations`
--

LOCK TABLES `operations` WRITE;
/*!40000 ALTER TABLE `operations` DISABLE KEYS */;
INSERT INTO `operations` VALUES (1, 'Colruyt', 4, 100, '2022-10-13', 2, '2022-10-13 19:09:18'),
(2, 'Plein essence', 4, 75, '2022-10-13', 1, '2022-10-13 20:10:41'),
(3, 'Grosses courses LIDL', 4, 212.47, '2022-10-13', 3, '2022-10-13 21:23:49'),
(4, 'Apéros', 4, 31.897456217, '2022-10-13', 1, '2022-10-13 23:51:20'),
(5, 'Boucherie', 4, 25.5, '2022-10-26', 2, '2022-10-26 09:59:56'),
(6, 'Loterie', 4, 35, '2022-10-26', 1, '2022-10-26 10:02:24'),
(9, 'Snack', 5, 15, '2023-02-05', 7, '2023-02-05 21:18:59'),
(10, 'Souvenirs', 5, 45, '2023-02-05', 5, '2023-02-05 21:19:26'),
(11, 'Billard', 5, 25, '2023-02-05', 6, '2023-02-05 21:19:50'),
(12, 'Hamam nudiste', 6, 45, '2023-02-05', 7, '2023-02-05 21:20:57'),
(13, 'Discothèque', 7, 5000, '2023-02-05', 7, '2023-02-05 21:22:02');
/*!40000 ALTER TABLE `operations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `repartition_template_items`
--

DROP TABLE IF EXISTS `repartition_template_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `repartition_template_items` (
  `user` int(11) NOT NULL,
  `repartition_template` int(11) NOT NULL,
  `weight` int(11) NOT NULL,
  PRIMARY KEY (`user`,`repartition_template`),
  KEY `Distribution` (`repartition_template`),
  CONSTRAINT `repartition_template_items_ibfk_1` FOREIGN KEY (`repartition_template`) REFERENCES `repartition_templates` (`id`),
  CONSTRAINT `repartition_template_items_ibfk_2` FOREIGN KEY (`user`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `repartition_template_items`
--

LOCK TABLES `repartition_template_items` WRITE;
/*!40000 ALTER TABLE `repartition_template_items` DISABLE KEYS */;
INSERT INTO `repartition_template_items` VALUES (1, 1, 2),
(1, 2, 1),
(2, 1, 1),
(3, 1, 1),
(3, 2, 1),
(5, 4, 1),
(6, 3, 1),
(7, 4, 1);
/*!40000 ALTER TABLE `repartition_template_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `repartition_templates`
--

DROP TABLE IF EXISTS `repartition_templates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `repartition_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `tricount` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Title` (`title`,`tricount`),
  KEY `Tricount` (`tricount`),
  CONSTRAINT `repartition_templates_ibfk_1` FOREIGN KEY (`tricount`) REFERENCES `tricounts` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `repartition_templates`
--

LOCK TABLES `repartition_templates` WRITE;
/*!40000 ALTER TABLE `repartition_templates` DISABLE KEYS */;
INSERT INTO `repartition_templates` VALUES (2, 'Benoit ne paye rien', 4),
(1, 'Boris paye double', 4),
(4, 'Mustafa et Amine paye parce que Yacine est fauché', 5),
(3, 'Yacine paye tout', 5);
/*!40000 ALTER TABLE `repartition_templates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `repartitions`
--

DROP TABLE IF EXISTS `repartitions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `repartitions` (
  `operation` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `weight` int(11) NOT NULL,
  PRIMARY KEY (`operation`,`user`),
  KEY `User` (`user`),
  CONSTRAINT `repartitions_ibfk_1` FOREIGN KEY (`operation`) REFERENCES `operations` (`id`),
  CONSTRAINT `repartitions_ibfk_2` FOREIGN KEY (`user`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `repartitions`
--

LOCK TABLES `repartitions` WRITE;
/*!40000 ALTER TABLE `repartitions` DISABLE KEYS */;
INSERT INTO `repartitions` VALUES (1, 1, 1),
(1, 2, 1),
(2, 1, 1),
(2, 2, 1),
(3, 1, 2),
(3, 2, 1),
(3, 3, 1),
(4, 1, 1),
(4, 2, 2),
(4, 3, 3),
(5, 1, 2),
(5, 2, 1),
(5, 3, 1),
(6, 1, 1),
(6, 3, 1),
(9, 5, 1),
(9, 6, 1),
(9, 7, 1),
(10, 5, 1),
(10, 7, 1),
(11, 5, 1),
(11, 6, 2),
(12, 7, 1),
(13, 1, 1),
(13, 2, 8),
(13, 3, 1),
(13, 4, 4),
(13, 5, 1),
(13, 6, 3),
(13, 7, 1);
/*!40000 ALTER TABLE `repartitions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subscriptions`
--

DROP TABLE IF EXISTS `subscriptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subscriptions` (
  `tricount` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  PRIMARY KEY (`tricount`,`user`),
  KEY `User` (`user`),
  CONSTRAINT `subscriptions_ibfk_1` FOREIGN KEY (`tricount`) REFERENCES `tricounts` (`id`),
  CONSTRAINT `subscriptions_ibfk_2` FOREIGN KEY (`user`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subscriptions`
--

LOCK TABLES `subscriptions` WRITE;
/*!40000 ALTER TABLE `subscriptions` DISABLE KEYS */;
INSERT INTO `subscriptions` VALUES (1, 1),
(2, 1),
(2, 2),
(4, 1),
(4, 2),
(4, 3),
(5, 5),
(5, 6),
(5, 7),
(6, 7),
(7, 1),
(7, 2),
(7, 3),
(7, 4),
(7, 5),
(7, 6),
(7, 7),
(8, 7),
(9, 2),
(9, 7);
/*!40000 ALTER TABLE `subscriptions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tricounts`
--

DROP TABLE IF EXISTS `tricounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tricounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(1024) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `creator` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Title` (`title`,`creator`),
  KEY `Creator` (`creator`),
  CONSTRAINT `tricounts_ibfk_1` FOREIGN KEY (`creator`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tricounts`
--

LOCK TABLES `tricounts` WRITE;
/*!40000 ALTER TABLE `tricounts` DISABLE KEYS */;
INSERT INTO `tricounts` VALUES (1, 'Gers 2022', NULL, '2022-10-10 18:42:24', 1),
(2, 'Resto badminton', NULL, '2022-10-10 19:25:10', 1),
(4, 'Vacances', 'A la mer du nord', '2022-10-10 19:31:09', 1),
(5, 'Voyage en Italie', 'Très beau pays :D', '2023-02-05 09:02:29', 7),
(6, 'Camping', 'Très belle forêt', '2023-02-05 09:02:06', 7),
(7, 'Roadtrip à Amsterdam', 'Les gens sont sympas et sourient tout le temps', '2023-02-05 09:02:39', 7),
(8, 'Balade en solitaire', '', '2023-02-05 09:02:59', 7),
(9, 'Portugal', '', '2023-02-05 09:02:38', 7);
/*!40000 ALTER TABLE `tricounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mail` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `hashed_password` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
  `full_name` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `role` enum('user','admin') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'user',
  `iban` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Mail` (`mail`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1, 'boverhaegen@epfc.eu', '56ce92d1de4f05017cf03d6cd514d6d1', 'Boris', 'user', NULL),
(2, 'bepenelle@epfc.eu', '56ce92d1de4f05017cf03d6cd514d6d1', 'Benoît', 'user', NULL),
(3, 'xapigeolet@epfc.eu', '56ce92d1de4f05017cf03d6cd514d6d1', 'Xavier', 'user', NULL),
(4, 'mamichel@epfc.eu', '56ce92d1de4f05017cf03d6cd514d6d1', 'Marc', 'user', '1234'),
(5, 'amine@hotmail.com', 'b2e6e428b3d8f8010b5457e107f2e822', 'Amine', 'user', 'BE68 5390 0754 7034'),
(6, 'yacine@hotmail.com', 'b2e6e428b3d8f8010b5457e107f2e822', 'Yacine', 'user', 'BE68 5390 0754 7478'),
(7, 'mustafa@hotmail.com', 'b2e6e428b3d8f8010b5457e107f2e822', 'Mustafa', 'user', 'BE68 5390 0754 7477');
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

-- Dump completed on 2022-12-01 18:43:19
