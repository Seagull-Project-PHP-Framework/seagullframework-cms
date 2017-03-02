--
-- Dumping data for table `content_type`
--
INSERT INTO `content_type` VALUES (1, 'Article');
INSERT INTO `content_type` VALUES (2, 'Curriculum Vitae');

--
-- Dumping data for table `attribute`
--
INSERT INTO `attribute` VALUES
(1, 2, 1, 'introduction', 'Introduction', NULL, ''),
(2, 3, 1, 'body', 'Body', NULL, ''),
(3, 6, 2, 'website', 'Website', NULL, ''),
(4, 5, 2, 'lastSalaryAmount', 'Last Salary Amount', NULL, ''),
(5, 9, 2, 'dateOfBirth', 'Date of Birth', NULL, ''),
(6, 11, 2, 'availableImmediately', 'Available Immediately', NULL, 'a:1:{s:15:"attributeListId";s:1:"1";}'),
(7, 7, 2, 'picture', 'Picture', NULL, ''),
(8, 3, 2, 'experience', 'Experience', NULL, ''),
(9, 3, 2, 'skills', 'Skills', NULL, ''),
(10, 3, 2, 'hobbies', 'Hobbies', NULL, ''),
(11, 11, 1, 'isPublished', 'Is Published', NULL, 'a:1:{s:15:"attributeListId";s:1:"1";}');


--
-- Dumping data for table `content`
--
INSERT INTO `content` VALUES
(1, 1, 1, 'en', 1, 4, 'Article with leader and body', 1, 1, '2006-11-22 20:49:26', '2006-11-23 01:11:11'),
(1, 2, 0, 'en', 1, 4, 'Article with leader and body', 1, 1, '2008-03-29 14:53:03', '2008-03-29 14:53:18'),
(2, 1, 0, 'en', 2, 4, 'Alouicious Bird Resume', 1, 1, '2006-11-22 20:20:58', '2006-11-23 01:07:34'),
(2, 2, 1, 'en', 2, 4, 'Alouicious Bird Resume', 1, 1, '2008-03-29 14:53:55', '2008-03-29 14:53:55');


--
-- Dumping data for table `attribute_data`
--
INSERT INTO `attribute_data` VALUES
(1, 1, 'en', 1, 'This is a textarea', ''),
(1, 1, 'en', 2, 'And this is some richtext', ''),
(1, 2, 'en', 1, 'This is a modified introduction', NULL),
(1, 2, 'en', 2, 'And this is some richtext in version 2.<br>', NULL),
(2, 1, 'en', 3, 'http://www.example.com', ''),
(2, 1, 'en', 4, '50 000$', ''),
(2, 1, 'en', 5, '1983-11-22', ''),
(2, 1, 'en', 6, '1', ''),
(2, 1, 'en', 7, '3', ''),
(2, 1, 'en', 8, '<p class="">Freelance PHP Developer Seagull Project</p><ul><li>did code and CSS for project site with registration, newsletter and member features</li><li>migrated project to Trac for project management and issue tracking</li><li>helped integrate a number of 3rd party projects into framework: Serendipity, SimpleTest, Gallery2, FUDforum, phpOpenTracker</li><li>built a URI aliasing system for search engine friendly URIs</li><li>created a task-based installer with multistep Quickform wizard</li><li>implemented all modules as PEAR packages with relevant dependency solving, setup PEAR channel</li><li>built a system for point-and-click system upgrades in the web interface, similar to Firefox extensions</li></ul>', ''),
(2, 1, 'en', 9, '<ul><li><span style="font-weight: bold;">Programming Languages:</span> PHP, Java, JSP/Servlets, ASP/VBscript, JavaScript, Python, C, Perl, SmallTalk</li></ul><ul><li><span style="font-weight: bold;">Methodologies:</span> XP, Agile, Scrum</li><li><span style="font-weight: bold;">Markup Languages:</span> XML, XML Schema, XSLT, XHTML, XUL, CSS, WML, DocBook</li><li><span style="font-weight: bold;">Web Standards:</span>FOAF, RSS, Atom, vCARD, OpenID</li><li><span style="font-weight: bold;">Databases: </span>MySQL, SQL Server, PostgreSQL, Oracle, Access\nMessaging</li></ul>', ''),
(2, 1, 'en', 10, '<p>painting, drawing, music</p>', ''),
(2, 2, 'en', 3, 'http://www.example.com', NULL),
(2, 2, 'en', 4, '60 000$', NULL),
(2, 2, 'en', 5, '1983-11-22', NULL),
(2, 2, 'en', 6, '1', NULL),
(2, 2, 'en', 7, '4', NULL),
(2, 2, 'en', 8, '<p class="">Freelance PHP Developer Seagull Project</p><ul><li>did code and CSS for project site with registration, newsletter and member features</li><li>migrated project to Trac for project management and issue tracking</li><li>helped integrate a number of 3rd party projects into framework: Serendipity, SimpleTest, Gallery2, FUDforum, phpOpenTracker</li><li>built a URI aliasing system for search engine friendly URIs</li><li>created a task-based installer with multistep Quickform wizard</li><li>implemented all modules as PEAR packages with relevant dependency solving, setup PEAR channel</li><li>built a system for point-and-click system upgrades in the web interface, similar to Firefox extensions</li></ul>', NULL),
(2, 2, 'en', 9, '<ul><li><span style="font-weight: bold;">Programming Languages:</span> PHP, Java, JSP/Servlets, ASP/VBscript, JavaScript, Python, C, Perl, SmallTalk</li></ul><ul><li><span style="font-weight: bold;">Methodologies:</span> XP, Agile, Scrum</li><li><span style="font-weight: bold;">Markup Languages:</span> XML, XML Schema, XSLT, XHTML, XUL, CSS, WML, DocBook</li><li><span style="font-weight: bold;">Web Standards:</span>FOAF, RSS, Atom, vCARD, OpenID</li><li><span style="font-weight: bold;">Databases: </span>MySQL, SQL Server, PostgreSQL, Oracle, Access\r\nMessaging</li></ul>', NULL),
(2, 2, 'en', 10, '<p>painting, drawing, music</p>', NULL);

