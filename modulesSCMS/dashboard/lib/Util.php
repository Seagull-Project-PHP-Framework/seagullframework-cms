<?php
require_once SGL_MOD_DIR . '/dashboard/classes/DashboardDAO.php';

class SGL_Dashboard_Util
{
    public static function renderBlocks($aBlocksMap, $pageId,
        $userId, SGL_Output $output)
    {
        // render blocks
        $aRenderedBlocks = array();
        foreach ($aBlocksMap as $blockName => $aBlockParams) {
            $aRenderedBlocks[$blockName] = self::_renderTemplate($output,
                $aBlockParams);
        }

        // widgets from database
        $aOrder = DashboardDAO::singleton()->getWidgetsByUserId($userId, $pageId);

        // put blocks by columns here
        $aColumns = array();

        // adding block content to columns according to position
        $aColumns = array(0 => '');
        foreach ($aOrder as $column => $aBlocks) {
            $aColumns[$column] = '';
            foreach ($aBlocks as $position => $blockname) {
                if (!empty($aRenderedBlocks[$blockname])) {
                    $aColumns[$column] .= $aRenderedBlocks[$blockname];
                }
                unset($aRenderedBlocks[$blockname]);
            }
        }

        // adding blocks without ordering in DB (if exists) to first column
        foreach ($aRenderedBlocks as $Blockcontent) {
            $aColumns[0] .= $Blockcontent;
        }

        return $aColumns;
    }

    private static function _renderTemplate($output, $aParams)
    {
        if (!is_array($aParams)) {
            $aParams = array('masterTemplate' => $aParams);
        }
        $o = clone $output;
        foreach ($aParams as $k => $v) {
            $o->$k = $v;
        }
        $view = new SGL_HtmlSimpleView($o);
        return $view->render();
    }
}
?>