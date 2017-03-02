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
 * Interface for CMS contexts.
 *
 * @package SGL
 * @subpackage cms
 */
interface SGL_CmsFindable
{
    public function addFilter($filterName, $filterValue);
    public function retrieve();
}

/**
 * A factory object that allows building a finder for various framework resources,
 * eg: files, content, etc.
 *
 * Example to get content of a type and by a certain owner
 *
 * <code>
 * $aReviews = SGL_Finder::factory('content')
 *   ->addFilter('type', 'RestaurantReview')
 *   ->addFilter('createdBy', SGL_Session::getUid())
 *   ->retrieve();
* </code>
 *
 *
 * Example to get files within a date range and of a certain extension
 *
 * <code>
 * $aFiles = SGL_Finder::factory('file')
 *   ->addFilter('directory', '/foo/bar/baz')
 *   ->addFilter('extension', 'php')
 *   ->addFilter('startDate', '2006/05/11')
 *   ->addFilter('endDate', '2006/11/11')
 *   ->retrieve();
 * </code>
 *
 * @package SGL
 * @subpackage cms
 * @category module
 */
class SGL_Finder implements SGL_CmsFindable
{
    protected $_aFilters = array();
    protected $_aAttributes = array();
    protected $_aTypeIds = array();
    protected $_paginated = false;

    /**
     * Used to create concrete Finder implementations.
     *
     * @param string $handler
     * @return SGL_Filter
     */
    public static function factory($handler)
    {
        $handler = ucfirst(strtolower($handler));
        $class = 'SGL_Finder_' . $handler;
        if (class_exists($class)) {
            $obj = new $class();
            return $obj;
        }
        $null = null;
        return $null;
    }

    /**
     * Used to add filters and build dynamic Finder objects.
     *
     * @param string $filterName
     * @param string $filterValue
     * @return SGL_Finder
     */
    public function addFilter($filterName, $filterValue)
    {
        // zero means all content types
        if ($filterName == 'typeId' && !empty($filterValue)) {
            $this->_aTypeIds[] = (int)$filterValue;
        } elseif ($filterName == 'attribute') {
            $this->_aAttributes[] = $filterValue;
        } else {
            $this->_aFilters[$filterName] = $filterValue;
        }
        return $this;
    }

    /**
     * Returns the array collection of content objects.
     *
     * @return array();
     */
    public function retrieve()
    {
        return;
    }

    /**
     *  Returns an array of the Finder's filters.
     *
     */
    public function getFilters()
    {
        return $this->_aFilters;
    }

    /**
     * Returns an array of attribute filters.
     *
     * @return array
     */
    public function getAttributeFilters()
    {
        return $this->_aAttributes;
    }

