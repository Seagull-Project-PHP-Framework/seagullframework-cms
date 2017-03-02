INSERT INTO module VALUES ({SGL_NEXT_ID}, 1, 'simplecms', 'Simple CMS', NULL, NULL, NULL, 'Dmitri Lakachauskis', NULL, NULL, NULL);

SELECT @moduleId    := MAX(module_id) FROM module;
SELECT @memberId    := 2;
SELECT @moderatorId := 3;

--
-- Add permissions
--
INSERT INTO `permission` VALUES ({SGL_NEXT_ID}, 'cmscontentmgr', '', @moduleId);
SELECT @permissionId := `permission_id` FROM `permission` WHERE `name` = 'cmscontentmgr';
INSERT INTO `role_permission` VALUES ({SGL_NEXT_ID}, @moderatorId, @permissionId);

INSERT INTO `permission` VALUES ({SGL_NEXT_ID}, 'cmsactivitymgr', '', @moduleId);
SELECT @permissionId := `permission_id` FROM `permission` WHERE `name` = 'cmsactivitymgr';
INSERT INTO `role_permission` VALUES ({SGL_NEXT_ID}, @moderatorId, @permissionId);

INSERT INTO `permission` VALUES ({SGL_NEXT_ID}, 'cmscontenttypemgr', '', @moduleId);
SELECT @permissionId := `permission_id` FROM `permission` WHERE `name` = 'cmscontenttypemgr';
INSERT INTO `role_permission` VALUES ({SGL_NEXT_ID}, @moderatorId, @permissionId);

INSERT INTO `permission` VALUES ({SGL_NEXT_ID}, 'cmsattriblistmgr', '', @moduleId);
SELECT @permissionId := `permission_id` FROM `permission` WHERE `name` = 'cmsattriblistmgr';
INSERT INTO `role_permission` VALUES ({SGL_NEXT_ID}, @moderatorId, @permissionId);

INSERT INTO `permission` VALUES ({SGL_NEXT_ID}, 'cmsexportermgr', '', @moduleId);
SELECT @permissionId := `permission_id` FROM `permission` WHERE `name` = 'cmsexportermgr';
INSERT INTO `role_permission` VALUES ({SGL_NEXT_ID}, @moderatorId, @permissionId);
