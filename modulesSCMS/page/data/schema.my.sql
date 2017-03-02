DROP TABLE `page`;

--
-- page
--
CREATE TABLE `page`
(
  `page_id`         INT(11)             NOT NULL,
  `parent_id`       INT(11)                                  DEFAULT NULL,
  `order_id`        INT(11)             NOT NULL             DEFAULT 0,
  `level_id`        INT(11)             NOT NULL             DEFAULT 0,
  `status`          TINYINT(1)          NOT NULL             DEFAULT 1,
  `site_id`         INT(11)             NOT NULL,

  `content_id`      INT(11)                                  DEFAULT NULL,
  `layout_id`       INT(11)                                  DEFAULT NULL,

  `appears_in_nav`  TINYINT(1)          NOT NULL             DEFAULT 1,
  `are_comments_allowed` TINYINT(1)     NOT NULL             DEFAULT 1,

  `date_created`    DATETIME            NOT NULL,
  `last_updated`    DATETIME            NOT NULL,
  `created_by`      INT(11)             NOT NULL,
  `updated_by`      INT(11)             NOT NULL,

  PRIMARY KEY (`page_id`),
  KEY (`parent_id`),
  KEY (`status`),
  KEY (`site_id`),
  KEY (`created_by`),
  KEY (`updated_by`),
  KEY (`parent_id`, `status`, `appears_in_nav`)

) ENGINE=InnoDB;

--
-- page trans
--
CREATE TABLE `page_trans`
(
  `page_id`          INT(11)            NOT NULL,
  `language_id`      VARCHAR(5)         NOT NULL,
  `title`            VARCHAR(255)                            DEFAULT NULL,
  `meta_desc`        TEXT                                    DEFAULT NULL,
  `meta_key`         TEXT                                    DEFAULT NULL,

  PRIMARY KEY (`page_id`, `language_id`)
) ENGINE=InnoDB;

--
-- route
--
CREATE TABLE `route`
(
  `route_id`        INT(11)             NOT NULL,
  `site_id`         INT(11)             NOT NULL,
  `page_id`         INT(11)             DEFAULT NULL,
  `route`           TEXT                NOT NULL,
  `description`     TEXT                DEFAULT NULL,
  `route_data`      TEXT                DEFAULT NULL,
  `is_active`       TINYINT(1)          NOT NULL             DEFAULT 1,

  PRIMARY KEY (`route_id`),
  KEY (`site_id`),
  KEY (`page_id`)
) ENGINE=InnoDB;

--
-- site
--
CREATE TABLE `site`
(
  `site_id`         INT(11)             NOT NULL,
  `name`            VARCHAR(255)        NOT NULL,

  PRIMARY KEY (`site_id`)
) ENGINE=InnoDB;