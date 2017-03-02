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
// | CategoryMgr.php                                                           |
// +---------------------------------------------------------------------------+
// | Author:   Demian Turner <demian@phpkitchen.com>                           |
// +---------------------------------------------------------------------------+
// $Id: CategoryMgr.php,v 1.27 2005/05/17 23:54:51 demian Exp $

require_once 'SGL/Category.php';
require_once 'SGL/Translation3.php';
require_once SGL_MOD_DIR . '/cms/classes/MenuBuilder.php';
require_once SGL_MOD_DIR . '/cms/classes/CmsDAO.php';

/**
 * For performing operations on Category objects.
 *
 * @package publisher
 * @author  Demian Turner <demian@phpkitchen.com>
 * @version $Revision: 1.27 $
 */
class CategoryMgr extends SGL_Manager
{
    var $_redirectCatId = 1;
    var $_category = null;

    function CategoryMgr()
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);
        parent::SGL_Manager();

        $this->aggregateOutput = true;
        $this->pageTitle        = 'Category Manager';
        $this->template         = 'categoryMgr.html';
        $this->da               = CmsDAO::singleton();

        $this->_aActionsMapping =  array(
            'insert'    => array('insert', 'redirectToDefault'),
            'update'    => array('update', 'redirectToDefault'),
            'delete'    => array('delete', 'redirectToDefault'),
            'list'      => array('list'),
        );
        $this->_category = new CmsCategory();
    }

    function validate($req, &$input)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $this->validated        = true;
        $input->error           = array();
        $input->pageTitle       = $this->pageTitle;
        $input->masterTemplate  = 'masterLeftCol.html';
        $input->template        = $this->template;

        // We need the categories dictionary
        $trans = SGL_Translation3::singleton('array');
        $input->cLang = $req->get('cLang')
            ? $req->get('cLang')
            : $trans->defaultLangCode;
        $trans->loadDictionary('categories', $input->cLang);
        $input->aLanguages = $trans->getAvailableLanguages();

        //  form vars
        $input->submitted       = $req->get('submitted');
        $input->action          = ($req->get('action')) ? $req->get('action') : 'list';
        $input->category        = $req->get('category');
        $input->move            = $req->get('move');
        $input->targetId        = $req->get('targetId');
        $input->aDelete         = $req->get('frmDelete');

        if ($input->action == 'update') {
            $input->category_id         = $input->category['category_id'];
            $input->label               = $input->category['label'];
            $input->parent_id           = $input->category['parent_id'];
            $input->perms               = $input->category['perms'];
            $input->orginial_parent_id  = $input->category['original_parent_id'];
        } elseif ($input->action =='insert') {
            $input->category['parent_id'] = $req->get('frmCatID');
        } else {
            $input->category_id = ($req->get('frmCatID') == '') ? 1 : $req->get('frmCatID');
        }

        if (SGL::moduleIsEnabled('tag')) {
            $c = SGL_Config::singleton();
            $c->ensureModuleConfigLoaded('tag');
            $input->classifyByTags = true;
        } else {
            $input->classifyByTags = false;
        }

    }

    function _cmd_insert(&$input, &$output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $values = (array) $input->category;
        $this->_redirectCatId = $this->_category->create($values);
    }

    function _cmd_list(&$input, &$output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        //  load category
        if (!$this->_category->load($input->category_id)) {
            $output->noEditForm = 1;
            return;
        }
        $output->category = $this->_category->getValues();
        //  get category-media association
        $output->category['media_id'] = $this->da->getMediaIdByCategoryId($input->category_id);
        if (PEAR::isError($output->category)) {
            SGL::raiseError('problem fetching categories');
            return false;
        }
        $output->breadCrumbs = $this->_category->getBreadCrumbs($output->category['category_id']);
        $output->perms = $this->_category->getPerms();

        //  categories select box
        $options = array('exclude' => $output->category['category_id']);
        $menu = & new CmsMenuBuilder('SelectBox', $options);
        $aCategories = $menu->toHtml();
        $output->aCategories = $aCategories;

        //  get tags
        if ($input->classifyByTags) {
            $output->category['tags'] = $this->_getTags($input->category_id);
        }

        $redir = $input->getCurrentUrl()->toString();
        $output->redir = base64_encode($redir);
    }

    function _cmd_update(&$input, &$output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $aValues = (array) $input->category;
        $aValues['label'] = trim(trim($aValues['label'], '>'), '<');
        $trans = SGL_Translation3::singleton('array');
        $aValues['label_translation'] = $aValues['label'];
        if ($input->cLang != $trans->defaultLangCode) {
            $aValues['label'] = $aValues['label_original'];
        }

        //  set tags
        if ($input->classifyByTags) {
            $this->_setTags($input->category_id,$aValues['tags']);
        }

        $message = $this->_category->update($input->category_id, $aValues);
        if ($message != '') {
            // let's update category label translation
            $aString = array($aValues['label_original'], $aValues['label'], $aValues['label_translation']);
            $trans->update($aString, 'categories', $input->cLang);
            SGL::raiseMsg($message, true, SGL_MESSAGE_INFO);
            $this->_redirectCatId = $input->category_id;
        } else {
            SGL::raiseError('Problem updating category', SGL_ERROR_NOAFFECTEDROWS);
        }

    }

    function _cmd_delete(&$input, &$output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        //  do not allow deletion of root category
        if (in_array('1',$input->aDelete)) {
            SGL::raiseMsg('do not delete root category', true, SGL_MESSAGE_WARNING);

            $aParams = array(
                'moduleName'    => 'cms',
                'managerName'   => 'category',
                'action'        => 'list',
                );
            SGL_HTTP::redirect($aParams);
        }
        //  delete categories
        $this->_category->delete($input->aDelete);
        $output->category_id = 0;

        SGL::raiseMsg('The category has successfully been deleted', true, SGL_MESSAGE_INFO);
    }

    function _cmd_redirectToDefault(&$input, &$output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        //  if no errors have occured, redirect
        if (!SGL_Error::count()) {
            $aParams = array(
                'frmCatID' => $this->_redirectCatId
            );
            if (!empty($output->cLang)) {
                $aParams['cLang'] = $output->cLang;
            }
            SGL_HTTP::redirect($aParams);

        //  else display error with blank template
        } else {
            $output->template = 'docBlank.html';
        }
    }

    function display(&$output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        //  category nav widget
        $startParentNode = !empty($output->conf['CategoryMgr']['startParentNode'])
            ? (int)$output->conf['CategoryMgr']['startParentNode']
            : 0;

        $levelsToRender = !empty($output->conf['CategoryMgr']['levelsToRender'])
            ? (int)$output->conf['CategoryMgr']['levelsToRender']
            : 0;

        $aCategoryNavOptions = array(
            'startParentNode' => $startParentNode,
            'levelsToRender'  => $levelsToRender
        );

        require_once SGL_MOD_DIR . '/cms/classes/MenuBuilder.php';
        $menu = & new CmsMenuBuilder('UnorderedList', $aCategoryNavOptions);
        $output->categoryNav = $menu->toHtml();

        // js & css includes
        $output->addJavascriptFile(array(
            'js/jquery/jquery.js',
            'cms/js/string.js',
            'cms/js/cms.js',
            'cms/js/cms_category.js',
            'cms/js/jquery/jquery.simpletree.js'
        ), $optimize = false);

        $output->addCssFile(array(
            'cms/css/cms_category.css',
            'cms/css/jquery/simpletree/jquery.simpletree.css'
        ));
    }

    function _getTags($category_id)
    {
        require_once SGL_MOD_DIR . '/tag/lib/Tag.php';

        $oTag  = new SGL_Tag();
        return ($oTag->getTaggableByFkId($category_id,'category'))
            ? implode(', ',$oTag->getTags())
            : '';
    }

    function _setTags($category_id,$tags)
    {
        require_once SGL_MOD_DIR . '/tag/lib/Tag.php';

        $oTag  = new SGL_Tag();
        $oTag->getTaggableByFkId($category_id,'category');

        return $oTag->saveTags(SGL_Tag::parseTags($tags));
    }

}
?>
