<?php
require_once SGL_MOD_DIR . '/media2/lib/Media/Util.php';
require_once SGL_MOD_DIR . '/simplecms/classes/Output.php';

class AdminOutput
{
    public function getColumnSortIcon($header, $sortBy, $sortOrder)
    {
        $ret = 'sort';
        if ($header == $sortBy) {
            $direction = $sortOrder == 'asc' ? 'up' : 'down';
            $ret      .= ' sort-' . $direction;
        }
        return " class=\"$ret\"";
    }

    /**
     * Simple method to show content list in zebra style.
     *
     * @return string
     */
    public function getContentRowStyle($append = '', $change = null)
    {
        static $row, $changeAfter;
        if (!isset($row)) {
            $row = 0;
        }
        if (!empty($change)) {
            if (!isset($changeAfter)) {
                $changeAfter = 0;
            } else {
                $changeAfter++;
            }
            if ($changeAfter % $change == 0) {
                $row = ($changeAfter/$change) % 2 == 0 ? 0 : 1;
            }
        }
        $ret = $append;
        if ($row % 2 == 1) {
            $ret .= ' zebra';
        }
        if (!empty($ret)) {
            $ret = ' class="' . trim($ret) . '"';
        }
        $row++;
        return $ret;
    }

    /**
     * Wrapper for SGL_Output::generateSelect(). Generates selectboxes
     * for various filter parameters.
     *
     * @param array $aValues
     * @param string $selected
     * @param string $param
     *
     * @return string
     */
    public function generateFilterSelect($aValues, $selected, $param,
        $includeAll = false)
    {
        if ($includeAll) {
            $aValues = array('all' => SGL_Output::tr('all')) + $aValues;
        }
        $aRet = array();
        $oUrl = SGL_Registry::singleton()->getCurrentUrl();
        foreach ($aValues as $id => $value) {
            $url = $oUrl->makeCurrentLink(array($param => $id));
            $aRet[$url] = $value;
            if ($id == $selected) {
                $selected = $url;
            }
        }
        return SGL_Output::generateSelect($aRet, $selected);
    }

    /**
     * @todo fixme
     */
    public function getArrayValueByKey(array $aArray, $key, $key2 = null)
    {
        if (isset($aArray[$key])) {
            $ret = $aArray[$key];
        }
        if (!empty($key2) && is_array($ret) && isset($ret[$key2])) {
            $ret = $ret[$key2];
        }
        return $ret;
    }

    // --------------
    // --- media2 ---
    // --------------

    public function formatFileSize($size)
    {
        $aUnits = array('B', 'Kb', 'Mb', 'Gb');
        foreach ($aUnits as $unit) {
            if ($size > 1024) {
                $size = round($size / 1024, 2);
            } else {
                break;
            }
        }
        $ret = $size . ' ' . $unit;
        return $ret;
    }

    public function isImageMimeType($mimeType)
    {
        return SGL_Media_Util::isImageMimeType($mimeType);
    }

    public function getIconByMimeType($mimeType)
    {
        return SGL_Media_Util::getIconPathByMimeType($mimeType, SGL_BASE_URL);
    }

    public function getImagePath($oMedia, $thumb = false)
    {
        return SGL_Media_Util::getImagePathByMimeType(
            $oMedia->file_name,
            $oMedia->mime_type,
            $thumb
        );
    }

    // -----------------
    // --- simplecms ---
    // -----------------

    /**
     * Render xHTML for language icon.
     *
     * @param string $lang
     *
     * @return string
     */
    public function getContentLangIcon($lang)
    {
        $src = SGL_BASE_URL . '/themes/'
            . $_SESSION['aPrefs']['admin theme'] . '/images/icons/flags/'
            . $lang . '.gif';
        return "<img src=\"$src\" alt=\"$lang\" />";
    }

    public function getContentActivityClass($oContent)
    {
        if ($oContent->version == 1) {
            $ret = 'new';
        } else {
            $ret = 'edit';
        }
        return $ret;
    }

    public function getContentActivityMessage($oContent, $aTypes,
        $redir = null, $short = false)
    {
        $type = $this->getContentActivityClass($oContent);

        // new content / language version
        if ($type == 'new') {
            $msg = '%username% added new %type% content item';
            $rev = false;

        // update content / new version
        } else {
            $msg = '%username% updated %type% content item (%revision%)';
            $rev = true;
        }
        if ($short) {
            $msg .= ' (short)';
        }

        $linkContent = SGL_Registry::singleton()->getCurrentUrl()->makeLink(array(
            'moduleName'  => 'simplecms',
            'managerName' => 'cmscontent',
            'action'      => 'edit',
            'contentId'   => $oContent->content_id,
            'cLang'       => $oContent->language_id,
            'versionId'   => $oContent->version
        ));
        if ($redir) {
            $linkContent .= "?redir=" . $redir;
        }
        $linkUser = SGL_Registry::singleton()->getCurrentUrl()->makeLink(array(
            'moduleName'  => 'simplecms',
            'managerName' => 'cmsactivity',
            'userId'      => $oContent->updated_by_id
        ));
//        $typeName = isset($aTypes[$oContent->content_type_id])
//            ? $aTypes[$oContent->content_type_id]
//            : 'unknown';

        $oContentFull = SGL_Content::getById($oContent->content_id);
        $charsLen     = $short ? 10 : 50;
        $typeName     = SimpleCmsOutput::getContentTitle($oContentFull, $charsLen);

        // prepare params
        $aParams = array(
            'username' => "<a href=\"$linkUser\">{$oContent->username}</a>",
            'type'     => "<a href=\"$linkContent\">$typeName</a>",
        );
        if ($rev) {
            $aParams['revision'] = $oContent->version;
        }
        $msg = SGL_Output::tr($msg, 'vprintf', $aParams);

        return $msg;
    }
}
?>