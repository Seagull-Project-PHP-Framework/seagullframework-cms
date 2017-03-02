<?php
/**
 * Table Definition for category2
 */
require_once 'DB/DataObject.php';

class DataObjects_Category2 extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'category2';                       // table name
    public $category2_id;                    // int(11)  not_null primary_key
    public $parent_id;                       // int(11)  multiple_key
    public $order_id;                        // int(11)  not_null
    public $level_id;                        // int(11)  not_null
    public $is_active;                       // int(1)  not_null multiple_key

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Category2',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
