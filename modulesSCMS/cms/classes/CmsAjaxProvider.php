<?php
/* Reminder: always indent with 4 spaces (no tabs). */
// +---------------------------------------------------------------------------+
// | Copyright (c) 2008, Demian Turner                                         |
// | All rights reserved.                                                      |
// |                                                                           |
// | Redistribution and use in source and binary forms, with or without        |
// | modification, are permitted provided that the following conditions        |
// | are met:                                                                  |
// |                                                                           |
// | o Redistributions of source code must retain the above copyright          |
// |   notice, this list of conditions and the following disclaimer.           |
// | o Redistributions in binary form must reproduce the above copyright       |
// |   notice, this list of conditions and the following disclaimer in the     |
// |   documentation and/or other materials provided with the distribution.    |
// | o The names of the authors may not be used to endorse or promote          |
// |   products derived from this software without specific prior written      |
// |   permission.                                                             |
// |                                                                           |
// | THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS       |
// | "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT         |
// | LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR     |
// | A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT      |
// | OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,     |
// | SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT          |
// | LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,     |
// | DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY     |
// | THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT       |
// | (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE     |
// | OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.      |
// |                                                                           |
// +---------------------------------------------------------------------------+
// | Seagull 0.6                                                               |
// +---------------------------------------------------------------------------+
// | CmsAjaxProvider.php                                                       |
// +---------------------------------------------------------------------------+
// | Authors:   Thomas Goetz  <thomas@getconfuse.net>                          |
// +---------------------------------------------------------------------------+


/**
 * Wrapper to various methods for CmsDAO to use with AJAX.
 *
 * @package seagull
 * @subpackage cms
 */

require_once 'DB/DataObject.php';
require_once 'HTML/Template/Flexy.php';
require_once SGL_MOD_DIR . '/cms/classes/CmsDAO.php';
require_once SGL_MOD_DIR . '/media2/classes/Media2DAO.php';

class CmsAjaxProvider extends SGL_AjaxProvider
{
    /**
     *
     */
    function __construct()
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);
        parent::SGL_AjaxProvider();

        $daCms     = CmsDAO::singleton();
        $daMedia   = Media2DAO::singleton();
        $this->da  = new SGL_Delegator();
        $this->da->add($daCms);
        $this->da->add($daMedia);
        // In several methods, we'll use JSON
        $this->responseFormat = SGL_RESPONSEFORMAT_JSON;
    }

    function &singleton()
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);
        static $instance;

        // If the instance is not there, create one
        if (!isset($instance)) {
            $instance = new CmsAjaxProvider();
        }
        return $instance;
    }

    /**
     * Main workflow.
     *
     * @param SGL_Registry $input
     * @param SGL_Output $output
     *
     * @return void
     */
    public function process(SGL_Registry $input, SGL_Output $output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $req = $input->getRequest();
        $actionName = $req->getActionName();

        if (method_exists($this, $actionName)) {
            $ok = true; // by default request is authorised

            $container = ucfirst($req->getModuleName() . 'AjaxProvider');
            if (SGL_Config::get("$container.requiresAuth")
                && SGL_Config::get('debug.authorisationEnabled'))
            {
                $aMethods = explode(',', SGL_Config::get("$container.requiresAuth"));
                $aMethods = array_map('trim', $aMethods);
                if (in_array($actionName, $aMethods)) {
                    $ok = $this->_isOwner(SGL_Session::getUid());
                }
            }
            if (PEAR::isError($ok)) {
                return $ok;
            } elseif (!$ok) {
                SGL::raiseError('authorisation failed', SGL_ERROR_INVALIDAUTHORISATION);
                return;
            } else {
                $ret = $this->{$actionName}($input, $output);
                if (SGL_Error::count()) {
                    $err = SGL_Error::getLast();
                    SGL::raiseError($err);
                }
            }
        } else {
            SGL::raiseError('requested method does not exist');
            return;
        }
        $output->data = $ret;
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

        return in_array(SGL_Session::getRoleId(), array(SGL_ADMIN));
    }

