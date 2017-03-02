<?php
/**
 * Table Definition for log_table
 */
require_once 'DB/DataObject.php';

class DataObjects_Log_table extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'log_table';                       // table name
    public $id;                              // int(11)  not_null primary_key
    public $logtime;                         // timestamp(19)  not_null unsigned zerofill binary timestamp
    public $ident;                           // string(48)  not_null
    public $priority;                        // int(11)  not_null
    public $message;                         // string(600)  

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Log_table',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
