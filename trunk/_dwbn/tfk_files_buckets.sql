CREATE TABLE `tfk_files_buckets` (
`id` INT( 11 ) NOT NULL ,
`name` VARCHAR( 50 ) NOT NULL ,
`parent_id` INT( 11 ) NOT NULL ,
`parent_type` VARCHAR( 10 ) NOT NULL ,
`rec_dateadd` DATETIME NOT NULL ,
PRIMARY KEY ( `id` )
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci;

ALTER TABLE `tfk_files_buckets` ADD UNIQUE (
`name` ,
`parent_id` ,
`parent_type`
)
