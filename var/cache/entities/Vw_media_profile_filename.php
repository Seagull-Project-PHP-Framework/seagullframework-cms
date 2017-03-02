<?php
/**
 * Table Definition for vw_media_profile_filename
 */
require_once 'DB/DataObject.php';

class DataObjects_Vw_media_profile_filename extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'vw_media_profile_filename';       // table name
    public $media_file_name;                 // string(765)  not_null
    public $usr_id;                          // int(11)  multiple_key

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Vw_media_profile_filename',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