/************************************/
/*      CONTENT TYPE METHODS        */
/************************************/

    function addContentType()
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $input->contentType = SGL_Request::singleton()->get('contentType');

        // insert new content type
        $newContentType = SGL_Content::createType($input->contentType['name']);

        if (!PEAR::isError($newContentType)) {
            // Returns new content type html code
            $output = new SGL_Output();
            $output->theme = 'default';
            $output->webRoot = SGL_Config::get('site.baseUrl');
            $output->moduleName = 'cms';
            $output->managerName = 'contenttype';
            $output->masterTemplate = 'contentType_contentType.html';
            $output->attrTypes  = $this->da->getAttribTypeConstants();
            $output->aListTypes = $this->da->getAttributeListTypes();
            $output->oContentType = $newContentType;
            $output->id = $newContentType->typeId;
            $templ = new SGL_HtmlSimpleView($output);
            $aResponse = array(
                'id'    => $newContentType->typeId,
                'html'  => $templ->render()
            );
        } elseif ($newContentType->getCode() == DB_ERROR_ALREADY_EXISTS) {
            //  Ensure content type name uniqueness
            SGL_Error::pop();
            $aResponse = array(
                'status'    => -1,
                'message'   => 'This content type name already exists'
            );
        } else {
            $aResponse = array(
                'status'    => -1,
                'message'   => 'There was a problem inserting the content type'
            );
        }

        return $aResponse;
    }

    function updateName()
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $req = SGL_Request::singleton();
        $input->contentType     = $req->get('contentType');
        $input->contentTypeId   = $req->get('contentTypeId');
        $ok = $this->da->updateContentTypeName($input->contentTypeId, $input->contentType['name']);


        if (!PEAR::isError($ok)) {
            $contentType = SGL_Content::getByType($input->contentTypeId);
            $aResponse = array(
                'contentType' => $contentType
            );
        } elseif ($ok->getCode() == DB_ERROR_ALREADY_EXISTS) {
            //  Ensure content type name uniqueness
            $aResponse = array(
                'status'        => -1,
                'contentTypeId' => $input->contentTypeId,
                'message'       => 'This content type name already exists'
            );
            SGL_Error::pop();
        } else {
            $aResponse =  array(
                'message'       => 'Problem while updating contentType name',
                'contentTypeId' => $input->contentTypeId,
                'html'          => $ok
            );
        }

        return $aResponse;
    }

    function deleteContentType()
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $contentTypeId = SGL_Request::singleton()->get('contentTypeId');
        $ok = $this->da->deleteContentTypeById($contentTypeId);
        //  delete content type attribs
        $ok = $this->da->deleteAttribsByContentTypeId($contentTypeId);
        if (PEAR::isError($ok)) {
            return $ok;
        } else {
            return $contentTypeId;
        }
    }

    function addAttribute()
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $req = SGL_Request::singleton();
        $contentTypeId  = $req->get('contentTypeId');
        $aAttrib        = $req->get('newAttribute');

        // by default no dupes are found
        $isDuplicated = false;

        // check for duplicated attribute names
        $aAttribs = $this->da->getContentTypeAttribsById($contentTypeId);
        foreach ($aAttribs as $oAttr) {
            if ($oAttr->attr_name == $aAttrib['fieldName']
                || $oAttr->attr_alias == $aAttrib['fieldAlias'])
            {
                $aResponse = array(
                    'contentTypeId' => $contentTypeId,
                    'status'        => -1,
                    'message'       => 'attribute with such name/alias already exists'
                );
                $isDuplicated = true;
                break;
            }
        }

        if (!$isDuplicated) {
            $aData = array(
                'name' => $aAttrib['fieldName'],
                'alias' => $aAttrib['fieldAlias'],
                'typeId' => $aAttrib['fieldType'],
                'params' => (!empty($aAttrib['fieldParams']))
                    ? $aAttrib['fieldParams']
                    : '',
            );
            $newAttribute   = new SGL_Attribute($aData);
            $attribId = $this->da->addAttrib($contentTypeId, $aData['name'], $aData['alias'], $aData['typeId'], $aData['params']);
            $newAttribute->id = $attribId;
            if (PEAR::isError($attribId)) {
                $aResponse = array(
                    'contentTypeId' => $contentTypeId,
                    'status'        => -1,
                    'message'       => 'An error occured while adding the attribute'
                );
            } else {
                $contentType    = SGL_Content::getByType($contentTypeId);
                $output         = new SGL_Output();
                $output->theme      = 'default';
                $output->webRoot    = SGL_Config::get('site.baseUrl');
                $output->moduleName = 'cms';
                $output->managerName= 'contenttype';
                $output->attrTypes  = $this->da->getAttribTypeConstants();
                $output->aListTypes = $this->da->getAttributeListTypes();
                $output->masterTemplate = 'contentType_attribute.html';
                $output->oAttrib    = $newAttribute;
                $templ = new SGL_HtmlSimpleView($output);
                $aResponse = array(
                    'html'          => $templ->render(),
                    'contentTypeId' => $contentTypeId,
                    'attribId'      => $attribId
                );
            }
        }
        return $aResponse;
    }

    function updateAttribute()
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $req = SGL_Registry::singleton()->getRequest();
        $aContentType   = $req->get('contentType');
        $attribId       = $req->get('attributeId');
        $aAttrib = $aContentType['attributes'][$attribId];
        $aData = array(
            'id'    => $attribId,
            'name'  => $aAttrib['fieldName'],
            'alias' => $aAttrib['fieldAlias'],
            'typeId'=> $aAttrib['fieldType'],
            'params'=> (!empty($aAttrib['fieldParams']))
                ? $aAttrib['fieldParams']
                : "",
        );
        $attribute = new SGL_Attribute($aData);
        $ok = $attribute->save();

        // Get a brand new Attribute so typeName is updated
        $attribute = SGL_Attribute::getById($aData['id']);

        if (!PEAR::isError($ok)) {
            $aResponse = $attribute;
        } else {
            $aResponse = "There was a problem when updating attribute";
        }

        return $aResponse;
    }

    function deleteAttribute()
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $attributeId = SGL_Request::singleton()->get('attributeId');
        $ok = $this->da->deleteAttribById($attributeId);
        if (PEAR::isError($ok)) {
            return $ok;
        } else {
            return $attributeId;
        }
    }

