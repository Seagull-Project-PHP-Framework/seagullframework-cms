--
-- Table structure for table `content`
--
CREATE TABLE `content` (
  `content_id` int(11) NOT NULL default '0',
  `version` smallint(6) NOT NULL default '1',
  `is_current` tinyint(1) NOT NULL default '0',
  `language_id` varchar(5) NOT NULL default 'en',
  `content_type_id` int(11) NOT NULL default '0',
  `status` smallint(6) NOT NULL,
  `name` varchar(255) NOT NULL default '',
  `created_by_id` int(11) NOT NULL,
  `updated_by_id` int(11) NOT NULL,
  `date_created` datetime NOT NULL,
  `last_updated` datetime NOT NULL,
  PRIMARY KEY  (`content_id`,`version`,`language_id`),
  KEY `content_type_id_fk` (`content_type_id`),
  KEY `version` (`version`),
  KEY `language_id` (`language_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `content_type`
--

CREATE TABLE `content_type` (
  `content_type_id` int(11) NOT NULL default '0',
  `name` varchar(64) default NULL,
  PRIMARY KEY  (`content_type_id`),
  UNIQUE KEY `content_type_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `attribute`
--

CREATE TABLE `attribute` (
  `attribute_id` int(11) NOT NULL default '0',
  `attribute_type_id` smallint(6) default NULL,
  `content_type_id` int(11) NOT NULL default '0',
  `name` varchar(64) default NULL,
  `alias` varchar(128) NOT NULL default '',
  `desc` text default NULL,
  `params` text,
  PRIMARY KEY  (`attribute_id`),
  KEY `content_type_id_fk` (`content_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `attribute_data`
--

CREATE TABLE `attribute_data` (
  `content_id` int(11) NOT NULL default '0',
  `version` smallint(6) NOT NULL default '1',
  `language_id` varchar(5) NOT NULL default 'en',
  `attribute_id` int(11) NOT NULL default '0',
  `value` text,
  `params` text,
  PRIMARY KEY  (`content_id`,`version`,`language_id`,`attribute_id`),
  KEY `version` (`version`),
  KEY `language_id` (`language_id`),
  KEY `attribute_id` (`attribute_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Constraints for table `attribute_data`
--
ALTER TABLE `attribute_data`
  ADD CONSTRAINT `attribute_data_ibfk_1` FOREIGN KEY (`content_id`, `version`, `language_id`) REFERENCES `content` (`content_id`, `version`, `language_id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- --------------------------------------------------------

--
-- Table structure for table `attribute_type`
--

CREATE TABLE `attribute_type` (
  `attribute_type_id` int(11) NOT NULL default '0',
  `name` varchar(64) NOT NULL default '',
  `alias` varchar(255) NOT NULL default '',
  `params` text,
  PRIMARY KEY  (`attribute_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `attribute_list` (
  `attribute_list_id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `params` text NOT NULL,
  PRIMARY KEY  (`attribute_list_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*==============================================================*/
/* Table: category                                              */
/*==============================================================*/

CREATE TABLE `category` (
  `category_id` int(11) NOT NULL default '0',
  `label` varchar(32) default NULL,
  `description` text NOT NULL,
  `perms` varchar(32) default NULL,
  `parent_id` int(11) default NULL,
  `root_id` int(11) default NULL,
  `left_id` int(11) default NULL,
  `right_id` int(11) default NULL,
  `order_id` int(11) default NULL,
  `level_id` int(11) default NULL,
  PRIMARY KEY  (`category_id`),
  KEY `AK_key_root_id` (`root_id`),
  KEY `AK_key_order_id` (`order_id`),
  KEY `AK_key_left_id` (`left_id`),
  KEY `AK_key_right_id` (`right_id`),
  KEY `AK_id_root_l_r` (`category_id`,`root_id`,`left_id`,`right_id`),
  KEY `AK_key_level_id` (`level_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*==============================================================*/
/* Index: parent_fk                                             */
/*==============================================================*/
create index parent_fk on category
(
   parent_id
);

CREATE TABLE `content-category` (
  `content_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  UNIQUE KEY `content_id` (`content_id`,`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Manages associations so content can be assigned to cats';

CREATE TABLE `category-media` (
  `category_id` int(11) NOT NULL,
  `media_id` int(11) NOT NULL,
  UNIQUE KEY `content_id` (`media_id`,`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Manages associations so images can be assigned to categories';

CREATE TABLE `content-content` (
  `content_id_pk` int(11) NOT NULL,
  `content_id_fk` int(11) NOT NULL,
  UNIQUE KEY `content_id` (`content_id_pk`,`content_id_fk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Manages associations so content can link to other content';

CREATE TABLE `page` (
  `page_id` int(11) NOT NULL,
  `title` varchar(32) default NULL,
  `resource_uri` varchar(128) default NULL,
  `perms` varchar(32) default NULL,
  `parent_id` int(11) default NULL,
  `root_id` int(11) default NULL,
  `left_id` int(11) default NULL,
  `right_id` int(11) default NULL,
  `order_id` int(11) default NULL,
  `level_id` int(11) default NULL,
  `is_enabled` smallint(6) default NULL,
  `is_static` smallint(6) default NULL,
  `access_key` char(1) default NULL,
  `rel` varchar(16) default NULL,
  PRIMARY KEY  (`page_id`),
  KEY `AK_key_root_id` (`root_id`),
  KEY `AK_key_order_id` (`order_id`),
  KEY `AK_key_left_id` (`left_id`),
  KEY `AK_key_right_id` (`right_id`),
  KEY `AK_id_root_l_r` (`page_id`,`root_id`,`left_id`,`right_id`),
  KEY `AK_key_level_id` (`level_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

