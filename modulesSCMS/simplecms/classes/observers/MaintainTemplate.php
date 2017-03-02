<?php
require_once SGL_MOD_DIR . '/simplecms/classes/Output.php';
require_once 'SimpleCms/Util.php';

/**
 * Create/updates content type template.
 *
 * @package simplecms
 * @author Dmitri Lakachauskis <lakiboy83@gmail.com>
 */
class MaintainTemplate extends SGL_Observer
{
    protected function _getValueHtml(SGL_Attribute $oAttrib)
    {
        $attribName = $oAttrib->name;
        if (SimpleCmsOutput::attribTypeIsRichtext($oAttrib->typeId)) {
            $ret = "{oContent.$attribName:h}\n";
        } elseif (SimpleCmsOutput::attribTypeIsList($oAttrib->typeId)) {
            $ret = "<p>{this.plugin(#renderListAttribValue#,oContent,#$attribName#)}</p>\n";
        } elseif (SimpleCmsOutput::attribTypeIsMedia($oAttrib->typeId)) {
            $ret = "<p>{this.plugin(#renderMediaFile#,oContent.$attribName):h}</p>\n";
        } elseif (SimpleCmsOutput::attribTypeIsDate($oAttrib->typeId)) {
            $ret = "<p>{formatDate2(oContent.$attribName)}</p>\n";
        } else {
            $ret = "<p>{oContent.$attribName}</p>\n";
        }
        return $ret;
    }

    public function update(SimpleCms_Observable $observable)
    {
        $oContent = SGL_Content::getByType($observable->input->contentTypeName);

        // drop old tpl
        $oldName = $observable->input->contentTypeNameOld;
        $oldPath = SimpleCms_Util::getContentTypeTemplatePath($oldName);
        @unlink($oldPath);

        // build standard template
        $class = SGL_Output::camelise($oContent->typeName);
        $html  = '';
        foreach ($oContent->aAttribs as $oAttrib) {
            $value = $this->_getValueHtml($oAttrib);
            $html .= "    <div class=\"field\">\n";
            $html .= "        <h3>{this.plugin(#renderAttributeAlias#,oContent,#{$oAttrib->name}#)}</h3>\n";
            $html .= "        $value";
            $html .= "    </div>\n";
        }
        $html = "<div class=\"$class\">\n$html</div>\n";

        // save template
        $savePath = SimpleCms_Util::getContentTypeTemplatePath($oContent->typeName);
        $ok = self::_ensureDirIsWritable(dirname($savePath));
        if (!PEAR::isError($ok) && $ok) {
            file_put_contents($savePath, $html);
        }
    }

    /**
     * @todo move to SGL_File, too many dupes.
     *
     * @param string $dirName
     *
     * @return boolean
     */
    protected static function _ensureDirIsWritable($dirName)
    {
        $ok = true;
        if (!is_writable($dirName)) {
            require_once 'System.php';
            $ok = System::mkDir(array('-p', $dirName));
            if (!PEAR::isError($ok)) {
                $mask = umask(0);
                $ok   = @chmod($dirName, 0777);
                umask($mask);
            }
        }
        return $ok;
    }
}
?>