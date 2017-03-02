<?php

require_once SGL_MOD_DIR . '/user2/classes/User2DAO.php';
require_once SGL_MOD_DIR . '/admin/classes/Output.php';

/**
 * Media output.
 *
 * @package media2
 * @author Thomas Goetz
 * @author Demian Turner <demian@phpkitchen.com>
 * @author Dmitri Lakachauskis <lakiboy83@gmail.com>
 */
class Media2Output extends AdminOutput
{
    public static function getUserFullName($userId)
    {
        $oUser = User2DAO::singleton()->getUserById($userId);
        $ret   = $oUser->first_name . ' ' . $oUser->last_name;
        if (empty($ret)) {
            $ret = $oUser->username;
        }
        return $ret;
    }
}
?>