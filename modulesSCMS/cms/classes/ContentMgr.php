<?php
require_once SGL_MOD_DIR . '/cms/classes/CmsDAO.php';
require_once SGL_MOD_DIR . '/cms/classes/ContentViewMgr.php';
require_once 'SGL/Observer.php';
require_once 'SGL/Delegator.php';
require_once 'SGL/Translation3.php';

/**
 * @package    seagull
 * @subpackage cms
 * @author     Demian Turner <demian@phpkitchen.com>
 */
class ContentMgr extends ContentViewMgr
{
    function __construct()
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);
        parent::SGL_Manager();

        $this->da = new SGL_Delegator();
        $this->da->add(CmsDAO::singleton());
        $this->masterLayout = 'layout-navtop-1col.css';
        $this->pageTitle = 'Content Manager';
        $this->_aActionsMapping =  array(
            'add'       => array('add'),
            'insert'    => array('insert', 'redirectToEdit', 'redirectToDefault'),
            'edit'      => array('edit'),
            'update'    => array('update', 'redirectToEdit', 'redirectToDefault'),
            'delete'    => array('delete', 'redirectToDefault'),
            'list'      => array('list'),
            'view'      => array('view'),
        );

    }

    function validate($req, &$input)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $this->template = 'contentList.html';

        $this->validated        = true;
        $input->masterLayout    = $this->masterLayout;
        $input->error           = array();
        $input->pageTitle       = $this->pageTitle;
        $input->template        = $this->template;

        //  form vars
        $input->action          = ($req->get('action')) ? $req->get('action') : 'list';
        $input->contentTypeId   = $req->get('frmContentTypeId');
        $input->contentId       = (int)$req->get('frmContentId');
        $input->version         = $req->get('version');
        $input->aDelete         = $req->get('frmDelete');

        //  new content form vars
        $input->content         = (object)$req->get('content', $allowTags = true);
        $input->content->id     = $input->contentId;

        //  misc
        $input->redir           = $req->get('redir');
        $input->preview         = $req->get('preview');
        $input->create          = $req->get('create');
        $input->save            = $req->get('save');
        $input->continueEdit    = $req->get('continueEdit');
        $input->newVersion      = $req->get('newVersion');
        $input->newLang         = $req->get('newLang');
        $input->newLanguageId   = $req->get('newLanguageId');

        //paging
        $input->pageId          = $req->get('pageId');
        $input->resPerPage      = $req->get('resPerPage');

        //  sorting
        $input->sortBy = $this->_loadSort('sortBy', __CLASS__,
            // sortable fields
            array('name', 'last_updated', 'status', 'created_by_id'),
            'last_updated'); // default sort field
        $input->sortOrder = $this->_loadSort('sortOrder', __CLASS__,
            // sort types
            array('asc', 'desc'),
            'desc'); // default sort order

        //  reformat checkbox/radio input submissions
        if (isset($input->content->attributes['checkbox'])) {
            for ($x = 0; $x < $total = count($input->content->attributes['attr_id']); $x++) {
                if (isset($input->content->attributes['checkbox'][$input->content->attributes['attr_id'][$x]])) {
                    if (isset($input->content->attributes['data'][$x])) {
                        // If checkboxes, we have an array to convert to string
                        $input->content->attributes['data'][$x] = is_array($input->content->attributes['data'][$x])
                            ? implode(';', $input->content->attributes['data'][$x])
                            : $input->content->attributes['data'][$x];
                    } else {
                        $input->content->attributes['data'][$x] = '';
                    }
                }
            }
            unset($input->content->attributes['checkbox']);
            ksort($input->content->attributes['data']);
        }

        //  we need to be sure that neither empty content name
        //  nor duplicated content name is specified
        if (in_array($input->action, array('insert', 'update'))) {
            $error = false;
            //  empty content name is tricky, because it transforms to
            //  'please provide a name for the content', if user will not
            //  provide content name two times, duplication will occur
            if (empty($input->content->name)) {
                $error = true;
                $msg   = 'Error: content name can\'t be empty';
            }

            if ($this->da->contentNameExists($input->content->name, $input->content->id)) {
            //  ensure a content name is not duplicated
                $error = true;
                $msg   = 'Error: content name is duplicated';
            }
            if ($error) {
                SGL::raiseMsg(SGL_Output::translate($msg));
                $aRedir = array(
                    'moduleName'  => 'cms',
                    'managerName' => 'content',
                    'action'      => 'add'
                );
                if (!empty($input->content->id)) {
                    $aRedir['action']       = 'edit';
                    $aRedir['frmContentId'] = $input->content->id;
                }
                SGL_HTTP::redirect($aRedir);
            }
        }

        //  sort
        $input->contentFilter = $req->get('contentFilter');
        $input->contentFilterActivated = $this->_maintainState(
            $input->contentFilter['typeId'],
            $input->contentFilter['status'],
            $input->contentFilter['categoryId'],
            $req->get('filter') // force to show filter panel
        );

        // Loading I18N tool
        $trans = SGL_Translation3::singleton('array');
        $input->cLang = $req->get('cLang')
            ? $req->get('cLang')
            : $trans->getDefaultLangCode();
        $input->aLanguages = $trans->getAvailableLanguages();

        //  put some validation here
    }

    function display(&$output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $aContentTypes = $this->da->getContentTypes();
        asort($aContentTypes);
        $output->aContentTypes = $aContentTypes;

        //  build total number of attributes for "new content type" form
        if ($output->action == 'list') {
            $totalFields = $this->conf['ContentTypeMgr']['totalFields'];
            for ($x = 1; $x <= $totalFields; $x++) {
                $output->totalFields[$x] = $x;
            }
        }
        //  select appropriate jscalendar lang file depending on prefs defined language
        $lang = SGL::getCurrentLang();
        $jscalendarLangFile = (is_file(SGL_WEB_ROOT . '/js/jscalendar/lang/calendar-'. $lang . '.js'))
            ? 'js/jscalendar/lang/calendar-'. $lang . '.js'
            : 'js/jscalendar/lang/calendar-en.js';
        $output->addJavascriptFile(array(
            'js/jscalendar/calendar_stripped.js',
            $jscalendarLangFile,
            'js/jscalendar/calendar-setup_stripped.js',
            'js/jquery/jquery.js',
            'js/jquery/plugins/jquery.form.js',
            'cms/js/jquery/jquery.tablesorter.js',
            'cms/js/string.js',
            'cms/js/cms.js',
            'cms/js/cms_content.js',
            'cms/js/cms_autocomplete.js'
        ), $optimize = false);
        $output->addCssFile(array(
            'cms/css/jquery/tablesorter/blue/style.css',
            'cms/css/jquery/tablesorter/blue/pager/jquery.tablesorter.pager.css',
            'cms/css/cms.css',
            'cms/css/modalWindow.css',
            'cms/css/cms_modalWindow.css',
            'cms/css/calendar.css',
        ));
        //  build content status info
        $output->aStatusList = $this->da->getContentStatusList();
        //  with sensible default for new content
        if ($output->action == 'insert') {
            $oContent = new stdClass();
            $output->oContent->status = SGL_CMS_STATUS_FOR_APPROVAL;
        }


        if ($output->action == 'view') {
            $output->addOnLoadEvent("cms.initContents()", true);
        }
        if ($output->action == 'insert' || $output->action == 'update') {
            $output->aVersions = $this->da->getVersionsByContentId($output->contentId, $output->cLang);
            $output->wysiwyg = true;
            //$output->addOnLoadEvent("cms.content.editor.loadRichTextAttributes()");
        }
        if ($output->action == 'list') {
            $output->aResPerPage = array(
                10 => 10,
                20 => 20,
                50 => 50
            );

            $output->contentTypeId = (SGL_Session::get('cms.content.add.contentTypeId'))
                ? SGL_Session::get('cms.content.add.contentTypeId')
                : '';
        }

        //  conditionally load classification widgets
        if (strlen($this->conf['ContentMgr']['classification'])) {
            $output->aClassifiers = array_map('trim',
                explode(',',$this->conf['ContentMgr']['classification']));

            if (in_array('categories',$output->aClassifiers)) {
                $output->classifyByCategory = true;
                require_once SGL_MOD_DIR  . '/cms/classes/MenuBuilder.php';
                $menu = new CmsMenuBuilder('SelectBox');
                $output->aElems = $menu->toArray();
            }
            if (in_array('tags',$output->aClassifiers)
                && SGL::moduleIsEnabled('tag')) {
                $output->classifyByTags = true;
            } elseif (in_array('tags',$output->aClassifiers)) {
                SGL::raiseMsg('In order to use tagging please install Tag module first',
                    false, SGL_MESSAGE_ERROR);
            }
        }

        // init autocomplete for content name
        if (isset($output->oContent)) {
            $this->_initAutoComplete($output);
        }

        // get available statuses
        $output->aStatusTypes = $this->da->getStatusTypes();
    }

    public function _cmd_add(SGL_Registry $input, SGL_Output $output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $output->oContent       = SGL_Content::getByType($input->contentTypeId);
        $output->todaysDate     = SGL_Date::getTime(true);
        $output->editable       = true;
        $output->masterTemplate = 'masterNoCols.html';
        $output->template       = 'contentEdit.html';
        $output->pageTitle      = 'New content';
        $output->action         = 'insert';

        SGL_Session::set('cms.content.add.contentTypeId',$input->contentTypeId);
    }

    function _cmd_insert($input, $output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $addContent = new Cms_AddContent($input, $output);
        $aObservers = explode(',', $this->conf['ContentMgr']['observers']);
        if (count($aObservers)) {
            foreach ($aObservers as $observer) {
                $path = SGL_MOD_DIR . "/cms/classes/observers/$observer.php";
                if (is_file($path)) {
                    require_once $path;
                    $addContent->attach(new $observer());
                }
            }
        }
        //  returns id for new content
        $ok = $addContent->run();
    }

    function _cmd_edit($input, $output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $output->masterTemplate = 'masterNoCols.html';
        $output->template = 'contentEdit.html';
        $output->pageTitle = 'Editing content';
        $output->action = 'update';

        $output->oContent = SGL_Content::getById($input->contentId, $input->cLang, $input->version);
    }

    function _cmd_update($input, $output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $updateContent = new Cms_UpdateContent($input, $output);
        $aObservers = explode(',', $this->conf['ContentMgr']['observers']);
        if (count($aObservers)) {
            foreach ($aObservers as $observer) {
                $path = SGL_MOD_DIR . "/cms/classes/observers/$observer.php";
                if (is_file($path)) {
                    require_once $path;
                    $updateContent->attach(new $observer());
                }
            }
        }
        //  returns updated content
        $oContent = $updateContent->run();
        if ($input->newVersion || $input->newLang) {
            $input->continueEdit = true;
            $output->version    = $oContent->version;
            $output->cLang      = $oContent->langCode;
        }

    }

    function _cmd_delete($input, $output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        if (is_array($input->aDelete)) {
            $ok = $this->da->deleteContentById($input->aDelete);
            SGL::raiseMsg('The selected content has successfully been deleted',
                false, SGL_MESSAGE_INFO);
        } else {
            SGL::raiseError('Incorrect parameter passed to ' .
                __CLASS__ . '::' . __FUNCTION__, SGL_ERROR_INVALIDARGS);
        }
    }

    function _cmd_view($input, $output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $output->editable = true;
        parent::_cmd_view($input, $output);
    }

    function _cmd_preview($input, $output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);
        $output->masterTemplate = 'masterBlank.html';
        $output->template = 'contentView.html';
        $output->oContent = SGL_Content::getById($input->contentId);
    }

    function _cmd_list($input, $output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);
        $oFinder = SGL_Finder::factory('content');
        if (!empty($input->contentFilter['typeId'])) {
            $oFinder->addFilter('typeId', $input->contentFilter['typeId']);
        }
        if (!empty($input->cLang)) {
            $oFinder->addFilter('lang', $input->cLang);
        }
        if (!empty($input->contentFilter['status'])) {
            $oFinder->addFilter('status', $input->contentFilter['status']);
        }
        if (!empty($input->contentFilter['categoryId'])) {
            $oFinder->addFilter('categoryId', $input->contentFilter['categoryId']);
        }
        $aContents = $oFinder
            ->addFilter('sortBy', $input->sortBy)
            ->addFilter('sortOrder', $input->sortOrder)
            ->paginate()
            ->retrieve();
        // Get pagination info
        $pager = $oFinder->getPager();
        if ($pager) {
            $output->pageLinks = $pager->pageLinks;
            $output->resPerPage = $pager->_perPage;
        }

        //  add link info
        foreach ($aContents as $k => $oContent) {
            $aContents[$k]->numLinks = $oContent->getLinkCount();
        }

        $output->aContents = $aContents;
        $output->{'sort_' . $input->sortBy} = true;
        $output->sortOrderDisplay = (strtolower($output->sortOrder) == 'asc')
            ? 'desc' : 'asc';
        if ($input->contentFilterActivated) {
            $output->addOnLoadEvent('cms.content.filter.toggle()');
        }
    }

    function _cmd_redirectToEdit($input, $output)
    {
        if (!$input->continueEdit) {
            return false;
        }
        //  if no errors have occured, redirect
        if (!SGL_Error::count()) {
            $aParams =
            array(
                'moduleName'   => 'cms',
                'managerName'  => 'content',
                'action'       => 'edit',
                'frmContentId' => $input->contentId
            );
//            if (!empty($output->version)) {
//                $aParams['version'] = $output->version;
//            }
            if (!empty($output->cLang)) {
                $aParams['cLang'] = $output->cLang;
            }
            SGL_HTTP::redirect($aParams);

        //  else display error with blank template
        } else {
            $output->template = 'docBlank.html';
        }
    }

    public function _cmd_redirectToDefault(SGL_Registry $input, SGL_Output $output)
    {
        if (!empty($input->redir)) {
            SGL_HTTP::redirect(base64_decode($input->redir));
        } else {
            parent::_cmd_redirectToDefault($input, $output);
        }
    }

    /**
     * Sort management. (copied from LakiBoy's custom lib)
     *
     * @param string $sortType      sortBy or sortOrder
     * @param string $managerName   we need to remember manager name
     * @param array  $aRange        allowed range of fields
     * @param string $defaultValue  default value
     */
    function _loadSort($sortType, $managerName, $aRange, $defaultValue)
    {
        $sortValue = SGL_Request::singleton()->get($sortType);
        $sessId = $sortType . $managerName; // e.g. sortByColorAdminMgr
        if (empty($sortValue) || (!in_array($sortValue, $aRange) && !empty($aRange))) {
            $sessValue = SGL_Session::get($sessId);
            if (empty($sessValue)) {
                $sessValue = $defaultValue;
            }
            $sortValue = $sessValue;
        }
        SGL_Session::set($sessId, $sortValue);
        return $sortValue;
    }

    function _maintainState($typeId, $status, $categoryId, $forceFilters = null)
    {
        $options = array('typeId', 'status', 'categoryId', 'forceFilters');
        $filterActivated = false;
        $sessValue = SGL_Session::get('cmsContentListFilter');
        foreach ($options as $filterName) {
            if (is_null($$filterName) && !empty($sessValue[$filterName])) {
                $$filterName = $sessValue[$filterName];
            }
            $sessValue[$filterName] = $$filterName;
            // at least one param is not null - activate panel
            if (!empty($sessValue[$filterName])) {
                $filterActivated = true;
            }
        }
        SGL_Session::set('cmsContentListFilter', $sessValue);
        return $filterActivated;
    }

    protected function _getAutoCompleteMap()
    {
        $aRet = array();
        if (SGL_Config::get('ContentMgr.autoComplete')) {
            $aTypes = explode(',', SGL_Config::get('ContentMgr.autoComplete'));
            foreach ($aTypes as $typeString) {
            	$pos = strpos($typeString, ':');
            	if ($pos !== false) {
            	    $typeName = substr($typeString, 0, $pos);
            	    $fields   = substr($typeString, $pos + 1);
            	    $aFields  = explode('^', $fields);

            	    $aRet[$typeName] = $aFields;
            	}
            }
        }
        return $aRet;
    }

    protected function _initAutoComplete(SGL_Output $output)
    {
        $aMap     = $this->_getAutoCompleteMap();
        $typeName = $output->oContent->typeName;
        if (isset($aMap[$typeName])) {
            $output->addOnLoadEvent("cms.autoComplete.add('$typeName')", true);
            foreach ($aMap[$output->oContent->typeName] as $fieldName) {
                $output->addOnLoadEvent("cms.autoComplete.add('$fieldName')", true);
            }
            $output->addOnLoadEvent("cms.autoComplete.init()", true);
        }
    }
}

