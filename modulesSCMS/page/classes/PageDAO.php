<?php

/**
 * Page data access object.
 *
 * @package page
 * @author Dmitri Lakachauskis <lakiboy83@gmail.com>
 */
class PageDAO extends SGL_Manager
{
    const OPERATION_INSERT = 1;
    const OPERATION_UPDATE = 2;

    /**
     * Returns a singleton PageDAO instance.
     *
     * @return PageDAO
     */
    public static function &singleton()
    {
        static $instance;

        // If the instance is not there, create one
        if (!isset($instance)) {
            $class = __CLASS__;
            $instance = new $class();
        }
        return $instance;
    }

    /**
     * Determines which operation should be executed
     *
     * @param object $input
     * @param string $idAlias
     */
    protected function _getOperationType($input, $idAlias='id')
    {
        if (is_null($input->$idAlias)) {
            return self::OPERATION_INSERT;
        } else {
            return self::OPERATION_UPDATE;
        }
    }

    // -------------
    // --- Sites ---
    // -------------

    /**
     * Returns assoc array of sites.
     *
     * @return array
     */
    public function getSitesList()
    {
        $query = "
            SELECT   site_id, name
            FROM     site
            ORDER BY name ASC
        ";
        return $this->dbh->getAssoc($query);
    }

    /**
     * Get site object by ID.
     *
     * @param integer $siteId
     *
     * @return object
     */
    public function getSiteById($siteId)
    {
        $query = "
            SELECT *
            FROM   site
            WHERE  site_id = " . intval($siteId) . "
        ";
        return $this->dbh->getRow($query);
    }

    // --------------
    // --- Routes ---
    // --------------

    /**
     * Get route object.
     *
     * @param integer $siteId
     * @param integer $pageId
     *
     * @return object
     */
    public function getRouteByPageId($siteId, $pageId)
    {
        $query = "
            SELECT *
            FROM   route
            WHERE  site_id = " . intval($siteId) . "
                   AND page_id = " . intval($pageId) . "
        ";
        return $this->dbh->getRow($query);
    }

    /**
     * Get route object.
     *
     * @param integer $siteId
     * @param integer $pageId
     *
     * @return object
     */
    public function getRouteById($routeId)
    {
        $query = "
            SELECT *
            FROM   route
            WHERE  route_id = " . intval($routeId) . "
        ";
        return $this->dbh->getRow($query);
    }

    /**
     * Check if route is unique within a site.
     *
     * TODO: remove this and replace calls with $this->isUniquePath
     *
     * @param integer $siteId
     * @param string $routeName
     *
     * @return booleam
     */
    public function isUniqueRoute($siteId, $routeName)
    {
        $query = "
            SELECT COUNT(*)
            FROM   route
            WHERE  site_id = " . intval($siteId) . "
                   AND route = " . $this->dbh->quoteSmart($routeName) . "
        ";
        $ret = $this->dbh->getOne($query);
        return !$ret;
    }

    /**
     * Check if route is unique within a site.
     *
     * @param integer $siteId
     * @param string $routeName
     *
     * @return booleam
     */
    public function isUniquePath(SGL_Routes_Route $route)
    {
        $constraint = '';

        if (is_numeric($route->route_id)) {
            $constraint .= ' AND route_id != ' . intval($route->route_id);
        }

        $query = "
            SELECT COUNT(*)
            FROM   route
            WHERE  site_id = " . intval($route->site_id) . "
                   AND route = " . $this->dbh->quoteSmart($route->path) . "
                   $constraint
        ";
        $ret = $this->dbh->getOne($query);
        return !$ret;
    }

    /**
     * Delete routes for certain page.
     *
     * @param integer $siteId
     * @param integer $pageId
     *
     * @return boolean
     */
    public function deleteRouteByPageId($siteId, $pageId)
    {
        $query = "
            DELETE FROM route
            WHERE  site_id = " . intval($siteId) . "
                   AND page_id = " . intval($pageId) . "
        ";
        return $this->dbh->query($query);
    }

