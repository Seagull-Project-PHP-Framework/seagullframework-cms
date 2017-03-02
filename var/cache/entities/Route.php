<?php
/**
 * Table Definition for route
 */
require_once 'DB/DataObject.php';

class DataObjects_Route extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'route';                           // table name
    public $route_id;                        // int(11)  not_null primary_key
    public $site_id;                         // int(11)  not_null multiple_key
    public $page_id;                         // int(11)  multiple_key
    public $route;                           // blob(196605)  not_null blob
    public $description;                     // blob(196605)  blob
    public $route_data;                      // blob(196605)  blob
    public $is_active;                       // int(1)  not_null

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Route',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
