<?php
/**
 * Table Definition for category
 */
require_once 'DB/DataObject.php';

class DataObjects_Category extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'category';                        // table name
    public $category_id;                     // int(11)  not_null primary_key multiple_key
    public $label;                           // string(96)  
    public $description;                     // blob(196605)  not_null blob
    public $perms;                           // string(96)  
    public $parent_id;                       // int(11)  multiple_key
    public $root_id;                         // int(11)  multiple_key
    public $left_id;                         // int(11)  multiple_key
    public $right_id;                        // int(11)  multiple_key
    public $order_id;                        // int(11)  multiple_key
    public $level_id;                        // int(11)  multiple_key

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Category',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
