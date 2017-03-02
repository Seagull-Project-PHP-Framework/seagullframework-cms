<?php

/**
 * SimpleCms data access object.
 *
 * @package simplecms
 * @author Dmitri Lakachauskis <lakiboy83@gmail.com>
 */
class SimpleCmsDAO extends SGL_Manager
{
    /**
     * Returns a singleton SimpleCmsDAO instance.
     *
     * @return SimpleCmsDAO
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
     * Get all content (including revisions and language versions)
     * objects by $userId.
     *
     * @example used to show latest CMS activity
     *
     * @param integer $userId
     * @param integer $limit
     * @param integer $offset
     *
     * @return array
     */
    public function getContentList($userId = null, $limit = null, $offset = 0)
    {
        $contentTbl = SGL_Config::get('table.content');
        $userTbl    = SGL_Config::get('table.user');
        $constraint = '';
        if (!empty($userId)) {
            $constraint = ' WHERE c.updated_by_id = ' . intval($userId);
        }
        $query = "
            SELECT   c.*, u.username, DATE(c.last_updated) AS date
            FROM     $contentTbl AS c
                     INNER JOIN $userTbl AS u ON c.updated_by_id = u.usr_id
                     $constraint
            ORDER BY c.last_updated DESC
        ";
        if (!empty($limit)) {
            $query = $this->dbh->modifyLimitQuery($query, $offset, $limit);
        }
        return $this->dbh->getAll($query);
    }

    /**
     * Get total number of contents (including revisions and language versions)
     * updated by $userId.
     *
     * @example used by CMS activity screen
     *
     * @param integer $userId
     * @param integer $ctId
     *
     * @return integer
     */
    public function getContentsCount($userId = null, $ctId = null)
    {
        $contentTbl   = SGL_Config::get('table.content');
        $constraint   = '';
        $aConstraints = array();
        if (!empty($userId)) {
            $aConstraints[] = 'updated_by_id = ' . intval($userId);
        }
        if (!empty($ctId)) {
            $aConstraints[] = 'content_type_id = ' . intval($ctId);
        }
        $constraint = !empty($aConstraints)
            ? ' WHERE ' . implode(' AND ', $aConstraints)
            : '';
        $query = "
            SELECT   COUNT(*)
            FROM     $contentTbl
                     $constraint
        ";
        return $this->dbh->getOne($query);
    }

    /**
     * Get collection of raw content objects.
     *
     * @param integer $contentTypeId
     *
     * @return array
     */
    public function getContentsByContentTypeId($contentTypeId)
    {
        $query = "
            SELECT *
            FROM   content
            WHERE  content_type_id = " . intval($contentTypeId) . "
        ";
        return $this->dbh->getAll($query);
    }

    /**
     * Get matching content IDs by search string and other params.
     *
     * @param string $q
     * @param string $langId
     * @param integer $contentTypeId
     *
     * @return array
     */
    public function getContentIdsByPattern($q, $langId, $contentTypeId = null)
    {
        $q          = $this->dbh->escapeSimple($q);
        $constraint = '';
        if (!empty($contentTypeId)) {
            $constraint = ' AND c.content_type_id = ' . intval($contentTypeId);
        }
        $query = "
            SELECT    DISTINCT c.content_id
            FROM      content AS c, attribute_data AS a
            WHERE     c.content_id = a.content_id AND c.language_id = a.language_id
                      AND c.version = a.version AND c.is_current = 1
                      AND c.language_id = " . $this->dbh->quoteSmart($langId) . "
                      AND a.value LIKE '%$q%'
                      $constraint
            ORDER BY  c.content_type_id ASC, a.value ASC
        ";
        return $this->dbh->getCol($query);
    }

    // ------------------
    // --- Categories ---
    // ------------------

    public function getCategoryIdsByContentId($contentId)
    {
        $query = "
            SELECT  category_id
            FROM    `content-category`
            WHERE   content_id = " . intval($contentId) . "
        ";
        return $this->dbh->getCol($query);
    }

    // ------------------
    // --- Attributes ---
    // ------------------

    /**
     * Add attribute data.
     *
     * @param array $aData
     *
     * @return boolean
     *
     * @todo check $aData keys for valid field values
     */
    public function addAttributeData(array $aData)
    {
        $ok = $this->dbh->autoExecute('attribute_data', $aData, DB_AUTOQUERY_INSERT);
        return $ok;
    }

    /**
     * Delete attribute data records by $attrId.
     *
     * @param integer $attrId
     *
     * @return boolean
     */
    public function deleteAttributeDataByAttributeId($attrId)
    {
        $query = "
            DELETE FROM attribute_data
            WHERE attribute_id = " . intval($attrId) . "
        ";
        return $this->dbh->query($query);
    }
}
?>