<?php
/**
 * Table Definition for section
 */
require_once 'DB/DataObject.php';

class DataObjects_Section extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'section';                         // table name
    public $section_id;                      // int(11)  not_null primary_key multiple_key
    public $title;                           // string(96)  
    public $resource_uri;                    // string(384)  
    public $perms;                           // string(96)  
    public $trans_id;                        // int(11)  
    public $parent_id;                       // int(11)  
    public $root_id;                         // int(11)  multiple_key
    public $left_id;                         // int(11)  multiple_key
    public $right_id;                        // int(11)  multiple_key
    public $order_id;                        // int(11)  multiple_key
    public $level_id;                        // int(11)  multiple_key
    public $is_enabled;                      // int(6)  
    public $is_static;                       // int(6)  
    public $access_key;                      // string(3)  
    public $rel;                             // string(48)  

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Section',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
