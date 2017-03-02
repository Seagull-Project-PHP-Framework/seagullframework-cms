<?php
require_once 'Uber.php';
Uber::init();
$cms_path       = SGL_PATH .'/modules/cms/lib';
Uber_Loader::registerNamespace('SGL', array(SGL_LIB_DIR, $cms_path));

class ContentTest extends UnitTestCase {

    function ContentTest()
    {
        $this->UnitTestCase('Content Test');
    }

    //  empty content
    function testContentMapperNoType()
    {
        $oContent = new SGL_Content;
        $this->assertTrue(is_a($oContent, 'SGL_Content'));
        $this->assertFalse(count($oContent->aAttribs));
    }

    //  empty content with relevant attribs for type 2
    function testContentMapperWithType()
    {
        $typeId = 2;
        $oContent = SGL_Content::getByType($typeId);
        $this->assertTrue(is_a($oContent, 'SGL_Content'));
        $this->assertTrue(count($oContent->aAttribs));
    }

    //  content with data
    function testContentMapperNoTypeWithData()
    {
        $oContent = SGL_Content::getById(6);
        $this->assertTrue(is_a($oContent, 'SGL_Content'));
        $this->assertTrue(count($oContent->aAttribs));
        $this->assertEqual(6, $oContent->id);
    }

    function testSettingStatus()
    {
        $oContent = SGL_Content::getById(6);
        $oContent->setStatus(SGL_CMS_STATUS_PUBLISHED);
        $oSaved = $oContent->save();
        $this->assertEqual($oSaved->getStatus(), SGL_CMS_STATUS_PUBLISHED);
    }

    //  all content types
    function testContentListMapperNoType()
    {
        $list = SGL_Finder::factory('contenttype')->retrieve();
        $this->assertTrue(is_array($list));
        $oContent = $list[1];
        $this->assertTrue(is_a($oContent, 'SGL_Content'));
        $this->assertTrue(count($oContent->aAttribs));
    }

    //  returns all content with data
    function testContentListMapperNoTypeWithDataSummary()
    {
        $list = SGL_Finder::factory('content')->retrieve();
        $this->assertTrue(is_array($list));
        $oContent = $list[1];
        $this->assertTrue(is_a($oContent, 'SGL_Content'));
    }

    //  all content with data of type 2
    function testContentListMapperWithTypeWithData()
    {
        $list = SGL_Finder::factory('content')
            ->addFilter('typeId', 2)
            ->retrieve();
        $this->assertTrue(is_array($list));
        $this->assertEqual(count($list), 1);
        $oContent = $list[0];
        $this->assertTrue(is_a($oContent, 'SGL_Content'));
    }

    function test_SGL_Attribute_getById()
    {
        $id = 10;
        $oAttribute = SGL_Attribute::getById($id);
        $this->assertTrue(is_a($oAttribute, 'SGL_Attribute'));
        $this->assertTrue(isset($oAttribute->id));
        $this->assertTrue(isset($oAttribute->typeId));
        $this->assertTrue(isset($oAttribute->contentTypeId));
        $this->assertTrue(isset($oAttribute->name));
        $this->assertTrue(isset($oAttribute->alias));
        $this->assertTrue(empty($oAttribute->params));
    }

    function test_SGL_Attribute_getType()
    {
        $id = 10;
        $oAttribute = SGL_Attribute::getById($id);
        $this->assertEqual($oAttribute->getType(), SGL_CONTENT_ATTR_TYPE_RICHTEXT);
    }

    function test_SGL_Attribute_changeType()
    {
        $id = 10;
        $oAttribute = SGL_Attribute::getById($id);
        $this->assertEqual($oAttribute->getType(), SGL_CONTENT_ATTR_TYPE_RICHTEXT);
        $this->assertTrue($oAttribute->changeType(SGL_CONTENT_ATTR_TYPE_LARGETEXT));
        $this->assertEqual($oAttribute->getType(), SGL_CONTENT_ATTR_TYPE_LARGETEXT);
    }

    function test_SGL_Attribute_rename()
    {
        $id = 10;
        $oAttribute = SGL_Attribute::getById($id);
        $this->assertEqual($oAttribute->name, 'hobbies');
        $this->assertTrue($oAttribute->rename('foo'));
        $this->assertEqual($oAttribute->name, 'foo');
    }

    function test_SGL_Attribute_setValue()
    {
        $id = 10;
        $oAttribute = SGL_Attribute::getById($id);
        $this->assertNull($oAttribute->get());
        $oAttribute->set('bar');
        $this->assertEqual($oAttribute->value, 'bar');

    }

