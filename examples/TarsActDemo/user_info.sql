/*
SQLyog Ultimate v11.24 (64 bit)
MySQL - 5.6.30 : Database - tars_test
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`tars_test` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;

USE `tars_test`;

/*Table structure for table `user_info` */

DROP TABLE IF EXISTS `user_info`;

CREATE TABLE `user_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nickname` varchar(200) NOT NULL,
  `password` char(40) DEFAULT NULL,
  `age` int(11) NOT NULL,
  `status` tinyint(4) DEFAULT NULL COMMENT '1正常 -1删除',
  `avatar` varchar(255) DEFAULT NULL,
  `createTime` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=80003 DEFAULT CHARSET=utf8mb4;

/*Data for the table `user_info` */

insert  into `user_info`(`id`,`nickname`,`password`,`age`,`status`,`avatar`,`createTime`) values (80001,'testuser80001','e10adc3949ba59abbe56e057f20f883e',18,1,'https://qidian.qpic.cn/qd_face/349573/3398262/100','2018-08-29 17:20:25'),(80002,'yong','e10adc3949ba59abbe56e057f20f883e',23,1,'https://qidian.qpic.cn/qd_face/349573/3398262/100','2018-08-30 11:57:20');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
