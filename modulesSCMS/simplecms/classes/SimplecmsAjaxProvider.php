<?php

require_once SGL_MOD_DIR . '/user2/classes/User2DAO.php';
require_once SGL_MOD_DIR . '/cms/classes/CmsDAO.php';
require_once SGL_MOD_DIR . '/simplecms/classes/SimpleCmsDAO.php';

/**
 * Ajax provider.
 *
 * @package simplecms
 * @author Dmitri Lakachauskis <lakiboy83@gmail.com>
 */
class SimplecmsAjaxProvider extends SGL_AjaxProvider2
{
    public function __construct()
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);
        parent::__construct();

        $this->da = new SGL_Delegator;
        $this->da->add(User2DAO::singleton());
        $this->da->add(CmsDAO::singleton());
        $this->da->add(SimpleCmsDAO::singleton());
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

    public function getContensFilteredList(SGL_Registry $input, SGL_Output $output)
    {
        // sorting parameters
        $typeId     = $this->req->get('typeId');
        $status     = $this->req->get('status');
        $langId     = $this->req->get('langId');
        $resPerPage = $this->req->get('resPerPage');
        $sortBy     = $this->req->get('sortBy');
        $sortOrder  = $this->req->get('sortOrder');
        $pageId     = $this->req->get('pageId');

        // sorting parameters variants
        $aContentTypes = $this->da->getContentTypes();
        $aStatuses     = $this->da->getContentStatusList();
        $aLangs        = SimpleCms_Util::formatLanguageList(SGL_Util::getLangsDescriptionMap());
        $aResPerPage   = SimpleCms_Util::getPageRanges();
        $aSortFields   = array('last_updated', 'status', 'username');
        $aSortOrder    = array('asc', 'desc');

        // validation
        if ($typeId != 'all' && !array_key_exists($typeId, $aContentTypes)) {
            $typeId = 'all';
        }
        if ($status != 'all' && !array_key_exists($status, $aStatuses)) {
            $status = 'all';
        }
        if (!array_key_exists($langId, $aLangs)) {
            $langId = SGL_Translation3::getDefaultLangCode();
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
        $aFilter              = SGL_Session::get('simplecms_filter');
        $aFilter['sortBy']    = $sortBy;
        $aFilter['sortOrder'] = $sortOrder;
        SGL_Session::set('simplecms_filter', $aFilter);

        // get contents
        $oFinder = SGL_Finder::factory('content');
        if ($typeId != 'all') {
            $oFinder->addFilter('typeId', $typeId);
        }
        if ($status != 'all') {
            $oFinder->addFilter('status', $status);
        }
        $aContents = $oFinder
            ->addFilter('sortBy', $sortBy)
            ->addFilter('sortOrder', $sortOrder)
            ->addFilter('lang', $langId)
            ->paginate(array(

                // force current page and number of items per page
                'currentPage' => $pageId,
                'perPage'     => $resPerPage,

                // generate proper links
                'fileName'    => '#%d',
                'path'        => ''
            ))
            ->retrieve();

        $aLangs        = SimpleCms_Util::formatLanguageList(SGL_Util::getLangsDescriptionMap());
        $aStatuses     = $this->da->getContentStatusList();
        $aStatusesData = array();
        foreach ($aStatuses as $statusId => $statusName) {
            $aStatusesData[$statusId] = array(
                'status_id' => $statusId,
                'name'      => $statusName . ' (status)',
                'className' => end(explode(' ', $statusName)),
            );
        }

        // render contents
        $output->html = $this->_renderTemplate($output, array(
            'masterTemplate' => 'cmscontent_tablelist.html',
            'aContents'      => $aContents,
            'aStatusesData'  => $aStatusesData,
            'aLangs'         => $aLangs,
            'cLang'          => $langId,
            'theme'          => $_SESSION['aPrefs']['admin theme'],
        ));
        $output->pagerLinks = str_replace('="/', '="', $oFinder->getPager()->pageLinks);
    }

    public function updateContentStatus(SGL_Registry $input, SGL_Output $output)
    {
        $contentId = $this->req->get('contentId');
        $status    = $this->req->get('status');
        $lang      = $this->req->get('cLang');

        $oContent = SGL_Content::getById($contentId, $lang);
        $oContent->setStatus($status);
        $oContent->save();
    }

    public function deleteContent(SGL_Registry $input, SGL_Output $output)
    {
        $contentId = $this->req->get('contentId');
        $lang      = $this->req->get('cLang');
        $redir     = $this->req->get('redir');

        $oContent = SGL_Content::getById($contentId, $lang);
        $oContent->delete($safe = false);

        // post process actions
        $input->contentId = $contentId;
        $input->oContent  = $oContent;
        $oPostProcess     = new SimpleCms_Observable($input, $output);
        $oPostProcess->attachMany(SGL_Config::get('CmsContentMgr.deleteObservers'));
        $oPostProcess->notify();

        if (empty($redir)) {
            $redir = $input->getCurrentUrl()->makeLink(array(
                'moduleName'  => 'simplecms',
                'managerName' => 'cmscontent',
            ));
        }
        $output->redir = $redir;
    }

    public function updateContent(SGL_Registry $input, SGL_Output $output)
    {
        $oData     = (object) $this->req->get('content', true);
        $redir     = $this->req->get('redir');
        $goBack    = $this->req->get('submitted');
        $refresh   = $this->req->get('submittedContinue');
        $aAssocIds = $this->req->get('assocs') ? $this->req->get('assocs') : array();

        $newVersion = false;
        $newLang    = false;

        // add new content or create new language version
        if (!empty($oData->type_id)) {
            $oContent = SGL_Content::getByType($oData->type_id);

            // we need this hack to enable unique content names
            $oContent->name = sprintf('%s - %s - %s - %s',
                $oData->type_id,
                $oData->lang,
                SGL_Session::getUid(),
                time()
            );

            // specify explicitly the content language
            $oContent->langCode = $oData->lang;

            // we add new language version
            if (!empty($oData->id)) {
                $oContent->id = $oData->id;
                $newLang      = true;
            }

            $observersString = SGL_Config::get('CmsContentMgr.createObservers');

        // edit existing content
        } else {
            $oContent        = SGL_Content::getById($oData->id, $oData->lang);
            $newVersion      = true;
            $observersString = SGL_Config::get('CmsContentMgr.updateObservers');
        }

        // populate content with fields
        foreach ($oData->attr as $attrName => $attrValue) {
            if (is_array($attrValue)) {
                $attrValue = implode(';', $attrValue);
            }
            $oContent->$attrName = $attrValue;
        }
        // add categories
        if (SGL::moduleIsEnabled('simplecategory')) {
            $oContent->aClassifiers['categories'] = isset($oData->aClassifiers['categories'])
                ? $oData->aClassifiers['categories']
                : array();
        }
        $oContent->setStatus($oData->status);
        $oContent->save($newVersion, $newLang);

        // save assoc contents
        $this->da->addContentAssocsByContentId($oContent->id, $aAssocIds);

        // post process actions
        $input->oData    = $oData;
        $input->oContent = $oContent;
        $oPostProcess    = new SimpleCms_Observable($input, $output);
        $oPostProcess->attachMany($observersString);
        $oPostProcess->notify();

        if ($refresh) {
            $goto = $input->getCurrentUrl()->makeLink(array(
                'moduleName'  => 'simplecms',
                'managerName' => 'cmscontent',
                'action'      => 'edit',
                'cLang'       => $oContent->langCode,
                'contentId'   => $oContent->id
            ));
            $redir = !empty($redir) ? $goto . '?redir=' . $redir : $goto;
        } elseif (empty($redir)) {
            $redir = $input->getCurrentUrl()->makeLink(array(
                'moduleName'  => 'simplecms',
                'managerName' => 'cmscontent',
            ));
        }

        // result
        $output->result = $oContent->id;
        $output->redir  = $redir;
    }

    public function renderMediaField(SGL_Registry $input, SGL_Output $output)
    {
        $attrId    = $this->req->get('attrId');
        $attrName  = $this->req->get('attrName');
        $attrValue = $this->req->get('mediaId');

        // fixme
        require_once dirname(__FILE__) . '/Output.php';
        $output->html = SimpleCmsOutput::renderMediaFieldForEdit($attrName,
            $attrValue, $attrId);
    }

    public function matchUsersByPattern(SGL_Registry $input, SGL_Output $output)
    {
        $q = $this->req->get('q');

        $aRet = array();
        $aUsers = $this->da->findUsersByPattern($q);
        foreach ($aUsers as $oUser) {
            $aRet[] = $oUser->username . ' (' . $oUser->first_name .
                ' ' . $oUser->last_name . ')';
        }
        $output->data = implode("\n", $aRet);
        $this->responseFormat = SGL_RESPONSEFORMAT_HTML;
    }

    public function matchContentsByPattern(SGL_Registry $input, SGL_Output $output)
    {

        //  TODO: Change autocomplete js to interface this function
        //  with params: contentTypeId & langId

        $q             = $this->req->get('q');
        $contentTypeId = $this->req->get('assoc_contentTypeId');
        $langId        = $this->req->get('assoc_langId');

        $content = $this->req->get('content');
        if (!empty($content)) {
            $contentTypeId = $content['type'];
            $langId        = $content['langId'];
        }

        $aContentIds = $this->da->getContentIdsByPattern($q, $langId, $contentTypeId);

        // prepare output
        $aRet = array();
        foreach ($aContentIds as $contentId) {
            $oContent = SGL_Content::getById($contentId, $langId);
            $aRet[]   = $oContent->aAttribs[0]->value
                . ' | ' . $oContent->typeName
                . ' (' . $oContent->id . ')';
        }

        $output->data = implode("\n", $aRet);
        $this->responseFormat = SGL_RESPONSEFORMAT_HTML;
    }

    // -----------------------
    // --- Attribute lists ---
    // -----------------------

    /**
     * Delete attribute list.
     *
     * @param SGL_Registry $input
     * @param SGL_Output $output
     */
    public function deleteAttributeList(SGL_Registry $input, SGL_Output $output)
    {
        $listId = $this->req->get('id');

        $this->da->deleteAttribListById($listId);
    }

    public function updateAttributeList(SGL_Registry $input, SGL_Output $output)
    {
        $listId = $this->req->get('listId');
        $oList  = (object) $this->req->get('list');

        if (empty($oList->fields)) {
            $oList->fields = array();
        }

        $oCurrentList = $this->da->getAttribListById($listId);

        // update params
        $aParams = $this->_getAttributeListParams(
            unserialize($oCurrentList->params),
            $oList->fields
        );

        // update list
        $this->da->updateAttribListById($listId, array(
            'name'   => $oList->name,
            'params' => $aParams
        ));

        $oList->id    = $listId;
        $output->html = $this->_renderTemplate($output, array(
            'masterTemplate' => 'admin_attribute_item_view.html',
            'theme'          => $_SESSION['aPrefs']['admin theme'],
            'oAttr'          => $oList
        ));
    }

    public function addAttributeList(SGL_Registry $input, SGL_Output $output)
    {
        $oList = new stdClass;

        $oList->id   = $this->da->addAttribList('');
        $oList->name = '';

        $output->html = $this->_renderTemplate($output, array(
            'isAdd'          => true,
            'masterTemplate' => 'admin_attribute_item_full.html',
            'theme'          => $_SESSION['aPrefs']['admin theme'],
            'oAttr'          => $oList,
        ));
        $output->listId = $oList->id;
    }

    protected function _getAttributeListParams($aCurrentParams, $aNewParams)
    {
        if (empty($aCurrentParams)) {
            $aCurrentParams = array('data' => $aNewParams);
        } elseif (array_key_exists('data-inline', $aCurrentParams)) {
            $aCurrentParams['data-inline'] = $aNewParams;
        } elseif (array_key_exists('data', $aCurrentParams)) {
            $aCurrentParams['data'] = $aNewParams;
        }
        return $aCurrentParams;
    }

    // ---------------------
    // --- Content types ---
    // ---------------------

    /**
     * Delete content type.
     *
     * @param SGL_Registry $input
     * @param SGL_Output $output
     */
    public function deleteContentType(SGL_Registry $input, SGL_Output $output)
    {
        $contentTypeId = $this->req->get('id');

        // delete content type entity + relevant attrib entities
        $ok = $this->da->deleteContentTypeById($contentTypeId);
        if (!PEAR::isError($ok)) {
            $ok = $this->da->deleteAttribsByContentTypeId($contentTypeId);
        }
    }

    public function getContentTypeAttributes(SGL_Registry $input, SGL_Output $output)
    {
        $contentTypeId = $this->req->get('id');
        $viewType      = $this->req->get('type');

        $tpl = $viewType == 'view'
            ? 'admin_contenttype_item_view.html'
            : 'admin_contenttype_item_form.html';

        $aAttribs = $this->da->getContentTypeAttribsById($contentTypeId);
        foreach ($aAttribs as $oAttr) {
            if (!empty($oAttr->attr_params)) {
                $oAttr->attr_params = unserialize($oAttr->attr_params);
            }
        }

        $aAttribTypes = $this->da->getAttribTypeConstants();
        $aAttribLists = SGL_Finder::factory('attriblist')->retrieve();

        // prepare lists
        $aLists = array();
        foreach ($aAttribLists as $oList) {
            $aLists[$oList->id] = $oList->name; // . ' (' . $oList->total . ')';
        }

        $totalContents = $this->da->getContentsCount($userId = null, $contentTypeId);

        $aTplParams = array(
            'masterTemplate' => $tpl,
            'aAttribs'       => $aAttribs,
            'aLists'         => $aLists,
            'aAttribTypes'   => $aAttribTypes,
            'contentTypeId'  => $contentTypeId,
            'theme'          => $_SESSION['aPrefs']['admin theme'],
            'aStats'         => array('total' => $totalContents),
            'conf'           => $input->getConfig()
        );
        // get content type name for edit template
        if ($viewType == 'edit') {
            $aContentTypes = $this->da->getContentTypes();
            $aTplParams['contentTypeName'] = $aContentTypes[$contentTypeId];
        }
        $output->html = $this->_renderTemplate($output, $aTplParams);
    }

    public function addContentType(SGL_Registry $input, SGL_Output $output)
    {
        $oCt = SGL_Content::createType('');
        if (PEAR::isError($oCt)) {
            SGL_Error::pop();
            $this->_raiseMsg(array(
                'message' => 'fix existent blank content type',
                'type'    => SGL_MESSAGE_ERROR
            ), true);
            $output->isAdded = false;

        // create content type with 2 demo text fields
        } else {
            $this->da->addAttrib($oCt->typeId, '', '', SGL_CONTENT_ATTR_TYPE_TEXT);
            $this->da->addAttrib($oCt->typeId, '', '', SGL_CONTENT_ATTR_TYPE_TEXT);

            // run post add routine
            $oPostProcess = new SimpleCms_Observable($input, $output);
            $oPostProcess->attachMany(SGL_Config::get('CmsContentTypeMgr.createObservers'));
            $oPostProcess->notify();

            // render edit tpl
            $this->req->set('id', $oCt->typeId);
            $this->req->set('type', 'edit');
            $this->getContentTypeAttributes($input, $output);

            $output->html = $this->_renderTemplate($output, array(
                'masterTemplate' => 'admin_contenttype_item_new.html',
                'contentTypeId'  => $oCt->typeId,
                'form'           => $output->html,
                'style'          => ' style="display: none"',
                'theme'          => $_SESSION['aPrefs']['admin theme'],
            ));
            $output->contentTypeId = $oCt->typeId;
            $output->isAdded       = true;
        }
    }

    public function updateContentType(SGL_Registry $input, SGL_Output $output)
    {
        $contentTypeId   = $this->req->get('id');
        $contentTypeName = $this->req->get('name');
        $aAttrs          = $this->req->get('attr');

        $validated = false;

        $aContentTypes      = $this->da->getContentTypes();
        $oldContentTypeName = $aContentTypes[$contentTypeId];
        unset($aContentTypes[$contentTypeId]);

        // check for content name uniquness
        if (in_array($contentTypeName, $aContentTypes)) {
            $this->_raiseMsg(array(
                'type'    => SGL_MESSAGE_ERROR,
                'message' => 'content type with such name exists'
            ), true);

        // no attrs check
        } elseif (empty($aAttrs)) {
            $this->_raiseMsg(array(
                'type'    => SGL_MESSAGE_ERROR,
                'message' => 'specify at least one attribute'
            ), true);

        // content type name check for proper chars
        } elseif (preg_match('/[^a-z0-9_ ]/i', $contentTypeName)) {
            $this->_raiseMsg(array(
                'type'    => SGL_MESSAGE_ERROR,
                'message' => 'content type name error'
            ), true);

        // attr uniqueness check
        } else {
            $attrCount  = count($aAttrs['alias']);
            $attrCount2 = count(array_unique($aAttrs['alias']));
            if ($attrCount != $attrCount2) {
                $this->_raiseMsg(array(
                    'type'    => SGL_MESSAGE_ERROR,
                    'message' => 'there are attributes with duplicated names'
                ), true);
            } else {

                $input->aNewAttrIds        = array();
                $input->aDelAttrIds        = array();
                $input->ctId               = $contentTypeId;
                $input->contentTypeName    = $contentTypeName;
                $input->contentTypeNameOld = $oldContentTypeName;

                // prepare new attributes
                $aNewAttrs = array();
                $newAttrId = 0;
                foreach ($aAttrs['id'] as $index => $attrId) {
                    if (empty($attrId)) {
                        $attrId = --$newAttrId;
                    }
                    $aNewAttrs[$attrId] = array(
                        'alias'  => $aAttrs['alias'][$index],
                        'name'   => $aAttrs['name'][$index],
                        'typeId' => $aAttrs['type_id'][$index],
                        'params' => null
                    );
                    if ($attrId > 0) {
                        $aNewAttrs[$attrId]['id'] = $attrId;
                    }
                    if (in_array($aNewAttrs[$attrId]['typeId'], array(
                        SGL_CONTENT_ATTR_TYPE_CHOICE,
                        SGL_CONTENT_ATTR_TYPE_LIST,
                        SGL_CONTENT_ATTR_TYPE_RADIO)))
                    {
                        $aNewAttrs[$attrId]['params'] = serialize(array(
                            'attributeListId' => $aAttrs['list_id'][$index]
                        ));
                    }
                }

                // update/remove old attribs
                $aCurrentAttrs = $this->da->getContentTypeAttribsById($contentTypeId);
                foreach ($aCurrentAttrs as $oCurrentAttr) {

                    // attribute was removed
                    if (!array_key_exists($oCurrentAttr->attr_id, $aNewAttrs)) {
                        $ok = $this->da->deleteAttribById($oCurrentAttr->attr_id);
                        $input->aDelAttrIds[] = $oCurrentAttr->attr_id;

                    // update existing attribute
                    } else {
                        $oAttribute = new SGL_Attribute($aNewAttrs[$oCurrentAttr->attr_id]);
                        $ok = $oAttribute->save();
                        unset($aNewAttrs[$oCurrentAttr->attr_id]);
                    }
                }

                // add new attributes
                foreach ($aNewAttrs as $aNewAttr) {
                    $attribId = $this->da->addAttrib($contentTypeId,
                        $aNewAttr['name'], $aNewAttr['alias'],
                        $aNewAttr['typeId'], $aNewAttr['params']
                    );
                    $input->aNewAttrIds[] = $attribId;
                }

                // update name
                $ok = $this->da->updateContentTypeName($contentTypeId, $contentTypeName);

                $validated = true;

                // run post update routine
                $oPostProcess = new SimpleCms_Observable($input, $output);
                $oPostProcess->attachMany(SGL_Config::get('CmsContentTypeMgr.updateObservers'));
                $oPostProcess->notify();
            }
        }

        if ($validated) {
            $output->aNewAttrIds     = $input->aNewAttrIds;
            $output->contentTypeName = $contentTypeName;

            // render view tpl
            $this->req->set('type', 'view');
            $this->getContentTypeAttributes($input, $output);
        }
        $output->validated = $validated;
    }

    public function getContentTypeEditAttribute(SGL_Registry $input, SGL_Output $output)
    {
        $aAttribTypes = $this->da->getAttribTypeConstants();
        $aAttribLists = SGL_Finder::factory('attriblist')->retrieve();

        // prepare lists
        $aLists = array();
        foreach ($aAttribLists as $oList) {
            $aLists[$oList->id] = $oList->name; // . ' (' . $oList->total . ')';
        }

        $output->html = $this->_renderTemplate($output, array(
            'masterTemplate' => 'admin_contenttype_item_form_attr.html',
            'aLists'         => $aLists,
            'aAttribTypes'   => $aAttribTypes,
            'theme'          => $_SESSION['aPrefs']['admin theme'],
        ));
    }
}

?>