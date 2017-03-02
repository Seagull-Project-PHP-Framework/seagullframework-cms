<?php

require_once SGL_CORE_DIR . '/Category.php';

/**
 * Enter description here...
 *
 * @todo change to class constants
 */

define('SGL_CMS_STATUS_DELETED',           -1);
define('SGL_CMS_STATUS_FOR_APPROVAL',       1);
define('SGL_CMS_STATUS_BEING_EDITED',       2);
define('SGL_CMS_STATUS_APPROVED',           3);
define('SGL_CMS_STATUS_PUBLISHED',          4);
define('SGL_CMS_STATUS_ARCHIVED',           5);
define('SGL_CMS_ATTRIB_VALUE_NO',          -1);

require_once SGL_MOD_DIR . '/cms/classes/NavigationDAO.php';
require_once SGL_MOD_DIR . '/user/classes/UserDAO.php';

class SGL_Task_BuildNavigation2 extends SGL_Task
{
    var $groupId = null;
    var $childId = null;

    function run($data)
    {
        if (!SGL_Config::get('navigation.enabled')) {
            return;
        }

        if (array_key_exists('createTables', $data) && $data['createTables'] == 1
                && (!array_key_exists('useExistingData', $data) || $data['useExistingData'] == 0)
                && SGL_Config::get('navigation.driver') != 'ArrayDriver') {

            $da = CmsNavigationDAO::singleton();

            foreach ($data['aModuleList'] as $module) {
                $navigationPath = SGL_MOD_DIR . '/' . $module  . '/data/navigation.php';
                // try to load module's navigation file
                if (file_exists($navigationPath)) {
                    require_once $navigationPath;
                }
                if (!empty($aSections)) {
                    foreach ($aSections as $aSection) {

                        //  check if section is designated as child to last insert
                        if ($aSection['parent_id'] == SGL_NODE_GROUP) {
                            $aSection['parent_id'] = $this->groupId;
                        }
                        $id = $da->addSimpleSection($aSection);
                        if (!PEAR::isError($id)) {
                            if ( $aSection['parent_id'] == SGL_NODE_ADMIN ||
                                 $aSection['parent_id'] == SGL_NODE_USER) {
                                $this->groupId = $id;
                            } else {
                                $this->childId = $id;
                            }
                        } else {
                            SGL_Install_Common::errorPush($id);
                        }
                    }
                    unset($aSections);
                }
            }
        } elseif (SGL_Config::get('navigation.driver') == 'ArrayDriver') {
            require_once dirname(__FILE__) . '/classes/ArrayDriver.php';
            $aNodes = CmsArrayDriver::getNavigationStructure();
            $ok = CmsArrayDriver::saveNodes($aNodes);
            if (!$ok) {
                SGL::raiseError('ArrayDriver: can\'t save nodes');
            }
        }
    }
}

/**
 * @package Task
 */
class SGL_Task_RemoveNavigation2 extends SGL_Task
{
    function run($data)
    {
        $da = CmsNavigationDAO::singleton();

        foreach ($data['aModuleList'] as $module) {
            $navigationPath = SGL_MOD_DIR . '/' . $module  . '/data/navigation.php';
            // try to load module's navigation file
            if (file_exists($navigationPath)) {
                require_once $navigationPath;
            }
            if (!empty($aSections)) {
                foreach ($aSections as $aSection) {
                    $sectionId = $da->getSectionIdByTitle($aSection['title']);
                    if ($sectionId) {
                        $ok = $da->deleteSectionById($sectionId);
                    }
                }
                unset($aSections);
            }
        }
    }
}

/**
 * Builds navigation menus.
 *
 * @package Task
 * @author  Demian Turner <demian@phpkitchen.com>
 */
class SGL_Task_SetupNavigation2 extends SGL_DecorateProcess
{
    function process($input, $output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $this->processRequest->process($input, $output);

        if (SGL_Config::get('navigation.enabled') && !SGL::runningFromCli()) {

            //  prepare navigation driver
            $navDriver    = SGL_Config::get('navigation.driver');
            $navDrvFile   = SGL_MOD_DIR . '/cms/classes/' . $navDriver . '.php';
            if (is_file($navDrvFile)) {
                require_once $navDrvFile;
            } else {
                SGL::raiseError('specified navigation driver does not exist',
                    SGL_ERROR_NOFILE);
            }
            $navClass = 'Cms' . $navDriver;
            if (!class_exists($navClass)) {
                SGL::raiseError('problem with navigation driver object',
                    SGL_ERROR_NOCLASS);
            }
            $nav = new $navClass($output);

            //  render navigation menu
            $navRenderer = SGL_Config::get('navigation.renderer');
            $aRes        = $nav->render($navRenderer);
            if (!PEAR::isError($aRes)) {
                list($sectionId, $html)  = $aRes;
                $output->sectionId  = $sectionId;
                $output->navigation = $html;
                $output->currentSectionName = $nav->getCurrentSectionName();
            }
        }
    }
}

