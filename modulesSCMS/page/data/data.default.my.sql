INSERT INTO module VALUES ({SGL_NEXT_ID}, 1, 'page', 'Pages Management', NULL, NULL, NULL, 'Dmitri Lakachauskis', NULL, NULL, NULL);

SELECT @moduleId    := MAX(module_id) FROM module;
SELECT @memberId    := 2;
SELECT @moderatorId := 3;

--
-- Add permissions
--
INSERT INTO permission VALUES ({SGL_NEXT_ID}, 'pagemgr', '', @moduleId);
SELECT @permissionId := permission_id FROM permission WHERE name = 'pagemgr';
INSERT INTO role_permission VALUES ({SGL_NEXT_ID}, @moderatorId, @permissionId);
INSERT INTO permission VALUES ({SGL_NEXT_ID}, 'routemgr', '', @moduleId);
SELECT @permissionId := permission_id FROM permission WHERE name = 'routemgr';
INSERT INTO role_permission VALUES ({SGL_NEXT_ID}, @moderatorId, @permissionId);