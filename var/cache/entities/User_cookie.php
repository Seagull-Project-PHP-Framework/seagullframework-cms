<?php
/**
 * Table Definition for user_cookie
 */
require_once 'DB/DataObject.php';

class DataObjects_User_cookie extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'user_cookie';                     // table name
    public $usr_id;                          // int(11)  not_null multiple_key
    public $cookie_name;                     // string(96)  not_null multiple_key
    public $login_time;                      // datetime(19)  not_null binary

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_User_cookie',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
