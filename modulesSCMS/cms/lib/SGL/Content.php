<?php

/**
 * Enter description here...
 *
 * @package SGL
 * @subpackage cms
 */

/**
 * Requires
 */
require_once SGL_MOD_DIR . '/cms/classes/CmsDAO.php';
require_once SGL_MOD_DIR . '/cms/init.php';

/**
 * @package SGL
 * @subpackage cms
 * @category module
 * @author  Demian Turner <demian@phpkitchen.com>
 */
class SGL_Content
{
    public $id;
    public $version;
    public $isCurrent;
    public $langCode;
    public $name;
    public $createdByName;
    public $createdById;
    public $updatedById;
    public $dateCreated;
    public $lastUpdated;
    public $typeId;
    public $typeName;
    public $status;
    public $numLinks;
    public $aAttribs = array();
    public $aClassifiers = array();
    public $media;
    private static $_aMap = array(
        'id' => array('content_id', 'id'),
        'version' => array('version', 'version'),
        'isCurrent' => array('is_current', 'isCurrent'),
        'langCode' => array('language_id', 'langCode'),
        'name' => array('content_name', 'name'),
        'createdByName' => array('username', 'createdByName'),
        'createdById' => array('created_by_id', 'createdById'),
        'updatedById' => array('updated_by_id', 'updatedById'),
        'dateCreated' => array('date_created', 'dateCreated'),
        'lastUpdated' => array('last_updated', 'lastUpdated'),
        'status' => array('status', 'status'),
        'typeId' => array('content_type_id', 'typeId'),
        'typeName' => array('content_type_name', 'typeName'),
        );

    /**
     * Consructor.
     *
     * Populates the object if $oData is supplied.  Here's an example
     * using data from a web form:
     *
     * <code>
     *   $oContent = new SGL_Content($oContext->process());
     * </code>
     *
     * @param object $oData
     * @return SGL_Content
     */
    function __construct($oData = null, $loadAttribs = true)
    {
        if (!is_null($oData)) {
            if (is_array($oData)) {
                $oData = (object)$oData;
            }
            foreach (self::$_aMap as $objAttribName => $dbFieldName) {
                if (is_array($dbFieldName)) {
                    foreach ($dbFieldName as $alias) {
                        if (!empty($oData->$alias)) {
                            $this->$objAttribName = $oData->$alias;
                        }
                    }
                } else {
                    if (!empty($oData->$dbFieldName)) {
                        $this->$objAttribName = $oData->$dbFieldName;
                    }
                }
            }
            if ($loadAttribs && isset($oData->aAttribs)) {
                $this->aAttribs = $oData->aAttribs;
            }
            if (isset($oData->aClassifiers)) {
                $this->aClassifiers = $oData->aClassifiers;
            }
        }
    }

    /**
     * Sets value property for searched SGL_Attribute.
     *
     * @param string $propName
     * @param string $propValue
     * @return boolean
     */
    function __set($propName, $propValue)
    {
        foreach ($this->aAttribs as $k => $oAttribute) {
            if ($oAttribute->name == $propName) {
                $this->aAttribs[$k]->value = $propValue;
                return true;
            }
        }
    }

    /**
     * Returns value property for searched SGL_Attribute.
     *
     * @param string $propName
     * @return string
     */
    function __get($propName)
    {
        foreach ($this->aAttribs as $k => $oAttribute) {
            if ($oAttribute->name == $propName) {
                return $this->aAttribs[$k]->value;
            }
        }
    }

    /**
     * Returns ID for a given attrib name.
     *
     * <code>
     *   $oRestoReview  = SGL_Content::getById($id);
     *   $id = $oRestoReview->getAttribId('dishName');
     * </code>
     *
     * @param string $attribName
     * @return integer
     */
    public function getAttribId($attribName)
    {
        foreach ($this->aAttribs as $k => $oAttribute) {
            if ($oAttribute->name == $attribName) {
                return $this->aAttribs[$k]->id;
            }
        }
    }
    /**
     * Returns a populated content object by id at current version
     *
     * <code>
     *   $oRestoReview  = SGL_Content::getById($id);
     * </code>
     *
     * @param integer $id
     * @return SGL_Content
     */
    public static function getById($id, $langCode = null, $version = null)
    {
        if (is_null($langCode)) {
            $langCode = SGL_Translation3::getDefaultLangCode();
        }
        $_da = CmsDAO::singleton();
        //  get meta info
        $oData = $_da->getContentById($id, $langCode, $version);
        if (PEAR::isError($oData)) {
            return $oData;
        } elseif (empty($oData)) {
            return SGL::raiseError('No content found');
        }
        $oContent = new SGL_Content($oData);

        //  get attributes
        $aRows = $_da->getAttribDataByContentId($oContent->id,
                                                $oContent->version,
                                                $oContent->langCode);
        if (PEAR::isError($aRows)) {
            return $aRows;
        }
        foreach ($aRows as $obj) {
            $oAttrib = new SGL_Attribute($obj);
            $oContent->aAttribs[] = $oAttrib;
        }

        //  get classifiers
        //  category classifier
        $aCats = $_da->getCategoriesByContentId($oContent->id);
        if (count($aCats)) {
            $oContent->aClassifiers['categories'] = $aCats;
        }

        //  tags classifier
        if (SGL::moduleIsEnabled('tag')) {
            $aTags = $_da->getTagsByContentId($oContent->id,$oContent->getType());
            if (is_array($aTags) && count($aTags)) {
                $oContent->aClassifiers['tags'] = implode(', ',$aTags);
            }
        }
        return $oContent;
    }

