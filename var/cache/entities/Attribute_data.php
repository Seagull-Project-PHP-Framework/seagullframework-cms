<?php
/**
 * Table Definition for attribute_data
 */
require_once 'DB/DataObject.php';

class DataObjects_Attribute_data extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'attribute_data';                  // table name
    public $content_id;                      // int(11)  not_null primary_key
    public $version;                         // int(6)  not_null primary_key multiple_key
    public $language_id;                     // string(15)  not_null primary_key multiple_key
    public $attribute_id;                    // int(11)  not_null primary_key multiple_key
    public $value;                           // blob(196605)  blob
    public $params;                          // blob(196605)  blob

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Attribute_data',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
