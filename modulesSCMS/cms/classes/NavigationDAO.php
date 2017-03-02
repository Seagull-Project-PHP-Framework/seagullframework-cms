<?php
require_once SGL_CORE_DIR . '/NestedSet.php';

/**
 * Data access methods for the navigation module.
 *
 * @package Navigation
 * @author  Demian Turner <demian@phpkitchen.com>
 * @author  Andrey Podshivalov <demian@phpkitchen.com>
 */
class CmsNavigationDAO extends SGL_Manager
{
    var $_params  = array();

    /**
     * Constructor - set default resources.
     *
     * @return CmsNavigationDAO
     */
    function __construct()
    {
        parent::SGL_Manager();
        $this->_params = array(
            'tableStructure' => array(
                'page_id'    => 'id',
                'title'         => 'title',
                'resource_uri'  => 'resource_uri',
                'perms'         => 'perms',
                'root_id'       => 'rootid',
                'left_id'       => 'l',
                'right_id'      => 'r',
                'order_id'      => 'norder',
                'level_id'      => 'level',
                'parent_id'     => 'parent',
                'is_enabled'    => 'is_enabled',
                'is_static'     => 'is_static',
                'access_key'    => 'access_key',
                'rel'           => 'rel'
            ),
            'tableName'      => $this->conf['table']['page'],
            'lockTableName'  => $this->conf['db']['prefix'] . 'table_lock',
            'sequenceName'   => $this->conf['table']['page']);

        $this->nestedSet = new SGL_NestedSet($this->_params);
    }

    /**
     * Returns a singleton CmsNavigationDAO instance.
     *
     * @access  public
     * @static
     * @return  CmsNavigationDAO reference to CmsNavigationDAO object
     */
    function &singleton($forceNew = false)
    {
        static $instance;

        // If the instance is not there, create one
        if (!isset($instance) || $forceNew) {
            $class = __CLASS__;
            $instance = new $class();
        }
        return $instance;
    }

    /**
     * Returns pages from parent page.
     *
     * @access  public
     *
     * @param   int $parentId  Parent section id
     * @return  array
     */
   function getSectionsByParentId($parentId = 0)
    {
        $query = "
            SELECT * FROM {$this->conf['table']['page']}
            WHERE parent_id = " . $parentId . '
            ORDER BY order_id';

        $result = $this->dbh->getAll($query);
        if (PEAR::isError($result, DB_ERROR_NOSUCHTABLE)) {
            SGL::raiseError('The database exists, but does not appear to have any tables,
                please delete the config file from the var directory and try the install again',
                SGL_ERROR_DBFAILURE, PEAR_ERROR_DIE);
        }
        if (PEAR::isError($result)) {
            SGL::raiseError('Cannot connect to DB, check your credentials, exiting ...',
                SGL_ERROR_DBFAILURE, PEAR_ERROR_DIE);
        }
        return $result;
    }

    /**
     * Returns page by given id.
     *
     * @access  public
     *
     * @param   int $sectionId
     * @return  array
     */
    function getSectionById($sectionId = null)
    {
        $section = array();

        //  get raw section
        $section = $this->getRawSectionById($sectionId);

        //  passing a non-existent section id results in null or false $section
        if ($section) {

            //  setup article type, dropdowns built in display()
            if (preg_match('/^uriExternal:(.*)$/', $section['resource_uri'], $aUri)) {
                $section['resource_uri'] = $aUri[1];
                $section['uriType']      = 'uriExternal';

            } elseif (preg_match('/^uriNode:(.*)$/', $section['resource_uri'], $aUri)) {
                $section['uri_node'] = $aUri[1];
                $section['uriType']  = 'uriNode';

            } elseif ('uriEmpty:' == $section['resource_uri']) {
                $section['uriType'] = 'uriEmpty';

            } else {
                $section['uriType'] = ($section['is_static']) ? 'static' : 'dynamic';

                //  parse uri details
                $parsed = SGL_Url::parseResourceUri($section['resource_uri']);
                $section = array_merge($section, $parsed);

                //  adjust friendly mgr name to class filename
                if (SGL::moduleIsEnabled($parsed['module'])) {
                    $c = &SGL_Config::singleton();
                    $moduleConf = $c->load(SGL_MOD_DIR . '/' . $parsed['module'] . '/conf.ini', true);
                    $c->merge($moduleConf);
                    $className  = SGL_Inflector::getManagerNameFromSimplifiedName($section['manager']);
                    if ($className) {
                        $section['manager'] = $className . '.php';
                    } else {
                        SGL::raiseMsg('Manager was not found', true, SGL_MESSAGE_WARNING);
                    }
                }

                //  represent additional params as string
                if (array_key_exists('parsed_params', $parsed) && count($parsed['parsed_params'])) {
                    foreach ($parsed['parsed_params'] as $k => $v) {
                        $ret[] = $k . '/' . $v;
                    }
                    $section['add_params'] = implode('/', $ret);
                } else {
                    $section['add_params'] = null;
                }
                //  deal with content items
                if ($section['is_static'] && SGL::moduleIsEnabled('cms')) {
                    if (isset($parsed['parsed_params'])) {
                        $section['staticArticleId'] = $parsed['parsed_params']['frmContentId'];
                    }
                    $section['add_params'] = '';
                }
                //  split off anchor if exists
                if (stristr($section['resource_uri'], '#')) {
                    list(,$anchor) = split("#", $section['resource_uri']);
                    $section['anchor'] = $anchor;
                }
            }
        }
        return $section;
    }

