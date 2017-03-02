<?php
require_once SGL_MOD_DIR . '/cms/classes/CmsDAO.php';

/**
 * Search manager.
 *
 * @package seagull
 * @subpackage cms
 * @author Dmitri Lakachauskis <lakiboy83@gmail.com>
 */
class SearchMgr extends SGL_Manager
{
    public function __construct()
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);
        parent::SGL_Manager();

        $this->pageTitle = 'Quick search';
        $this->template  = 'searchList.html';

        $this->_aActionsMapping = array(
            'list'   => array('list'),
            'search' => array('search', 'redirectToDefault'),
        );
        $this->da = CmsDAO::singleton();
    }

    public function validate(SGL_Request $req, SGL_Registry $input)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $this->validated       = true;
        $input->pageTitle      = $this->pageTitle;
        $input->masterTemplate = $this->masterTemplate;
        $input->template       = $this->template;
        $input->action         = $req->get('action')
            ? $req->get('action') : 'list';

        $input->submitted = $req->get('submitted');
        $input->pattern   = $req->get('pattern');

        if ($input->submitted
                && in_array($input->action, array('search', 'result'))) {
            if (empty($input->pattern)) {
                $aErrors['pattern'] = 'Please, specify search text';
            } elseif (strlen($input->pattern) < 3) {
                $aErrors['pattern'] = 'Text too short';
            }
        }
        if (!empty($aErrors)) {
            SGL::raiseMsg('Please, correct the following errors', false);
            $input->error = $aErrors;
            $this->validated = false;
        }
    }

    public function display(SGL_Output $output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        // removeme, temporary hack
        if (!$this->validated && $output->action == 'search') {
            $this->_cmd_list($output, $output);
        }
        $output->aStatusTypes = $this->da->getStatusTypes();
    }

    public function _cmd_list(SGL_Registry $input, SGL_Output $output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $pattern = SGL_Session::get('cmsSearchPattern');
        if (empty($pattern)) {
            return false;
        }

        $output->aContentTypes  = $this->da->searchContactItemsByPattern($pattern);
        $output->currentPattern = $pattern;
    }

    public function _cmd_search(SGL_Registry $input, SGL_Output $output)
    {
        $pattern = SGL_Session::get('cmsSearchPattern');
        $pattern = $input->pattern;
        SGL_Session::set('cmsSearchPattern', $pattern);
        SGL::raiseMsg('Search results updated', true, SGL_MESSAGE_INFO);
    }
}
?>
