<?php
require_once dirname(__FILE__) . '/PageDAO.php';
require_once SGL_MOD_DIR . '/user2/classes/User2DAO.php';
require_once SGL_MOD_DIR . '/cms/classes/CmsDAO.php';
require_once 'Pager.php';

/**
 * Route manager.
 *
 * @package route
 */
class RouteMgr extends SGL_Manager
{
    public function __construct()
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);
        parent::SGL_Manager();

        $this->pageTitle      = 'Route Manager';
        $this->masterTemplate = 'master.html';
        $this->template       = 'routeList.html';

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

        $input->filter  = (array)$req->get('filter');

        // add/edit
        $input->routeId = $req->get('routeId');
        $input->siteId  = $req->get('siteId');
    }

    public function display(SGL_Output $output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        if (in_array($output->action, array('add','edit'))) {
            $output->aModules = SGL_Util::getAllModuleDirs();
            reset($output->aModules);
            if (!isset($output->route->moduleName)) {
                $output->route->moduleName = 'simplecms';
            }
            $output->aManagers = array_map(
                array('SGL_Inflector', 'getSimplifiedNameFromManagerName'),
                SGL_Util::getAllManagersPerModule(SGL_MOD_DIR .'/'. $output->route->moduleName));

            if (!isset($output->route->controller)) {
                $output->route->controller = 'CmsContentViewMgr.php';
            } else {
                // get managers full name
                $output->route->controller = array_search($output->route->controller, $output->aManagers);
            }

            $output->aActions = ($output->route->controller != 'none')
                ? SGL_Util::getAllActionMethodsPerMgr(SGL_MOD_DIR .'/'. $output->route->moduleName .'/classes/'. $output->route->controller)
                : 'list';

            if (!isset($output->route->params) && isset($output->oContent->id)) {
                $output->route->params = 'frmContentId/' . $output->oContent->id;
            }
        }
    }

    public function _cmd_list(SGL_Registry $input, SGL_Output $output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $aPager = array(
            'mode'      => 'Sliding',
            'delta'     => 3,
            'perPage'   => $output->resPerPage = $_SESSION['aPrefs']['resPerPage'],
        );

        $output->aPagedData = $this->da->getRoutes($input->filter, $aPager);

        if (is_array($output->aPagedData['data']) && count($output->aPagedData['data'])) {
            $output->pager = ($output->aPagedData['totalItems'] <= $output->resPerPage) ? false : true;
            if ($output->pager) {
                $output->pagerLinks = $output->aPagedData['links'];
            }
        } else {
            $output->pager = false;
        }

        $output->aSites      = $this->da->getSitesList();
        $output->aResPerPage = SimpleCms_Util::getPageRanges();
        $output->aStatuses   = array(
            '1' => SGL_Output::tr('active'),
            '0' => SGL_Output::tr('disabled'),
        );

        $output->addJavascriptFile(array(
            'simplecms/js/jquery/jquery.form.js',
            'page/js/Route.js',
        ));
        $output->addOnloadEvent('Route.List.init()', false);

    }

    public function _cmd_edit(SGL_Registry $input, SGL_Output $output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $output->route = SGL_Routes_Route::getById($input->routeId);

        $output->isEdit        = true;
        $output->template      = 'routeEdit.html';
        $output->addJavascriptFile(array(
            // ui
            'simplecms/js/jquery/ui/ui.core.js',
            'page/js/jquery/ui/modulematrix.js',

            // plugins
            'js/jquery/plugins/jquery.form.js',

            // cms
            'simplecms/js/CmsContent.js',

             // page
            'page/js/Route.js',
        ));

        $output->addCssFile(array(
            'simplecms/css/jquery/ui/ui.core.css',
            'simplecms/css/jquery/ui/ui.theme.css',
        ));

        $output->addOnloadEvent('Route.Edit.init()', false);
    }

    public function _cmd_add(SGL_Registry $input, SGL_Output $output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $output->route = new SGL_Routes_Route();

        // set defaults
        $output->route->site_id = $input->siteId;
        $output->route->is_active = 1;

        $output->template      = 'routeEdit.html';
        $output->addJavascriptFile(array(
            'simplecms/js/jquery/ui/ui.core.js',
            'page/js/jquery/ui/modulematrix.js',
            'js/jquery/plugins/jquery.form.js',
            'simplecms/js/CmsContent.js',
            'page/js/Route.js',
        ));

        $output->addCssFile(array(
            'simplecms/css/jquery/ui/ui.core.css',
            'simplecms/css/jquery/ui/ui.theme.css',
        ));

        $output->addOnloadEvent('Route.Edit.init()', false);
    }
}
?>