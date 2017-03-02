<?php
/*
Data setup in navigation files such as this one will allow you to add sections
to the 'Admin menu' that only the admin user can see.  There are 2 types of
nodes you can add:

 (1)    a SGL_NODE_ADMIN node
        These will appear at the top level in the admin menu
 (2)    a SGL_NODE_GROUP node
        These will appear as a subsection of the node inserted previously

Using this logic you can create node groups that correspond to modules, with a
SGL_NODE_ADMIN as the designated parent, and all children designated as
SGL_NODE_GROUP nodes.

*/
$aSections = array(
    array (
      'title'           => 'CMS',
      'parent_id'       => SGL_NODE_ADMIN,
      'uriType'         => 'dynamic',
      'module'          => 'cms',
      'manager'         => 'ContentMgr.php',
      'actionMapping'   => '',
      'add_params'      => '',
      'is_enabled'      => 1,
      'perms'           => SGL_ADMIN,
        ),
    array (
      'title'           => 'Manage Content',
      'parent_id'       => SGL_NODE_GROUP,
      'uriType'         => 'dynamic',
      'module'          => 'cms',
      'manager'         => 'ContentMgr.php',
      'actionMapping'   => '',
      'add_params'      => '',
      'is_enabled'      => 1,
      'perms'           => SGL_ADMIN,
        ),
    array (
      'title'           => 'Content Types',
      'parent_id'       => SGL_NODE_GROUP,
      'uriType'         => 'dynamic',
      'module'          => 'cms',
      'manager'         => 'ContentTypeMgr.php',
      'actionMapping'   => '',
      'add_params'      => '',
      'is_enabled'      => 1,
      'perms'           => SGL_ADMIN,
        ),
    array (
      'title'           => 'Attribute Lists',
      'parent_id'       => SGL_NODE_GROUP,
      'uriType'         => 'dynamic',
      'module'          => 'cms',
      'manager'         => 'AttribListMgr.php',
      'actionMapping'   => '',
      'add_params'      => '',
      'is_enabled'      => 1,
      'perms'           => SGL_ADMIN,
        ),
    array (
      'title'           => 'Export',
      'parent_id'       => SGL_NODE_GROUP,
      'uriType'         => 'dynamic',
      'module'          => 'cms',
      'manager'         => 'DumperMgr.php',
      'actionMapping'   => '',
      'add_params'      => '',
      'is_enabled'      => 1,
      'perms'           => SGL_ADMIN,
        ),
    array (
      'title'           => 'Import (Publisher)',
      'parent_id'       => SGL_NODE_GROUP,
      'uriType'         => 'dynamic',
      'module'          => 'cms',
      'manager'         => 'ImportMgr.php',
      'actionMapping'   => '',
      'add_params'      => '',
      'is_enabled'      => 1,
      'perms'           => SGL_ADMIN,
        ),
    array (
      'title'           => 'Search',
      'parent_id'       => SGL_NODE_GROUP,
      'uriType'         => 'dynamic',
      'module'          => 'cms',
      'manager'         => 'QueryMgr.php',
      'actionMapping'   => '',
      'add_params'      => '',
      'is_enabled'      => 1,
      'perms'           => SGL_ADMIN,
        ),
    array (
      'title'           => 'Manage Navigation',
      'parent_id'       => SGL_NODE_GROUP,
      'uriType'         => 'dynamic',
      'module'          => 'cms',
      'manager'         => 'PageMgr.php',
      'actionMapping'   => '',
      'add_params'      => '',
      'is_enabled'      => 1,
      'perms'           => SGL_ADMIN,
        ),
    array (
      'title'           => 'Manage Categories',
      'parent_id'       => SGL_NODE_GROUP,
      'uriType'         => 'dynamic',
      'module'          => 'cms',
      'manager'         => 'CategoryMgr.php',
      'actionMapping'   => '',
      'add_params'      => '',
      'is_enabled'      => 1,
      'perms'           => SGL_ADMIN,
        ),
    array (
      'title'           => 'Manage Links',
      'parent_id'       => SGL_NODE_GROUP,
      'uriType'         => 'dynamic',
      'module'          => 'cms',
      'manager'         => 'LinkerMgr.php',
      'actionMapping'   => '',
      'add_params'      => '',
      'is_enabled'      => 0,
      'perms'           => SGL_ADMIN,
        )
    );
?>
