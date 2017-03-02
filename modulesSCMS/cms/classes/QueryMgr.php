<?php

/**
 * Query manager.
 *
 * @package    seagull
 * @subpackage cms
 * @author     Dmitri Lakachauskis <lakiboy83@gmail.com>
 */
class QueryMgr extends SGL_Manager
{
    function __construct()
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);
        parent::SGL_Manager();

        $this->pageTitle = 'Custom Query';
        $this->template  = 'queryList.html';
        $this->masterLayout = 'layout-navtop-1col.css';

        $this->_aActionsMapping = array(
            'list'   => array('list'),
            'search' => array('search', 'redirectToDefault'),
        );
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
    }

    function display(&$output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $output->addJavascriptFile(array(
            'js/jquery/jquery.js',
            'js/jquery/plugins/jquery.form.js',
            'cms/js/jquery/jquery.tablesorter.js',
            'cms/js/CmsQuery.js'
        ), $optimize = false);
        
        $output->addCssFile(array(
            'cms/css/jquery/tablesorter/blue/style.css',
            'cms/css/jquery/tablesorter/blue/pager/jquery.tablesorter.pager.css',
            'cms/css/cms.css',
        ));
        $output->addOnLoadEvent("CmsQuery.initFilters('contentName', 'status')");
    }

    function _cmd_list(&$input, &$output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);
    }
}
?>