
--
-- Constants
--

-- roles
SELECT @roleAnyId       := -2;
SELECT @roleAdminId     := 1;
SELECT @roleMemberId    := 2;
SELECT @roleModeratorId := 3;

-- Add new role
INSERT INTO `role` VALUES(@roleModeratorId, 'moderator', NULL, NULL, NULL, NULL, NULL);

--
-- Grant permissions to "Moderator"
--
SELECT @permissionId := `permission_id` FROM `permission` WHERE `name` = 'contentmgr';
INSERT INTO `role_permission` VALUES ({SGL_NEXT_ID}, @roleModeratorId, @permissionId);

-- New preference
INSERT INTO `preference` VALUES (9, 'admin theme', 'default_admin');

--
-- Add navigation block
--
INSERT INTO `block` VALUES ({SGL_NEXT_ID}, 'Navigation_Block_Navigation', 'Admin menu primary', '', '', 2, 'AdminNavPri', 1, 0, 'a:9:{s:15:"startParentNode";s:2:"10";s:10:"startLevel";s:1:"0";s:14:"levelsToRender";s:1:"1";s:9:"collapsed";s:1:"1";s:10:"showAlways";s:1:"1";s:12:"cacheEnabled";s:1:"1";s:11:"breadcrumbs";s:1:"0";s:8:"renderer";s:16:"TemplateRenderer";s:8:"template";s:20:"adminNavPrimary.html";}');
INSERT INTO `block` VALUES ({SGL_NEXT_ID}, 'Navigation_Block_Navigation', 'Admin menu secondary', '', '', 2, 'AdminNavSec', 1, 0, 'a:9:{s:15:"startParentNode";s:2:"10";s:10:"startLevel";s:1:"1";s:14:"levelsToRender";s:1:"1";s:9:"collapsed";s:1:"1";s:10:"showAlways";s:1:"1";s:12:"cacheEnabled";s:1:"1";s:11:"breadcrumbs";s:1:"0";s:8:"renderer";s:16:"TemplateRenderer";s:8:"template";s:22:"adminNavSecondary.html";}');

SELECT @blockIdAdminNavPrimary:= block_id
FROM   `block`
WHERE  `name` = 'Navigation_Block_Navigation' AND `title` = 'Admin menu primary';

SELECT @blockIdAdminNavSecondary:= block_id
FROM   `block`
WHERE  `name` = 'Navigation_Block_Navigation' AND `title` = 'Admin menu secondary';

-- assignment
INSERT INTO `block_assignment` VALUES (@blockIdAdminNavPrimary, 0);
INSERT INTO `block_role` VALUES (@blockIdAdminNavPrimary, @roleAdminId);
INSERT INTO `block_role` VALUES (@blockIdAdminNavPrimary, @roleModeratorId);

INSERT INTO `block_assignment` VALUES (@blockIdAdminNavSecondary, 0);
INSERT INTO `block_role` VALUES (@blockIdAdminNavSecondary, @roleAdminId);
INSERT INTO `block_role` VALUES (@blockIdAdminNavSecondary, @roleModeratorId);