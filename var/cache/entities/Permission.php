<?php
/**
 * Table Definition for permission
 */
require_once 'DB/DataObject.php';

class DataObjects_Permission extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'permission';                      // table name
    public $permission_id;                   // int(11)  not_null primary_key
    public $name;                            // string(765)  unique_key
    public $description;                     // blob(196605)  blob
    public $module_id;                       // int(11)  not_null

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Permission',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