    /**
     * Delete routes.
     *
     * @param array $aRouteId
     *
     * @return boolean
     */
    public function deleteRoutes($aRouteId)
    {
        $constraint = implode(',', $aRouteId);
        $query = "
            DELETE FROM route
            WHERE  route_id IN (" . $constraint . ")
        ";
        return $this->dbh->query($query);
    }

    /**
     * Add new route to certain page within site.
     *
     * @param integer $siteId
     * @param integer $pageId
     * @param string $routeName
     *
     * @return boolean
     */
    /*
    public function addRoute($siteId, $pageId, $routeName)
    {
        return $this->dbh->autoExecute('route', array(
            'route_id' => $this->dbh->nextId('route'),
            'page_id'  => $pageId,
            'site_id'  => $siteId,
            'route'    => $routeName
        ), DB_AUTOQUERY_INSERT);
    }
    */
    /**
     * Update route.
     *
     * @param integer $routeId
     * @param string $routeName
     *
     * @param boolean
     */
    public function updateRouteById($routeId, $routeName)
    {
        return $this->dbh->autoExecute('route', array('route' => $routeName),
            DB_AUTOQUERY_UPDATE, 'route_id = ' . intval($routeId));
    }

    /**
     * Get routes.
     *
     * @param array $aFilter
     * @param array $pagerOptions
     *
     * @return array
     */
    public function getRoutes($aFilter, $pagerOptions = array())
    {
        // constraints
        $aConstraints = array();

        if (!empty($aFilter['route'])) {
            $aConstraints[] = sprintf('r.`route` LIKE "%%%s%%"',$aFilter['route']);
        }

        if (array_key_exists('siteId', $aFilter) && is_numeric($aFilter['siteId'])) {
            $aConstraints[] = 'r.`site_id` = ' . intval($aFilter['siteId']);
        }

        if (array_key_exists('isActive', $aFilter) && is_numeric($aFilter['isActive'])) {
            $aConstraints[] = 'r.`is_active` = ' . intval($aFilter['isActive']);
        }

        $constraint = !empty($aConstraints)
            ? ' WHERE ' . implode(' AND ', $aConstraints)
            : '';

        // sorting
        $sortBy = (!empty($aFilter['sortBy']) && in_array('route','is_active'))
            ? $aFilter['sortBy']
            : 'r.`route_id`';

        $sortOrder = (!empty($aFilter['sortOrder']) && in_array('ASC','DESC'))
            ? $aFilter['sortBy']
            : 'DESC';

        $query = "
                SELECT
                    r.*
                FROM `" . SGL_Config::get('table.route') . "` AS r
                $constraint
                ORDER BY $sortBy $sortOrder";

        $aResult = SGL_DB::getPagedData($this->dbh, $query, $pagerOptions, false, DB_FETCHMODE_OBJECT);

        return (count($pagerOptions)) ? $aResult : $aResult['data'];
    }

    public function updateRouteStatus($routeId, $status)
    {
        $query = "
            UPDATE `" . SGL_Config::get('table.route') . "`
            SET `is_active` = ?
            WHERE  `route_id` = ?
        ";

        return $this->dbh->query($query, array($status, $routeId));
    }

    public function saveRoute(SGL_Routes_Route $route)
    {
        $type = $this->_getOperationType($route, 'route_id');

        switch ($type) {
            case self::OPERATION_INSERT:
                $this->addRoute($route);
                break;
            case self::OPERATION_UPDATE:
                $this->updateRoute($route);
                break;
            default:
                break;
        }
        return $route;
    }

    public function addRoute(SGL_Routes_Route $route)
    {
        $route->is_active = ($route->is_active) ? 1 : 0;

        if (!is_numeric($route->page_id)) {
            $route->page_id = null;
        }

        return $this->dbh->autoExecute('route', array(
            'route_id'    => $route->route_id = $this->dbh->nextId('route'),
            'page_id'     => $route->page_id,
            'site_id'     => $route->site_id,
            'route'       => $route->path,
            'description' => $route->description,
            'is_active'   => $route->is_active,
            'route_data'  => serialize($route->toRouteArray()),
        ), DB_AUTOQUERY_INSERT);
    }

