<?php
/**
 * Table Definition for user_passwd_hash
 */
require_once 'DB/DataObject.php';

class DataObjects_User_passwd_hash extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'user_passwd_hash';                // table name
    public $user_passwd_hash_id;             // int(11)  not_null primary_key
    public $usr_id;                          // int(11)  not_null multiple_key
    public $hash;                            // string(96)  not_null
    public $date_created;                    // datetime(19)  not_null binary

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_User_passwd_hash',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
