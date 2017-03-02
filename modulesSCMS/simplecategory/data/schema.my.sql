/*==============================================================*/
/* Table: category2                                             */
/*==============================================================*/
CREATE TABLE `category2`
(
  `category2_id`    INT(11)             NOT NULL,
  `parent_id`       INT(11)                                  DEFAULT NULL,
  `order_id`        INT(11)             NOT NULL             DEFAULT 0,
  `level_id`        INT(11)             NOT NULL             DEFAULT 0,
  `is_active`       TINYINT(1)          NOT NULL             DEFAULT 1,

  PRIMARY KEY (`category2_id`),
  KEY (`parent_id`),
  KEY (`is_active`),
  KEY (`parent_id`, `is_active`)

) ENGINE=InnoDB;

CREATE TABLE `category2_trans`
(
  `category2_id`     INT(11)            NOT NULL,
  `language_id`      VARCHAR(5)         NOT NULL,
  `name`             VARCHAR(128)                            DEFAULT NULL,
  `description`      TEXT                                    DEFAULT NULL,

  PRIMARY KEY (`category2_id`, `language_id`)
) ENGINE=InnoDB;