-- MySQL Workbench Synchronization
-- Generated: 2015-06-09 21:41
-- Model: New Model
-- Version: 1.0
-- Project: Name of the project
-- Author: Simon

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

ALTER TABLE `kidup`.`review`
DROP FOREIGN KEY `fk_review_rent1`;

ALTER TABLE `kidup`.`media`
DROP FOREIGN KEY `fk_media_user1`;

ALTER TABLE `kidup`.`item_has_media`
DROP FOREIGN KEY `fk_item_has_media_item1`,
DROP FOREIGN KEY `fk_item_has_media_media1`;

ALTER TABLE `kidup`.`conversation`
DROP COLUMN `booking_id`,
ADD COLUMN `booking_id` INT(11) NOT NULL AFTER `updated_at`;

ALTER TABLE `kidup`.`booking`
ADD COLUMN `request_expires_at` INT(11) NOT NULL;

ALTER TABLE `kidup`.`review`
CHANGE COLUMN `rent_id` `booking_id` INT(11) NOT NULL ,
ADD COLUMN `is_public` TINYINT(4) NULL DEFAULT 0 AFTER `updated_at`;

ALTER TABLE `kidup`.`payin`
DROP COLUMN `service_fee_vat`,
DROP COLUMN `service_fee`,
DROP COLUMN `created_at`,
ADD COLUMN `created_at` INT(11) NULL DEFAULT NULL AFTER `currency_id`,
ADD COLUMN `invoice_id` INT(11) NULL DEFAULT NULL AFTER `amount`,
ADD INDEX `fk_payin_invoice1_idx` (`invoice_id` ASC);

ALTER TABLE `kidup`.`log`
ADD COLUMN `session_id` INT(11) NULL DEFAULT NULL AFTER `created_at`;

ALTER TABLE `kidup`.`payout`
DROP COLUMN `service_fee_vat`,
DROP COLUMN `service_fee`,
CHANGE COLUMN `staus` `status` VARCHAR(45) NOT NULL ,
ADD COLUMN `invoice_id` INT(11) NULL DEFAULT NULL AFTER `updated_at`,
ADD INDEX `fk_payout_invoice1_idx` (`invoice_id` ASC);

ALTER TABLE `kidup`.`mail_message`
DROP COLUMN `mail_account_id`,
ADD COLUMN `mail_account_name` VARCHAR(128) NOT NULL AFTER `created_at`,
ADD INDEX `fk_mail_message_mail_account1_idx` (`mail_account_name` ASC);

ALTER TABLE `kidup`.`payout_method`
CHANGE COLUMN `address` `address` VARCHAR(45) NULL DEFAULT NULL ,
CHANGE COLUMN `city` `bank_name` VARCHAR(256) NOT NULL ,
CHANGE COLUMN `zip_code` `payee_name` VARCHAR(256) NOT NULL ,
ADD COLUMN `user_id` INT(11) NOT NULL AFTER `updated_at`,
ADD INDEX `fk_payout_method_user1_idx` (`user_id` ASC);

CREATE TABLE IF NOT EXISTS `kidup`.`mail_log` (
  `id` VARCHAR(256) NOT NULL,
  `data` TEXT NULL DEFAULT NULL,
  `email` VARCHAR(256) NOT NULL,
  `type` VARCHAR(45) NOT NULL,
  `created_at` INT(11) NOT NULL,
  PRIMARY KEY (`id`))
  ENGINE = InnoDB
  DEFAULT CHARACTER SET = latin1
  COLLATE = latin1_swedish_ci;

CREATE TABLE IF NOT EXISTS `kidup`.`invoice` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `invoice_number` INT(11) NULL DEFAULT NULL,
  `data` TEXT NULL DEFAULT NULL,
  `created_at` INT(11) NULL DEFAULT NULL,
  `updated_at` INT(11) NULL DEFAULT NULL,
  `status` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
  ENGINE = InnoDB
  DEFAULT CHARACTER SET = latin1
  COLLATE = latin1_swedish_ci;

ALTER TABLE `kidup`.`review`
ADD CONSTRAINT `fk_review_rent1`
FOREIGN KEY (`booking_id`)
REFERENCES `kidup`.`booking` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

ALTER TABLE `kidup`.`payin`
ADD CONSTRAINT `fk_payin_invoice1`
FOREIGN KEY (`invoice_id`)
REFERENCES `kidup`.`invoice` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

ALTER TABLE `kidup`.`payout`
ADD CONSTRAINT `fk_payout_invoice1`
FOREIGN KEY (`invoice_id`)
REFERENCES `kidup`.`invoice` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

ALTER TABLE `kidup`.`media`
ADD CONSTRAINT `fk_media_user1`
FOREIGN KEY (`user_id`)
REFERENCES `kidup`.`user` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

ALTER TABLE `kidup`.`mail_message`
ADD CONSTRAINT `fk_mail_message_mail_account1`
FOREIGN KEY (`mail_account_name`)
REFERENCES `kidup`.`mail_account` (`name`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

ALTER TABLE `kidup`.`payout_method`
ADD CONSTRAINT `fk_payout_method_user1`
FOREIGN KEY (`user_id`)
REFERENCES `kidup`.`user` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

ALTER TABLE `kidup`.`item_has_media`
ADD CONSTRAINT `fk_item_has_media_item1`
FOREIGN KEY (`item_id`)
REFERENCES `kidup`.`item` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE,
ADD CONSTRAINT `fk_item_has_media_media1`
FOREIGN KEY (`media_id`)
REFERENCES `kidup`.`media` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