/**************************************/
/*      CONTENT ATRIBUTE LIST METHODS */
/**************************************/
    function addAttributeList()
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);
        $req = SGL_Request::singleton();

        $name        = $req->get('name');
        $aAttribList = $req->get('aAttribList');

        $aParams = array(
            'type'        => 'select',
            'multiple'    => false,
            'data-inline' => $aAttribList,
        );
        $attribListId = $this->da->addAttribList($name, $aParams);

        // @note does client need generated HTML?
        if (!PEAR::isError($attribListId)) {

            // render html
            $output                  = new SGL_Output();
            $output->theme           = 'default';
            $output->webRoot         = SGL_Config::get('site.baseUrl');
            $output->moduleName      = 'cms';
            $output->managerName     = 'attriblist';
            $output->masterTemplate  = 'attribList_editAttribList.html';
            $output->attributeListId = $attribListId;
            $output->aAttribList     = array(
                'name'   => $name,
                'fields' => $aAttribList,
            );

            $oView     = new SGL_HtmlSimpleView($output);
            $aResponse = array(
                'attributeListId' => $attribListId,
                'html'            => $oView->render(),
                'message'         => 'Attribute list added successfully'
            );
        } else {
            $aResponse = array(
//                'attributeListId' => $attrList->attribute_list_id,
                'status'  => -1,
                'message' => 'There was a problem adding the attribute list'
            );
        }
        return $aResponse;
    }

    function updateAttributeList()
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $req = SGL_Request::singleton();

        $attributeListId = $req->get('attributeListId');
        $name            = $req->get('name');
        $aAttribList     = $req->get('aAttribList');

        $oAttribList = $this->da->getAttribListById($attributeListId);

        // update params
        $aParams = unserialize($oAttribList->params);
        if (array_key_exists('data-inline', $aParams)) {
            $aParams['data-inline'] = $aAttribList;
        } elseif (array_key_exists('data', $aParams)) {
            $aParams['data'] = $aAttribList;
        }

        $ok = $this->da->updateAttribListById($attributeListId, array(
            'name'   => $name,
            'params' => $aParams
        ));

        if (PEAR::isError($ok)) {
            $aResponse = array(
                'attributeListId' => $attributeListId,
                'status'          => -1,
                'message'         => 'There was a problem while updating attribute list'
            );
        } else {
            $aResponse = array(
                'attributeListId' => $attributeListId,
                'message'         => 'Attribute list successfully updated'
            );
        }
        return $aResponse;
    }

    function deleteAttributeList()
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $attribListId = SGL_Request::singleton()->get('attributeListId');

        $ok = $this->da->deleteAttribListById($attribListId);

        if (PEAR::isError($ok)) {
            $aResponse = array(
                'attributeListId' => $attribListId,
                'status'          => -1,
                'message'         => 'There was a problem while deleting attribute list'
            );
        } else {
            $aResponse = array(
                'attributeListId' => $attribListId,
                'message'         => 'Attribute list successfully deleted'
            );
        }
        return $aResponse;
    }