    public function updateRoute(SGL_Routes_Route $route)
    {
        $route->is_active = ($route->is_active) ? 1 : 0;

        if (!is_numeric($route->page_id)) {
            $route->page_id = null;
        }

        return $this->dbh->autoExecute('route', array(
            'page_id'     => $route->page_id,
            'site_id'     => $route->site_id,
            'route'       => $route->path,
            'description' => $route->description,
            'is_active'   => $route->is_active,
            'route_data'  => serialize($route->toRouteArray()),
        ), DB_AUTOQUERY_UPDATE, 'route_id = ' . intval($route->route_id));
    }

    public function rebuildRouteCache()
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);
        SGL_Task_RebuildRouteCache::run();
    }


    // -------------
    // --- Pages ---
    // -------------

    /**
     * Returns assoc array of page statuses.
     *
     * @return array
     */
    public function getPageStatusesList()
    {
        return array(
            1 => 'active'
        );
    }

    /**
     * Adds new page.
     *
     * @param array $aData
     *
     * @return integer
     */
    public function addPage(array $aData)
    {
        // prepare tree data
        if (empty($aData['parent_id'])) {
            $aData['parent_id'] = null;
        }
        if ($aData['parent_id']) {
            $oParentNode       = $this->getPageById($aData['parent_id']);
            $aData['level_id'] = $oParentNode->level_id + 1;
        } else {
            $aData['level_id'] = 0;
        }
        $aData['order_id'] = $this->getChildrenCountByPageId($aData['site_id'],
            $aData['parent_id']);

        // prepare system data
        if (empty($aData['date_created'])) {
            $aData['date_created'] = SGL_Date::getTime($gmt = true);
        }
        $aData['last_updated'] = $aData['date_created'];
        if (!empty($aData['created_by'])) {
            $aData['updated_by'] = $aData['created_by'];
        }

        // validate
        $aAllowedFields = array(
            'parent_id', 'order_id', 'level_id', 'status', 'site_id',
            'content_id', 'layout_id', 'appears_in_nav', 'are_comments_allowed',
            'date_created', 'last_updated', 'created_by', 'updated_by'
        );
        foreach ($aData as $k => $v) {
            if (!in_array($k, $aAllowedFields)) {
                unset($aData[$k]);
            }
        }

        // add page
        $aData['page_id'] = $this->dbh->nextId('page');
        $ok = $this->dbh->autoExecute('page', $aData, DB_AUTOQUERY_INSERT);
        if (!PEAR::isError($ok)) {
            $ok = $aData['page_id'];
        }
        return $ok;
    }

    /**
     * Updates page by ID.
     *
     * @param integer $pageId
     * @param array $aData
     *
     * @param boolean
     */
    public function updatePageById($pageId, array $aData)
    {
        if (empty($aData['last_updated'])) {
            $aData['last_updated'] = SGL_Date::getTime($gmt = true);
        }
        // validate
        $aAllowedFields = array(
            'site_id', 'status',
            'order_id', 'parent_id', 'level_id',
            'content_id', 'layout_id', 'appears_in_nav', 'are_comments_allowed',
            'last_updated', 'updated_by'
        );
        foreach ($aData as $k => $v) {
            if (!in_array($k, $aAllowedFields)) {
                unset($aData[$k]);
            }
        }
        return $this->dbh->autoExecute('page', $aData,
            DB_AUTOQUERY_UPDATE, 'page_id = ' . intval($pageId));
    }

    /**
     * Updates/inserts page's translation.
     *
     * @param integer $pageId
     * @param integer $langId
     * @param array $aData
     *
     * return boolean
     */
    public function updatePageTranslationById($pageId, $langId, array $aData)
    {
        // validate
        $aAllowedFields = array('title', 'meta_desc', 'meta_key');
        foreach ($aData as $k => $v) {
            if (!in_array($k, $aAllowedFields)) {
                unset($aData[$k]);
            }
        }

        // try to insert new translation
        $ret = $this->dbh->autoExecute('page_trans',
            array_merge($aData, array(
                'page_id'     => $pageId,
                'language_id' => $langId
            )),
            DB_AUTOQUERY_INSERT
        );

        // if failed, then update existing one
        if (PEAR::isError($ret, DB_ERROR_ALREADY_EXISTS)) {
            SGL_Error::pop();
            $where = 'page_id = ' . intval($pageId)
                . ' AND language_id = ' . $this->dbh->quoteSmart($langId);
            $ret = $this->dbh->autoExecute('page_trans', $aData,
                DB_AUTOQUERY_UPDATE, $where);
        }

        return $ret;
    }

    /**
     * Get page object.
     *
     * @param integer $pageId
     * @param string $langId
     *
     * @return object
     */
    public function getPageById($pageId, $langId = null)
    {
        if (!empty($langId)) {
            $query = "
                SELECT    p.*, pt.*
                FROM      page AS p
                LEFT JOIN page_trans AS pt
                  ON      p.page_id = pt.page_id
                          AND pt.language_id = " . $this->dbh->quoteSmart($langId) . "
                WHERE     p.page_id = " . intval($pageId);
            $oRes = $this->dbh->getRow($query);
            if (!empty($oRes) && empty($oRes->language_id)) {
                $oRes->language_id = $langId;
            }
        } else {
            $query = "
                SELECT *
                FROM   page
                WHERE  page_id = " . intval($pageId);
            $oRes = $this->dbh->getRow($query);
        }
        return $oRes;
    }

    /**
     * Deletes page from db + all associated data.
     *
     * @param integer $pageId
     *
     * @return boolean
     */
    public function deletePageById($pageId)
    {
        $oNode = $this->getPageById($pageId);

        $query = "
            DELETE FROM page
            WHERE page_id = " . intval($pageId) . "
        ";
        $ok = $this->dbh->query($query);

        // move remaining nodes up
        $query = "
            UPDATE page
            SET    order_id = order_id - 1
            WHERE  parent_id " . $this->_getPageParentConstraint($oNode->parent_id) . "
                   AND order_id > " . intval($oNode->order_id) . "
        ";
        return $this->dbh->query($query);
    }

    /**
     * Get collection of pages according to specified criteria.
     *
     * @param integer $siteId
     * @param string $langId
     * @param integer $parentId
     * @param mixed $status
     * @param boolean $showUntranslated
     * @param integer $limit
     * @param integer $offset
     * @param string $sortBy
     * @param string $sortOrder
     *
     * return array
     */
    public function getPages($siteId, $langId, $parentId = null,
        $status = 'all', $showUntranslated = true, $limit = null, $offset = 0,
        $sortBy = 'p.last_updated', $sortOrder = 'DESC')
    {
        $join       = $showUntranslated ? 'LEFT JOIN' : 'INNER JOIN';
        $constraint = '';
        if ($status != 'all') {
            $constraint .= ' AND p.status = ' . intval($status);
        }
        $aChildrenIds = $this->getDescendantPageIdsByParentId($siteId,
            $parentId, $status);
        if ($parentId) {
            array_unshift($aChildrenIds, $parentId);
        }
        if (count($aChildrenIds) == 1) {
            $constraint .= ' AND p.page_id = ' . $aChildrenIds[0];
        } elseif (!empty($aChildrenIds)) {
            $constraint .= ' AND p.page_id IN (' . implode(', ', $aChildrenIds) . ')';
        }
        $query = "
            SELECT     p.*, pt.title, pt.language_id, u.username
            FROM       page AS p
            $join      page_trans AS pt
              ON       p.page_id = pt.page_id
                       AND pt.language_id = " . $this->dbh->quoteSmart($langId) . "
            INNER JOIN usr AS u
              ON       p.updated_by = u.usr_id
            WHERE      p.site_id = " . intval($siteId) . "
                       $constraint
            ORDER BY   $sortBy $sortOrder
        ";
        if (!empty($limit)) {
            $query = $this->dbh->modifyLimitQuery($query, $offset, $limit);
        }
        return $this->dbh->getAll($query);
    }

    /**
     * Get count of pages according to specified criteria.
     *
     * @param integer $siteId
     * @param string $langId
     * @param integer $parentId
     * @param mixed $status
     * @param boolean $showUntranslated
     *
     * @return integer
     */
    public function getPageCount($siteId, $langId, $parentId = null,
        $status = 'all', $showUntranslated = true)
    {
        $join       = $showUntranslated ? 'LEFT JOIN' : 'INNER JOIN';
        $constraint = '';
        if ($status != 'all') {
            $constraint .= ' AND p.status = ' . intval($status);
        }
        $aChildrenIds = $this->getDescendantPageIdsByParentId($siteId,
            $parentId, $status);
        if ($parentId) {
            array_unshift($aChildrenIds, $parentId);
        }
        if (count($aChildrenIds) == 1) {
            $constraint .= ' AND p.page_id = ' . $aChildrenIds[0];
        } elseif (!empty($aChildrenIds)) {
            $constraint .= ' AND p.page_id IN (' . implode(', ', $aChildrenIds) . ')';
        }
        $query = "
            SELECT COUNT(p.page_id)
            FROM   page AS p
            $join  page_trans AS pt
              ON   p.page_id = pt.page_id
                   AND pt.language_id = " . $this->dbh->quoteSmart($langId) . "
            WHERE  p.site_id = " . intval($siteId) . "
                   $constraint
        ";
        return $this->dbh->getOne($query);
    }

    /**
     * Get array of IDs for child pages for certain parent.
     *
     * @param integer $siteId
     * @param integer $parentId
     * @param mixed $status
     *
     * @return array
     */
    public function getDescendantPageIdsByParentId($siteId, $parentId, $status = 'all')
    {
        $constraint = $status != 'all' ? ' AND status = ' . intval($status) : '';
        $query = "
            SELECT page_id
            FROM   page
            WHERE  site_id = " . intval($siteId) . "
                   AND parent_id " . $this->_getPageParentConstraint($parentId) . "
                   $constraint
        ";
        $aPages = $this->dbh->getCol($query);
        foreach ($aPages as $pageId) {
            $aChildren = $this->getDescendantPageIdsByParentId($siteId, $pageId, $status);
            $aPages    = array_merge($aPages, $aChildren);
        }
        return $aPages;
    }

    /**
     * Currently only one page type is supported - flat page.
     *
     * @return array
     */
    public function getPageTypesList()
    {
        return array(
            1 => 'flat page'
        );
    }


    //
    // Below is almost duplicated code from SimpleCategoryDAO.
    // We need to fix it.
    //


    /**
     * Gets all child nodes for certain page.
     *
     * @param integer $siteId
     * @param integer $pagId
     * @param string $langId
     * @param mixed $status
     * @param boolean $showUntranslated
     *
     * @return array
     */
    public function getChildrenByPageId($siteId, $pageId, $langId,
        $status = 'all', $showUntranslated = true)
    {
        $constraint = '';
        $join       = $showUntranslated ? 'LEFT JOIN' : 'INNER JOIN';
        if ($status != 'all') {
            $constraint .= ' AND p.status = ' . intval($status);
        }
        $query = "
            SELECT   p.*, pt.language_id, pt.title
            FROM     page AS p
            $join    page_trans AS pt
              ON     p.page_id = pt.page_id
                     AND pt.language_id = " . $this->dbh->quoteSmart($langId) . "
            WHERE    p.site_id = " . intval($siteId) . "
                     AND p.parent_id " . $this->_getPageParentConstraint($pageId) . "
                     $constraint
            ORDER BY p.order_id ASC
        ";
        $aRet = $this->dbh->getAll($query);
        $aRet = $this->_addNodesMetaData($aRet);
        return $aRet;
    }

    /**
     * Calculates children number for certain page.
     *
     * @param integer $siteId
     * @param integer $pageId
     *
     * @return integer
     */
    public function getChildrenCountByPageId($siteId, $pageId)
    {
        $query = "
            SELECT COUNT(*)
            FROM   page
            WHERE  site_id = " . intval($siteId) . "
                   AND parent_id " . $this->_getPageParentConstraint($pageId);
        return $this->dbh->getOne($query);
    }

    /**
     * Get subtree of certain page. Recursive!
     *
     * @param integer $siteId
     * @param integer $pageId
     * @param string $langId
     * @param mixed $status
     * @param boolean $showUntranslated
     *
     * @return array
     */
    public function getTreeByPageId($siteId, $pageId, $langId,
        $status = 'all', $showUntranslated = true)
    {
        $aNodes = $this->getChildrenByPageId($siteId, $pageId, $langId, $status,
            $showUntranslated);
        foreach ($aNodes as $oNode) {
            $oNode->aChildren = $this->getTreeByPageId($siteId,
                $oNode->page_id, $langId, $status, $showUntranslated);
        }
        return $aNodes;
    }

    /**
     * Moves page $pageId under parent $parentId with order $orderId.
     * Applies to site $siteId.
     *
     * @param integer $siteId
     * @param integer $pageId
     * @param integer $parentId
     * @param integer $orderId
     *
     * @return boolean
     */
    public function movePageById($siteId, $pageId, $parentId, $orderId)
    {
        $ret     = false;
        $oNode   = $this->getPageById($pageId);
        $levelId = $oNode->level_id;

        // make sure parent ID is not zero or false
        if (empty($parentId)) {
            $parentId = null;
        }

        if ($oNode->parent_id != $parentId || $oNode->order_id != $orderId) {

            if ($oNode->parent_id == $parentId) {
                $query = "
                    UPDATE page
                    SET    order_id = order_id - 1
                    WHERE  site_id = " . intval($siteId) . "
                           AND parent_id " . $this->_getPageParentConstraint($parentId) . "
                           AND order_id > " . intval($oNode->order_id) . "
                ";
                $ret = $this->dbh->query($query);
            }

            $query = "
                UPDATE page
                SET    order_id = order_id + 1
                WHERE  site_id = " . intval($siteId) . "
                       AND parent_id " . $this->_getPageParentConstraint($parentId) . "
                       AND order_id >= " . intval($orderId) . "
            ";
            $ret = $this->dbh->query($query);
        }

        if ($oNode->parent_id != $parentId) {
            $query = "
                UPDATE page
                SET    order_id = order_id - 1
                WHERE  site_id = " . intval($siteId) . "
                       AND parent_id " . $this->_getPageParentConstraint($oNode->parent_id) . "
                       AND order_id > " . intval($oNode->order_id) . "
            ";
            $ret = $this->dbh->query($query);

            $oParentNode = $this->getPageById($parentId);
            $levelId     = ++$oParentNode->level_id;
        }

        // if something was changed, update page record
        if ($ret && !PEAR::isError($ret)) {
            $ret = $this->updatePageById($pageId, array(
                'order_id'  => $orderId,
                'parent_id' => $parentId,
                'level_id'  => $levelId
            ));
        }

        return $ret;
    }

    public function getPathByPageId($pageId, $langId, $includeSelf = true)
    {
        $aRet = array();
        if (!empty($pageId)) {
            do {
                $oNode  = $this->getPageById($pageId, $langId);
                $pageId = $oNode->parent_id;
                $aRet[] = $oNode;
            } while ($pageId);
            // remove last
            if (!$includeSelf) {
                array_shift($aRet);
            }
        }
        return array_reverse($aRet);
    }

    protected function _addNodesMetaData(array $aNodes)
    {
        if (!empty($aNodes)) {
            $aNodes[0]->first                 = true;
            $aNodes[(count($aNodes)-1)]->last = true;
        }
        return $aNodes;
    }

    protected function _getPageParentConstraint($parentId)
    {
        $ret = is_null($parentId) ? ' IS ' : ' = ';
        return $ret . $this->dbh->quoteSmart($parentId);
    }
}
?>