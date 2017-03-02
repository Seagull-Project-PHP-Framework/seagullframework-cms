<?php
require_once SGL_MOD_DIR . '/cms/classes/CmsDAO.php';

/**
 * Handles import of content
 *
 * @package    seagull
 * @subpackage cms
 * @author     Laszlo Horvath <pentarim@gmail.com>
 */
class ImportMgr extends SGL_Manager
{
    function ImportMgr()
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);
        parent::SGL_Manager();

        $this->pageTitle    = 'Import Content';
        $this->template     = 'importConfigurePublisher.html';

        $this->_aActionsMapping =  array(
            'configurePublisher' => array('configurePublisher'),
            'importPublisher'    => array('importPublisher','redirectToDefault'),
        );

        $this->da = new SGL_CmsImport();
    }

    function validate($req, &$input)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $this->validated    = true;
        $input->masterTemplate = $this->masterTemplate;
        $input->template    = $this->template;
        $input->pageTitle   = $this->pageTitle;
        $input->action      = ($req->get('action')) ? $req->get('action') : 'configurePublisher';
        $input->submitted   = $req->get('submitted');

        // get import config
        $input->import   = (array)$req->get('import');

        if (in_array($input->action,array('configurePublisher','importPublisher'))) {

            $this->da->loadStrategy('Publisher',$input->import);
            $this->da->setConfig($input->import);

            if ($input->submitted) {
                $result = $this->da->init();
                if ($result !== true) {
                    SGL::raiseMsg('Unable to connect to remote database, please check connection parameters');
                    $this->validated = false;
                }
            // if we changed content mapping
            } elseif(!empty($input->import)) {
                $input->action = 'configurePublisher';
            }
        }
    }

    function display(&$output)
    {
        if (($output->action == 'importPublisher') && !$this->validated) {
            $this->_cmd_configurePublisher($output,$output);
        }
    }

    function _cmd_configurePublisher(&$input, &$output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        // set the defaults if needed
        $strategyConfig = $this->da->getConfig();
        if (empty($output->import)) {
            $output->import = $strategyConfig;
        }

        $cmsDAO = CmsDAO::singleton();
        $aContentTypes = $cmsDAO->getContentTypes();

        if (empty($aContentTypes)) {
            SGL::raiseMsg(SGL_Output::translate('Please define some content types first'));
            $aRedir = array(
                'moduleName'  => 'cms',
                'managerName' => 'contenttype',
                'action'      => ''
            );
            SGL_HTTP::redirect($aRedir);
        }

        asort($aContentTypes);
        $output->aContentTypes = $aContentTypes;

        $output->contentTypeId = $output->import['mapping']['content_type_id'];

        $output->oContentType = SGL_Content::getByType($output->contentTypeId);

        $output->aImportMapping = (@isset($output->import['mapping'][$output->contentTypeId]))
            ? $output->import['mapping'][$output->contentTypeId]
            : array();

        foreach ($output->oContentType->aAttribs as &$oAttribute) {
            $oAttribute->import_mapping = array_key_exists($oAttribute->name,$output->aImportMapping)
                ? $output->aImportMapping[$oAttribute->name]
                : '';
        }

        $output->aSourceFields = array('' => 'empty') + $strategyConfig['fields'];

        $output->aDbTypes = array(
            'mysql_SGL'  => 'mysql_SGL',
            'mysqli_SGL' => 'mysqli_SGL',
            'mysql'      => 'mysql',
            'mysqli'     => 'mysqli',
            'pgsql'      => 'pgsql',
            'oci8_SGL'   => 'oci8',
        );

        $output->aArticleTypes = array(
            ''  => 'Any',
            '2' => 'Html Articles',
            '4' => 'News Items',
            '5' => 'Static Html Articles',
        );

        $output->aArticleStatus = array(
            ''  => 'Any',
            '4' => 'Published',
            '5' => 'Archieved',
            '3' => 'Approved',
            '2' => 'Being edited',
            '1' => 'For approval',
            '0' => 'Deleted',
        );

        $output->contentTypeId = $output->import['mapping']['content_type_id'];

        $output->aSourceFields = array('' => 'empty') + $strategyConfig['fields'];

        $cmsDAO = CmsDAO::singleton();
        $aContentTypes = $cmsDAO->getContentTypes();
        asort($aContentTypes);
        $output->aContentTypes = $aContentTypes;

        $output->oContentType = SGL_Content::getByType($output->contentTypeId);

        $output->aImportMapping = (@isset($output->import['mapping'][$output->contentTypeId]))
            ? $output->import['mapping'][$output->contentTypeId]
            : array();

        foreach ($output->oContentType->aAttribs as &$oAttribute) {
            $oAttribute->import_mapping = array_key_exists($oAttribute->name,$output->aImportMapping)
                ? $output->aImportMapping[$oAttribute->name]
                : '';
        }

        if (!isset($output->import['filters']['date_created_before']) || ($output->import['filters']['date_created_before'] == '')) {
            $output->addOnLoadEvent("setEmpty('frmCreatedBeforeShow')");
        }
        if (!isset($output->import['filters']['date_created_after']) || ($output->import['filters']['date_created_after'] == '')) {
            $output->addOnLoadEvent("setEmpty('frmCreatedAfterShow')");
        }

        //  select appropriate jscalendar lang file depending on prefs defined language
        $lang = SGL::getCurrentLang();
        $jscalendarLangFile = (is_file(SGL_WEB_ROOT . '/js/jscalendar/lang/calendar-'. $lang . '.js'))
            ? 'js/jscalendar/lang/calendar-'. $lang . '.js'
            : 'js/jscalendar/lang/calendar-en.js';
        $output->addJavascriptFile(array(
            'js/jscalendar/calendar.js',
            $jscalendarLangFile,
            'js/jscalendar/calendar-setup.js'));

    }

    function _cmd_importPublisher(&$input, &$output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $result = $this->da->import();

        if (!PEAR::isError($result)) {
            SGL::raiseMsg('content successfully imported', true, SGL_MESSAGE_INFO);
        } else {
            SGL::raiseMsg('problem importing content', true, SGL_MESSAGE_ERROR);
        }
    }
}
?>
