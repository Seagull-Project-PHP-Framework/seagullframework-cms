INSERT INTO module VALUES ({SGL_NEXT_ID}, 1, 'dashboard', 'Dashboard', NULL, NULL, NULL, 'Andrey Baigozin', NULL, NULL, NULL);

SELECT @moduleId    := MAX(module_id) FROM module;
SELECT @memberId    := 2;
SELECT @moderatorId := 3;

--
-- Add permissions
--
-- INSERT INTO permission VALUES ({SGL_NEXT_ID}, 'dashboardmgr', '', @moduleId);
-- SELECT @permissionId := permission_id FROM permission WHERE name = 'dashboardmgr';
-- INSERT INTO role_permission VALUES ({SGL_NEXT_ID}, @memberId, @permissionId);
