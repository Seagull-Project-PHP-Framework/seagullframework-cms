<?php
require_once 'Uber.php';
Uber::init();
$cms_path       = SGL_PATH .'/modules/cms/lib';
Uber_Loader::registerNamespace('SGL', array(SGL_LIB_DIR, $cms_path));

class ContentCollectionTest extends UnitTestCase {

    function ContentCollectionTest()
    {
        $this->UnitTestCase('ContentCollection Test');
    }

    function testFinderFactory()
    {
        $ret = SGL_Finder::factory('content');
        $this->assertIsA($ret, 'SGL_Finder_Content');
        $this->assertTrue(empty($ret->_aFilters));
    }

    function testFinderFactoryWithFilters()
    {
        $ret = SGL_Finder::factory('content')
            ->addFilter('foo', 'bar')
            ->addFilter('baz', 'quux');
        $this->assertIsA($ret, 'SGL_Finder_Content');
        $this->assertEqual(count($ret->getFilters()), 2);
    }

    function testFilteringByTypeId()
    {
        $aContent = SGL_Finder::factory('content')
            ->addFilter('typeId', 2) //  	CV Format Simple
            ->retrieve();
        $this->assertTrue(is_array($aContent));
        $this->assertIsA($aContent[0], 'SGL_Content');
        //  test content object is populated
        $this->assertTrue(is_array($aContent[0]->aAttribs));
    }

    function testFilteringByTypeIdZero()
    {
        $aContent = SGL_Finder::factory('content')
            ->addFilter('typeId', 0) //  	Means all types
            ->retrieve();
        $this->assertTrue(is_array($aContent));
        $this->assertEqual(count($aContent), 5);
        $this->assertIsA($aContent[0], 'SGL_Content');
        //  test content object is populated
        $this->assertTrue(is_array($aContent[0]->aAttribs));
    }

    function testFilteringByCreatedBy()
    {
        $aContent = SGL_Finder::factory('content')
            ->addFilter('createdBy', 1)
            ->retrieve();
        $this->assertTrue(is_array($aContent));
        $this->assertIsA($aContent[0], 'SGL_Content');
        //  test content object is populated
        $this->assertTrue(is_array($aContent[0]->aAttribs));
    }

    function testFilteringByCreatedByNoOne()
    {
        //  test we get an empty array of results
        $aContent = SGL_Finder::factory('content')
            ->addFilter('createdBy', 99)
            ->retrieve();
        $this->assertTrue(is_array($aContent));
        $this->assertFalse(count($aContent));
    }

    function testFilteringByTypeNameWithWrongTypeName()
    {
        $ret = SGL_Finder::factory('contentproxy') // to enable testing protected methods
            ->addFilter('typeName', 'NonExisting TypeName')
            ->_prepareContentConstraint();
        //  ensure correct query
        $expected = "WHERE c.content_type_id = -1 AND c.language_id = 'en' AND c.is_current = 1";
        //  remove newlines
        $ret = str_replace("\n", "", $ret);
        $this->assertEqual($ret, $expected);
        //  ensure no items returned
        $aContent = SGL_Finder::factory('content')
            ->addFilter('typeName', 'NonExisting TypeName')
            ->retrieve();
        $this->assertTrue(empty($aContent));
    }

    function testFilteringByTypeName()
    {
        $aContent = SGL_Finder::factory('content')
            ->addFilter('typeName', 'Curriculum Vitae')
            ->retrieve();
        $this->assertTrue(is_array($aContent));
        $this->assertIsA($aContent[0], 'SGL_Content');
        //  test content object is populated
        $this->assertTrue(is_array($aContent[0]->aAttribs));
    }

    function testFilterAttributeConstraint1()
    {
        $aRet = SGL_Finder::factory('contentproxy') // to enable testing protected methods
            ->addFilter('typeName', 'Article')
        	->addFilter('attribute', array(
                           'name' => 'body',
                           'operator' => '>',
                           'value' => 'myBody'))
            ->addFilter('attribute', array(
                           'name' => 'introduction',
                           'operator' => '=',
                           'value' => 'myIntroduction'))
            ->_prepareAttribConstraint();
        $expected  = "WHERE at.name = 'body' AND ad.value > 'myBody' AND at1.name = 'introduction' AND ad1.value = 'myIntroduction'";
        $aExpected = array(
            'where'  => $expected,
            'tables' => 'INNER JOIN attribute_data ad1
                        ON ad1.content_id = c.content_id
                        AND ad1.version = c.version
                        AND ad1.language_id = c.language_id
                    INNER JOIN attribute at1 ON at1.attribute_id = ad1.attribute_id '
        );
        //  removing whitespace for comparison purposes
        $string1 = preg_replace('/\s\s+/', ' ',$aRet);
        $string2 = preg_replace('/\s\s+/', ' ',$aExpected);
        $this->assertEqual($string1, $string2);
    }

