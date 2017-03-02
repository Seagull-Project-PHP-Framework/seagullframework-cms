<?php
/**
 * Table Definition for content
 */
require_once 'DB/DataObject.php';

class DataObjects_Content extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'content';                         // table name
    public $content_id;                      // int(11)  not_null primary_key
    public $version;                         // int(6)  not_null primary_key multiple_key
    public $is_current;                      // int(1)  not_null
    public $language_id;                     // string(15)  not_null primary_key multiple_key
    public $content_type_id;                 // int(11)  not_null multiple_key
    public $status;                          // int(6)  not_null
    public $name;                            // string(765)  not_null
    public $created_by_id;                   // int(11)  not_null
    public $updated_by_id;                   // int(11)  not_null
    public $date_created;                    // datetime(19)  not_null binary
    public $last_updated;                    // datetime(19)  not_null binary

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Content',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
