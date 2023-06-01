-- MySQL dump 10.13  Distrib 8.0.30, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: project
-- ------------------------------------------------------
-- Server version	8.0.32

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `galerijos_nuotraukos`
--

DROP TABLE IF EXISTS `galerijos_nuotraukos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `galerijos_nuotraukos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `pavadinimas` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nuotraukos_kelias` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `formatas` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sukurimo_data` datetime NOT NULL,
  `fk_naudotojas` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `galerijos_nuotraukos_fk0` (`fk_naudotojas`),
  CONSTRAINT `galerijos_nuotraukos_fk0` FOREIGN KEY (`fk_naudotojas`) REFERENCES `naudotojai` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `galerijos_nuotraukos`
--

LOCK TABLES `galerijos_nuotraukos` WRITE;
/*!40000 ALTER TABLE `galerijos_nuotraukos` DISABLE KEYS */;
/*!40000 ALTER TABLE `galerijos_nuotraukos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `galerijos_nuotraukos_etikete`
--

DROP TABLE IF EXISTS `galerijos_nuotraukos_etikete`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `galerijos_nuotraukos_etikete` (
  `id` int NOT NULL AUTO_INCREMENT,
  `pavadinimas` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sukurimo_data` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `galerijos_nuotraukos_etikete`
--

LOCK TABLES `galerijos_nuotraukos_etikete` WRITE;
/*!40000 ALTER TABLE `galerijos_nuotraukos_etikete` DISABLE KEYS */;
/*!40000 ALTER TABLE `galerijos_nuotraukos_etikete` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `galerijos_nuotraukos_pamegimai`
--

DROP TABLE IF EXISTS `galerijos_nuotraukos_pamegimai`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `galerijos_nuotraukos_pamegimai` (
  `id` int NOT NULL AUTO_INCREMENT,
  `sukurimo_data` date NOT NULL,
  `nuotraukos_pamegimas` tinyint(1) NOT NULL,
  `fk_komentaras` int DEFAULT NULL,
  `fk_nuotrauka` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `galerijos_nuotraukos_pamegimai_fk0` (`fk_komentaras`),
  KEY `galerijos_nuotraukos_pamegimai_fk1` (`fk_nuotrauka`),
  CONSTRAINT `galerijos_nuotraukos_pamegimai_fk0` FOREIGN KEY (`fk_komentaras`) REFERENCES `galerijos_nuotrauku_komentarai` (`id`) ON DELETE CASCADE,
  CONSTRAINT `galerijos_nuotraukos_pamegimai_fk1` FOREIGN KEY (`fk_nuotrauka`) REFERENCES `galerijos_nuotraukos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `galerijos_nuotraukos_pamegimai`
--

LOCK TABLES `galerijos_nuotraukos_pamegimai` WRITE;
/*!40000 ALTER TABLE `galerijos_nuotraukos_pamegimai` DISABLE KEYS */;
/*!40000 ALTER TABLE `galerijos_nuotraukos_pamegimai` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `galerijos_nuotrauku_etiketes`
--

DROP TABLE IF EXISTS `galerijos_nuotrauku_etiketes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `galerijos_nuotrauku_etiketes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `fk_nuotrauka` int NOT NULL,
  `fk_etikete` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `galerijos_nuotrauku_etiketes_fk0` (`fk_nuotrauka`),
  KEY `galerijos_nuotrauku_etiketes_fk1` (`fk_etikete`),
  CONSTRAINT `galerijos_nuotrauku_etiketes_fk0` FOREIGN KEY (`fk_nuotrauka`) REFERENCES `galerijos_nuotraukos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `galerijos_nuotrauku_etiketes_fk1` FOREIGN KEY (`fk_etikete`) REFERENCES `galerijos_nuotraukos_etikete` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `galerijos_nuotrauku_etiketes`
--

LOCK TABLES `galerijos_nuotrauku_etiketes` WRITE;
/*!40000 ALTER TABLE `galerijos_nuotrauku_etiketes` DISABLE KEYS */;
/*!40000 ALTER TABLE `galerijos_nuotrauku_etiketes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `galerijos_nuotrauku_komentarai`
--

DROP TABLE IF EXISTS `galerijos_nuotrauku_komentarai`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `galerijos_nuotrauku_komentarai` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tekstas` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sukurimo_data` datetime NOT NULL,
  `fk_naudotojas` int NOT NULL,
  `fk_galerijos_nuotrauka` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `galerijos_nuotrauku_komentarai_fk0` (`fk_naudotojas`),
  KEY `galerijos_nuotrauku_komentarai_fk1` (`fk_galerijos_nuotrauka`),
  CONSTRAINT `galerijos_nuotrauku_komentarai_fk0` FOREIGN KEY (`fk_naudotojas`) REFERENCES `naudotojai` (`id`) ON DELETE CASCADE,
  CONSTRAINT `galerijos_nuotrauku_komentarai_fk1` FOREIGN KEY (`fk_galerijos_nuotrauka`) REFERENCES `galerijos_nuotraukos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `galerijos_nuotrauku_komentarai`
--

LOCK TABLES `galerijos_nuotrauku_komentarai` WRITE;
/*!40000 ALTER TABLE `galerijos_nuotrauku_komentarai` DISABLE KEYS */;
/*!40000 ALTER TABLE `galerijos_nuotrauku_komentarai` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `katalogai`
--

DROP TABLE IF EXISTS `katalogai`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `katalogai` (
  `id` int NOT NULL AUTO_INCREMENT,
  `pavadinimas` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sukurimo_data` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `katalogai`
--

LOCK TABLES `katalogai` WRITE;
/*!40000 ALTER TABLE `katalogai` DISABLE KEYS */;
/*!40000 ALTER TABLE `katalogai` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `naudotojai`
--

DROP TABLE IF EXISTS `naudotojai`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `naudotojai` (
  `id` int NOT NULL AUTO_INCREMENT,
  `slapyvardis` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slaptazodis` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `registracijos_data` datetime NOT NULL,
  `avataro_kelias` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `uzblokuotas` int NOT NULL DEFAULT '0',
  `uztildytas` int NOT NULL DEFAULT '0',
  `paskutini_karta_prisijunges` datetime NOT NULL,
  `role` int NOT NULL,
  `salis` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `adresas` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefono_nr` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vardas` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pavarde` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gimimo_data` date DEFAULT NULL,
  `miestas` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `megstamiausias_zaidimas` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `biografine_zinute` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discord` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `facebook` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `instagram` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `skype` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parasas` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `snapchat` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tinklalapis` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mokykla` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `aukstasis_issilavinimas` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_roles0` (`role`),
  CONSTRAINT `fk_roles0` FOREIGN KEY (`role`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `naudotojai`
--

LOCK TABLES `naudotojai` WRITE;
/*!40000 ALTER TABLE `naudotojai` DISABLE KEYS */;
INSERT INTO `naudotojai` VALUES (1,'ghost','ghost','ghost@ghost.dev','0001-06-01 18:49:53',NULL,0,0,'0001-06-01 18:49:53',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(2,'user','$2y$10$AOUvJP3h/1vYfSGx.14JYeeXTmYCGhAq2oLjQ.cAoSQ4gBRo59hvy','user@user.dev','0001-06-01 18:49:53',NULL,0,0,'0001-06-01 18:49:53',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(3,'mod','$2y$10$w9GlJkts5u.DbXUztRIDWu/chBLqmcTujJnjfSA4bWTGiU57rtZHW','mod@mod.dev','0001-06-01 18:49:53',NULL,0,0,'0001-06-01 18:49:53',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(4,'admin','$2y$10$JrmBKffVuaHwHLSkN0XYpuuYXBKArKc52be2zg4cSjoXdww2yOx5m','admin@admin.dev','0001-06-01 18:49:53',NULL,0,0,'0001-06-01 18:49:53',3,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `naudotojai` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `naudotoju_ipai`
--

DROP TABLE IF EXISTS `naudotoju_ipai`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `naudotoju_ipai` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ip` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `paskutinis_prisijungimas` date NOT NULL,
  `fk_naudotojas` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_naudotojas00` (`fk_naudotojas`),
  CONSTRAINT `fk_naudotojas00` FOREIGN KEY (`fk_naudotojas`) REFERENCES `naudotojai` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `naudotoju_ipai`
--

LOCK TABLES `naudotoju_ipai` WRITE;
/*!40000 ALTER TABLE `naudotoju_ipai` DISABLE KEYS */;
/*!40000 ALTER TABLE `naudotoju_ipai` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `pavadinimas` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'Naudotojas'),(2,'Moderatorius'),(3,'Administratorius');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `slaptazodziu_priminikliai`
--

DROP TABLE IF EXISTS `slaptazodziu_priminikliai`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `slaptazodziu_priminikliai` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tokenas` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sukurimo_data` datetime NOT NULL,
  `pabaigos_data` datetime NOT NULL,
  `fk_naudotojas` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_naudotojas1` (`fk_naudotojas`),
  CONSTRAINT `fk_naudotojas1` FOREIGN KEY (`fk_naudotojas`) REFERENCES `naudotojai` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `slaptazodziu_priminikliai`
--

LOCK TABLES `slaptazodziu_priminikliai` WRITE;
/*!40000 ALTER TABLE `slaptazodziu_priminikliai` DISABLE KEYS */;
/*!40000 ALTER TABLE `slaptazodziu_priminikliai` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `temos`
--

DROP TABLE IF EXISTS `temos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `temos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `pavadinimas` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sukurimo_data` datetime NOT NULL,
  `fk_naudotojas` int NOT NULL,
  `fk_katalogas` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `temos_fk0` (`fk_naudotojas`),
  KEY `temos_fk1` (`fk_katalogas`),
  CONSTRAINT `temos_fk0` FOREIGN KEY (`fk_naudotojas`) REFERENCES `naudotojai` (`id`) ON DELETE CASCADE,
  CONSTRAINT `temos_fk1` FOREIGN KEY (`fk_katalogas`) REFERENCES `katalogai` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `temos`
--

LOCK TABLES `temos` WRITE;
/*!40000 ALTER TABLE `temos` DISABLE KEYS */;
/*!40000 ALTER TABLE `temos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `temu_atsakymai`
--

DROP TABLE IF EXISTS `temu_atsakymai`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `temu_atsakymai` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tekstas` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sukurimo_data` datetime NOT NULL,
  `fk_naudotojas` int NOT NULL,
  `fk_tema` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `temu_atsakymai_fk0` (`fk_naudotojas`),
  KEY `temu_atsakymai_fk1` (`fk_tema`),
  CONSTRAINT `temu_atsakymai_fk0` FOREIGN KEY (`fk_naudotojas`) REFERENCES `naudotojai` (`id`) ON DELETE CASCADE,
  CONSTRAINT `temu_atsakymai_fk1` FOREIGN KEY (`fk_tema`) REFERENCES `temos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `temu_atsakymai`
--

LOCK TABLES `temu_atsakymai` WRITE;
/*!40000 ALTER TABLE `temu_atsakymai` DISABLE KEYS */;
/*!40000 ALTER TABLE `temu_atsakymai` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `temu_pamegimai`
--

DROP TABLE IF EXISTS `temu_pamegimai`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `temu_pamegimai` (
  `id` int NOT NULL AUTO_INCREMENT,
  `sukurimo_data` datetime NOT NULL,
  `fk_naudotojas` int NOT NULL,
  `fk_temos_atsakymas` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `temu_pamegimai_fk0` (`fk_naudotojas`),
  KEY `temu_pamegimai_fk1` (`fk_temos_atsakymas`),
  CONSTRAINT `temu_pamegimai_fk0` FOREIGN KEY (`fk_naudotojas`) REFERENCES `naudotojai` (`id`) ON DELETE CASCADE,
  CONSTRAINT `temu_pamegimai_fk1` FOREIGN KEY (`fk_temos_atsakymas`) REFERENCES `temu_atsakymai` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `temu_pamegimai`
--

LOCK TABLES `temu_pamegimai` WRITE;
/*!40000 ALTER TABLE `temu_pamegimai` DISABLE KEYS */;
/*!40000 ALTER TABLE `temu_pamegimai` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zurnalo_irasai`
--

DROP TABLE IF EXISTS `zurnalo_irasai`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `zurnalo_irasai` (
  `id` int NOT NULL AUTO_INCREMENT,
  `data` date NOT NULL,
  `tekstas` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `fk_naudotojas` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_naudotojas0` (`fk_naudotojas`),
  CONSTRAINT `fk_naudotojas0` FOREIGN KEY (`fk_naudotojas`) REFERENCES `naudotojai` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zurnalo_irasai`
--

LOCK TABLES `zurnalo_irasai` WRITE;
/*!40000 ALTER TABLE `zurnalo_irasai` DISABLE KEYS */;
/*!40000 ALTER TABLE `zurnalo_irasai` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-06-01 18:57:54
