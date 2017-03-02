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
require_once SGL_MOD_DIR . '/tag/classes/TagDAO.php';

/**
 * Finds tags (suggest)
 *
 * @package SGL
 * @subpackage cms
 * @category module
 */
class SGL_Finder_Tag extends SGL_Finder
{
    public function __construct()
    {
        $this->da = TagDAO::singleton();
    }

    public function addFilter($filterName, $filterValue)
    {
        $this->_aFilters[$filterName] = $filterValue;
        return $this;
    }
    
    public function retrieve()
    {
        $aContentId = $this->getContentIdPool();        
        return $this->getTags($aContentId);
    }

    /**
     * Returns an array of tags for a given array of Content IDs.
     *
     * @param array $aContentId
     * @return array
     */
    public function getTags($aContentId)
    {
        $aFilters = $this->getFilters();
        
        if (array_key_exists('excludeContentId', $aFilters)
            && is_array($aFilters['excludeContentId'])) {
            $aContentId = array_diff($aContentId, $aFilters['excludeContentId']);
        }
        
        $aTags = $this->da->getTagsByTaggableFkId($aContentId,'cms_'.$aFilters['typeId']);
        
        if (array_key_exists('excludeTags', $aFilters)
            && is_array($aFilters['excludeTags'])) {
            $aTags = array_diff($aTags, $aFilters['excludeTags']);
        }        
        return $aTags;
    }

    /**
     * Returns an array of Content IDs for the current category and type.
     * 
     * @return array
     */
    public function getContentIdPool()
    {
        $aFilters = $this->getFilters();
        
        $oContentFinder   = SGL_Finder::factory('content');
        
        if (array_key_exists('categoryId', $aFilters)) {
            $oContentFinder->addFilter('categoryId', $aFilters['categoryId']);
        }
        if (array_key_exists('typeId', $aFilters)) {
            $oContentFinder->addFilter('typeId', $aFilters['typeId']);
        }
        
        $aContent = $oContentFinder->retrieve();

        $aContentId = array();
        foreach ($aContent as $oContent) {
            $aContentId[] = $oContent->id;
        }        
        return $aContentId;
    }    
}

?>