<?php
/**
 * Table Definition for organisation_type
 */
require_once 'DB/DataObject.php';

class DataObjects_Organisation_type extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'organisation_type';               // table name
    public $organisation_type_id;            // int(11)  not_null primary_key
    public $name;                            // string(192)  

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Organisation_type',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
