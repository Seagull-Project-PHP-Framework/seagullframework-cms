INSERT INTO module VALUES ({SGL_NEXT_ID}, 1, 'admin', 'Admin', NULL, NULL, NULL, 'Dmitri Lakachauskis', NULL, NULL, NULL);

SELECT @moduleId    := MAX(module_id) FROM module;
SELECT @memberId    := 2;
SELECT @moderatorId := 3;

--
-- Add permissions
--
INSERT INTO `permission` VALUES ({SGL_NEXT_ID}, 'adminmgr', '', @moduleId);
SELECT @permissionId := `permission_id` FROM `permission` WHERE `name` = 'adminmgr';
INSERT INTO `role_permission` VALUES ({SGL_NEXT_ID}, @moderatorId, @permissionId);
