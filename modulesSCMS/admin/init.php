<?php

// custom roles
define('SGL_ROLE_MODERATOR', 3);

// nav nodes
define('SGL_NODE_ADMIN2', 10);

$GLOBALS['_SGL']['aBlocksNames'] = array(

    // essential
    'MainNav'        => 'MainNav',
    'AdminNav'       => 'AdminNav',
    'MainBreadcrumb' => 'MainBreadcrumb',

    // admin2
    'AdminNavPri' => 'AdminNavPri',
    'AdminNavSec' => 'AdminNavSec',

    // default
    'Left'   => 'Left',
    'Right'  => 'Right',
    'Top'    => 'Top',
    'Bottom' => 'Bottom',

    // additional
    'BodyTop' => 'BodyTop',

    // additional for admin
    'AdminCategory'    => 'AdminCategory',
    'AdminBreadcrumbs' => 'AdminBreadcrumbs',
);

/**
 * Rebuild task.
 *
 * @package Task
 * @author Dmitri Lakachauskis <lakiboy83@gmail.com>
 */
class SGL_Task_CreateModeratorUser extends SGL_Task
{
    public function run($data)
    {
        require_once SGL_MOD_DIR . '/user2/classes/User2DAO.php';
        $oUserDAO = User2DAO::singleton();

        $aUser = array(
            'username'   => 'moderator',
            'first_name' => 'Content',
            'last_name'  => 'Moderator',
            'email'      => 'moderator@simplecms.net',
            'passwd'     => md5('qwerty')
        );
        $aPrefs = array(
            'admin theme' => 'default_admin2'
        );

        // create new user with relevant prefs
        $userId = $oUserDAO->addUser($aUser, true, SGL_ROLE_MODERATOR);
        $oUserDAO->addMasterPrefsByUserId($userId, $aPrefs);
    }
}

// ---------------
// --- Filters ---
// ---------------

/**
 * Loads some Javascript/CSS for admin/moderators in front-end.
 *
 * @package Filter
 * @author Dmitri Lakachauskis <lakiboy83@gmail.com>
 */
class SGL_Task_InitHelpers extends SGL_DecorateProcess
{
    public function process(SGL_Registry $input, SGL_Output $output)
    {
        $this->processRequest->process($input, $output);

        SGL::logMessage(null, PEAR_LOG_DEBUG);

        if (in_array(SGL_Session::getRoleId(), array(SGL_ADMIN, SGL_ROLE_MODERATOR))) {
            $output->addJavascriptFile(array(
                'admin/js/Admin.js',
                'admin/js/Localisation/' . $output->currLang . '-utf-8.js',
            ));
            if (empty($output->adminGuiAllowed)) {
                $output->addCssFile('admin/css/frontend.css');
                $output->addOnLoadEvent('Admin.Frontend.init()');
            } else {
                $output->addJavascriptFile(array(
                    'js/jquery/plugins/jquery.cookie.js'
                ));
                $output->addOnLoadEvent('Admin.Backend.init()');
            }
        }
    }
}

/**
 * Post filter. Modifies Output object for admin UI rendering.
 *
 * @package Filter
 * @author Dmitri Lakachauskis <lakiboy83@gmail.com>
 */
class SGL_Task_SetupGui2 extends SGL_DecorateProcess
{
    public static function adminGuiIsAllowed(SGL_Registry $input, SGL_Output $output)
    {
#FIXME: why are we invoking admin gui?
        $managerName = ($input->get('manager'))? get_class($input->get('manager')) : null;
        $adminGuiRequested = $managerName
            ? SGL_Config::get($managerName . '.adminGuiAllowed')
            : $input->getRequest()->get('adminGuiAllowed');

        return
            !SGL::runningFromCLI()
            // manager allows admin view
            && $adminGuiRequested
            // current role is allowed to view admin GUI
            && SGL_Session::hasAdminGui()
            // admin UI is not overriden
            && empty($output->overrideAdminGuiAllowed);
    }

    public function process(SGL_Registry $input, SGL_Output $output)
    {
        $this->processRequest->process($input, $output);

        SGL::logMessage(null, PEAR_LOG_DEBUG);

        if (self::adminGuiIsAllowed($input, $output)) {
            // change templates
            $output->template = 'admin_' . $output->template;
            if (!empty($output->masterTemplate)) {
                $output->masterTemplate = 'admin_' . $output->masterTemplate;
            }

            // ???
            $output->adminGuiAllowed = true;

            // change theme
            $output->theme = !empty($_SESSION['aPrefs']['admin theme'])
                ? $_SESSION['aPrefs']['admin theme']
                : SGL_Config::get('site.adminGuiTheme');

            if ($output->theme == 'default_admin') {
                $output->masterTemplate = 'admin_master.html';
            }

//            if (isset($output->submitted) && $output->submitted) {
//                $output->addOnLoadEvent("formErrorCheck()");
//            }
        }
        //  send wysiwyg toolbar type to output
        $output->wysiwygToolbarType = SGL_Config::get('CmsContentMgr.wysiwygToolbarType');
    }
}

/**
 * Pre filter. Uses 'admin' theme as default translation module
 * when 'adminGuiIsAllowed' option is requested by current manager.
 *
 * @package Filter
 * @author Dmitri Lakachauskis <lakiboy83@gmail.com>
 */
class SGL_Task_SetupLangSupport3 extends SGL_Task_SetupLangSupport
{
    public function process(SGL_Registry $input, SGL_Output $output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        // save language in settings
        $_SESSION['aPrefs']['language'] = $lang = $this->_resolveLanguage();

        // modules to get translation for
        if (SGL_Config::get('translation.defaultLangBC')) {
            $moduleDefault = 'default';
        } else {
            $moduleDefault = SGL_Task_SetupGui2::adminGuiIsAllowed($input, $output)
                ? 'admin'
                :  SGL_Config::get('site.defaultModule');
        }

        $moduleCurrent = $input->getRequest()->get('moduleName')
            ? $input->getRequest()->get('moduleName')
            : $moduleDefault;

        // get translations
        $aWords = SGL_Translation::getTranslations($moduleDefault, $lang);

        // in case default module is replaced with admin we wan't default
        // translations as well
        if ($moduleDefault != SGL_Config::get('site.defaultModule')) {
            $aWords = array_merge(
                SGL_Translation::getTranslations(SGL_Config::get('site.defaultModule'), $lang),
                $aWords
            );
        }

        if ($moduleCurrent != $moduleDefault) {
            $aWords = array_merge(
                $aWords,
                SGL_Translation::getTranslations($moduleCurrent, $lang)
            );
        }

        // populate SGL globals
        $GLOBALS['_SGL']['TRANSLATION'] = $aWords;
        // we can remove this one, left for BC
        $GLOBALS['_SGL']['CHARSET'] = SGL_Translation::getCharset();

        // continue chain execution
        $this->processRequest->process($input, $output);
    }
}
?>