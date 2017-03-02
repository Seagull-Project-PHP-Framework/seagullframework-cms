<?php

/**
 * Content finder.
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
class SGL_Finder_Content extends SGL_Finder
{
    protected $_aMedia = array();

    public function __construct()
    {
        $this->da = CmsDAO::singleton();
    }

    public function addFilter($filterName, $filterValue)
    {
        if ($filterName == 'mediaInfo' && is_array($filterValue)
                && array_key_exists('name', $filterValue)) {

            $this->_aMedia[] = $filterValue['name'];
            return $this;
        } else {
            return parent::addFilter($filterName, $filterValue);
        }
    }

    public function getMediaFilters()
    {
        return $this->_aMedia;
    }

    protected function _buildSearchQuery()
    {
        // prepare constraints
        $constraintContent  = $this->_prepareContentConstraint();
        $constraintOrdering = $this->_prepareOrdering();
        $constraintAttrib   = $this->_prepareAttribConstraint();
        $constraintLimit    = $this->_prepareLimit();
        $constraintCatId    = $this->_prepareCategoryConstraint();

        $this->query = $this->da->buildSearchQuery(
            $constraintContent,
            $constraintAttrib,
            $constraintOrdering,
            $constraintLimit,
            $constraintCatId
        );
    }

    public function getSql()
    {
        $this->_buildSearchQuery();
        return $this->query;
    }

    public function executeSql($sql)
    {
        $aData = $this->da->getMatchingContents($sql);
        return $aData;
    }

    public function retrieve()
    {
        $this->_buildSearchQuery();
        $aData = $this->da->getMatchingContents($this->query);

        return $this->_decorateContents($this->_createContents($aData));
    }

    /**
     * Returns SGL_Content collection.
     *
     * @param  array $aData
     * @return SGL_Content collection
     */
    protected function _createContents($aData)
    {
        $aRet = array();
        foreach ($aData as $obj) {
            //  get meta info
            $oContent = new SGL_Content($obj);

            //  get attribute data
            $aRows = $this->da->getAttribDataByContentId($obj->content_id, $obj->version, $obj->language_id);
            foreach ($aRows as $oRow) {
                $oAttrib = new SGL_Attribute($oRow);
                $oContent->aAttribs[] = $oAttrib;
            }
            $aRet[] = $oContent;
        }
        return $aRet;
    }

    /**
     * Adds media attribute to SGL_Content object
     *
     * @param array $aContent
     * @return array
     */
    protected function _decorateContents($aContent)
    {
        if (empty($aContent)) {
            return $aContent;
        }

        // decorate with media
        $aMediaFilters = $this->getMediaFilters();
        if (!empty($aMediaFilters)) {
            require_once SGL_MOD_DIR . '/media2/classes/Media2DAO.php';
            $_media2Dao = Media2DAO::singleton();

            foreach ($aContent as $key => $oContent) {
                $oContent->media = new stdClass();
                foreach ($aMediaFilters as $mediaField) {
                    if (is_numeric($oContent->$mediaField)) {
                        $oContent->media->$mediaField = $_media2Dao->getMediaById($oContent->$mediaField);
                    }
                }
            }
        }
        return $aContent;
    }
}

?>
