<?php
$aRoutes = array(
    array('page/manage/:siteId/:langId/:parentId/:status/:resPerPage/:page', array(
        'moduleName' => 'page',
//        'controller' => 'page',

        // defaults
        'siteId'     => 'default',
        'langId'     => 'default',
        'parentId'   => 'default',
        'status'     => 'default',
        'resPerPage' => 'default',
        'page'       => 1
    )),
    array('page/edit/:pageId/:langId', array(
        'moduleName' => 'page',
//        'controller' => 'page',
        'action'     => 'edit',

        // defaults
        'langId'     => 'default'
    )),
    array('page/add/:langId/:siteId', array(
        'moduleName' => 'page',
//        'controller' => 'page',
        'action'     => 'add',

        // defaults
        'langId'     => 'default',
        'siteId'     => 0
    ))
);
?>