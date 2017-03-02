<?php

require_once SGL_CORE_DIR . '/Output.php';
require_once 'SimpleCms/Util.php';

/**
 * SimpleCms output class.
 *
 * @package SimpleCms
 * @author Dmitri Lakachauskis <lakiboy83@gmail.com>
 */
class SimpleCms_Output extends SGL_Output
{
    /**
     * Additional project specific global Output vars.
     */
    public function __construct()
    {
        $this->commentOpen  = '<!--';
        $this->commentClose = '-->';
        $this->rev          = SimpleCms_Util::getRevisionNum();
    }

    /**
     * Before exporting variables (constants) to Javascript make sure
     * we include default vars set.
     *
     * @return string
     */
    public function getExportedJsVars()
    {
        // default vars
        $this->exportJsVar('WEBROOT', $this->webRoot);
        $this->exportJsVar('CURRURL', $this->currUrl);
        $this->exportJsVar('FC', SGL_Config::get('site.frontScriptName'));
        $this->exportJsVar('THEME', $this->theme);
        $this->exportJsVar('SESSID', $this->sessID);
        $this->exportJsVar('USERID', SGL_Session::getUid());
        $this->exportJsVar('IMAGESDIR', $this->webRoot . '/themes/' . $this->theme . '/images');

        // messages
        $this->exportJsVar('MSG_INFO', SGL_MESSAGE_INFO);
        $this->exportJsVar('MSG_WARNING', SGL_MESSAGE_WARNING);
        $this->exportJsVar('MSG_ERROR', SGL_MESSAGE_ERROR);

        return parent::getExportedJsVars();
    }

    public function msgGet($elem = 'message')
    {
        // BC for admin GUI
        if ($this->adminGuiAllowed && $this->theme == 'default_admin') {
            return SGL_Output::msgGetAdmin();
        }

        $message     = SGL_Session::get('message');
        $messageType = SGL_Session::get('messageType');
        $html        = '';

        // show messages left from managers
        if (!empty($message)) {
            SGL_Session::remove('message');
            SGL_Session::remove('messageType');

            $message = addcslashes($message, '\'');

            $html .= $this->scriptOpen . "\n";
            $html .= <<< JS
$(document).ready(function() {
    SGL2.showMessage('#$elem', '$message', $messageType);
});
JS;
            $html .= $this->scriptClose . "\n";
        }
        // show PEAR errors
        if (SGL_Error::count() && (!SGL_Config::get('debug.production')
            || SGL_Session::getRoleId() == SGL_ADMIN))
        {
            $message     = SGL_Error::getLast()->getMessage();
            $messageType = SGL_MESSAGE_ERROR;
            $message     = addcslashes($message, '\'');

            $html .= $this->scriptOpen . "\n";
            $html .= <<< JS
$(document).ready(function() {
    SGL2.showMessage('#$elem', '$message', $messageType);
});
JS;
            $html .= $this->scriptClose . "\n";
        }
        return $html;
    }

    /**
     * Generates language switching array.
     *
     * @param string $selected
     *
     * @return string
     *
     * @todo fix explode('-', $k) stuff
     */
    public function generateLanguageSelect($selected = null)
    {
        $aValues = SGL_Util::getLangsDescriptionMap();
        foreach ($aValues as $k => $v) {
            unset($aValues[$k]);
            $langCode = reset(explode('-', $k));
            if (SGL_Config::get('translation.langInUrl')) {
                $k = $langCode;
            }
            $link = SGL_Registry::singleton()
                ->getCurrentUrl()
                ->makeCurrentLink(array('lang' => $k));

            if ($langCode == $selected) {
                $selected = $link;
            }

            $pos = strpos($v, '(');
            $aValues[$link] = SGL_Output::translate(strtolower(substr($v, 0, $pos-1)));
        }
        // sort by keys rather than by values
        ksort($aValues);
        return SGL_Output::generateSelect($aValues, $selected);
    }

    /**
     * Generate link to current homepage. Used mainly in banners
     * for clickable logos.
     *
     * @return string
     *
     * @note default action and/or params are not taken into account
     */
    public function getHomePageLink()
    {
        $aParams['moduleName']  = SGL_Config::get('site.defaultModule');
        $aParams['managerName'] = SGL_Config::get('site.defaultManager');
        if (SGL_Config::get('translation.langInUrl')) {
            $aParams['lang'] = SGL::getCurrentLang();
        }

        return SGL_Registry::singleton()
            ->getCurrentUrl()
            ->makeLink($aParams);
    }

    // --------------------------
    // --- Strings with links ---
    // --------------------------

    public function getLoggedOnUserString($username)
    {
        $link = $this->makeUrl('', 'account2', 'user2');
        $link = '<a href="' . $link . '">' . $username . '</a>';
        return $this->tr('logged in as %link%', 'vprintf', array('link' => $link));
    }

    /**
     * Seagull copyright string.
     *
     * @return string
     */
    public function getFooterPoweredByString()
    {
        $sglName = $this->tr('seagull php framework');
        $link    = "<a href=\"http://seagullproject.org\" title=\"$sglName\">$sglName</a>";
        return $this->tr('powered by %link%', 'vprintf', array('link' => $link));
    }

