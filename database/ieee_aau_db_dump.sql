-- MySQL dump 10.16  Distrib 10.1.38-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: ieee_aau_db
-- ------------------------------------------------------
-- Server version	10.1.38-MariaDB

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
-- Table structure for table `articles`
--

DROP TABLE IF EXISTS `articles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `snippet` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `author` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('published','draft','archived') COLLATE utf8mb4_unicode_ci DEFAULT 'published',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `articles`
--

LOCK TABLES `articles` WRITE;
/*!40000 ALTER TABLE `articles` DISABLE KEYS */;
INSERT INTO `articles` VALUES (1,'The Future of AI in Education','Technology','How artificial intelligence is transforming adaptive learning and digital curriculum design.','Full article text on AI...','https://images.unsplash.com/photo-1620712943543-bcc4688e7485?auto=format&fit=crop&w=600&q=80','Sara Ali','published','2026-10-26 11:01:09'),(2,'Getting Started with Embedded Systems','Technology','A practical guide for engineering students stepping into microcontrollers, ARM, and real-time OS.','Full article text on embedded systems...','https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=600&q=80','Ahmed Mohamed','published','2026-10-26 11:01:09'),(3,'Why IEEE Membership Matters','IEEE','Unlocking access to IEEE Xplore digital library, global grant competitions, and international conferences.','Full article on IEEE membership value...','https://images.unsplash.com/photo-1521737604893-d14cc237f11d?auto=format&fit=crop&w=600&q=80','Branch Chair','published','2026-10-26 11:01:09');
/*!40000 ALTER TABLE `articles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `audit_logs`
--

