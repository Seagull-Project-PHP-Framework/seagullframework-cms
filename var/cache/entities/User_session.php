<?php
/**
 * Table Definition for user_session
 */
require_once 'DB/DataObject.php';

class DataObjects_User_session extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'user_session';                    // table name
    public $session_id;                      // string(765)  not_null primary_key
    public $last_updated;                    // datetime(19)  multiple_key binary
    public $data_value;                      // blob(196605)  blob
    public $usr_id;                          // int(11)  not_null multiple_key
    public $username;                        // string(192)  multiple_key
    public $expiry;                          // int(11)  not_null

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_User_session',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
