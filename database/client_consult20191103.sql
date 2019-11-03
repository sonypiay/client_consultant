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
  `created_by` varchar(7) NOT NULL,
  `schedule_date` datetime NOT NULL,
  `location` text DEFAULT NULL,
  `additional_file` varchar(128) DEFAULT NULL,
  `description` text NOT NULL,
  `status_request` enum('accept','reject','waiting_respond') NOT NULL DEFAULT 'waiting_respond',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`apt_id`),
  KEY `fk_req_client_idx` (`client_id`),
  KEY `fk_req_consultant_idx` (`consultant_id`),
  CONSTRAINT `fk_req_client_idx` FOREIGN KEY (`client_id`) REFERENCES `client_user` (`client_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_req_consultant_idx` FOREIGN KEY (`consultant_id`) REFERENCES `consultant_user` (`consultant_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `appointment_request` */

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `client_user` */

insert  into `client_user`(`client_id`,`client_fullname`,`client_email`,`client_phone_number`,`client_password`,`client_gender`,`client_photo`,`client_type`,`client_address`,`city_id`,`seqid`,`created_at`,`updated_at`) values ('CL0001','Sony Darmawan','sonypiay@mail.com','08561969052','$2y$10$uI5NzdNTpizKB0pSHHGWUOiov05Y6oGdbnmFGBl9x.NHAxWZfuMfK','L',NULL,'individual','Jl. Jendral Sudirman Kavling 10 - 11, Gedung Midplaza 2 lantai 8','JKP',1,'2019-11-02 19:05:06','2019-11-03 08:37:42');

/*Table structure for table `consultant_user` */

DROP TABLE IF EXISTS `consultant_user`;

CREATE TABLE `consultant_user` (
  `consultant_id` varchar(7) NOT NULL,
  `consultant_fullname` varchar(64) NOT NULL,
  `consultant_email` varchar(64) NOT NULL,
  `consultant_password` varchar(60) NOT NULL,
  `consultant_phone_number` varchar(64) DEFAULT NULL,
  `consultant_gender` enum('L','P') DEFAULT 'L',
  `consultant_photo` varchar(64) DEFAULT NULL,
  `city_id` char(3) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`consultant_id`),
  UNIQUE KEY `consultant_email` (`consultant_email`),
  KEY `fk_consultant_city` (`city_id`),
  CONSTRAINT `fk_consultant_city` FOREIGN KEY (`city_id`) REFERENCES `city` (`city_id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `consultant_user` */

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
