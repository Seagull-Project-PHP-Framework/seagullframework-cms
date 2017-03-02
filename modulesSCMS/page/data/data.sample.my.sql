--
-- Sites
--
INSERT INTO `site` VALUES ({SGL_NEXT_ID}, 'CMS Demo Site One');
INSERT INTO `site` VALUES ({SGL_NEXT_ID}, 'CMS Demo Site Two');

SELECT @site_id_1 := `site_id` FROM `site` WHERE `name` = 'CMS Demo Site One';
SELECT @site_id_2 := `site_id` FROM `site` WHERE `name` = 'CMS Demo Site Two';

--
-- Constants
--
SELECT @layout_id         := NULL;
SELECT @role_admin_id     := 1;
SELECT @role_moderator_id := 3;
SELECT @page_no_parent_id := NULL;

--
-- Pages
--
TRUNCATE TABLE `page`;

INSERT INTO `page` VALUES (1, @page_no_parent_id, 0, 0, 1, @site_id_1, NULL, @layout_id, 1, 1, NOW(), NOW(), @role_moderator_id, @role_moderator_id);
INSERT INTO `page` VALUES (2, @page_no_parent_id, 1, 0, 1, @site_id_1, NULL, @layout_id, 1, 1, NOW(), NOW(), @role_moderator_id, @role_moderator_id);
INSERT INTO `page` VALUES (3, @page_no_parent_id, 2, 0, 1, @site_id_1, NULL, @layout_id, 1, 1, NOW(), NOW(), @role_moderator_id, @role_moderator_id);
INSERT INTO `page` VALUES (4, @page_no_parent_id, 3, 0, 0, @site_id_1, NULL, @layout_id, 1, 1, NOW(), NOW(), @role_moderator_id, @role_moderator_id);
INSERT INTO `page` VALUES (5, @page_no_parent_id, 4, 0, 1, @site_id_1, NULL, @layout_id, 1, 1, NOW(), NOW(), @role_moderator_id, @role_moderator_id);
INSERT INTO `page` VALUES (6, 2, 0, 1, 1, @site_id_1, NULL, @layout_id, 1, 1, NOW(), NOW(), @role_moderator_id, @role_moderator_id);
INSERT INTO `page` VALUES (7, 2, 1, 1, 1, @site_id_1, NULL, @layout_id, 1, 1, NOW(), NOW(), @role_moderator_id, @role_moderator_id);
INSERT INTO `page` VALUES (8, 2, 2, 1, 1, @site_id_1, NULL, @layout_id, 1, 1, NOW(), NOW(), @role_moderator_id, @role_moderator_id);
INSERT INTO `page` VALUES (9, 6, 0, 2, 1, @site_id_1, NULL, @layout_id, 1, 1, NOW(), NOW(), @role_moderator_id, @role_moderator_id);
INSERT INTO `page` VALUES (10, 6, 1, 2, 1, @site_id_1, NULL, @layout_id, 1, 1, NOW(), NOW(), @role_moderator_id, @role_moderator_id);

-- site 2
INSERT INTO `page` VALUES (11, @page_no_parent_id, 0, 0, 0, @site_id_2, NULL, @layout_id, 1, 1, '2009-01-28 14:00:00', '2009-01-29 15:00:00', @role_admin_id, @role_admin_id);
INSERT INTO `page` VALUES (12, @page_no_parent_id, 1, 0, 1, @site_id_2, NULL, @layout_id, 1, 1, '2009-01-28 14:00:00', '2009-01-29 15:00:00', @role_admin_id, @role_admin_id);
INSERT INTO `page` VALUES (13, @page_no_parent_id, 2, 0, 1, @site_id_2, NULL, @layout_id, 1, 1, '2009-01-28 14:00:00', '2009-01-29 15:00:00', @role_admin_id, @role_moderator_id);
INSERT INTO `page` VALUES (14, 11, 0, 1, 1, @site_id_2, NULL, @layout_id, 1, 1, '2009-01-27 15:00:00', '2009-01-27 16:00:00', @role_moderator_id, @role_moderator_id);
INSERT INTO `page` VALUES (15, 11, 1, 1, 1, @site_id_2, NULL, @layout_id, 1, 1, '2009-01-27 15:00:00', '2009-01-27 16:00:00', @role_moderator_id, @role_moderator_id);

