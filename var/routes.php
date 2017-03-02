<?php
$aRoutes = array (
  0 => 
  array (
    0 => '/about',
    1 => 
    array (
      'moduleName' => 'simplecms',
      'controller' => 'cmscontentview',
      'action' => 'list',
      'frmContentId' => '4',
    ),
  ),
  1 => 
  array (
    0 => 'this/is/my/route',
    1 => 
    array (
      'moduleName' => 'simplecms',
      'controller' => 'cmscontentview',
      'action' => 'list',
    ),
  ),
  2 => 
  array (
    0 => '/this-is-my-page-baz',
    1 => 
    array (
      'moduleName' => 'simplecms',
      'controller' => 'cmscontentview',
      'action' => 'list',
      'frmContentId' => '3',
    ),
  ),
  3 => 
  array (
    0 => 'admin/dashboard',
    1 => 
    array (
      'moduleName' => 'admin',
    ),
  ),
  4 => 
  array (
    0 => 'media/manage/:mimeTypeId/:mediaTypeId/:page',
    1 => 
    array (
      'moduleName' => 'media2',
      'mimeTypeId' => 'all',
      'mediaTypeId' => 'all',
      'page' => 1,
    ),
  ),
  5 => 
  array (
    0 => 'media/upload',
    1 => 
    array (
      'moduleName' => 'media2',
      'action' => 'upload',
    ),
  ),
  6 => 
  array (
    0 => 'media/edit/:mediaId',
    1 => 
    array (
      'moduleName' => 'media2',
      'action' => 'edit',
    ),
  ),
  7 => 
  array (
    0 => 'media/download/:mediaId',
    1 => 
    array (
      'moduleName' => 'media2',
      'action' => 'download',
    ),
  ),
  8 => 
  array (
    0 => 'content/manage/:type/:status/:cLang/:resPerPage/:page',
    1 => 
    array (
      'moduleName' => 'simplecms',
      'controller' => 'cmscontent',
      'type' => 'default',
      'status' => 'default',
      'cLang' => 'default',
      'resPerPage' => 'default',
      'page' => 1,
    ),
  ),
  9 => 
  array (
    0 => 'content/edit/:contentId/:cLang/:versionId',
    1 => 
    array (
      'moduleName' => 'simplecms',
      'controller' => 'cmscontent',
      'action' => 'edit',
      'cLang' => 'default',
      'versionId' => 0,
    ),
  ),
  10 => 
  array (
    0 => 'content/add/:type/:cLang/:contentId',
    1 => 
    array (
      'moduleName' => 'simplecms',
      'controller' => 'cmscontent',
      'action' => 'add',
      'type' => 'default',
      'cLang' => 'default',
      'contentId' => 0,
    ),
  ),
  11 => 
  array (
    0 => 'content/activity/:userId/:page',
    1 => 
    array (
      'moduleName' => 'simplecms',
      'controller' => 'cmsactivity',
      'userId' => 'all',
      'page' => 1,
    ),
  ),
  12 => 
  array (
    0 => 'cms/choices/manage',
    1 => 
    array (
      'moduleName' => 'simplecms',
      'controller' => 'cmsattriblist',
    ),
  ),
  13 => 
  array (
    0 => 'cms/content-types/manage',
    1 => 
    array (
      'moduleName' => 'simplecms',
      'controller' => 'cmscontenttype',
    ),
  ),
  14 => 
  array (
    0 => 'cms/export',
    1 => 
    array (
      'moduleName' => 'simplecms',
      'controller' => 'cmsexporter',
    ),
  ),
  15 => 
  array (
    0 => 'login',
    1 => 
    array (
      'moduleName' => 'user2',
      'controller' => 'login2',
      'action' => 'login',
    ),
  ),
  16 => 
  array (
    0 => 'register',
    1 => 
    array (
      'moduleName' => 'user2',
      'controller' => 'login2',
      'action' => 'register',
    ),
  ),
  17 => 
  array (
    0 => 'logout',
    1 => 
    array (
      'moduleName' => 'user2',
      'controller' => 'login2',
      'action' => 'logout',
    ),
  ),
  18 => 
  array (
    0 => 'password/reset',
    1 => 
    array (
      'moduleName' => 'user2',
      'controller' => 'passwordrecovery',
    ),
  ),
  19 => 
  array (
    0 => 'password/reset/:userId/:k',
    1 => 
    array (
      'moduleName' => 'user2',
      'controller' => 'passwordrecovery',
      'action' => 'reset',
    ),
  ),
  20 => 
  array (
    0 => 'category/manage',
    1 => 
    array (
      'moduleName' => 'simplecategory',
    ),
  ),
  21 => 
  array (
    0 => 'page/manage/:siteId/:langId/:parentId/:status/:resPerPage/:page',
    1 => 
    array (
      'moduleName' => 'page',
      'siteId' => 'default',
      'langId' => 'default',
      'parentId' => 'default',
      'status' => 'default',
      'resPerPage' => 'default',
      'page' => 1,
    ),
  ),
  22 => 
  array (
    0 => 'page/edit/:pageId/:langId',
    1 => 
    array (
      'moduleName' => 'page',
      'action' => 'edit',
      'langId' => 'default',
    ),
  ),
  23 => 
  array (
    0 => 'page/add/:langId/:siteId',
    1 => 
    array (
      'moduleName' => 'page',
      'action' => 'add',
      'langId' => 'default',
      'siteId' => NULL,
    ),
  ),
  24 => 
  array (
    0 => 'url/manage/:siteId',
    1 => 
    array (
      'moduleName' => 'page',
      'controller' => 'route',
      'siteId' => NULL,
    ),
  ),
  25 => 
  array (
    0 => 'url/edit/:routeId/:siteId',
    1 => 
    array (
      'moduleName' => 'page',
      'controller' => 'route',
      'action' => 'edit',
      'siteId' => NULL,
    ),
  ),
  26 => 
  array (
    0 => 'url/add/:siteId',
    1 => 
    array (
      'moduleName' => 'page',
      'controller' => 'route',
      'action' => 'add',
      'siteId' => NULL,
    ),
  ),
)
?>