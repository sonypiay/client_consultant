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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

/*Data for the table `appointment_request` */

insert  into `appointment_request`(`apt_id`,`client_id`,`consultant_id`,`created_by`,`schedule_date`,`location`,`additional_file`,`description`,`status_request`,`is_solved`,`created_at`,`updated_at`,`seqid`) values ('APT1911050001','CL0001','CLT0001','client','2019-11-09 10:00:00',NULL,NULL,'Hello world','done','Y','2019-11-05 16:08:32','2019-11-06 15:51:40',1),('APT1911050002','CL0002','CLT0001','client','2019-11-15 10:00:00',NULL,NULL,'testing','done','N','2019-11-05 19:43:03','2019-11-06 16:25:41',2),('APT1911050003','CL0002','CLT0002','client','2019-11-18 10:30:00',NULL,NULL,'Jawa Ipsum gelung kalung ayam manah jawah, luh bebed kancing. Sapu sima peksi susu piring tuwi enjing mucal dipun pendhet wos? Peksi cariyos, sakit suku epek-epek ngulemi pedhang, untu manah nyukani maesa. Bidal minggat supena ngulemi? Peksi dhateng, kangge; kinten nyepeng ayam toya peksi, \"sima wilujeng maos sirah kuping ngadeg dolan makarya.\" Jejeran wawarat embok saweg mantun ajrih benang ningali gujeng pedhang tumut swanten awon.','decline','P','2019-11-05 19:43:22','2019-11-05 21:58:17',3),('APT1911050004','CL0002','CLT0003','client','2019-11-23 13:00:00',NULL,NULL,'testing','done','N','2019-11-05 19:43:45','2019-11-06 16:25:50',4),('APT1911060005','CL0001','CLT0003','client','2019-11-18 10:30:00',NULL,NULL,'Jawa Ipsum gelung kalung ayam manah jawah, luh bebed kancing. Sapu sima peksi susu piring tuwi enjing mucal dipun pendhet wos? Peksi cariyos, sakit suku epek-epek ngulemi pedhang, untu manah nyukani maesa. Bidal minggat supena ngulemi? Peksi dhateng, kangge; kinten nyepeng ayam toya peksi, \"sima wilujeng maos sirah kuping ngadeg dolan makarya.\" Jejeran wawarat embok saweg mantun ajrih benang ningali gujeng pedhang tumut swanten awon.','done','Y','2019-11-06 01:04:03','2019-11-06 16:22:07',5),('APT1911060006','CL0001','CLT0002','client','2019-11-16 11:30:00',NULL,NULL,'Jawa Ipsum gelung kalung ayam manah jawah, luh bebed kancing. Sapu sima peksi susu piring tuwi enjing mucal dipun pendhet wos? Peksi cariyos, sakit suku epek-epek ngulemi pedhang, untu manah nyukani maesa. Bidal minggat supena ngulemi? Peksi dhateng, kangge; kinten nyepeng ayam toya peksi, \"sima wilujeng maos sirah kuping ngadeg dolan makarya.\" Jejeran wawarat embok saweg mantun ajrih benang ningali gujeng pedhang tumut swanten awon.','waiting_respond','P','2019-11-06 01:04:36','2019-11-06 01:04:36',6),('APT1911060007','CL0001','CLT0001','client','2019-11-20 10:00:00',NULL,NULL,'Jawa Ipsum gelung kalung ayam manah jawah, luh bebed kancing. Sapu sima peksi susu piring tuwi enjing mucal dipun pendhet wos? Peksi cariyos, sakit suku epek-epek ngulemi pedhang, untu manah nyukani maesa. Bidal minggat supena ngulemi? Peksi dhateng, kangge; kinten nyepeng ayam toya peksi, \"sima wilujeng maos sirah kuping ngadeg dolan makarya.\" Jejeran wawarat embok saweg mantun ajrih benang ningali gujeng pedhang tumut swanten awon.','decline','P','2019-11-06 01:05:16','2019-11-06 01:29:46',7);

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `client_user` */

insert  into `client_user`(`client_id`,`client_fullname`,`client_email`,`client_phone_number`,`client_password`,`client_gender`,`client_photo`,`client_type`,`client_address`,`city_id`,`seqid`,`created_at`,`updated_at`) values ('CL0001','Sony Darmawan','sonypiay@mail.com','08561969052','$2y$10$QHeHTZLaY5U1OdCQ0BYmN.un2VkD//tdqKlESE9z6DZQQVLCyJThO','L',NULL,'individual','Jl. Jendral Sudirman Kavling 10 - 11, Gedung Midplaza 2 lantai 8','JKP',1,'2019-11-02 19:05:06','2019-11-03 18:07:36'),('CL0002','Ridwan Dwi Saputra','ridwan_dwi@gmail.com','08561969052','$2y$10$BU.7pZMxjv2otZzZYLLBl.S7X7kBLL5uWxAz3z7YXVYFRHhvmp.CC','L',NULL,'individual','Depok','DPK',2,'2019-11-03 19:51:05','2019-11-03 19:56:55');

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