DROP TABLE IF EXISTS `audit_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `audit_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `user_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `action` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `details` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip_address` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=79 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audit_logs`
--

LOCK TABLES `audit_logs` WRITE;
/*!40000 ALTER TABLE `audit_logs` DISABLE KEYS */;
INSERT INTO `audit_logs` VALUES (1,1,'Branch Chair (Super Admin)','Login','User logged in with role office_member',NULL,'2026-10-26 11:43:43'),(2,1,'Branch Chair (Super Admin)','Create Term','Created new office term: 2027 – 2028 (#3)',NULL,'2026-10-26 11:59:26'),(3,1,'Branch Chair (Super Admin)','Delete Term','Deleted office term #3 (2027 – 2028)',NULL,'2026-10-26 12:01:29'),(4,1,'Branch Chair (Super Admin)','Update Term','Updated office term details & positions: 2025 – 2026 (#1)',NULL,'2026-10-26 12:08:35'),(5,1,'Branch Chair (Super Admin)','Update Term','Updated office term details & positions: 2025 – 2026 (#1)',NULL,'2026-10-26 12:13:39'),(6,1,'Branch Chair (Super Admin)','Login','User logged in with role office_member',NULL,'2026-10-26 12:33:56'),(7,1,'Branch Chair (Super Admin)','Login','User logged in with role office_member',NULL,'2026-10-26 13:30:49'),(8,2,'HR Coordinator','Login','User logged in with role hr',NULL,'2026-10-26 13:30:49'),(9,3,'Programs Coordinator','Login','User logged in with role programs_coordinator',NULL,'2026-10-26 13:30:50'),(10,4,'Magazine Editor','Login','User logged in with role magazine_coordinator',NULL,'2026-10-26 13:30:50'),(11,5,'Media Lead','Login','User logged in with role media_coordinator',NULL,'2026-10-26 13:30:50'),(12,6,'Ahmed Mohamed (Student)','Login','User logged in with role registered_user',NULL,'2026-10-26 13:30:50'),(13,NULL,'Programs Coordinator','Create Event','Created event QA Automated Test Event (EV-261026-162)',NULL,'2026-10-26 13:30:50'),(14,1,'Branch Chair (Super Admin)','Create Term','Created new office term: 2027 – 2028 (#4)',NULL,'2026-10-26 13:30:50'),(15,1,'Branch Chair (Super Admin)','Login','User logged in with role office_member',NULL,'2026-10-26 13:31:09'),(16,2,'HR Coordinator','Login','User logged in with role hr',NULL,'2026-10-26 13:31:09'),(17,3,'Programs Coordinator','Login','User logged in with role programs_coordinator',NULL,'2026-10-26 13:31:09'),(18,4,'Magazine Editor','Login','User logged in with role magazine_coordinator',NULL,'2026-10-26 13:31:09'),(19,5,'Media Lead','Login','User logged in with role media_coordinator',NULL,'2026-10-26 13:31:09'),(20,6,'Ahmed Mohamed (Student)','Login','User logged in with role registered_user',NULL,'2026-10-26 13:31:09'),(21,NULL,'Programs Coordinator','Create Event','Created event QA Automated Test Event (EV-261026-306)',NULL,'2026-10-26 13:31:09'),(22,1,'Branch Chair (Super Admin)','Login','User logged in with role office_member',NULL,'2026-10-26 13:31:30'),(23,2,'HR Coordinator','Login','User logged in with role hr',NULL,'2026-10-26 13:31:30'),(24,3,'Programs Coordinator','Login','User logged in with role programs_coordinator',NULL,'2026-10-26 13:31:30'),(25,4,'Magazine Editor','Login','User logged in with role magazine_coordinator',NULL,'2026-10-26 13:31:30'),(26,5,'Media Lead','Login','User logged in with role media_coordinator',NULL,'2026-10-26 13:31:30'),(27,6,'Ahmed Mohamed (Student)','Login','User logged in with role registered_user',NULL,'2026-10-26 13:31:30'),(28,NULL,'Programs Coordinator','Create Event','Created event QA Automated Test Event (EV-261026-832)',NULL,'2026-10-26 13:31:30'),(29,1,'Branch Chair (Super Admin)','Create Term','Created new office term: 2027 – 2028 (#5)',NULL,'2026-10-26 13:31:31'),(30,1,'Branch Chair (Super Admin)','Login','User logged in with role office_member',NULL,'2026-10-26 13:58:35'),(31,2,'HR Coordinator','Login','User logged in with role hr',NULL,'2026-10-26 13:58:35'),(32,3,'Programs Coordinator','Login','User logged in with role programs_coordinator',NULL,'2026-10-26 13:58:35'),(33,4,'Magazine Editor','Login','User logged in with role magazine_coordinator',NULL,'2026-10-26 13:58:35'),(34,5,'Media Lead','Login','User logged in with role media_coordinator',NULL,'2026-10-26 13:58:35'),(35,6,'Ahmed Mohamed (Student)','Login','User logged in with role registered_user',NULL,'2026-10-26 13:58:35'),(36,NULL,'Programs Coordinator','Create Event','Created event QA Automated Test Event (EV-261026-726)',NULL,'2026-10-26 13:58:35'),(37,1,'Branch Chair (Super Admin)','Create Term','Created new office term: 2027 – 2028 (#6)',NULL,'2026-10-26 13:58:35'),(38,1,'Branch Chair (Super Admin)','Login','User logged in with role office_member',NULL,'2026-10-26 14:00:10'),(39,NULL,'Branch Chair (Super Admin)','Create Event','Created event Test Live Flow Event (EV-261026-595)',NULL,'2026-10-26 14:00:10'),(40,1,'Branch Chair (Super Admin)','Login','User logged in with role office_member',NULL,'2026-10-26 14:02:32'),(41,NULL,'Branch Chair (Super Admin)','Create Event','Created event Test Live Flow Event (EV-261026-684)',NULL,'2026-10-26 14:02:32'),(42,1,'Branch Chair (Super Admin)','Login','User logged in with role office_member',NULL,'2026-10-26 14:02:52'),(43,2,'HR Coordinator','Login','User logged in with role hr',NULL,'2026-10-26 14:02:52'),(44,3,'Programs Coordinator','Login','User logged in with role programs_coordinator',NULL,'2026-10-26 14:02:52'),(45,4,'Magazine Editor','Login','User logged in with role magazine_coordinator',NULL,'2026-10-26 14:02:52'),(46,5,'Media Lead','Login','User logged in with role media_coordinator',NULL,'2026-10-26 14:02:53'),(47,6,'Ahmed Mohamed (Student)','Login','User logged in with role registered_user',NULL,'2026-10-26 14:02:53'),(48,NULL,'Programs Coordinator','Create Event','Created event QA Automated Test Event (EV-261026-601)',NULL,'2026-10-26 14:02:53'),(49,1,'Branch Chair (Super Admin)','Create Term','Created new office term: 2027 – 2028 (#7)',NULL,'2026-10-26 14:02:53'),(50,1,'Branch Chair (Super Admin)','Login','User logged in with role office_member',NULL,'2026-10-26 14:06:11'),(51,2,'HR Coordinator','Login','User logged in with role hr',NULL,'2026-10-26 14:06:11'),(52,3,'Programs Coordinator','Login','User logged in with role programs_coordinator',NULL,'2026-10-26 14:06:11'),(53,4,'Magazine Editor','Login','User logged in with role magazine_coordinator',NULL,'2026-10-26 14:06:11'),(54,5,'Media Lead','Login','User logged in with role media_coordinator',NULL,'2026-10-26 14:06:11'),(55,6,'Ahmed Mohamed (Student)','Login','User logged in with role registered_user',NULL,'2026-10-26 14:06:12'),(56,NULL,'Programs Coordinator','Create Event','Created event QA Automated Test Event (EV-261026-560)',NULL,'2026-10-26 14:06:12'),(57,1,'Branch Chair (Super Admin)','Create Term','Created new office term: 2027 – 2028 (#8)',NULL,'2026-10-26 14:06:12'),(58,1,'Branch Chair (Super Admin)','Login','User logged in with role office_member',NULL,'2026-10-26 14:06:29'),(59,2,'HR Coordinator','Login','User logged in with role hr',NULL,'2026-10-26 14:06:29'),(60,3,'Programs Coordinator','Login','User logged in with role programs_coordinator',NULL,'2026-10-26 14:06:30'),(61,4,'Magazine Editor','Login','User logged in with role magazine_coordinator',NULL,'2026-10-26 14:06:30'),(62,5,'Media Lead','Login','User logged in with role media_coordinator',NULL,'2026-10-26 14:06:30'),(63,6,'Ahmed Mohamed (Student)','Login','User logged in with role registered_user',NULL,'2026-10-26 14:06:30'),(64,NULL,'Programs Coordinator','Create Event','Created event QA Automated Test Event (EV-261026-580)',NULL,'2026-10-26 14:06:30'),(65,1,'Branch Chair (Super Admin)','Create Term','Created new office term: 2027 – 2028 (#9)',NULL,'2026-10-26 14:06:30'),(66,1,'Branch Chair (Super Admin)','Login','User logged in with role office_member',NULL,'2026-10-26 14:07:50'),(67,NULL,'Branch Chair (Super Admin)','Create Event','Created event Test Live Flow Event (EV-261026-430)',NULL,'2026-10-26 14:07:50'),(68,1,'Branch Chair (Super Admin)','Login','User logged in with role office_member',NULL,'2026-10-27 12:29:43'),(69,2,'HR Coordinator','Login','User logged in with role hr',NULL,'2026-10-27 12:29:43'),(70,3,'Programs Coordinator','Login','User logged in with role programs_coordinator',NULL,'2026-10-27 12:29:43'),(71,4,'Magazine Editor','Login','User logged in with role magazine_coordinator',NULL,'2026-10-27 12:29:43'),(72,5,'Media Lead','Login','User logged in with role media_coordinator',NULL,'2026-10-27 12:29:43'),(73,6,'Ahmed Mohamed (Student)','Login','User logged in with role registered_user',NULL,'2026-10-27 12:29:43'),(74,NULL,'Programs Coordinator','Create Event','Created event QA Automated Test Event (EV-261027-934)',NULL,'2026-10-27 12:29:43'),(75,1,'Branch Chair (Super Admin)','Create Term','Created new office term: 2027 – 2028 (#10)',NULL,'2026-10-27 12:29:43'),(76,1,'Branch Chair (Super Admin)','Login','User logged in with role office_member',NULL,'2026-10-27 12:29:49'),(77,NULL,'Branch Chair (Super Admin)','Create Event','Created event Test Live Flow Event (EV-261027-326)',NULL,'2026-10-27 12:29:49'),(78,1,'Branch Chair (Super Admin)','Login','User logged in with role office_member',NULL,'2026-10-27 13:00:52');
/*!40000 ALTER TABLE `audit_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `event_registrations`
--