    /**
     * Returns raw section by given id.
     *
     * @access  public
     *
     * @param   int $sectionId
     * @return  array
     */
    function getRawSectionById($pageId)
    {
        return $this->nestedSet->getNode($pageId);
    }

    /**
     * Moves section.
     *
     * @access  public
     *
     * @param   int $sectionId
     * @param   int $targedId
     * @param   string $direction BE | AF
     */
    function moveSection($pageId = 0, $targetId = 0, $direction = null)
    {
        $this->nestedSet->moveTree($pageId, $targetId, $direction);
    }

    /**
     * Deletes section by given id.
     *
     * @access  public
     *
     * @param   int $sectionId
     */
    function deleteSectionById($pageId = null)
    {
        //  deleting parent nodes automatically deletes chilren nodes, but user
        //  might have checked child nodes for deletion, in which case deleteNode()
        //  would try to delete nodes that no longer exist, after parent deletion,
        //  and therefore error, so test first to make sure they're still around
        if ($section = $this->nestedSet->getNode($pageId)){
            //  remove section
            $this->nestedSet->deleteNode($pageId);
        }
    }

    function getSectionIdByTitle($title)
    {
        $query = "
            SELECT page_id
            FROM {$this->conf['table']['page']}
            WHERE title = '$title'";

        $result = $this->dbh->getOne($query);
        return $result;
    }

    /**
     * Returns all sections.
     *
     * @access  public
     * @return  array
     */
    function getSectionTree()
    {
        $this->nestedSet->setImage('folder', 'images/treeNav/file.png');
        $sectionNodes = $this->nestedSet->getTree();

        if (is_array($sectionNodes) && count($sectionNodes)) {
            //  remove first element of array which serves as a 'no section' fk
            //  for joins from the block_assignment table
            unset($sectionNodes[0]);
            $this->nestedSet->addImages($sectionNodes);
            $ret = $sectionNodes;
        } else {
            $ret = array();
        };
        return $ret;
    }

    /**
     * Returns sections are prepared for select.
     *
     * @access  public
     *
     * @return  array
     */
    function getSectionsForSelect()
    {
        $aTranslations     = array();
        $aSections         = array();
        $sectionNodesArray = $this->nestedSet->getTree();
        if (isset($sectionNodesArray[0])) {
            unset($sectionNodesArray[0]);
        }

        if (is_array($sectionNodesArray) && count($sectionNodesArray)) {
            foreach ($sectionNodesArray as $k => $sectionNode) {
                $spacer = str_repeat('&nbsp;&nbsp;', $sectionNode['level_id']-1);
                $aSections[$sectionNode['page_id']] = $spacer . $sectionNode['title'];
            }
        }
        return $aSections;
    }

    /**
     * Adds new section.
     *
     * @access  public
     *
     * @param   array $section
     * @return  boolean false | int
     */
    function addSection($page)
    {
        $this->prepareSection($page);

        if ($page['parent_id'] == 0) {    //  they want a root node
            $nodeId = $this->nestedSet->createRootNode($page);
        } elseif ((int)$page['parent_id'] > 0) { //    they want a sub node
            $nodeId = $this->nestedSet->createSubNode($page['parent_id'], $page);
        } else { //  error
            return false;
        }
        return true;
    }


    /**
     * For installer purposes, returns insert ID.
     *
     * @param array $section
     */
    function addSimpleSection($section)
    {
        // prepare dynamic section
        if ($section['uriType'] == 'dynamic') {
            $separator = '/';

            //  strip extension and 'Mgr'
            $simplifiedMgrName = SGL_Inflector::getSimplifiedNameFromManagerName($section['manager']);
            $actionPair = (!(empty($section['actionMapping'])) && ($section['actionMapping'] != 'none'))
                ? 'action' . $separator . $section['actionMapping'] . $separator
                : '';
            $addParams = (!empty($section['add_params']))
                ? $section['add_params']
                : '';
            $section['resource_uri'] =
                $section['module'] . $separator .
                $simplifiedMgrName . $separator .
                $actionPair .
                $addParams;

            //  remove trailing slash/ampersand if one is present
            if (substr($section['resource_uri'], -1) == $separator) {
                $section['resource_uri'] = substr($section['resource_uri'], 0, -1);
            }

        // prepare external section
        } elseif ($section['uriType'] == 'external') {
            $section['resource_uri'] = 'uriExternal:' . $section['uri'];
        }

        //  intercept a list of constants occuring in quotes,
        //  ie 'perms' => "SGL_GUEST, SGL_MEMBER, SGL_ADMIN",
        if (is_string($section['perms'])) {
            $aConstants = split(',', $section['perms']);
            if (is_array($aConstants)) {
                $aPerms = array();
                foreach ($aConstants as $myconstant) {
                    $aPerms[] = constant(trim($myconstant));
                }
                //$perms = substr($perms, 0, -1);
                $perms = join(',', $aPerms);
                $section['perms'] = $perms;
            }
        }

        $nodeId = $this->nestedSet->createSubNode($section['parent_id'], $section);

        // sync with sequence table
        $this->dbh->nextID($this->conf['table']['page']);

        return $nodeId;
    }