    /**
     *
     * Ensures all attributes of a content item are loaded when editing the content item.
     * This is useful when a content type has been modified and extra attributes have been added.
     *
     * @return      SGL_Content
     * @todo contains hard-coded content type ID
     * @todo remove, never called
     */
    public function ensureAllAttribsLoaded()
    {
        $da         = CmsDAO::singleton();
        $aAttribs   = $da->getDefaultAttribsByContentTypeId(3);
        foreach ($aAttribs as $oDefaultAttrib) {
            if (!($exists = (boolean) $this->getAttribId($oDefaultAttrib->name))) {
                $oAttrib    = new SGL_Attribute($oDefaultAttrib);
                $this->aAttribs[] = $oAttrib;
            }
        }
        return $this;
    }

    /**
     * Returns a content object matching supplied name at current version.
     *
     * <code>
     *   $oRestoReview  = SGL_Content::getByName('review of marcos spaghetti');
     * </code>
     *
     * @param string $name
     * @return SGL_Content
     * @todo ensure uniqueness of name property
     */
    public static function getByName($name)
    {
        $_da = CmsDAO::singleton();
        //  get meta info
        $oData = $_da->getContentByName($name);
        if (PEAR::isError($oData)) {
            return $oData;
        }
        $oContent = new SGL_Content($oData);

        //  get attributes
        $aRows = $_da->getAttribDataByContentId($oContent->id, $oContent->version,
            $oContent->langCode);
        if (PEAR::isError($aRows)) {
            return $aRows;
        }
        foreach ($aRows as $obj) {
            $oAttrib = new SGL_Attribute($obj);
            $oContent->aAttribs[] = $oAttrib;
        }
        return $oContent;
    }

    /**
     * Returns a content object structure with no data, according to
     * type name or ID passed.
     *
     * <code>
     *   $oRestoReview = SGL_Content::getByType('RestaurantReview');
     *   $oRestoReview->rename('Foo');
     * </code>
     *
     *
     * @param mixed $type  An integer or string referencing a content type
     * @return SGL_Content
     */
    public static function getByType($type)
    {
        $_da = CmsDAO::singleton();
        if (!is_numeric($type)) {
            $typeId = $_da->getContentTypeIdByName($type);
            if (PEAR::isError($typeId)) {
                return $typeId;
            }
        } else {
            $typeId = $type;
        }
        $aRows = $_da->getContentTypeAttribsById($typeId);
        $aRet = $_da->remapDataRowsToContentObjects($aRows);
        $oContent = current($aRet);
        return $oContent;
    }

    /**
     * Returns an array of content objects associated with current content object.
     *
     * Alternatively associated contents can be constrained by type.
     *
     * <code>
     *   $oRestoReview  = SGL_Content::getById($id);
     *   // get all associated comments
     *   $aComments = $oRestoReview->getLinkedContents(SGL_CMS_CONTENTTYPE_REVIEW_COMMENTS);
     * </code>
     *
     * @param integer $typeId
     * @return array
     *
     * @deprecated  use SGL_Finder::factory('assocContent') instead
     *
     */
    public function getLinkedContents($typeId = null)
    {
        $aContents = array();
        if (empty($this->id)) {
            return $aContents;
        }
        $_da = CmsDAO::singleton();
        $aRet = $_da->getContentAssocsByContentId($this->id, $typeId);
        foreach ($aRet as $contentId) {
            $aContents[] = SGL_Content::getById($contentId);
        }
        return $aContents;
    }

    public function hasLinks()
    {
        return (boolean) $this->getLinkCount();
    }

    public function getLinkCount()
    {
        $count = CmsDAO::singleton()->contentHasAssocs($this->id);
        return $count;
    }

