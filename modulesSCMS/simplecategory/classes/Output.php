<?php
require_once SGL_MOD_DIR . '/admin/classes/Output.php';

/**
 * Output helper.
 *
 * @package simplecategory
 * @author Dmitri Lakachauskis <lakiboy83@gmail.com>
 */
class SimpleCategoryOutput extends AdminOutput
{
    /**
     * Render tree nav. Recursive!
     *
     * @param array $aNodes
     *
     * @return string
     */
    public function renderCategoriesNav(array $aNodes)
    {
        $ret = '';
        foreach ($aNodes as $oNode) {
            $name = !empty($oNode->name)
                ? $oNode->name
                : SGL_Output::tr('no category translation');

            $ret .= "<li id=\"category_{$oNode->category2_id}\">";

            // category name
            $ret .= "<span class=\"text\">$name</span>\n";

            // actions
            $ret .= "&nbsp;";
            $ret .= "<a id=\"category-add_{$oNode->category2_id}\" class=\"action add\" href=\"#\">+</a>";
            $ret .= "&nbsp;&nbsp;";
            $ret .= "<a id=\"category-delete_{$oNode->category2_id}\" class=\"action del\" href=\"#\">-</a>";

            $ret .= $this->renderCategoriesNav($oNode->aChildren);
            $ret .= "</li>";
        }
        if (!empty($ret)) {
            $ret = "<ul>\n$ret\n</ul>\n";
        }
        return $ret;
    }

    /**
     * Render path.
     *
     * @param array $aNodes
     *
     * @return string
     */
    public function renderCategoryPath(array $aNodes)
    {
        $ret = '';
        foreach ($aNodes as $oNode) {
            if ($ret) {
                $ret .= '&nbsp;&nbsp;&gt;&nbsp;&nbsp;';
            }
            $ret .= !empty($oNode->name)
                ? $oNode->name
                : SGL_Output::tr('no category translation');
        }
        return $ret;
    }
}
?>