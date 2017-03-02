<?php
/**
 * Table Definition for media
 */
require_once 'DB/DataObject.php';

class DataObjects_Media extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'media';                           // table name
    public $media_id;                        // int(11)  not_null primary_key
    public $media_type_id;                   // int(11)  multiple_key
    public $media_mime_id;                   // int(11)  not_null multiple_key
    public $fk_id;                           // int(11)  multiple_key
    public $name;                            // string(384)  not_null
    public $description;                     // blob(196605)  blob
    public $item_order;                      // int(6)  
    public $file_name;                       // string(765)  not_null
    public $file_size;                       // int(11)  not_null
    public $mime_type;                       // string(96)  not_null
    public $date_created;                    // datetime(19)  not_null binary
    public $last_updated;                    // datetime(19)  not_null binary
    public $created_by;                      // int(11)  not_null
    public $updated_by;                      // int(11)  not_null

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Media',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
