<?php
/**
 * Table Definition for block
 */
require_once 'DB/DataObject.php';

class DataObjects_Block extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'block';                           // table name
    public $block_id;                        // int(11)  not_null primary_key
    public $name;                            // string(192)  
    public $title;                           // string(96)  
    public $title_class;                     // string(96)  
    public $body_class;                      // string(96)  
    public $blk_order;                       // int(6)  
    public $position;                        // string(48)  
    public $is_enabled;                      // int(6)  
    public $is_cached;                       // int(6)  
    public $params;                          // blob(-3)  blob

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Block',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
