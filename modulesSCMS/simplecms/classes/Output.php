<?php
require_once SGL_MOD_DIR . '/media2/classes/Media2DAO.php';
require_once SGL_MOD_DIR . '/media2/lib/Media/Util.php';
require_once SGL_MOD_DIR . '/admin/classes/Output.php';

class SimpleCmsOutput extends AdminOutput
{
    /**
     * Check if attribute type is one if lists.
     *
     * @param integer $typeId
     *
     * @return boolean
     */
    public function attribTypeIsList($typeId)
    {
        return in_array($typeId, array(
            SGL_CONTENT_ATTR_TYPE_CHOICE,
            SGL_CONTENT_ATTR_TYPE_LIST,
            SGL_CONTENT_ATTR_TYPE_RADIO
        ));
    }

    /**
     * Check if attribute type is a rich text.
     *
     * @param integer $typeId
     *
     * @return boolean
     */
    public function attribTypeIsRichtext($typeId)
    {
        return $typeId == SGL_CONTENT_ATTR_TYPE_RICHTEXT;
    }

    /**
     * Check if attribute type is a media file.
     *
     * @param integer $typeId
     *
     * @return boolean
     */
    public function attribTypeIsMedia($typeId)
    {
        return $typeId == SGL_CONTENT_ATTR_TYPE_FILE;
    }

    /**
     * Check if attribute type is a date.
     *
     * @param integer $typeId
     *
     * @return boolean
     */
    public function attribTypeIsDate($typeId)
    {
        return $typeId == SGL_CONTENT_ATTR_TYPE_DATE;
    }

    /**
     * Attept to summarise content values in one string.
     * We don't want to show useless "content name" field.
     *
     * @param SGL_Content $oContent
     * @param integer $summariseChars
     *
     * @return string
     */
    public function getContentTitle(SGL_Content $oContent, $summariseChars = 40)
    {
        /*
        $ret = '';
        do {
            $oAttr = current($oContent->aAttribs);
            if (!empty($oAttr)) {
                if (!empty($oAttr->value)) {
                    if (!empty($ret)) {
                        $ret .= ' / ';
                    }
                    $ret .= strip_tags($oAttr->value);
                }
                next($oContent->aAttribs);
            }
        } while (strlen($ret) < $summariseChars && !empty($oAttr));
        reset($oContent->aAttribs);
        */
        $ret = $oContent->aAttribs[0]->value;
        if ($summariseChars) {
            $ret = SGL_Output::summarise($ret, $summariseChars, SGL_CHAR);
        }
        return $ret;
    }

    /**
     * Renders list with edit links to various content versions.
     *
     * @param SGL_Content $oContent
     * @param string $langCode
     *
     * @return string
     */
    public function renderContentEditVersionList(SGL_Content $oContent, $langCode)
    {
        $ret  = '';
        $oUrl = SGL_Registry::singleton()->getCurrentUrl();
        for ($i = $oContent->version; $i >= 1; $i--) {
            $str = SGL_Output::tr('content version %version%', 'vprintf',
                array('version' => $i));
            $url = $oUrl->makeLink(array(
                'moduleName'  => 'simplecms',
                'managerName' => 'cmscontent',
                'action'      => 'edit',
                'cLang'       => $langCode,
                'contentId'   => $oContent->id,
                'versionId'   => $i
            ));
            $ret .= "<li><a href=\"$url\">$str</a></li>\n";
        }
        if (!empty($ret)) {
            $ret = "<ul>\n" . $ret . "</ul>\n";
        }
        return $ret;
    }

    /**
     * Renders list with add/edit links to various content language versions.
     *
     * @param SGL_Content $oContent
     * @param array $aLangs
     *
     * @return string
     */
    public function renderContentEditLangList(SGL_Content $oContent, array $aLangs)
    {
        $ret       = '';
        $oUrl      = SGL_Registry::singleton()->getCurrentUrl();
        $aLangVers = CmsDAO::singleton()->getExistingLanguagesByContentId($oContent->id);
        foreach ($aLangs as $langCode => $langName) {
            if (in_array($langCode, $aLangVers)) {
                $str = SGL_Output::tr('%language% (edit latest version)',
                    'vprintf', array('language' => $langName));
                $url = $oUrl->makeLink(array(
                    'moduleName'  => 'simplecms',
                    'managerName' => 'cmscontent',
                    'action'      => 'edit',
                    'cLang'       => $langCode,
                    'contentId'   => $oContent->id,
                ));
            } else {
                $str = SGL_Output::tr('%language% (create new)', 'vprintf',
                    array('language' => $langName));
                $url = $oUrl->makeLink(array(
                    'moduleName'  => 'simplecms',
                    'managerName' => 'cmscontent',
                    'action'      => 'add',
                    'cLang'       => $langCode,
                    'type'        => $oContent->typeId,
                    'contentId'   => $oContent->id
                ));
            }
            $curr = $oContent->langCode == $langCode ? ' class="current"' : '';
            $ret .= "<li$curr><a href=\"$url\">$str</a></li>\n";
        }
        if (!empty($ret)) {
            $ret = "<ul>\n" . $ret . "</ul>\n";
        }
        return $ret;
    }

