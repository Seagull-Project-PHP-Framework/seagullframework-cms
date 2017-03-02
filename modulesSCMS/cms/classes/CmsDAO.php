<?php
define('SGL_RET_TYPE_ARRAY', 1);
define('SGL_CONTENT_INSERT', 1);
define('SGL_CONTENT_UPDATE', 2);
define('SGL_CONTENTTYPE_INSERT', 3);
define('SGL_CONTENTTYPE_UPDATE', 4);

/**
 * Data access methods for the cms module.
 *
 * @package cms
 * @author  Demian Turner <demian@phpkitchen.com>
 */
class CmsDAO extends SGL_Manager
{

    public $aTypeConsts = array();

    /**
     * Constructor - set default resources.
     *
     * @return CmsDAO
     */
    function __construct()
    {
        parent::SGL_Manager();
        $this->_initialiseAttribTypeConstants();
    }

    /**
     * Returns a singleton CmsDAO instance.
     *
     * example usage:
     * $da = & CmsDAO::singleton();
     * warning: in order to work correctly, the DA
     * singleton must be instantiated statically and
     * by reference
     *
     * @access  public
     * @static
     * @return  CmsDAO reference to CmsDAO object
     */
    function &singleton()
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
     * Sets up constants used for attribute types.
     *
     * Currently defined are:
     *
     *  SGL_CONTENT_ATTR_TYPE_TEXT
     *  SGL_CONTENT_ATTR_TYPE_LARGETEXT
     *  SGL_CONTENT_ATTR_TYPE_RICHTEXT
     *  SGL_CONTENT_ATTR_TYPE_INT
     *  SGL_CONTENT_ATTR_TYPE_FLOAT
     *  SGL_CONTENT_ATTR_TYPE_URL
     *  SGL_CONTENT_ATTR_TYPE_FILE
     *  SGL_CONTENT_ATTR_TYPE_CHOICE
     *  SGL_CONTENT_ATTR_TYPE_DATE
     *  SGL_CONTENT_ATTR_TYPE_LIST
     *  SGL_CONTENT_ATTR_TYPE_RADIO
     *
     */
    function _initialiseAttribTypeConstants()
    {
        $aData = $this->getAttribTypes();
        foreach ($aData as $oAttributeType) {
            define('SGL_CONTENT_ATTR_TYPE_'.$oAttributeType->name,
                $oAttributeType->attribute_type_id);
            $this->aTypeConsts[constant('SGL_CONTENT_ATTR_TYPE_'.$oAttributeType->name)] =
                $oAttributeType->alias;
        }
    }

    function getAttribTypeConstants()
    {
        return $this->aTypeConsts;
    }

    function getAttribTypes()
    {
        $query = "
            SELECT  attribute_type_id, name, alias
            FROM " . SGL_Config::get('table.attribute_type');
        return $this->dbh->getAll($query);
    }

    function getAttribById($id)
    {
        $query = "
            SELECT
                a.attribute_id,
                a.attribute_type_id,
                at.alias as attribute_type_name,
                a.content_type_id,
                a.name,
                a.alias, ".
                $this->dbh->quoteIdentifier('desc').",
                a.params
            FROM ".SGL_Config::get('table.attribute')." a
            JOIN ".SGL_Config::get('table.attribute_type')."  at ON at.attribute_type_id = a.attribute_type_id
            WHERE attribute_id = $id
        ";
        return $this->dbh->getRow($query);
    }

    function getAttribData($contentId, $version, $langCode)
    {
        $query = "
            SELECT  value
            FROM    " . SGL_Config::get('table.attribute_data') . "
            WHERE   content_id = $contentId
            AND     version = " . $this->dbh->quoteSmart($version) . "
            AND     language_id = " . $this->dbh->quoteSmart($langCode)
        ;
        return $this->dbh->getOne($query);
    }

    function getAttribDataByContentId($contentId, $version, $langCode)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $query = "
            SELECT
                    ad.content_id,
                    ad.version,
                    ad.language_id,
                    ad.attribute_id,
                    at.name,
                    at.alias,
                    ad.value,
                    at.attribute_type_id,
                    att.name as attribute_type_name,
                    at.content_type_id,
                    at.params

            FROM    " . SGL_Config::get('table.attribute_type') . "  att
            JOIN    " . SGL_Config::get('table.attribute') . " at
                ON at.attribute_type_id = att.attribute_type_id
            JOIN    " . SGL_Config::get('table.attribute_data') . " ad
                ON ad.attribute_id = at.attribute_id
            JOIN    " . SGL_Config::get('table.content_type') . " ct
                ON ct.content_type_id = at.content_type_id

