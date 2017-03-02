<?php
$aSections = array (
  10 => 
  array (
    101 => 
    array (
      'title' => 'dashboard (section)',
      'module' => 'admin',
      'manager' => 'admin',
      'perms' => 'SGL_ADMIN,SGL_ROLE_MODERATOR',
    ),
    123 => 
    array (
      'title' => 'media (section)',
      'module' => 'media2',
      'manager' => 'media2',
      'perms' => 'SGL_ADMIN,SGL_ROLE_MODERATOR',
      'sub' => 
      array (
        124 => 
        array (
          'title' => 'media list (section)',
          'module' => 'media2',
          'manager' => 'media2',
          'perms' => 'SGL_ADMIN,SGL_ROLE_MODERATOR',
        ),
      ),
    ),
    127 => 
    array (
      'title' => 'organise (section)',
      'module' => 'page',
      'manager' => 'page',
      'perms' => 'SGL_ADMIN,SGL_ROLE_MODERATOR',
      'sub' => 
      array (
        128 => 
        array (
          'title' => 'pages (section)',
          'module' => 'page',
          'manager' => 'page',
          'perms' => 'SGL_ADMIN,SGL_ROLE_MODERATOR',
        ),
        129 => 
        array (
          'parent_id' => 10,
          'title' => 'urls (section)',
          'module' => 'page',
          'manager' => 'route',
          'perms' => 'SGL_ADMIN,SGL_ROLE_MODERATOR',
        ),
        130 => 
        array (
          'parent_id' => 10,
          'title' => 'categories (section)',
          'module' => 'simplecategory',
          'manager' => 'simplecategory',
          'perms' => 'SGL_ADMIN,SGL_ROLE_MODERATOR',
        ),
      ),
    ),
    131 => 
    array (
      'title' => 'content (section)',
      'module' => 'simplecms',
      'manager' => 'cmscontent',
      'perms' => 'SGL_ADMIN,SGL_ROLE_MODERATOR',
      'sub' => 
      array (
        132 => 
        array (
          'title' => 'content list (section)',
          'module' => 'simplecms',
          'manager' => 'cmscontent',
          'perms' => 'SGL_ADMIN,SGL_ROLE_MODERATOR',
        ),
        133 => 
        array (
          'title' => 'content activity (section)',
          'module' => 'simplecms',
          'manager' => 'cmsactivity',
          'perms' => 'SGL_ADMIN,SGL_ROLE_MODERATOR',
          'is_enabled' => false,
        ),
        134 => 
        array (
          'title' => 'content type (section)',
          'module' => 'simplecms',
          'manager' => 'cmscontenttype',
          'perms' => 'SGL_ADMIN,SGL_ROLE_MODERATOR',
        ),
        135 => 
        array (
          'title' => 'attribute lists (section)',
          'module' => 'simplecms',
          'manager' => 'cmsattriblist',
          'perms' => 'SGL_ADMIN,SGL_ROLE_MODERATOR',
        ),
        136 => 
        array (
          'title' => 'sql exporter (section)',
          'module' => 'simplecms',
          'manager' => 'cmsexporter',
          'perms' => 'SGL_ADMIN,SGL_ROLE_MODERATOR',
        ),
      ),
    ),
  ),
  4 => 
  array (
    102 => 
    array (
      'title' => 'Blocks',
      'module' => 'block',
      'manager' => 'block',
      'perms' => 1,
      'sub' => 
      array (
        103 => 
        array (
          'title' => 'Manage Blocks',
          'module' => 'block',
          'manager' => 'block',
          'perms' => 1,
        ),
      ),
    ),
    104 => 
    array (
      'title' => 'CMS',
      'module' => 'cms',
      'manager' => 'content',
      'perms' => 1,
      'sub' => 
      array (
        105 => 
        array (
          'title' => 'Manage Content',
          'module' => 'cms',
          'manager' => 'content',
          'perms' => 1,
        ),
        106 => 
        array (
          'title' => 'Content Types',
          'module' => 'cms',
          'manager' => 'contenttype',
          'perms' => 1,
        ),
        107 => 
        array (
          'title' => 'Attribute Lists',
          'module' => 'cms',
          'manager' => 'attriblist',
          'perms' => 1,
        ),
        108 => 
        array (
          'title' => 'Export',
          'module' => 'cms',
          'manager' => 'dumper',
          'perms' => 1,
        ),
        109 => 
        array (
          'title' => 'Import (Publisher)',
          'module' => 'cms',
          'manager' => 'import',
          'perms' => 1,
        ),
        110 => 
        array (
          'title' => 'Search',
          'module' => 'cms',
          'manager' => 'query',
          'perms' => 1,
        ),
        111 => 
        array (
          'title' => 'Manage Navigation',
          'module' => 'cms',
          'manager' => 'page',
          'perms' => 1,
        ),
        112 => 
        array (
          'title' => 'Manage Categories',
          'module' => 'cms',
          'manager' => 'category',
          'perms' => 1,
        ),
        113 => 
        array (
          'title' => 'Manage Links',
          'module' => 'cms',
          'manager' => 'linker',
          'is_enabled' => 0,
          'perms' => 1,
        ),
      ),
    ),
    114 => 
    array (
      'title' => 'General',
      'module' => 'default',
      'manager' => 'module',
      'perms' => 1,
      'sub' => 
      array (
        115 => 
        array (
          'title' => 'Manage Modules',
          'module' => 'default',
          'manager' => 'module',
          'perms' => 1,
        ),
        116 => 
        array (
          'title' => 'Configuration',
          'module' => 'default',
          'manager' => 'config',
          'perms' => 1,
        ),
        117 => 
        array (
          'title' => 'Maintenance',
          'module' => 'default',
          'manager' => 'maintenance',
          'perms' => 1,
        ),
        118 => 
        array (
          'title' => 'Module Generator',
          'module' => 'default',
          'manager' => 'modulegeneration',
          'perms' => 1,
        ),
      ),
    ),
    125 => 
    array (
      'title' => 'Navigation',
      'module' => 'navigation',
      'manager' => 'section',
      'perms' => 1,
      'sub' => 
      array (
        126 => 
        array (
          'title' => 'Manage Navigation',
          'module' => 'navigation',
          'manager' => 'section',
          'perms' => 1,
        ),
      ),
    ),
    137 => 
    array (
      'title' => 'Manage Translations',
      'module' => 'translation',
      'manager' => 'translation',
      'perms' => 'SGL_ADMIN',
      'sub' => 
      array (
        138 => 
        array (
          'title' => 'Summary',
          'module' => 'translation',
          'manager' => 'translation',
          'perms' => 'SGL_ADMIN',
          'action' => 'summary',
        ),
      ),
    ),
    139 => 
    array (
      'title' => 'Users and security',
      'module' => 'user',
      'manager' => 'user',
      'perms' => 1,
      'sub' => 
      array (
        140 => 
        array (
          'title' => 'Manage users',
          'module' => 'user',
          'manager' => 'user',
          'perms' => 1,
        ),
        141 => 
        array (
          'title' => 'Manage permissions',
          'module' => 'user',
          'manager' => 'permission',
          'perms' => 1,
        ),
        142 => 
        array (
          'title' => 'Manage roles',
          'module' => 'user',
          'manager' => 'role',
          'perms' => 1,
        ),
        143 => 
        array (
          'title' => 'Manage preferences',
          'module' => 'user',
          'manager' => 'preference',
          'perms' => 1,
        ),
      ),
    ),
    144 => 
    array (
      'title' => 'My Account',
      'module' => 'user',
      'manager' => 'account',
      'perms' => 1,
      'sub' => 
      array (
        145 => 
        array (
          'title' => 'Summary',
          'module' => 'user',
          'manager' => 'account',
          'perms' => 1,
        ),
        146 => 
        array (
          'title' => 'View Profile',
          'module' => 'user',
          'manager' => 'account',
          'perms' => 1,
          'action' => 'viewProfile',
        ),
        147 => 
        array (
          'title' => 'Edit Preferences',
          'module' => 'user',
          'manager' => 'userpreference',
          'perms' => 1,
        ),
      ),
    ),
  ),
  2 => 
  array (
    119 => 
    array (
      'title' => 'Home',
      'module' => 'default',
      'manager' => 'default',
      'perms' => 0,
    ),
    120 => 
    array (
      'title' => 'Admin Home',
      'module' => 'default',
      'manager' => 'default',
      'perms' => 1,
    ),
    121 => 
    array (
      'title' => 'Admin',
      'module' => 'default',
      'manager' => 'module',
      'perms' => 1,
    ),
    122 => 
    array (
      'title' => 'Contact Us',
      'module' => 'enquiry',
      'manager' => 'enquiry',
      'perms' => 'SGL_GUEST, SGL_MEMBER, SGL_ADMIN',
      'action' => 'form',
      'params' => 'type/ContactUs',
    ),
    148 => 
    array (
      'title' => 'My Account',
      'module' => 'user',
      'manager' => 'account',
      'perms' => 2,
    ),
  ),
);
?>