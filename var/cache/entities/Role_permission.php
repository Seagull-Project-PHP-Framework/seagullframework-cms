<?php
/**
 * Table Definition for role_permission
 */
require_once 'DB/DataObject.php';

class DataObjects_Role_permission extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'role_permission';                 // table name
    public $role_permission_id;              // int(11)  not_null primary_key
    public $role_id;                         // int(11)  not_null multiple_key
    public $permission_id;                   // int(11)  not_null multiple_key

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Role_permission',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