    function test_SGL_Attribute_delete()
    {
        $id = 10;
        $oAttribute = SGL_Attribute::getById($id);
        $this->assertTrue(is_a($oAttribute, 'SGL_Attribute'));
        $this->assertTrue($oAttribute->delete());
        $oAttribute = SGL_Attribute::getById($id);
        $this->assertTrue(is_null($oAttribute->id));
    }

    function test_SGL_Attribute_instantiation()
    {
        $aProps = array('name' => 'dateEaten', 'typeId' => SGL_CONTENT_ATTR_TYPE_DATE);
        $oAttribute = new SGL_Attribute($aProps);
        $this->assertTrue(is_a($oAttribute, 'SGL_Attribute'));
        $this->assertTrue(isset($oAttribute->name));
        $this->assertTrue(isset($oAttribute->typeId));
        $this->assertTrue(!isset($oAttribute->params));
        // Attribute with params
        $aProps = array(
            'name' => 'country',
            'typeId' => SGL_CONTENT_ATTR_TYPE_LIST,
            'params' => array(
                'listTypeId' => 1,
                'foo' => 'bar'
            )
        );
        $oAttribute = new SGL_Attribute($aProps);
        $this->assertTrue(is_a($oAttribute, 'SGL_Attribute'));
        $this->assertTrue(isset($oAttribute->name));
        $this->assertTrue(isset($oAttribute->typeId));
        $this->assertTrue(isset($oAttribute->params));
        $this->assertEqual(count($oAttribute->params), 2);
    }

    function test_SGL_Content_getById()
    {
        $id = 6;
        $oContent = SGL_Content::getById($id);
        $this->assertTrue(is_a($oContent, 'SGL_Content'));
        $this->assertTrue(count($oContent->aAttribs));
        $this->assertEqual(count($oContent->aAttribs), 2);
    }


    function test_SGL_Content_getByName()
    {
        $name = 'Alouicious Bird Resume';
        $oContent = SGL_Content::getByName($name);
        $this->assertTrue(is_a($oContent, 'SGL_Content'));
        $this->assertTrue(count($oContent->aAttribs));
        $this->assertEqual(count($oContent->aAttribs), 7);
    }

    function test_SGL_Content_getType()
    {
        $name = 'Article';
        $oContent = SGL_Content::getByType($name);
        $this->assertTrue(is_a($oContent, 'SGL_Content'));
        $this->assertTrue(count($oContent->aAttribs));
        $this->assertEqual(count($oContent->aAttribs), 3);
    }

    function test_SGL_Content_rename()
    {
        $id = 6;
        $oContent = SGL_Content::getById($id);
        $this->assertTrue(is_a($oContent, 'SGL_Content'));
        $this->assertTrue(count($oContent->aAttribs));
        $this->assertEqual($oContent->name, 'Article with leader and body');
        $this->assertTrue($oContent->rename('foo'));

        $this->assertEqual($oContent->name, 'foo');
    }

    function test_SGL_Content_renameTypeName()
    {
        $typeId = 1;
        $oContent = SGL_Content::getByType($typeId);
        $this->assertTrue(is_a($oContent, 'SGL_Content'));
        $this->assertEqual($oContent->typeName, 'Article');
        $oContent->rename('foo');

        $this->assertEqual($oContent->typeName, 'foo');
        // Rename back to Article not to break further tests
        $oContent->rename('Article');
        $this->assertEqual($oContent->typeName, 'Article');
    }

    function test_SGL_Content_createType()
    {
        $name = 'Foo';
        $oContent = SGL_Content::createType($name);
        $this->assertTrue(is_a($oContent, 'SGL_Content'));
        $this->assertTrue(isset($oContent->typeId));
        $this->assertTrue(isset($oContent->typeName));
    }

    function test_SGL_Content_dynamicCreateType()
    {
        $oRestoReview = SGL_Content::createType('RestaurantReview')
            ->addAttribute(new SGL_Attribute(array('name' => 'dateEaten', 'typeId' => SGL_CONTENT_ATTR_TYPE_DATE)))
            ->addAttribute(new SGL_Attribute(array('name' => 'dishName', 'typeId' => SGL_CONTENT_ATTR_TYPE_TEXT)))
            ->addAttribute(new SGL_Attribute(array('name' => 'overallRating', 'typeId' => SGL_CONTENT_ATTR_TYPE_FLOAT)));
        $this->assertTrue(is_array($oRestoReview->aAttribs));
        $this->assertEqual(count($oRestoReview->aAttribs), 3);
        $this->assertEqual($oRestoReview->typeName, 'RestaurantReview');
        $this->assertTrue(!empty($oRestoReview->typeId));
    }

