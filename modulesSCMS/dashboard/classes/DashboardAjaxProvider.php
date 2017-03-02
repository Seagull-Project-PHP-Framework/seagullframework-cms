<?php
require_once SGL_CORE_DIR . '/AjaxProvider2.php';
require_once SGL_MOD_DIR . '/dashboard/classes/DashboardDAO.php';

/**
 * Ajax provider.
 *
 * @package dashboard
 * @author Andrey Baigozin <a.baigozin@gmail.com>
 */
class DashboardAjaxProvider extends SGL_AjaxProvider2
{
    public function __construct()
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);
        parent::__construct();

        $this->fallbackLangCode = reset(
            explode('-', SGL_Translation::getFallbackLangID(SGL_LANG_ID_SGL)));

        $this->da = DashboardDAO::singleton();
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

        return true;
    }

    public function updateOrdering(SGL_Registry $input, SGL_Output $output)
    {
        $userId = SGL_Session::getUid();
        $aColumns = range(0, 3);
        $aContent = array();
        foreach ($aColumns as $columnId){
            $aContent[$columnId] = $this->req->get('sort'.$columnId);
            if (!is_array($aContent[$columnId])) {
                $aContent[$columnId] = array();
            }
        }


        // wigdets naming convention: id="widget-pageId-elementId"
        foreach ($aContent as $column => $aSections) {
            foreach ($aSections as $position => $sectionName) {
                $aParams = explode('-', $sectionName);
                if ($aParams[0] == 'widget' && isset($aParams[1])
                    && isset($aParams[2])) {
                    $this->da->updateWidget(
                        $userId,
                        $aParams[2],
                        $aParams[1],
                        $column,
                        $position
                    );
                }
            }
        }
        return true;
    }

}
?>