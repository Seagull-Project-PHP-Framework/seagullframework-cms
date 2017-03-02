<?php
require_once SGL_MOD_DIR . '/simplecms/classes/SimpleCmsDAO.php';

/**
 * Updates contents of certain content type.
 *
 * @package simplecms
 * @author Dmitri Lakachauskis <lakiboy83@gmail.com>
 */
class UpdateContentAttributes extends SGL_Observer
{
    public function __construct()
    {
        $this->da = SimpleCmsDAO::singleton();
    }

    public function update(SimpleCms_Observable $observable)
    {
        if (SGL_Config::get('CmsContentTypeMgr.updateContentsOnChange')) {

            // delete old attribute data
            foreach ($observable->input->aDelAttrIds as $attrId) {
                $this->da->deleteAttributeDataByAttributeId($attrId);
            }

            // add new attribute data
            if (!empty($observable->input->aNewAttrIds)) {
                $aContents = $this->da->getContentsByContentTypeId($observable->input->ctId);
                foreach ($observable->input->aNewAttrIds as $attrId) {
                    foreach ($aContents as $oContent) {
                        $aData['content_id']   = $oContent->content_id;
                        $aData['version']      = $oContent->version;
                        $aData['language_id']  = $oContent->language_id;
                        $aData['attribute_id'] = $attrId;

                        // specify better default values here
                        // based on attribute type?
                        $aData['value']  = null;
                        $aData['params'] = null;

                        $this->da->addAttributeData($aData);
                    }
                }
            }
        }
    }
}
?>