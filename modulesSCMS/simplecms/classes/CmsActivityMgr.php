<?php

require_once dirname(__FILE__) . '/SimpleCmsDAO.php';
require_once SGL_MOD_DIR . '/cms/classes/CmsDAO.php';
require_once SGL_MOD_DIR . '/user2/classes/User2DAO.php';
require_once 'Pager.php';

/**
 * @package simplecms
 * @author Dmitri Lakachauskis <lakiboy83@gmail.com>
 */
class CmsActivityMgr extends SGL_Manager
{
    public function __construct()
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);
        parent::SGL_Manager();

        $this->pageTitle      = 'Content Activity Manager';
        $this->masterTemplate = 'master.html';
        $this->template       = 'cmsactivityList.html';

        $this->_aActionsMapping = array(
            'list'   => array('list'),
            'search' => array('search')
        );

        $this->da = new SGL_Delegator();
        $this->da->add(SimpleCmsDAO::singleton());
        $this->da->add(CmsDAO::singleton());
        $this->da->add(User2DAO::singleton());
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

        $input->userId = $req->get('userId');
        $input->page   = $req->get('page');
        $input->search = $req->get('search');
    }

    public function display(SGL_Output $output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);
    }

    public function _cmd_search(SGL_Registry $input, SGL_Output $output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $userId = $this->da->getUserIdByUsername($input->search);
        if (!empty($userId)) {
            $redir = $input->getCurrentUrl()->makeLink(array(
                'moduleName'  => 'simplecms',
                'managerName' => 'cmsactivity',
                'userId'      => $userId
            ));
            SGL_HTTP::redirect($redir);
        }

        $output->template = 'cmsactivitySearch.html';
        $output->aParams['username'] = $input->search;
    }

    public function _cmd_list(SGL_Registry $input, SGL_Output $output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        if ($input->userId == 'all') {
            $input->userId = null;
        }

        $contentCount = $this->da->getContentsCount($input->userId);
        $url          = $input->getCurrentUrl()->makeCurrentLink(
            array('page' => 'page-id'));

        // page options
        $options = array(
            'totalItems'            => $contentCount,
            'currentPage'           => $input->page,
            'mode'                  => 'Sliding',
            'perPage'               => 15, // make it configurable
            'delta'                 => 3,
            'fileName'              => str_replace('page-id', '%d', $url),
            'path'                  => '',

            // taken from finder
            'curPageSpanPre'        => '<span>',
            'curPageSpanPost'       => '</span>',
            'spacesBeforeSeparator' => 1,
            'spacesAfterSeparator'  => 1,
            'append'                => false
        );
        $oPager = Pager::factory($options);

        // get offsets and limits needed for DAO
        list($from, $to) = $oPager->getOffsetByPageId();
        $limit  = $to - $from + 1;
        $offset = $from - 1;

        // re-format output
        $aContents = $this->da->getContentList($input->userId, $limit, $offset);
        $aRet      = array();
        foreach ($aContents as $oContent) {
        	$aRet[$oContent->date][] = $oContent;
        }
        $aContents = $aRet;

        $aContentTypes = $this->da->getContentTypes();
        if (!empty($input->userId)) {
            $output->oUser = $this->da->getUserById($input->userId);
        }

        $output->redir = $input->getCurrentUrl()->makeCurrentLink();

        // fix 'path=""' bug
        $output->pagerLinks    = str_replace('="/', '="', $oPager->links);
        $output->aContentTypes = $aContentTypes;
        $output->aContents     = $aContents;

        $output->addJavascriptFile(array(
            'js/jquery/plugins/jquery.suggest.js',
            'simplecms/js/CmsActivity.js'
        ));
        $output->addCssFile('admin/css/jquery/jquery.suggest.css');
        $output->addOnLoadEvent('SimpleCms.Activity.init()');
    }
}
?>