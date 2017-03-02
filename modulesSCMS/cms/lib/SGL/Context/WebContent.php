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

/**
 * Packages data from a web form into an SGL_Content object.
 *
 * Expects data for an existing SGL_Content object.
 *
 * @package SGL
 * @subpackage cms
 * @category module
 */
class SGL_Context_WebContent implements SGL_CmsContextStrategy
{
    private $_aMap = array(
        'id'            => 'id',
        'version'       => 'version',
        'isCurrent'     => 'is_current',
        'langCode'      => 'language_id',
        'typeId'        => 'type_id',
        'name'          => 'name',
        'status'        => 'status',
        );

    function process($oData)
    {
        if (!is_null($oData)) {
            if (is_array($oData)) {
                $oData = (object)$oData;
            }
            //  massage data SGL_Content members
            foreach ($this->_aMap as $objAttribName => $formFieldName) {
                if (!empty($oData->$formFieldName)) {
                    $this->$objAttribName = $oData->$formFieldName;
                }
            }
            //  massage SGL_Attributes - updating existing content
            if (isset($oData->attributes) && count($oData->attributes)) {
                for ($x = 0; $x < count($oData->attributes['data']); $x ++) {
                    $oAttrib = SGL_Attribute::getById($oData->attributes['attr_id'][$x]);
                    $oAttrib->set($oData->attributes['data'][$x]);
                    $this->aAttribs[] = $oAttrib;
                }
            }
            //  massage classifiers
            if (isset($oData->aClassifiers) && count($oData->aClassifiers)) {
                foreach ($oData->aClassifiers as $classifierName => $classifierValue) {
                    $this->aClassifiers[$classifierName] = $classifierValue;
                }
            }
            //  ensure name has a default value
            if (empty($this->name)) {
                $this->name = '[please provide a name for the content]';
            }
        }
        return $this;
    }
}
?>