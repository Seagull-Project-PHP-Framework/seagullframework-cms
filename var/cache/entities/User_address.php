<?php
/**
 * Table Definition for user-address
 */
require_once 'DB/DataObject.php';

class DataObjects_User_address extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'user-address';                    // table name
    public $usr_id;                          // int(11)  not_null primary_key
    public $address_id;                      // int(11)  not_null primary_key multiple_key
    public $address_type;                    // string(96)  not_null primary_key

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_User_address',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
