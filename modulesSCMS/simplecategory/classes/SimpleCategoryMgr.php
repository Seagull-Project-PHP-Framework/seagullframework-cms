<?php
require_once SGL_CORE_DIR . '/Delegator.php';
require_once dirname(__FILE__) . '/SimpleCategoryDAO.php';
require_once 'SimpleCms/Util.php';

/**
 * Categories manager.
 *
 * @package simplecategory
 * @author Dmitri Lakachauskis <lakiboy83@gmail.com>
 */
class SimpleCategoryMgr extends SGL_Manager
{
    public function __construct()
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);
        parent::SGL_Manager();

        $this->pageTitle      = 'Categories Manager';
        $this->masterTemplate = 'master.html';
        $this->template       = 'simplecategoryList.html';

        $this->_aActionsMapping = array(
            'list' => array('list')
        );

        $this->da = new SGL_Delegator();
        $this->da->add(SimpleCategoryDAO::singleton());
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

    public function display(SGL_Output $output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);
    }

    public function _cmd_list(SGL_Registry $input, SGL_Output $output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        // get all possible categories including inactive
        // and with missing translation
        $output->aCats = $this->da->getTreeByCategoryId(
            SGL_CATEGORY_ROOT, SGL::getCurrentLang(),
            $onlyActive = false, $showUntranlsated = true);

        $output->aLangs = SimpleCms_Util::formatLanguageList(SGL_Util::getLangsDescriptionMap());

        $output->addJavascriptFile(array(
            'js/jquery/plugins/jquery.form.js',
            'admin/js/jquery/jquery.simpletree.js',
            'simplecategory/js/SimpleCategory.js'
        ));
        $output->addCssFile(array(
            'admin/css/jquery/jquery.simpletree.css'
        ));
        $output->addOnloadEvent('SimpleCategory.List.init()', true);
    }
}
?>