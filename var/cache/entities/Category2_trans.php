<?php
/**
 * Table Definition for category2_trans
 */
require_once 'DB/DataObject.php';

class DataObjects_Category2_trans extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'category2_trans';                 // table name
    public $category2_id;                    // int(11)  not_null primary_key
    public $language_id;                     // string(15)  not_null primary_key
    public $name;                            // string(384)  
    public $description;                     // blob(196605)  blob

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Category2_trans',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