DROP TABLE IF EXISTS `event_registrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event_registrations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('registered','waitlist','attended','did_not_attend','cancelled') COLLATE utf8mb4_unicode_ci DEFAULT 'registered',
  `registered_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `event_id` (`event_id`),
  CONSTRAINT `event_registrations_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `event_registrations`
--

LOCK TABLES `event_registrations` WRITE;
/*!40000 ALTER TABLE `event_registrations` DISABLE KEYS */;
INSERT INTO `event_registrations` VALUES (1,'EV-01',1,'Branch Chair (Super Admin)','admin@ieee-aau.org','+249912000001','registered','2026-10-26 20:49:34'),(2,'EV-261026-162',1,'Branch Chair (Super Admin)','admin@ieee-aau.org','+249912000001','registered','2026-10-27 12:00:51');
/*!40000 ALTER TABLE `event_registrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `events` (
  `id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `starts_at` datetime NOT NULL,
  `ends_at` datetime NOT NULL,
  `registration_deadline` datetime DEFAULT NULL,
  `location` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `event_type` enum('in_person','online','hybrid') COLLATE utf8mb4_unicode_ci DEFAULT 'in_person',
  `meeting_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `speakers` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seats` int(11) DEFAULT '100',
  `status` enum('upcoming','live','completed','cancelled') COLLATE utf8mb4_unicode_ci DEFAULT 'upcoming',
  `is_featured` tinyint(1) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `events`
--

LOCK TABLES `events` WRITE;
/*!40000 ALTER TABLE `events` DISABLE KEYS */;
INSERT INTO `events` VALUES ('EV-01','AI & Future Technologies Symposium','Premier international conference delving into Generative AI, machine perception, and engineering solutions.','2026-10-31 10:00:00','2026-10-31 14:00:00','2026-10-30 23:59:59','AAU Main Conference Hall','in_person',NULL,'Dr. Al-Amin & Eng. Maryam',150,'upcoming',1,'2026-10-26 10:03:35','2026-10-26 10:03:35'),('EV-02','Fullstack Web Architecture Bootcamp','Hands-on practical development workshop building scalable REST APIs and modern reactive user interfaces.','2026-11-07 09:30:00','2026-11-07 13:00:00',NULL,'Computer Lab 2 - Engineering Campus','in_person',NULL,'Lead Dev Mohamed Hassan',45,'upcoming',0,'2026-10-26 10:03:35','2026-10-26 10:03:35'),('EV-03','Global IEEE Day & Student Innovation Pitch','Annual branch gathering celebrating regional achievements, IEEE membership milestones, and student awards.','2026-11-15 16:00:00','2026-11-15 19:00:00',NULL,'Virtual Zoom / Campus Garden','hybrid','https://ieee.zoom.us/j/aau-branch','Branch Counselor & Alumni Panel',250,'upcoming',0,'2026-10-26 10:03:35','2026-10-26 10:03:35'),('EV-261026-162','QA Automated Test Event','','2026-10-29 14:30:50','2026-10-29 16:30:50',NULL,'Main Hall A','in_person',NULL,'',100,'upcoming',0,'2026-10-26 13:30:50','2026-10-26 13:30:50'),('EV-261026-306','QA Automated Test Event','','2026-10-29 14:31:09','2026-10-29 16:31:09',NULL,'Main Hall A','in_person',NULL,'',100,'upcoming',0,'2026-10-26 13:31:09','2026-10-26 13:31:09'),('EV-261026-430','Test Live Flow Event','','2026-11-20 10:00:00','2026-11-20 12:00:00',NULL,'Main Hall','in_person',NULL,'',100,'upcoming',0,'2026-10-26 14:07:50','2026-10-26 14:07:50'),('EV-261026-560','QA Automated Test Event','','2026-10-29 15:06:12','2026-10-29 17:06:12',NULL,'Main Hall A','in_person',NULL,'',100,'upcoming',0,'2026-10-26 14:06:12','2026-10-26 14:06:12'),('EV-261026-580','QA Automated Test Event','','2026-10-29 15:06:30','2026-10-29 17:06:30',NULL,'Main Hall A','in_person',NULL,'',100,'upcoming',0,'2026-10-26 14:06:30','2026-10-26 14:06:30'),('EV-261026-595','Test Live Flow Event','','2026-11-20 10:00:00','2026-11-20 12:00:00',NULL,'Main Hall','in_person',NULL,'',100,'upcoming',0,'2026-10-26 14:00:10','2026-10-26 14:00:10'),('EV-261026-601','QA Automated Test Event','','2026-10-29 15:02:53','2026-10-29 17:02:53',NULL,'Main Hall A','in_person',NULL,'',100,'upcoming',0,'2026-10-26 14:02:53','2026-10-26 14:02:53'),('EV-261026-684','Test Live Flow Event','','2026-11-20 10:00:00','2026-11-20 12:00:00',NULL,'Main Hall','in_person',NULL,'',100,'upcoming',0,'2026-10-26 14:02:32','2026-10-26 14:02:32'),('EV-261026-726','QA Automated Test Event','','2026-10-29 14:58:35','2026-10-29 16:58:35',NULL,'Main Hall A','in_person',NULL,'',100,'upcoming',0,'2026-10-26 13:58:35','2026-10-26 13:58:35'),('EV-261026-832','QA Automated Test Event','','2026-10-29 14:31:30','2026-10-29 16:31:30',NULL,'Main Hall A','in_person',NULL,'',100,'upcoming',0,'2026-10-26 13:31:30','2026-10-26 13:31:30'),('EV-261027-326','Test Live Flow Event','','2026-11-20 10:00:00','2026-11-20 12:00:00',NULL,'Main Hall','in_person',NULL,'',100,'upcoming',0,'2026-10-27 12:29:49','2026-10-27 12:29:49'),('EV-261027-934','QA Automated Test Event','','2026-10-30 13:29:43','2026-10-30 15:29:43',NULL,'Main Hall A','in_person',NULL,'',100,'upcoming',0,'2026-10-27 12:29:43','2026-10-27 12:29:43');
/*!40000 ALTER TABLE `events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `magazine_issues`
--

DROP TABLE IF EXISTS `magazine_issues`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `magazine_issues` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `volume` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cover` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `file_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('published','hidden','archived') COLLATE utf8mb4_unicode_ci DEFAULT 'published',
  `year` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `published_date` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `magazine_issues`
--

LOCK TABLES `magazine_issues` WRITE;
/*!40000 ALTER TABLE `magazine_issues` DISABLE KEYS */;
INSERT INTO `magazine_issues` VALUES (1,'Vol. 12 | Issue 3 | 2025','Engineering a Smarter Tomorrow','https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=600&q=80','Cutting-edge breakthroughs in renewable grid integration, quantum computing, and student innovations from AAU.','#','published','2025','April 2025'),(2,'Vol. 12 Issue 1','The AI Renaissance','https://images.unsplash.com/photo-1634017839464-5c339ebe3cb4?auto=format&fit=crop&w=600&q=80','Generative computing in academia and research.','#','published','2025','January 2025'),(3,'Vol. 11 Issue 4','Smart Grids & Africa','https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=600&q=80','Sustainable energy transitions.','#','published','2024','November 2024'),(4,'Vol. 11 Issue 3','Robotics in Surgery','https://images.unsplash.com/photo-1550751827-4bd374c3f58b?auto=format&fit=crop&w=600&q=80','Biomedical engineering milestones.','#','published','2024','August 2024'),(6,'Vol. 13 | Issue 8','IEEEAAU','uploads/covers/1793014413_99d8715c.jpg','','uploads/magazines/1793014425_c80328a7.pdf','published','2026','October 2026');
/*!40000 ALTER TABLE `magazine_issues` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `members`
--

DROP TABLE IF EXISTS `members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `members` (
  `id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `team` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `year` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT 'Member',
  `status` enum('Active','Inactive','Archived') COLLATE utf8mb4_unicode_ci DEFAULT 'Active',
  `join_date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `members`
--

LOCK TABLES `members` WRITE;
/*!40000 ALTER TABLE `members` DISABLE KEYS */;
/*!40000 ALTER TABLE `members` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `news`
--

DROP TABLE IF EXISTS `news`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `snippet` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `author` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('published','draft','archived') COLLATE utf8mb4_unicode_ci DEFAULT 'published',
  `published_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news`
--

LOCK TABLES `news` WRITE;
/*!40000 ALTER TABLE `news` DISABLE KEYS */;
INSERT INTO `news` VALUES (1,'IEEE AAU Organizes a Technical Workshop','Events','A hands-on workshop on Embedded Systems and IoT architectures for engineering undergraduates.','Full workshop coverage and outcomes...','https://images.unsplash.com/photo-1531482615713-2afd69097998?auto=format&fit=crop&w=800&q=80','Media Team','published','2026-10-26 13:01:09','2026-10-26 11:01:09'),(2,'New IEEE Magazine Issue Released','Announcements','Explore the latest issue of IEEE AAU Magazine featuring student innovations, AI ethics, and interviews.','Issue release notes...','https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?auto=format&fit=crop&w=800&q=80','Editorial Committee','published','2026-10-26 13:01:09','2026-10-26 11:01:09'),(3,'Upcoming Event: AI & Future Technologies','Events','Register now for our flagship symposium featuring guest keynote speakers from regional tech firms.','Symposium schedule and topics...','https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=800&q=80','Events Team','published','2026-10-26 13:01:09','2026-10-26 11:01:09'),(4,'IEEE AAU Participates in IEEE Region 8 Event','Achievements','Our branch represented Alzaeim Alazhari University at the annual cross-sectional congress.','Congress representation recap...','https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=800&q=80','Branch Chair','published','2026-10-26 13:01:09','2026-10-26 11:01:09'),(5,'QA News Article','Events','Test snippet','','https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=800&q=80','Media Lead','published','2026-10-26 15:30:50','2026-10-26 13:30:50'),(6,'QA News Article','Events','Test snippet','','https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=800&q=80','Media Lead','published','2026-10-26 15:31:10','2026-10-26 13:31:10'),(7,'QA News Article','Events','Test snippet','','https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=800&q=80','Media Lead','published','2026-10-26 15:31:30','2026-10-26 13:31:30'),(8,'QA News Article','Events','Test snippet','','https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=800&q=80','Media Lead','published','2026-10-26 15:58:35','2026-10-26 13:58:35'),(9,'QA News Article','Events','Test snippet','','https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=800&q=80','Media Lead','published','2026-10-26 16:02:53','2026-10-26 14:02:53'),(10,'QA News Article','Events','Test snippet','','https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=800&q=80','Media Lead','published','2026-10-26 16:06:12','2026-10-26 14:06:12'),(11,'QA News Article','Events','Test snippet','','https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=800&q=80','Media Lead','published','2026-10-26 16:06:30','2026-10-26 14:06:30'),(12,'QA News Article','Events','Test snippet','','https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=800&q=80','Media Lead','published','2026-10-27 14:29:43','2026-10-27 12:29:43');
/*!40000 ALTER TABLE `news` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `office_terms`
--

DROP TABLE IF EXISTS `office_terms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `office_terms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `starts_on` date NOT NULL,
  `ends_on` date NOT NULL,
  `status` enum('draft','current','archived') COLLATE utf8mb4_unicode_ci DEFAULT 'draft',
  `published_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `office_terms`
--

LOCK TABLES `office_terms` WRITE;
/*!40000 ALTER TABLE `office_terms` DISABLE KEYS */;
INSERT INTO `office_terms` VALUES (1,'2025 – 2026','2025-09-01','2026-08-31','current','2026-10-26 13:43:08',1,'2026-10-26 11:43:08'),(2,'2024 – 2025','2024-09-01','2025-08-31','archived','2026-10-26 13:43:08',1,'2026-10-26 11:43:08');
/*!40000 ALTER TABLE `office_terms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rate_limits`
--

DROP TABLE IF EXISTS `rate_limits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rate_limits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `action_key` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` int(11) NOT NULL DEFAULT '1',
  `last_attempt` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ip_action_idx` (`ip_address`,`action_key`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rate_limits`
--

LOCK TABLES `rate_limits` WRITE;
/*!40000 ALTER TABLE `rate_limits` DISABLE KEYS */;
INSERT INTO `rate_limits` VALUES (7,'127.0.0.1','volunteer_submit',2,'2026-10-27 14:29:43');
/*!40000 ALTER TABLE `rate_limits` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `recruitment_campaigns`
--

DROP TABLE IF EXISTS `recruitment_campaigns`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `recruitment_campaigns` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `opens_at` datetime NOT NULL,
  `closes_at` datetime NOT NULL,
  `status` enum('draft','active','closed') COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `terms_version` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'v2.0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recruitment_campaigns`
--

LOCK TABLES `recruitment_campaigns` WRITE;
/*!40000 ALTER TABLE `recruitment_campaigns` DISABLE KEYS */;
INSERT INTO `recruitment_campaigns` VALUES (1,'حملة التطوع السنوية 2026/2027','2026-10-19 00:00:00','2026-11-25 23:59:59','active','v2.0','2026-10-26 13:19:16');
/*!40000 ALTER TABLE `recruitment_campaigns` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `team_specialties`
--

DROP TABLE IF EXISTS `team_specialties`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `team_specialties` (
  `id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `team_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_en` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_ar` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `team_id` (`team_id`),
  CONSTRAINT `team_specialties_ibfk_1` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `team_specialties`
--

LOCK TABLES `team_specialties` WRITE;
/*!40000 ALTER TABLE `team_specialties` DISABLE KEYS */;
INSERT INTO `team_specialties` VALUES ('content_writer','media','Content Writer','كاتب محتوى'),('graphic_designer','media','Graphic Designer','مصمم جرافيك'),('photographer','media','Photographer','مصور فوتوغرافي'),('video_editor','media','Video Editor','محرر فيديو / مونتير');
/*!40000 ALTER TABLE `team_specialties` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `teams`
--

DROP TABLE IF EXISTS `teams`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `teams` (
  `id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_en` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_ar` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description_en` text COLLATE utf8mb4_unicode_ci,
  `description_ar` text COLLATE utf8mb4_unicode_ci,
  `tasks_en` text COLLATE utf8mb4_unicode_ci,
  `tasks_ar` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) DEFAULT '1',
  `order_index` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teams`
--

LOCK TABLES `teams` WRITE;
/*!40000 ALTER TABLE `teams` DISABLE KEYS */;
INSERT INTO `teams` VALUES ('hr','HR','الموارد البشرية','Member onboarding, evaluations, conflict resolution','إدارة وتوجيه المتطوعين، التقييم، وفض النزاعات','Attendance tracking, team morale, interviews & onboarding','متابعة الحضور، تحفيز الفرق، والمقابلات الشخصية',1,6),('magazine','Magazine','المجلة العلمية','Branch editorial magazine and scientific writing','إعداد وتحرير أعداد مجلة الفرع الدورية والأبحاث','Editing articles, science journalism, peer reviews','تحرير المقالات، الصحافة العلمية، والمراجعة',1,3),('media','Media','الإعلام والتغطيات','Creative production, visual branding, social coverage','إدارة الإنتاج الإبداعي والتصميم والتغطية الرقمية','Graphic design, photo & video coverage, content writing','تصميم الجرافيك، المونتاج، التصوير، كتابة المحتوى',1,1),('pr','PR','العلاقات العامة','Partnerships, external outreach, sponsor relations','بناء الشراكات والتواصل مع الجهات الخارجية والرعاة','Sponsorship outreach, speaker invitations, guest hosting','التواصل مع الرعاة، دعوة المتحدثين، واستقبال الضيوف',1,5),('presenters','Presenters','عرافة الحفل والتقديم','Public speaking, stage presence, MCing official ceremonies','التقديم المسرحي وإدارة منصات الفعاليات الرسمية','Master of ceremonies, welcoming guests, speeches delivery','عرافة الحفل، إدارة المنصة، إلقاء الكلمات الرسمية',1,7),('program','Program','البرامج والتنظيم','Event planning, agenda management, execution flow','تخطيط الفعاليات والمؤتمرات وإدارة الأجندة','Logistics coordination, crowd management, scheduling','التنسيق اللوجستي، إدارة الحشود، وضبط التوقيت',1,4),('speaker','Speaker','المتحدثون والتنسيق','Speaker acquisition, communication, session prep','البحث والتنسيق مع المحاضرين والمتحدثين المتخصصين','Speaker outreach, bio vetting, topic harmonization','البحث عن المحاضرين، التنسيق، وضبط محاور الجلسات',1,8),('technical','Technical','الفريق التقني','Audio/visual systems, logistics tech, coding bootcamps','إدارة الأجهزة الصوتية والمرئية، البرمجة والأنظمة','Sound setup, projection, IT logistics, tech workshops','تجهيز الصوت، العرض، الدعم الفني، وورش البرمجة',1,2);
/*!40000 ALTER TABLE `teams` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `term_members`
--

DROP TABLE IF EXISTS `term_members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `term_members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `term_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name_en` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_ar` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `photo_path` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bio_en` text COLLATE utf8mb4_unicode_ci,
  `bio_ar` text COLLATE utf8mb4_unicode_ci,
  `linkedin_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_public` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `term_id` (`term_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `term_members`
--

LOCK TABLES `term_members` WRITE;
/*!40000 ALTER TABLE `term_members` DISABLE KEYS */;
INSERT INTO `term_members` VALUES (1,1,1,'Abubakr siddig','ابوبكر صديق','uploads/media/1793016813_a8af8a63.webp','Senior electrical engineering undergraduate leading branch growth, global partnerships, and student innovation.','طالب هندسة كهربائية يقود نمو الفرع والشراكات الإقليمية والمبادرات الابتكارية للطلاب.','https://www.linkedin.com/in/abubakr-siddig-853478224','chairman@ieee-aau.org','2026-10-26 11:43:08'),(2,1,NULL,'Maryam Osman','مريم عثمان','https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&w=400&q=80','Telecommunications engineering enthusiast focused on executive operations and strategic planning.','مهندسة اتصالات مهتمة بالعمليات التنفيذية والتخطيط الإستراتيجي للفعاليات.','https://linkedin.com','vicechair@ieee-aau.org','2026-10-26 11:43:08'),(3,1,NULL,'Yusuf Ibrahim','يوسف إبراهيم','https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=400&q=80','Computer systems undergraduate managing branch official correspondence, reporting, and archival records.','طالب هندسة حاسوب مسؤول عن المراسلات والتقارير الدورية وأرشفة وثائق الفرع.','https://linkedin.com','secretary@ieee-aau.org','2026-10-26 11:43:08'),(4,1,NULL,'Fatima Hassan','فاطمة حسن','https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=400&q=80','Financial planning and budget auditing for branch student activities and technical projects.','إدارة الميزانيات والتدقيق المالي لجميع الأنشطة الطلابية والمشاريع التقنية.','https://linkedin.com','treasurer@ieee-aau.org','2026-10-26 11:43:08'),(5,1,2,'Sara Ahmed','سارة أحمد','https://images.unsplash.com/photo-1567532939604-b6b5b0db2604?auto=format&fit=crop&w=400&q=80','Talent management, volunteer recruitment screening, onboarding, and organizational culture building.','إدارة المواهب والفرز الأكاديمي للمتطوعين وصقل المهارات القيادية بالفرع.','https://linkedin.com','hr@ieee-aau.org','2026-10-26 11:43:08'),(6,1,5,'Tariq Zein','طارق زين','https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=400&q=80','Creative direction, visual branding, social media coverage, and live event video production.','الإخراج الإبداعي والهوية البصرية والتغطيات الإعلامية وتصميم المحتوى الرقمي.','https://linkedin.com','media@ieee-aau.org','2026-10-26 11:43:08'),(7,1,NULL,'Omer Khalid','عمر خالد','https://images.unsplash.com/photo-1492562080023-ab3db95bfbce?auto=format&fit=crop&w=400&q=80','Hands-on embedded systems developer leading audio/visual logistics, booth setups, and hackathons.','مطور أنظمة مدمجة يشرف على اللوجستيات التقنية والصوتية وتنظيم الهاكاثونات البرمجية.','https://github.com','technical@ieee-aau.org','2026-10-26 11:43:08'),(8,1,4,'Hiba El-Nour','هبة النور','https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=400&q=80','Chief editor overseeing technical writing, peer reviewing, science journalism, and volume publishing.','رئيسة تحرير مجلة الفرع، مسؤولة عن تدقيق الأبحاث والمقالات العلمية وإصدار النسخ.','https://linkedin.com','magazine@ieee-aau.org','2026-10-26 11:43:08'),(9,1,3,'Ali Babiker','علي بابكر','https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&w=400&q=80','Event scheduling, venue coordination, crowd logistics, and speaker hospitality management.','جدولة المؤتمرات، التنسيق الميداني للقاعات، وإدارة تدفق الفعاليات والضيوف.','https://linkedin.com','programs@ieee-aau.org','2026-10-26 11:43:08'),(10,1,NULL,'Zainab Mahmoud','زينب محمود','https://images.unsplash.com/photo-1573497019940-1c28c88b4f3e?auto=format&fit=crop&w=400&q=80','External outreach, corporate sponsorship relations, partner communication, and institutional ties.','بناء الشراكات مع الشركات الراعية، وإدارة العلاقات الخارجية والتواصل المؤسسي.','https://linkedin.com','pr@ieee-aau.org','2026-10-26 11:43:08'),(11,1,NULL,'Mustafa Jalal','مصطفى جلال','https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=400&q=80','Stage presentation training, ceremonial MCing, welcoming delegations, and public speaking coaching.','عرافة الحفل، تدريب مقدمي المنصة، وإلقاء الخطابات الرسمية في الاحتفالات.','https://linkedin.com','presenters@ieee-aau.org','2026-10-26 11:43:08'),(12,1,NULL,'Rania Siddig','رانيا صديق','https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=400&q=80','Keynote speaker research, outreach, briefing kits, and technical session topic synchronization.','البحث عن المتحدثين والخبراء، والتنسيق معهم لإعداد الجلسات العلمية وتسهيل مشاركتهم.','https://linkedin.com','speakers@ieee-aau.org','2026-10-26 11:43:08'),(13,2,NULL,'Hassan Abdelrahman','حسن عبد الرحمن','https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=400&q=80','Past Branch Chair 2024-2025 who chartered major community initiatives.','رئيس الفرع للدورة السابقة 2024-2025.','https://linkedin.com','alumni@ieee-aau.org','2026-10-26 11:43:08');
/*!40000 ALTER TABLE `term_members` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `term_positions`
--

DROP TABLE IF EXISTS `term_positions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `term_positions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `term_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `position_group` enum('office','coordinator') COLLATE utf8mb4_unicode_ci NOT NULL,
  `position_key` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `team_id` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title_en` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title_ar` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` int(11) DEFAULT '0',
  `is_vacant` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `term_id` (`term_id`),
  KEY `member_id` (`member_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `term_positions`
--

LOCK TABLES `term_positions` WRITE;
/*!40000 ALTER TABLE `term_positions` DISABLE KEYS */;
INSERT INTO `term_positions` VALUES (1,1,1,'office','chairman',NULL,'IEEE AAU Chairman','رئيس فرع IEEE AAU',1,0),(2,1,2,'office','vice_chair',NULL,'Vice Chair','نائب الرئيس',2,0),(3,1,3,'office','secretary',NULL,'Secretary','أمين السر',3,0),(4,1,4,'office','treasurer',NULL,'Treasurer','أمين الصندوق',4,0),(5,1,5,'office','hr',NULL,'HR Officer','مسؤول الموارد البشرية',5,0),(6,1,5,'coordinator','hr','hr','HR Team Coordinator','منسق فريق الموارد البشرية',11,0),(7,1,6,'coordinator','media','media','Media Team Coordinator','منسق فريق الإعلام',6,0),(8,1,7,'coordinator','technical','technical','Technical Team Coordinator','منسق الفريق التقني',7,0),(9,1,8,'coordinator','magazine','magazine','Magazine Team Coordinator','منسق فريق المجلة',8,0),(10,1,9,'coordinator','program','program','Program Team Coordinator','منسق فريق البرامج',9,0),(11,1,10,'coordinator','pr','pr','PR Coordinator','منسق العلاقات العامة',10,0),(12,1,11,'coordinator','presenters','presenters','Presenters Team Coordinator','منسق فريق التقديم',12,0),(13,1,12,'coordinator','speaker','speaker','Speaker Relations Coordinator','منسق المتحدثين',13,0),(14,2,13,'office','chairman',NULL,'IEEE AAU Chairman','رئيس فرع IEEE AAU',1,0);
/*!40000 ALTER TABLE `term_positions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_tokens`
--

DROP TABLE IF EXISTS `user_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_tokens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `token_hash` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT 'web_session',
  `last_used_at` datetime DEFAULT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token_hash` (`token_hash`),
  KEY `user_id` (`user_id`),
  KEY `token_hash_2` (`token_hash`),
  CONSTRAINT `user_tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_tokens`
--

LOCK TABLES `user_tokens` WRITE;
/*!40000 ALTER TABLE `user_tokens` DISABLE KEYS */;
INSERT INTO `user_tokens` VALUES (1,1,'56cf1c487db960342d95dabbdc63d09ff54fb117c62810719ed4644ef5f5ea31','web_login','2026-10-26 15:30:50','2026-11-02 15:30:49','2026-10-26 13:30:49'),(2,2,'7b9d6d71801f99247989f6abeb0c578afb09269e7627ef78e8076629cfcc314a','web_login','2026-10-26 15:30:50','2026-11-02 15:30:49','2026-10-26 13:30:49'),(3,3,'a7e561ea3387196377cf5996333c3d1faa1905588685a6442e30bb75f9aafb5d','web_login','2026-10-26 15:30:50','2026-11-02 15:30:50','2026-10-26 13:30:50'),(4,4,'d32dca323aa6836df09bd0435a0542fae30cb43073dd8573f0812244631dde74','web_login','2026-10-26 15:30:50','2026-11-02 15:30:50','2026-10-26 13:30:50'),(5,5,'775389e5b026bf7cf4492bc00cc97a2ebd14125b322f0cf9ccf6072396ba69d0','web_login','2026-10-26 15:30:50','2026-11-02 15:30:50','2026-10-26 13:30:50'),(6,6,'1f5692053ca7c1799c4823dcd3c132152f10decb4bbc552b88d336b93588d658','web_login','2026-10-26 15:30:50','2026-11-02 15:30:50','2026-10-26 13:30:50'),(7,1,'75da57803b80779a13d56c95879fc9fd004d32661b3321e1d55c5219e3f36b49','web_login','2026-10-26 15:31:10','2026-11-02 15:31:09','2026-10-26 13:31:09'),(8,2,'4778996a9339c331ca5aeb2f6fb222a35baa3f9831a26d87071a5d031d01cf77','web_login','2026-10-26 15:31:10','2026-11-02 15:31:09','2026-10-26 13:31:09'),(9,3,'0c2e386163d1885c8869449c66c181301cdd49b81aaec362c2e67a4e2f51f4fe','web_login','2026-10-26 15:31:10','2026-11-02 15:31:09','2026-10-26 13:31:09'),(10,4,'2db8cd1c3662f46a5942a9c77f34ccc6f6eb6a3aaccbcf5187a639b7c8b69156','web_login','2026-10-26 15:31:10','2026-11-02 15:31:09','2026-10-26 13:31:09'),(11,5,'4f57833262b4236917259973fee8da2c145163aeb61c2a9134bdfd85443afd8d','web_login','2026-10-26 15:31:10','2026-11-02 15:31:09','2026-10-26 13:31:09'),(12,6,'11dc4d3379b7ee9d88ee59f808692dea6775d2f15350b1a29cb8ce625b767a80','web_login','2026-10-26 15:31:10','2026-11-02 15:31:09','2026-10-26 13:31:09'),(13,1,'017077ff27fc5a3adad436e26223f001b2f14f97d6e9cf49df420db13bdadd91','web_login','2026-10-26 15:31:31','2026-11-02 15:31:30','2026-10-26 13:31:30'),(14,2,'a2f903fdac4dd46c8c66478141ceaa1ac4e04888fc578dafc22e0d520c800b32','web_login','2026-10-26 15:31:31','2026-11-02 15:31:30','2026-10-26 13:31:30'),(15,3,'461140822dabb51e48802f94b7c72c7bb128312cfe368118aae3ed86f27a42d2','web_login','2026-10-26 15:31:31','2026-11-02 15:31:30','2026-10-26 13:31:30'),(16,4,'fda6edc6e4e574c4575f3cba1a862d6e748a482979eff08f9ae168302c223637','web_login','2026-10-26 15:31:30','2026-11-02 15:31:30','2026-10-26 13:31:30'),(17,5,'3911ae549b7f83eecc57597cd0f67883cac837f55c7195eb3b89d4bbf8378fa0','web_login','2026-10-26 15:31:31','2026-11-02 15:31:30','2026-10-26 13:31:30'),(18,6,'66374273af7a0c5733e9e065c6f9215b4e5b27ce56647f986c08ae358f46f7b1','web_login','2026-10-26 15:31:31','2026-11-02 15:31:30','2026-10-26 13:31:30'),(20,1,'537790a38bac3127c67f734afb2a3aab30e5b1ad393e55d0b9013c8a6d23f37a','web_login','2026-10-26 15:58:35','2026-11-02 15:58:35','2026-10-26 13:58:35'),(21,2,'7ff796e08691c4e7ffc0eed3340774ac41c6fec137178b262d6cc0f8c19d80cd','web_login','2026-10-26 15:58:36','2026-11-02 15:58:35','2026-10-26 13:58:35'),(22,3,'bbd63dd2356966bc715085516f086c0dc0d8fe658789091b48690482b7448909','web_login','2026-10-26 15:58:36','2026-11-02 15:58:35','2026-10-26 13:58:35'),(23,4,'ed37a6ba7bdadbb3e1c40c9d6c883ee6e9205bda9e4eea9c1315be219c8e6370','web_login','2026-10-26 15:58:35','2026-11-02 15:58:35','2026-10-26 13:58:35'),(24,5,'0447719f5bdb2db6f5f40d1798ba4b1b27a65ae566388b293bc07a10ff8f51f1','web_login','2026-10-26 15:58:36','2026-11-02 15:58:35','2026-10-26 13:58:35'),(25,6,'55ae18fb69b6ced7359832dbc731cb556cc072b4927c19bd8b59a890089f4941','web_login','2026-10-26 15:58:36','2026-11-02 15:58:35','2026-10-26 13:58:35'),(26,1,'e0fa5ab0469319c66b951d2cca60bf6411ec20f821b1d4ca212a9fdb0dbef1d1','web_login','2026-10-26 16:02:37','2026-11-02 16:00:10','2026-10-26 14:00:10'),(27,1,'39e010cbc1ee9bb6f0cd2509e1b05c167d40e6d37a2ba9f09cbb0cc68a598c85','web_login','2026-10-26 16:02:32','2026-11-02 16:02:32','2026-10-26 14:02:32'),(28,1,'f35f99f045e077bf2708f7c750a627454e17fb0a6228f77f7bc8ace4835f87da','web_login','2026-10-26 16:02:53','2026-11-02 16:02:52','2026-10-26 14:02:52'),(29,2,'cb9d6e4d60ceee28269bf354825dfa1bc9292966ded99923d2cd4081130bf918','web_login','2026-10-26 16:02:53','2026-11-02 16:02:52','2026-10-26 14:02:52'),(30,3,'c044b1bd421126da5bc194550c43b2fbbec01875b0060ec5e41a0bed4beb33fa','web_login','2026-10-26 16:02:53','2026-11-02 16:02:52','2026-10-26 14:02:52'),(31,4,'b529d2e4f994b53609dc1067c1e62cd38c6c8531316641bb4ff1bde134745546','web_login','2026-10-26 16:02:53','2026-11-02 16:02:52','2026-10-26 14:02:52'),(32,5,'284dab3ccd70a09d11a8bf4938f3c712bfdc5cfe840a39bee573c5929facc766','web_login','2026-10-26 16:02:53','2026-11-02 16:02:53','2026-10-26 14:02:53'),(33,6,'95bd5a68eff6fa93dd0e71865efb8282152b8e819a0f46736ffd692d8e080760','web_login','2026-10-26 16:02:53','2026-11-02 16:02:53','2026-10-26 14:02:53'),(34,1,'28746209240fff466d76e236ab6ef7877d08652174e718af498a5683308a26f1','web_login','2026-10-26 16:06:12','2026-11-02 16:06:11','2026-10-26 14:06:11'),(35,2,'500727700fe41b3e0df22ab84a6ae02c9b3fb0c357e6b2b16f5f89a3b0256ac0','web_login','2026-10-26 16:06:12','2026-11-02 16:06:11','2026-10-26 14:06:11'),(36,3,'f3989fd6d8b1ff7997eff25526dea3d695f0b641c12b028383a37fec3f8966c7','web_login','2026-10-26 16:06:12','2026-11-02 16:06:11','2026-10-26 14:06:11'),(37,4,'8ed1c42aed0f5398d1545205b3c36d78d510bfbca67de86dd2d102407517d915','web_login','2026-10-26 16:06:12','2026-11-02 16:06:11','2026-10-26 14:06:11'),(38,5,'2f800f85ee46f13db9f6711497eef2612f268427341d6fdbf2078b45cfcc110d','web_login','2026-10-26 16:06:12','2026-11-02 16:06:11','2026-10-26 14:06:11'),(39,6,'4d4b9a8d1975e96a40cad7a566ed84f719387d829fb11a816c8113caf04d8dca','web_login','2026-10-26 16:06:12','2026-11-02 16:06:12','2026-10-26 14:06:12'),(40,1,'23d8d49b7673fa9004cb5d2c944f057c472308216210b02942b10bd47678433c','web_login','2026-10-26 16:06:30','2026-11-02 16:06:29','2026-10-26 14:06:29'),(41,2,'a3b2fc0ab949e108003760df3f2a1549c099e61d5a2d85f2321a44a46213afc9','web_login','2026-10-26 16:06:30','2026-11-02 16:06:29','2026-10-26 14:06:29'),(42,3,'ef19fd9dd12648012bb130c6511cb80a888c65446399eab6816f000641df09c8','web_login','2026-10-26 16:06:30','2026-11-02 16:06:30','2026-10-26 14:06:30'),(43,4,'a46564538f786ab90057c6487f0996b5abff12e4f41fd583627f55b74081038d','web_login','2026-10-26 16:06:30','2026-11-02 16:06:30','2026-10-26 14:06:30'),(44,5,'b342d91ea97259107c41ed073063008ac900b6d3820c6662ec51de124261ec42','web_login','2026-10-26 16:06:30','2026-11-02 16:06:30','2026-10-26 14:06:30'),(45,6,'01eddd32319ad24a029ad9627d00d3b9b3620380922833246ae6bc1d845e2546','web_login','2026-10-26 16:06:30','2026-11-02 16:06:30','2026-10-26 14:06:30'),(46,1,'e859230c3071f82d3cf43e3545f128f8c7cd7138cca6fcd702866650cbd2d775','web_login','2026-10-26 16:16:10','2026-11-02 16:07:50','2026-10-26 14:07:50'),(47,1,'0b16d9153a05740492513a2e81deddca580249fdfe4bdc904ca6c7f49d0354c8','migrated_session',NULL,'2026-11-03 14:29:42','2026-10-27 12:29:42'),(48,1,'05733395fba25716602e10db9bec87c8a39340f582a5ea4f0cbd221dd78d723d','web_login','2026-10-27 14:29:43','2026-11-03 14:29:43','2026-10-27 12:29:43'),(49,2,'91ad1d2e8679cb33cc0c6fcb8f560ba643982731759fb4ebfd66039d430a7113','web_login','2026-10-27 14:29:43','2026-11-03 14:29:43','2026-10-27 12:29:43'),(50,3,'d3a451963f47a3550170ca8c8592d362680ab5177f7865bde2c868e19825cfdd','web_login','2026-10-27 14:29:43','2026-11-03 14:29:43','2026-10-27 12:29:43'),(51,4,'a0824bbab3a7cf909a6ae2ef046dac08f61724bf8dcf5772db6a7af21bc8e052','web_login','2026-10-27 14:29:43','2026-11-03 14:29:43','2026-10-27 12:29:43'),(52,5,'2a142fa835e534c076157e58990885acfb158c0890d5c712cc5f4828cd755ea5','web_login','2026-10-27 14:29:43','2026-11-03 14:29:43','2026-10-27 12:29:43'),(53,6,'7726eab076204c7ea8b92061e2f5a4ac442b62ed10e3ee46ef61f6077479d5df','web_login','2026-10-27 14:29:44','2026-11-03 14:29:43','2026-10-27 12:29:43'),(54,1,'7470d2196945ae28051ca700aafa180dac82f10b677d67b16d5f138083baa543','web_login','2026-10-27 14:29:49','2026-11-03 14:29:49','2026-10-27 12:29:49'),(55,1,'d04746fbd6bb3a04701c46100cdb8363d35b850a9b83d394ea75d0484fbe672f','web_login','2026-10-27 15:01:57','2026-11-03 15:00:52','2026-10-27 13:00:52');
/*!40000 ALTER TABLE `user_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('office_member','hr','programs_coordinator','magazine_coordinator','media_coordinator','registered_user') COLLATE utf8mb4_unicode_ci DEFAULT 'registered_user',
  `phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `email_verified_at` datetime DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Branch Chair (Super Admin)','admin@ieee-aau.org','$2y$10$USqscam471QFfzw5jNd0ueR8HTPF30suGp0H2ayBp2rn1rt/KM/dO','office_member','+249912000001',1,NULL,NULL,'2026-10-26 10:03:35','2026-10-26 10:03:35'),(2,'HR Coordinator','hr@ieee-aau.org','$2y$10$USqscam471QFfzw5jNd0ueR8HTPF30suGp0H2ayBp2rn1rt/KM/dO','hr','+249912000002',1,NULL,NULL,'2026-10-26 10:03:35','2026-10-26 10:03:35'),(3,'Programs Coordinator','programs@ieee-aau.org','$2y$10$USqscam471QFfzw5jNd0ueR8HTPF30suGp0H2ayBp2rn1rt/KM/dO','programs_coordinator','+249912000003',1,NULL,NULL,'2026-10-26 10:03:35','2026-10-26 10:03:35'),(4,'Magazine Editor','magazine@ieee-aau.org','$2y$10$USqscam471QFfzw5jNd0ueR8HTPF30suGp0H2ayBp2rn1rt/KM/dO','magazine_coordinator','+249912000004',1,NULL,NULL,'2026-10-26 10:03:35','2026-10-26 10:03:35'),(5,'Media Lead','media@ieee-aau.org','$2y$10$USqscam471QFfzw5jNd0ueR8HTPF30suGp0H2ayBp2rn1rt/KM/dO','media_coordinator','+249912000005',1,NULL,NULL,'2026-10-26 10:03:35','2026-10-26 10:03:35'),(6,'Ahmed Mohamed (Student)','student@aau.edu','$2y$10$USqscam471QFfzw5jNd0ueR8HTPF30suGp0H2ayBp2rn1rt/KM/dO','registered_user','+249912345678',1,NULL,NULL,'2026-10-26 10:03:35','2026-10-26 10:03:35');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `volunteer_applications`
--

DROP TABLE IF EXISTS `volunteer_applications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `volunteer_applications` (
  `id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `campaign_id` int(11) DEFAULT NULL,
  `full_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `university` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `college` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `major` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `whatsapp` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `team_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `specialty_id` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `general_answers` text COLLATE utf8mb4_unicode_ci,
  `team_answers` text COLLATE utf8mb4_unicode_ci,
  `why_choose_you` text COLLATE utf8mb4_unicode_ci,
  `status` enum('new','screening','candidate_interview','interview_scheduled','accepted','rejected') COLLATE utf8mb4_unicode_ci DEFAULT 'new',
  `reviewer_notes` text COLLATE utf8mb4_unicode_ci,
  `reviewer_score` int(11) DEFAULT NULL,
  `terms_version` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'v2.0',
  `terms_accepted_at` datetime DEFAULT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `volunteer_applications`
--

LOCK TABLES `volunteer_applications` WRITE;
/*!40000 ALTER TABLE `volunteer_applications` DISABLE KEYS */;
INSERT INTO `volunteer_applications` VALUES ('VOL-26-3143',1,NULL,'Branch Chair (Super Admin)','جامعة الزعيم الأزهري','علوم الحاسوب وتقنية المعلومات','IS','26','admin@ieee-aau.org','+249912000001','+249912000001','media','graphic_designer','{\"q1_about_ieee\":\"SFS\",\"q2_attended\":\"no\",\"q2_event_details\":\"\",\"q3_why_join\":\"SFSSF\",\"q4_commitment\":8,\"q5_meeting_type\":\"both\",\"q6_volunteered\":\"no\",\"q6_org_details\":\"\",\"q7_english_level\":\"Intermediate\"}','{\"media_programs\":\"ALL\",\"media_device\":\"Laptop\"}','DSGDGD','screening','',NULL,'v2.0','2026-10-26 13:47:28','2026-10-26 11:47:28'),('VOL-26-6734',1,1,'Branch Chair (Super Admin)','جامعة الزعيم الأزهري','علوم الحاسوب وتقنية المعلومات','IS','26','admin@ieee-aau.org','+249912000001','+249912000001','media','graphic_designer','{\"q1_about_ieee\":\"NNM\",\"q2_attended\":\"no\",\"q2_event_details\":\"\",\"q3_why_join\":\"GHJ\",\"q4_commitment\":5,\"q5_meeting_type\":\"online\",\"q6_volunteered\":\"no\",\"q6_org_details\":\"\",\"q7_english_level\":\"Advanced\"}','{\"media_programs\":\"PH&AI\",\"media_portfolio\":\"KKLKL\",\"media_device\":\"Both\"}','NM,M','screening','',NULL,'v2.0','2026-10-27 14:04:44','2026-10-27 12:04:44');
/*!40000 ALTER TABLE `volunteer_applications` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-10-27 16:29:35
