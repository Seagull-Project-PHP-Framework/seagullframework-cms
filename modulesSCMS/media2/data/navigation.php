<?php
$aSections = array(
    array(
        'parent_id' => SGL_NODE_ADMIN2,
        'title'     => 'media (section)',
        'module'    => 'media2',
        'manager'   => 'media2',
        'perms'     => 'SGL_ADMIN,SGL_ROLE_MODERATOR',
        'sub'       => array(
            array(
                'title'     => 'media list (section)',
                'module'    => 'media2',
                'manager'   => 'media2',
                'perms'     => 'SGL_ADMIN,SGL_ROLE_MODERATOR',
            ),
        )
    ),
);
?>