--
-- Page translation
--
INSERT INTO `page_trans` VALUES (1, 'en', 'Home', NULL, NULL);
INSERT INTO `page_trans` VALUES (2, 'en', 'About', NULL, NULL);
INSERT INTO `page_trans` VALUES (3, 'en', 'Services', NULL, NULL);
INSERT INTO `page_trans` VALUES (4, 'en', 'Products', NULL, NULL);
INSERT INTO `page_trans` VALUES (5, 'en', 'Feedback', NULL, NULL);
INSERT INTO `page_trans` VALUES (6, 'en', 'Company', NULL, NULL);
INSERT INTO `page_trans` VALUES (7, 'en', 'Staff', NULL, NULL);
INSERT INTO `page_trans` VALUES (8, 'en', 'History', NULL, NULL);
INSERT INTO `page_trans` VALUES (9, 'en', 'Board', NULL, NULL);
INSERT INTO `page_trans` VALUES (10, 'en', 'Reports', NULL, NULL);
INSERT INTO `page_trans` VALUES (1, 'ru', 'Главная', NULL, NULL);
INSERT INTO `page_trans` VALUES (2, 'ru', 'О нас', NULL, NULL);
INSERT INTO `page_trans` VALUES (3, 'ru', 'Услуги', NULL, NULL);

-- site 2
INSERT INTO `page_trans` VALUES (11, 'en', 'Our products', NULL, NULL);
INSERT INTO `page_trans` VALUES (12, 'en', 'Our services', NULL, NULL);
INSERT INTO `page_trans` VALUES (13, 'en', 'Contact us', NULL, NULL);
INSERT INTO `page_trans` VALUES (14, 'en', 'Apple computers', NULL, NULL);
INSERT INTO `page_trans` VALUES (15, 'en', 'PC computers', NULL, NULL);
INSERT INTO `page_trans` VALUES (11, 'ru', 'Наши продукты', NULL, NULL);
INSERT INTO `page_trans` VALUES (12, 'ru', 'Наши услуги', NULL, NULL);
INSERT INTO `page_trans` VALUES (13, 'ru', 'Связаться с нами', NULL, NULL);
INSERT INTO `page_trans` VALUES (14, 'ru', 'Компьютеры Apple', NULL, NULL);
INSERT INTO `page_trans` VALUES (15, 'ru', 'PC компьютеры', NULL, NULL);

--
-- Routes
--
INSERT INTO `route` VALUES (1, @site_id_1, 1, '/', NULL, NULL, 1);
INSERT INTO `route` VALUES (2, @site_id_1, 2, '/about', NULL, NULL, 1);
INSERT INTO `route` VALUES (3, @site_id_1, 3, '/services', NULL, NULL, 1);
INSERT INTO `route` VALUES (4, @site_id_1, 4, '/products', NULL, NULL, 1);
INSERT INTO `route` VALUES (5, @site_id_1, 5, '/feedback', NULL, NULL, 1);
INSERT INTO `route` VALUES (6, @site_id_1, 6, '/about/company', NULL, NULL, 1);
INSERT INTO `route` VALUES (7, @site_id_1, 7, '/about/staff', NULL, NULL, 1);
INSERT INTO `route` VALUES (8, @site_id_1, 8, '/about/history', NULL, NULL, 1);
INSERT INTO `route` VALUES (9, @site_id_1, 9, '/about/company/board', NULL, NULL, 1);
INSERT INTO `route` VALUES (10, @site_id_1, 10, '/about/company/reports', NULL, NULL, 1);