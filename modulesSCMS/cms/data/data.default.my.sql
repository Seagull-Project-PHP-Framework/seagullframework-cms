--
-- Dumping data for table `attribute_type`
--

INSERT INTO `attribute_type` VALUES (1, 'TEXT', 'Text', '');
INSERT INTO `attribute_type` VALUES (2, 'LARGETEXT', 'Large text', '');
INSERT INTO `attribute_type` VALUES (3, 'RICHTEXT', 'Rich text', '');
INSERT INTO `attribute_type` VALUES (4, 'INT', 'Integer', '');
INSERT INTO `attribute_type` VALUES (5, 'FLOAT', 'Float', '');
INSERT INTO `attribute_type` VALUES (6, 'URL', 'Url', '');
INSERT INTO `attribute_type` VALUES (7, 'FILE', 'File', '');
INSERT INTO `attribute_type` VALUES (8, 'CHOICE', 'Checkbox', '');
INSERT INTO `attribute_type` VALUES (9, 'DATE', 'Date', '');
INSERT INTO `attribute_type` VALUES (10, 'LIST', 'Combo', '');
INSERT INTO `attribute_type` VALUES (11, 'RADIO', 'Radio', '');

-- sample 'YesNo' list type
INSERT INTO `attribute_list` VALUES ({SGL_NEXT_ID}, 'yesNo', 'a:3:{s:4:"type";s:6:"select";s:8:"multiple";b:0;s:11:"data-inline";a:2:{i:1;s:3:"Yes";i:-1;s:2:"No";}}');


INSERT INTO `module` VALUES ({SGL_NEXT_ID}, 1, 'cms', 'Content Management', 'The Content Management module allows you to create and modify your own content.', '', '48/module_block.png', 'D Turner, J Casanova, D Lakachauskis', NULL, 'Community', 'beta');

SELECT @moduleId := MAX(module_id) FROM module;

INSERT INTO permission VALUES ({SGL_NEXT_ID}, 'contenttypemgr', '', @moduleId);
INSERT INTO permission VALUES ({SGL_NEXT_ID}, 'contenttypemgr_cmd_add', '', @moduleId);
INSERT INTO permission VALUES ({SGL_NEXT_ID}, 'contenttypemgr_cmd_insert', '', @moduleId);
INSERT INTO permission VALUES ({SGL_NEXT_ID}, 'contenttypemgr_cmd_edit', '', @moduleId);
INSERT INTO permission VALUES ({SGL_NEXT_ID}, 'contenttypemgr_cmd_update', '', @moduleId);
INSERT INTO permission VALUES ({SGL_NEXT_ID}, 'contenttypemgr_cmd_delete', '', @moduleId);
INSERT INTO permission VALUES ({SGL_NEXT_ID}, 'contenttypemgr_cmd_list', '', @moduleId);
INSERT INTO permission VALUES ({SGL_NEXT_ID}, 'contentmgr', '', @moduleId);
INSERT INTO permission VALUES ({SGL_NEXT_ID}, 'contentmgr_cmd_add', '', @moduleId);
INSERT INTO permission VALUES ({SGL_NEXT_ID}, 'contentmgr_cmd_insert', '', @moduleId);
INSERT INTO permission VALUES ({SGL_NEXT_ID}, 'contentmgr_cmd_edit', '', @moduleId);
INSERT INTO permission VALUES ({SGL_NEXT_ID}, 'contentmgr_cmd_update', '', @moduleId);
INSERT INTO permission VALUES ({SGL_NEXT_ID}, 'contentmgr_cmd_changeStatus', '', @moduleId);
INSERT INTO permission VALUES ({SGL_NEXT_ID}, 'contentmgr_cmd_delete', '', @moduleId);
INSERT INTO permission VALUES ({SGL_NEXT_ID}, 'contentmgr_cmd_view', '', @moduleId);
INSERT INTO permission VALUES ({SGL_NEXT_ID}, 'contentmgr_cmd_list', '', @moduleId);
INSERT INTO permission VALUES ({SGL_NEXT_ID}, 'contentassocmgr', '', @moduleId);
INSERT INTO permission VALUES ({SGL_NEXT_ID}, 'contentassocmgr_cmd_editMedia', '', @moduleId);
INSERT INTO permission VALUES ({SGL_NEXT_ID}, 'contentassocmgr_cmd_editLink', '', @moduleId);
INSERT INTO permission VALUES ({SGL_NEXT_ID}, 'querymgr', '', @moduleId);
INSERT INTO permission VALUES ({SGL_NEXT_ID}, 'querymgr_cmd_list', '', @moduleId);
INSERT INTO permission VALUES ({SGL_NEXT_ID}, 'querymgr_cmd_search', '', @moduleId);

#member role perms
#SELECT @permissionId := permission_id FROM permission WHERE name = 'contentmgr';
#INSERT INTO role_permission VALUES ({SGL_NEXT_ID}, 2, @permissionId);
#SELECT @permissionId := permission_id FROM permission WHERE name = 'contentmgr_cmd_edit';
#INSERT INTO role_permission VALUES ({SGL_NEXT_ID}, 2, @permissionId);
#SELECT @permissionId := permission_id FROM permission WHERE name = 'contenttypemgr';
#INSERT INTO role_permission VALUES ({SGL_NEXT_ID}, 2, @permissionId);
#SELECT @permissionId := permission_id FROM permission WHERE name = 'contentassocmgr';
#INSERT INTO role_permission VALUES ({SGL_NEXT_ID}, 2, @permissionId);
#SELECT @permissionId := permission_id FROM permission WHERE name = 'querymgr_cmd_list';
#INSERT INTO role_permission VALUES ({SGL_NEXT_ID}, 2, @permissionId);
#SELECT @permissionId := permission_id FROM permission WHERE name = 'querymgr_cmd_search';
#INSERT INTO role_permission VALUES ({SGL_NEXT_ID}, 2, @permissionId);


INSERT INTO `page` VALUES (1, 'root', 'uriEmpty:', '1', 0, 0, 0, 0, 0, 0, 0, 0, '', '');
INSERT INTO `page` VALUES (2, 'User menu', 'uriEmpty:', '-2', 0, 2, 1, 2, 1, 1, 1, 0, '', '');
INSERT INTO `page` VALUES (4, 'Admin menu', 'uriEmpty:', '1', 0, 4, 1, 2, 2, 1, 1, 0, '', '');

-- at least 1 root category
INSERT INTO `category` VALUES (1, 'root', '', NULL, 0, 1, 1, 2, 1, 1);