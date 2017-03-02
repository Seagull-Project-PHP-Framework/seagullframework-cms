<?php

/**
 * The Attribute class
 *
 * @author Demian Turner <demian@phpkitchen.com>
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
class SGL_Attribute
{
    public $id;
    public $typeId;
    public $contentId;
    public $version;
    public $langCode;
    public $typeName;
    public $contentTypeId;
    public $name;
    public $alias;
    public $desc;
    public $params;
    public $value;
    private static $_aMap = array(
        'id' => array('attribute_id', 'id'),
        'name' => 'name',
        'typeId' => array('attribute_type_id', 'typeId'),
        'contentId' => array('content_id', 'contentId'),
        'version' => 'version',
        'langCode' => array('language_id', 'langCode'),
        'typeName' => array('attribute_type_name', 'typeName'),
        'contentTypeId' => array('content_type_id', 'contentTypeId'),
        'alias' => 'alias',
        'desc' => 'desc',
        'value' => 'value',
        'params' => 'params',
        );

    /**
     * Constructor.
     *
     * Populates the object if $oData is supplied.
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
     * @param object $oData
     * @return SGL_Attribute
     */
    public function __construct($oData = null)
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
            if (empty($this->alias)) {
                $this->alias = ucfirst($this->name);
            }
            if (!empty($this->params) && is_string($this->params)) {
                $this->params = unserialize($this->params);
            }
        }
    }

    /**
     * Sets value property for SGL_Attribute.
     *
     * @param string $value
     */
    public function set($value)
    {
        $this->value = $value;
        return true;
    }

    /**
     * Returns value property of SGL_Attribute.
     *
     * @return string
     */
    public function get()
    {
        if (isset($this->value)) {
            $ret = $this->value;
        } else {
            $ret = null;
        }
        return $ret;
    }

    /**
     * Returns an attribute object referenced by supplied id.
     *
     * <code>
     *   $bar = SGL_Attribute::getById($id); // never by name, too ambiguous
     *   $bar->rename('fluux');
     *   $bar->delete();
     * </code>
     *
     * @param integer $id
     * @return SGL_Attribute
     */
    public static function getById($id)
    {
        $_da = CmsDAO::singleton();
        $oData = $_da->getAttribById($id);
        $oAttribute = new SGL_Attribute($oData);
        return $oAttribute;
    }

    /**
     * Returns an integer representing the type id of the attribute.
     *
     * Types are represented by constants.
     *
     * <code>
     *   $attrib = SGL_Attribute::getById($id);
     *   $type = $attrib->getType();
     * </code>
     *
     * @return integer
     * @see CmsDAO::_initialiseAttribTypeConstants()
     */
    public function getType()
    {
        return $this->typeId;
    }

    /**
     * Modifies the attributes type.
     *
     * <code>
     *   // changing an attribute's type
     *   $bar = SGL_Attribute::getById($id);
     *   $bar->changeType(SGL_ATTRTYPE_FLOAT);
     *
     *   // changing an attribute's value
     *   $bar = SGL_Attribute::getById($id);
     *   $bar->value = 'new value';
     *   $oAttrib = $bar->save();
     * </code>
     *
     * @param integer $newTypeId
     * @return mixed  true of PEAR error
     */
    public function changeType($newTypeId)
    {
        $_da = CmsDAO::singleton();
        $this->typeId = $newTypeId;
        $ok = $_da->updateAttrib($this);
        return $ok;
    }

    /**
     * Changes the name property of SGL_Attribute.
     *
     * <code>
     *   $bar = SGL_Attribute::getById($id);
     *   $bar->rename('fluux');
     * </code>
     *
     * @param string $newName
     * @return mixed  true of PEAR error
     */
    public function rename($newName)
    {
        $_da = CmsDAO::singleton();
        $this->name = $newName;
        $ok = $_da->updateAttrib($this);
        return $ok;
    }

    /**
     * Deletes the current attribute instance.
     *
     * <code>
     *   $attr = SGL_Attribute::getById($id);
     *   $attr->delete();
     * </code>
     *
     * @return boolean
     */
    public function delete()
    {
        $_da = CmsDAO::singleton();
        $ok = $_da->deleteAttribById($this->id);
        return $ok;
    }


    /**
     * Saves the current attribute instance.
     *
     * <code>
     *   $bar = SGL_Attribute::getById($id);
     *   $bar->value = 'new value';
     *   $oAttrib = $bar->save();
     * </code>
     *
     * @return SGL_Attribute
     */
    public function save()
    {
        $this->_serializeParams();
        $_da = CmsDAO::singleton();
        $ok = $_da->updateAttrib($this);
        return $this;
    }


    /**
     * Serializes attribute params for storage.
     *
     */
    protected function _serializeParams()
    {
        if (!empty($this->params) && is_array($this->params)) {
            $this->params = serialize($this->params);
        }
    }

    /**
     * Returns attribute list params.
     *
     * <code>
     *   $attr = SGL_Attribute::getById($id);
     *   if ($attr->getType() == SGL_CONTENT_ATTR_TYPE_RADIO) {
     *       $aParams = $attr->getParams();
     *   }
     * </code>
     *
     * @return array
     */
    public function getParams()
    {
        $aData = array();
        $attributeListId = (isset($this->params['attributeListId']))
            ? $this->params['attributeListId']
            : 0;

        if ($attributeListId) {
            $_da = CmsDAO::singleton();
            $attributeList      = $_da->getAttribListParamsByListId($attributeListId);
            $aAttributeParams   = unserialize($attributeList);
            $aData              = $this->_getParams($aAttributeParams);
        }
        return $aData;
    }

    /**
     * Enter description here...
     *
     * @param array $aAttributeParams
     * @return array
     */
    protected function _getParams($aAttributeParams)
    {
        $aData = array();
        if (isset($aAttributeParams['data-inline'])) {
            $aData = $aAttributeParams['data-inline'];
        } elseif (isset($aAttributeParams['data-db'])) {
            $aData = CmsDAO::singleton()->getAttribListDbData($aAttributeParams['data-db']);
        } elseif (isset($aAttributeParams['data-file'])) {
            if (file_exists($file = SGL_PATH . $aAttributeParams['data-file']['location'])) {
                include $file;
            }
        } else {
            $aData = $aAttributeParams['data'];
        }
        return $aData;
    }
}
?>