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
 * Expects data for a new SGL_Content type.
 *
 * @package SGL
 * @subpackage cms
 * @category module
 */
class SGL_Context_WebContentType implements SGL_CmsContextStrategy
{
    private $_aMap = array(
        'typeId' => 'id',
        'typeName' => 'name',
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
            //  create SGL_Attributes
            if (isset($oData->attributes) && count($oData->attributes)) {
                foreach ($oData->attributes as $k => $aAttrib) {
                    $aData = array(
                        'name' => $aAttrib['fieldName'],
                        'alias' => $aAttrib['fieldAlias'],
                        'typeId' => $aAttrib['fieldType'],
                        'params' => (!empty($aAttrib['fieldParams']))
                            ? $aAttrib['fieldParams']
                            : '',
                    );
                    //  if we're updating, we can get attribute id
                    if (isset($aAttrib['fieldId'])) {
                        $aData['id'] = $aAttrib['fieldId'];
                    }
                    $oAttrib = new SGL_Attribute($aData);
                    $this->aAttribs[] = $oAttrib;
                }
            }
        }
        return $this;
    }
}
?>