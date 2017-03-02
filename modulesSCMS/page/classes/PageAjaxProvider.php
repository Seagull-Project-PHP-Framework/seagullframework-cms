<?php
require_once SGL_CORE_DIR . '/Delegator.php';
require_once SGL_CORE_DIR . '/AjaxProvider2.php';
require_once SGL_MOD_DIR . '/page/classes/PageDAO.php';
require_once 'Pager.php';

/**
 * Ajax provider.
 *
 * @package page
 * @author Dmitri Lakachauskis <lakiboy83@gmail.com>
 */
class PageAjaxProvider extends SGL_AjaxProvider2
{
    public function __construct()
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);
        parent::__construct();

        $this->da = new SGL_Delegator();
        $this->da->add(PageDAO::singleton());
    }

    public function process(SGL_Registry $input, SGL_Output $output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        // turn off autocommit
        $this->dbh->autoCommit(false);

        $ok = parent::process($input, $output);
        DB::isError($ok)
            ? $this->dbh->rollback()
            : $this->dbh->commit();

        // turn autocommit on
        $this->dbh->autoCommit(true);

        return $ok;
    }

    /**
     * Ensure the current user can perform requested action.
     *
     * @param integer $requestedUserId
     *
     * @return boolean
     */
    protected function _isOwner($requestedUserId)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        return in_array(SGL_Session::getRoleId(), array(SGL_ADMIN, SGL_ROLE_MODERATOR));
    }

    public function getPageFilteredList(SGL_Registry $input, SGL_Output $output)
    {
        // sorting parameters
        $siteId     = $this->req->get('siteId');
        $langId     = $this->req->get('langId');
        $parentId   = $this->req->get('parentId');
        $status     = $this->req->get('status');
        $resPerPage = $this->req->get('resPerPage');
        $sortBy     = $this->req->get('sortBy');
        $sortOrder  = $this->req->get('sortOrder');
        $pageId     = $this->req->get('pageId');

        // sorting parameters variants
        $aSites        = $this->da->getSitesList();
        $aPageTypes    = $this->da->getPageTypesList();
        $aStatuses     = $this->da->getPageStatusesList();
        $aLangs        = SimpleCms_Util::formatLanguageList(SGL_Util::getLangsDescriptionMap());
        $aResPerPage   = SimpleCms_Util::getPageRanges();
        $aSortFields   = array('last_updated', 'status', 'username', 'title');
        $aSortOrder    = array('asc', 'desc');

        // validation
        if (!is_numeric($siteId)) {
            $siteId = key($aSites);
        }
        if (!array_key_exists($langId, $aLangs)) {
            $langId = SGL_Translation3::getDefaultLangCode();
        }
        if ($parentId != 'all' && !is_numeric($parentId)) {
            $parentId = 'all';
        }
        if ($status != 'all' && !array_key_exists($status, $aStatuses)) {
            $status = 'all';
        }
        if (!in_array($sortBy, $aSortFields)) {
            $sortBy = reset($aSortFields);
        }
        if (!in_array($sortOrder, $aSortOrder)) {
            $sortOrder = end($aSortOrder);
        }
        if (!in_array($resPerPage, $aResPerPage)) {
            $resPerPage = reset($aResPerPage);
        }

        // remember sorting prefs for browser requests
        $aFilter              = SGL_Session::get('page_filter');
        $aFilter['sortBy']    = $sortBy;
        $aFilter['sortOrder'] = $sortOrder;
        SGL_Session::set('page_filter', $aFilter);

        if ($sortBy == 'username') {
            $sortBy = 'u.' . $sortBy;
        } elseif ($sortBy == 'title') {
            $sortBy = 'pt.' . $sortBy;
        } else {
            $sortBy = 'p.' . $sortBy;
        }
        $parentId  = $parentId == 'all' ? null : $parentId;
        $pageCount = $this->da->getPageCount($siteId, $langId,
            $parentId, $status, $showUntrans = true);

        // page options
        $aOptions = array(
            'totalItems'            => $pageCount,
            'currentPage'           => $pageId,
            'mode'                  => 'Sliding',
            'perPage'               => $resPerPage,
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

        $aPages = $this->da->getPages($siteId, $langId,
            $parentId, $status, $showUntrans = true, $limit, $offset,
            $sortBy, $sortOrder);
        // ensure page collection has all needed attr values
        foreach ($aPages as $oPage) {
            if (empty($oPage->language_id)) {
                $oPage->language_id = $langId;
            }
        }

        // render contents
        $output->html = $this->_renderTemplate($output, array(
            'masterTemplate' => 'page_tablelist.html',
            'aPages'         => $aPages,
            'theme'          => $_SESSION['aPrefs']['admin theme'],
        ));
        $output->pagerLinks = str_replace('="/', '="', $oPager->links);
    }

    public function deletePage(SGL_Registry $input, SGL_Output $output)
    {
        $pageId = $this->req->get('pageId');

        $ok = $this->da->deletePageById($pageId);
        $ok = $this->da->rebuildRouteCache();
        if (!PEAR::isError($ok)) {
            $output->redir = $input->getCurrentUrl()->makeLink(array(
                'moduleName'  => 'page',
                'managerName' => 'page',
//                'parentId'    => 'all'
            ));
        }
    }

    public function getPageData(SGL_Registry $input, SGL_Output $output)
    {
        $pageId = $this->req->get('pageId');
        $langId = $this->req->get('langId');

        $output->oPage = $this->da->getPageById($pageId, $langId);
    }

    public function movePage(SGL_Registry $input, SGL_Output $output)
    {
        $siteId     = $this->req->get('siteId');
        $pageId     = $this->req->get('pageId');
        $orderId    = $this->req->get('orderId');
        $parentId   = $this->req->get('parentId');

        $this->da->movePageById($siteId, $pageId, $parentId, $orderId);
    }

    public function updatePage(SGL_Registry $input, SGL_Output $output)
    {
        $aPage   = $this->req->get('page');
        $redir   = $this->req->get('redir');
        $goBack  = $this->req->get('submitted');
        $refresh = $this->req->get('submittedContinue');

        $pageId     = $aPage['page_id'];
        $newPage    = empty($pageId);

        $aPage['created_by'] = $aPage['updated_by'] = SGL_Session::getUid();
        if ($newPage) {
            $ok = $pageId = $this->da->addPage($aPage);
        } else {
            $ok = $this->da->updatePageById($pageId, $aPage);
        }

        // ROUTE
        $aRoute = $this->req->get('route');
        $aRoute['is_active'] = intval($aPage['status']);

        if (isset($aRoute['controller'])) {
            $aRoute['controller'] = SGL_Inflector::getSimplifiedNameFromManagerName($aRoute['controller']);
        }

        // make sure front-end doesn't fool us
        $aRoute['path'] = preg_replace('/\s+/', '-', $aRoute['path']);
        $aRoute['path'] = preg_replace('/[^\/_a-z0-9]/i', '-', $aRoute['path']);

        if (empty($aRoute['path']) && !$newPage) {
            $ok = $this->da->deleteRouteByPageId($aPage['site_id'], $pageId);
        } elseif (!empty($aRoute['path'])) {
            $route = new SGL_Routes_Route();

            $route->setFrom(array(
                'site_id' => $aPage['site_id'],
                'page_id' => $pageId
            ));

            if (!$newPage) {
                $rawRoute = $this->da->getRouteByPageId($aPage['site_id'], $pageId);
                if (!is_null($rawRoute)) {
                    $route->setFrom($rawRoute);
                }
            }
            $route->setFrom($aRoute);

            if (!$this->da->isUniquePath($route)) {
                $this->_raiseMsg(array(
                    'type'    => SGL_MESSAGE_ERROR,
                    'message' => 'route exists within current site'
                ), true);
                return false;
            }

            $route->save();
            $ok = $this->da->rebuildRouteCache();
        }

        // add or update translation
        if (!PEAR::isError($ok)) {
            $ok = $this->da->updatePageTranslationById($pageId,
                $aPage['language_id'], $aPage);

            // refresh current screen
            if ($refresh) {
                if ($newPage) {
                    $goto = $input->getCurrentUrl()->makeLink(array(
                        'moduleName'  => 'page',
                        'managerName' => 'page',
                        'action'      => 'edit',
                        'langId'      => $aPage['language_id'],
                        'pageId'      => $pageId
                    ));
                // we don't want to redir for edits
                } else {
                    $goto = null;
                    $this->_raiseMsg(array(
                        'type'    => SGL_MESSAGE_INFO,
                        'message' => 'page was successfully updated'
                    ), true);
                }
            // go to previous screen
            } elseif ($goBack && $redir) {
                $goto = $redir;
            // go to page list screen
            } else {
                $goto = $input->getCurrentUrl()->makeLink(array(
                    'moduleName'  => 'page',
                    'managerName' => 'page'
                ));
            }
            $output->redir = $goto;
        }
    }

    public function getRoutes(SGL_Registry $input, SGL_Output $output)
    {
        // sorting parameters
        $aFilter     = $this->req->get('filter');

        $resPerPage = (array_key_exists('resPerPage', $aFilter) && is_numeric($aFilter['resPerPage']))
            ? $aFilter['resPerPage']
            : $_SESSION['aPrefs']['resPerPage'];

        $aPager = array(
            'mode'      => 'Sliding',
            'delta'     => 3,
            'perPage'   => $resPerPage,
        );

        $aPagedData = $this->da->getRoutes($aFilter, $aPager);

        if (is_array($aPagedData['data']) && count($aPagedData['data'])) {
            $output->pager = ($aPagedData['totalItems'] <= $resPerPage) ? false : true;
            if ($output->pager) {
                $output->pagerLinks = $aPagedData['links'];
            }
        } else {
            $output->pager = false;
        }

        // render contents
        $output->html = $this->_renderTemplate($output, array(
            'masterTemplate' => 'route_tablelist.html',
            'aPagedData'     => $aPagedData,
            'theme'          => $_SESSION['aPrefs']['admin theme'],
        ));
    }


    public function updateRoute(SGL_Registry $input, SGL_Output $output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $routeId = $this->req->get('routeId');
        $aRoute  = $this->req->get('route');

        $redir   = $this->req->get('redir');
        $goBack  = $this->req->get('submitted');
        $refresh = $this->req->get('submittedContinue');

        $newRoute    = empty($routeId);

        if (array_key_exists('controller',$aRoute)) {
            $aRoute['controller'] = SGL_Inflector::getSimplifiedNameFromManagerName($aRoute['controller']);
        }

        $route = (is_numeric($routeId))
            ? SGL_Routes_Route::getById($routeId)
            : new SGL_Routes_Route();

        $route->setFrom($aRoute);

        if (!$this->da->isUniquePath($route)) {
            $this->_raiseMsg(array(
                'type'    => SGL_MESSAGE_ERROR,
                'message' => 'route exists within current site'
            ), true);
            return false;
        }
        $ok = $route->save();
        $ok = $this->da->rebuildRouteCache();

        // take care of redirection
        if (!PEAR::isError($ok)) {
            // refresh current screen
            if ($refresh) {
                if ($newRoute) {
                    $goto = $input->getCurrentUrl()->makeLink(array(
                        'moduleName'  => 'page',
                        'managerName' => 'route',
                        'action'      => 'edit',
                        'routeId'     => $route->route_id
                    ));
                // we don't want to redir for edits
                } else {
                    $goto = null;
                    $this->_raiseMsg(array(
                        'type'    => SGL_MESSAGE_INFO,
                        'message' => 'route was successfully updated'
                    ), true);
                }
            // go to previous screen
            } elseif ($goBack && $redir) {
                $goto = $redir;
            // go to route list screen
            } else {
                $goto = $input->getCurrentUrl()->makeLink(array(
                    'moduleName'  => 'page',
                    'managerName' => 'route'
                ));
            }
            $output->redir = $goto;
        }

    }

    public function updateRouteStatus(SGL_Registry $input, SGL_Output $output)
    {
        $routeId = $this->req->get('routeId');
        $status  = intval($this->req->get('isActive'));

        $ok = $this->da->updateRouteStatus($routeId, $status);
        $ok = $this->da->rebuildRouteCache();

        if ($ok instanceof PEAR_Error) {
            $this->_raiseMsg(array(
                'type'    => SGL_MESSAGE_ERROR,
                'message' => 'unable to update url'
            ), true);
        } else {
            $this->_raiseMsg(array(
                'type'    => SGL_MESSAGE_INFO,
                'message' => 'url was successfully updated'
            ), true);
        }

    }

    public function deleteRoutes(SGL_Registry $input, SGL_Output $output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $aRouteId = $this->req->get('aRouteId');

        // check if route is associated with page
        foreach ($aRouteId as $routeId) {
            $routeId = intval($routeId);
        	$route = SGL_Routes_Route::getById($routeId);
        	if (is_numeric($route->page_id)) {
                $this->_raiseMsg(array(
                    'type'    => SGL_MESSAGE_ERROR,
                    'message' => 'unable to delete url associated with page'
                ), true);
                return false;
        	}
        }

        $this->da->deleteRoutes($aRouteId);

        $ok = $this->da->rebuildRouteCache();
    }
    public function getRouteWidgetData(SGL_Registry $input, SGL_Output $output)
    {
        $module     = $this->req->get('module');
        $manager    = $this->req->get('manager');

        $output->aModules = SGL_Util::getAllModuleDirs();

        $output->aManagers = array_map(
                array('SGL_Inflector', 'getSimplifiedNameFromManagerName'),
                SGL_Util::getAllManagersPerModule(SGL_MOD_DIR .'/'. $module));

        $currentMgr = (!empty($manager) && isset($output->aManagers[$manager]))
                ? $manager
                : key($output->aManagers);

        $output->aActions = ($currentMgr != 'none')
            ? SGL_Util::getAllActionMethodsPerMgr(SGL_MOD_DIR .'/'. $module .'/classes/'. $currentMgr)
            : array();

    }
}
?>