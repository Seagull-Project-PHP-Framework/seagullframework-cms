<?php

require_once dirname(__FILE__) . '/CmsDAO.php';

/**
 * Attribute list manager.
 *
 * @package    seagull
 * @subpackage cms
 * @author     Dmitri Lakachauskis <lakiboy83@gmail.com>
 */
class AttribListMgr extends SGL_Manager
{
    function __construct()
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);
        parent::SGL_Manager();

        $this->pageTitle = 'Attribute List Manager';
        $this->template  = 'attribListList.html';

        $this->_aActionsMapping = array(
            'list' => array('list'),
        );

        $this->da = &CmsDAO::singleton();
    }

    function validate($req, &$input)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $this->validated       = true;
        $input->pageTitle      = $this->pageTitle;
        $input->template       = $this->template;
        $input->masterTemplate = 'masterNoCols.html';
        $input->action         = $req->get('action')
            ? $req->get('action') : 'list';
    }

    function display(&$output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $output->addJavascriptFile(array(
            'js/jquery/jquery.js',
            'js/jquery/plugins/jquery.form.js',
            'js/jquery/plugins/jquery.selectboxes.js',
            'js/jquery/plugins/ui/ui.core.js',
            'js/jquery/plugins/ui/ui.accordion.js',
            'cms/js/string.js',
            'cms/js/cms.js',
            'cms/js/cms_contentAttribute.js'
        ), $optimize = false);
    }

    function _cmd_list(&$input, &$output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $constraint = ' ORDER BY name ';
        $aAttribListTypes = $aTypes = $this->da->getAttributeListTypes($constraint);
        foreach ($aAttribListTypes as $listId => $listName) {
            $params = $this->da->getAttribListParamsByListId($listId);
            $data   = $this->_getDataFromParamString($params);
            $aAttribListTypes[$listId] = array(
                'list_id'    => $listId,
                'name'       => $listName,
                'params'     => $params,
                'fields'     => $data,
                'fieldCount' => count($data)
            );
        }
        $output->aAttribListTypes = $aTypes;
        $output->aAttribLists     = $aAttribListTypes;
    }

    function _getDataFromParamString($paramString)
    {
        $fields = unserialize($paramString);
        // if serialized data are corrupted null is returned
        if (empty($fields) && !is_array($fields)) {
            $fields = array();
        }
        $data = array();
        if (isset($fields['data-inline'])) {
            $data = $fields['data-inline'];
        } elseif (isset($fields['data'])) {
            $data = $fields['data'];
        }
        return $data;
    }
}
?>