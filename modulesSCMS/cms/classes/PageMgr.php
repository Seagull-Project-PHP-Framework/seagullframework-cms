<?php

require_once SGL_MOD_DIR  . '/default/classes/DefaultDAO.php';
require_once SGL_MOD_DIR  . '/cms/classes/CmsDAO.php';
require_once SGL_MOD_DIR  . '/cms/classes/NavigationDAO.php';
require_once SGL_MOD_DIR  . '/user/classes/UserDAO.php';

/**
 * To administer sections.
 *
 * @package navigation
 * @author  Demian Turner <demian@phpkitchen.com>
 */
class PageMgr extends SGL_Manager
{
    function __construct()
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);
        parent::SGL_Manager();

        $this->pageTitle      = 'Page Manager';
        $this->masterTemplate = 'masterMinimal.html';
        $this->template       = 'pageList.html';

        $daDefault = DefaultDAO::singleton();
        $daUser    = UserDAO::singleton();
        $daNav     = CmsNavigationDAO::singleton();
        $daCms     = CmsDAO::singleton();
        $this->da  = new SGL_Delegator();
        $this->da->add($daDefault);
        $this->da->add($daUser);
        $this->da->add($daNav);
        $this->da->add($daCms);
        $this->_aActionsMapping =  array(
            'add'       => array('add'),
            'insert'    => array('insert', 'redirectToDefault'),
            'edit'      => array('edit'),
            'update'    => array('update', 'redirectToDefault'),
            'reorder'   => array('reorder', 'redirectToDefault'),
            'delete'    => array('delete', 'redirectToDefault'),
            'list'      => array('list'),
        );
    }

    function validate(SGL_Request $req, SGL_Registry $input)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        // Forward default values
        $input->pageTitle   = $this->pageTitle;
        $input->masterTemplate = $this->masterTemplate;
        $input->template    = $this->template;
        $input->error       = array();
        $input->action      = ($req->get('action')) ? $req->get('action') : 'list';
        $input->aDelete     = $req->get('frmDelete');

        // We need the navigation dictionary
        $trans = SGL_Translation3::singleton('array');
        $input->cLang = $req->get('cLang')
            ? $req->get('cLang')
            : $trans->defaultLangCode;
        $trans->loadDictionary('navigation', $input->cLang);
        $input->aLanguages = $trans->getAvailableLanguages();

        //  Retrieve form values
        $input->pageId      = $req->get('frmPageId');
        $input->targetId    = $req->get('targetId');
        $input->move        = $req->get('move');
        $input->page        = $req->get('page');
        $input->page['is_enabled'] = (isset($input->page['is_enabled'])) ? 1 : 0;
        $input->articleType = @$input->page['articleType'];
        $input->contentTypeId = @$input->page['contentTypeId'];

        if (is_null($input->articleType)) {
            $input->articleType = 'static';
        }
        //  flatten perm IDs for easy DB storage
        $input->page['perms'] = (isset($input->page['perms'])
                && count($input->page['perms']))
            ? join(',',  $input->page['perms'])
            : null;

        //  Misc.
        $this->validated  = true;
        $input->submitted = $req->get('submitted');
        $input->aParams   = $req->get('aParams', $allowTags = true);
        $input->isAdd     = $req->get('isadd');
        $input->mode      = $req->get('mode');

        //  validate form data
        if ($input->submitted) {
            if (empty($input->page['title'])) {
                $aErrors['title'] = 'Please fill in a title';
            }
            //  zero is a valid property, refers to public role
            if (is_null($input->page['perms'])) {
                $aErrors['perms'] = 'Please assign viewing rights to least one role';
            }
            //  If a child, need to make sure its is_enabled status OK with parents
            //  Only warn if they attempt to make child active when a parent is inactive
            if (($input->action == 'update' || $input->action == 'insert') && $input->page['parent_id'] != 0) {
                $parent = $this->da->getSectionById($input->page['parent_id']);
                if ( $parent['is_enabled'] == 0 &&  $input->page['is_enabled'] == 1) {
                    $aErrors['is_enabled']['string'] = 'You cannot activate unless you first activate.';
                    $aErrors['is_enabled']['args'][] = (!empty($input->page['title']))
                        ?  $input->page['title']
                        : ($input->action == 'insert')
                            ? ''
                            : $input->page['title_original'];
                    $aErrors['is_enabled']['args'][] = $parent['title'];
                }
                //  check child has same or subset of parents permissions
                if ($input->page['perms']) {
                    $aPerms = explode(',', $input->page['perms']);
                    $aParentPerms = explode(',', $parent['perms']);
                    foreach ($aPerms as $permID) {
                        if (!in_array($permID, $aParentPerms) && !in_array(SGL_ANY_ROLE, $aParentPerms)) {
                            $aErrors['perms']['string'] = 'To access this section, a user must have access to the parent section.';
                            $aErrors['perms']['args'][] = $parent['title'];
                            break;
                        }
                    }
                }
            }
            if (isset( $input->page['staticArticleId'])
                    && $input->page['staticArticleId'] == 0) {
                $aErrors['staticArticleId'] = 'You must select a valid article';
            }


        } elseif (!empty($input->page['edit'])) {
            unset($input->aParams);
            $this->validated = false;
        }
        //  if errors have occured
        if (isset($aErrors) && count($aErrors)) {
            SGL::raiseMsg('Please fill in the indicated fields', true, SGL_MESSAGE_ERROR);
            $input->error    = $aErrors;
            $this->validated = false;
        }

        if (!$this->validated) {
            $input->template = 'pageEdit.html';
            $this->_editDisplay($input);
        }
    }

    function _cmd_add(SGL_Registry $input, SGL_Output $output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);
        $output->template = 'pageEdit.html';
        $output->mode     = 'New page';
        $output->isAdd    = true;
        $output->page['is_enabled'] = 1;
        $this->_editDisplay($output);
    }

    function _cmd_edit(SGL_Registry $input, SGL_Output $output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $output->mode     = 'Edit page';
        $output->template = 'pageEdit.html';

        $page = $this->da->getSectionById($input->pageId);
        $page['title_original']      = $page['title'];
        $page['is_enabled_original'] = $page['is_enabled'];
        $page['parent_id_original']  = $page['parent_id'];
        $output->page = $page;
        $this->_editDisplay($output);
    }

    function _cmd_insert(SGL_Registry $input, SGL_Output $output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        if (!count($input->page)) {
            SGL::raiseError('No data in input object', SGL_ERROR_NODATA);
            return false;
        }
        if (!empty($input->aParams)) {
            $input->section['aParams'] = $input->aParams;
        }
        if ($this->da->addSection($input->page)) {
            SGL::raiseMsg('Page successfully added', true, SGL_MESSAGE_INFO);
        } else {
            SGL::raiseMsg('There was a problem inserting the record', true, SGL_MESSAGE_ERROR);
        }
        //  clear cache so a new cache file is built reflecting changes
        SGL_Cache::clear('nav');
    }

    function _cmd_update(SGL_Registry $input, SGL_Output $output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        if (!empty($input->aParams)) {
            $input->page['aParams'] = $input->aParams;
        }

        $trans = SGL_Translation3::singleton('array');
        $input->page['title_translation'] = $input->page['title'];
        if ($input->cLang != $trans->defaultLangCode) {
            $input->page['title'] = $input->page['title_original'];
        }

        if ($this->da->updateSection($input->page)) {
            // let's update page title translation
            $aString = array($input->page['title_original'], $input->page['title'],
                $input->page['title_translation']);
            $trans->update($aString, 'navigation', $input->cLang);
            SGL::raiseMsg('Page details successfully updated', true, SGL_MESSAGE_INFO);
        } else {
            SGL::raiseMsg('There was a problem updating the record', true, SGL_MESSAGE_ERROR);
        }
        //  clear cache so a new cache file is built reflecting changes
        SGL_Cache::clear('nav');
    }

    function _cmd_delete(SGL_Registry $input, SGL_Output $output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        if (is_array($input->aDelete)) {
            foreach ($input->aDelete as $index => $pageId) {
                $this->da->deleteSectionById($pageId);
            }
            SGL::raiseMsg('The selected page(s) have successfully been deleted',
                true, SGL_MESSAGE_INFO);

            //  clear cache so a new cache file is built reflecting changes
            SGL_Cache::clear('nav');
        } else {
            SGL::raiseMsg('There is no page to delete',
                true, SGL_MESSAGE_ERROR);
        }
    }

    function _cmd_reorder(SGL_Registry $input, SGL_Output $output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $aMoveTo = array('BE' => 'up',
                         'AF' => 'down');
        if (isset($input->pageId, $input->targetId)
                && ($pos = array_search($input->move, $aMoveTo))) {
            $this->da->moveSection($input->pageId, $input->targetId, $pos);
        } else {
            SGL::raiseError("Incorrect parameter passed to " . __CLASS__ . '::' .
                __FUNCTION__, SGL_ERROR_INVALIDARGS);
        }
        //  clear cache so a new cache file is built reflecting changes
        SGL_Cache::clear('nav');
        SGL::raiseMsg('Pages reordered successfully', true, SGL_MESSAGE_INFO);
    }

    /**
     * Generate list of all nodes displayed in their hierarchy.
     *
     * Gets set of section nodes in tree-style order, and adds to each an array of
     * images to represent place in tree. $output->results is array of section nodes
     * and their values, including tree-builder images. $output->sectionArrayJS is
     * a string Javascript array representing the tree, for use by JS confirmDelete()
     * in mainAdmin.js.
     *
     * @access  private
     * @param   object $input    not used;might want to eliminate; here only for consistency with other process methods
     * @return  object $output
     */
    function _cmd_list(SGL_Registry $input, SGL_Output $output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);
        $output->template = 'pageList.html';
        $output->mode     = 'Browse';

        //  get all sections
        $aSections        = $this->da->getSectionTree();
        $output->results  = $aSections;

        $output->pageArrayJS = $this->_createNodesArrayJS($aSections);
        $output->addOnLoadEvent("switchRowColorOnHover()");
    }

    function display(SGL_Output $output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $output->addJavascriptFile(array(
            'js/jquery/jquery.js',
        ), $optimize = false);
    }

    function _editDisplay(SGL_Output $output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        //  pre-check enabled box
        $output->pageIsEnabled  = !empty($output->page['is_enabled'])
            ? 'checked="checked"'
            : '';
        //  get array of section node objects
        $output->pageNodesOptions[0] = SGL_String::translate('Top level (no parent)');
        $aNodesForSelect = $this->da->getSectionsForSelect($output->cLang);
        if (is_array($aNodesForSelect) && count($aNodesForSelect)) {
            $output->pageNodesOptions  = $output->pageNodesOptions
                                          + $aNodesForSelect;
        }
        switch (@$output->page['uriType']) {

        case 'dynamic':
            $output->dynamicSelected = 'selected';

            //  build dynamic page choosers
            $output->aModules = SGL_Util::getAllModuleDirs();
            reset($output->aModules);
            $currentModule = isset($output->page['module'])
                ? $output->page['module']
                : key($output->aModules);
            $output->aManagers = SGL_Util::getAllManagersPerModule(SGL_MOD_DIR .'/'. $currentModule);
            $currentMgr = (isset($output->page['manager'])
                        && isset($output->aManagers[$output->page['manager']]))
                ? $output->page['manager']
                : key($output->aManagers);
            $output->aActions = ($currentMgr != 'none')
                ? SGL_Util::getAllActionMethodsPerMgr(SGL_MOD_DIR .'/'. $currentModule .'/classes/'. $currentMgr)
                : array();
            break;

        case'uriExternal':
            $output->uriExternalSelected = 'selected';
            break;

        case'uriEmpty':
            $output->uriEmptySelected = 'selected';
            break;

        case'uriNode':
            $output->uriNodeSelected = 'selected';

            //  get array of section node objects for internal link
            $output->pageNodesOptions2 = $this->da->getSectionsForSelect();
            break;

        default: //static
            $output->staticSelected = 'selected';

            //  build static article list
            if (SGL::moduleIsEnabled('cms')) {

                //  get content types
                $aContentTypes = $this->da->getContentTypes();
                asort($aContentTypes);
                $output->aContentTypes = $aContentTypes;

                //  get content items
                if (!isset($output->contentTypeId) && isset($output->page['staticArticleId'])) {
                    $oContent = SGL_Content::getById($output->page['staticArticleId']);
                    $output->contentTypeId = $oContent->getType();
                }
                if (isset($output->contentTypeId) && is_numeric($output->contentTypeId)) {

                    $aContents = SGL_Finder::factory('content')
                        ->addFilter('typeId', $output->contentTypeId)
                        ->retrieve();
                    $aContentIds = array();
                    foreach ($aContents as $oContent) {
                        $aContentIds[$oContent->id] = $oContent->name;
                    }
                    $output->aContentIds = $aContentIds;
                } else {
                    $output->aContents = array();
                }

            } else {
                $output->aContentTypes = array('' => 'invalid w/out cms module');
            }
        }

        //  build role widget
        $aRoles[SGL_ANY_ROLE]   = SGL_String::translate('All roles');
        $aRoles[SGL_GUEST]      = SGL_String::translate('guest');
        $output->aRoles         = $aRoles + $this->da->getRoles();
        $output->aSelectedRoles = isset($output->page['perms'])
            ? explode(',', $output->page['perms'])
            : SGL_ANY_ROLE;
    }

    /**
     * Creates and returns a string representing JavaScript array of node info arrays,
     * for use by JS confirmDelete() in mainAdmin.js.
     *
     * @access  private
     * @param   array   $nodesArray an array of nodes arrays
     * @return  string  representation of a JavaScript array, for use
     */
    function _createNodesArrayJS($nodesArray)
    {
        $nodesArrayJS = '';
        if (is_array($nodesArray) && count($nodesArray)) {
            foreach($nodesArray as $node) {
                //need to build string array for Javascript confirmDelete()
                //now since Flexy won't compile stuff inside JS tags
                $nodesArrayJS .= 'nodeArray[' . $node['page_id'] . "] = new Array();\n";
                $nodesArrayJS .= 'nodeArray[' . $node['page_id'] . '][0] = ' . $node['left_id'] . ";\n";
                $nodesArrayJS .= 'nodeArray[' . $node['page_id'] . '][1] = ' . $node['right_id'] . ";\n";
                $nodesArrayJS .= 'nodeArray[' . $node['page_id'] . '][2] = "' . $node['title'] . "\";\n";
                $nodesArrayJS .= 'nodeArray[' . $node['page_id'] . '][3] = ' . $node['level_id'] . ";\n";
                $nodesArrayJS .= 'nodeArray[' . $node['page_id'] . '][4] = ' . $node['root_id'] . ";\n";
            }
        }
        return "<script type='text/javascript'>\nvar nodeArray = new Array()\n" . $nodesArrayJS . "</script>\n";
    }

    /**
     * Default redirect with cLang
     *
     */
    function _cmd_redirectToDefault(SGL_Registry $input, SGL_Output $output)
    {
        //  must not logmessage here

        //  if no errors have occured, redirect
        if (!SGL_Error::count()) {
            SGL_HTTP::redirect(array('cLang' => $input->cLang));

        //  else display error with blank template
        } else {
            $output->template = 'error.html';
        }
    }

}
?>