/**
 * Resolve current language and put in current user preferences.
 * Load relevant language translation file.
 *
 * @package Task
 * @author Julien Casanova <julien@soluo.fr>
 */

class SGL_Task_SetupLangSupport2 extends SGL_DecorateProcess
{
    /**
     * Main workflow.
     *
     * langCodeCharset still set in prefs for BC, ie
     *  $_SESSION[aPrefs][language] => es-utf-8
     *
     * @access public
     *
     * @param SGL_Registry $input
     * @param SGL_Output $output
     */
    function process($input, $output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        //  sets default language for framework, checks for lang param used to set
        //  user lang
        $trans = SGL_Translation3::singleton('array');
        try {
            $trans->loadDefaultDictionaries();
        } catch (Exception $e) {
            SGL::raiseError($e->getMessage(), SGL_ERROR_NOFILE);
        }
        // save language in settings
        $_SESSION['aPrefs']['language'] = $trans->langCodeCharset;

        // we can remove this one, left for BC
        $GLOBALS['_SGL']['CHARSET'] = $trans->getDefaultCharset();

        // continue chain execution
        $this->processRequest->process($input, $output);
    }
}

/**
 * Checks to see if the cms module is setup properly.
 *
 * @package Task
 * @author Demian Turner <demian@phpkitchen.com>
 */

class SGL_Task_CmsSetupCheck extends SGL_Task
{
    function run($data)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        //  ensure sgl/var/translation dir exists
        $this->ensureDirIsWritable(SGL_VAR_DIR . '/translation');

        //  check default lang data copied over
        if (!file_exists(SGL_VAR_DIR . '/translation/data')) {
            require_once 'SGL/File.php';
            $src = SGL_MOD_DIR . '/cms/data/install/data';
            $target = SGL_VAR_DIR . '/translation/data';
            $ok = SGL_File::copyDir($src, $target);
        }

        //  check TranslationMgr config
        $c = SGL_Config::singleton();
        $c->ensureModuleConfigLoaded('translation');
        if (!SGL_Config::get('TranslationMgr.otherDictionaries')) {
            $cc = new SGL_Config();
            $path = SGL_MOD_DIR . '/translation/conf.ini';
            $moduleConf = $cc->load($path, true);
            $cc->replace($moduleConf);
            $cc->set('TranslationMgr', array('otherDictionaries' => 'categories,navigation'));
            $ok1 = $cc->save($path);
        }
        if (!SGL_Config::get('debug.showUntranslated')) {
            SGL_Config::set('debug.showUntranslated', 1);
            $ok2 = $c->save();
        }
    }

    function ensureDirIsWritable($dirName)
    {
        if (!is_writable($dirName)) {
            require_once 'System.php';
            $ok = System::mkDir(array('-p', $dirName));
            if (PEAR::isError($ok)) {
                return $ok;
            }
            if (!$ok) {
                return SGL::raiseError("Error making directory
                    '$dirName' writable");
            }
            $mask = umask(0);
            $ok   = @chmod($dirName, 0777);
            if (!$ok) {
                return SGL::raiseError("Error performing chmod on
                    directory '$dirName'");
            }
            umask($mask);
        }
        return true;
    }
}



class CmsCategory extends SGL_Category
{
    function __construct()
    {
        $this->conf = SGL_Config::singleton()->getAll();

        $this->da = UserDAO::singleton();
        $this->dbh = SGL_DB::singleton();

        //  Nested Set Params
        $this->_params = array(
            'tableStructure' => array(
                'category_id' => 'id',
                'root_id'     => 'rootid',
                'left_id'     => 'l',
                'right_id'    => 'r',
                'order_id'    => 'norder',
                'level_id'    => 'level',
                'parent_id'   => 'parent',
                'label'       => 'label',
                'description' => 'description',
                'perms'       => 'perms',
            ),
            'tableName'     => $this->conf['table']['category'],
            'lockTableName' => $this->conf['db']['prefix'] . 'table_lock',
            'sequenceName'  => $this->conf['table']['category']);
    }

    /**
     * Retrieve children
     *
     * @access  public
     * @param   int     $id
     * @return  array   categories children
     */
    function getChildren($id)
    {
        if (!is_numeric($id)) {
            SGL::raiseError('Wrong datatype passed to '  . __CLASS__ . '::' .
                __FUNCTION__, SGL_ERROR_INVALIDARGS, PEAR_ERROR_DIE);
        }
        $query = "  SELECT category_id, label, description
                    FROM " . $this->conf['table']['category'] . "
                    WHERE parent_id = $id
                    ORDER BY parent_id, order_id";

        $result = $this->dbh->query($query);
        $count = 0;
        $aChildren = array();
        while ($row = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
            $aChildren[$count]['category_id'] = $row['category_id'];
            $aChildren[$count]['label'] = $row['label'];
            $aChildren[$count]['description'] = $row['description'];
            $count++;
        }
        return $aChildren;
    }
}
?>