            WHERE   ad.content_id = $contentId
            AND     ad.version = $version
            AND     ad.language_id = " . $this->dbh->quoteSmart($langCode) . "
            ORDER BY at.attribute_id
                ";
        $aRes = $this->dbh->getAll($query);
        return $aRes;
    }

    function getDefaultAttribsByContentTypeId($contentTypeId)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $query = "
            SELECT
                    at.attribute_id,
                    at.name,
                    at.alias,
                    att.attribute_type_id,
                    att.name as attribute_type_name,
                    at.content_type_id,
                    at.params

            FROM    " . SGL_Config::get('table.attribute_type') . "  att
            JOIN    " . SGL_Config::get('table.attribute') . " at
                ON at.attribute_type_id = att.attribute_type_id

            WHERE   at.content_type_id = $contentTypeId
            ORDER BY at.attribute_id
                ";
        $aRes = $this->dbh->getAll($query);
        return $aRes;
    }

    function getAttribListParamsByListId($listId)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $query = "
            SELECT  al.params
            FROM    ".SGL_Config::get('table.attribute_list')."  al
            WHERE   al.attribute_list_id = $listId
                ";
        return $this->dbh->getOne($query);
    }

    function getContentAssocsByContentId($contentId, $typeId = null)
    {
        $tableName = 'content-content';
        $tableName = $this->dbh->quoteIdentifier($tableName);
        $constraint = '';
        if (!empty($typeId)) {
            $typeId = intval($typeId);
            $constraint = "
                INNER JOIN " . SGL_Config::get('table.content') . " AS c
                  ON c.content_id = cc.content_id_fk
                    AND c.content_type_id = $typeId";
        }
        $query = "
            SELECT cc.content_id_fk
            FROM   $tableName AS cc
            $constraint
            WHERE  cc.content_id_pk = " . intval($contentId);
        return $this->dbh->getCol($query);
    }

    function getParentAssocsByContentId($contentId, $typeId = null)
    {
        $tableName = 'content-content';
        $tableName = $this->dbh->quoteIdentifier($tableName);
        $constraint = '';
        if (!empty($typeId)) {
            $typeId = intval($typeId);
            $constraint = "
                INNER JOIN " . SGL_Config::get('table.content') . " AS c
                  ON c.content_id = cc.content_id_pk AND c.is_current = 1
                    AND c.content_type_id = $typeId";
        }
        $query = "
            SELECT cc.content_id_pk
            FROM   $tableName AS cc
            $constraint
            WHERE  cc.content_id_fk = " . intval($contentId);
        return $this->dbh->getCol($query);
    }

    /**
     * Returns hash of content types.
     *
     * @access  public
     * @return  array   hash of content types
     */
    function getContentTypes($retType = null, $orderBy = null)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        if (!empty($orderBy)) {
            $orderBy .= ' ORDER BY ' . $orderBy;
        }
        $query = "
            SELECT  content_type_id, name
            FROM    ".SGL_Config::get('table.content_type')."
	    $orderBy
            ";
        if (is_null($retType)) {
            $contentTypes = $this->dbh->getAssoc($query);
        } else {
            $contentTypes = $this->dbh->getAll($query);
        }
        return $contentTypes;
    }

    function getContentStatusList()
    {
        return array(
            SGL_CMS_STATUS_DELETED => 'deleted',
            SGL_CMS_STATUS_FOR_APPROVAL => 'for approval',
            SGL_CMS_STATUS_BEING_EDITED => 'being edited',
            SGL_CMS_STATUS_APPROVED => 'approved',
            SGL_CMS_STATUS_PUBLISHED => 'published',
            SGL_CMS_STATUS_ARCHIVED => 'archived',
        );
    }

    /**
     * Converts content type constants into equivalent strings.
     *
     * @access  public
     * @param   int     $errorCode  type ID
     * @return  string              text representing attribute type
     */
    function attrTypeConstantToString($const)
    {
        if (in_array($const, array_keys($this->aTypeConsts))) {
            return strtoupper($this->aTypeConsts[$const]);

        } else {
            return 'not found';
        }
    }

    function addContentType($name)
    {
        $id = $this->dbh->nextId('content_type');
        $query = "
            INSERT INTO " . SGL_Config::get('table.content_type') . "
                (content_type_id, name)
            VALUES ($id, ". $this->dbh->quoteSmart($name) . ")";
        $ok = $this->dbh->query($query);
        return (PEAR::isError($ok)) ? $ok : $id;
    }

    function addAttrib($contentTypeId, $name, $alias, $attrTypeId, $params = null)
    {
        if (is_array($params)) {
            $params = serialize($params);
        }
        $id = $this->dbh->nextId('attribute');
        $query = "
            INSERT INTO ".SGL_Config::get('table.attribute')."  (
                attribute_id,
                content_type_id,
                name,
                alias,
                attribute_type_id,
                params
            ) VALUES (
                $id,
                $contentTypeId,".
                $this->dbh->quote($name) .",".
                $this->dbh->quote($alias) .",
                $attrTypeId,".
                $this->dbh->quote($params) ."
            )";
        $ok = $this->dbh->query($query);
        if (PEAR::isError($ok)) {
            return $ok;
        } else {
            return $id;
        }
    }

    public function putContentToHistory($contentId, $langCode)
    {
        return $this->dbh->autoExecute(
            SGL_Config::get('table.content'),
            array('is_current' => 0),
            DB_AUTOQUERY_UPDATE,
            'content_id = ' . intval($contentId)
                . ' AND language_id = ' . $this->dbh->quoteSmart($langCode)
        );
    }

    function addContentMetaData($oContent, $newVersion = false, $newLang = false, $current = true)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        if ($current && ($newVersion || $newLang)) {
            $this->putContentToHistory($oContent->id, $oContent->langCode);
        }

        $id = ($newVersion || $newLang)
            ? $oContent->id
            : $this->dbh->nextId(SGL_Config::get('table.content'));
        $version    = $newVersion
            ? $this->getNextContentVersion($id, $oContent->langCode)
            : 1;
        $currentUser = SGL_Session::getUid();
        $dateCreated = (strlen($oContent->dateCreated))
            ? $oContent->dateCreated
            : SGL_Date::getTime(true);

        $query = "
            INSERT INTO " . SGL_Config::get('table.content') . " (
                content_id,
                version,
                is_current,
                language_id,
                content_type_id,
                status,
                name,
                created_by_id,
                updated_by_id,
                date_created,
                last_updated
            ) VALUES (
                $id,
                $version, ".
                intval($current) . "," .
                $this->dbh->quote($oContent->langCode) .",
                $oContent->typeId,
                $oContent->status,".
                $this->dbh->quote($oContent->name) .",".
                $this->dbh->quote($currentUser) .",".
                $this->dbh->quote($currentUser) .",".
                $this->dbh->quote($dateCreated) .",".
                $this->dbh->quote(SGL_Date::getTime(true))."
            )";
        $result = $this->dbh->query($query);
        if (PEAR::isError($result)) {
            return $result;
        } else {
            // update oContent meta info and return
            $oContent->id = $id;
            $oContent->version = $version;
            $oContent->isCurrent = intval($current);
            $oContent->createdById = $oContent->updatedById = $currentUser;
            $oContent->dateCreated = $dateCreated;
            $oContent->lastUpdated = SGL_Date::getTime(true);
            return $oContent;
        }
    }

    function saveContent($oContent, $newVersion = false, $newLang = false)
    {
        $type = $this->_getOperationType($oContent);

        //  first switch for SGL_Content processing
        switch ($type) {
        case SGL_CONTENT_INSERT:
            //  add system info
            $ok = $this->addContentMetaData($oContent);
            if (PEAR::isError($ok)) {
                return $ok;
            }
            break;

        case SGL_CONTENT_UPDATE:
            //  update system info
            if ($newVersion || $newLang) {
                $current = (bool) ($newLang || $newVersion);
                $ok = $this->addContentMetaData($oContent, $newVersion, $newLang, $current);
            } else {
                $ok = $this->updateContentMetaData($oContent);
            }
            if (PEAR::isError($ok)) {
                return $ok;
            }
            break;

        case SGL_CONTENTTYPE_INSERT:
            //  insert content type
            $contentTypeId = $this->addContentType($oContent->typeName);
            if (PEAR::isError($contentTypeId)) {
                return $contentTypeId;
            }
            $oContent->typeId = $contentTypeId;
            break;

        case SGL_CONTENTTYPE_UPDATE:
            //  update content type name
            $ok = $this->updateContentTypeName($oContent->typeId, $oContent->typeName);
            if (PEAR::isError($ok)) {
                return $ok;
            }
            break;
        }

        //  second switch for attribute processing
        switch ($type) {
        case SGL_CONTENT_INSERT:
        case SGL_CONTENT_UPDATE:
            foreach ($oContent->aAttribs as $k => $oAttrib) {
                $oAttrib->contentId = $oContent->id;
                $oAttrib->version   = $oContent->version;
                $oAttrib->langCode  = $oContent->langCode;
                $ok = $this->updateAttribData($oAttrib);
            }
            break;

        case SGL_CONTENTTYPE_INSERT:
        case SGL_CONTENTTYPE_UPDATE:
            //  add or insert attributes types
            foreach ($oContent->aAttribs as $k => $oAttrib) {
                //  serialize attribute params if exists
                if (!is_null($oAttrib->params) &&
                    ($oAttrib->typeId == SGL_CONTENT_ATTR_TYPE_LIST ||
                     $oAttrib->typeId == SGL_CONTENT_ATTR_TYPE_CHOICE ||
                     $oAttrib->typeId == SGL_CONTENT_ATTR_TYPE_RADIO)) {
                    $oAttrib->params = serialize($oAttrib->params);
                } else {
                    $oAttrib->params = "";
                }

                if (empty($oAttrib->id)) {
                    $attribId = $this->addAttrib($oContent->typeId, $oAttrib->name,
                        $oAttrib->alias, $oAttrib->typeId, $oAttrib->params);
                    $oContent->aAttribs[$k]->id = $attribId;
                } else {
                    $ok = $this->updateAttrib($oAttrib);
                }
            }
            break;
        }

        //  process classifiers if exist
        if (!empty($oContent->aClassifiers)) {
            if (array_key_exists('categories',$oContent->aClassifiers)) {
                $this->addCategoriesByContentId($oContent->id, $oContent->aClassifiers['categories']);
            }
            if (array_key_exists('tags',$oContent->aClassifiers)) {
                $this->addTagsByContentId($oContent->aClassifiers['tags'], $oContent->id, $oContent->getType());
            }
        }
        return $oContent;
    }

    function getExistingLanguagesByContentId($contentId)
    {
        $query = "
            SELECT  language_id
            FROM    " . SGL_Config::get('table.content') . "
            WHERE   content_id = $contentId
            AND     is_current = 1
            ORDER BY language_id ASC
        ";
        return $this->dbh->getCol($query);
    }

    function getCategoriesByContentId($contentId)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $query = "
            SELECT  category_id
            FROM    `".SGL_Config::get('table.content-category')."`
            WHERE   content_id = $contentId
                ";
        return $this->dbh->getCol($query);
    }

    function addCategoriesByContentId($contentId, $aCatIds)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        //  delete existing assocs
        $ok = $this->deleteCategoriesByContentId($contentId);

        //  and add new ones
        $sth = $this->dbh->prepare("
            INSERT INTO `".SGL_Config::get('table.content-category')."` (
                content_id,
                category_id
            ) VALUES (
                $contentId,
                ?
                )");
        foreach ($aCatIds as $catId) {
            $this->dbh->execute($sth, $catId);
        }
    }

    function deleteCategoriesByContentId($contentId)
    {
        $query = "
            DELETE FROM `".SGL_Config::get('table.content-category')."`
            WHERE content_id = $contentId";
        $this->dbh->query($query);
    }

    function addTagsByContentId($tags, $contentId, $contentTypeId = '')
    {
        require_once SGL_MOD_DIR . '/tag/lib/Tag.php';

        $oTag = new SGL_Tag();
        if (!$oTag->getTaggableByFkId($contentId,'cms_' . $contentTypeId)) {
            return false;
        }

        return $oTag->saveTags(SGL_Tag::parseTags($tags));
    }

    function getTagsByContentId($contentId, $contentTypeId = '')
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        require_once SGL_MOD_DIR . '/tag/lib/Tag.php';
        $oTag  = new SGL_Tag();

        if (!$oTag->getTaggableByFkId($contentId,'cms_' . $contentTypeId)) {
            return false;
        }

        return $oTag->getTags();
    }

    function associateContents($contentIdPk, $contentIdFk)
    {
        $tableName = '`' . SGL_Config::get('table.content-content') . '`';
        return $this->dbh->autoExecute($tableName, array(
            'content_id_pk' => $contentIdPk,
            'content_id_fk' => $contentIdFk
        ), DB_AUTOQUERY_INSERT);
    }

    function addContentAssocsByContentId($contentId, $aContentAssocIds)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        //  delete existing assocs
        $ok = $this->deleteContentAssocsByContentId($contentId);

        //  and add new ones
        $sth = $this->dbh->prepare("
            INSERT INTO `".SGL_Config::get('table.content-content')."` (
                content_id_pk,
                content_id_fk
            ) VALUES (
                $contentId,
                ?
                )");
        foreach ($aContentAssocIds as $assocId) {
            $this->dbh->execute($sth, $assocId);
        }
        return true;
    }

    function deleteContentAssocsByContentId($contentId)
    {
        $query = "
            DELETE FROM `".SGL_Config::get('table.content-content')."`
            WHERE content_id_pk = $contentId";
        return $this->dbh->query($query);
    }

    function deleteContentAssocByContentIdAndFkId($contentId, $fkId)
    {
        $tableName = '`' . SGL_Config::get('table.content-content') . '`';
        $query     = "
            DELETE FROM $tableName
            WHERE  content_id_pk = " . intval($contentId) . "
                   AND content_id_fk = " . intval($fkId) . "
        ";
        return $this->dbh->query($query);
    }

    function contentHasAssocs($contentId)
    {
        $query = "
            SELECT COUNT(content_id_fk)
            FROM `".SGL_Config::get('table.content-content')."`
            WHERE content_id_pk = $contentId";
        return $this->dbh->getOne($query);
    }

    function updateContentTypeName($contentTypeId, $newName)
    {
        $query = "
            UPDATE ".SGL_Config::get('table.content_type')."
            SET name = " . $this->dbh->quoteSmart($newName) . "
            WHERE  content_type_id=" . $contentTypeId;
        return $this->dbh->query($query);
    }

    function updateContentMetaData($oContent)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        // update oContent meta info
        $currentUser = SGL_Session::getUid();
        $oContent->updatedById = $currentUser;
        $oContent->lastUpdated = SGL_Date::getTime(true);

        $query = "
            UPDATE " . SGL_Config::get('table.content') . "
            SET     status = $oContent->status,
                    updated_by_id = $currentUser,
                    last_updated = " . $this->dbh->quote($oContent->lastUpdated) . ",
                    name = " . $this->dbh->quote($oContent->name) . "
            WHERE   content_id = $oContent->id
            AND     version = $oContent->version
            AND     language_id = " . $this->dbh->quoteSmart($oContent->langCode);
        $result = $this->dbh->query($query);
        if ($result instanceof PEAR_Error) {
            return $result;
        } else {
            return $oContent;
        }
    }

    function updateAttrib($oAttribute)
    {
        $query = "
            UPDATE " . SGL_Config::get('table.attribute') . " SET
                name = " . $this->dbh->quote($oAttribute->name) .",
                alias = " . $this->dbh->quote($oAttribute->alias) .",
                attribute_type_id = {$oAttribute->typeId},
                params = '{$oAttribute->params}'
            WHERE  attribute_id = {$oAttribute->id}";
        $ret = $this->dbh->query($query);
        return $ret;
    }

    function updateAttribData($oAttribute)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $query = "
            REPLACE INTO  " . SGL_Config::get('table.attribute_data') . "
                    (content_id, version, language_id, attribute_id, value)
            VALUES  (
                    $oAttribute->contentId,
                    $oAttribute->version, " .
                    $this->dbh->quote($oAttribute->langCode) . ",
                    $oAttribute->id, " .
                    $this->dbh->quote($oAttribute->value) . "
            )";
        $result = $this->dbh->query($query);
        if (PEAR::isError($result)) {
            return $result;
        } else {
            return $this->dbh->affectedRows();
        }
    }

    function _getOperationType($oContent)
    {
        if (!empty($oContent->id)) {
            $type = SGL_CONTENT_UPDATE;

        } else {
            if (!empty($oContent->typeId) && !empty($oContent->name)) {
                $type = SGL_CONTENT_INSERT;

            //  it's a content_type update if SGL_Content->typeId is set
            } elseif (!empty($oContent->typeId) && !empty($oContent->typeName)) {
                $type = SGL_CONTENTTYPE_UPDATE;

            //  it's a content_type insert if only SGL_Content->typeName is set
            } else {
                $type = SGL_CONTENTTYPE_INSERT;
            }
        }
        return $type;
    }

    function deleteContentTypeById($id)
    {
        $query = "
            DELETE FROM " . SGL_Config::get('table.content_type') . "
            WHERE content_type_id = $id";
        $this->dbh->query($query);
    }

    function deleteAttribsByContentTypeId($id)
    {
        $query = "
            DELETE FROM " . SGL_Config::get('table.attribute') . "
            WHERE content_type_id = $id";
        $this->dbh->query($query);
    }

    function deleteAttribById($id)
    {
        $query = "
            DELETE FROM " . SGL_Config::get('table.attribute') . "
            WHERE attribute_id = $id";
        return $this->dbh->query($query);
    }

    /**
     * Deletes content and its component parts.
     *
     * @access  public
     * @param   array     $aContentIds   Array of IDs to delete.
     * @return  void
     */
    function deleteContentById($aContentIds, $safe = true)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        // if safeDelete is enabled, just set content status to SGL_CMS_STATUS_DELETED,
        // don't actually delete
        $ok = false;
        if (is_array($aContentIds) && count($aContentIds)) {
            foreach ($aContentIds as $contentId) {
                if ($safe || $this->conf['site']['safeDelete']) {
                    $query = "
                        UPDATE " . SGL_Config::get('table.content') . "
                        SET status = " . SGL_CMS_STATUS_DELETED . "
                        WHERE content_id = $contentId
                    ";
                    $ok = $this->dbh->query($query);
                } else {
                    // delete assocs
                    $this->deleteContentAssocsByContentId($contentId);

                    $query = "
                        DELETE FROM " . SGL_Config::get('table.content') . "
                        WHERE   content_id = $contentId
                        ";
                    $ok = $this->dbh->query($query);
                }
            }
        }
        return $ok;
    }

    function contentNameExists($name, $id)
    {
        $checkOnId = (!empty($id))
            ? 'AND content_id <> ' . $id
            : '';
        $query = "
            SELECT content_id
            FROM   " . SGL_Config::get('table.content') . "
            WHERE  name = '{$this->dbh->escapeSimple($name)}'
            $checkOnId
        ";
        return (boolean)$this->dbh->getOne($query);
    }

    /**
     * Retrieve specific version of a content
     */
    function getContentByVersionId($versionId)
    {
        $query = "
            SELECT  c.content_id AS id,
                    c.version,
                    c.is_current,
                    c.language_id,
                    c.name AS content_name,
                    u.username,
                    c.created_by_id,
                    c.updated_by_id,
                    c.date_created,
                    c.last_updated,
                    c.content_type_id,
                    c.status,
                    ct.name AS content_type_name
            FROM    " . SGL_Config::get('table.content') . " c
            JOIN    " . SGL_Config::get('table.content_type') . " ct ON ct.content_type_id = c.content_type_id
            LEFT JOIN " . SGL_Config::get('table.user') . " u ON c.created_by_id = u.usr_id
            WHERE   c.version = $version
            ";
        $result = $this->dbh->query($query);
        if (!PEAR::isError($result)) {
            $ret = $result->fetchRow();
        } else {
            $ret = $result;
        }
        return $ret;
    }

    /**
     * Retrieve current version of a content or specific version if specified
     *
     * @param   int     $id         content id
     * @param   string  $langCode   language
     * @param   int     $version    version
     * @return  object  oContent data | PEAR_Error
     */
    function getContentById($id, $langCode = null, $version = null)
    {
        $versionConstraint = is_null($version)
            ? "AND c.is_current = 1"
            : "AND c.version = $version";
        $langCode = is_null($langCode)
            ? SGL::getCurrentLang()
            : $langCode;
        $query = "
            SELECT  c.content_id AS id,
                    c.version,
                    c.is_current,
                    c.language_id,
                    c.name AS content_name,
                    u.username,
                    c.created_by_id,
                    c.updated_by_id,
                    c.date_created,
                    c.last_updated,
                    c.content_type_id,
                    c.status,
                    ct.name AS content_type_name
            FROM    " . SGL_Config::get('table.content') . " c
            JOIN    " . SGL_Config::get('table.content_type') . " ct ON ct.content_type_id = c.content_type_id
            LEFT JOIN " . SGL_Config::get('table.user') . " u ON c.created_by_id = u.usr_id
            WHERE   c.content_id = $id
            $versionConstraint
            AND     c.language_id = " . $this->dbh->quoteSmart($langCode);
        $result = $this->dbh->query($query);
        if (!PEAR::isError($result)) {
            $ret = $result->fetchRow();
        } else {
            $ret = $result;
        }
        return $ret;
    }

    function getContentByName($name)
    {
        $query = "
            SELECT  c.content_id AS id,
                    c.version,
                    c.is_current,
                    c.language_id,
                    c.name AS content_name,
                    u.username,
                    c.created_by_id,
                    c.updated_by_id,
                    c.date_created,
                    c.last_updated,
                    c.content_type_id,
                    ct.name AS content_type_name
            FROM    " . SGL_Config::get('table.content') . " c
            JOIN    " . SGL_Config::get('table.content_type') . " ct ON ct.content_type_id = c.content_type_id
            LEFT JOIN " . SGL_Config::get('table.user') . " u ON c.created_by_id = u.usr_id
            WHERE   c.name = '$name'
            AND     c.is_current = 1
            ";
        $result = $this->dbh->query($query);
        if (!PEAR::isError($result)) {
            $ret = $result->fetchRow();
        } else {
            $ret = $result;
        }
        return $ret;
    }

    /**
     * Returns an array of content type attribute row objects.
     *
     * Data structure:
     *
     *      [0] => stdClass Object
     *          (
     *              [content_type_id] => 2
     *              [content_type_name] => Html Article
     *              [attr_id] => 7
     *              [attr_name] => title
     *              [attr_alias] => Title
     *              [attr_type_id] => 1
     *          )
     *
     *      [1] => stdClass Object
     *          (
     *              [content_type_id] => 2
     *              [content_type_name] => Html Article
     *              [attr_id] => 8
     *              [attr_name] => bodyHtml
     *              [attr_alias] => Body (HTML)
     *              [attr_type_id] => 3
     *          )
     *
     *
     * @param integer $contentTypeId
     * @return array
     */
    function getContentTypeAttribsById($contentTypeId = null)
    {
        $constraint = (is_null($contentTypeId))
            ? ''
            : "WHERE  ct.content_type_id = $contentTypeId";

        $query = "
            SELECT
                        ct.content_type_id,
                        ct.name AS content_type_name,
                        at.attribute_id AS attr_id,
                        at.name AS attr_name,
                        at.alias AS attr_alias,
                        at.params AS attr_params,
                        at.desc AS attr_desc,
                        at.attribute_type_id AS attr_type_id

            FROM        " . SGL_Config::get('table.content_type') . " ct
            LEFT JOIN   " . SGL_Config::get('table.attribute') . " at ON at.content_type_id = ct.content_type_id
            LEFT JOIN   " . SGL_Config::get('table.attribute_type') . " att ON att.attribute_type_id = at.attribute_type_id
            $constraint
            ORDER BY ct.name ASC
        ";
        return $this->dbh->getAll($query);
    }

    function getMediaById($mediaId = null)
    {
        $constraint = (is_null($mediaId)) ? '' : " AND d.media_id = $mediaId";
        $query = "
            SELECT
                media_id,
                d.file_type_id,
                d.name, d.file_name,
                file_size,
                mime_type,
                d.date_created,
                description,
                dt.name AS document_type_name,
                u.username AS document_added_by
            FROM
                " . SGL_Config::get('table.media') . " m,
                " . SGL_Config::get('table.file_type') . " mt,
                " . SGL_Config::get('table.user') . " u
            WHERE mt.file_type_id = m.file_type_id
            AND u.usr_id = m.added_by
            $constraint
            ORDER BY m.date_created DESC";
        return $this->dbh->getAll($query);
    }

    function getMediaHash()
    {
        $query = "
            SELECT
                media_id,
                m.name
            FROM
                " . SGL_Config::get('table.media') . " m
            ";
        return $this->dbh->getAssoc($query);
    }

    function getMediaTypeIdByName($name)
    {
        $query = "
            SELECT
                file_type_id
            FROM
                " . SGL_Config::get('table.file_type') . " ft
            WHERE name LIKE '%$name%'
            ";
        $ret = $this->dbh->getOne($query);
        return $ret;
    }

    function getMediaIdByCategoryId($categoryId)
    {
        $query = "
            SELECT
                media_id
            FROM
                `" . SGL_Config::get('table.category-media') . "`
            WHERE category_id = $categoryId
            ";
        $ret = $this->dbh->getOne($query);
        return $ret;
    }

    /**
     *  Array
     *  (
     *      [0] => stdClass Object
     *          (
     *              [content_id] => 1
     *              [name] => Test article 1
     *              [username] =>
     *              [created_by_id] => 71
     *              [date_created] => 2004-01-03 18:21:25
     *              [last_updated] => 2004-03-16 22:38:38
     *              [content_type_id] => 2
     *              [content_type_name] => Html Article
     *          )
     *
     *      [1] => stdClass Object
     *          (
     *              [content_id] => 2
     *              [name] => Test article 2
     *              [username] =>
     *              [created_by_id] => 71
     *              [date_created] => 2006-02-27 15:50:50
     *              [last_updated] => 2006-02-27 15:50:50
     *              [content_type_id] => 2
     *              [content_type_name] => Html Article
     *          )
     */

    /**
     * Returns query
     *
     */
    function buildSearchQuery($contentConstraint = null,
        $attribConstraint = null, $ordering = null, $limit = null,
        $catConstraint = null, $assocConstraint = null)
    {
        $query = "
            SELECT  c.content_id,
                    c.version,
                    c.is_current,
                    c.language_id,
                    c.name,
                    u.username,
                    c.created_by_id,
                    c.updated_by_id,
                    c.date_created,
                    c.last_updated,
                    c.content_type_id,
                    c.status,
                    ct.name AS content_type_name
            FROM    " . SGL_Config::get('table.content') . " c
            {$assocConstraint}
            JOIN    " . SGL_Config::get('table.content_type') . " ct ON ct.content_type_id = c.content_type_id
            JOIN    " . SGL_Config::get('table.attribute_data') . " ad ON ad.content_id = c.content_id AND ad.version = c.version AND ad.language_id = c.language_id
            JOIN    " . SGL_Config::get('table.attribute') . " at ON at.attribute_id = ad.attribute_id
            {$ordering['constraint']}
            {$attribConstraint['tables']}
            LEFT JOIN " . SGL_Config::get('table.user') . " u ON c.created_by_id = u.usr_id
            $catConstraint
            $contentConstraint
            {$attribConstraint['where']}
            GROUP BY c.content_id
            {$ordering['order']}
        ";
        if (is_array($limit) && count($limit)) {
            $query = $this->dbh->modifyLimitQuery($query, $limit['offset'], $limit['count']);
        }
        return $query;
    }

    /**
     * Returns matching contents given params
     *
     */
    function getMatchingContents($query)
    {
        $aRet = $this->dbh->getAll($query);
        return $aRet;
    }

    /**
     * Returns number of matching contents given params
     * Important: this query should be the exact copy of above in getMatchingContents
     *
     */
    function getNumberOfMatchingContents($query)
    {
        $res = $this->dbh->query($query);
        $numRecords = (int)$res->numRows();
        return $numRecords;
    }

    /**
     * Returns the content type id given its name.
     *
     * @param string $name
     * @return integer
     */
    function getContentTypeIdByName($name)
    {
        $query = "
            SELECT  content_type_id
            FROM    " . SGL_Config::get('table.content_type') . "
            WHERE   name = '$name'
        ";
        $id = $this->dbh->getOne($query);
        return $id;
    }

    /**
     * Method to execute a DB query and return an array of results
     * subject to table name, key field, and value field names.
     *
     * $aDbData = array(
     *     'listTable' => 'table',
     *     'listKey'   => 'key',
     *     'listValue' => 'value',
     * );
     *
     * @param array $aDbData
     * @return mixed array or false on failure
     */
    function getAttribListDbData($aDbData)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        if (!$aDbData['listTable'] || !$aDbData['listKey']
                || !$aDbData['listValue']) {
            return false;
        }

        $query = "
            SELECT
                {$aDbData['listKey']} AS k,
                {$aDbData['listValue']} AS v
            FROM
                {$aDbData['listTable']}";
        if (PEAR::isError($aRows = $this->dbh->getAssoc($query))) {
            return false;
        }
        return $aRows;
    }

    ///////////////////////////////////////////////////
    //                                               //
    //                    HELPERS                    //
    //                                               //
    ///////////////////////////////////////////////////

    /**
     * Returns true if a value has already been assigned to an attribute.
     *
     * This information is required in order to know whether to apply an
     * updateAttribData and addAttribData operation
     *
     * @param integer $attribId
     * @return integer
     */
    function _attribDataExistsForAttribId($attribId)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $query = "
            SELECT  attribute_data_id
            FROM    " . SGL_Config::get('table.attribute_data') . "
            WHERE   attribute_id = $attribId
                 ";
        $result = $this->dbh->getOne($query);
        return $result;
    }

    function _arrayOfAttribsHasAllIdsSet($aAttribs)
    {
        foreach ($aAttribs as $oAttrib) {
            if (empty($oAttrib->id)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Tests if content is of type uploadable
     *
     * @param integer $contentTypeId
     * @return boolean
     * @todo delete if not used
     */
    function _isContentTypeUploadable($contentTypeId)
    {
        $aContentTypeRows = $this->getContentTypeAttribsById($contentTypeId);
        $ret = false;
        foreach ($aContentTypeRows as $oRow) {
            if ($oRow->attr_type_id == SGL_CONTENT_ATTR_TYPE_FILE) {
                $ret = true;
                break;
            }
        }
        return $ret;
    }

    function remapDataRowsToContentObjects($aRows)
    {
        $aRet = array();
        $newObject = false;

        foreach ($aRows as $obj) {
            if (array_key_exists($obj->content_type_id, $aRet)) {
                $content = $aRet[$obj->content_type_id];
            } else {
                $content = new SGL_Content();
                $newObject = true;
            }
            $content->typeId = $obj->content_type_id;
            $content->typeName = $obj->content_type_name;
            //  Add attribute to content type, if data is not an empty row
            if (!empty($obj->attr_id)) {
                $oAttrib = new SGL_Attribute();
                $oAttrib->id = $obj->attr_id;
                $oAttrib->name = $obj->attr_name;
                $oAttrib->alias = $obj->attr_alias;
                $oAttrib->typeId = $obj->attr_type_id;
                // Need to be sure we have an (even empty) string to unserialize
                $oAttrib->params = unserialize((string) $obj->attr_params);
                $newObject
                    ? $content->aAttribs[$obj->attr_id] = $oAttrib
                    : $aRet[$obj->content_type_id]->aAttribs[$obj->attr_id] = $oAttrib;
            }
            if ($newObject) {
                $aRet[$obj->content_type_id] = $content;
            }
            $newObject = false;
            unset($content);
        }
        return $aRet;
    }

    function getVersionsByContentId($contentId, $langCode, $limit = 5)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $query = "
            SELECT  c.version,
                    c.is_current,
                    c.last_updated,
                    c.updated_by_id,
                    CONCAT_WS(' ', u.first_name, u.last_name) AS author
            FROM    " . SGL_Config::get('table.content') . " c
            JOIN    " . SGL_Config::get('table.user') . " u ON c.updated_by_id = u.usr_id
            WHERE   c.content_id = $contentId
            AND     c.language_id = " . $this->dbh->quoteSmart($langCode) . "
            ORDER BY version DESC
        ";
        $aRet = $this->dbh->getAll($this->dbh->modifyLimitQuery($query, 0, $limit));
        return $aRet;
    }

    function getNextContentVersion($contentId, $langCode)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $query = "
            SELECT  MAX(version)
            FROM    " . SGL_Config::get('table.content') . "
            WHERE   content_id = " . intval($contentId) . "
                    AND language_id = " . $this->dbh->quoteSmart($langCode) . "
        ";
        $version = $this->dbh->getOne($query);
        return intval($version) + 1;
    }

    function getAttributeListTypes($constraint = '')
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $query = "
            SELECT  attribute_list_id, name
            FROM    " . SGL_Config::get('table.attribute_list') . "
            $constraint
                 ";
        $result = $this->dbh->getAssoc($query);
        return $result;
    }

    public function getAttributeLists()
    {
        $tableName = SGL_Config::get('table.attribute_list');
        $query     = "
            SELECT    *
            FROM      $tableName
            ORDER BY  name ASC
        ";
        return $this->dbh->getAll($query);
    }

    function buildWhereConditions($aConstraints, $sepator = 'WHERE')
    {
        if (count($aConstraints)) {
            $aRet = array();
            foreach ($aConstraints as $constraint) {
                if (!empty($constraint)) {
                    $aRet[] = $constraint;
                }
            }
            if (count($aRet)) {
                $ret = 'WHERE ' . implode(' AND ', $aRet);
            } else {
                $ret = '';
            }
        } else {
            $ret = '';
        }
        return $ret;
    }
    /**
     * Get status hash.
     *
     * @return array
     */
    function getStatusTypes()
    {
        $aConstants = get_defined_constants(true);
        $aConstants = $aConstants['user'];
        $aStatusTypes = array();
        $prefix = 'SGL_CMS_STATUS_';
        foreach ($aConstants as $name => $value) {
            if (strpos($name, $prefix) !== false) {
                $statusName = substr($name, strlen($prefix));
                $statusName = strtolower(str_replace('_', ' ', $statusName));
                $statusName = ucfirst($statusName);
                $aStatusTypes[$value] = $statusName;

                $found = true;
                continue;
            }
            if (isset($found)) {
                break;
            }
        }
        return $aStatusTypes;
    }

    public function searchContactItemsByPattern($pattern, $constraint = '')
    {
        $pattern = $this->dbh->escapeSimple($pattern);
        // search in attributes
        $query = "
            SELECT    ct.name AS content_type,
                      c.content_id, COUNT(c.content_id) AS match_number
            FROM      content AS c, content_type AS ct, attribute_data AS ad
            WHERE     c.content_type_id = ct.content_type_id
                        AND c.content_id = ad.content_id
                        AND ad.value LIKE '%$pattern%'
                      $constraint
            GROUP BY  c.content_id, ct.name
            ORDER BY  ct.name, match_number DESC, c.content_id
        ";
        $aResult = $this->dbh->getAll($query);
        // search in names
        $query = "
            SELECT c.content_id, ct.name AS content_type
            FROM   content AS c, content_type AS ct
            WHERE  c.content_type_id = ct.content_type_id
                     AND c.name LIKE '%$pattern%'
                   $constraint
        ";
        $aResultNames = $this->dbh->getAssoc($query);

        $aContentTypes = array();
        $aData = array(
           'name'  => 'matchNumber',
           'alias' => 'Match number'
        );
        do {
            foreach ($aResult as $oRow) {
                if (array_key_exists($oRow->content_id, $aResultNames)) {
                    unset($aResultNames[$oRow->content_id]);
                    $oRow->match_number++;
                }
                $oAttribute = new SGL_Attribute($aData);
                $oAttribute->set($oRow->match_number);
                $oContent = SGL_Content::getById($oRow->content_id);
                $oContent->addAttribute($oAttribute);
                $aContentTypes[$oRow->content_type][] = $oContent;
            }
            $aResult = array();
            foreach ($aResultNames as $contentId => $contentType) {
                $oRow               = new stdClass();
                $oRow->content_id   = $contentId;
                $oRow->match_number = 1;
                $oRow->content_type = $contentType;
                $aResult[] = clone($oRow);
                unset($aResultNames[$contentId]);
            }
        } while (!empty($aResult));
        return $aContentTypes;
    }

    // -----------------------
    // --- Attribute lists ---
    // -----------------------

    /**
     * Add attribute list.
     *
     * @param string $name
     * @param array $aParams
     *
     * @return boolean
     */
    public function addAttribList($name, array $aParams = array())
    {
        $tableName = SGL_Config::get('table.attribute_list');

        $aFields['attribute_list_id'] = $this->dbh->nextId($tableName);
        $aFields['name']              = $name;
        $aFields['params']            = serialize($aParams);

        $ok = $this->dbh->autoExecute($tableName, $aFields, DB_AUTOQUERY_INSERT);
        if (!PEAR::isError($ok)) {
            $ok = $aFields['attribute_list_id'];
        }
        return $ok;
    }

    /**
     * Update attribute list by ID.
     *
     * @param integer $attribListId
     * @param array $aFields
     *
     * @return boolean
     *
     * @todo check for allowed fields
     */
    public function updateAttribListById($attribListId, array $aFields)
    {
        $tableName = SGL_Config::get('table.attribute_list');
        $where     = 'attribute_list_id = ' . intval($attribListId);

        if (isset($aFields['params'])) {
            $aFields['params'] = serialize($aFields['params']);
        }

        $ok = $this->dbh->autoExecute($tableName, $aFields, DB_AUTOQUERY_UPDATE,
            $where);
        return $ok;
    }

    /**
     * Delete attribute list by ID.
     *
     * @param integer $attribListId
     *
     * @return boolean
     */
    public function deleteAttribListById($attribListId)
    {
        $tableName = SGL_Config::get('table.attribute_list');
        $query     = "
            DELETE FROM $tableName
            WHERE  attribute_list_id = " . intval($attribListId) . "
        ";
        return $this->dbh->query($query);
    }

    /**
     * Get attribute list object by ID.
     *
     * @param integet $attribListId
     *
     * @return object
     */
    public function getAttribListById($attribListId)
    {
        $tableName = SGL_Config::get('table.attribute_list');
        $query     = "
            SELECT *
            FROM   $tableName
            WHERE  attribute_list_id = " . intval($attribListId) . "
        ";
        return $this->dbh->getRow($query);
    }
}
?>