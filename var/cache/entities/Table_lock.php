<?php
/**
 * Table Definition for table_lock
 */
require_once 'DB/DataObject.php';

class DataObjects_Table_lock extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'table_lock';                      // table name
    public $lockID;                          // string(96)  not_null primary_key
    public $lockTable;                       // string(96)  not_null primary_key
    public $lockStamp;                       // int(11)  

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Table_lock',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
