<?php
$aSections = array(
    array(
        'parent_id' => SGL_NODE_ADMIN2,
        'title'     => 'content (section)',
        'module'    => 'simplecms',
        'manager'   => 'cmscontent',
        'perms'     => 'SGL_ADMIN,SGL_ROLE_MODERATOR',
        'sub'       => array(
            array(
                'title'     => 'content list (section)',
                'module'    => 'simplecms',
                'manager'   => 'cmscontent',
                'perms'     => 'SGL_ADMIN,SGL_ROLE_MODERATOR',
            ),
            array(
                'title'     => 'content activity (section)',
                'module'    => 'simplecms',
                'manager'   => 'cmsactivity',
                'perms'     => 'SGL_ADMIN,SGL_ROLE_MODERATOR',
                'is_enabled'=> false,
            ),
            array(
                'title'     => 'content type (section)',
                'module'    => 'simplecms',
                'manager'   => 'cmscontenttype',
                'perms'     => 'SGL_ADMIN,SGL_ROLE_MODERATOR',
            ),
            array(
                'title'     => 'attribute lists (section)',
                'module'    => 'simplecms',
                'manager'   => 'cmsattriblist',
                'perms'     => 'SGL_ADMIN,SGL_ROLE_MODERATOR',
            ),
            array(
                'title'     => 'sql exporter (section)',
                'module'    => 'simplecms',
                'manager'   => 'cmsexporter',
                'perms'     => 'SGL_ADMIN,SGL_ROLE_MODERATOR',
            ),
        )
    ),
);
?>