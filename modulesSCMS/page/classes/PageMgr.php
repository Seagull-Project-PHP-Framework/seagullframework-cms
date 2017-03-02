<?php
require_once dirname(__FILE__) . '/PageDAO.php';
require_once SGL_MOD_DIR . '/user2/classes/User2DAO.php';
require_once SGL_MOD_DIR . '/cms/classes/CmsDAO.php';
require_once 'Pager.php';

/**
 * Pages manager.
 *
 * @package page
 * @author Dmitri Lakachauskis <lakiboy83@gmail.com>
 */
class PageMgr extends SGL_Manager
{
    public function __construct()
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);
        parent::SGL_Manager();

        $this->pageTitle      = 'Pages Manager';
        $this->masterTemplate = 'master.html';
        $this->template       = 'pageList.html';

        $this->_aActionsMapping = array(
            'list' => array('list'),
            'add'  => array('add'),
            'edit' => array('edit')
        );

        $this->da = new SGL_Delegator();
        $this->da->add(PageDAO::singleton());
        $this->da->add(User2DAO::singleton());
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

        $input->redir = $req->get('redir');

        // add/edit
        $input->pageId = $req->get('pageId');

        // list action
        $input->siteId     = $req->get('siteId');
        $input->langId     = $req->get('langId');
        $input->parentId   = $req->get('parentId');
        $input->status     = $req->get('status');
        $input->sortBy     = $req->get('sortBy');
        $input->sortOrder  = $req->get('sortOrder');
        $input->resPerPage = $req->get('resPerPage');
        $input->page       = $req->get('page');

        $input->aSites      = $this->da->getSitesList();
        $input->aPageTypes  = $this->da->getPageTypesList();
        $input->aStatuses   = $this->da->getPageStatusesList();
        $input->aLangs      = SimpleCms_Util::formatLanguageList(SGL_Util::getLangsDescriptionMap());
        $input->aSortFields = array(
            'last_updated', 'status', 'username', 'title'
        );
        $input->aSortOrder  = array('asc', 'desc');
        $input->aResPerPage = SimpleCms_Util::getPageRanges();

        $aDefaults = array(
            'siteId'     => key($input->aSites),
            'langId'     => SGL_Translation3::getDefaultLangCode(),
            'parentId'   => 'all',
            'status'     => 'all',
            'sortBy'     => reset($input->aSortFields),
            'sortOrder'  => end($input->aSortOrder),
            'resPerPage' => reset($input->aResPerPage)
        );
        $aFilter = SGL_Session::get('page_filter');
        if (!is_array($aFilter)) {
            $aFilter = $aDefaults;
        }

        // validation
        if (!is_numeric($input->siteId)) {
            $input->siteId = $aFilter['siteId'];
        }
        if (!array_key_exists($input->langId, $input->aLangs)) {
            $input->langId = $aFilter['langId'];
        }
        if ($input->parentId != 'all' && !is_numeric($input->parentId)) {
            $input->parentId = $aFilter['parentId'];
        }
        if ($input->status != 'all' && !array_key_exists($input->status, $input->aStatuses)) {
            $input->status = $aFilter['status'];
        }
        if (!in_array($input->sortBy, $input->aSortFields)) {
            $input->sortBy = $aFilter['sortBy'];
        }
        if (!in_array($input->sortOrder, $input->aSortOrder)) {
            $input->sortOrder = $aFilter['sortOrder'];
        }
        if (!in_array($input->resPerPage, $input->aResPerPage)) {
            $input->resPerPage = $aFilter['resPerPage'];
        }

        // update filter in session only for page list
        if ($input->action == 'list') {
            foreach ($aDefaults as $fieldName => $fieldVal) {
                $aFilter[$fieldName] = $input->$fieldName;

                if (!in_array($fieldName, array('sortBy', 'sortOrder'))) {
                    SGL_Request::singleton()->set($fieldName, $input->$fieldName);
                }
            }
            SGL_Session::set('page_filter', $aFilter);
        }
    }

    public function display(SGL_Output $output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        foreach ($output->aStatuses as $statusId => $statusName) {
            $statusName                   = $statusName . ' (page status)';
            $output->aStatuses[$statusId] = SGL_Output::tr($statusName);
        }
        foreach ($output->aPageTypes as $pageId => $pageType) {
            $pageType                     = $pageType . ' (page type)';
            $output->aPageTypes[$pageId]  = SGL_Output::tr($pageType);
        }
        foreach ($output->aSortFields as $i => $fieldVal) {
            unset($output->aSortFields[$i]);
            $output->aSortFields[$fieldVal] = SGL_Output::tr($fieldVal . ' (field)');
        }
        foreach ($output->aSortOrder as $i => $fieldVal) {
            unset($output->aSortOrder[$i]);
            $output->aSortOrder[$fieldVal] = SGL_Output::tr($fieldVal . ' (sort order)');
        }

        // routes
        if (in_array($output->action, array('add','edit'))) {
            $output->aModules = SGL_Util::getAllModuleDirs();
            reset($output->aModules);
            if (empty($output->route->moduleName)) {
                $output->route->moduleName = 'simplecms';
            }
            $output->aManagers = array_map(
                array('SGL_Inflector', 'getSimplifiedNameFromManagerName'),
                SGL_Util::getAllManagersPerModule(SGL_MOD_DIR .'/'. $output->route->moduleName));

            if (empty($output->route->controller)) {
                $output->route->controller = 'CmsContentViewMgr.php';
            } else {
                // get managers full name
                $output->route->controller = array_search($output->route->controller, $output->aManagers);
            }

            $output->aActions = ($output->route->controller != 'none')
                ? SGL_Util::getAllActionMethodsPerMgr(SGL_MOD_DIR .'/'. $output->route->moduleName .'/classes/'. $output->route->controller)
                : 'list';
        }
    }

    public function _cmd_list(SGL_Registry $input, SGL_Output $output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        if ($input->sortBy == 'username') {
            $sortBy = 'u.' . $input->sortBy;
        } elseif ($input->sortBy == 'title') {
            $sortBy = 'pt.' . $input->sortBy;
        } else {
            $sortBy = 'p.' . $input->sortBy;
        }
        $parentId  = $input->parentId == 'all' ? null : $input->parentId;
        $pageCount = $this->da->getPageCount($input->siteId, $input->langId,
            $parentId, $input->status, $showUntrans = true);

        // page options
        $aOptions = array(
            'totalItems'            => $pageCount,
            'currentPage'           => $input->page,
            'mode'                  => 'Sliding',
            'perPage'               => $input->resPerPage,
            'delta'                 => 3,
            'fileName'              => '#%d',
            'path'                  => '',

            // taken from finder
            'curPageSpanPre'        => '<span>',
            'curPageSpanPost'       => '</span>',
            'spacesBeforeSeparator' => 1,
            'spacesAfterSeparator'  => 1,
            'append'                => false
        );
        $oPager = Pager::factory($aOptions);

        // get offsets and limits needed for DAO
        list($from, $to) = $oPager->getOffsetByPageId();
        $limit  = $to - $from + 1;
        $offset = $from - 1;

        $aPages = $this->da->getPages($input->siteId, $input->langId,
            $parentId, $input->status, $showUntrans = true, $limit, $offset,
            $sortBy, $input->sortOrder);
        // ensure page collection has all needed attr values
        foreach ($aPages as $oPage) {
            if (empty($oPage->language_id)) {
                $oPage->language_id = $input->langId;
            }
        }

        // fix 'path=""' bug
        $output->pagerLinks = str_replace('="/', '="', $oPager->links);
        $output->aPages     = $aPages;
        $output->aTree      = $this->da->getTreeByPageId($input->siteId,
            SGL_PAGE_ROOT, $input->langId, $input->status);
        $output->addJavascriptFile(array(
            'simplecms/js/CmsContent.js',
            'page/js/Page.js'
        ));
        $output->addOnloadEvent('Page.List.init()', false);
    }

    public function _cmd_add(SGL_Registry $input, SGL_Output $output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $oPage = new stdClass();
        $oPage->language_id    = $input->langId;
        $oPage->appears_in_nav = true;
        $oPage->status         = true; // active

        $output->oSite         = $this->da->getSiteById($input->siteId);
        $output->aContentTypes = $this->da->getContentTypes();
        $output->aTree         = $this->da->getTreeByPageId($input->siteId,
            SGL_PAGE_ROOT, $input->langId);

        $output->oPage         = $oPage;
        $output->template      = 'pageEdit.html';
        $output->addJavascriptFile(array(
            // ui
            'simplecms/js/jquery/ui/ui.core.js',
            'simplecms/js/jquery/ui/ui.tabs.js',
            'page/js/jquery/ui/modulematrix.js',

            // plugins
            'js/jquery/plugins/jquery.form.js',
            'admin/js/jquery/jquery.suggest.js',
            'admin/js/jquery/jquery.simpletree.js',

            // cms
            'simplecms/js/CmsContent.js',

             // page
            'page/js/Page.js'
        ));
        $output->addCssFile(array(
            'simplecms/css/jquery/ui/ui.core.css',
            'simplecms/css/jquery/ui/ui.theme.css',
            'simplecms/css/jquery/ui/ui.tabs.css',

            'admin/css/jquery/jquery.simpletree.css',
            'admin/css/jquery/jquery.suggest.css'
        ));
        $output->addOnloadEvent('Page.Edit.init()', false);
    }

    public function _cmd_edit(SGL_Registry $input, SGL_Output $output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $oPage = $this->da->getPageById($input->pageId, $input->langId);
        if (!empty($oPage)) {
            $output->aPath          = $this->da->getPathByPageId(
                $input->pageId, $input->langId);
            $output->oSite          = $this->da->getSiteById($oPage->site_id);
            $output->oCreatedByUser = $this->da->getUserById($oPage->created_by);
            $output->oUpdatedByUser = $this->da->getUserById($oPage->updated_by);
            $output->oRoute         = $this->da->getRouteByPageId(
                $oPage->site_id, $input->pageId);

            if (!empty($oPage->content_id)) {
                $oContent = SGL_Content::getById(
                    $oPage->content_id, $oPage->language_id);
                if (!PEAR::isError($oContent)) {
                    $output->oContent      = $oContent;
                    $output->contentTypeId = $output->oContent->typeId;
                } else {
                    SGL_Error::pop();
                }
            }

            if (!empty($output->oRoute->route)) {
                $output->route = SGL_Routes_Route::getById($output->oRoute->route_id);
            }
        }

        $output->aTree         = $this->da->getTreeByPageId($input->siteId,
            SGL_PAGE_ROOT, $input->langId);
        $output->aContentTypes = $this->da->getContentTypes();
        $output->oPage         = $oPage;
        $output->isEdit        = true;
        $output->template      = 'pageEdit.html';
        $output->addJavascriptFile(array(
            // ui
            'simplecms/js/jquery/ui/ui.core.js',
            'simplecms/js/jquery/ui/ui.tabs.js',
            'page/js/jquery/ui/modulematrix.js',

            // plugins
            'js/jquery/plugins/jquery.form.js',
            'admin/js/jquery/jquery.suggest.js',
            'admin/js/jquery/jquery.simpletree.js',

            // cms
            'simplecms/js/CmsContent.js',

             // page
            'page/js/Page.js'
        ));

        $output->addCssFile(array(
            'simplecms/css/jquery/ui/ui.core.css',
            'simplecms/css/jquery/ui/ui.theme.css',
            'simplecms/css/jquery/ui/ui.tabs.css',

            'admin/css/jquery/jquery.simpletree.css',
            'admin/css/jquery/jquery.suggest.css'
        ));
        $output->addOnloadEvent('Page.Edit.init()', false);
    }
}
?>