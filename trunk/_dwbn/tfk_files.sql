CREATE TABLE `tfk_files` (
`id` INT( 11 ) UNSIGNED NOT NULL ,
`extension` VARCHAR( 10 ) NOT NULL ,
`parent_id` INT( 11 ) UNSIGNED NOT NULL ,
`member_id` INT( 11 ) NOT NULL ,
`seed` VARCHAR( 10 ) NOT NULL ,
`rec_dateadd` DATETIME NOT NULL ,
PRIMARY KEY ( `id` ) ,
INDEX ( `parent_id` )
) ENGINE = MYISAM ;

ALTER TABLE `tfk_files` DROP INDEX `parent_id` ,
ADD INDEX `id_type_index` ( `parent_id` , `parent_type` ) 
ALTER TABLE `tfk_files` ADD `realname` VARCHAR( 255 ) NOT NULL AFTER `path` ;
