<?php
/**
 * Table Definition for role
 */
require_once 'DB/DataObject.php';

class DataObjects_Role extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'role';                            // table name
    public $role_id;                         // int(11)  not_null primary_key
    public $name;                            // string(765)  
    public $description;                     // blob(196605)  blob
    public $date_created;                    // datetime(19)  binary
    public $created_by;                      // int(11)  
    public $last_updated;                    // datetime(19)  binary
    public $updated_by;                      // int(11)  

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Role',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
