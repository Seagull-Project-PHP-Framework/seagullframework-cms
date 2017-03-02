<?php
/**
 * Table Definition for media_type
 */
require_once 'DB/DataObject.php';

class DataObjects_Media_type extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'media_type';                      // table name
    public $media_type_id;                   // int(11)  not_null primary_key
    public $name;                            // string(384)  not_null
    public $description;                     // blob(196605)  blob

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Media_type',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
