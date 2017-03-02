<?php
/**
 * Table Definition for page
 */
require_once 'DB/DataObject.php';

class DataObjects_Page extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'page';                            // table name
    public $page_id;                         // int(11)  not_null primary_key
    public $parent_id;                       // int(11)  multiple_key
    public $order_id;                        // int(11)  not_null
    public $level_id;                        // int(11)  not_null
    public $status;                          // int(1)  not_null multiple_key
    public $site_id;                         // int(11)  not_null multiple_key
    public $content_id;                      // int(11)  
    public $layout_id;                       // int(11)  
    public $appears_in_nav;                  // int(1)  not_null
    public $are_comments_allowed;            // int(1)  not_null
    public $date_created;                    // datetime(19)  not_null binary
    public $last_updated;                    // datetime(19)  not_null binary
    public $created_by;                      // int(11)  not_null multiple_key
    public $updated_by;                      // int(11)  not_null multiple_key

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Page',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
