<?php
/**
 * Table Definition for media_type-mime
 */
require_once 'DB/DataObject.php';

class DataObjects_Media_type_mime extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'media_type-mime';                 // table name
    public $media_type_id;                   // int(11)  not_null primary_key
    public $media_mime_id;                   // int(11)  not_null primary_key multiple_key

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Media_type_mime',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