    public function renderAttributeAlias(SGL_Content $oContent, $attrName)
    {
        $ret = '';
        foreach ($oContent->aAttribs as $oAttrib) {
            if ($oAttrib->name == $attrName) {
                $ret = $oAttrib->alias;
                break;
            }
        }
        return $ret;
    }

    /**
     * Get attribute list values for certain attribute.
     *
     * @return string
     */
    public function renderListAttribValue(SGL_Content $oContent, $attrName)
    {
        // find attribute
        $oAttrib = null;
        foreach ($oContent->aAttribs as $oAttr) {
            if ($oAttr->name == $attrName) {
                $oAttrib = $oAttr;
                break;
            }
        }

        $ret = 'n/a';
        if (!empty($oAttrib) && !empty($oAttrib->value)) {
            $aValues = $oAttrib->getParams();
            switch ($oAttrib->typeId) {
                case SGL_CONTENT_ATTR_TYPE_RADIO:
                case SGL_CONTENT_ATTR_TYPE_LIST:
                    if (isset($aValues[$oAttrib->value])) {
                        $ret = $aValues[$oAttrib->value];
                    }
                    break;
                case SGL_CONTENT_ATTR_TYPE_CHOICE:
                    $aKeys    = explode(';', $oAttrib->value);
                    $variants = '';
                    foreach ($aKeys as $key) {
                        if (isset($aValues[$key])) {
                            if ($variants) {
                                $variants .= ', ';
                            }
                            $variants .= $aValues[$key];
                        }
                    }
                    if ($variants) {
                        $ret = $variants;
                    }
                    break;
            }
        }
        return $ret;
    }

    /**
     * Get HTML for media ID.
     *
     * @param integer  $mediaId
     *
     * @return string
     */
    public function renderMediaFile($mediaId)
    {
        $oMedia = Media2DAO::singleton()->getMediaById($mediaId);

        // get media icon/image preview
        if (!empty($oMedia)) {
            if (SGL_Media_Util::isImageMimeType($oMedia->mime_type)) {
                $mediaPath = SGL_Media_Util::getImagePathByMimeType(
                    $oMedia->file_name,
                    $oMedia->mime_type,
                    'small'
                );
                $mediaPath = SGL_BASE_URL
                    . '/media2/img.php?path=' . $mediaPath;
            } else {
                $mediaPath = SGL_Media_Util::getIconPathByMimeType(
                    $oMedia->mime_type, SGL_BASE_URL);
            }
            $ret = '<img src="' . $mediaPath . '" alt="" />';

        // error: media should be there
        } else {
            $msg = 'media file is missing in database';
            $ret = SGL_Output::tr($msg);
        }
        return $ret;
    }

    public function renderCategoriesSelect($langId, array $aCurrentCats)
    {
        // get full category tree
        require_once SGL_MOD_DIR . '/simplecategory/classes/SimpleCategoryDAO.php';
        $aCaterories = SimpleCategoryDAO::singleton()->getTreeByCategoryId(
            SGL_CATEGORY_ROOT, $langId, $onlyActive = false, $showUntranslated = true);

        // make "linear" array
        $aSelect = array();
        self::_lineupCategories($aCaterories, $aSelect);

        return SGL_Output::generateSelect($aSelect, $aCurrentCats, $multi = true);
    }

    protected static function _lineupCategories(array $aCategories, &$aSelect)
    {
        foreach ($aCategories as $oNode) {
            $prefix = str_repeat('&nbsp;&nbsp;&nbsp;', $oNode->level_id);
            $name   = !empty($oNode->name)
                ? $oNode->name : SGL_Output::tr('no category translation');
            $aSelect[$oNode->category2_id] = $prefix . $name;
            self::_lineupCategories($oNode->aChildren, $aSelect);
        }
    }

