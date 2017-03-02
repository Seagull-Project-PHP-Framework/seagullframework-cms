<?php
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'Uber.php';

$sgl_path = $_SERVER['SGL_PATH'];
require_once $sgl_path  . '/lib/SGL/FrontController.php';



/**
 *  test case.
 */
class SGL_Finder_Test extends PHPUnit_Framework_TestCase
{

    function setup()
    {
        /**
         * Uber::init() always on top, will require the Autoloader class etc.
         */
        Uber::init();
        //  setup sgl + cms lib paths
        $sgl_lib_path   = $GLOBALS['sgl_path'] . '/lib';
        $cms_path       = $GLOBALS['sgl_path'] .'/modules/cms/lib';
        Uber_Loader::registerNamespace('SGL', array($sgl_lib_path, $cms_path));

        //  init sgl fw
        SGL_FrontController::init();
        $ok = SGL_Config::set('db.name', 'seagull');

        parent::setUp();
    }

    public function test_buildQuery()
    {
        $finder = SGL_Finder::factory('content')
        	->addFilter('typeName', 'Article')
        	->addFilter('createdBy', SGL_Session::getUid())
            ->addFilter('dateCreated', array('operator' => '<', 'value' => '2009-09-01 00:00:00'))
            ->addFilter('lastUpdated', array('operator' => '>=', 'value' => '2009-08-01 00:00:00'))
        	->addFilter('sortOrder', 'DESC')
            ;

        $sql = $finder->getSql();


//print_r($sql);
    }
}

