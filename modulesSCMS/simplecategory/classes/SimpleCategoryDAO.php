<?php

/**
 * SimpleCategory data access object.
 *
 * @package simplecategory
 * @author Dmitri Lakachauskis <lakiboy83@gmail.com>
 */
class SimpleCategoryDAO extends SGL_Manager
{
    /**
     * Returns a singleton SimpleCategoryDAO instance.
     *
     * @return SimpleCategoryDAO
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
     * Get category data.
     *
     * @param integer $categoryId
     * @param string $langId
     * @param boolean $showUntranslated
     *
     * @return array
     */
    public function getCategoryById($categoryId, $langId = null,
        $showUntranslated = false)
    {
        if (empty($langId)) {
            $query = "
                SELECT *
                FROM   category2
                WHERE  category2_id = " . intval($categoryId) . "
            ";
        } else {
            if ($showUntranslated) {
                $query = "
                    SELECT    c.*, ct.language_id, ct.name, ct.description
                    FROM      category2 AS c
                    LEFT JOIN category2_trans AS ct
                           ON c.category2_id = ct.category2_id
                              AND ct.language_id = " . $this->dbh->quoteSmart($langId) . "
                    WHERE     c.category2_id = " . intval($categoryId) . "
                ";
            } else {
                $query = "
                    SELECT c.*, ct.language_id, ct.name, ct.description
                    FROM   category2 AS c, category2_trans AS ct
                    WHERE  c.category2_id = ct.category2_id
                           AND ct.language_id = " . $this->dbh->quoteSmart($langId) . "
                           AND c.category2_id = " . intval($categoryId) . "
                ";
            }
        }
        return $this->dbh->getRow($query);
    }

    /**
     * Deletes category and all sub categories including their translations.
     *
     * @param integer $categoryId
     *
     * @return boolean
     */
    public function deleteCategoryById($categoryId)
    {
        $oNode = $this->getCategoryById($categoryId);

        // delete subtree + translations
        $query = "
            DELETE
            FROM   category2
            WHERE  category2_id = " . intval($categoryId) . "
        ";
        $ret = $this->dbh->query($query);

        // move remaining nodes up
        $query = "
            UPDATE category2
            SET    order_id = order_id - 1
            WHERE  parent_id " . $this->_getCategoryParentConstraint($oNode->parent_id) . "
                   AND order_id > " . intval($oNode->order_id) . "
        ";
        $ret = $this->dbh->query($query);

        return $ret;
    }

    /**
     * Gets all child categories for certain category.
     *
     * @param integer $categoryId
     * @param string $langId
     * @param boolean $onlyActive
     * @param boolean $showUntranslated
     *
     * @return array
     */
    public function getChildrenByCategoryId($categoryId, $langId,
        $onlyActive = false, $showUntranslated = false)
    {
        $constraint       = $onlyActive ? ' AND c.is_active = 1 ' : '';
        $parentConstraint = $this->_getCategoryParentConstraint($categoryId);
        if ($showUntranslated) {
            $query      = "
                SELECT     c.*, ct.language_id, ct.name, ct.description
                FROM       category2 AS c
                LEFT JOIN  category2_trans AS ct
                       ON  c.category2_id = ct.category2_id
                           AND ct.language_id = " . $this->dbh->quoteSmart($langId) . "
                WHERE      c.parent_id $parentConstraint
                           $constraint
                ORDER BY   c.order_id
            ";
        } else {
            $query      = "
                SELECT    c.*, ct.language_id, ct.name, ct.description
                FROM      category2 AS c, category2_trans AS ct
                WHERE     c.category2_id = ct.category2_id
                          AND c.parent_id $parentConstraint
                          AND ct.language_id = " . $this->dbh->quoteSmart($langId) . "
                          $constraint
                ORDER BY  c.order_id
            ";
        }
        $aRet = $this->dbh->getAll($query);
        $aRet = $this->_addNodesMetaData($aRet);
        return $aRet;
    }

    /**
     * Calculates children number for certain category.
     *
     * @param integer $categoryId
     *
     * @return integer
     */
    public function getChildrenCountByCategoryId($categoryId)
    {
        $query = "
            SELECT COUNT(*)
            FROM   category2
            WHERE  parent_id " . $this->_getCategoryParentConstraint($categoryId);
        return $this->dbh->getOne($query);
    }

    /**
     * Get subtree of certain category. Recursive!
     *
     * @param integer $categoryId
     * @param string $langId
     * @param boolean $onlyActive
     * @param boolean $showUntranslated
     *
     * @return array
     */
    public function getTreeByCategoryId($categoryId, $langId,
        $onlyActive = false, $showUntranslated = false)
    {
        $aNodes = $this->getChildrenByCategoryId($categoryId, $langId,
            $onlyActive, $showUntranslated);
        foreach ($aNodes as $oNode) {
            $oNode->aChildren = $this->getTreeByCategoryId(
                $oNode->category2_id, $langId, $onlyActive, $showUntranslated);
        }
        return $aNodes;
    }

