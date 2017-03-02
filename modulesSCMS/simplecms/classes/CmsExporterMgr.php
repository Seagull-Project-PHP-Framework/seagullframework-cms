<?php

require_once SGL_MOD_DIR . '/cms/classes/CmsDAO.php';
require_once SGL_MOD_DIR . '/simplecms/classes/SimpleCmsDAO.php';

/**
 * CMS SQL exporter.
 *
 * @package simplecms
 * @author Dmitri Lakachauskis <lakiboy83@gmail.com>
 */
class CmsExporterMgr extends SGL_Manager
{
    public function __construct()
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);
        parent::SGL_Manager();

        $this->pageTitle = 'SQL Exporter';
        $this->template  = 'cmsexporterList.html';

        $this->_aActionsMapping = array(
            'list'   => array('list'),
            'export' => array('export'),
        );
        $this->da = new SGL_Delegator();
        $this->da->add(CmsDAO::singleton());
        $this->da->add(SimpleCMSDAO::singleton());
    }

    public function validate(SGL_Request $req, SGL_Registry $input)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $this->validated       = true;
        $input->pageTitle      = $this->pageTitle;
        $input->masterTemplate = $this->masterTemplate;
        $input->template       = $this->template;
        $input->action         = $req->get('action')
            ? $req->get('action') : 'list';

        $input->contentTypeId = $req->get('contentTypeId');
        $input->config        = $req->get('config');
    }

    public function display(SGL_Output $output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $output->aContentTypes = $this->da->getContentTypes();
        $output->addJavascriptFile('simplecms/js/CmsExporter.js');
        $output->addOnloadEvent('SimpleCms.Exporter.init()');
    }

    public function _cmd_list(SGL_Registry $input, SGL_Output $output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        // by default we want to have data and {SGL_NEXT_ID}
        $output->config['replace_ids'] = true;
        $output->config['data']        = true;
    }

    public function _cmd_export(SGL_Registry $input, SGL_Output $output)
    {
        $output->sglExport = $this->_export($input->contentTypeId, $input->config);
    }

    // ---------------
    // --- Private ---
    // ---------------

    /**
     * Remove non-alphabet chars from string.
     *
     * @param string $string
     *
     * @return string
     */
    protected static function _sanitize($string)
    {
        return preg_replace('/[^a-z]/i', '', $string);
    }

    /**
     * Get valid SQL variable name for content type ID value based on it's name.
     *
     * @param string $contentTypeName
     *
     * @return string
     */
    protected static function _getContentTypeSqlVarName($contentTypeName)
    {
        $ret = SGL_Inflector::camelise($contentTypeName);
        $ret = self::_sanitize($ret);
        $ret = 'contentType' . ucfirst($ret);
        return '@' . $ret;
    }

    /**
     * Creates SQL export for specified content type IDs taking into account
     * $aConfig options.
     *
     * @param mixed $contentTypeId  content type ID or array of IDs
     * @param array $aConfig        export options
     *
     * @return string
     */
    protected function _export($contentTypeId = null, array $aConfig = array())
    {
        $aContentTypes = $this->da->getContentTypes();

        // identify content type IDs to process
        if (empty($contentTypeId)) {
            $aContentTypeIds = array_keys($aContentTypes);
        } elseif (!is_array($contentTypeId)) {
            $aContentTypeIds = array($contentTypeId);
        } else {
            $aContentTypeIds = $contentTypeId;
        }

        $ret = '';
        foreach ($aContentTypeIds as $typeId) {

            // content type object
            $oContentType       = new stdClass();
            $oContentType->id   = $typeId;
            $oContentType->name = $aContentTypes[$typeId];

            // content type attributes
            $aAttributes = $this->da->getContentTypeAttribsById($typeId);

            // add content types definition to export
            $ret .= $this->_exportContentTypeDefinition($oContentType,
                $aAttributes, $aConfig['replace_ids']);

            // add contents data to export
            if ($aConfig['data']) {
                $ret .= $this->_exportContentsData($oContentType,
                    $aAttributes, $aConfig);
            }
        }

        // add all attribute lists to export
        if ($aConfig['attrib_lists']) {
            $ret .= $this->_exportAttributeLists();
        }

        return $ret;
    }

    /**
     * Export content type definition.
     *
     * @param object $oCt          content type object
     * @param array $aAttributes   collection of attributes
     * @param boolean $replaceIds  replace real IDs with 'SGL_NEXT_ID'
     *
     * @return string
     */
    protected function _exportContentTypeDefinition($oCt,
        array $aAttributes = array(), $replaceIds = false)
    {
        $ctId   = $replaceIds ? '{SGL_NEXT_ID}' : $oCt->id;
        $ctName = $this->dbh->escapeSimple($oCt->name);

        $ret = <<< SQL

--
-- "{$oCt->name}" content type
--

-- insert content type
INSERT INTO `content_type` VALUES ($ctId, '$ctName');


SQL;
        $ctVar = self::_getContentTypeSqlVarName($oCt->name);

        $ret .= <<< SQL
-- get content type ID
SELECT $ctVar := content_type_id FROM `content_type` WHERE name = '$ctName';

-- insert attributes

SQL;

        foreach ($aAttributes as $oAttribute) {
            $attrId     = $replaceIds ? '{SGL_NEXT_ID}' : $oAttribute->attr_id;
            $attrTypeId = $this->dbh->quoteSmart($oAttribute->attr_type_id);
            $attrName   = $this->dbh->quoteSmart($oAttribute->attr_name);
            $attrAlias  = $this->dbh->quoteSmart($oAttribute->attr_alias);
            $attrDesc   = $this->dbh->quoteSmart($oAttribute->attr_desc);
            $attrParams = $this->dbh->quoteSmart($oAttribute->attr_params);

            $ret .= <<< SQL
INSERT INTO `attribute` VALUES ($attrId, $attrTypeId, $ctVar, $attrName, $attrAlias, $attrDesc, $attrParams);

SQL;
        }
        $ret .= "\n";

        return $ret;
    }

    /**
     * Export attribute lists definition.
     *
     * @return string
     */
    protected function _exportAttributeLists()
    {
        $aAttributeLists = $this->da->getAttributeLists();

        $ret = '';
        foreach ($aAttributeLists as $oAttrList) {
            $listId     = $oAttrList->attribute_list_id;
            $listName   = $this->dbh->quoteSmart($oAttrList->name);
            $listParams = $this->dbh->quoteSmart($oAttrList->params);

            $ret .= <<< SQL
INSERT INTO `attribute_list` VALUES ($listId, $listName, $listParams);

SQL;
        }
        if ($ret) {
            $ret = "\n--\n-- Attribute lists\n--\n\n" . $ret . "\n";
        }
        return $ret;
    }

    /**
     * Export content items.
     *
     * @param object $oCt         content type object
     * @param array $aAttributes  collection of content type attributes
     * @param array $aConfig      config options
     *
     * @return string
     */
    protected function _exportContentsData(object $oCt, array $aAttributes,
        array $aConfig)
    {
        $aContents = $this->da->getContentsByContentTypeId($oCt->id);
        $ret       = '';

        if (!empty($aContents)) {

            // content types attributes
            $ctVar = self::_getContentTypeSqlVarName($oCt->name);
            $ret  .= "-- get attribute IDs\n";
            foreach ($aAttributes as $oAttribute) {
                $attrVar  = $ctVar . $oAttribute->attr_id;
                $attrName = $this->dbh->escapeSimple($oAttribute->attr_name);
                $ret .= <<< SQL
SELECT $attrVar := attribute_id FROM `attribute` WHERE content_type_id = $ctVar AND name = '$attrName';

SQL;
            }
            $ret .= "\n";

            $currentContentId = null;
            $processedContentId = null;

            // content records + attribute data
            foreach ($aContents as $oContent) {
                if ($oContent->content_id != $currentContentId) {
                    $contentId = $aConfig['replace_ids']
                        ? '{SGL_NEXT_ID}' : $oContent->content_id;
                } else {
                    $contentId = '@content' . $oContent->content_id;
                }

                $contentVersion    = $this->dbh->quoteSmart($oContent->version);
                $contentIsCurrent  = $this->dbh->quoteSmart($oContent->is_current);
                $contentLang       = $this->dbh->quoteSmart($oContent->language_id);
                $contentStatus     = $this->dbh->quoteSmart($oContent->status);
                $contentName       = $this->dbh->quoteSmart($oContent->name);
                $contentCreatedId  = $this->dbh->quoteSmart($oContent->created_by_id);
                $contentModifiedId = $this->dbh->quoteSmart($oContent->updated_by_id);
                $contentCreatedDt  = $this->dbh->quoteSmart($oContent->date_created);
                $contentModifiedDt = $this->dbh->quoteSmart($oContent->last_updated);

                $ret .= <<< SQL
-- insert content
INSERT INTO `content` VALUES ($contentId, $contentVersion, $contentIsCurrent, $contentLang, $ctVar, $contentStatus, $contentName, $contentCreatedId, $contentModifiedId, $contentCreatedDt, $contentModifiedDt);


SQL;
                if ($oContent->content_id != $currentContentId) {
                    $currentContentId = $oContent->content_id;

                    $ret .= <<< SQL
-- get content ID
SELECT @content{$currentContentId} := content_id FROM `content` WHERE name = $contentName AND content_type_id = $ctVar;


SQL;
                }

                // add attributes data
                $aAttributesData = $this->da->getAttribDataByContentId(
                    $oContent->content_id, $oContent->version, $oContent->language_id);
                foreach ($aAttributesData as $oAttributeData) {
                    $attrDataId     = $ctVar . $oAttributeData->attribute_id;
                    $attrDataValue  = $this->dbh->quoteSmart($oAttributeData->value);
                    $attrDataParams = $this->dbh->quoteSmart($oAttributeData->params);
//                    $attrDataValue  = trim(preg_replace('/\s+/', ' ', $attrDataValue));

                    $ret .= <<< SQL
INSERT INTO `attribute_data` VALUES (@content{$currentContentId}, $contentVersion, $contentLang, $attrDataId, $attrDataValue, $attrDataParams);

SQL;
                }
                $ret .= "\n";

                // add categories
                if ($aConfig['categories']
                    && $processedContentId !== $oContent->content_id)
                {
                    $aCats = $this->da->getCategoryIdsByContentId($oContent->content_id);
                    if (!empty($aCats)) {
                        $ret .= <<< SQL
-- insert categories

SQL;
                        foreach ($aCats as $categoryId) {
                            $categoryId = intval($categoryId);
                            $ret .= <<< SQL
INSERT INTO `content-category` VALUES (@content{$currentContentId}, $categoryId);

SQL;
                        }
                        $ret .= "\n";
                    }
                    // remeber current processed category ID
                    $processedContentId = $oContent->content_id;
                }
            }
        }
        return $ret;
    }

    /*
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
    */
}
?>