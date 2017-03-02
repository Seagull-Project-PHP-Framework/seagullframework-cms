<?php
$aSections = array(

    array(
        'parent_id' => SGL_NODE_ADMIN2,
        'title'     => 'organise (section)',
        'module'    => 'page',
        'manager'   => 'page',
        'perms'     => 'SGL_ADMIN,SGL_ROLE_MODERATOR',
        'sub'       => array(
            array(
                'title'     => 'pages (section)',
                'module'    => 'page',
                'manager'   => 'page',
                'perms'     => 'SGL_ADMIN,SGL_ROLE_MODERATOR'
            ),
            array(
                'parent_id' => SGL_NODE_ADMIN2,
                'title'     => 'urls (section)',
                'module'    => 'page',
                'manager'   => 'route',
                'perms'     => 'SGL_ADMIN,SGL_ROLE_MODERATOR',
            ),
            array(
                'parent_id' => SGL_NODE_ADMIN2,
                'title'     => 'categories (section)',
                'module'    => 'simplecategory',
                'manager'   => 'simplecategory',
                'perms'     => 'SGL_ADMIN,SGL_ROLE_MODERATOR',
            ),
        )
    )
);
?>