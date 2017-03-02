<?php
/* Reminder: always indent with 4 spaces (no tabs). */
// +---------------------------------------------------------------------------+
// | Copyright (c) 2008, Demian Turner                                         |
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
// | Seagull 0.6                                                               |
// +---------------------------------------------------------------------------+
// | Output.php                                                                |
// +---------------------------------------------------------------------------+
// | Author: Demian Turner <demian@phpkitchen.com>                             |
// +---------------------------------------------------------------------------+
// $Id: Output.php,v 1.9 2005/01/27 12:33:45 demian Exp $

require_once SGL_MOD_DIR  . '/cms/classes/CmsDAO.php';
require_once SGL_MOD_DIR  . '/cms/classes/NavigationDAO.php';
require_once SGL_MOD_DIR  . '/media2/classes/Media2DAO.php';

/**
 * View helper methods.
 *
 * @package cms
 * @author  Demian Turner <demian@phpkitchen.com>
 */
class CmsOutput
{
    function attrTypeConstantToString($attrTypeId)
    {
        static $contentTypeMap;
        if (!isset($contentTypeMap)) {
            $da = CmsDAO::singleton();
            $aRes = $da->getAttribTypes();
            $contentTypeMap = array();
            foreach ($aRes as $oContent) {
                $contentTypeMap[$oContent->attribute_type_id] = $oContent->alias;
            }
        }
        if (in_array($attrTypeId, array_keys($contentTypeMap))) {
            return $contentTypeMap[$attrTypeId];
        }
    }

    function checkCheckbox($value)
    {
        if ($value) {
            $ret = 'checked="checked"';
        } else {
            $ret = '';
        }
        return $ret;
    }

    function getByKey($aArray, $key)
    {
        return $aArray[$key];
    }

