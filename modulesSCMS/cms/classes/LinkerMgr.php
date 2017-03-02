<?php
require_once SGL_MOD_DIR . '/cms/classes/CmsDAO.php';

class LinkerMgr extends SGL_Manager
{
    function __construct()
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);
        parent::SGL_Manager();

        $this->da = CmsDAO::singleton();
        $this->pageTitle = 'Content Linking Manager';
        $this->masterTemplate = 'masterNoCols.html';
        $this->template = 'linkerList.html';
        $this->_aActionsMapping =  array(
            'edit'      => array('edit'),
            'update'    => array('update', 'redirectToDefault'),
        );
    }

    function validate($req, &$input)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $this->validated        = true;
        $input->error           = array();
        $input->pageTitle       = $this->pageTitle;
        $input->template        = $this->template;

        //  form vars
        $input->action          = ($req->get('action')) ? $req->get('action') : 'edit';
        $input->contentTypeId   = (int)$req->get('frmContentTypeId');
        $input->contentId       = (int)$req->get('frmContentId');
        $input->aContentAssocIds= $req->get('frmContentAssocIds');
    }

    function _cmd_edit(&$input, &$output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        //  get content types
        $aContentTypes = $this->da->getContentTypes();
        asort($aContentTypes);
        $output->aContentTypes = $aContentTypes;

        $aContents = SGL_Finder::factory('content')
            ->addFilter('typeId', $input->contentTypeId)
            ->retrieve();
        //  reformat data for combobox
        $aOut = array();
        foreach ($aContents as $oContent) {
            $aOut[$oContent->id] = $oContent->name;
        }
        //  remove calling content item, ie an item cannot be linked to itself
        unset($aOut[$input->contentId]);

        $output->aContents = $aOut;
        $output->aAssocIds = $this->da->getContentAssocsByContentId($input->contentId);

        //  get content caller name
        $oContent = SGL_Content::getById($input->contentId);
        $output->contentName = $oContent->name;
    }

    function _cmd_update(&$input, &$output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        if (empty($input->aContentAssocIds)) {
            $input->aContentAssocIds = array();
        }
        // saved from main screen
        if (empty($input->contentTypeId)) {
            $aNewAssocs = $input->aContentAssocIds;

        // saved from specific ct screen
        } else {
            $aOldAssocsAll = $this->da->getContentAssocsByContentId($input->contentId);
            $aOldAssocsCt  = $this->da->getContentAssocsByContentId($input->contentId, $input->contentTypeId);
            $aOldAssocs    = array_diff($aOldAssocsAll, $aOldAssocsCt);
            $aNewAssocs    = array_merge($aOldAssocs, $input->aContentAssocIds);
        }

        $ok = $this->da->addContentAssocsByContentId($input->contentId, $aNewAssocs);
        if (!PEAR::isError($ok)) {
            SGL::raiseMsg('Content successfully linked', false, SGL_MESSAGE_INFO);
        } else {
            SGL::raiseError('There was a problem linking the content',
                SGL_ERROR_NOAFFECTEDROWS);
        }
    }

    function _cmd_redirectToDefault(&$input, &$output)
    {
        //  must not logmessage here

        //  if no errors have occured, redirect
        if (!SGL_Error::count()) {
            SGL_HTTP::redirect(array(
                'action' => 'list',
                'managerName' => 'content',
                'moduleName' => 'cms'));

        //  else display error with blank template
        } else {
            $output->template = 'docBlank.html';
        }
    }
}
?>
