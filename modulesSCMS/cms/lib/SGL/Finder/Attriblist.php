<?php

require_once SGL_MOD_DIR . '/cms/classes/CmsDAO.php';

/**
 * Finder's attribute list driver.
 *
 * @package SGL
 * @subpackage cms
 * @author Dmitri Lakachauskis <lakiboy83@gmail.com>
 */
class SGL_Finder_Attriblist extends SGL_Finder
{
    public function retrieve()
    {
        $aLists = CmsDAO::singleton()->getAttributeLists();
        return $this->_remapDataRowsToListObjects($aLists);
    }

    protected function _remapDataRowsToListObjects($aData)
    {
        $aRet = array();
        foreach ($aData as $k => $oList) {
            $aFields = $this->_getDataFromParamString($oList->params);
            $aList   = array(
                'id'     => $oList->attribute_list_id,
                'name'   => $oList->name,
                'fields' => $aFields,
                'total'  => count($aFields)
            );
            $aRet[$k] = (object) $aList;
        }
        return $aRet;
    }

    protected function _getDataFromParamString($paramString)
    {
        $fields = unserialize($paramString);
        // if serialized data are corrupted null is returned
        if (empty($fields) && !is_array($fields)) {
            $fields = array();
        }
        $data = array();
        if (isset($fields['data-inline'])) {
            $data = $fields['data-inline'];
        } elseif (isset($fields['data'])) {
            $data = $fields['data'];
        }
        return $data;
    }
}
?>