    /**
     * Returns an integer representing the type id of the content object.
     *
     * <code>
     *   $oRestoReview  = SGL_Content::getById($id);
     *   $type = $oRestoReview->getType();
     *   if ($type != SGL_CMS_CONTENTTYPE_RESTAURANT_REVIEW) { // do something ...}
     * </code>
     *
     * @return integer
     */
    public function getType()
    {
        return $this->typeId;
    }

    /**
     * Returns the status of a content object.
     *
     * <code>
     *   $oRestoReview  = SGL_Content::getById($id);
     *   $status = $oRestoReview->getStatus();
     * </code>
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Sets the status of a content object.
     *
     * <code>
     *   $oRestoReview  = SGL_Content::getById($id);
     *   $oRestoReview->setStatus(SGL_CMS_STATUS_PUBLISHED);
     * </code>
     *
     * @param integer $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Creates a new content type.
     *
     * <code>
     *   $oRestoReview  = SGL_Content::createType('RestaurantReview')
     *       ->addAttribute(new SGL_Attribute(
     *           array('name' => 'dateEaten', 'typeId' => SGL_CONTENT_ATTR_TYPE_DATE)))
     *       ->addAttribute(new SGL_Attribute(
     *           array('name' => 'dishName', 'typeId' => SGL_CONTENT_ATTR_TYPE_TEXT)))
     *       ->addAttribute(new SGL_Attribute(
     *           array('name' => 'overallRating', 'typeId' => SGL_CONTENT_ATTR_TYPE_FLOAT)))
     *       ->save();
     * </code>
     *
     * @param string $name
     * @return SGL_Content
     *
     */
    public static function createType($name)
    {
        $_da = CmsDAO::singleton();
        $id = $_da->addContentType($name);
        if (PEAR::isError($id)) {
            return $id;
        }
        $aData = array(
            'content_type_id' => $id,
            'content_type_name' => $name);
        $oContent = new SGL_Content($aData);
        return $oContent;
    }

    /**
     * Adds an attribute object to a SGL_Content object instance.
     *
     * <code>
     *   $oRestoReview  = SGL_Content::createType('RestaurantReview')
     *       ->addAttribute(new SGL_Attribute(
     *           array('name' => 'dateEaten', 'typeId' => SGL_CONTENT_ATTR_TYPE_DATE))
     *       );
     * </code>
     *
     * @param SGL_Attribute $oAttrib
     * @return SGL_Content
     */
    public function addAttribute(SGL_Attribute $oAttrib)
    {
        if (is_a($oAttrib, 'SGL_Attribute')) {
            $this->aAttribs[] = $oAttrib;
        }
        return $this;
    }

    /**
     * Returns false if any attribute names are empty, or duplicates are found.
     *
     * @return boolean
     *
     */
    public function validate()
    {
        //  validate attribute names
        $aTest = array();
        foreach ($this->aAttribs as $k => $oAttrib) {
            if (empty($oAttrib->name)) {
                return false;
            }
            $aTest[$k] = $oAttrib->name;
        }
        $aKeys = array_keys($aTest);
        $aValues = array_values($aTest);
        return count($aKeys) == count(array_unique($aValues));
    }

    /**
     * Saves a content object and returns the object with PK.
     *
     * @return SGL_Content
     * @see SGL_Content::createType();
     */
    public function save($newVersion = false, $newLang = false)
    {
        $_da = CmsDAO::singleton();
        if (is_null($this->getStatus())) {
            $this->setStatus(SGL_CMS_STATUS_FOR_APPROVAL);
        }
        $oSavedContent = $_da->saveContent($this, $newVersion, $newLang);
        return $oSavedContent;
    }

    /**
     * Renames a SGL_Content item.
     *
     * <code>
     *   $oRestoReview  = SGL_Content::getByType('RestaurantReview');
     *   $oRestoReview->rename('Foo');
     * </code>
     *
     * @param string $newName
     * @return boolean
     */
    public function rename($newName)
    {
        $_da = CmsDAO::singleton();
        if (!empty($this->id)) {
            // We have a content instance, updating its name
            $this->name = $newName;
        } else {
            // We have a content type instance, updating its typeName
            $this->typeName = $newName;
        }
        $ok = $this->save();
        return $ok;
    }

    /**
     * Deletes an instance of content.
     *
     * <code>
     *   $oRestoReview  = SGL_Content::getById($id);
     *   $oRestoReview->delete();
     * </code>
     *
     * @param boolean $safe
     */
    public function delete($safe = true)
    {
        $_da = CmsDAO::singleton();
        $ok = $_da->deleteContentById(array($this->id), $safe);
        return $ok;
    }
}
?>
