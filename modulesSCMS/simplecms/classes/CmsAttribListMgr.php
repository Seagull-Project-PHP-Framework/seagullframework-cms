<?php

/**
 * @package simplecms
 *
 * @author Dmitri Lakachauskis <lakiboy83@gmail.com>
 */
class CmsAttribListMgr extends SGL_Manager
{
    public function __construct()
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);
        parent::SGL_Manager();

        $this->pageTitle      = 'Attribute Lists Manager';
        $this->masterTemplate = 'master.html';
        $this->template       = 'cmsattributelistList.html';

        $this->_aActionsMapping = array(
            'list' => array('list')
        );
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

        $output->aAttrLists = SGL_Finder::factory('attriblist')
            ->retrieve();
        $output->addJavascriptFile(array(
            'js/jquery/plugins/jquery.form.js',
            'simplecms/js/CmsAttribList.js'
        ));
        $output->addOnLoadEvent('SimpleCms.AttribList.init()');
    }
}
?>