--
-- Categories
--
INSERT INTO `category` VALUES (2, 'example', '', NULL, 1, 1, 2, 3, 1, 2);
INSERT INTO `category` VALUES (3, 'OtherRoot', '', NULL, 0, 3, 1, 2, 1, 1);
INSERT INTO `category` VALUES (4, 'Shop', '', NULL, 0, 4, 1, 16, 2, 1);
INSERT INTO `category` VALUES (6, 'Printers', '', NULL, 4, 4, 8, 9, 2, 2);
INSERT INTO `category` VALUES (5, 'Monitors', '', NULL, 4, 4, 2, 7, 1, 2);
INSERT INTO `category` VALUES (13, 'CRT', '', NULL, 5, 4, 3, 4, 1, 3);
INSERT INTO `category` VALUES (7, 'Laptop Computers', '', NULL, 4, 4, 10, 15, 3, 2);
INSERT INTO `category` VALUES (9, 'Notebook', '', NULL, 7, 4, 11, 12, 1, 3);
INSERT INTO `category` VALUES (11, 'Tablet PC', '', NULL, 7, 4, 13, 14, 2, 3);
INSERT INTO `category` VALUES (15, 'LCD', '', NULL, 5, 4, 5, 6, 2, 3);

--
-- Default mime types
--
SELECT @mediaMimeId_gif := `media_mime_id` FROM `media_mime` WHERE `name` = 'gif image';
SELECT @mediaMimeId_jpg := `media_mime_id` FROM `media_mime` WHERE `name` = 'jpeg image';
SELECT @mediaMimeId_png := `media_mime_id` FROM `media_mime` WHERE `name` = 'png image';

--
-- Media types
--
INSERT INTO `media_type` VALUES ({SGL_NEXT_ID}, 'category', 'category image');
SELECT @mediaTypeId_category := `media_type_id` FROM `media_type` WHERE `name` = 'category';

INSERT INTO `media_type-mime` VALUES (@mediaTypeId_category, @mediaMimeId_gif);
INSERT INTO `media_type-mime` VALUES (@mediaTypeId_category, @mediaMimeId_jpg);
INSERT INTO `media_type-mime` VALUES (@mediaTypeId_category, @mediaMimeId_png);