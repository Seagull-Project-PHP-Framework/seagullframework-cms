--
-- Respect order of insertion to match FOREIGN KEY INDEXES
-- or disable FOREIGN KEY CHECKS
--
-- SET FOREIGN_KEY_CHECKS=0;

--
-- Dumping data for table `content_type`
--
INSERT INTO `content_type` VALUES (1, 'Article');
INSERT INTO `content_type` VALUES (2, 'Curriculum Vitae');

--
-- Dumping data for table `attribute`
--
INSERT INTO `attribute` VALUES (1, 2, 1, 'introduction', 'Introduction', NULL, '');
INSERT INTO `attribute` VALUES (2, 3, 1, 'body', 'Body', NULL, '');
INSERT INTO `attribute` VALUES (3, 6, 2, 'website', 'Website', NULL, '');
INSERT INTO `attribute` VALUES (4, 5, 2, 'lastSalaryAmount', 'Last Salary Amount', NULL, '');
INSERT INTO `attribute` VALUES (5, 9, 2, 'dateOfBirth', 'Date of Birth', NULL, '');
INSERT INTO `attribute` VALUES (6, 11, 2, 'availableImmediately', 'Available Immediately', NULL, 'a:1:{s:15:"attributeListId";s:1:"1";}');
INSERT INTO `attribute` VALUES (7, 7, 2, 'picture', 'Picture', NULL, '');
INSERT INTO `attribute` VALUES (8, 3, 2, 'experience', 'Experience', NULL, '');
INSERT INTO `attribute` VALUES (9, 3, 2, 'skills', 'Skills', NULL, '');
INSERT INTO `attribute` VALUES (10, 3, 2, 'hobbies', 'Hobbies', NULL, '');
INSERT INTO `attribute` VALUES (11, 11, 1, 'isPublished', 'Is Published', NULL, 'a:1:{s:15:"attributeListId";s:1:"1";}');

--
-- Dumping data for table `content`
--
INSERT INTO `content` VALUES 
(2, 1, 1, 'en', 2, 4, 'Alouicious Bird Resume', 1, 1, '2006-11-22 20:20:58', '2006-11-23 01:07:34'),
(6, 1, 1, 'en', 1, 4, 'Article with leader and body', 1, 1, '2006-11-22 20:49:26', '2006-11-23 01:11:11'),
(7, 1, 1, 'en', 1, 4, 'unpublished article', 1, 1, '2007-02-06 13:30:24', '2007-02-06 13:30:24'),
(8, 1, 1, 'en', 1, 4, 'published article', 1, 1, '2007-02-06 13:30:45', '2007-02-06 13:30:45'),
(9, 1, 1, 'en', 1, 4, 'another article', 1, 1, '2007-02-06 13:30:45', '2007-02-06 13:30:45');

--
-- Dumping data for table `attribute_data`
--
INSERT INTO `attribute_data` VALUES 
(2, 1, 'en', 3, 'http://www.example.com', ''),
(2, 1, 'en', 4, '50 000$', ''),
(2, 1, 'en', 5, '1983-11-22', ''),
(2, 1, 'en', 6, '1', ''),
(2, 1, 'en', 7, '3', ''),
(2, 1, 'en', 8, '<p class="">Freelance PHP Developer Seagull Project</p><ul><li>did code and CSS for project site with registration, newsletter and member features</li><li>migrated project to Trac for project management and issue tracking</li><li>helped integrate a number of 3rd party projects into framework: Serendipity, SimpleTest, Gallery2, FUDforum, phpOpenTracker</li><li>built a URI aliasing system for search engine friendly URIs</li><li>created a task-based installer with multistep Quickform wizard</li><li>implemented all modules as PEAR packages with relevant dependency solving, setup PEAR channel</li><li>built a system for point-and-click system upgrades in the web interface, similar to Firefox extensions</li></ul>', ''),
(2, 1, 'en', 9, '<ul><li><span style="font-weight: bold;">Programming Languages:</span> PHP, Java, JSP/Servlets, ASP/VBscript, JavaScript, Python, C, Perl, SmallTalk</li></ul><ul><li><span style="font-weight: bold;">Methodologies:</span> XP, Agile, Scrum</li><li><span style="font-weight: bold;">Markup Languages:</span> XML, XML Schema, XSLT, XHTML, XUL, CSS, WML, DocBook</li><li><span style="font-weight: bold;">Web Standards:</span>FOAF, RSS, Atom, vCARD, OpenID</li><li><span style="font-weight: bold;">Databases: </span>MySQL, SQL Server, PostgreSQL, Oracle, Access\nMessaging</li></ul>', ''),
(2, 1, 'en', 10, '<p>painting, drawing, music</p>', ''),
(6, 1, 'en', 1, 'This is a textarea', ''),
(6, 1, 'en', 2, 'And this is some richtext', ''),
(7, 1, 'en', 1, 'foo', NULL),
(7, 1, 'en', 2, 'bar', NULL),
(7, 1, 'en', 11, '-1', NULL),
(8, 1, 'en', 1, 'foo', NULL),
(8, 1, 'en', 2, 'bar&nbsp; <br>', NULL),
(8, 1, 'en', 11, '1', NULL),
(9, 1, 'en', 1, 'foo', NULL),
(9, 1, 'en', 2, 'bar', NULL),
(9, 1, 'en', 11, '1', NULL);

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `label`, `description`, `perms`, `parent_id`, `root_id`, `left_id`, `right_id`, `order_id`, `level_id`) VALUES
(2, 'example', '', NULL, 1, 1, 2, 3, 1, 2),
(3, 'OtherRoot', '', NULL, 0, 3, 1, 2, 1, 1),
(4, 'Shop', '', NULL, 0, 4, 1, 16, 2, 1),
(6, 'Printers', '', NULL, 4, 4, 8, 9, 2, 2),
(5, 'Monitors', '', NULL, 4, 4, 2, 7, 1, 2),
(13, 'CRT', '', NULL, 5, 4, 3, 4, 1, 3),
(7, 'Laptop Computers', '', NULL, 4, 4, 10, 15, 3, 2),
(9, 'Notebook', '', NULL, 7, 4, 11, 12, 1, 3),
(11, 'Tablet PC', '', NULL, 7, 4, 13, 14, 2, 3),
(15, 'LCD', '', NULL, 5, 4, 5, 6, 2, 3);

--
-- Dumping data for table `content-category`
--

-- "Alouicious Bird Resume" is member of "Printers" and "LCD"
-- "Article with leader and body" is member of "Printers"
-- "unpublished article" is member of "LCD"
-- "published article" is member of "CRT"
INSERT INTO `content-category` VALUES (2, 6);
INSERT INTO `content-category` VALUES (6, 6);
INSERT INTO `content-category` VALUES (2, 15);
INSERT INTO `content-category` VALUES (7, 15);
INSERT INTO `content-category` VALUES (8, 13);

--
-- Dumping data for table `content-content`
--

-- link 4 articles to resume
INSERT INTO `content-content` VALUES (2, 7);
INSERT INTO `content-content` VALUES (2, 8);
INSERT INTO `content-content` VALUES (2, 9);

INSERT INTO `content-content` VALUES (9, 8);

--
-- re-enable FOREIGN KEY CHECKS if needed
--
-- SET FOREIGN_KEY_CHECKS=1;
