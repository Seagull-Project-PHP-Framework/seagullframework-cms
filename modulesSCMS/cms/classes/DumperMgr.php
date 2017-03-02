<?php

require_once SGL_MOD_DIR . '/cms/classes/CmsDAO.php';

/**
 * SQL dumper.
 *
 * @package    seagull
 * @subpackage cms
 * @author     Dmitri Lakachauskis <lakiboy83@gmail.com>
 */
class DumperMgr extends SGL_Manager
{
    function __construct()
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);
        parent::SGL_Manager();

        $this->pageTitle = 'SQL dumper';
        $this->template  = 'dumperList.html';
        $this->masterLayout = 'layout-navtop-1col.css';

        $this->_aActionsMapping = array(
            'list' => array('list'),
            'dump' => array('dump'),
        );
        $this->da = &CmsDAO::singleton();
    }

    function validate($req, &$input)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $this->validated       = true;
        $input->error          = array();
        $input->pageTitle      = $this->pageTitle;
        $input->masterLayout   = $this->masterLayout;
        $input->template       = $this->template;
        $input->action         = $req->get('action') ? $req->get('action') : 'list';

        $input->contentTypeId  = $req->get('contentTypeId');
        $input->replaceIds     = $req->get('replaceIds') ?
            $req->get('replaceIds') : SGL_CMS_ATTRIB_VALUE_NO;
        $input->includeData    = $req->get('includeData');
        $input->includeLinks   = $req->get('includeLinks');
        $input->includeCats    = $req->get('includeCats');
        $input->includeAttributeLists   = $req->get('includeAttributeLists');
    }

    function display(&$output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $output->aContentTypes = $this->da->getContentTypes();
    }

    function _cmd_list(&$input, &$output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);
    }

    function _cmd_dump(&$input, &$output)
    {
        $output->sqlDump = $this->_dump($input->contentTypeId,
            $input->includeData, $input->includeLinks, $input->includeCats,
            $input->replaceIds,$input->includeAttributeLists);
    }

    function _dump($contentTypeId = null, $includeData = true,
            $includeLinks = true, $includeCats = true, $replaceIds = false, $includeAttribList = false)
    {
        $aContentTypes = $this->da->getContentTypes();
        if (empty($contentTypeId)) {
            $aContentTypeIds = array_keys($aContentTypes);
        } elseif (!is_array($contentTypeId)) {
            $aContentTypeIds = array($contentTypeId);
        } else {
            $aContentTypeIds = $contentTypeId;
        }
        $sql = '';
        foreach ($aContentTypeIds as $typeId) {
            // content type object
            $oContentType       = new stdClass();
            $oContentType->id   = $typeId;
            $oContentType->name = $aContentTypes[$typeId];
            // content type attributes
            $aAttributes = $this->da->getContentTypeAttribsById($typeId);
            // dump content type
            $sql .= $this->_dumpContentTypeId($oContentType, $replaceIds);
            $sql .= $this->_dumpContentTypeIdAttributes($oContentType,
                $aAttributes, $replaceIds);

            // dump data
            if ($includeData) {
                $sql .= $this->_dumpContents($oContentType, $aAttributes,
                    $includeLinks, $includeCats, $replaceIds);
            }
        }
        // dump attribute lists
        if ($includeAttribList) {
            $sql .= $this->_dumpAttributeLists($aContentTypeIds);
        }

        return $sql;
    }

    function _stripNonAplphaChars($string)
    {
        $alpha = range('a', 'z');
        $ret   = '';
        for ($i = 0, $count = strlen($string); $i < $count; $i++) {
            if (!in_array(strtolower($string[$i]), $alpha)) {
                continue;
            }
            $ret .= $string[$i];
        }
        return $ret;
    }

    function _getParentContents($contentId)
    {
        $tableName = $this->conf['table']['content-content'];
        $tableName = $this->dbh->quoteIdentifier($tableName);
        $query = "
            SELECT  content_id_pk
            FROM    $tableName
            WHERE   content_id_fk = $contentId
        ";
        return $this->dbh->getCol($query);
    }

    function _getAttributeLists($aContentTypeIds)
    {
        // first extract id`s of attrib lists
        $tableName = $this->conf['table']['attribute'];
        $tableName = $this->dbh->quoteIdentifier($tableName);
        $filter = implode(',',$aContentTypeIds);

        $query = "
            SELECT *
            FROM $tableName
            WHERE content_type_id IN ({$filter})
        ";

        $aResult = $this->dbh->getAll($query);

        $aAttribListId = array();
        foreach ($aResult as $oAttribute) {
        	if (strlen($oAttribute->params)) {
        	    $attributeParam = unserialize($oAttribute->params);
        	    if (array_key_exists('attributeListId',$attributeParam)
        	       && is_numeric($attributeParam['attributeListId'])
        	       && !in_array($attributeParam['attributeListId'],$aAttribListId)) {
                    $aAttribListId[] = $attributeParam['attributeListId'];
        	    }
        	}
        }

        if (empty($aAttribListId)) {
            return $aAttribListId;
        }

        $tableName = $this->conf['table']['attribute_list'];
        $tableName = $this->dbh->quoteIdentifier($tableName);
        $filter = implode(',',$aAttribListId);


        $query = "
            SELECT  *
            FROM    $tableName
            WHERE attribute_list_id IN ({$filter})
        ";

        return $this->dbh->getAll($query);
    }

    function _getCategoriesByContent($contentId)
    {
        $tableName = $this->conf['table']['content-category'];
        $tableName = $this->dbh->quoteIdentifier($tableName);
        $query = "
            SELECT  category_id
            FROM    $tableName
            WHERE   content_id = $contentId
        ";
        return $this->dbh->getCol($query);
    }

    /**
     * Dump content type definition.
     */
    function _dumpContentTypeId($oContentType, $replaceIds = SGL_CMS_ATTRIB_VALUE_NO)
    {
        if ($replaceIds == SGL_CMS_ATTRIB_VALUE_NO) {
            $typeId = $oContentType->id;
        } else {
            $typeId = '{SGL_NEXT_ID}';
        }
        return <<< SQL

--
-- "{$oContentType->name}" content type
--

-- insert content type
INSERT INTO `content_type` VALUES ($typeId, '{$oContentType->name}');


SQL;
    }

    /**
     * Dump attributes definition.
     */
    function _dumpContentTypeIdAttributes($oContentType, $aAttributes,
            $replaceIds = SGL_CMS_ATTRIB_VALUE_NO)
    {
        $typeId = SGL_Inflector::camelise($oContentType->name);
        $typeId = $this->_stripNonAplphaChars('contentTypeId' . ucfirst($typeId));
        $sql    = <<< SQL
-- get content type id
SELECT @{$typeId} := content_type_id FROM `content_type` WHERE name = '{$oContentType->name}';

-- insert attributes

SQL;
        foreach ($aAttributes as $oAttribute) {
            if ($replaceIds == SGL_CMS_ATTRIB_VALUE_NO) {
                $attrId = $oAttribute->attr_id;
            } else {
                $attrId = '{SGL_NEXT_ID}';
            }
            $sql .= <<< SQL
INSERT INTO `attribute` VALUES ($attrId, {$oAttribute->attr_type_id}, @{$typeId}, '{$oAttribute->attr_name}', '{$oAttribute->attr_alias}', '{$oAttribute->attr_desc}', '{$oAttribute->attr_params}');

SQL;
        }
        $sql .= "\n";
        return $sql;
    }

    function _dumpContents($oContentType, $aAttributes, $dumpLinks = true,
            $dumpCats = true, $replaceIds = SGL_CMS_ATTRIB_VALUE_NO)
    {
        static $lastContentId;
        $constraint = 'SELECT *
                       FROM ' . SGL_Config::get('table.content') . ' c
                       WHERE c.content_type_id = ' . $oContentType->id;
        $aContents  = $this->da->getMatchingContents($constraint);

        if (!is_array($aContents) || empty($aContents)) {
            return '';
        }

        $sql    = "-- get attribute ids\n";
        $typeId = SGL_Inflector::camelise($oContentType->name);
        $typeId = $this->_stripNonAplphaChars('contentTypeId' . ucfirst($typeId));
        foreach ($aAttributes as $oAttribute) {
            $attrId = $typeId . $oAttribute->attr_id;
            $sql .= <<< SQL
SELECT @{$attrId} := attribute_id FROM `attribute` WHERE content_type_id = @{$typeId} AND name = '{$oAttribute->attr_name}';

SQL;
        }
        $sql .= "\n";

        foreach ($aContents as $oContent) {
            if ($replaceIds == SGL_CMS_ATTRIB_VALUE_NO) {
                $contentId = $oContent->content_id;
            } else {
                if (is_null($lastContentId) || $lastContentId != $oContent->content_id) {
                    $contentId = '{SGL_NEXT_ID}';
                    $lastContentId = $oContent->content_id;
                } else {
                    $contentId = '@contentId';
                }
            }
            // ensure addslashes is performed once on $content->name
            $oContent->name = addslashes(stripslashes($oContent->name));
            $sql .= <<< END
-- insert content
INSERT INTO `content` VALUES ($contentId, {$oContent->version}, {$oContent->is_current}, '{$oContent->language_id}', @{$typeId}, {$oContent->status}, '{$oContent->name}', {$oContent->created_by_id}, {$oContent->updated_by_id}, '{$oContent->date_created}', '{$oContent->last_updated}');

-- get content id, version and language
SELECT @contentId := MAX(content_id) FROM content;
SELECT @version := version FROM `content` WHERE content_id = @contentId;
SELECT @languageId := language_id FROM `content` WHERE content_id = @contentId;

-- insert attribute data

END;
            $langId = SGL_Translation3::getDefaultLangCode();
            $aAttributesData = $this->da->getAttribDataByContentId($oContent->content_id,
                $oContent->version, $langId);
            foreach ($aAttributesData as $oAttributeData) {
                $attrId = $typeId . $oAttributeData->attribute_id;
                $oAttributeData->value = (!get_magic_quotes_gpc())
                    ? addslashes(stripslashes($oAttributeData->value))
                    : $oAttributeData->value;
                $value  = trim(preg_replace('/\s+/', ' ', $oAttributeData->value));
                $sql   .= <<< SQL
INSERT INTO `attribute_data` VALUES (@contentId, @version, @languageId, @{$attrId}, '{$value}', '{$oAttributeData->params}');

SQL;
            }
            $sql .= "\n";
            if ($dumpLinks) {
                $sql .= $this->_dumpContentLinks($oContent->content_id);
            }
            if ($dumpCats) {
                $sql .= $this->_dumpContentCategories($oContent->content_id);
            }
        }
        $sql .= "\n";
        return $sql;
    }

    function _dumpContentLinks($contentId)
    {
        $aContents = $this->_getParentContents($contentId);
        if (empty($aContents)) {
            return '';
        }
        $sql = "-- assign content\n";
        foreach ($aContents as $parentId) {
            $sql .= <<< SQL
INSERT INTO `content-content` VALUES ({$parentId}, @contentId);

SQL;
        }
        $sql .= "\n";
        return $sql;
    }

    function _dumpAttributeLists($aContentTypeIds)
    {
        $aAttributeLists = $this->_getAttributeLists($aContentTypeIds);
        if (empty($aAttributeLists)) {
            return '';
        }
        $sql = "-- insert attribute lists\n";
        foreach ($aAttributeLists as $attributeList) {
            $sql .= <<< SQL
INSERT INTO `attribute_list` VALUES ($attributeList->attribute_list_id, '{$attributeList->name}', '{$attributeList->params}');

SQL;
        }
        $sql .= "\n";
        return $sql;
    }



    function _dumpContentCategories($contentId)
    {
        $aCategories = $this->_getCategoriesByContent($contentId);
        if (empty($aCategories)) {
            return '';
        }
        $sql = "-- assign categories\n";
        foreach ($aCategories as $catId) {
            $sql .= <<< SQL
INSERT INTO `content-category` VALUES (@contentId, {$catId});

SQL;
        }
        $sql .= "\n";
        return $sql;
    }
}
?>