class Cms_AddContent extends SGL_Observable
{
    function __construct($input, $output)
    {
        $this->input = $input;
        $this->output = $output;
    }

    function run()
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

		$oCtx = new SGL_Context($this->input);
        $oContent = new SGL_Content($oCtx->process());
        $ok = $oContent->save();

        //  make content object available to observers
        $this->oContent = $oContent;

        if (!PEAR::isError($ok)) {
            //  pass to managers
            $this->input->contentId = $oContent->id;
            //  invoke observers
            $ret = $this->notify();
            if (!PEAR::isError($ret)) {
                SGL::raiseMsg('Content successfully added', false, SGL_MESSAGE_INFO);
                $ret = $ok;
            }
        } elseif ($ok->getCode() == DB_ERROR_ALREADY_EXISTS) {
            SGL::raiseError('Content with this name already exists, please give a unique name',
                SGL_ERROR_NOAFFECTEDROWS);
            $ret = false;
        } else {
            SGL::raiseError('There was a problem inserting the content',
                SGL_ERROR_NOAFFECTEDROWS);
            $ret = false;
        }
        return $ret;
    }
}

class Cms_UpdateContent extends SGL_Observable
{
    function __construct($input, $output)
    {
        $this->input = $input;
        $this->output = $output;
    }

    function run()
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $this->output->template = 'contentEdit.html';

		$oCtx = new SGL_Context($this->input);
        $oContent = new SGL_Content($oCtx->process());
        if ($this->input->newLang) {
            $oContent->langCode = $this->input->newLanguageId;
        }
        // always save as new version
        $ok = $oContent->save($newVersion = true, $this->input->newLang);

        //  make content object available to observers
        $this->oContent = $oContent;

        if (!PEAR::isError($ok)) {
            SGL::raiseMsg('Content successfully updated', false, SGL_MESSAGE_INFO);
            //  invoke observers
            $this->notify();
            // we return the saved content for further processing
            $ret = $ok;

        } elseif($ok->getCode() == DB_ERROR_ALREADY_EXISTS) {
            SGL::raiseError('Content with this name already exists, please give a unique name',
                SGL_ERROR_NOAFFECTEDROWS);
            $ret = false;
        } else {
            SGL::raiseError('There was a problem updating the content',
                SGL_ERROR_NOAFFECTEDROWS);
            $ret = false;
        }
        return $ret;
    }
}
?>
