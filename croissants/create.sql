CREATE TABLE `USER` (
`pk_user_id` VARCHAR( 3 ) NOT NULL ,
`firstname` VARCHAR( 80 ) NOT NULL ,
`lastname` VARCHAR( 80 ) NOT NULL ,
`email` VARCHAR( 80 ) NOT NULL ,
`password` VARCHAR( 80 ) NOT NULL ,
PRIMARY KEY ( `pk_user_id` )
);

CREATE TABLE `VENDREDI` (
`pk_vendredi_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`date` DATE NOT NULL ,
`fk_user_id` VARCHAR( 3 ) NOT NULL ,
`fk_user_id_sauveteur` VARCHAR( 3 ) NOT NULL ,
`remarque` VARCHAR( 1000 ) NOT NULL
);

ALTER TABLE `croissants`.`VENDREDI` ADD INDEX `fk_user`(`fk_user_id`),
 ADD CONSTRAINT `fk_user` FOREIGN KEY `nom_index` (`fk_user_id`)
    REFERENCES `user` (`pk_user_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE;

CREATE TABLE `WAIT_CONFIRM` (
`pk_fk_user_id` VARCHAR( 3 ) NOT NULL ,
`code` VARCHAR( 32 ) NOT NULL ,
PRIMARY KEY ( `pk_fk_user_id` )
);
ALTER TABLE `WAIT_CONFIRM` ADD `password` VARCHAR( 80 ) NOT NULL AFTER `pk_fk_user_id` ;
ALTER TABLE `WAIT_CONFIRM` ADD UNIQUE ( `pk_fk_user_id` );
ALTER TABLE `WAIT_CONFIRM` ADD `wait_since` DATE NOT NULL ;
ALTER TABLE `USER` ADD UNIQUE ( `email` );
ALTER TABLE `USER` ADD `last_connexion` DATETIME NOT NULL ;

CREATE TABLE `SETTINGS` (
`pk_fk_user_id` VARCHAR( 3 ) NOT NULL ,
`nb_day_before_mail` INT NOT NULL ,
`nb_day_to_see` INT NOT NULL ,
`send_mail_before_date` BOOL NOT NULL ,
`email_visible` BOOL NOT NULL ,
`receive_notification` BOOL NOT NULL ,
PRIMARY KEY ( `pk_fk_user_id` )
);

ALTER TABLE `SETTINGS` ADD `page_accueil` VARCHAR( 80 ) NOT NULL ;

CREATE TABLE `HISTORY` (
`pk_history_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`fk_user_id` VARCHAR( 3 ) NOT NULL ,
`date` DATE NOT NULL ,
`date_from_change` DATETIME NOT NULL ,
`subscribe` TINYINT( 2 ) NOT NULL
);

CREATE TABLE `ALERT_MAIL` (
`fk_user_id` VARCHAR( 3 ) NOT NULL ,
`date_croissant` DATE NOT NULL , 
`error` VARCHAR( 500 ) NOT NULL ,
UNIQUE ( `fk_user_id`, `date_croissant` )
);

CREATE TABLE `LOGIN_IP` (
`login_ip_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`fk_user_id` VARCHAR( 3 ) NOT NULL ,
`ip` VARCHAR( 15 ) NOT NULL ,
`date` DATETIME NOT NULL
)

CREATE TABLE `CROISSANTS_APPORTES` (
`pk_croissants_apportes_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`fk_user_id` VARCHAR( 3 ) NOT NULL ,
`date` DATE NOT NULL
)

CREATE TABLE `RSS` (
`pk_rss_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
`fk_user_id` VARCHAR( 3 ) NOT NULL ,
`fk_vendredi_id` INT NOT NULL ,
`title` VARCHAR( 80 ) NOT NULL ,
`link` VARCHAR( 200 ) NOT NULL ,
`description` VARCHAR( 5000 ) NOT NULL ,
`date` DATETIME NOT NULL
) ENGINE = InnoDB