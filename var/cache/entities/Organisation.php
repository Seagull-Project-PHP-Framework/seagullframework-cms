<?php
/**
 * Table Definition for organisation
 */
require_once 'DB/DataObject.php';

class DataObjects_Organisation extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'organisation';                    // table name
    public $organisation_id;                 // int(11)  not_null primary_key
    public $parent_id;                       // int(11)  not_null
    public $root_id;                         // int(11)  not_null
    public $left_id;                         // int(11)  not_null
    public $right_id;                        // int(11)  not_null
    public $order_id;                        // int(11)  not_null
    public $level_id;                        // int(11)  not_null
    public $role_id;                         // int(11)  not_null
    public $organisation_type_id;            // int(11)  not_null
    public $name;                            // string(384)  
    public $description;                     // blob(196605)  blob
    public $addr_1;                          // string(384)  not_null
    public $addr_2;                          // string(384)  
    public $addr_3;                          // string(384)  
    public $city;                            // string(96)  not_null
    public $region;                          // string(96)  
    public $country;                         // string(6)  
    public $post_code;                       // string(48)  
    public $telephone;                       // string(96)  
    public $website;                         // string(384)  
    public $email;                           // string(384)  
    public $date_created;                    // datetime(19)  binary
    public $created_by;                      // int(11)  
    public $last_updated;                    // datetime(19)  binary
    public $updated_by;                      // int(11)  

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Organisation',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
