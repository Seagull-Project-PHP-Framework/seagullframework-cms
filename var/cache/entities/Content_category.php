<?php
/**
 * Table Definition for content-category
 */
require_once 'DB/DataObject.php';

class DataObjects_Content_category extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'content-category';                // table name
    public $content_id;                      // int(11)  not_null primary_key
    public $category_id;                     // int(11)  not_null primary_key

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Content_category',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