    /**
     * Prepare constraint to match against attribute props
     *
     * Valid keys from $this->_aFilters:
     *  typeId
     *  createdBy
     *  status
     *  sortBy
     *  sortOrder
     *
     * @return string sql query or empty string
     * $ret = "WHERE c.content_type_id = 1";
     */
    protected function _prepareContentConstraint()
    {
        $aConstraint = array();
        $ret = "";
        $aParams = $this->getFilters();
        //  convert a type name to ID
        if (isset($aParams['typeName'])) {
            $typeId = $this->da->getContentTypeIdByName($aParams['typeName']);
            unset($aParams['typeName']);
            $this->_aTypeIds[] = (!empty($typeId))
                ? $typeId
                : -1; // -1 means wrong typeName was passed
        }
        // TypeId
        if (!empty($this->_aTypeIds)) {
            // if typeName is wrong do not select anything, even if we have
            // multiple typeId filters
            if (in_array(-1, $this->_aTypeIds)) {
                $aConstraint[] = 'c.content_type_id = -1';
            } elseif (count($this->_aTypeIds) == 1) {
                $aConstraint[] = 'c.content_type_id = ' . $this->_aTypeIds[0];
            } else {
                $aConstraint[] = 'c.content_type_id IN (' .
                    implode(',', $this->_aTypeIds) . ')';
            }
        }
        // Language
        if (empty($aParams['lang'])) {
            $aParams['lang'] = SGL_Translation3::getDefaultLangCode();
        }
        $aConstraint[] = "c.language_id = '" . addslashes($aParams['lang']) . "'";
        // Owner
        if (isset($aParams['createdBy']) && !is_null($aParams['createdBy'])) {
            $aConstraint[] = "c.created_by_id = " . $aParams['createdBy'];
        }
        // Status
        if (isset($aParams['status']) && !is_null($aParams['status'])) {
            if (is_scalar($aParams['status'])) {
                $aConstraint[] = "c.status = " . $aParams['status'];
            } elseif (is_array($aParams['status'])) {
                $operator   = $aParams['status']['operator'];
                $value      = $aParams['status']['value'];
                $aConstraint[] = "c.status " . $operator . " " . addslashes($value);
            }
        }

        if (isset($aParams['getMultiple']) && is_array($aParams['getMultiple'])) {
            foreach ($aParams['getMultiple'] as $fieldName => $aValues) {
                if (is_array($aValues) && !empty($aValues)) {
                    $aConstraint[] = "c.{$fieldName} IN (" . implode(',',$aValues) . ")";
                }
            }
        }

        // limit content name by constraint
        // @todo restrict value for at least 3 chars for LIKE operator
        if (!empty($aParams['nameSearch']) && is_array($aParams['nameSearch'])
            // we need operator to be specified
            && !empty($aParams['nameSearch']['operator'])
            // we need value to be set
            && isset($aParams['nameSearch']['value']))
        {
            $v = SGL_DB::singleton()->escapeSimple($aParams['nameSearch']['value']);
            if (strtolower($aParams['nameSearch']['operator']) == 'like') {
                $v = "%$v%";
            }
            $aConstraint[] = 'c.name ' . $aParams['nameSearch']['operator']
                . ' \'' . $v . '\'';
        }

        if (empty($this->_aAttributes) && isset($aParams['search'])) {
            $aConstraint[] = "ad.value LIKE '%" . $this->da->dbh->escapeSimple($aParams['search'])  . "%'";
        }

        // Version - by default we check for current version
        $aConstraint[] = "c.is_current = 1";
        // Ordering
//        if (!empty($aParams['sortBy']) && !empty($aParams['sortOrder'])) {
//            $orderConstraint = "ORDER BY {$aParams['sortBy']} {$aParams['sortOrder']}";
//        }

        //  date support
        if (isset($aParams['dateCreated']) && !is_null($aParams['dateCreated'])) {
            $operator   = $aParams['dateCreated']['operator'];
            $value      = $aParams['dateCreated']['value'];
            $aConstraint[] = "c.date_created " . $operator . " " . $this->da->dbh->quoteSmart($value);
        }
        if (isset($aParams['lastUpdated']) && !is_null($aParams['lastUpdated'])) {
            $operator   = $aParams['lastUpdated']['operator'];
            $value      = $aParams['lastUpdated']['value'];
            $aConstraint[] = "c.last_updated " . $operator . " " . $this->da->dbh->quoteSmart($value);
        }

        foreach ($aConstraint as $clause) {
            $join = (empty($ret)) ? "WHERE " : " AND ";
            $ret .= $join . $clause ."\n";
        }
        // Remember we have a contentConstaint
        $this->contentConstraint = (!empty($ret))
            ? true
            : false;
        return $ret;
    }

