CREATE SCHEMA IF NOT EXISTS `minecraft_lst` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE `minecraft_lst`;

DROP TABLE IF EXISTS `minecraft_lst`.`participant`;

CREATE TABLE IF NOT EXISTS `minecraft_lst`.`participant` (
    `participant_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `list_id` VARCHAR(25),

    `username` VARCHAR(75) NOT NULL,
    `email` VARCHAR(75),

    `stripe_id` VARCHAR(455) NOT NULL,
    `payment_intent` VARCHAR(455) NOT NULL,
    `payment_status` VARCHAR(455) NOT NULL,

    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE = InnoDB;