/****************************/
/*      CONTENT METHODS     */
/****************************/
    function outputFilteredContents()
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $req = SGL_Request::singleton();

        $aFilter    = $req->get('aFilter');
        $sortBy     = $req->get('sortBy');
        $sortOrder  = $req->get('sortOrder');

        //  Check for sort field
        $sortBy = (!empty($sortBy)) ? $sortBy : 'last_updated';

        //  Check for sort order
        $sortOrder = (isset($sortOrder) && $sortOrder == 'asc') ? 'ASC' : 'DESC';

        //  Check for the filter array
        //  TODO : allow SGL_Finder::addFilter() to receive an array as a param
        $typeId = (isset($aFilter['typeId'])) ? $aFilter['typeId'] : 0;

        // filter by status
        $status = isset($aFilter['status']) ? $aFilter['status'] : 0;
        // filter by category
        $categoryId = isset($aFilter['categoryId']) ? $aFilter['categoryId'] : 0;
        // pass to manager
        SGL_Session::set('cmsContentListFilter', array(
            'typeId'     => $typeId,
            'status'     => $status,
            'categoryId' => $categoryId
        ));

        //  Prepare view
        $output = new SGL_Output();
        $output->theme = 'default';
        $output->webRoot = SGL_Config::get('site.baseUrl');
        $output->moduleName = 'cms';
        $output->managerName = 'contenttype';

        $oFinder = SGL_Finder::factory('content')
            ->addFilter('typeId', $typeId)
            ->addFilter('sortBy', $sortBy)
            ->addFilter('sortOrder', $sortOrder);
        if (!empty($categoryId)) {
            $oFinder->addFilter('categoryId', $categoryId);
        }
        if (!empty($status)) {
            $oFinder->addFilter('status', $status);
        }
        $aPagerOptions = array(
            'path'        => SGL_Output::makeUrl('', 'content', 'cms'),
            'currentPage' => 1
        );

        $output->aContents = $oFinder->paginate($aPagerOptions)->retrieve();
        $output->aStatusTypes = $this->da->getStatusTypes();

        //  add link info
        foreach ($output->aContents as $k => $oContent) {
            $output->aContents[$k]->numLinks = $this->da->contentHasAssocs($oContent->id);
        }

        //  activate list
        SGL_Session::set('cmsContentListActivated', true);

        $output->masterTemplate = 'contentList_items.html';
        $templ = new SGL_HtmlSimpleView($output);
        $html = $templ->render();
        $aResponse = array();
        if (!is_a($html, 'PEAR_Error')) {
            $aResponse = array(
                'status'    => 1,
                'html'      => $html,
                'message'   => 'Filter successfully applied'
            );

            // Get pagination info
            $pager = $oFinder->getPager();

            if ($pager) {
                $aResponse['pager'] = $pager->links;
            }
        } else {
            $aResponse = array(
                'status'        => -1,
                'message'       => 'There was a problem rendering Flexy template in ' . __CLASS__ . __FUNCTION__
            );
        }

        return $aResponse;
    }

    function outputContent($contentId, $mode)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        //  Prepare view
        $output = new SGL_Output();
        $output->theme = 'default';
        $output->webRoot = SGL_Config::get('site.baseUrl');
        $output->moduleName = 'cms';
        $output->managerName = 'contenttype';

        $oContent = SGL_Content::getById($contentId);
        $output->oContent = $oContent;
        $output->editable = ($mode == 'edit') ? true : false;

        $templateFile = 'contentTypes/' . SGL_Inflector::camelise($oContent->typeName) . '.html';
        $templatePath1 = SGL_WEB_ROOT . '/themes/' . SGL_Config::get('site.defaultTheme') . '/cms/' . $templateFile;
        $templatePath2 = SGL_MOD_DIR . '/cms/templates/' . $templateFile;
        $output->masterTemplate = (is_file($templatePath1) || is_file($templatePath2))
            ? $templateFile
            : 'contentView.html';

        $templ = new SGL_HtmlSimpleView($output);
        $html = $templ->render();
        if (!is_a($html, 'PEAR_Error')) {
            $ret[0] = true;
            $ret[1] = $html;
        } else {
            $ret[0] = false;
            $ret[1] = 'There was a problem rendering Flexy template in ' . __CLASS__ . __FUNCTION__;
        }

        return $ret;
    }

    function outputContentTypeAttributes()
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $this->responseFormat = SGL_RESPONSEFORMAT_HTML;
        //  Prepare view
        $output = new SGL_Output();
        $output->theme = 'default';
        $output->webRoot = SGL_Config::get('site.baseUrl');
        $output->moduleName = 'cms';
        $output->managerName = 'contenttype';

        $typeId = SGL_Request::singleton()->get('contentTypeId');

        $output->oContentType = SGL_Content::getByType($typeId);
        $output->id = $typeId;
        //  load available attribute types
        $output->attrTypes  = $this->da->getAttribTypeConstants();
        $output->aListTypes = $this->da->getAttributeListTypes();
        $output->masterTemplate = 'contentType_attributes.html';

        $templ = new SGL_HtmlSimpleView($output);
        $html = $templ->render();
        if (!is_a($html, 'PEAR_Error')) {
            $ret = $html;
        } else {
            $ret[0] = false;
            $ret[1] = 'There was a problem rendering Flexy template in ' . __CLASS__ . __FUNCTION__;
        }

        return $ret;
    }

    function outputEditLinkWindow()
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $this->responseFormat = SGL_RESPONSEFORMAT_HTML;
        // Get params
        $req = SGL_Request::singleton();
        $linkHref = $req->get('linkHref');
        $linkText = $req->get('linkText');
        $attribId = $req->get('attribId');

        //  Prepare view
        $output = new SGL_Output();
        $output->moduleName     = 'cms';
        $output->theme          = 'default';
        $output->masterTemplate = 'linkAssoc.html';
        $output->webRoot        = SGL_Config::get('site.baseUrl');
        $output->scriptOpen     = "\n<script type=\"text/javascript\"> <!--\n";
        $output->scriptClose    = "\n//--> </script>\n";

        $output->linkHref = urldecode($linkHref);
        $output->linkText = urldecode($linkText);
        $output->attribId = $attribId;

        if (preg_match('#' . SGL_BASE_URL . '#', $linkHref)) {
            $output->linkType = 'internal';
        } else {
            $output->linkType = 'external';
        }

        $templ = new SGL_HtmlSimpleView($output);
        $html = $templ->render();
        if (!is_a($html, 'PEAR_Error')) {
            return $html;
        } else {
            header('status: 500 Internal server error');// TODO : put this in SGL_Task_ExecuteAjaxAction
            return 'There was a problem rendering Flexy template in ' . __CLASS__ . __FUNCTION__;
        }
    }

    function updateContentNameById($contentId, $name)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        //  get content to update
        $oContent = SGL_Content::getById($contentId);
        $oContent->name = $name;
        $ok = $oContent->save();
        return $ok;
    }

    function updateAttributeValueById()
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $oFormAttrib = (object) SGL_Request::singleton()->get('attrib');
        //  get attribute to update
        $oAttrib = SGL_Attribute::getById($oFormAttrib->id);
        $oAttrib->contentId     = $oFormAttrib->contentId;
        $oAttrib->version       = $oFormAttrib->version;
        $oAttrib->langCode      = $oFormAttrib->langCode;
        //  deal with cases where a choice selected value === 0
        //$value = (isset($value) && ($value || $value === 0)) ? $value : 'Insert content here';
        $oAttrib->set($oFormAttrib->value);
        $ok = $this->da->updateAttribData($oAttrib);
        return $ok;
    }

    function getFoo()
    {
        $val = SGL_Registry::singleton()->getRequest()->get('myvalue');
        return $val;
    }

    function addAttribList()
    {
        $aElemList = SGL_Registry::singleton()->getRequest()->get('elemList');

        // prepare params
        $elems  = urldecode($aElemList['elems']);
        $aElems = explode(',', urldecode($elems));
        $aData  = array();
        foreach ($aElems as $elem) {
            list($k, $v) = split("\|", $elem);
            $aData[$k]   = $v;
        }

        $name    = SGL_String::toValidVariableName($aElemList['name']);
        $aParams = array(
            'type'        => 'select',
            'multiple'    => false,
            'data-inline' => $aData,
        );
        $ok = $this->da->addAttribList($name, $aParams);

        if (!PEAR::isError($ok)) {
            $ret = 'Attribute list added successfully';
        } else {
            $ret = 'There was a problem adding the attribute list';
        }
        return $ret;
    }

    function getTranslations()
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $req    = SGL_Request::singleton();
        $lang   = $req->get('lang');
        $dict   = (string) $req->get('dictionary');
        $langId = str_replace('-', '_', $lang);

        //  fetch dictionnaries
        if (!empty($dict)) {
            $aDictionaries = explode(',', $dict);
        } else {
            $aDictionaries = array();
        }

        $aTranslations = array();
        foreach ($aDictionaries as $dictionary) {
            $aWords = SGL_Translation::getGuiTranslationsFromFile($dictionary, $langId);
            if (is_array($aWords)) {
                $aTranslations = array_merge($aTranslations, $aWords);
            }
        }

        if (count($aTranslations)) {
            $aResponse = array(
                'status' => 1,
                'lang' => $lang,
                'dictionaries' => $aDictionaries,
                'translations' => $aTranslations
            );
        } else {
            $aResponse = array(
                'status' => -1,
                'msg' => 'Could not fetch a dictionnary'
            );
        }

        return $aResponse;
    }

    //  advanced search methods
    function addQueryFilter()
    {
        $filterName = SGL_Registry::singleton()->getRequest()->get('filterName');

        // prepare output
        $output = new SGL_Output();
        $output->moduleName  = 'cms';
        $output->theme       = 'default';
        $output->manager     = 'query';

        switch ($filterName) {
            case 'status':
                $output->aStatusTypes   = $this->da->getStatusTypes();
                $output->masterTemplate = 'query_statusFilter.html';
                break;

            case 'contentType':
                $output->aContentTypes  = $this->da->getContentTypes();
                $output->masterTemplate = 'query_contentTypeFilter.html';
                break;

            case 'createdBy':
                $output->aUserList      = $this->_getUserList();
                $output->masterTemplate = 'query_createdByFilter.html';
                break;

            case 'contentName':
                $output->masterTemplate = 'query_contentName.html';
                break;

            default:
        }
        $this->responseFormat = SGL_RESPONSEFORMAT_HTML;
        $view = new SGL_HtmlSimpleView($output);
        return $view->render();
    }

    function getAttributesByContentTypeId()
    {
        $contentTypeId = (int)SGL_Registry::singleton()
            ->getRequest()->get('contentTypeId');
        return $this->da->getContentTypeAttribsById($contentTypeId);
    }

    function getQueryResult()
    {
        $status      = SGL_Registry::singleton()->getRequest()->get('status');
        $createdBy   = SGL_Registry::singleton()->getRequest()->get('createdBy');
        $contentType = SGL_Registry::singleton()->getRequest()->get('contentType');
        $contentName = SGL_Registry::singleton()->getRequest()->get('contentName');
        $aAttributes = SGL_Registry::singleton()->getRequest()->get('aAttributes');

        $oFinder = SGL_Finder::factory('content');

        if ($status > 0) { // filter by status
            // filtering by one status is supported for now
            $oFinder->addFilter('status', (int)$status);
        }
        if ($createdBy) { // filter by user
            $oFinder->addFilter('createdBy', (int)$createdBy);
        }
        if ($contentType) {
            $oFinder->addFilter('typeId', (int)$contentType);
        }
        if (!empty($contentName)) {
            $oFinder->addFilter('nameSearch', array(
                'operator' => 'like',
                'value'    => $contentName
            ));
        }
        if (!empty($aAttributes)) { // filter by attrib values
            foreach ($aAttributes as $attributeName => $attributeValue) {
                $oFinder->addFilter('attribute', array(
                    'name'     => $attributeName,
                    'operator' => 'LIKE',
                    'value'    => '%' . $this->dbh->escapeSimple($attributeValue) . '%'
                ));
            }
        }
        $this->responseFormat = SGL_RESPONSEFORMAT_JAVASCRIPT;
        $aContents = $oFinder->retrieve();
        if (empty($aContents)) {
            return false;
        }

        // prepare output
        $output = new SGL_Output();
        $output->moduleName     = 'cms';
        $output->theme          = 'default';
        $output->manager        = 'query';
        $output->aContents      = $aContents;
        $output->aStatusTypes   = $this->da->getStatusTypes();
        $output->masterTemplate = 'contentList_items.html';
        $view = new SGL_HtmlSimpleView($output);
        return $view->render();
    }

    function _getUserList()
    {
        $query = "
            SELECT   usr_id, username
            FROM     {$this->conf['table']['user']}
            ORDER BY username
        ";
        return $this->dbh->getAssoc($query);
    }
    function isContentNameUnique()
    {
        $this->responseFormat = SGL_RESPONSEFORMAT_JSON;

        $req = SGL_Request::singleton();

        $contentName = $req->get('contentName');
        $contentId   = $req->get('contentId');

        return !$this->da->contentNameExists($contentName,$contentId);
    }