    function testFilterAttributeConstraint2()
    {
        //  search for an article where with specific body
        $aRet = SGL_Finder::factory('content')
            ->addFilter('typeName', 'Article')
        	->addFilter('attribute', array(
                           'name' => 'body',
                           'operator' => '=',
                           'value' => 'And this is some richtext'))
            ->retrieve();
        //  ensure correct content object returned
        $this->assertEqual($aRet[0]->id, 6);
    }

    function testFilterAttributeConstraint3()
    {
        //  search for an article where with specific body
        $aRet = SGL_Finder::factory('content')
            ->addFilter('typeName', 'Article')
        	->addFilter('attribute', array(
                           'name' => 'isPublished',
                           'operator' => '=',
                           'value' => '1'))
            ->addFilter('sortBy', 'content_id')
            ->addFilter('sortOrder', 'asc')
            ->retrieve();
        //  ensure correct content object returned
        $this->assertEqual($aRet[0]->id, 8);
    }

    function testFilterAttributeConstraint4()
    {
        //  checking for returned attribConstraint
        $aRet = SGL_Finder::factory('contentproxy') // to enable testing protected methods
            ->addFilter('typeName', 'Article')
        	->addFilter('attribute', array(
                           'name' => 'body',
                           'operator' => '=',
                           'value' => 'And this is some richtext'))
            ->_prepareAttribConstraint();
        //  ensure correct query
        $expected = "WHERE at.name = 'body' AND ad.value = 'And this is some richtext'";
        $aExpected = array(
            'where'  => $expected,
            'tables' => ''
        );
        $this->assertEqual($aRet, $aExpected);
    }

    function testFilterAttributeConstraint5()
    {
        //  only set attribute name constraint
        $aRet = SGL_Finder::factory('content')
            ->addFilter('typeName', 'Article')
        	->addFilter('attribute', array(
                           'name' => 'isPublished'))
            ->retrieve();
        //  ensure correct number of contents returned
        $this->assertEqual(count($aRet), 3);
    }

    function testFilterAttributeSelect()
    {
        //  search for an article where with specific body
        $aRet = SGL_Finder::factory('content')
            ->addFilter('typeName', 'Article')
            ->addFilter('attribute', array(
                           'name' => 'isPublished',
                           'operator' => '=',
                           'value' => '1'))
            ->retrieve();
        $this->assertEqual(count($aRet[0]->aAttribs), 3);

        // see if we selected all fields for content type
        $aExpected = array('introduction', 'isPublished', 'body');
        $aRet2     = array();
        foreach ($aRet[0]->aAttribs as $oAttrib) {
            $aRet2[] = $oAttrib->name;
        }
        sort($aRet2); sort($aExpected);
        $this->assertEqual($aRet2, $aExpected);
    }

    function testFilterAttributeSelect2()
    {
        // search for article with specific header and body
        $aRet = SGL_Finder::factory('content')
            ->addFilter('typeName', 'Article')
            ->addFilter('attribute', array(
                'name'     => 'introduction',
                'operator' => '=',
                'value'    => 'This is a textarea'))
            ->addFilter('attribute', array(
                'name'     => 'body',
                'operator' => '=',
                'value'    => 'And this is some richtext'))
            ->retrieve();
        $this->assertEqual($aRet[0]->id, 6);

        // search for article with specific header only
        $aRet = SGL_Finder::factory('content')
            ->addFilter('typeName', 'Article')
            ->addFilter('attribute', array(
                'name'     => 'introduction',
                'operator' => '=',
                'value'    => 'foo'))
            ->retrieve();
        $this->assertEqual(count($aRet), 3);

        // search for article with specific header, body and published status
        $aRet = SGL_Finder::factory('content')
            ->addFilter('typeName', 'Article')
            ->addFilter('attribute', array(
                'name'     => 'introduction',
                'operator' => '=',
                'value'    => 'foo'))
            ->addFilter('attribute', array(
                'name'     => 'body',
                'operator' => '=',
                'value'    => 'bar'))
            ->addFilter('attribute', array(
                'name'     => 'isPublished',
                'operator' => '=',
                'value'    => '-1'))
            ->retrieve();
        $this->assertEqual(count($aRet), 1);
        $this->assertEqual($aRet[0]->id, 7);

        // search for article with header = 'foo' and body = 'bar'
        $aRet = SGL_Finder::factory('content')
            ->addFilter('typeName', 'Article')
            ->addFilter('attribute', array(
                'name'     => 'introduction',
                'operator' => '=',
                'value'    => 'foo'))
            ->addFilter('attribute', array(
                'name'     => 'body',
                'operator' => '=',
                'value'    => 'bar'))
            ->addFilter('sortBy', 'content_id')
            ->addFilter('sortOrder', 'ASC')
            ->retrieve();
        // ensure correct number of contents was recieved
        $this->assertEqual(count($aRet), 2);
        // ensure we recieved what we needed
        $this->assertEqual($aRet[0]->id, 7);
        $this->assertEqual($aRet[1]->id, 9);
    }

