<?php
require_once SGL_MOD_DIR . '/cms/classes/CmsDAO.php';

/**
 * @author  Julien Casanova <julien_casanova@yahoo.fr>
 */
class ContentViewMgr extends SGL_Manager
{
    function ContentViewMgr()
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);
        parent::SGL_Manager();

        $this->da = &CmsDAO::singleton();
        $this->masterTemplate = 'masterLeftCol.html';
        $this->pageTitle = 'Content Manager';
        $this->_aActionsMapping =  array(
            'view'      => array('view'),
            'list'      => array('list'),
            'blog'      => array('blog')
        );
    }

    function validate($req, &$input)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $this->template = 'contentViewList.html';
        $this->validated        = true;
        $input->masterTemplate  = $this->masterTemplate;
        $input->error           = array();
        $input->pageTitle       = $this->pageTitle;
        $input->template        = $this->template;

        //  form vars
        $input->action          = ($req->get('action')) ? $req->get('action') : 'view';
        $input->contentId       = (int)$req->get('frmContentId');
        $input->contentTypeId   = (int)$req->get('frmContentTypeId');
        $input->cLang           = $req->get('cLang');
    }

    function display(&$output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);
    }

    function _cmd_view(&$input, &$output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $output->masterTemplate = 'masterLeftCol.html';
        $langCode = $input->cLang
            ? $input->cLang
            : SGL::getCurrentLang();
        $oContent = SGL_Content::getById($input->contentId, $langCode);

        if (!empty($oContent->id)) {
            $templateFile = 'contentTypes/' . SGL_Inflector::camelise($oContent->typeName) . '.html';
            $templatePath1 = SGL_WEB_ROOT . '/themes/' . $this->conf['site']['defaultTheme'] . '/cms/' . $templateFile;
            $templatePath2 = SGL_MOD_DIR . '/cms/templates/' . $templateFile;
            $output->template = (is_file($templatePath1) || is_file($templatePath2))
                ? $templateFile
                : 'contentView.html';
            $output->oContent = $oContent;

            //  Encode current url for redirecting purposes
            $url = $input->getCurrentUrl();
            $output->redir = urlencode(urlencode($url->toString()));

            //  set default master template if configured
            if (is_array($this->conf['defaultMasterTemplates']) && count($this->conf['defaultMasterTemplates'])) {
                $aMapping = $this->conf['defaultMasterTemplates'];


                foreach ($aMapping as $contentType => $masterTemplate) {
                    if ($oContent->typeName == $contentType) {
                        $output->masterTemplate = $masterTemplate;
                        break;
                    }
                }
            }
        } else {
            $output->template = 'contentviewNotFound.html';
        }
    }

    function _cmd_list(&$input, &$output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        if (!empty($input->contentTypeId)) {
            $output->result = SGL_Finder::factory('content')
                ->addFilter('typeId', $input->contentTypeId)
		        ->addFilter('status', SGL_CMS_STATUS_PUBLISHED)
                ->retrieve();

           if (count($output->result)) {
               $typeName = $output->result[0]->typeName;
               $templateFile = 'contentTypes/' . SGL_Inflector::camelise($typeName) . 'List.html';
               $templatePath1 = SGL_WEB_ROOT . '/themes/' . $this->conf['site']['defaultTheme'] . '/cms/' . $templateFile;
               $templatePath2 = SGL_MOD_DIR . '/cms/templates/' . $templateFile;
               $output->template = (is_file($templatePath1) || is_file($templatePath2))
                    ? $templateFile
                    : 'contentViewList.html';
           }
        } else {
            SGL::raiseMsg('There was a problem while obtaining the content', true, SGL_MESSAGE_ERROR);
        }
    }
}
?>
