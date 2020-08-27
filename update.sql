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