    function testFilterContentConstraint1()
    {
        //  checking for returned contentConstraint
        $aRet = SGL_Finder::factory('contentproxy') // to enable testing protected methods
            ->addFilter('typeName', 'Article')
        	->addFilter('attribute', array(
                           'name' => 'body',
                           'operator' => '=',
                           'value' => 'And this is some richtext'))
            ->_prepareContentConstraint();
        //  ensure correct query
        $expected = "WHERE c.content_type_id = 1 AND c.language_id = 'en' AND c.is_current = 1";
        //  remove newlines
        $aRet = str_replace("\n", "", $aRet);
        $this->assertEqual($aRet, $expected);
    }

    function testFilterContentConstraint2()
    {
        //  checking for returned contentConstraint
        //  when typeId == 0
        $aRet = SGL_Finder::factory('contentproxy') // to enable testing protected methods
            ->addFilter('typeId', 0)
            ->_prepareContentConstraint();
        //  ensure correct query
        $expected = "WHERE c.language_id = 'en' AND c.is_current = 1";
        //  remove newlines
        $aRet = str_replace("\n", "", $aRet);
        $this->assertEqual($aRet, $expected);
    }

    function testPrepareOrderingWithoutFilters()
    {
        //  don't set an ordering filter
        $aRet = SGL_Finder::factory('contentproxy') // to enable testing protected methods
            ->addFilter('typeName', 'Article')
            ->_prepareOrdering();
        //  ensure correct default ordering
        $expected = "ORDER BY last_updated DESC";
        $aExpected = array('constraint' => '', 'order' => $expected);
        $this->assertEqual($aRet, $aExpected);
    }

    function testPrepareOrderingWithFilters()
    {
        //  set both sortOrder and sortBy filters
        $aRet = SGL_Finder::factory('contentproxy') // to enable testing protected methods
            ->addFilter('typeName', 'Article')
        	->addFilter('sortBy', 'name')
        	->addFilter('sortOrder', 'ASC')
            ->_prepareOrdering();
        //  ensure correct ordering
        $expected = "ORDER BY name ASC";
        $aExpected = array('constraint' => '', 'order' => $expected);
        $this->assertEqual($aRet, $aExpected);
    }

    function testFilterOrdering()
    {
        //  set both sortOrder and sortBy filters
        $aRet = SGL_Finder::factory('content')
            ->addFilter('typeName', 'Article')
        	->addFilter('sortBy', 'name')
        	->addFilter('sortOrder', 'DESC')
            ->retrieve();
        //  ensure correct number of contents
        $this->assertEqual(count($aRet), 4);
        //  ensure first content is "unpublished article", content_id = 7
        $this->assertEqual($aRet[0]->id, 7);
    }

    function testFilterOrderingByAttrib()
    {
        // test when there is no attribute filtering
        $aRet = SGL_Finder::factory('content')
            ->addFilter('typeName', 'Article')
        	->addFilter('sortBy', array('attribute' => 'introduction'))
        	->addFilter('sortOrder', 'DESC')
            ->retrieve();
        // ensure 'article with header and body' is first one in the list,
        // it's introducation's first letter is 'T'
        $this->assertEqual($aRet[0]->id, 6);

        // test when there is the attribute filtering and
        // we order by one of 'filterable' attributes
        $aRet = SGL_Finder::factory('content')
            ->addFilter('typeName', 'Article')
            ->addFilter('attribute', array(
                'name'     => 'introduction',
                'operator' => '=',
                'value'    => 'foo'))
            ->addFilter('attribute', array(
                'name'     => 'body',
                'operator' => '=',
                'value'    => 'bar'))
            ->addFilter('sortBy', array('attribute' => 'introduction'))
            ->addFilter('sortOrder', 'DESC')
            ->retrieve();
        // ensure correct number of contents is returned,
        // we do not check actual filtering here
        $this->assertEqual(count($aRet), 2);

        // test again when there is the attribute filtering and
        // we order by one of 'filterable' attributes
        $aRet = SGL_Finder::factory('content')
            ->addFilter('typeName', 'Article')
            ->addFilter('attribute', array(
                'name'     => 'introduction',
                'operator' => '=',
                'value'    => 'foo'))
            ->addFilter('attribute', array(
                'name'     => 'body',
                'operator' => '=',
                'value'    => 'bar'))
            ->addFilter('sortBy', array('attribute' => 'body'))
            ->addFilter('sortOrder', 'DESC')
            ->retrieve();
        // ensure correct number of contents is returned
        // we do not check actual filtering here
        $this->assertEqual(count($aRet), 2);

        // we filter by introduction and body, but order by 'isPublished'
        $aRet = SGL_Finder::factory('content')
            ->addFilter('typeName', 'Article')
            ->addFilter('attribute', array(
                'name'     => 'introduction',
                'operator' => '=',
                'value'    => 'foo'))
            ->addFilter('attribute', array(
                'name'     => 'body',
                'operator' => '=',
                'value'    => 'bar'))
            ->addFilter('sortBy', array('attribute' => 'isPublished'))
            ->addFilter('sortOrder', 'DESC')
            ->retrieve();
        // article with id = 9 is published, but article with id = 7 is not,
        // i.e. id = 9 goes first
        $this->assertEqual($aRet[0]->id, 9);
    }

