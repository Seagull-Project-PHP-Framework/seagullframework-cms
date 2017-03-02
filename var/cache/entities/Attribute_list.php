<?php
/**
 * Table Definition for attribute_list
 */
require_once 'DB/DataObject.php';

class DataObjects_Attribute_list extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'attribute_list';                  // table name
    public $attribute_list_id;               // int(11)  not_null primary_key
    public $name;                            // string(384)  not_null
    public $params;                          // blob(196605)  not_null blob

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Attribute_list',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
