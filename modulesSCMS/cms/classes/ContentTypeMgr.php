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
// | ContentTypeMgr.php                                                          |
// +---------------------------------------------------------------------------+
// | Author: Demian Turner <demian@phpkitchen.com>                             |
// +---------------------------------------------------------------------------+
// $Id: ContentTypeMgr.php,v 1.2 2005/02/26 21:02:21 demian Exp $

require_once SGL_MOD_DIR . '/cms/classes/CmsDAO.php';

/**
 * For performing operations on content type objects.
 *
 * @author  Demian Turner <demian@phpkitchen.com>
 */
class ContentTypeMgr extends SGL_Manager
{
    /**
     * Field Types
     *
     * @access  public
     * @var     array
     */
    var $attrTypes;

    /**
     * Constructor
     *
     * @access  public
     * @return  void
     */
    function ContentTypeMgr()
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);
        parent::SGL_Manager();

        $this->pageTitle    = 'Content Type Manager';
        $this->template     = 'contentTypeList.html';
        $this->masterLayout = 'layout-navtop-1col.css';
        $this->da           = CmsDAO::singleton();

        $this->attrTypes   = $this->da->getAttribTypeConstants();

        $this->_aActionsMapping =  array(
            'add'       => array('add'),
            'insert'    => array('insert', 'redirectToDefault'),
            'edit'      => array('edit'),
            'update'    => array('update', 'redirectToDefault'),
            'delete'    => array('delete', 'redirectToDefault'),
            'list'      => array('list'),
            'view'      => array('view'),
        );
    }

    /**
     * Validate
     *
     * @access  public
     * @param   object  $req    SGL_Request
     * @param   object  $input  SGL_Output
     * @return  void
     * @see     lib/SGL/SGL_Controller.php
     */
    function validate($req, &$input)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $this->validated    = true;
        $input->error       = array();
        $input->pageTitle   = $this->pageTitle;
        $input->masterLayout = $this->masterLayout;
        $input->template    = $this->template;
        $input->action      = ($req->get('action')) ? $req->get('action') : 'list';
        $input->aDelete     = $req->get('frmDelete');
        $input->submit      = $req->get('submitted');
        $input->contentType   = $req->get('contentType');
        $input->contentTypeId = $req->get('frmContentTypeId');

        //  Validate forms
        if ($input->submit) {
            if ($input->action == 'add') {
                if (empty($input->contentType['name'])) {
                    $aErrors['name'] = 'Please enter a name';
                    $input->action = 'list';
                }
            }
            //  transform post data
    		$oCtx = new SGL_Context($input);
            $oContent = new SGL_Content($oCtx->process());

    		if (!$oContent->validate()) {
                $aErrors['name'] = 'Ensure all attributes have names and no duplicates exist';
                $input->template = 'contentTypeEdit.html';
                $input->oContentType = $oContent;
    		} else {
    		    $input->oContent = $oContent;
    		}
            //  if errors have occured
            if (isset($aErrors) && count($aErrors)) {
                SGL::raiseMsg('Please fill in the indicated fields');
                $input->error = $aErrors;
                $this->validated = false;
            }
        }
    }

    function display(&$output)
    {
        $output->addJavascriptFile(array(
            'js/jquery/jquery.js',
            'js/jquery/plugins/jquery.form.js',
            'cms/js/string.js',
            'cms/js/cms.js',
            'cms/js/cms_contentType.js'
        ), $optimize = false);
        $output->addCssFile(array(
            'cms/css/cms.css',
        ));

        //  load available attribute types
        $output->attrTypes = $this->attrTypes;

        if (isset($output->error) && isset($output->error['name']) && $output->action == 'list') {
            $output->addOnLoadEvent('cms.contentType.creator.toggle()');
        }
        $output->aListTypes = $this->da->getAttributeListTypes();
    }

    /**
     * Creates array used to create field name/type form.
     *
     * @access  private
     * @param   object  $input
     * @param   object  $output
     * @return  void
     */
    function _cmd_add(&$input, &$output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $output->action = 'insert';
        $output->template = 'contentTypeEdit.html';

        //  Setup contentType
        $output->oContentType->typeName = $input->contentType['name'];
    }

    /**
     * Creates a new content type.
     *
     * @access  private
     * @param   object  $input
     * @param   object  $output
     * @return  void
     */
    function _cmd_insert(&$input, &$output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $ok = $input->oContent->save();

        if (!PEAR::isError($ok)) {
            SGL::raiseMsg('Content type has successfully been added', false, SGL_MESSAGE_INFO);
        } else {
            SGL::raiseError('There was a problem inserting the content type',
                SGL_ERROR_NOAFFECTEDROWS);
        }
    }

    /**
     * Retrieves data for selected content type.
     *
     * @access  private
     * @param   object  $input
     * @param   object  $output
     * @return  void
     */
    function _cmd_edit(&$input, &$output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $output->action = 'update';
        $output->template = 'contentTypeEdit.html';
        $output->oContentType = SGL_Content::getByType($input->contentTypeId);
    }

    /**
     * Updates Item Type Name on item_type table and Item Type fields on item_type_mapping table.
     *
     * @access  private
     * @param   object  $input
     * @param   object  $output
     * @return  void
     */
    function _cmd_update(&$input, &$output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $ok = $input->oContent->save();

        if (!PEAR::isError($ok)) {
            SGL::raiseMsg('Content type has successfully been updated', false, SGL_MESSAGE_INFO);
        } else {
            SGL::raiseError('There was a problem updating the content type',
                SGL_ERROR_NOAFFECTEDROWS);
        }

    }

    function _cmd_view(&$input, &$output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $output->template = 'contentTypeView.html';
        $output->oContentType = SGL_Content::getByType($input->contentTypeId);
        $output->id = $input->contentTypeId;
    }

    /**
     * Retrieves all content types w/ field names and types.
     *
     * @access  private
     * @param   object  $input
     * @param   object  $output
     * @return  void
     */
    function _cmd_list(&$input, &$output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);
        $output->aContentTypes = SGL_Finder::factory('contenttype')->retrieve();
    }

    /**
     * Deletes selected content type.
     *
     * @access  private
     * @param   object  $input
     * @param   object  $output
     * @return  void
     */
    function _cmd_delete(&$input, &$output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        if (is_array($input->aDelete)) {
            foreach ($input->aDelete as $contentTypeId) {

                //  delete content type
                $ok = $this->da->deleteContentTypeById($contentTypeId);
                if (PEAR::isError($ok)) {
                    SGL::raiseError('There was a problem deleting the content type',
                        SGL_ERROR_NOAFFECTEDROWS);
                    return false;
                }
                //  delete content type attribs
                $ok = $this->da->deleteAttribsByContentTypeId($contentTypeId);
                if (PEAR::isError($ok)) {
                    SGL::raiseError('There was a problem deleting the content attributes',
                        SGL_ERROR_NOAFFECTEDROWS);
                    return false;
                }
            }
            SGL::raiseMsg('Content type(s) have successfully been deleted', false, SGL_MESSAGE_INFO);
        } else {
            SGL::raiseError('Incorrect parameter passed to ' .
                __CLASS__ . '::' . __FUNCTION__, SGL_ERROR_INVALIDARGS);
        }
    }

    function _cmd_redirectToEdit(&$input, &$output)
    {
        //  must not logmessage here

        //  if no errors have occured, redirect
        if (!SGL_Error::count()) {
            $aParams = array(
                'action' => 'edit',
                'frmContentTypeId' => $input->contentType['id']
            );
            SGL_HTTP::redirect($aParams);

        //  else display error with blank template
        } else {
            $output->template = 'docBlank.html';
        }
    }
}
?>
