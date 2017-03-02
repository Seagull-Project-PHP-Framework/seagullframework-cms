<?php

require_once SGL_MOD_DIR . '/cms/classes/CmsDAO.php';
require_once SGL_MOD_DIR . '/simplecms/classes/SimpleCmsDAO.php';

/**
 * @package simplecms
 *
 * @author Dmitri Lakachauskis <lakiboy83@gmail.com>
 */
class CmsContentTypeMgr extends SGL_Manager
{
    public function __construct()
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);
        parent::SGL_Manager();

        $this->pageTitle      = 'Content Type Manager';
        $this->masterTemplate = 'master.html';
        $this->template       = 'cmscontenttypeList.html';

        $this->_aActionsMapping = array(
            'list' => array('list')
        );

        $this->da = new SGL_Delegator();
        $this->da->add(SimpleCmsDAO::singleton());
        $this->da->add(CmsDAO::singleton());
    }

    public function validate(SGL_Request $req, SGL_Registry $input)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $this->validated       = true;
        $input->pageTitle      = $this->pageTitle;
        $input->template       = $this->template;
        $input->masterTemplate = $this->masterTemplate;
        $input->action         = $req->get('action')
            ? $req->get('action') : 'list';
    }

    public function _cmd_list(SGL_Registry $input, SGL_Output $output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

//        $output->aContentTypes = SGL_Finder::factory('contenttype')
//            ->retrieve();
        $aContentTypes = $this->da->getContentTypes(null, 'name');
        $aStats        = array();
        foreach ($aContentTypes as $contentTypeId => $contentTypeName) {
            $stats = $this->da->getContentsCount(
                $userId = null, $contentTypeId);
            $aStats[$contentTypeId] = array(
                'name'  => $contentTypeName,
                'total' => $stats
            );
        }

        /*
        Array
        (
            [1] => Text
            [2] => Large text
            [3] => Rich text
            [4] => Integer
            [5] => Float
            [6] => Url
            [7] => File
            [8] => Checkbox
            [9] => Date
            [10] => Combo
            [11] => Radio
        )
        */
        $aConsts = $this->da->getAttribTypeConstants();
        // define constants
        foreach ($aConsts as $v => $name) {
            $name = strtoupper(str_replace(' ', '', $name));
            $output->exportJsVar('ATTR_TYPE_' . $name, $v);
        }

        $output->aStats        = $aStats;
        $output->aContentTypes = $aContentTypes;
        $output->addJavascriptFile(array(
            'js/jquery/plugins/jquery.form.js',
            'simplecms/js/CmsContentType.js'
        ));
        $output->addOnLoadEvent('SimpleCms.ContentType.init()');
    }
}
?>