    /**
     * Get status text by value.
     *
     * @param SGL_Content $oContent
     *
     * @return string
     */
    public function getContentStatusString(SGL_Content $oContent)
    {
        $ret = 'uknown';
        switch ($oContent->status) {
            case SGL_CMS_STATUS_BEING_EDITED:
                $ret = 'being editted';
                break;
            case SGL_CMS_STATUS_FOR_APPROVAL:
                $ret = 'for approval';
                break;
            case SGL_CMS_STATUS_APPROVED:
                $ret = 'approved';
                break;
            case SGL_CMS_STATUS_PUBLISHED:
                $ret = 'published';
                break;
            case SGL_CMS_STATUS_ARCHIVED:
                $ret = 'archived';
                break;
            case SGL_CMS_STATUS_DELETED:
                $ret = 'deleted';
                break;
        }
        return SGL_Output::tr($ret . ' (status)');
    }

    /**
     * Get XHTML of content status img.
     *
     * @param SGL_Content $oContent
     *
     * @return string
     */
    public function getContentStatusIcon(SGL_Content $oContent)
    {
        switch ($oContent->status) {
            case SGL_CMS_STATUS_BEING_EDITED:
                $color = 'white';
                break;
            case SGL_CMS_STATUS_FOR_APPROVAL:
                $color = 'blue';
                break;
            case SGL_CMS_STATUS_APPROVED:
                $color = 'orange';
                break;
            case SGL_CMS_STATUS_PUBLISHED:
                $color = 'green';
                break;
            case SGL_CMS_STATUS_ARCHIVED:
            case SGL_CMS_STATUS_DELETED:
            default:
                $color = 'red';
                break;
        }
        $title = self::getContentStatusString($oContent);
        $src   = SGL_BASE_URL . '/themes/'
            . $_SESSION['aPrefs']['admin theme'] . '/images/icons/flag_'
            . $color . '.gif';
        return "<img src=\"$src\" alt=\"$title\" />";
    }