insert  into `feedbacks`(`fd_id`,`review_description`,`feedback`,`apt_id`,`seqid`,`created_at`,`updated_at`) values ('FD0001','This is awesome!','excellent','APT1911050001',1,'2019-11-06 16:04:37','2019-11-06 16:04:37'),('FD0002','Awesome dude!','excellent','APT1911060005',2,'2019-11-06 16:22:20','2019-11-06 16:22:20');

/*Table structure for table `notification` */

DROP TABLE IF EXISTS `notification`;

CREATE TABLE `notification` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `notif_type` enum('chat','request','feedback') DEFAULT NULL,
  `client_id` varchar(7) DEFAULT NULL,
  `consultant_id` varchar(7) DEFAULT NULL,
  `notif_message` text NOT NULL,
  `parent_id` varchar(13) NOT NULL,
  `notif_read` enum('R','N') DEFAULT NULL,
  `notif_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_notif_client_idx` (`client_id`),
  KEY `fk_notif_consultan_idx` (`consultant_id`),
  CONSTRAINT `fk_notif_client_idx` FOREIGN KEY (`client_id`) REFERENCES `client_user` (`client_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_notif_consultan_idx` FOREIGN KEY (`consultant_id`) REFERENCES `consultant_user` (`consultant_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

/*Data for the table `notification` */

insert  into `notification`(`id`,`notif_type`,`client_id`,`consultant_id`,`notif_message`,`parent_id`,`notif_read`,`notif_date`) values (1,'request',NULL,'CLT0001','You have a new request appointment from Sony Darmawan','APT1911050001','R','2019-11-05 16:08:32'),(2,'request','CL0001',NULL,'Your request #APT1911050001 has been accepted','APT1911050001','R','2019-11-05 19:37:04'),(3,'request',NULL,'CLT0001','You have a new request appointment from Ridwan Dwi Saputra','APT1911050002','R','2019-11-05 19:43:03'),(4,'request',NULL,'CLT0002','You have a new request appointment from Ridwan Dwi Saputra','APT1911050003','N','2019-11-05 19:43:22'),(5,'request',NULL,'CLT0003','You have a new request appointment from Ridwan Dwi Saputra','APT1911050004','R','2019-11-05 19:43:45'),(6,'request','CL0002',NULL,'Your request #APT1911050002 has been accepted','APT1911050002','R','2019-11-05 19:44:31'),(7,'request',NULL,'CLT0002','Request APT1911050003 has been rescheduled.','APT1911050003','N','2019-11-05 21:54:57'),(8,'request','CL0002',NULL,'Your request #APT1911050003 has been declined','APT1911050003','R','2019-11-05 21:58:17'),(9,'request',NULL,'CLT0003','You have a new request appointment. Request ID APT1911060005','APT1911060005','R','2019-11-06 01:04:03'),(10,'request',NULL,'CLT0002','You have a new request appointment. Request ID APT1911060006','APT1911060006','N','2019-11-06 01:04:36'),(11,'request',NULL,'CLT0001','You have a new request appointment. Request ID APT1911060007','APT1911060007','R','2019-11-06 01:05:16'),(12,'request','CL0001',NULL,'Your request #APT1911060005 has been accepted','APT1911060005','R','2019-11-06 01:15:11'),(13,'request','CL0002',NULL,'Your request #APT1911050004 has been accepted','APT1911050004','R','2019-11-06 01:15:29'),(14,'request','CL0001',NULL,'Your request #APT1911060007 has been declined','APT1911060007','R','2019-11-06 01:29:46'),(15,'request','CL0001',NULL,'Your request #APT1911050001 has been completed','APT1911050001','R','2019-11-06 01:47:43'),(16,'request','CL0002',NULL,'Your request #APT1911050002 has been completed','APT1911050002','R','2019-11-06 01:47:48'),(17,'request','CL0001',NULL,'Request #APT1911050001 has been completed. Case closed.','APT1911050001','R','2019-11-06 15:51:40'),(18,'request',NULL,'CLT0001','Client gave you a review on request #APT1911050001','APT1911050001','R','2019-11-06 16:04:37'),(19,'request','CL0001',NULL,'Request #APT1911060005 has been completed. Waiting for review by Client.','APT1911060005','R','2019-11-06 16:18:49'),(20,'request','CL0002',NULL,'Request #APT1911050004 has been completed. Waiting for review by Client.','APT1911050004','R','2019-11-06 16:18:58'),(21,'request','CL0001',NULL,'Request #APT1911060005 has been completed. Case closed.','APT1911060005','R','2019-11-06 16:22:07'),(22,'request',NULL,'CLT0003','Client gave you a review on request #APT1911060005','APT1911060005','N','2019-11-06 16:22:20'),(23,'request','CL0002',NULL,'Request #APT1911050002 has been completed but case is not finished yet.','APT1911050002','R','2019-11-06 16:25:41'),(24,'request','CL0002',NULL,'Request #APT1911050004 has been completed but case is not finished yet.','APT1911050004','R','2019-11-06 16:25:50');

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
