<?php
/**
 * Table Definition for page_trans
 */
require_once 'DB/DataObject.php';

class DataObjects_Page_trans extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'page_trans';                      // table name
    public $page_id;                         // int(11)  not_null primary_key
    public $language_id;                     // string(15)  not_null primary_key
    public $title;                           // string(765)  
    public $meta_desc;                       // blob(196605)  blob
    public $meta_key;                        // blob(196605)  blob

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Page_trans',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
