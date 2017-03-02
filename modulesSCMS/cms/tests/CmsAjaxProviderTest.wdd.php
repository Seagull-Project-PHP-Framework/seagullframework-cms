<?php
require_once 'Uber.php';
Uber::init();
$cms_path       = SGL_PATH .'/modules/cms/lib';
Uber_Loader::registerNamespace('SGL', array(SGL_LIB_DIR, $cms_path));
require_once SGL_MOD_DIR . '/cms/classes/CmsAjaxProvider.php';



/**
 * Test suite.
 *
 * @package user
 * @author  Demian Turner <demian@phpkitchen.net>
 * @version $Id: UserDAOTest.wdb.php,v 1.1 2005/06/23 15:18:06 demian Exp $
 */
class CmsAjaxProviderTest extends UnitTestCase
{

    function CmsAjaxProviderTest()
    {
        $this->UnitTestCase('CmsAjaxProvider Test');
    }

    function testAddContentType() /* READY, waiting for ajax provider returning only arrays */
    {
        $provider = & CmsAjaxProvider::singleton();
        $aContentTypes = SGL_Finder::factory('contenttype')->retrieve();
        $this->assertEqual(count($aContentTypes), 2);
        $newContentType = array(
            'name' => 'New content type'
        );
        SGL_Request::singleton()->set('contentType', $newContentType);
        $aRet = $provider->addContentType();
        $aContentTypes = SGL_Finder::factory('contenttype')->retrieve();
        $this->assertEqual(count($aContentTypes), 3);
        $this->assertTrue($aRet['id']);
        //  Remember this contentTypeId for further use
        $this->lastInsertedId = $aRet['id'];
        //  Check for content type name uniqueness
        //  I.e this content type should not be inserted
        SGL_Request::singleton()->set('contentType', $newContentType);
        $provider->addContentType();
        $aContentTypes = SGL_Finder::factory('contenttype')->retrieve();
        $this->assertEqual(count($aContentTypes), 3);
    }

    function testUpdateContentTypeName() /* READY, waiting for ajax provider returning only arrays */
    {
        $provider = CmsAjaxProvider::singleton();
        $aContentTypes = SGL_Finder::factory('contenttype')->retrieve();
        $this->assertEqual(count($aContentTypes), 3);
        SGL_Request::singleton()->set('contentTypeId', $this->lastInsertedId);
        $contentType = array(
            'name' => 'Updated name'
        );
        SGL_Request::singleton()->set('contentType', $contentType);
        $aRet = $provider->updateName();
        $this->assertTrue(is_a($aRet['contentType'], 'SGL_Content'));
        $this->assertEqual($aRet['contentType']->typeName, 'Updated name');
        //  Check for content type name uniqueness
        //  I.e this content type should not be updated
        $contentType = array(
            'name' => 'Article'
        );
        SGL_Request::singleton()->set('contentType', $contentType);
        $aRet = $provider->updateName();
        $this->assertEqual($aRet['status'], -1);
        $oContentType = SGL_Content::getByType($this->lastInsertedId);
        $this->assertEqual($oContentType->typeName, 'Updated name');
        //  Ensure we still have same number of content types
        $aContentTypes = SGL_Finder::factory('contenttype')->retrieve();
        $this->assertEqual(count($aContentTypes), 3);
    }

    function testDeleteContentType() /* READY, waiting for ajax provider returning only arrays */
    {
        $provider = CmsAjaxProvider::singleton();
        $aContentTypes = SGL_Finder::factory('contenttype')->retrieve();
        $this->assertEqual(count($aContentTypes), 3);
        SGL_Request::singleton()->set('contentTypeId', $this->lastInsertedId);
        $provider->deleteContentType();
        $aContentTypes = SGL_Finder::factory('contenttype')->retrieve();
        $this->assertEqual(count($aContentTypes), 2);
    }

    function test_outputFilteredContents()
    {
        $provider = CmsAjaxProvider::singleton();

        $ret = $provider->outputFilteredContents(array(), '', '');
        $this->assertTrue(is_array($ret));
        $this->assertTrue($ret['status']);

        $ret = $provider->outputFilteredContents(array('typeId' => 1), '', '');
        $this->assertTrue(is_array($ret));
        $this->assertTrue($ret['status']);

        $ret = $provider->outputFilteredContents(array(), 'name', '');
        $this->assertTrue(is_array($ret));
        $this->assertTrue($ret['status']);
    }

    function test_outputContent()
    {
        $provider = CmsAjaxProvider::singleton();

        $ret = $provider->outputContent(2, 'edit');
        $this->assertTrue(is_array($ret));
        $this->assertTrue($ret[0]);
    }

    function test_updateAttributeValueById()
    {
        $provider = CmsAjaxProvider::singleton();
        $req = SGL_Request::singleton();
        $oAttrib = array(
            "contentId"     => 2,
            "version"       => 1,
            "langCode"      => 'en',
            "id"            => 10,
            "value"         => 'test data'
        );
        $req->set('attrib', $oAttrib);

        $ok = $provider->updateAttributeValueById();
        // $ok should be 2, cause updateAttributeValueById uses REPLACE to perform DB operation
        $this->assertEqual(2, $ok);
    }

    function test_getTranslations()
    {
        $req = SGL_Request::singleton();
        $req->set('lang', 'en-iso-8859-15');
        $req->set('dictionary', 'default');
        $provider = CmsAjaxProvider::singleton();
        // Test default translations
        $aResponse = $provider->getTranslations();
        $this->assertTrue(is_array($aResponse));
        $this->assertEqual($aResponse['status'], 1);
        $aTranslations = $aResponse['translations'];
        $this->assertTrue(is_array($aTranslations));
        $this->assertTrue(count($aTranslations));
        $this->assertTrue(array_key_exists('Home', $aTranslations));
    }
}
?>