    /**
     * Prepare constraint to match against attribute props.
     *
     * Valid keys from $this->_aAttributes:
     *  name
     *  operator
     *  value
     *
     * @return string sql query or empty string
     * $ret = "
     *    AND at.name = 'body'
     *    AND ad.value = 'And this is some richtext'";
     * @todo clean up quoting
     */
    protected function _prepareAttribConstraint()
    {
        if (!count($this->_aAttributes)) {
            return null;
        }
        $aConstraint = array(
            'AND' => array(),
            'OR'  => array()
        );
        $i     = '';
        $ret   = '';
        $where = (!empty($this->contentConstraint)) ? "AND " : "WHERE ";
        $constraintTables = '';
        $constraintWhere  = '';
        foreach ($this->_aAttributes as $aAttribParams) {
            if (!empty($ret)) {
                $i++;
                $constraintTables .=
                    "INNER JOIN attribute_data ad{$i}
                        ON  ad{$i}.content_id = c.content_id
                        AND ad{$i}.version = c.version
                        AND ad{$i}.language_id = c.language_id
                    INNER JOIN attribute at{$i} ON at{$i}.attribute_id = ad{$i}.attribute_id ";
            }
            $ret  = '';
            $join = (empty($ret)) ? "" : "AND ";
            if (!empty($aAttribParams['name'])) {
                $ret .= $join . "at{$i}.name = '{$aAttribParams['name']}'";
            }
            $join = (empty($ret)) ? "" : " AND ";
            if (!empty($aAttribParams['operator'])) {
                if ($aAttribParams['operator'] == 'IN') {
                    $dbh = SGL_DB::singleton();
                    $value = '(' . addslashes($aAttribParams['value']) . ')';
                } elseif ($aAttribParams['operator'] == 'BETWEEN') {
                    $value = $aAttribParams['value'];
                } else {
                    $value = "'{$aAttribParams['value']}'";
                }
                $field = isset($aAttribParams['expression'])
                    ? sprintf($aAttribParams['expression'], "ad{$i}.value")
                    : "ad{$i}.value";
                $ret .= $join . "$field {$aAttribParams['operator']} $value";
            }

            if (array_key_exists('clauseJoinOperator', $aAttribParams)) {
                $aConstraint[$aAttribParams['clauseJoinOperator']][] = $ret;
            } else {
                $aConstraint['AND'][] = $ret;
            }
        }

        $constraintAndOperator = (!empty($aConstraint['AND']))
            ? implode(' AND ', $aConstraint['AND'])
            : '';

        $constraintWhere .= $where . $constraintAndOperator;

        if (!empty($aConstraint['OR'])) {
            $aTmp = array();
            foreach ($aConstraint['OR'] as $orConstraint) {
            	$aTmp[] = "({$orConstraint})";
            }
            $constraintOrOperator = '(' . implode(' OR ', $aTmp) . ')';

            $constraintWhere .= (strlen($constraintAndOperator))
                ? ' AND ' . $constraintOrOperator
                : $constraintOrOperator;
        }
        return array(
            'where'  => $constraintWhere,
            'tables' => $constraintTables
        );
    }

    /**
     * Prepare ordering.
     *
     * Valid keys from $this->_aFilters:
     *  sortOrder
     *  sortBy
     *
     * @return array
     */
    protected function _prepareOrdering()
    {
        $aSortParams['constraint'] = ''; // by default we don't need any
        $aFilters = $this->getFilters();

        // get sort field
        if (!empty($aFilters['sortBy'])) {
            if (is_array($aFilters['sortBy'])) {
                $attrValue = $aFilters['sortBy']['attribute'];
                if (empty($this->_aAttributes)
                        || (count($this->_aAttributes) == 1
                            && $this->_aAttributes[0]['name'] == $attrValue)) {
                    $aSortParams['constraint'] = " AND at.name = '{$attrValue}'";
                    $aSortParams['order'] = 'ad.value';
                } else {
                    foreach ($this->_aAttributes as $k => $aAttribParams) {
                        if ($aAttribParams['name'] == $attrValue) {
                            $aSortParams['order'] = 'ad' . ($k ? $k : '') . '.value';
                            break;
                        }
                    }
                    // in case needed attribute's table is not joined
                    if (empty($aSortParams['order'])) {
                        $k++;
                        $aSortParams['constraint'] =
                            "INNER JOIN attribute_data ad{$k}
                                ON  ad{$k}.content_id = c.content_id
                                AND ad{$k}.version = c.version
                                AND ad{$k}.language_id = c.language_id
                            INNER JOIN attribute at{$k} ON at{$k}.attribute_id = ad{$k}.attribute_id " .
                            "    AND at{$k}.name = '{$attrValue}'";
                        $aSortParams['order'] = "ad{$k}.value";
                    }
                }
            } else { // sort by content system field
                $aSortParams['order'] = $aFilters['sortBy'];
            }
        } else { // default sort field
            $aSortParams['order'] = 'last_updated';
        }

        // get sort order
        $sortOrder = !empty($aFilters['sortOrder']) ? $aFilters['sortOrder'] : "DESC";

        $aSortParams['order'] = "ORDER BY {$aSortParams['order']} $sortOrder";
        return $aSortParams;
    }

