<?php

/**
 * Associated contents filter.
 *
 * @package SGL
 * @subpackage cms
 */

/**
 * Associated contents filter.
 *
 * @package    SGL
 * @subpackage cms
 * @category module
 * @author     Dmitri Lakachauskis <lakiboy83@gmail.com>
 */
class SGL_Finder_AssocContent extends SGL_Finder_Content
{
    private $_constraintAssoc = array();

    function _buildSearchQuery()
    {
        // prepare constraints
        $constraintContent  = $this->_prepareContentConstraint();
        $constraintOrdering = $this->_prepareOrdering();
        $constraintAttrib   = $this->_prepareAttribConstraint();
        $constraintLimit    = $this->_prepareLimit();
        $constraintCatId    = $this->_prepareCategoryConstraint();
        $constraintAssoc    = $this->_prepareAssocConstraint();

        $this->query = $this->da->buildSearchQuery(
            $constraintContent,
            $constraintAttrib,
            $constraintOrdering,
            $constraintLimit,
            $constraintCatId,
            $constraintAssoc
        );
    }

    public function retrieve()
    {
        $this->_buildSearchQuery();
        $aData = $this->da->getMatchingContents($this->query);

        return $this->_decorateContents($this->_createContents($aData));
    }

    /**
     * Adds a filter to SGL_Finder to constrain results.
     *
     * <code>
     * // 1) Getting contents which are linked to content with content_id = ID.
     * $aContents = SGL_Finder::factory('AssocContent')
     *     ->addFilter('assocContents', array('parentId' => ID))
     *     ->retrieve();
     *
     * // 2) Getting contents which are parents to content with content_id = ID.
     * $aContents = SGL_Finder::factory('AssosContent')
     *     ->addFilter('assocContents', array('childId' => ID))
     *     ->retrieve();
     * </code>
     *
     * @param string $filterName
     * @param mixed $filterValue
     */
    public function addFilter($filterName, $filterValue)
    {
        if ($filterName != 'assocContents') {
            return parent::addFilter($filterName, $filterValue);
        }
        $errorText = '';
        if (!is_array($filterValue)) {
            $errorText = 'array expected as filter value';
        } elseif (!empty($this->_constraintAssoc)) {
            $errorText = 'assocContents filter already defined';
        } elseif (!array_key_exists('parentId', $filterValue)
                && !array_key_exists('childId', $filterValue)) {
            $errorText = 'parentId or childId expected';
        }
        if (empty($errorText)) {
            $this->_constraintAssoc = $filterValue;
        } else {
            // do not return to keep chainability
            SGL::raiseError(__METHOD__ . ': ' . $errorText);
        }
        return $this;
    }

    protected function _prepareAssocConstraint()
    {
        $ret = '';
        if (!empty($this->_constraintAssoc)) {
            $tableName = $this->da->conf['table']['content-content'];
            $tableName = $this->da->dbh->quoteIdentifier($tableName);
            $ret .= " INNER JOIN $tableName AS cc ON";
            if (array_key_exists('parentId', $this->_constraintAssoc)) {
                $key  = 'parentId';
                $ret .= " cc.content_id_fk = c.content_id AND cc.content_id_pk = ";
            } else {
                $key  = 'childId';
                $ret .= " cc.content_id_pk = c.content_id AND cc.content_id_fk = ";
            }
            $ret .= intval($this->_constraintAssoc[$key]);
            $ret .= " ";
        }
        return $ret;
    }
}
?>