    /**
     * Generates the form fields from the item_type_mapping table for the
     * methods getDynamicContent() and getDynamicFields.
     *
     * @access  public
     * @param   int     $fieldID    Field ID
     * @param   string  $fieldName  Field Name
     * @param   mixed   $fieldValue Field Value
     * @param   int     $fieldType  Field Type
     * @param   string  $language   Language
     * @return  mixed   $formHTML   HTML Form
     */
    function renderEditAttribute($oAttribute)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);
        static $key = 0;
        switch ($oAttribute->typeId) {

        case SGL_CONTENT_ATTR_TYPE_FLOAT:
        case SGL_CONTENT_ATTR_TYPE_URL:
        case SGL_CONTENT_ATTR_TYPE_INT:
        case SGL_CONTENT_ATTR_TYPE_TEXT:
            $formHTML =  "<input type='text' class='text medium' name='content[attributes][data][$key]' id='frmFieldName_{$oAttribute->name}' value='{$oAttribute->value}' />";
            $formHTML .= "<input type='hidden' name='content[attributes][attr_id][$key]' value='{$oAttribute->id}' />";
            break;

        case SGL_CONTENT_ATTR_TYPE_LARGETEXT:
            $formHTML =  "<textarea class='textarea large' name='content[attributes][data][$key]' id='frmFieldName_{$oAttribute->name}' class='longText'>{$oAttribute->value}</textarea>";
            $formHTML .= "<input type='hidden' name='content[attributes][attr_id][$key]' value='{$oAttribute->id}' />";
            break;

        case SGL_CONTENT_ATTR_TYPE_RICHTEXT:
            //$formHTML = "<div class='wysiwyg' id='attrib{$oAttribute->id}'>{$oAttribute->value}</div>";
            $formHTML .="<textarea class='wysiwyg' name='content[attributes][data][$key]' id='attrib{$oAttribute->id}-field'>" .
                        $oAttribute->value . "</textarea>";
            //$formHTML .= "<iframe id='attrib{$oAttribute->id}-iframe' style='display:none'></iframe>";
            $formHTML .= "<input type='hidden' name='content[attributes][attr_id][$key]' value='{$oAttribute->id}' />";
            break;

        case SGL_CONTENT_ATTR_TYPE_FILE:
            static $aMedia;
            if (!isset($aMedia)) {
                $da = CmsDAO::singleton();
                $aMedia = $da->getMediaHash();
            }
            $htmlOptions = SGL_Output::generateSelect($aMedia, $oAttribute->value);
            $imageUrl = SGL_Output::makeUrl('preview', 'media2', 'media2')
                . 'mediaId/' . $oAttribute->value . '/thumb/small/';

            $formHTML = <<< HTML
            <select class="choice medium" name="content[attributes][data][$key]" id="frmFieldName_{$oAttribute->name}" onchange="switch_image(this)" />
                <option value="0">Choose ..</option>
                $htmlOptions
            </select>

            &nbsp;
            <input type="file" class="text" name="content[attributes][data][$key]" />
            <div class="preview">
                <img id="the_image_frmFieldName_{$oAttribute->name}" src="$imageUrl" alt="" />
            </div>
            <input type='hidden' name='content[attributes][attr_id][$key]' value='{$oAttribute->id}' />
HTML;
            break;

        case SGL_CONTENT_ATTR_TYPE_CHOICE:
            $da = CmsDAO::singleton();


            $aListValues = $oAttribute->getParams();
            $aAttributeValues = !empty($oAttribute->value)
                ? explode(';', $oAttribute->value)
                : array();
            $formHTML = "";
            foreach ($aListValues as $k => $v) {
                $checked = (in_array($k, $aAttributeValues)) ? " checked='checked'" : "";
                $formHTML .= "<input type='checkbox' name='content[attributes][data][$key][]'" .
                             " id='frmFieldName_{$oAttribute->name}_$k' value='$k'$checked />" .
                             "<label for='frmFieldName_{$oAttribute->name}_$k'>$v</label>";
            }
            $formHTML .= "<input type='hidden' name='content[attributes][checkbox][{$oAttribute->id}]' />";
            $formHTML .= "<input type='hidden' name='content[attributes][attr_id][$key]' value='{$oAttribute->id}' />";
            break;

        case SGL_CONTENT_ATTR_TYPE_RADIO:
            $da = CmsDAO::singleton();

            $aListValues = $oAttribute->getParams();
            $attrValue = $oAttribute->value;
            $formHTML = "";

            if (empty($aListValues)) {
                break;
            }
            foreach ($aListValues as $k => $v) {
                // Be carefull, choices values should not allow 0.
                // Stored as a string, we cannot make the difference between 0 and empty string
                $checked = ($k == $attrValue) ? " checked='checked'" : "";
                $formHTML .= "<input type='radio' name='content[attributes][data][$key]'" .
                             " id='frmFieldName_{$oAttribute->name}_$k' value='$k'$checked />" .
                             "<label for='frmFieldName_{$oAttribute->name}_$k'>$v</label>";
            }
            $formHTML .= "<input type='hidden' name='content[attributes][checkbox][{$oAttribute->id}]' />";
            $formHTML .= "<input type='hidden' name='content[attributes][attr_id][$key]' value='{$oAttribute->id}' />";
            break;

        case SGL_CONTENT_ATTR_TYPE_LIST:
            $da = CmsDAO::singleton();


            $formHTML =  "<select name='content[attributes][data][$key]' id='frmFieldName_{$oAttribute->name}'>";
            $formHTML .= "<option value='0'>" . SGL_String::translate("choose...") . "</option>";

            $aListValues = $oAttribute->getParams();
            foreach ($aListValues as $k => $v) {
                $selected = ($k == $oAttribute->value) ? " selected='selected'" : "";
                $formHTML .= "<option value='$k'$selected>$v</option>";
            }
            $formHTML .= "</select>";
            $formHTML .= "<input type='hidden' name='content[attributes][attr_id][$key]' value='{$oAttribute->id}' />";
            break;

        case SGL_CONTENT_ATTR_TYPE_DATE:
            $formHTML =  "<input type='text' class='text' name='content[attributes][data][$key]' id='frmFieldName_{$oAttribute->name}' value='{$oAttribute->value}' />";
            $formHTML .= "&nbsp;<img class=\"dateSelector\" id=\"startDateTrigger_{$oAttribute->name}\" src=\"".SGL_BASE_URL."/themes/default/images/icons/event_select_date.png\" width=\"18\" alt=\"cal\" /></p>";
            $formHTML .= "<input type='hidden' name='content[attributes][attr_id][$key]' value='{$oAttribute->id}' />";
            $js = <<<JS
<script type="text/javascript">
Calendar.setup(
    {
        inputField  : "frmFieldName_{$oAttribute->name}",       // ID of the input field
        ifFormat    : "%Y-%m-%d",                               // the date format
        displayArea : "frmFieldName_{$oAttribute->name}",
        daFormat    : "%Y-%m-%d",
        button      : "startDateTrigger_{$oAttribute->name}"    // ID of the button
    }
);
</script>
JS;
            $formHTML .= $js;
            break;

        default:
            $formHTML = 'not defined yet';
        }
        $key++;
        return $formHTML;
    }

    function renderAttribute($oContent, $attribName, $edit = false)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        foreach ($oContent->aAttribs as $key => $oAttrib) {
            if ($oAttrib->name == $attribName) {
                if ($edit) {
                    return self::renderEditAttribute($oAttrib);
                } else {
                    return self::renderViewAttribute($oAttrib);
                }
            }
        }
    }

    function renderAttributeAlias($oContent, $attribName)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $alias = '';
        foreach ($oContent->aAttribs as $key => $oAttrib) {
            if ($oAttrib->name == $attribName) {
                $alias = $oAttrib->alias;
            }
        }
        return $alias;
    }

    function renderViewAttribute($oAttribute, $editable = false)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $attributeInfo = ($editable)
            ? ' class="editable attribute attrib' .$oAttribute->name .' type' . $oAttribute->typeName . '" id="attrib' . $oAttribute->id .'"'
            : '';

        switch ($oAttribute->typeId) {

        case SGL_CONTENT_ATTR_TYPE_FLOAT:
            $html = "<p><span$attributeInfo>{$oAttribute->value}</span></p>";
            break;
        case SGL_CONTENT_ATTR_TYPE_INT:
        case SGL_CONTENT_ATTR_TYPE_TEXT:
            $html = "<p><span$attributeInfo>{$oAttribute->value}</span></p>";
            break;
        case SGL_CONTENT_ATTR_TYPE_LARGETEXT:
            $html = "<p$attributeInfo>{$oAttribute->value}</p>";
            break;
        case SGL_CONTENT_ATTR_TYPE_DATE:
            $date = $oAttribute->value;
            $html = "<p><span$attributeInfo>" . Cms_Date::formatDate($date) . "</span></p>";
            break;

        case SGL_CONTENT_ATTR_TYPE_RICHTEXT:
            //$html  = "<div>";
            //$html .= "<div class='imageEmpty floatLeft'>drag image here</div>";
            //$html .= "<div class='imageEmpty floatRight'>drag image here</div>";
            //$html .= "<div style='margin:0 95px;'>";
            $html .= "<div$attributeInfo>{$oAttribute->value}</div>";
            $html .= ($editable)
                ? "<iframe id='attrib{$oAttribute->id}-iframe' style='display:none'></iframe>"
                : "";
            //$html .= "<div class='clear'>&nbsp;</div></div>";
            //$html .= "</div>";
            break;

        case SGL_CONTENT_ATTR_TYPE_URL:
            $aUrlParts = explode(' ', $oAttribute->value, 2);
            $linkHref = (!empty($aUrlParts[0])) ? $aUrlParts[0] : 'http://www.example.com';
            $linkText = (!empty($aUrlParts[1])) ? $aUrlParts[1] : $aUrlParts[0];
            $html = "<a$attributeInfo href=\"{$linkHref}\">{$linkText}</a>";
            break;

        case SGL_CONTENT_ATTR_TYPE_CHOICE:
            $da = CmsDAO::singleton();

            $aListValues = $oAttribute->getParams();
            $aAttributeValues = !empty($oAttribute->value)
                ? explode(';', $oAttribute->value)
                : array();
            $sValues = "";
            $formHTML = "<div id='attrib{$oAttribute->id}-fieldEdit' style='display:none'>";
            foreach ($aListValues as $k => $v) {
                //echo'<pre>';die(print_r($aAttributeValues));
                if (in_array($k, $aAttributeValues)) {
                    $checked = " checked='checked'";
                    $sValues .= (strlen($sValues)) ? ", $v" : $v;
                } else {
                    $checked = "";
                }
                $formHTML .= "<input type='checkbox' name='content[attributes][data][$key][]'" .
                             " id='frmFieldName_{$oAttribute->name}_$k' value='$k'$checked />" .
                             "<label for='frmFieldName_{$oAttribute->name}_$k'>$v</label>";
            }
            $formHTML .= "</div>";
            $sValues = (strlen($sValues)) ? $sValues : "N/A";
            $html = "<p><span$attributeInfo>$sValues</span></p>" . $formHTML;
            break;

        case SGL_CONTENT_ATTR_TYPE_RADIO:
            $da = CmsDAO::singleton();

            $aListValues = $oAttribute->getParams();
            $formHTML = "<div id='attrib{$oAttribute->id}-fieldEdit' style='display:none'>";
            foreach ($aListValues as $k => $v) {
                //echo'<pre>';die(print_r($aAttributeValues));
                if ($k == $oAttribute->value) {
                    $checked = " checked='checked'";
                    $value = $v;
                } else {
                    $checked = "";
                }
                $formHTML .= "<input type='radio' name='content[attributes][data][$key]'" .
                             " id='frmFieldName_{$oAttribute->name}_$k' value='$k'$checked />" .
                             "<label for='frmFieldName_{$oAttribute->name}_$k'>$v</label>";
            }
            $formHTML .= "</div>";
            $value = (!empty($value)) ? $value : "N/A";
            $html = "<p><span$attributeInfo>$value</span></p>" . $formHTML;
            break;

        case SGL_CONTENT_ATTR_TYPE_LIST:
            $da = CmsDAO::singleton();

            $aListValues = $oAttribute->getParams();
            $html = "<select id='attrib{$oAttribute->id}-fieldEdit' style='display:none'>";
            $html .= "<option value='0'>Choose ..</option>";

            $displayValue = "Please choose";
            foreach ($aListValues as $k => $v) {
                if ($k == $oAttribute->value) {
                    $selected = " selected='selected'";
                    $displayValue = $v;
                } else {
                    $selected = "";
                }
                $html .= "<option value='$k'$selected>$v</option>";
            }
            $html .= "</select>";
            $html = "<p><span$attributeInfo>$displayValue</span></p>" . $html;
            break;

        case SGL_CONTENT_ATTR_TYPE_FILE:
            $mediaSrc = SGL_Url::makeLink('previewMedia', 'file', 'media') . 'frmMediaId/' . $oAttribute->value;
            $html = '<img' . $attributeInfo . ' src="' . $mediaSrc . '" alt="image" />';
            break;

        default:
            $html = "<p>Error: type not recognised</p>";
        }
        return $html;
    }

    function renderContent($oContent)
    {
        switch ($oContent->typeId) {

        case SGL_CONTENT_TYPE_CV_FORMAT_SIMPLE:
        default:

            $oContent->moduleName     = 'cms';
            $oContent->masterTemplate = 'cvFormatSimple.html';
            $view = new SGL_HtmlSimpleView($oContent);
            return $view->render();
        }
    }

    function getCounter($prepend='item', $mode='set')
    {
        static $count;
        if ($mode == 'set') {
            $count++;
        }
        return $prepend . '_' . $count;
    }

    function formatDate($date, $format = '%Y-%m-%d')
    {
        return Cms_Date::formatDate($date, $format);
    }

     /**
     * Generates collection of radio buttons.
     *
     * @access  public
     * @param   array   $array          hash of radio values
     * @return  string  $radioString    list of radio objects
     * @see     PermissionsMgr()
     */
    function generatePermsRadioList($array, $id = 'id')
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);
        $radioString = '';
        foreach ($array as $key => $valueObj) {
            $yes    = ($valueObj->isAllowed) ? ' checked' : '';
            $no     = (!$valueObj->isAllowed) ? ' checked' : '';
            $radioString .= "<tr class=" . SGL_Output::switchRowClass() . "><td align='center'>\n";
            $radioString .= "<input class='noBorder' type='radio' id='".$valueObj->$id."_yes' name='category[perms][".$valueObj->$id."]' value='1' $yes><label for=''>" . SGL_Output::translate('Yes') . "</label>\n";
            $radioString .= "<input class='noBorder' type='radio' id='".$valueObj->$id."_no' name='category[perms][".$valueObj->$id."]' value='0' $no><label for=''>" . SGL_Output::translate('No') . "</label>\n";
            $radioString .= "</td><td>$valueObj->name</td>";
            $radioString .= "</tr>\n";
        }
        return $radioString;
    }

     /**
     * Generates collection of radio buttons.
     *
     * @access  public
     * @param   array   $array          hash of radio values
     * @return  string  $radioString    list of radio objects
     * @see     PermissionsMgr()
     */
    function generatePermsRadioList1($array, $id = 'id')
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);
        $radioString = '';
        foreach ($array as $key => $valueObj) {
            $yes    = ($valueObj->isAllowed) ? ' checked="checked"' : '';
            $no     = (!$valueObj->isAllowed) ? ' checked="checked"' : '';
            $radioString .= "<p>\n\t\t";
            $radioString .= "<label>$valueObj->name</label>\n\t\t";
            $radioString .= "<input type='radio' id='".$valueObj->$id."_yes' name='category[perms][".$valueObj->$id."]' value='1' $yes>" . SGL_Output::translate('Yes') . "\n\t\t";
            $radioString .= "<input type='radio' id='".$valueObj->$id."_no' name='category[perms][".$valueObj->$id."]' value='0' $no>" . SGL_Output::translate('No') . "\n\t\t";
            $radioString .= "</p>\n";
        }
        return $radioString;
    }

    function getAllPages()
    {
        $_da = CmsNavigationDAO::singleton();
        $aPages = $_da->getSectionTree();
        $aRet = array();
        foreach ($aPages as $k => $aPage) {
            $padding = str_repeat('&nbsp;&nbsp;', ($aPage['level_id'] -1));
            $aRet[$aPage['resource_uri']] = $padding . $aPage['title'];
        }
        $r = SGL_Output::generateSelect($aRet);
        return $r;
    }

    function categoryImageExists($imgName)
    {
        $c = SGL_Config::singleton();
        $conf = $c->getAll();
        $webRootPath = $conf['path']['webRoot'];
        $catImagesPath = $conf['CategoryMgr']['categoryImagesDir'];
        $imagePath = $webRootPath . $catImagesPath .'/' . $imgName;
        return is_file($imagePath);
    }

    function getMediaTypeIdByName($name)
    {
        return Media2DAO::singleton()->getMediaTypeIdByName($name);
    }

    public function getExistingLanguageVersions($oContent)
    {
        $da = CmsDAO::singleton();
        $aLanguageVersions = $da->getExistingLanguagesByContentId($oContent->id);
        $countLangs = count($aLanguageVersions);
        $html = '';
        foreach ($aLanguageVersions as $k => $langCode) {
            $linkTpl    = '<a href="%s">%s</a>';
            $href       = SGL_Output::makeUrl('edit', 'content', 'cms') . 'frmContentId/' . $oContent->id . '/cLang/' . $langCode;
            $html      .= sprintf($linkTpl, $href, $langCode);
            if ($k < ($countLangs - 1)) {
                $html  .= ', ';
            }
        }
        return $html;
    }

    /**
     * Generates a combobox of languages.
     *
     * @param   $exclude    bool   If set to true, will exclude already existing languages
     *                              If set to false, will exclude non existing languages
     */
    function generateFilteredLanguagesSelect($aLanguages, $oContent, $exclude = true)
    {
        $da = CmsDAO::singleton();
        $aExistingLanguages = $da->getExistingLanguagesByContentId($oContent->id);
        $aValues = array();
        foreach ($aLanguages as $langCode => $aLang) {
            if (!preg_match('/utf-8$/', $langCode)) {
                continue;
            }
            if ($exclude) {
                if (!in_array($aLang[2], $aExistingLanguages)) {
                    $aValues[$aLang[2]] = $aLang[1];
                }
            } else {
                if (in_array($aLang[2], $aExistingLanguages)) {
                    $aValues[$aLang[2]] = $aLang[1];
                }
            }
        }
        return SGL_Output::generateSelect($aValues, $oContent->langCode);
    }

    /**
     * FIXME: not called anywhere
     *
     * @return string
     */
    public function getAvailLanguagesLinks()
    {
        $trans = SGL_Translation3::singleton('array');
        $aLanguages = $trans->getAvailableLanguages();
        $i = 1;
        $total = count($aLanguages);
        if ($total > 1) {
            $htmlLinks = '<div class="lang">';
            foreach ($aLanguages as $fullLangCode => $aLang) {
                $tA = '<a class="version %s" href="%s" title="%s"><img src="%s" alt="%s" /></a>';
                $class  = SGL::getCurrentLang() == $aLang[2] ? 'current' : '';
                $link   = SGL_BASE_URL . '/index.php?lang=' . $fullLangCode;
                preg_match('/(\w*)\-utf\-8$/', $aLang[1], $aMatches);
                $title  = SGL_String::translate($aMatches[1] . ' version');
                $flagSrc = SGL_BASE_URL . '/themes/default/images/icons/flag_' . $aLang[2] . '.png';
                $text   = $aLang[2];
                $htmlLinks .= sprintf($tA,
                    $class, $link, $title, $flagSrc, $text);
                if ($i < $total) {
                    //$htmlLinks .= '<span>|</span>';
                }
                $i++;
            }
            $htmlLinks .= '</div>';
            return $htmlLinks;
        }
    }

    function generateLanguagesSelect($aLanguages, $selected = null)
    {
        $aValues = array();
        foreach ($aLanguages as $key => $aLang) {
            $aValues[$aLang[2]] = $aLang[1];
        }
        return SGL_Output::generateSelect($aValues, $selected);
    }
}

class Cms_Date
{
    /**
     * Simple date formater (may be in the ISO, TIMESTAMP or UNIXTIME format)
     *
     * @access  static
     * @param   string  $input  date (may be in the ISO, TIMESTAMP or UNIXTIME format) value
     * @param   string  $input  format (strftime like)
     * @return  string  $output user-friendly format
     */
    function formatDate($date, $format = '%Y-%m-%d')
    {
        if (is_string($date)) {
            require_once 'Date.php';
            $date = new Date($date);

            return $date->format($format);
        } else {
            SGL::raiseError('no input date passed to SGL_Date::format incorrect type',
                SGL_ERROR_INVALIDARGS);
        }
    }
}
?>
