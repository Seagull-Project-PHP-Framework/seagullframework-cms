<?php
$aRoutes = array(

    array('media/manage/:mimeTypeId/:mediaTypeId/:page', array(
        'moduleName' => 'media2',
//        'controller' => 'media2',

        // defaults
        'mimeTypeId'  => 'all',
        'mediaTypeId' => 'all',
        'page'        => 1
    )),
    array('media/upload', array(
        'moduleName' => 'media2',
        'action'     => 'upload'
    )),
    array('media/edit/:mediaId', array(
        'moduleName' => 'media2',
        'action'     => 'edit'
    )),
    array('media/download/:mediaId', array(
        'moduleName' => 'media2',
        'action'     => 'download'
    )),
);
?>