<?php
$aRoutes = array(

//    array('content/manage/:type/:status/:cLang/:sortBy/:sortOrder/:resPerPage/:page', array(
    array('content/manage/:type/:status/:cLang/:resPerPage/:page', array(
        'moduleName' => 'simplecms',
        'controller' => 'cmscontent',

        // defaults
        'type'       => 'default',
        'status'     => 'default',
        'cLang'      => 'default',
//        'sortBy'     => 'default',
//        'sortOrder'  => 'default',
        'resPerPage' => 'default',
        'page'       => 1
    )),
    array('content/edit/:contentId/:cLang/:versionId', array(
        'moduleName' => 'simplecms',
        'controller' => 'cmscontent',
        'action'     => 'edit',

        // defaults
        'cLang'     => 'default',
        'versionId' => 0
    )),
    array('content/add/:type/:cLang/:contentId', array(
        'moduleName' => 'simplecms',
        'controller' => 'cmscontent',
        'action'     => 'add',

        // defaults
        'type'      => 'default',
        'cLang'     => 'default',
        'contentId' => 0
    )),
    array('content/activity/:userId/:page', array(
        'moduleName' => 'simplecms',
        'controller' => 'cmsactivity',

        // defaults
        'userId' => 'all',
        'page'   => 1,
    )),
    array('cms/choices/manage', array(
        'moduleName' => 'simplecms',
        'controller' => 'cmsattriblist',
    )),
    array('cms/content-types/manage', array(
        'moduleName' => 'simplecms',
        'controller' => 'cmscontenttype',
    )),
    array('cms/export', array(
        'moduleName' => 'simplecms',
        'controller' => 'cmsexporter',
    ))
);
?>