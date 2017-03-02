ALTER TABLE `category2_trans`
  ADD FOREIGN KEY (`category2_id`) REFERENCES `category2` (`category2_id`)
  ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `category2`
  ADD FOREIGN KEY (`parent_id`) REFERENCES `category2` (`category2_id`)
  ON DELETE CASCADE ON UPDATE CASCADE;