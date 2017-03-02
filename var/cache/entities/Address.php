<?php
/**
 * Table Definition for address
 */
require_once 'DB/DataObject.php';

class DataObjects_Address extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'address';                         // table name
    public $address_id;                      // int(11)  not_null primary_key
    public $address1;                        // string(384)  
    public $address2;                        // string(384)  
    public $city;                            // string(384)  
    public $state;                           // string(384)  
    public $post_code;                       // string(384)  
    public $country;                         // string(384)  

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Address',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
