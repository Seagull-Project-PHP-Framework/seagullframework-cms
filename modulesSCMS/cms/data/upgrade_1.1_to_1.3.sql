ALTER TABLE `content`
ADD `version` smallint(6) NOT NULL default '1' COMMENT 'Version number starts at 1' AFTER `content_id`,
ADD `is_current` tinyint(1) NOT NULL default '1' AFTER `version`,
ADD `language_id` char(4) NOT NULL default 'en' AFTER `is_current`,
MODIFY `created_by_id`  int(11) NOT NULL,
MODIFY `updated_by_id`  int(11) NOT NULL,
MODIFY `date_created` datetime NOT NULL,
MODIFY `last_updated` datetime NOT NULL,
DROP INDEX `name`,
DROP PRIMARY KEY,
ADD PRIMARY KEY  (`content_id`,`version`,`language_id`),
ENGINE=InnoDB;

ALTER TABLE `attribute_data`
DROP PRIMARY KEY,
DROP `attribute_data_id`,
ADD `version` smallint(6) NOT NULL default '1' AFTER `content_id`,
ADD `language_id` char(4) NOT NULL default 'en' AFTER `version`,
DROP INDEX `content_id_fk`,
ADD PRIMARY KEY  (`content_id`,`version`,`language_id`,`attribute_id`),
ADD CONSTRAINT `attribute_data_ibfk_1` FOREIGN KEY (`content_id`, `version`, `language_id`) REFERENCES `content` (`content_id`, `version`, `language_id`) ON DELETE CASCADE ON UPDATE CASCADE,
ENGINE=InnoDB;