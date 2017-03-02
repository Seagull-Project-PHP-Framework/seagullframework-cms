<?php
/**
 * Table Definition for sequence
 */
require_once 'DB/DataObject.php';

class DataObjects_Sequence extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'sequence';                        // table name
    public $name;                            // string(192)  not_null primary_key
    public $id;                              // int(20)  

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Sequence',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
