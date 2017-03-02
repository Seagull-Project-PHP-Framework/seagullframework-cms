INSERT INTO module VALUES ({SGL_NEXT_ID}, 1, 'simplecategory', 'Categories Management', NULL, NULL, NULL, 'Dmitri Lakachauskis', NULL, NULL, NULL);

SELECT @moduleId    := MAX(module_id) FROM module;
SELECT @memberId    := 2;
SELECT @moderatorId := 3;

--
-- Add permissions
--
INSERT INTO permission VALUES ({SGL_NEXT_ID}, 'simplecategorymgr', '', @moduleId);
SELECT @permissionId := permission_id FROM permission WHERE name = 'simplecategorymgr';
INSERT INTO role_permission VALUES ({SGL_NEXT_ID}, @moderatorId, @permissionId);