    function testFilterLimit()
    {
        // test LIMIT 2
        $aRet = SGL_Finder::factory('content')
        	->addFilter('sortBy', 'content_id')
        	->addFilter('sortOrder', 'ASC')
            ->addFilter('limit', 2)
            ->retrieve();
        // ensure correct number of contents
        $this->assertEqual(count($aRet), 2);
        // ensure last content is "Article with leader and body", content_id = 6
        $this->assertEqual($aRet[1]->id, 6);

        // test LIMIT 2
        $aRet = SGL_Finder::factory('content')
        	->addFilter('sortBy', 'content_id')
        	->addFilter('sortOrder', 'ASC')
            ->addFilter('limit', array('count' => 2))
            ->retrieve();
        // ensure correct number of contents
        $this->assertEqual(count($aRet), 2);
        // ensure last content is "Article with leader and body", content_id = 6
        $this->assertEqual($aRet[1]->id, 6);

        // test LIMIT 1, 2
        $aRet = SGL_Finder::factory('content')
        	->addFilter('sortBy', 'content_id')
        	->addFilter('sortOrder', 'ASC')
            ->addFilter('limit', array('offset' => 1, 'count' => 2))
            ->retrieve();
        // ensure correct number of contents
        $this->assertEqual(count($aRet), 2);
        // ensure first content is "Article with leader and body", content_id = 6
        $this->assertEqual($aRet[0]->id, 6);
        // ensure last content is "unpublished article", content_id = 7
        $this->assertEqual($aRet[1]->id, 7);
    }

    function testFilterCategory()
    {
        // retrieve Printers
        $aRet = SGL_Finder::factory('content')
            ->addFilter('sortBy', 'content_id')
        	->addFilter('sortOrder', 'ASC')
            ->addFilter('categoryId', 6) // printers
            ->retrieve();
        // ensure correct number of contents
        $this->assertEqual(count($aRet), 2);
        // ensure first content is "Alouicious Bird Resume", content_id = 2
        $this->assertEqual($aRet[0]->id, 2);

        // retrieve LCD
        $aRet = SGL_Finder::factory('content')
            ->addFilter('sortBy', 'content_id')
        	->addFilter('sortOrder', 'ASC')
            ->addFilter('categoryId', 15)
            ->retrieve();
        // ensure correct number of contents
        $this->assertEqual(count($aRet), 2);
        // ensure first content is "Alouicious Bird Resume", content_id = 2,
        // i.e. "Alouicious Bird Resume" is content of 2 categories
        $this->assertEqual($aRet[0]->id, 2);

        // retrieve CRT
        $aRet = SGL_Finder::factory('content')
            ->addFilter('sortBy', 'content_id')
        	->addFilter('sortOrder', 'ASC')
            ->addFilter('categoryId', 13)
            ->retrieve();
        // ensure correct number of contents
        $this->assertEqual(count($aRet), 1);
        // ensure first content is "published article", content_id = 8
        $this->assertEqual($aRet[0]->id, 8);
    }
    function testFilterAssocContent()
    {
        // test linked contents
        $aRet = SGL_Finder::factory('AssocContent')
            ->addFilter('sortBy', 'content_id')
        	->addFilter('sortOrder', 'DESC')
        	->addFilter('assocContents', array('parentId' => 2))
            ->retrieve();
        // 3 items returned, check correct order
        $this->assertEqual($aRet[0]->id, 9);
        $this->assertEqual($aRet[1]->id, 8);
        $this->assertEqual($aRet[2]->id, 7);

        // test parent contents
        $aRet = SGL_Finder::factory('AssocContent')
            ->addFilter('sortBy', 'content_id')
        	->addFilter('sortOrder', 'DESC')
        	->addFilter('assocContents', array('childId' => 8))
        	// get articles only
        	->addFilter('typeId', 1)
            ->retrieve();
        // there are 2 parent contents exits and only one of them is an article
        $this->assertEqual(count($aRet), 1);
        // check that one
        $this->assertEqual($aRet[0]->id, 9);
    }
}
?>
