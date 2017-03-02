<?php
/**
 * Table Definition for attribute_type
 */
require_once 'DB/DataObject.php';

class DataObjects_Attribute_type extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'attribute_type';                  // table name
    public $attribute_type_id;               // int(11)  not_null primary_key
    public $name;                            // string(192)  not_null
    public $alias;                           // string(765)  not_null
    public $params;                          // blob(196605)  blob

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Attribute_type',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
