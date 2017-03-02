<?php
/**
 * Table Definition for module
 */
require_once 'DB/DataObject.php';

class DataObjects_Module extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'module';                          // table name
    public $module_id;                       // int(11)  not_null primary_key
    public $is_configurable;                 // int(1)  
    public $name;                            // string(765)  
    public $title;                           // string(765)  
    public $description;                     // blob(196605)  blob
    public $admin_uri;                       // string(765)  
    public $icon;                            // string(765)  
    public $maintainers;                     // blob(196605)  blob
    public $version;                         // string(24)  
    public $license;                         // string(48)  
    public $state;                           // string(24)  

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Module',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
