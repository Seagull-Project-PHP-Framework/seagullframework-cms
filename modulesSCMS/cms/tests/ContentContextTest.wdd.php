<?php
require_once 'Uber.php';
Uber::init();
$cms_path       = SGL_PATH .'/modules/cms/lib';
Uber_Loader::registerNamespace('SGL', array(SGL_LIB_DIR, $cms_path));

/**
 * Test suite.
 *
 * @package user
 * @author  Demian Turner <demian@phpkitchen.net>
 * @version $Id: UserDAOTest.wdb.php,v 1.1 2005/06/23 15:18:06 demian Exp $
 */
class ContentContextTest extends UnitTestCase
{

    function ContentContextTest()
    {
        $this->UnitTestCase('ContentContext Test');
    }

    function testContextFactoryNoStrat()
    {
        $oContext = new SGL_Context(new stdClass());
        $this->assertIsA($oContext->process(), 'PEAR_Error');
    }

    function testContextFactoryStrat()
    {
        $input = new stdClass();
        $input->content = 'foo';
        $oContext = new SGL_Context($input);
        $this->assertIsA($oContext->process(), 'SGL_CmsContextStrategy');
    }

    function testContextProcessingWebContent()
    {
        $oData = new stdClass();
        $oData->content = array(
           'id' => 10,
           'version' => 1,
           'language_id' => 1,
           'type_id' => '16',
           'name' => 'Example of CV format HTML content',
           'attributes' =>
          array (
            'data' =>
            array (
              0 => 'fsdfsdf',
              1 => 'sdfsdfsdf',
              2 => 'qsdfqsdf',
              3 => 'qsdfqsdfqsdf',
              4 => 'sqdfqsdf',
            ),
            'attr_id' =>
            array (
              0 => '38',
              1 => '39',
              2 => '40',
              3 => '41',
              4 => '42',
            ),
          ),
        );
        $oContext = new SGL_Context($oData);
        $this->assertIsA($oContext->process(), 'SGL_Context_WebContent');
    }

    function testContextProcessingWebContentType()
    {
        $oData = new stdClass();
        $oData->contentType = array(
          'id' => '',
          'name' => 'my test content',
          'attributes' =>
          array (
            1 =>
            array (
              'fieldAlias' => 'attrib one',
              'fieldName' => 'attribOne',
              'fieldType' => '1'
            ),
            2 =>
            array (
              'fieldAlias' => 'attrib two',
              'fieldName' => 'attribTwo',
              'fieldType' => '2'
            ),
            3 =>
            array (
              'fieldAlias' => 'attrib three',
              'fieldName' => 'attribThree',
              'fieldType' => '3'
            ),
            4 =>
            array (
              'fieldAlias' => 'attrib four',
              'fieldName' => 'attribFour',
              'fieldType' => '10',
              'fieldParams' => array('attributeListId' => 1),
            ),
          ),
        );
        $oContext = new SGL_Context($oData);
        $this->assertIsA($oContext->process(), 'SGL_Context_WebContentType');
    }
}
?>