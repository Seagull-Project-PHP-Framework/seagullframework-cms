<?php
/**
 * Table Definition for preference
 */
require_once 'DB/DataObject.php';

class DataObjects_Preference extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'preference';                      // table name
    public $preference_id;                   // int(11)  not_null primary_key
    public $name;                            // string(384)  
    public $default_value;                   // string(384)  

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Preference',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
