<?php

require_once 'SimpleCms/Util.php';
require_once SGL_MOD_DIR . '/cms/classes/CmsDAO.php';

/**
 * @package simplecms
 * @author Dmitri Lakachauskis <lakiboy83@gmail.com>
 */
class CmsContentMgr extends SGL_Manager
{
    public function __construct()
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);
        parent::SGL_Manager();

        $this->pageTitle      = 'Content Manager';
        $this->masterTemplate = 'master.html';
        $this->template       = 'cmscontentList.html';

        $this->_aActionsMapping = array(
            'list' => array('list'),
            'edit' => array('edit'),
            'add'  => array('add')
        );

        $this->da = new SGL_Delegator();
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

        // edit action
        $input->contentId = $req->get('contentId');
        $input->versionId = $req->get('versionId');

        // we supply 0 by default (needed for routes)
        if (empty($input->versionId)) {
            $input->versionId = null;
        }
        if (empty($input->contentId)) {
            $input->contentId = null;
        }

        // list action
        $input->type       = $req->get('type');
        $input->status     = $req->get('status');
        $input->cLang      = $req->get('cLang');
        $input->sortBy     = $req->get('sortBy');
        $input->sortOrder  = $req->get('sortOrder');
        $input->resPerPage = $req->get('resPerPage');
        $input->page       = $req->get('page');

        $input->aStatuses     = $this->da->getContentStatusList();
        $input->aContentTypes = $this->da->getContentTypes();
        $input->aLangs        = SimpleCms_Util::formatLanguageList(SGL_Util::getLangsDescriptionMap());
        $input->aSortFields   = array(
            'last_updated', 'status', 'username'
        );
        $input->aSortOrder    = array('asc', 'desc');
        $input->aResPerPage   = SimpleCms_Util::getPageRanges();

        $aDefaults = array(
            'type'       => 'all',
            'status'     => 'all',
            'cLang'      => SGL_Translation3::getDefaultLangCode(),
            'sortBy'     => reset($input->aSortFields),
            'sortOrder'  => end($input->aSortOrder),
            'resPerPage' => reset($input->aResPerPage)
        );
        $aFilter = SGL_Session::get('simplecms_filter');
        if (!is_array($aFilter)) {
            $aFilter = $aDefaults;
        }

        // validation
        if ($input->type != 'all' && !array_key_exists($input->type, $input->aContentTypes)) {
            $input->type = $aFilter['type'];
        }
        if ($input->status != 'all' && !array_key_exists($input->status, $input->aStatuses)) {
            $input->status = $aFilter['status'];
        }
        if (!array_key_exists($input->cLang, $input->aLangs)) {
            $input->cLang = $aFilter['cLang'];
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

        // update filter in session only for content list
        if ($input->action == 'list') {
            foreach ($aDefaults as $fieldName => $fieldVal) {
                $aFilter[$fieldName] = $input->$fieldName;

                if (!in_array($fieldName, array('sortBy', 'sortOrder'))) {
                    SGL_Request::singleton()->set($fieldName, $input->$fieldName);
                }
            }
            SGL_Session::set('simplecms_filter', $aFilter);
        }
    }

    public function display(SGL_Output $output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $aStatusesData = array();
        foreach ($output->aStatuses as $statusId => $statusName) {
            $aStatusesData[$statusId] = array(
                'status_id' => $statusId,
                'name'      => $statusName . ' (status)',
                'className' => end(explode(' ', $statusName)),
            );
            $output->aStatuses[$statusId] = SGL_Output::tr($aStatusesData[$statusId]['name']);
        }
        $output->aStatusesData     = $aStatusesData;
        $output->categoriesEnabled = SGL::moduleIsEnabled('simplecategory');
    }

    public function _cmd_list(SGL_Registry $input, SGL_Output $output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $oFinder = SGL_Finder::factory('content');
        if ($input->type != 'all') {
            $oFinder->addFilter('typeId', $input->type);
        }
        if ($input->status != 'all') {
            $oFinder->addFilter('status', $input->status);
        }
//        $url = $input->getCurrentUrl()->makeCurrentLink(array('page' => 'page-id'));
        $aContents = $oFinder
            ->addFilter('sortBy', $input->sortBy)
            ->addFilter('sortOrder', $input->sortOrder)
            ->addFilter('lang', $input->cLang)
            ->paginate(array(

                // force current page and number of items per page
                'currentPage' => $input->page,
                'perPage'     => $input->resPerPage,

                // generate proper links
//                'fileName'    => str_replace('page-id', '%d', $url),
                'fileName'    => '#%d',
                'path'        => ''
            ))
            ->retrieve();

        foreach ($output->aSortFields as $i => $fieldVal) {
            unset($output->aSortFields[$i]);
            $output->aSortFields[$fieldVal] = SGL_Output::tr($fieldVal . ' (field)');
        }
        foreach ($output->aSortOrder as $i => $fieldVal) {
            unset($output->aSortOrder[$i]);
            $output->aSortOrder[$fieldVal] = SGL_Output::tr($fieldVal . ' (sort order)');
        }

        // fix 'path=""' bug
        $output->pagerLinks = str_replace('="/', '="', $oFinder->getPager()->pageLinks);
        $output->aContents  = $aContents;
        $output->addJavascriptFile(array(
            'simplecms/js/CmsContent.js'
        ));
        $output->addOnloadEvent('CmsContent.List.init()', false);
    }

    public function _cmd_edit(SGL_Registry $input, SGL_Output $output)
    {
        $lang = SGL::getCurrentLang();

        $oContent     = SGL_Content::getById($input->contentId,
            $input->cLang, $input->versionId);
        $aVersions    = $this->da->getVersionsByContentId($input->contentId,
            $input->cLang);
        $aAssocIds    = $this->da->getContentAssocsByContentId($input->contentId);
        $templatePath = SimpleCms_Util::getContentTypeTemplatePath(
            $oContent->typeName);

        if (file_exists($templatePath)) {
            $output->contentTemplate = file_get_contents($templatePath);
        }

        // init assoc contents
        $aAssocContents = array();
        if (!empty($aAssocIds)) {
            foreach ($aAssocIds as $assocId) {
                $oAssocContent = SGL_Content::getById($assocId, $input->cLang);
                if (!PEAR::isError($oAssocContent)) {
                    $aAssocContents[] = $oAssocContent;
                } else {
                    SGL_Error::pop();
                }
            }
        }

        $output->aAssocContents = $aAssocContents;
        $output->isEdit         = true;
        $output->aVersions      = $aVersions;

        // common for add/edit
        $output->wysiwyg   = true;
        $output->oContent  = $oContent;
        $output->template  = 'cmscontentEdit.html';
        $output->addJavascriptFile(array(
            'admin/js/jquery/jquery.suggest.js',

            'media2/js/Media2/Browser.js',

            'simplecms/js/jquery/jquery.form.js',
            'simplecms/js/jquery/jquery.bgiframe.js',
            'simplecms/js/jquery/ui/ui.core.js',
            'simplecms/js/jquery/ui/ui.tabs.js',
            'simplecms/js/jquery/ui/ui.dialog.js',
            // datepicker
            'simplecms/js/jquery/ui/i18n/ui.datepicker-' . $lang . '.js',
            'simplecms/js/jquery/ui/ui.datepicker.js',

            'simplecms/js/CmsContent.js'
        ));
        $output->addOnloadEvent('CmsContent.Edit.init()', false);
        $output->addCssFile(array(
            'simplecms/css/jquery/ui/ui.core.css',
            'simplecms/css/jquery/ui/ui.theme.css',
            'simplecms/css/jquery/ui/ui.tabs.css',
            'simplecms/css/jquery/ui/ui.dialog.css',
            'simplecms/css/jquery/ui/ui.datepicker.css',

            'media2/css/Browser.css',

            //'admin/css/jquery/ui.tabs.css',
            'admin/css/jquery/jquery.suggest.css'
        ));
    }

    public function _cmd_add(SGL_Registry $input, SGL_Output $output)
    {
        $lang = SGL::getCurrentLang();

        $oContent = SGL_Content::getByType($input->type);
        $oContent->langCode = $input->cLang;
        $oContent->id       = $input->contentId;
        $oContent->status   = SGL_STATUS_PUBLISHED;

        $output->isNew = empty($input->contentId) ? true : false;

        // common for add/edit
        $output->wysiwyg  = true;
        $output->oContent = $oContent;
        $output->template = 'cmscontentEdit.html';
        $output->addJavascriptFile(array(
            'admin/js/jquery/jquery.suggest.js',

            'media2/js/Media2/Browser.js',

            'simplecms/js/jquery/jquery.form.js',
            'simplecms/js/jquery/jquery.bgiframe.js',
            'simplecms/js/jquery/ui/ui.core.js',
            'simplecms/js/jquery/ui/ui.tabs.js',
            'simplecms/js/jquery/ui/ui.dialog.js',
            // datepicker
            'simplecms/js/jquery/ui/i18n/ui.datepicker-' . $lang . '.js',
            'simplecms/js/jquery/ui/ui.datepicker.js',

            'simplecms/js/CmsContent.js'
        ));
        $output->addOnloadEvent('CmsContent.Edit.init()', false);

        $output->addCssFile(array(
            'simplecms/css/jquery/ui/ui.core.css',
            'simplecms/css/jquery/ui/ui.theme.css',
            'simplecms/css/jquery/ui/ui.tabs.css',
            'simplecms/css/jquery/ui/ui.dialog.css',
            'simplecms/css/jquery/ui/ui.datepicker.css',
            'media2/css/Browser.css',
        ));
    }
}
?>