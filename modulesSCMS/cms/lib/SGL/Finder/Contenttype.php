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

/**
 * Enter description here...
 *
 * @package SGL
 * @subpackage cms
 * @category module
 */
class SGL_Finder_Contenttype extends SGL_Finder
{

    public function retrieve()
    {
        $_da = CmsDAO::singleton();
        $aData = $_da->getContentTypeAttribsById();
        $aContent = $_da->remapDataRowsToContentObjects($aData);
        return $aContent;
    }
}

?>