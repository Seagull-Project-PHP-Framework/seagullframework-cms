<?php
/**
 * Table Definition for widget
 */
require_once 'DB/DataObject.php';

class DataObjects_Widget extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'widget';                          // table name
    public $usr_id;                          // int(11)  not_null primary_key
    public $name;                            // string(192)  not_null primary_key
    public $page;                            // string(192)  not_null primary_key
    public $col;                             // int(3)  not_null
    public $position;                        // int(3)  not_null
    public $last_updated;                    // datetime(19)  binary

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Widget',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
