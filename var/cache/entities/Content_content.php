<?php
/**
 * Table Definition for content-content
 */
require_once 'DB/DataObject.php';

class DataObjects_Content_content extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'content-content';                 // table name
    public $content_id_pk;                   // int(11)  not_null primary_key
    public $content_id_fk;                   // int(11)  not_null primary_key

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Content_content',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
