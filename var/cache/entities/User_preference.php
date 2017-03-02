<?php
/**
 * Table Definition for user_preference
 */
require_once 'DB/DataObject.php';

class DataObjects_User_preference extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'user_preference';                 // table name
    public $user_preference_id;              // int(11)  not_null primary_key
    public $usr_id;                          // int(11)  not_null multiple_key
    public $preference_id;                   // int(11)  not_null multiple_key
    public $value;                           // string(384)  

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_User_preference',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