    /**
     * YAML copyright string.
     *
     * @return string
     */
    public function getFooterLayoutString()
    {
        $link = '<a href="http://www.yaml.de/en">YAML</a>';
        return $this->tr('layout based on %link%', 'vprintf', array('link' => $link));
    }

    // -------------------
    // --- CMS methods ---
    // -------------------

    public function renderCmsAttribViewList($oContent, $attribName)
    {
        $ret = '';
        foreach ($oContent->aAttribs as $oAttrib) {
            if ($oAttrib->name == $attribName) {
                switch ($oAttrib->typeId) {

                    // render radio list
                    case SGL_CONTENT_ATTR_TYPE_RADIO:
                        $aValues = $oAttrib->getParams();
                        foreach ($aValues as $k => $v) {
                            if ($k == $oAttrib->value) {
                                $ret .= $this->tr($v . ' (attrib value)');
                                break;
                            }
                        }
                        break;
                }
            }
        }
        return $ret;
    }

    public function renderCmsAttribEditList($oContent, $attribName, $fieldName = '')
    {
        $ret = '';
        foreach ($oContent->aAttribs as $oAttrib) {
            if ($oAttrib->name == $attribName) {
                $fieldName = !empty($fieldName) ? $fieldName : $attribName;
                switch ($oAttrib->typeId) {

                    // render options list
                    /*
                    case SGL_CONTENT_ATTR_TYPE_LIST:
                        $opts .= '<option value="0">'
                            . SGL_String::translate('choose...')
                            . '</option>';
                        $aValues = $oAttrib->getParams();
                        foreach ($aValues as $k => $v) {
                            $selected = $k == $oAttrib->value
                                ? ' selected="selected"' : '';
                            $opts .= "<option value=\"$k\" $selected>"
                                . htmlspecialchars($v)
                                . '</option>';
                        }
                        break;
                    */

                    // render radio list
                    case SGL_CONTENT_ATTR_TYPE_RADIO:
                        $aValues = $oAttrib->getParams();
                        $input   = '';
                        foreach ($aValues as $k => $v) {
                            $checked = $k == $oAttrib->value
                               ? ' checked="checked"' : '';
                            if ($input) {
                                // spacer between values
                                $input .= ' ';
                            } else {
                                // default value
                                $input .= "<input type=\"hidden\" name=\"$fieldName\""
                                    . " value=\"$k\" />";
                            }
                            $input .= $this->tr($v . ' (attrib value)') . ' '
                               . " <input type=\"radio\" name=\"$fieldName\""
                               . " value=\"$k\" $checked />";
                        }
                        $ret .= $input;
                        break;
                }
            }
        }
        return $ret;
    }

    // ---------------------------
    // --- Date transformation ---
    // ---------------------------

    /**
     * Format date according to tz and locale.
     *   NB! Suffix '2' needed to avoid duplication with original method.
     *
     * @param string $date  YYYY-MM-DD HH:II:SS
     *
     * @return string
     */
    public function formatDate2($date)
    {
        $dt = new DateTime($date);
        return $dt->format('j F Y');
    }

    /**
     * Format time according to tz and locale.
     *
     * @param string $date  YYYY-MM-DD HH:II:SS
     *
     * @return string
     */
    public function formatTime($date)
    {
        $dt = new DateTime($date);
        return $dt->format('H:i');
    }

    /**
     * Format both date and time according to tz and locale.
     *
     * @param string $date  YYYY-MM-DD HH:II:SS
     *
     * @return string
     */
    public function formatDateTime($date)
    {
        $dt = new DateTime($date);
        return $dt->format('j F Y H:i');
    }

    /**
     * Same as formatDate2() method. Tries to "convert" current date to
     *  - Tomorrow
     *  - Today
     *  - Yesterday
     *
     * @param string $date  YYYY-MM-DD HH:II:SS
     *
     * @return string
     *
     * @see self::formatDate2()
     */
    public function formatDate2Pretty($date)
    {
        $todaysDate    = new DateTime();
        $yesterdayDate = new DateTime();
        $tomorrowDate  = new DateTime();
        $currentDate   = new DateTime($date);

        $yesterdayDate->modify("-1 day");
        $tomorrowDate->modify("+1 day");

        $format        = 'd.m.Y';
        $todaysDate    = $todaysDate->format($format);
        $yesterdayDate = $yesterdayDate->format($format);
        $tomorrowDate  = $tomorrowDate->format($format);
        $currentDate   = $currentDate->format($format);

        if ($currentDate == $todaysDate) {
            $ret = SGL_String::translate('today (date)');
        } elseif ($currentDate == $tomorrowDate) {
            $ret = SGL_String::translate('tomorrow (date)');
        } elseif ($currentDate == $yesterdayDate) {
            $ret = SGL_String::translate('yesterday (date)');
        } else {
            $ret = $this->formatDate2($date);
        }

        return $ret;
    }

    /**
     * Same as formatDateTime() method, but uses formatDate2Pretty() logic
     * to show date part of datetime.
     *
     * @param string $date
     *
     * @return string
     *
     * @see self::formatDateTime()
     * @see self::formatDate2Pretty()
     */
    public function formatDateTime2Pretty($date)
    {
        return $this->formatDate2Pretty($date) . ' ' . $this->formatTime($date);
    }
}
?>