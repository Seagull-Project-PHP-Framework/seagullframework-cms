<?php
/**
 * Table Definition for login
 */
require_once 'DB/DataObject.php';

class DataObjects_Login extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'login';                           // table name
    public $login_id;                        // int(11)  not_null primary_key
    public $usr_id;                          // int(11)  multiple_key
    public $date_time;                       // datetime(19)  binary
    public $remote_ip;                       // string(48)  

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Login',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
