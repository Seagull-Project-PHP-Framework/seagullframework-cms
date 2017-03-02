<?php

require_once 'SimpleCms/Util.php';

/**
 * @package simplecms
 * @author Dmitri Lakachauskis <lakiboy83@gmail.com>
 */
class CmsContentViewMgr extends SGL_Manager
{
    public function __construct()
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);
        parent::SGL_Manager();

        $this->pageTitle      = 'Content View Manager';
        $this->masterTemplate = 'masterPreview.html';
        $this->template       = 'cmscontentviewList.html';

        $this->_aActionsMapping = array(
            'list' => array('live', 'list')
        );
    }

    public function validate(SGL_Request $req, SGL_Registry $input)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $this->validated       = true;
        $input->pageTitle      = $this->pageTitle;
        $input->template       = $this->template;
        $input->masterTemplate = $this->masterTemplate;
        $input->action         = $req->get('action')
            ? $req->get('action') : 'list';

        $input->content   = (object) $req->get('content', true);
        $input->contentId = $req->get('contentId');
        $input->contentId = $req->get('frmContentId');
        $input->langId    = $req->get('langId');
        $input->version   = $req->get('version');
    }

    public function display(SGL_Output $output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        if (!empty($output->oContent)) {

            // dynamic content type template
            if (isset($output->content->template)) {
                if (preg_match('<\?|\?>', $output->content->template)) {
                    $output->content->template = '';
                }

                $prefix       = 'ct_' . SGL_Session::getUid() . '_';
                $templatePath = tempnam(SGL_APP_ROOT . '/var/tmp', $prefix);
                file_put_contents($templatePath, $output->content->template);

            // change content type template
            } else {
                $templatePath = SimpleCms_Util::getContentTypeTemplatePath(
                    $output->oContent->typeName);
            }

            // use existing template
            if (file_exists($templatePath)) {
                $templatePath = str_replace(SGL_APP_ROOT, '', $templatePath);
                $templatePath = '../../..' . $templatePath;
                $output->template = $templatePath;
            }

            // change master template
            if ($aTemplates = SGL_Config::get('defaultMasterTemplates')) {
                if (isset($aTemplates[$output->oContent->typeName])) {
                    $output->masterTemplate = $aTemplates[$output->oContent->typeName];
                }
            }
        }
    }

    public function _cmd_list(SGL_Registry $input, SGL_Output $output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        if (!empty($input->contentId)) {
            $output->oContent = SGL_Content::getById($input->contentId,
                $input->langId, $input->version);
        }
    }

    public function _cmd_live(SGL_Registry $input, SGL_Output $output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        if (!empty($input->content) && count(get_object_vars($input->content))) {
            $oContent = SGL_Content::getByType($input->content->typeName);
            $index    = 0;
            foreach ($oContent->aAttribs as $oAttrib) {
                if (isset($input->content->aAttribs[$index])) {
                    $oAttrib->set($input->content->aAttribs[$index]);
                }
                $index++;
            }
            $output->oContent = $oContent;
        }
    }
}
?>