    /**
     * Get limit value.
     *
     * @return mixed
     */
    protected function _prepareLimit()
    {
        $ret = null;
        $aFilters = $this->getFilters();
        if (isset($aFilters['limit'])) {
            $limit = $aFilters['limit'];
            if (is_array($limit)) {
                // if hash recieved count must always be set
                if (isset($limit['count'])) {
                    $ret['count'] = intval($limit['count']);
                    if (isset($limit['offset'])) {
                        $ret['offset'] = intval($limit['offset']);
                    } else {
                        // default offset
                        $ret['offset'] = 0;
                    }
                }
            // in case scalar value is recieved, we consider it as count
            // with zero offset
            } elseif (is_scalar($limit)) {
                $ret['count'] = intval($limit);
                $ret['offset'] = 0;
            }
        }
        return $ret;
    }

    /**
     * Enter description here...
     *
     * @return string
     */
    protected function _prepareCategoryConstraint()
    {
        $ret = null;
        $aFilters = $this->getFilters();
        if (isset($aFilters['categoryId'])) {
            $tableName  = $this->da->conf['table']['content-category'];
            $tableName  = $this->da->dbh->quoteIdentifier($tableName);
            if (is_array($aFilters['categoryId'])) {
                $aCategories = array_map(
                    create_function('$categoryId', 'return intval($categoryId);'),
                    $aFilters['categoryId']
                );
                $constraint = ' AND cc2.category_id IN (' . implode(', ', $aCategories) . ')';
            } else {
                $constraint = ' AND cc2.category_id = ' . intval($aFilters['categoryId']);
            }

            $categoryId = intval($aFilters['categoryId']);
            $ret = "
                INNER JOIN $tableName AS cc2
                    ON cc2.content_id = c.content_id
                    $constraint
            ";
        }
        return $ret;
    }

    /**
     * Prepares results for pagination.
     *
     * @param array $pagerOptions
     * @return SGL_Finder
     */
    public function paginate($pagerOptions = array())
    {
        $this->_paginated = true;
        $this->setPager($pagerOptions);
        return $this;
    }

    /**
     * Sets up Pager object.
     *
     * @param array $pagerOptions
     */
    public function setPager($pagerOptions = array())
    {
        // get total number of contents
        $this->_buildSearchQuery();
        $numRecords = $this->da->getNumberOfMatchingContents($this->query);
        list($currentPage, $perPage) = $this->_maintainPagerState();
        // page options
        $options = array(
            'totalItems'            => $numRecords,
            'currentPage'           => $currentPage,
            'mode'                  => 'Sliding',
            'perPage'               => $perPage,
            'delta'                 => 3,
            'fileName'              => '/pageId/%d/',
            'curPageSpanPre'        => '<span>',
            'curPageSpanPost'       => '</span>',
            'spacesBeforeSeparator' => 1,
            'spacesAfterSeparator'  => 1,
            'append'                => false
        );
        $options = array_merge($options, $pagerOptions);
        require_once 'Pager.php';
        $this->pager = Pager::factory($options);

        list($from, $to) = $this->pager->getOffsetByPageId();
        $limit = array(
            'count'  => $to - $from + 1,
            'offset' => $from - 1
        );

        $this->pager->pageLinks = str_replace(
            '/pageId/' . $this->pager->getCurrentPageID() . '/',
            '/', $this->pager->links);

        $this->addFilter('limit', $limit);
    }

    /**
     * Gets Pager object.
     *
     * @return Pager
     */
    public function getPager()
    {
        return !empty($this->pager)
            ? $this->pager
            : '';
    }

    /**
     * Retrieves and maintains pager options.
     *
     * @return array $currentPage, $perPage
     */
    protected function _maintainPagerState()
    {
        $req = SGL_Request::singleton();
        // maintain number of results per page
        $resPerPage = $req->get('resPerPage');
        $currentPageId = $req->get('pageId');

        $options = array('resPerPage', 'currentPageId');
        $sessValue = SGL_Session::get('cmsPager');

        if (empty($resPerPage)
                && !isset($sessValue['resPerPage'])
                && !empty($this->da->conf['ContentMgr']['resPerPage'])) {
            $resPerPage = $this->da->conf['ContentMgr']['resPerPage'];
        }

        foreach ($options as $pagerOption) {
            if (is_null($$pagerOption) && !empty($sessValue[$pagerOption])) {
                $$pagerOption = $sessValue[$pagerOption];
            }
            $sessValue[$pagerOption] = $$pagerOption;
        }
        SGL_Session::set('cmsPager', $sessValue);
        return array(
            $sessValue['currentPageId'],
            $sessValue['resPerPage']
        );
    }
}
?>
