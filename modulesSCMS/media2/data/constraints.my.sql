/*==============================================================*/
/* Table: media_type-mime                                       */
/*==============================================================*/
ALTER TABLE `media_type-mime`
    ADD FOREIGN KEY (`media_type_id`) REFERENCES `media_type` (`media_type_id`)
    ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `media_type-mime`
    ADD FOREIGN KEY (`media_mime_id`) REFERENCES `media_mime` (`media_mime_id`)
    ON DELETE CASCADE ON UPDATE CASCADE;

/*==============================================================*/
/* Table: media                                                 */
/*==============================================================*/
ALTER TABLE `media`
    ADD FOREIGN KEY (`media_type_id`) REFERENCES `media_type` (`media_type_id`)
    ON DELETE RESTRICT ON UPDATE CASCADE;
ALTER TABLE `media`
    ADD FOREIGN KEY (`media_mime_id`) REFERENCES `media_mime` (`media_mime_id`)
    ON DELETE RESTRICT ON UPDATE CASCADE;
-- ALTER TABLE `media`
--    ADD FOREIGN KEY (`created_by`) REFERENCES `usr` (`usr_id`)
--    ON DELETE SET NULL ON UPDATE CASCADE;
-- ALTER TABLE `media`
--    ADD FOREIGN KEY (`updated_by`) REFERENCES `usr` (`usr_id`)
--    ON DELETE SET NULL ON UPDATE CASCADE;