/****************************/
/*     CATEGORY METHODS     */
/****************************/
    function outputCategoryEdit()
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $this->responseFormat = SGL_RESPONSEFORMAT_JSON;
        $req = SGL_Request::singleton();
        $categoryId    = (int)$req->get('frmCatID');

        //  Prepare view
        $output = new SGL_Output();
        $output->theme = 'default';
        $output->webRoot = SGL_Config::get('site.baseUrl');
        $output->moduleName = 'cms';
        $output->managerName = 'category';
        $output->masterTemplate = 'category_editCategory.html';
        $output->category_id = $categoryId;
        $output->cLang = $req->get('cLang');
        $output->redir = base64_encode($output->makeUrl('', 'category', 'cms',
            array(), 'frmCatID|' . $categoryId));

        //  load category
        $oCategory = new CmsCategory();

        if (!$oCategory->load($output->category_id)) {
            $output->noEditForm = 1;
            return;
        }
        $output->category = $oCategory->getValues();
        //  get category-media association
        $output->category['media_id'] = $this->da->getMediaIdByCategoryId($output->category_id);
        if (PEAR::isError($output->category)) {
            SGL::raiseError('problem fetching categories');
            return false;
        }
        // We need the categories dictionary
        require_once 'SGL/Translation3.php';
        $trans = SGL_Translation3::singleton('array');
        $trans->loadDictionary('categories', $output->cLang);
        $output->aLanguages = $trans->getAvailableLanguages();

        $req->set('managerName', 'category');
        $req->set('action', 'list');
        $output->breadCrumbs = $oCategory->getBreadCrumbs($output->category['category_id']);
        $output->perms = $oCategory->getPerms();

        //  categories select box
        require_once SGL_MOD_DIR . '/cms/classes/MenuBuilder.php';
        $options = array('exclude' => $output->category['category_id']);
        $menu = new CmsMenuBuilder('SelectBox', $options);
        $aCategories = $menu->toHtml();
        $output->aCategories = $aCategories;

        //  get tags
        if (SGL::moduleIsEnabled('tag')) {
            require_once SGL_MOD_DIR . '/tag/lib/Tag.php';

            $output->classifyByTags = true;
            $oTag  = new SGL_Tag();
            $output->category['tags'] = ($oTag->getTaggableByFkId($output->category_id,'category'))
                ? implode(', ',$oTag->getTags())
                : '';
        }

        // Render view
        $templ = new SGL_HtmlSimpleView($output);
        $html = $templ->render();
        if (!is_a($html, 'PEAR_Error')) {
            $ret = $html;
        } else {
            $ret[0] = false;
            $ret[1] = 'There was a problem rendering Flexy template in ' . __CLASS__ . __FUNCTION__;
        }
        return $ret;
    }

    function reorderCategory()
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $this->responseFormat = SGL_RESPONSEFORMAT_JSON;
        $req = SGL_Request::singleton();

        $categoryId     = (int)$req->get('frmCatID');
        $targetId       = (int)$req->get('targetId');
        $position       = $req->get('position');
        $aPositions     = array('BE','AF','SUB');

        if (isset($categoryId, $targetId) && (in_array($position,$aPositions))) {
            //  load category
            $oCategory = new CmsCategory();
            $oCategory->move($categoryId, $targetId, $position);
            return array(
                'category_id'   => $categoryId,
                'status'        => 1,
                'message'       => 'Categories reordered successfully'
            );
        } else {
            return array(
                'status'        => -1,
                'message'       => "Incorrect parameter passed to " . __CLASS__ . '::' .
                __FUNCTION__, SGL_ERROR_INVALIDARGS
            );
        }
    }

}
?>