    function test_SGL_Content_dynamicCreateTypeWithParams()
    {
        $oRestaurant = SGL_Content::createType('Restaurant')
            ->addAttribute(new SGL_Attribute(array('name' => 'name', 'typeId' => SGL_CONTENT_ATTR_TYPE_TEXT)))
            ->addAttribute(new SGL_Attribute(array('name' => 'address', 'typeId' => SGL_CONTENT_ATTR_TYPE_LARGETEXT)))
            ->addAttribute(new SGL_Attribute(array('name' => 'postalCode', 'typeId' => SGL_CONTENT_ATTR_TYPE_TEXT)))
            ->addAttribute(new SGL_Attribute(array('name' => 'city', 'typeId' => SGL_CONTENT_ATTR_TYPE_LIST,
                'params' => array('listTypeId' => 1))))
            ->addAttribute(new SGL_Attribute(array('name' => 'country', 'typeId' => SGL_CONTENT_ATTR_TYPE_LIST,
                'params' => array('listTypeId' => 2))));
        $this->assertTrue(is_array($oRestaurant->aAttribs));
        $this->assertEqual(count($oRestaurant->aAttribs), 5);
        $this->assertEqual($oRestaurant->typeName, 'Restaurant');
        $this->assertTrue(!empty($oRestaurant->typeId));
        $this->assertTrue(empty($oRestaurant->aAttribs[0]->params));
        $this->assertTrue(!empty($oRestaurant->aAttribs[3]->params));
    }

    function test_SGL_Content_dynamicCreateTypeSave()
    {
        $oRestoReview  = SGL_Content::createType('RestaurantReview1')
            ->addAttribute(new SGL_Attribute(array('name' => 'dateEaten', 'typeId' => SGL_CONTENT_ATTR_TYPE_DATE)))
            ->addAttribute(new SGL_Attribute(array('name' => 'dishName', 'typeId' => SGL_CONTENT_ATTR_TYPE_TEXT)))
            ->addAttribute(new SGL_Attribute(array('name' => 'overallRating', 'typeId' => SGL_CONTENT_ATTR_TYPE_FLOAT)))
            ->save();
        $this->assertTrue(is_a($oRestoReview, 'SGL_Content'));
        $this->assertTrue(is_array($oRestoReview->aAttribs));
        $this->assertEqual(count($oRestoReview->aAttribs), 3);
        //  ensure new attribs got IDs
        foreach ($oRestoReview->aAttribs as $oAttrib) {
            $this->assertTrue(!empty($oAttrib->id));
        }
        $this->assertEqual($oRestoReview->typeName, 'RestaurantReview1');
        $this->assertTrue(!empty($oRestoReview->typeId));
        //  test setting new values
        $oRestoReview->langCode = 'en';
        $oRestoReview->name = 'test obj';
        $oRestoReview->dateEaten = '12/34/05';
        $oRestoReview->dishName = 'tiramisu';
        $oRestoReview->overallRating = 9.5;
        $oNewObj = $oRestoReview->save();
        $this->assertEqual($oNewObj->dateEaten, '12/34/05');
        $this->assertEqual($oNewObj->dishName, 'tiramisu');
        $this->assertEqual($oNewObj->overallRating, 9.5);
        //  verify against persisted object
        $oFromDb = SGL_Content::getById($oNewObj->id);
        $this->assertEqual($oFromDb->name, 'test obj');
        $this->assertEqual($oFromDb->dateEaten, '12/34/05');
        $this->assertEqual($oFromDb->dishName, 'tiramisu');
        $this->assertEqual($oFromDb->overallRating, 9.5);
    }

    function test_SGL_Content_createContentWithChoiceAttrib()
    {
        // Let's create a new Curriculum Vitae with empty choice attrib
        //  pass data from post as arg
        $input = new stdClass();
        $input->content['versionId'] = '';
        $input->content['id'] = '';
        $input->content['typeId'] = 2;
        $input->content['name'] = 'New Curriculum Vitae';

        $input->content['attributes']['data'][0] = 'http://www.seagullproject.org Seagull Project';
        $input->content['attributes']['attr_id'][0] = 3;

        $input->content['attributes']['data'][1] = '20 000$';
        $input->content['attributes']['attr_id'][1] = 4;

        $input->content['attributes']['data'][2] = '1980-01-01';
        $input->content['attributes']['attr_id'][2] = 5;

        // In case of an unchecked checkbox field, no 'data' available
        $input->content['attributes']['data'][3][] = 'bar';
        $input->content['attributes']['data'][3][] = 'foo';
        $input->content['attributes']['attr_id'][3] = 6;
        $input->content['attributes']['checkbox'][6] = '';

        $input->content['attributes']['data'][4] = 1;
        $input->content['attributes']['attr_id'][4] = 7;

        $input->content['attributes']['data'][5] = 'Some experience';
        $input->content['attributes']['attr_id'][5] = 8;

        $input->content['attributes']['data'][6] = 'Some skills';
        $input->content['attributes']['attr_id'][6] = 9;

        $input->content['attributes']['data'][7] = 'Some hobbies';
        $input->content['attributes']['attr_id'][7] = 10;

        $this->validateContentInputFields($input);

        $oContext = new SGL_Context($input);

        //  context strategy determines if Content or ContentType
        //  and maps data correctly
        //  SGL_Content object can be built from any input (php object, post, xml, etc)
        $oContent = new SGL_Content($oContext->process());
        $oContent->save();

        $this->assertTrue(is_array($oContent->aAttribs));
        $oContentType = SGL_Content::getByType('Curriculum Vitae');
        $this->assertEqual(count($oContent->aAttribs), 8);
        $this->assertEqual($oContent->aAttribs[3]->value, "bar;foo");
    }

