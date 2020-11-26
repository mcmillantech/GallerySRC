/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Author:  John
 * Created: 25-Aug-2020
 */

ALTER TABLE `users` ADD COLUMN `nworks` TINYINT UNSIGNED DEFAULT 6 AFTER `territory4`,
 ADD COLUMN `enddate` VARCHAR(12) AFTER `nworks`;

ALTER TABLE `orders` ADD COLUMN `category` VARCHAR(16) AFTER `phone`;

/*  Following are for Lupe course bookings */ 
CREATE TABLE  `accounts` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `email` varchar(45) NOT NULL,
  `password` varchar(45) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `videos` (
  `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  `workshop` VARCHAR(45),
  `price` INTEGER UNSIGNED,
  `file` VARCHAR(45),
  PRIMARY KEY (`id`)
)
ENGINE = InnoDB;

CREATE TABLE  `videomatrix` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `account` varchar(45) default NULL,
  `workshop` varchar(45) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `events` ADD COLUMN `price` INTEGER UNSIGNED AFTER `address`;