    /**
     * Get path to certain category.
     *
     * @param integer $categoryId
     * @param string $langId
     * @param boolean $includeSelf
     *
     * @return array
     */
    public function getPathByCategoryId($categoryId, $langId = null,
        $includeSelf = true)
    {
        $aRet = array();
        if (!empty($categoryId)) {
            do {
                $oNode      = $this->getCategoryById($categoryId, $langId,
                    $showUntrans = true);
                $categoryId = $oNode->parent_id;
                $aRet[]     = $oNode;
            } while ($categoryId);
            // remove last
            if (!$includeSelf) {
                array_shift($aRet);
            }
        }
        return array_reverse($aRet);
    }

    /**
     * Add new category to certain parent node.
     *
     * @param integer $parentId
     * @param boolean $isActive
     *
     * @return integer
     */
    public function addCategory($parentId, $isActive = true)
    {
        if (empty($parentId)) {
            $parentId = null;
        }
        if ($parentId) {
            $aParentNode = $this->getCategoryById($parentId);
            $levelId     = $aParentNode->level_id + 1;
        } else {
            $levelId     = 0;
        }
        $aData['category2_id'] = $this->dbh->nextId('category2');
        $aData['parent_id']    = $parentId;
        $aData['order_id']     = $this->getChildrenCountByCategoryId($parentId);
        $aData['level_id']     = $levelId;
        $aData['is_active']    = $isActive;
        $this->dbh->autoExecute('category2', $aData);

        return $aData['category2_id'];
    }

    /**
     * Updates category's data.
     *
     * @param integer $categoryId
     * @param array $aData
     *
     * @return boolean
     */
    public function updateCategoryById($categoryId, array $aData)
    {
        return $this->dbh->autoExecute('category2', $aData,
            DB_AUTOQUERY_UPDATE, 'category2_id = ' . intval($categoryId));
    }

    /**
     * Updates/inserts category's translation.
     *
     * @param integer $categoryId
     * @param integer $langId
     * @param array $aData
     *
     * return boolean
     */
    public function updateCategoryTranslationById($categoryId, $langId, array $aData)
    {
        // try to insert new translation
        $ret = $this->dbh->autoExecute('category2_trans',
            array_merge($aData, array(
                'category2_id' => $categoryId,
                'language_id'     => $langId
            )),
            DB_AUTOQUERY_INSERT
        );

        // if failed, then update existing one
        if (PEAR::isError($ret, DB_ERROR_ALREADY_EXISTS)) {
            SGL_Error::pop();
            $where = 'category2_id = ' . intval($categoryId)
                . ' AND language_id = ' . $this->dbh->quoteSmart($langId);
            $ret = $this->dbh->autoExecute('category2_trans', $aData,
                DB_AUTOQUERY_UPDATE, $where);
        }

        return $ret;
    }

    /**
     * Moves category $categoryId under parent $parentId with order $orderId.
     *
     * @param integer $categoryId
     * @param integer $parentId
     * @param integer $orderId
     *
     * @return boolean
     */
    public function moveCategoryById($categoryId, $parentId, $orderId)
    {
        $ret     = false;
        $oNode   = $this->getCategoryById($categoryId);
        $levelId = $oNode->level_id;

        // make sure parent ID is not zero or false
        if (empty($parentId)) {
            $parentId = null;
        }

        if ($oNode->parent_id != $parentId || $oNode->order_id != $orderId) {

            if ($oNode->parent_id == $parentId) {
                $query = "
                    UPDATE category2
                    SET    order_id = order_id - 1
                    WHERE  parent_id " . $this->_getCategoryParentConstraint($parentId) . "
                           AND order_id > " . intval($oNode->order_id) . "
                ";
                $ret = $this->dbh->query($query);
            }

            $query = "
                UPDATE category2
                SET    order_id = order_id + 1
                WHERE  parent_id " . $this->_getCategoryParentConstraint($parentId) . "
                       AND order_id >= " . intval($orderId) . "
            ";
            $ret = $this->dbh->query($query);
        }

        if ($oNode->parent_id != $parentId) {
            $query = "
                UPDATE category2
                SET    order_id = order_id - 1
                WHERE  parent_id " . $this->_getCategoryParentConstraint($oNode->parent_id) . "
                       AND order_id > " . intval($oNode->order_id) . "
            ";
            $ret = $this->dbh->query($query);

            $oParentNode = $this->getCategoryById($parentId);
            $levelId     = ++$oParentNode->level_id;
        }

        // if something was changed, update category record
        if ($ret && !PEAR::isError($ret)) {
            $ret = $this->updateCategoryById($categoryId, array(
                'order_id'  => $orderId,
                'parent_id' => $parentId,
                'level_id'  => $levelId
            ));
        }

        return $ret;
    }

    // ---------------
    // --- Helpers ---
    // ---------------

    protected function _addNodesMetaData(array $aNodes)
    {
        if (!empty($aNodes)) {
            $aNodes[0]->first                 = true;
            $aNodes[(count($aNodes)-1)]->last = true;
        }
        return $aNodes;
    }

    protected function _getCategoryParentConstraint($parentId)
    {
        $ret = is_null($parentId) ? ' IS ' : ' = ';
        return $ret . $this->dbh->quoteSmart($parentId);
    }
}
?>