    function validateContentInputFields($input)
    {
        $input->content = (object) $input->content;
        //  reformat checkbox/radio input submissions
        if (isset($input->content->attributes['checkbox'])) {
            for ($x = 0; $x < $total = count($input->content->attributes['attr_id']); $x++) {
                if (isset($input->content->attributes['checkbox'][$input->content->attributes['attr_id'][$x]])) {
                    if (isset($input->content->attributes['data'][$x])) {
                        // If checkboxes, we have an array to convert to string
                        $input->content->attributes['data'][$x] = is_array($input->content->attributes['data'][$x])
                            ? implode(';', $input->content->attributes['data'][$x])
                            : $input->content->attributes['data'][$x];
                    } else {
                        $input->content->attributes['data'][$x] = '';

                    }
                }
            }
            unset($input->content->attributes['checkbox']);
            ksort($input->content->attributes['data']);
        }
    }

    function test_SGL_Content_overloadSet()
    {
        $id = 6;
        $oContent = SGL_Content::getById($id);
        $this->assertEqual($oContent->aAttribs[0]->name, 'introduction');
        $this->assertEqual($oContent->aAttribs[0]->value, 'This is a textarea');
        $oContent->introduction = 'http://example.com';
        $this->assertEqual($oContent->aAttribs[0]->name, 'introduction');
        $this->assertEqual($oContent->aAttribs[0]->value, 'http://example.com');
    }

    function test_SGL_Content_overloadGet()
    {
        $id = 6;
        $oContent = SGL_Content::getById($id);
        $this->assertEqual($oContent->aAttribs[0]->name, 'introduction');
        $this->assertEqual($oContent->aAttribs[0]->value, 'This is a textarea');
        $ret = $oContent->introduction;
        $this->assertEqual($ret, 'This is a textarea');
    }

    function test_SGL_ContentSaveWhenUpdate()
    {
        $id = 7;
        $oContent = SGL_Content::getById($id);
        $oSavedContent = $oContent->save();
        $this->assertEqual($oContent, $oSavedContent);
        $this->assertIdentical($oContent, $oSavedContent);
    }

    function xtest_SGL_ContentSaveWithExistingAttribsAndNewAttrib()
    {
        $id = 7;
        $oContent = SGL_Content::getById($id);
        $oContent->addAttribute(new SGL_Attribute(
            array('name' => 'dateEaten', 'typeId' => SGL_CONTENT_ATTR_TYPE_DATE)));
        $oContent->dateEaten = '12/23/04';
        $oNewContent = $oContent->save();
        //  ensure new attrib got an ID
        $this->assertNotNull($oContent->getAttribId('dateEaten'));
        //  verify against persisted object
        $oFromDb = SGL_Content::getById($oNewContent->id);
        $this->assertIdentical($oNewContent, $oFromDb);
    }

    function testValidateEmpty()
    {
        $attrib1 = new stdClass();
        $attrib2 = new stdClass();
        $oContent = new SGL_Content();
        $oContent->aAttribs[] = $attrib1;
        $oContent->aAttribs[] = $attrib2;
        $oContent->aAttribs[0]->name = 'foo';
        $oContent->aAttribs[1]->name = '';
        $this->assertFalse($oContent->validate());
    }

    function testValidateDuplicates()
    {
        $attrib1 = new stdClass();
        $attrib2 = new stdClass();
        $attrib3 = new stdClass();
        $oContent = new SGL_Content();
        $oContent->aAttribs[] = $attrib1;
        $oContent->aAttribs[] = $attrib2;
        $oContent->aAttribs[0]->name = 'foo';
        $oContent->aAttribs[1]->name = 'bar';
        $oContent->aAttribs[2]->name = 'foo';
        $this->assertFalse($oContent->validate());
    }
}
?>
