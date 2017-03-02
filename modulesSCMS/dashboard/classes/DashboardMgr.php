<?php
/**
 * Dashboard manager.
 *
 * @package dashboard
 * @author Andrey Baigozin <a.baigozin@gmail.com>
 */
class DashboardMgr extends SGL_Manager
{
    public function __construct()
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);
        parent::SGL_Manager();
    }

    public function validate(SGL_Request $req, SGL_Registry $input)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);
    }

    public function display(SGL_Output $output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);
    }
}
?>