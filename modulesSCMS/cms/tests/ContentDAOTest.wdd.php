<?php
require_once SGL_MOD_DIR . '/cms/classes/CmsDAO.php';
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
class DaoContentTest extends UnitTestCase {

    function DaoContentTest()
    {
        $this->UnitTestCase('DaoContent Test');
    }

    function setup()
    {
        //  get CmsDAO object
        $this->da = CmsDAO::singleton();
    }

    function testGetContentTypeAttribsByIdWithNoId()
    {
        $aData = $this->da->getContentTypeAttribsById();
        $this->assertTrue(is_array($aData));
        $obj = current($aData);

        //  standard fields
        $this->assertTrue(isset($obj->content_type_id));
        $this->assertTrue(isset($obj->content_type_name));
        $this->assertTrue(isset($obj->attr_id));
        $this->assertTrue(isset($obj->attr_name));
        $this->assertTrue(isset($obj->attr_alias));
        $this->assertTrue(isset($obj->attr_type_id));
        $this->assertTrue(isset($obj->attr_params));
        $this->assertTrue(empty($obj->attr_params));
    }

    function testGetContentTypeAttribsById()
    {
        $aData = $this->da->getContentTypeAttribsById(2);//CV Format Simple
        $this->assertTrue(is_array($aData));
        $this->assertEqual(count($aData), 8);
        $obj = current($aData);

        //  standard fields
        $this->assertTrue(isset($obj->content_type_id));
        $this->assertTrue(isset($obj->content_type_name));
        $this->assertTrue(isset($obj->attr_id));
        $this->assertTrue(isset($obj->attr_name));
        $this->assertTrue(isset($obj->attr_alias));
        $this->assertTrue(isset($obj->attr_type_id));
        $this->assertTrue(isset($obj->attr_params));
        $this->assertTrue(empty($obj->attr_params));
    }

    function testGetAttributeTypeByIdWithoutId()
    {
        $aData = $this->da->getAttribTypes();
        $this->assertTrue(is_array($aData));
        $obj = current($aData);
        $this->assertTrue(isset($obj->attribute_type_id));
        $this->assertTrue(isset($obj->name));
        $this->assertTrue(isset($obj->alias));
    }

    function xtestIsUploadable()
    {
        $contentTypeId = 12; // content type 12 has an upload field in default data
        $isUploadable = $this->da->_isContentTypeUploadable($contentTypeId);
        $this->assertTrue($isUploadable);
    }

    function testGetMatchingContents()
    {
        //  test total number of content items
        //  because no type ID is passed, all content will be
        //  returned
        $query = $this->da->buildSearchQuery();
        $aData = $this->da->getMatchingContents($query);
        $this->assertTrue(is_array($aData));
        $this->assertEqual(count($aData), 5);
    }

    function testGetContentById()
    {
        // test contents were all added in english
        $obj = $this->da->getContentById(6, 'en');
        $this->assertTrue(is_object($obj));
        $this->assertTrue(isset($obj->content_name));
        $this->assertTrue(isset($obj->created_by_id));
        $this->assertTrue(isset($obj->updated_by_id));
    }

    function testContentTypeDetection()
    {
        $oContent = new SGL_Content();
        $oContent->typeName = 'New content type';
        $res = CmsDAO::_getOperationType($oContent);
        $this->assertEqual($res, SGL_CONTENTTYPE_INSERT);

        $oContent->typeId = '1';
        $res = CmsDAO::_getOperationType($oContent);
        $this->assertEqual($res, SGL_CONTENTTYPE_UPDATE);


        $oContent = SGL_Content::getByType($typeId = 2);
        $res = CmsDAO::_getOperationType($oContent);
        $this->assertEqual($res, SGL_CONTENTTYPE_UPDATE);

        $oContent->name = 'New content';
        $res = CmsDAO::_getOperationType($oContent);
        $this->assertEqual($res, SGL_CONTENT_INSERT);

        $oContent->id = 1;
        $res = CmsDAO::_getOperationType($oContent);
        $this->assertEqual($res, SGL_CONTENT_UPDATE);

        $oContent = SGL_Content::getById(6);
        $res = CmsDAO::_getOperationType($oContent);
        $this->assertEqual($res, SGL_CONTENT_UPDATE);
    }

    function testGetNextContentVersion()
    {
        // test existing content version
        $contentId = 6;
        $currentVersion = 1;
        $nextVersion = $this->da->getNextContentVersion($contentId, 'en');
        $this->assertEqual($nextVersion, $currentVersion + 1);
        // test non created content version
        $contentId = 999;
        $currentVersion = 0;
        $nextVersion = $this->da->getNextContentVersion($contentId, 'en');
        $this->assertEqual($nextVersion, $currentVersion + 1);
    }
}

?>
