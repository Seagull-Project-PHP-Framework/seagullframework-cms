<?php
require_once dirname(__FILE__) . '/MaintainTemplate.php';

/**
 * Updates template for certain content type.
 *
 * @package simplecms
 * @author Dmitri Lakachauskis <lakiboy83@gmail.com>
 */
class UpdateContentTemplate extends MaintainTemplate
{
    public function update(SimpleCms_Observable $observable)
    {
        $typeName     = $observable->input->oContent->typeName;
        $templatePath = SimpleCms_Util::getContentTypeTemplatePath($typeName);
        $ok           = self::_ensureDirIsWritable(dirname($templatePath));
        $content      = $this->_stripPhp($observable->input->oData->template);
        if (!PEAR::isError($ok) && $ok) {
            file_put_contents($templatePath, $content);
        }
    }

    protected function _stripPhp($str)
    {
        if (preg_match('<\?|\?>', $str)) {
            $ret = '';
        } else {
            $ret = $str;
        }
        return $ret;
    }
}
?>