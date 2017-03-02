<?php
/**
 * Table Definition for category-media
 */
require_once 'DB/DataObject.php';

class DataObjects_Category_media extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'category-media';                  // table name
    public $category_id;                     // int(11)  not_null primary_key
    public $media_id;                        // int(11)  not_null primary_key

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Category_media',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
