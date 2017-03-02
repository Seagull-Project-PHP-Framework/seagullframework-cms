<?php

require_once SGL_CORE_DIR . '/Delegator.php';
require_once 'SimpleCms/Util.php';
require_once SGL_MOD_DIR . '/simplecms/classes/SimpleCmsDAO.php';
require_once SGL_MOD_DIR . '/cms/classes/CmsDAO.php';
require_once SGL_MOD_DIR . '/media2/classes/Media2DAO.php';
require_once SGL_MOD_DIR . '/translation/classes/Translation2.php';
require_once SGL_MOD_DIR . '/dashboard/lib/Util.php';

/**
 * Admin manager.
 *
 * @package admin
 * @author Dmitri Lakachauskis <lakiboy83@gmail.com>
 */
class AdminMgr extends SGL_Manager
{
    public function __construct()
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);
        parent::SGL_Manager();

        $this->pageTitle      = 'Admin Manager';
        $this->masterTemplate = 'master.html';
        $this->template       = 'adminList.html';

        $this->_aActionsMapping = array(
            'list' => array('list'),
        );

        $this->da = new SGL_Delegator();
        $this->da->add(SimpleCmsDAO::singleton());
        $this->da->add(CmsDAO::singleton());
        $this->da->add(Media2DAO::singleton());
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

        $input->cLang = SGL::getCurrentLang();
    }

    public function display(SGL_Output $output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);
    }

    public function _cmd_list(SGL_Registry $input, SGL_Output $output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        // --------------
        // --- media2 ---
        // --------------

        SGL_Translation2::loadDictionary('media2');

        $output->aMedias     = $this->da->getMedias(null, null, null, 15);
        $output->aMimeTypes  = $this->da->getMimeTypeInfoList();
        $output->aMediaTypes = $this->da->getMediaTypeInfoList();

        // -----------------
        // --- simplecms ---
        // -----------------

        SGL_Translation2::loadDictionary('simplecms');

        $output->aActivity     = $this->da->getContentList(null, 25);
        $output->aContentTypes = $this->da->getContentTypes();
        $output->aStatuses     = $this->da->getContentStatusList();
        $output->aLangs        = SimpleCms_Util::formatLanguageList(SGL_Util::getLangsDescriptionMap());
        foreach ($output->aStatuses as $statusId => $name) {
            $output->aStatuses[$statusId] = SGL_Output::tr($name . ' (status)');
        }
        $aRet = array();
        foreach ($output->aActivity as $oContent) {
            $aRet[$oContent->date][] = $oContent;
        }
        $output->aActivity = $aRet;

        $output->addJavascriptFile(array(
            'js/jquery/plugins/jquery.suggest.js',
            'simplecms/js/CmsActivity.js',
        ));
        $output->addOnLoadEvent('SimpleCms.Activity.init()');
        $output->addCssFile(array(
            'simplecms/css/simplecms.css',
            'admin/css/jquery/jquery.suggest.css'
        ));

        // -----------------
        // --- dashboard ---
        // -----------------

        $output->addJavascriptFile(array(
            'dashboard/js/interface/interface.js',
            'dashboard/js/Dashboard.js'
        ));
        $output->addOnLoadEvent('Dashboard.Widget.init()');
        $output->addCssFile('dashboard/css/widget.css');

        $aBlocksMap = array(
//            'block1' => array(
//                'masterTemplate' => 'block_demo1.html',
//                'moduleName'     => 'admin'
//             ),
//             'block2' => array(
//                'masterTemplate' => 'block_demo2.html',
//                'moduleName'     => 'admin'
//             ),
             'content_activity' => array(
                'masterTemplate' => 'block_simplecms_activity.html',
                'moduleName'     => 'admin',
             ),
             'content_create' => array(
                'masterTemplate' => 'block_content_add.html',
                'moduleName'     => 'simplecms',
             ),
             'content_filter' => array(
                'masterTemplate' => 'block_simplecms_content_filter.html',
                'moduleName'     => 'admin',
             ),
             'content_activity_search' => array(
                'masterTemplate' => 'block_activity_usersearch.html',
                'moduleName'     => 'simplecms',
             ),
             'media_list' => array(
                'masterTemplate' => 'block_media2_media_list.html',
                'moduleName'     => 'admin',
             )
        );

        $output->aCols = SGL_Dashboard_Util::renderBlocks($aBlocksMap,
            'admin_dashboard', SGL_Session::getUid(), $output);

        // -------------
        // --- admin ---
        // -------------

        $output->addJavascriptFile(array(
            'admin/js/Admin.js',
        ));
        $output->addOnLoadEvent('Admin.Dashboard.init()');
    }
}
?>