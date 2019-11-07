/*
SQLyog Community Edition- MySQL GUI v5.29
Host - 5.5.5-10.4.6-MariaDB : Database - client_consultant
*********************************************************************
Server version : 5.5.5-10.4.6-MariaDB
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

create database if not exists `client_consultant`;

USE `client_consultant`;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

/*Table structure for table `appointment_request` */

DROP TABLE IF EXISTS `appointment_request`;

CREATE TABLE `appointment_request` (
  `apt_id` varchar(13) NOT NULL,
  `client_id` varchar(7) DEFAULT NULL,
  `consultant_id` varchar(7) NOT NULL,
  `created_by` varchar(10) NOT NULL DEFAULT 'client',
  `schedule_date` datetime NOT NULL,
  `location` text DEFAULT NULL,
  `additional_file` varchar(128) DEFAULT NULL,
  `description` text NOT NULL,
  `status_request` enum('accept','decline','waiting_respond','cancel','done') NOT NULL DEFAULT 'waiting_respond',
  `is_solved` enum('N','Y','P') NOT NULL DEFAULT 'P',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `seqid` smallint(4) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`apt_id`),
  UNIQUE KEY `seqid` (`seqid`),
  KEY `fk_req_client_idx` (`client_id`),
  KEY `fk_req_consultant_idx` (`consultant_id`),
  CONSTRAINT `fk_req_client_idx` FOREIGN KEY (`client_id`) REFERENCES `client_user` (`client_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_req_consultant_idx` FOREIGN KEY (`consultant_id`) REFERENCES `consultant_user` (`consultant_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `appointment_request` */

insert  into `appointment_request`(`apt_id`,`client_id`,`consultant_id`,`created_by`,`schedule_date`,`location`,`additional_file`,`description`,`status_request`,`is_solved`,`created_at`,`updated_at`,`seqid`) values ('APT1911080001','CL0004','CLT0003','client','2019-11-09 11:00:00',NULL,NULL,'Jawa Ipsum gelung kalung ayam manah jawah, luh bebed kancing. Sapu sima peksi susu piring tuwi enjing mucal dipun pendhet wos? Peksi cariyos, sakit suku epek-epek ngulemi pedhang, untu manah nyukani maesa. Bidal minggat supena ngulemi? Peksi dhateng, kangge; kinten nyepeng ayam toya peksi, \"sima wilujeng maos sirah kuping ngadeg dolan makarya.\" Jejeran wawarat embok saweg mantun ajrih benang ningali gujeng pedhang tumut swanten awon.','done','Y','2019-11-08 00:02:31','2019-11-08 01:08:04',1),('APT1911080002','CL0004','CLT0003','consultant','2019-11-11 10:00:00',NULL,NULL,'Jawa Ipsum gelung kalung ayam manah jawah, luh bebed kancing. Sapu sima peksi susu piring tuwi enjing mucal dipun pendhet wos? Peksi cariyos, sakit suku epek-epek ngulemi pedhang, untu manah nyukani maesa. Bidal minggat supena ngulemi? Peksi dhateng, kangge; kinten nyepeng ayam toya peksi, \"sima wilujeng maos sirah kuping ngadeg dolan makarya.\" Jejeran wawarat embok saweg mantun ajrih benang ningali gujeng pedhang tumut swanten awon.','done','Y','2019-11-08 01:10:01','2019-11-08 01:18:51',2);

/*Table structure for table `city` */

DROP TABLE IF EXISTS `city`;

CREATE TABLE `city` (
  `city_id` char(3) NOT NULL,
  `city_name` varchar(32) NOT NULL,
  `province_id` char(2) NOT NULL,
  PRIMARY KEY (`city_id`),
  KEY `fk_city_province_idx` (`province_id`),
  CONSTRAINT `fk_city_province_idx` FOREIGN KEY (`province_id`) REFERENCES `province` (`province_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `city` */

insert  into `city`(`city_id`,`city_name`,`province_id`) values ('BGD','Bandung','JB'),('BGR','Bogor','JB'),('BKS','Bekasi','JB'),('CBN','Cirebon','JB'),('DPK','Depok','JB'),('JKB','Jakarta Barat','JK'),('JKP','Jakarta Pusat','JK'),('KWG','Karawang','JB'),('SRG','Serang','BT'),('TNG','Tangerang','BT'),('TSM','Tasikmalaya','JB');

/*Table structure for table `client_user` */

DROP TABLE IF EXISTS `client_user`;

CREATE TABLE `client_user` (
  `client_id` varchar(7) NOT NULL,
  `client_fullname` varchar(64) NOT NULL,
  `client_email` varchar(64) NOT NULL,
  `client_phone_number` varchar(17) DEFAULT NULL,
  `client_password` varchar(60) NOT NULL,
  `client_gender` enum('L','P') DEFAULT NULL,
  `client_photo` varchar(64) DEFAULT NULL,
  `client_type` enum('company','individual') NOT NULL,
  `client_address` text DEFAULT NULL,
  `city_id` char(3) DEFAULT NULL,
  `seqid` smallint(4) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`client_id`),
  UNIQUE KEY `client_email` (`client_email`),
  UNIQUE KEY `seqid` (`seqid`),
  KEY `fk_client_city` (`city_id`),
  CONSTRAINT `fk_client_city` FOREIGN KEY (`city_id`) REFERENCES `city` (`city_id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `client_user` */

insert  into `client_user`(`client_id`,`client_fullname`,`client_email`,`client_phone_number`,`client_password`,`client_gender`,`client_photo`,`client_type`,`client_address`,`city_id`,`seqid`,`created_at`,`updated_at`) values ('CL0001','Sony Darmawan','sonypiay@mail.com','08561969052','$2y$10$QHeHTZLaY5U1OdCQ0BYmN.un2VkD//tdqKlESE9z6DZQQVLCyJThO','L',NULL,'individual','Jl. Jendral Sudirman Kavling 10 - 11, Gedung Midplaza 2 lantai 8','JKP',1,'2019-11-02 19:05:06','2019-11-03 18:07:36'),('CL0002','Ridwan Dwi Saputra','ridwan_dwi@gmail.com','08561969052','$2y$10$BU.7pZMxjv2otZzZYLLBl.S7X7kBLL5uWxAz3z7YXVYFRHhvmp.CC','L',NULL,'individual','Depok','DPK',2,'2019-11-03 19:51:05','2019-11-03 19:56:55'),('CL0003','Puti Melinda Mayang Sari','puti.melinda@molecool.id','08581826887','$2y$10$kfMqPDHOCAv/IRlaa1CpjOUJAnoYik.cYeWha1But6B7lpmw8ZS4.','P',NULL,'individual','Cibinong, Bogor','JKP',3,'2019-11-07 22:56:49','2019-11-07 22:57:43'),('CL0004','Ayudina Nur Afifa','ayudina96@gmail.com','02198139879','$2y$10$400P1cisitTa7yYuNFeIQuyys6cV8NS5GGQuYeJ3sGtAgHdfXJo/m','P',NULL,'individual','Grogol','JKB',4,'2019-11-07 22:59:46','2019-11-07 23:00:08');

/*Table structure for table `consultant_user` */

DROP TABLE IF EXISTS `consultant_user`;

CREATE TABLE `consultant_user` (
  `consultant_id` varchar(7) NOT NULL,
  `consultant_fullname` varchar(64) NOT NULL,
  `consultant_email` varchar(64) NOT NULL,
  `consultant_password` varchar(60) NOT NULL,
  `consultant_phone_number` varchar(64) DEFAULT NULL,
  `consultant_gender` enum('L','P') DEFAULT 'L',
  `consultant_biography` text DEFAULT NULL,
  `consultant_photo` varchar(64) DEFAULT NULL,
  `consultant_address` text DEFAULT NULL,
  `consultant_type` enum('company','individual') NOT NULL,
  `city_id` char(3) DEFAULT NULL,
  `rating` float DEFAULT NULL,
  `seqid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`consultant_id`),
  UNIQUE KEY `consultant_email` (`consultant_email`),
  UNIQUE KEY `seqid` (`seqid`),
  KEY `fk_consultant_city` (`city_id`),
  CONSTRAINT `fk_consultant_city` FOREIGN KEY (`city_id`) REFERENCES `city` (`city_id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `consultant_user` */

insert  into `consultant_user`(`consultant_id`,`consultant_fullname`,`consultant_email`,`consultant_password`,`consultant_phone_number`,`consultant_gender`,`consultant_biography`,`consultant_photo`,`consultant_address`,`consultant_type`,`city_id`,`rating`,`seqid`,`created_at`,`updated_at`) values ('CLT0001','Rizqy Caesario','rizqycaesario@gmail.com','$2y$10$TBysW6oQEIvfv2k9.vmt9eXv6Ny50mfaNepRTVgbeCGWTjGw5HidO','08561969052','L',NULL,NULL,'Tambora, Jakarta Barat','individual','JKB',NULL,1,'2019-11-03 19:29:57','2019-11-03 19:58:09'),('CLT0002','Muhammad Ilham','ilham272727@gmail.com','$2y$10$0QKznwrQaqv81jsVDpftc.SEGi7Ms.jzMQtsFOyiSaOqxrtfC3UCy','02189483749','L',NULL,NULL,'Cempaka Baru, Jakarta Pusat','individual','JKP',NULL,2,'2019-11-04 07:18:55','2019-11-04 09:10:05'),('CLT0003','Ardiansyah','ardian@mercubuana.ac.id','$2y$10$KmdTg.4/AR4KTLZ8TT1ArObvCPgFY.cap/F5PQTled46ngFb3Cof6','02187987987','L',NULL,NULL,'Ciledug,','individual','TNG',NULL,3,'2019-11-04 19:36:23','2019-11-04 19:37:20');

/*Table structure for table `event_schedule` */

DROP TABLE IF EXISTS `event_schedule`;

CREATE TABLE `event_schedule` (
  `evt_id` varchar(7) NOT NULL,
  `evt_title` varchar(255) NOT NULL,
  `evt_schedule` datetime NOT NULL,
  `evt_type` enum('meeting','seminar') NOT NULL,
  `evt_file` varchar(128) NOT NULL,
  `evt_location` text NOT NULL,
  `city_id` char(3) NOT NULL,
  `consultant_id` varchar(7) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`evt_id`),
  KEY `fk_evt_consultant_idx` (`consultant_id`),
  KEY `fk_evt_city_idx` (`city_id`),
  CONSTRAINT `fk_evt_city_idx` FOREIGN KEY (`city_id`) REFERENCES `city` (`city_id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_evt_consultant_idx` FOREIGN KEY (`consultant_id`) REFERENCES `consultant_user` (`consultant_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `event_schedule` */

/*Table structure for table `feedbacks` */

DROP TABLE IF EXISTS `feedbacks`;

CREATE TABLE `feedbacks` (
  `fd_id` varchar(7) NOT NULL,
  `review_description` text NOT NULL,
  `feedback` enum('excellent','good','neutral','poor','disappointed') NOT NULL,
  `apt_id` varchar(13) NOT NULL,
  `seqid` smallint(4) NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`fd_id`),
  UNIQUE KEY `seqid` (`seqid`),
  KEY `fk_fd_aptidx` (`apt_id`),
  CONSTRAINT `fk_fd_aptidx` FOREIGN KEY (`apt_id`) REFERENCES `appointment_request` (`apt_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `feedbacks` */

insert  into `feedbacks`(`fd_id`,`review_description`,`feedback`,`apt_id`,`seqid`,`created_at`,`updated_at`) values ('FD0001','Nicely done!','good','APT1911080001',1,'2019-11-08 01:08:24','2019-11-08 01:08:24'),('FD0002','Amazing!','excellent','APT1911080002',2,'2019-11-08 01:19:02','2019-11-08 01:19:02');

/*Table structure for table `notification` */

DROP TABLE IF EXISTS `notification`;

CREATE TABLE `notification` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `notif_type` enum('chat','request','feedback') DEFAULT NULL,
  `user_id` varchar(7) NOT NULL,
  `notif_message` text NOT NULL,
  `parent_id` varchar(13) NOT NULL,
  `notif_read` enum('R','N') DEFAULT NULL,
  `notif_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8;

/*Data for the table `notification` */

insert  into `notification`(`id`,`notif_type`,`user_id`,`notif_message`,`parent_id`,`notif_read`,`notif_date`) values (1,'request','CLT0003','You have a new request appointment with ID APT1911080001','APT1911080001','R','2019-11-08 00:02:31'),(2,'request','CL0004','You create a new request appointment with ID APT1911080001','APT1911080001','N','2019-11-08 00:02:31'),(3,'request','CLT0003','Request appointment APT1911080001 accepted','APT1911080001','R','2019-11-08 00:08:47'),(4,'request','CL0004','Request appointment APT1911080001 accepted','APT1911080001','N','2019-11-08 00:08:47'),(5,'request','CLT0003','Request appointment APT1911080001 completed. Waiting for review.','APT1911080001','R','2019-11-08 00:12:41'),(6,'request','CL0004','Request appointment APT1911080001 completed. Waiting for review.','APT1911080001','N','2019-11-08 00:12:41'),(7,'request','CLT0003','Case is not finished yet with request APT1911080001','APT1911080001','N','2019-11-08 00:17:25'),(8,'request','CL0004','Case is not finished yet with request APT1911080001','APT1911080001','N','2019-11-08 00:17:25'),(9,'request','CLT0003','Request appointment with ID APT1911080001 has been rescheduled','APT1911080001','N','2019-11-08 00:30:32'),(10,'request','CL0004','Request appointment with ID APT1911080001 has been rescheduled','APT1911080001','N','2019-11-08 00:30:32'),(11,'request','CLT0003','Request appointment APT1911080001 accepted','APT1911080001','N','2019-11-08 00:32:14'),(12,'request','CL0004','Request appointment APT1911080001 accepted','APT1911080001','N','2019-11-08 00:32:14'),(13,'request','CLT0003','Request appointment APT1911080001 completed. Waiting for review.','APT1911080001','N','2019-11-08 00:58:53'),(14,'request','CL0004','Request appointment APT1911080001 completed. Waiting for review.','APT1911080001','N','2019-11-08 00:58:53'),(15,'request','CLT0003','Request appointment with ID APT1911080001 rescheduled','APT1911080001','N','2019-11-08 01:03:28'),(16,'request','CL0004','Request appointment with ID APT1911080001 rescheduled','APT1911080001','N','2019-11-08 01:03:28'),(17,'request','CLT0003','Request appointment APT1911080001 accepted','APT1911080001','N','2019-11-08 01:04:30'),(18,'request','CL0004','Request appointment APT1911080001 accepted','APT1911080001','N','2019-11-08 01:04:30'),(19,'request','CLT0003','Request appointment APT1911080001 completed. Waiting for review.','APT1911080001','N','2019-11-08 01:04:57'),(20,'request','CL0004','Request appointment APT1911080001 completed. Waiting for review.','APT1911080001','N','2019-11-08 01:04:57'),(21,'request','CLT0003','Case is not finished yet with request APT1911080001','APT1911080001','N','2019-11-08 01:07:51'),(22,'request','CL0004','Case is not finished yet with request APT1911080001','APT1911080001','N','2019-11-08 01:07:51'),(23,'request','CLT0003','Case closed for request APT1911080001','APT1911080001','N','2019-11-08 01:08:04'),(24,'request','CL0004','Case closed for request APT1911080001','APT1911080001','N','2019-11-08 01:08:04'),(25,'request','CLT0003','Client gave you a review with request APT1911080001','APT1911080001','N','2019-11-08 01:08:24'),(26,'request','CLT0003','You created a new request appointment with ID APT1911080002','APT1911080002','N','2019-11-08 01:10:01'),(27,'request','CL0004','You have a new request appointment with ID APT1911080002','APT1911080002','N','2019-11-08 01:10:01'),(28,'request','CLT0003','Request appointment APT1911080002 accepted','APT1911080002','N','2019-11-08 01:11:58'),(29,'request','CL0004','Request appointment APT1911080002 accepted','APT1911080002','N','2019-11-08 01:11:58'),(30,'request','CLT0003','Request appointment APT1911080002 completed. Waiting for review.','APT1911080002','N','2019-11-08 01:14:00'),(31,'request','CL0004','Request appointment APT1911080002 completed. Waiting for review.','APT1911080002','N','2019-11-08 01:14:00'),(32,'request','CLT0003','Case is not finished yet with request APT1911080002','APT1911080002','N','2019-11-08 01:17:17'),(33,'request','CL0004','Case is not finished yet with request APT1911080002','APT1911080002','N','2019-11-08 01:17:17'),(34,'request','CLT0003','Case closed for request APT1911080002','APT1911080002','N','2019-11-08 01:18:51'),(35,'request','CL0004','Case closed for request APT1911080002','APT1911080002','N','2019-11-08 01:18:51'),(36,'request','CLT0003','Client gave you a review with request APT1911080002','APT1911080002','N','2019-11-08 01:19:02');

/*Table structure for table `province` */

DROP TABLE IF EXISTS `province`;

CREATE TABLE `province` (
  `province_id` char(2) NOT NULL,
  `province_name` varchar(128) NOT NULL,
  PRIMARY KEY (`province_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `province` */

insert  into `province`(`province_id`,`province_name`) values ('BT','Banten'),('JB','Jawa Barat'),('JK','DKI Jakarta'),('JT','Jawa Tengah');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;