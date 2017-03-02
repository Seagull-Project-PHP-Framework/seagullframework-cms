/*==============================================================*/
/* Table: widget                                                */
/*==============================================================*/
CREATE TABLE `widget` (
    `usr_id`                   int(11)             NOT NULL,
    `name`                     varchar(64)         NOT NULL,
    `page`                     varchar(64)         NOT NULL,
    `col`                      tinyint(3)          NOT NULL      DEFAULT 0,
    `position`                 tinyint(3)          NOT NULL      DEFAULT 0,
    `last_updated`             datetime,
    PRIMARY KEY (`usr_id`, `name`, `page`)
) ENGINE=InnoDB;
