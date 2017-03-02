<?php
/**
 * Table Definition for attribute
 */
require_once 'DB/DataObject.php';

class DataObjects_Attribute extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'attribute';                       // table name
    public $attribute_id;                    // int(11)  not_null primary_key
    public $attribute_type_id;               // int(6)  
    public $content_type_id;                 // int(11)  not_null multiple_key
    public $name;                            // string(192)  
    public $alias;                           // string(384)  not_null
    public $desc;                            // blob(196605)  blob
    public $params;                          // blob(196605)  blob

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Attribute',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
