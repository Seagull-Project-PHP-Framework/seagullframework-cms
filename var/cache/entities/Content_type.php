<?php
/**
 * Table Definition for content_type
 */
require_once 'DB/DataObject.php';

class DataObjects_Content_type extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'content_type';                    // table name
    public $content_type_id;                 // int(11)  not_null primary_key
    public $name;                            // string(192)  unique_key

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Content_type',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