    /**
     * Render content edit fields.
     *
     * @param SGL_Content $oContent
     *
     * @return string
     */
    public function renderContentForEdit(SGL_Content $oContent)
    {
        $ret     = '';
        $aFields = array();
        foreach ($oContent->aAttribs as $oAttr) {
            $fieldHtml = '';
            $labelHtml = <<< HTML
<label for="content_attr_{$oAttr->name}">{$oAttr->alias}</label>
HTML;
            switch ($oAttr->typeId) {

                // regular fields
                case SGL_CONTENT_ATTR_TYPE_FLOAT:
                case SGL_CONTENT_ATTR_TYPE_URL:
                case SGL_CONTENT_ATTR_TYPE_INT:
                case SGL_CONTENT_ATTR_TYPE_TEXT:
                    $fieldHtml = <<< HTML
<input id="content_attr_{$oAttr->name}" class="text"
       name="content[attr][{$oAttr->name}]" value="{$oAttr->value}" />
HTML;
                    break;

                case SGL_CONTENT_ATTR_TYPE_DATE:
                    $fieldHtml = <<< HTML
<input id="content_attr_{$oAttr->name}" class="text datepicker"
       name="content[attr][{$oAttr->name}]" value="{$oAttr->value}" />
HTML;
                    break;

                // textareas
                case SGL_CONTENT_ATTR_TYPE_LARGETEXT:
                    $fieldHtml = <<< HTML
<textarea id="content_attr_{$oAttr->name}" name="content[attr][{$oAttr->name}]"
          rows="5" cols="20">{$oAttr->value}</textarea>
HTML;
                    break;

                // wysiwyg
                case SGL_CONTENT_ATTR_TYPE_RICHTEXT:
                    $fieldHtml = <<< HTML
<textarea id="content_attr_{$oAttr->name}" name="content[attr][{$oAttr->name}]"
          class="wysiwyg" rows="5" cols="20">{$oAttr->value}</textarea>
HTML;
                    break;

                // media
                case SGL_CONTENT_ATTR_TYPE_FILE:
                    $fieldHtml = self::renderMediaFieldForEdit($oAttr->name,
                        $oAttr->value, $oAttr->id);
                    break;

                // radio
                case SGL_CONTENT_ATTR_TYPE_RADIO:
                    $aValues = $oAttr->getParams();
                    foreach ($aValues as $k => $v) {
                        $checked = $k == $oAttr->value ? ' checked="checked"' : '';
                        if ($fieldHtml) { // spacer between values
                            $fieldHtml .= '&nbsp;&nbsp;';
                        } else { // default value
                            $fieldHtml .= <<< HTML
<input type="hidden" name="content[attr][{$oAttr->name}]" value="$k" />
HTML;
                        }
                        $fieldHtml .= <<< HTML
<input type="radio" name="content[attr][{$oAttr->name}]" value="$k" $checked /> $v
HTML;
                    }
                    break;

                // combobox
                case SGL_CONTENT_ATTR_TYPE_LIST:
                    $aValues    = $oAttr->getParams();
                    $aDefault   = array('' => SGL_Output::tr('select list value'));
                    $fieldHtml .= "<select id=\"content_attr_{$oAttr->name}\"";
                    $fieldHtml .= " name=\"content[attr][{$oAttr->name}]\">\n";
                    $fieldHtml .= SGL_Output::generateSelect(
                        $aDefault + $aValues, $oAttr->value);
                    $fieldHtml .= "</select>\n";
                    break;

                // checkboxes
                case SGL_CONTENT_ATTR_TYPE_CHOICE:
                    $aValues  = $oAttr->getParams();
                    $aChecked = !empty($oAttr->value)
                        ? explode(';', $oAttr->value)
                        : array();
                    foreach ($aValues as $k => $v) {
                        $checked = in_array($k, $aChecked) ? ' checked="checked"' : '';
                        if ($fieldHtml) { // spacer between values
                            $fieldHtml .= '&nbsp;&nbsp;';
                        } else { // nothing selected
                            $fieldHtml .= <<< HTML
<input type="hidden" name="content[attr][{$oAttr->name}]" value="" />
HTML;
                        }
                        $fieldHtml .= <<< HTML
<input type="checkbox" name="content[attr][{$oAttr->name}][]" value="$k" $checked /> $v
HTML;
                    }
                    break;

                // undefined
                default:
                    $fieldHtml = 'undefined';
            }

            // append preview type
            switch ($oAttr->typeId) {
                case SGL_CONTENT_ATTR_TYPE_LARGETEXT:
                    $previewType = 'textarea';
                    break;
                case SGL_CONTENT_ATTR_TYPE_FILE:
                    $previewType = 'media';
                    break;
                case SGL_CONTENT_ATTR_TYPE_RICHTEXT:
                    $previewType = 'wysiwyg';
                    break;
                case SGL_CONTENT_ATTR_TYPE_RADIO:
                    $previewType = 'radio';
                    break;
                case SGL_CONTENT_ATTR_TYPE_CHOICE:
                    $previewType = 'checkbox';
                    break;
                default:
                    $previewType = '';
            }
            if (!empty($previewType)) {
                $previewType = ' sgl:preview_type="' . $previewType . '"';
            }

            // create xHTML field record
            $aFields[] = $labelHtml
                . '<div class="field-wrapper" ' . $previewType
                . ' id="field-wrapper_' . $oAttr->name . '">'
                . $fieldHtml . '</div>';
        }
        if (!empty($aFields)) {
            $ret = '<li>' . implode("</li>\n<li>", $aFields) . '</li>' . "\n";
            $ret = "<ol class=\"clearfix\">\n$ret</ol>\n";
        }
        return $ret;
    }

    /**
     * Render media field for content edit screen.
     *
     * @param string $attrName   name of attribute e.g. screenshot
     * @param string $attrValue  value of attribute e.g. 235
     * @param integer $attrId    attribute ID
     *
     * @see self::renderContentForEdit()
     *
     * @return string
     */
    public static function renderMediaFieldForEdit($attrName, $attrValue, $attrId)
    {
        $fieldHtml = '';

        // show existent media
        if (!empty($attrValue)) {
            $fieldHtml  = self::renderMediaFile($attrValue);
            $selectText = 'change media';

        // upload/select new media
        } else {
            $fieldHtml  = SGL_Output::tr('no media file assigned');
            $selectText = 'assign media';
        }

        $fieldHtml   = '<span class="image">' . $fieldHtml . '</span>';
        $selectText  = SGL_Output::tr($selectText);
        $selectLink  = SGL_Registry::singleton()
                ->getCurrentUrl()->makeLink(array(
            'moduleName'  => 'media2',
            'managerName' => 'media2',
            'action'      => 'listSimple',
            'mediaId'     => $attrValue
        ));
        $loaderSrc  = SGL_BASE_URL . '/themes/default_admin2/images/ajax-loader.gif';
        $fieldHtml .= <<< HTML
<img class="ajaxLoader" src="$loaderSrc" alt="" style="display: none;" />
<p class="comment">
    <a id="media-select_{$attrId}" class="media-select" href="$selectLink"
       sgl:attr_name="$attrName"
       sgl:attr_id="$attrId">$selectText</a>
</p>
<input id="content_media_$attrId" type="hidden"
       name="content[attr][$attrName]" value="$attrValue" />
HTML;
        return $fieldHtml;
    }
}
?>