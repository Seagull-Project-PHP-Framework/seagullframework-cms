<?php
/**
 * Table Definition for site
 */
require_once 'DB/DataObject.php';

class DataObjects_Site extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'site';                            // table name
    public $site_id;                         // int(11)  not_null primary_key
    public $name;                            // string(765)  not_null

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Site',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
