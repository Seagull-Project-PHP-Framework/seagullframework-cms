<?php
/**
 * Table Definition for media_mime
 */
require_once 'DB/DataObject.php';

class DataObjects_Media_mime extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'media_mime';                      // table name
    public $media_mime_id;                   // int(11)  not_null primary_key
    public $name;                            // string(384)  not_null
    public $extension;                       // string(384)  not_null
    public $content_type;                    // string(384)  not_null
    public $ident;                           // string(384)  not_null

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Media_mime',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