    /**
     * Updates section.
     *
     * @access  public
     *
     * @param   array $section
     * @return  boolean false | int
     */
    function updateSection($section)
    {
        $this->prepareSection($section);

        //  attempt to update section values
        if (!$parentId = $this->nestedSet->updateNode($section['page_id'], $section)) {
            return false;
        }

        //  If changing activation status, we need to enable/disable this node's children too
        if (($section['is_enabled'] != $section['is_enabled_original'])){
            $children = $this->nestedSet->getSubBranch($section['page_id']);
            if ($children) {
                foreach ($children as $child){

                    //  change the child's is_enabled status to that of its parent
                    if (!$this->nestedSet->updateNode($child['page_id'],
                            array('is_enabled' => $section['is_enabled']))) {
                        return false;
                    }
                }
            }
        }

        //  move node if needed
        switch ($section['parent_id']) {
        case $section['parent_id_original']:
            //  usual case, no change => do nothing
            break;

        case $section['page_id']:
            //  cannot be parent to self => display user error
            break;

        case 0:
            //  move the section, make it into a root node, just above its own root
            $thisNode = $this->nestedSet->getNode($section['page_id']);
            $moveNode = $this->nestedSet->moveTree($section['page_id'], $thisNode['root_id'], 'BE');
            break;

        default:
            //  move the section under the new parent
            $moveNode = $this->nestedSet->moveTree($section['page_id'], $section['parent_id'], 'SUB');
        }
        return true;
    }

    /**
     * Prepares section for insert or update operations.
     *
     * @access  private
     *
     * @param   array $section
     */
    function prepareSection($page)
    {
        $separator = '/'; // can be configurable later

        //  if sectionType = static, append articleId, else build section url
        $page['is_static'] = 0;
        switch ($page['uriType']) {
        case 'static':
            $page['is_static'] = 1;
            $page['resource_uri'] =  'cms/contentview/frmContentId/' .
                $page['staticArticleId'] . '/';
            break;

        case 'uriExternal':
            $string = 'uriExternal:' . $page['resource_uri'];
            $page['resource_uri'] = $string;
            break;

        case 'uriNode':
            $string = 'uriNode:' . $page['uri_node'];
            $page['resource_uri'] = $string;
            break;

        case 'uriEmpty':
            $string = 'uriEmpty:';
            $page['resource_uri'] = $string;
            break;

        case 'dynamic':

            //  strip extension and 'Mgr'
            $simplifiedMgrName = SGL_Inflector::getSimplifiedNameFromManagerName($page['manager']);
            $actionPair = (!(empty($page['actionMapping'])) && ($page['actionMapping'] != 'none'))
                ? 'action' . $separator . $page['actionMapping'] . $separator
                : '';

            $page['resource_uri'] =
                $page['module'] . $separator .
                $simplifiedMgrName . $separator .
                $actionPair;
                break;
        }

        //  deal with additional params
        if (!(empty($page['add_params']))) {

            //  handle params abstractly to later accomodate traditional urls
            //  also strip blank array elements caused by input like '/foo/bar/'
            $params = array_filter(explode('/', $page['add_params']), 'strlen');
            $page['resource_uri'] .= implode($separator, $params);
        }

        //  add anchor if necessary
        if (!(empty($page['anchor']))) {
            $page['resource_uri'] .= '#' . $page['anchor'];
        }

        //  remove trailing slash/ampersand if one is present
        if ($page['uriType'] != 'uriExternal' && substr($page['resource_uri'], -1) == $separator) {
            $page['resource_uri'] = substr($page['resource_uri'], 0, -1);
        }

        // delete 'all roles' option
        $aRoles = explode(',', $page['perms']);
        if (count($aRoles) > 1) {
            foreach ($aRoles as $key => $value) {
                if ($value == SGL_ANY_ROLE) {
                    unset($aRoles[$key]);
                }
            }
            $page['perms'] = implode(',', $aRoles);
        }
    }
}
?>
