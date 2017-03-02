<?php

class SimpleCms_Util
{
    public static function formatLanguageList($aLangs)
    {
        $aRet = array();
        foreach ($aLangs as $langCode => $langName) {
            $langCode        = reset(explode('-', $langCode));
            $pos             = strpos($langName, '(');
            $langName        = strtolower(substr($langName, 0, $pos-1));
            $aRet[$langCode] = SGL_Output::translate($langName);
        }
        return $aRet;
    }

    /**
     * Get project revision number.
     *
     * @return string
     */
    public static function getRevisionNum()
    {
        $str = file_get_contents(SGL_APP_ROOT . '/REV.txt');
        $ok  = preg_match('/\$Revision:\s([0-9]+)\s\$/', $str, $aMatches);
        return $aMatches[1];
    }

    public static function getContentTypeTemplatePath($typeName)
    {
        $name = preg_replace('/[^a-z0-9_]/i', '_', $typeName);
        $name = strtolower($name);
        $path = SGL_VAR_DIR . '/cms/content_types/' . $name . '.html';
        return $path;
    }

    public static function getPageRanges()
    {
        return array(
            10 => 10,
            20 => 20,
            30 => 30,
            40 => 40,
            50 => 50
        );
    }
}
?>