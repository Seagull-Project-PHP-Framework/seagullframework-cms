<?php
/**
 * Table Definition for block_assignment
 */
require_once 'DB/DataObject.php';

class DataObjects_Block_assignment extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'block_assignment';                // table name
    public $block_id;                        // int(11)  not_null primary_key multiple_key
    public $section_id;                      // int(11)  not_null primary_key multiple_key

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Block_assignment',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
