<?php
/* Reminder: always indent with 4 spaces (no tabs). */
// +---------------------------------------------------------------------------+
// | Copyright (c) 2006, Demian Turner                                         |
// | All rights reserved.                                                      |
// |                                                                           |
// | Redistribution and use in source and binary forms, with or without        |
// | modification, are permitted provided that the following conditions        |
// | are met:                                                                  |
// |                                                                           |
// | o Redistributions of source code must retain the above copyright          |
// |   notice, this list of conditions and the following disclaimer.           |
// | o Redistributions in binary form must reproduce the above copyright       |
// |   notice, this list of conditions and the following disclaimer in the     |
// |   documentation and/or other materials provided with the distribution.    |
// | o The names of the authors may not be used to endorse or promote          |
// |   products derived from this software without specific prior written      |
// |   permission.                                                             |
// |                                                                           |
// | THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS       |
// | "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT         |
// | LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR     |
// | A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT      |
// | OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,     |
// | SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT          |
// | LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,     |
// | DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY     |
// | THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT       |
// | (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE     |
// | OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.      |
// |                                                                           |
// +---------------------------------------------------------------------------+
// | Seagull 0.6.2                                                             |
// +---------------------------------------------------------------------------+
// | ContentView.php                                                           |
// +---------------------------------------------------------------------------+
// | Author: Julien Casanova <julien@soluo.fr>                                 |
// +---------------------------------------------------------------------------+

require_once SGL_MOD_DIR . '/cms/lib/Content.php';

/**
 * ContentView block.
 *
 * @package cms
 */
class Cms_Block_ContentView
{
    /**
     * Holds the configuration
     *
     * @var array
     */
    var $conf;

    var $templatePath = 'cms';

    function init(&$output, $block_id, &$aParams)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $c = &SGL_Config::singleton();
        $this->conf = $c->getAll();
        return $this->getBlockContent($output, $aParams);
    }

    function getBlockContent(&$output, &$aParams)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $blockOutput          = &new SGL_Output();
        $blockOutput->moduleName  = 'cms';
        $blockOutput->theme   = $this->conf['site']['defaultTheme'];
        $blockOutput->webRoot = $output->webRoot;

        //  set default params
        //  N/A

        //  set custom params
        //  N/A

        if (empty($aParams['contentId'])) {
            SGL::raiseError('You must provide a content id or name
                for ContentView block to work properly', SGL_ERROR_INVALIDARGS);
        }
        if (is_numeric($aParams['contentId'])) {
            $oContent = SGL_Content::getById($aParams['contentId']);
        } else {
            //  Assume it's a contentName
            $oContent = SGL_Content::getByName($aParams['contentId']);
        }
        if (PEAR::isError($oContent)) {
            SGL::logMessage('A content id or name was provided in ' . __METHOD__ .
                ' but no content actually exist', true, PEAR_LOG_INFO);
            return false;
        }
        //  load appropriate template
        $templateFile = 'contentTypes/' . SGL_Inflector::camelise($oContent->typeName) . '.html';
        $templatePath1 = SGL_WEB_ROOT . '/themes/' . $this->conf['site']['defaultTheme'] . '/cms/' . $templateFile;
        $templatePath2 = SGL_MOD_DIR . '/cms/templates/' . $templateFile;
        $blockOutput->masterTemplate = (is_file($templatePath1) || is_file($templatePath2))
            ? $templateFile
            : 'contentView.html';
        $blockOutput->oContent = $oContent;

        //  Encode current url for redirecting purposes
        $reg = SGL_Registry::singleton();
        $url = $reg->getCurrentUrl();
        $blockOutput->redir = urlencode(urlencode($url->toString()));

        return $this->process($blockOutput);
    }

    function process(&$output)
    {
        $view = new SGL_HtmlSimpleView($output);
        return $view->render();
    }
}
?>