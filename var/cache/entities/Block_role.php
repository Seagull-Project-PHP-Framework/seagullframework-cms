<?php
/**
 * Table Definition for block_role
 */
require_once 'DB/DataObject.php';

class DataObjects_Block_role extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'block_role';                      // table name
    public $block_id;                        // int(11)  not_null
    public $role_id;                         // int(11)  not_null

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Block_role',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
