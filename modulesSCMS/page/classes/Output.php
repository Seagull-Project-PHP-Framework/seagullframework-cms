<?php
require_once SGL_MOD_DIR . '/admin/classes/Output.php';

/**
 * Page output helpers.
 *
 * @package page
 * @author Dmitri Lakachauskis <lakiboy83@gmail.com>
 */
class PageOutput extends AdminOutput
{
    /**
     * Site filter selectbox.
     *
     * @param array $aValues
     * @param integer $selected
     *
     * @return string
     */
    public function generateSiteFilterSelect(array $aValues, $selected)
    {
        $aRet = array();
        $oUrl = SGL_Registry::singleton()->getCurrentUrl();
        foreach ($aValues as $id => $value) {
            $url = $oUrl->makeLink(array(
                'moduleName'  => 'page',
                'managerName' => 'page',
                'siteId'      => $id,
                'langId'      => SGL_Request::singleton()->get('langId'),
                'parentId'    => 'all'
            ));
            $aRet[$url] = $value;
            if ($id == $selected) {
                $selected = $url;
            }
        }
        return SGL_Output::generateSelect($aRet, $selected);
    }

    /**
     * Page filter selectbox.
     *
     * @param array $aPages
     * @param integer $parentId
     *
     * @return string
     */
    public function renderPageFilterSelect(array $aPages, $parentId)
    {
        $aSelect = array();
        self::_lineupPages($aPages, $aSelect);
        return $this->generateFilterSelect($aSelect, $parentId, 'parentId', true);
    }

    /**
     * Page selectbox.
     *
     * @param array $aPages
     *
     * @return string
     */
    public function renderPageSelect(array $aPages)
    {
        $aSelect = array();
        self::_lineupPages($aPages, $aSelect, 1);
        $aSelect = array('' => SGL_Output::tr('top level page')) + $aSelect;
        return SGL_Output::generateSelect($aSelect);
    }

    public function getContentTitle(SGL_Content $oContent)
    {
        return SimpleCmsOutput::getContentTitle($oContent, 50);
    }

    //
    // Below code is almost duplicated from SimpleCategoryDAO.
    // This needs to be fixed/merged.
    //

    protected static function _lineupPages(array $aPages, &$aSelect, $startLevel = 0)
    {
        foreach ($aPages as $oNode) {
            $prefix = str_repeat('&nbsp;&nbsp;&nbsp;', $oNode->level_id + $startLevel);
            $name   = !empty($oNode->title)
                ? $oNode->title : SGL_Output::tr('no page title translation');
            $aSelect[$oNode->page_id] = $prefix . $name;
            self::_lineupPages($oNode->aChildren, $aSelect, $startLevel);
        }
    }

    /**
     * Render path.
     *
     * @param array $aNodes
     *
     * @return string
     */
    public function renderPagePath(array $aNodes)
    {
        $ret = '';
        foreach ($aNodes as $oNode) {
            if ($ret) {
                $ret .= '&nbsp;&nbsp;&gt;&nbsp;&nbsp;';
            }
            $ret .= !empty($oNode->title)
                ? $oNode->title
                : SGL_Output::tr('no page title translation');
        }
        return $ret;
    }

    /**
     * Render navigation tree. Recursive!
     *
     * @param array $aNodes
     *
     * @return string
     */
    public function renderPageNav(array $aNodes)
    {
        $ret = '';
        foreach ($aNodes as $oNode) {
            $name = !empty($oNode->title)
                ? $oNode->title
                : SGL_Output::tr('no page title translation');

            $ret .= "<li id=\"page-item_{$oNode->page_id}\">";

            // category name
            $ret .= "<span class=\"text\">$name</span>\n";

            $ret .= $this->renderPageNav($oNode->aChildren);
            $ret .= "</li>";
        }
        if (!empty($ret)) {
            $ret = "<ul>\n$ret\n</ul>\n";
        }
        return $ret;
    }

    public function renderRoutePathParameters(SGL_Routes_Route $route)
    {
        $ret = '';

        foreach ($route->getPathParams() as $name => $value) {
            $item = <<<EOL
            <li>
            <label>{$name}</label>
            <div><input name="route[{$name}]" value="{$value}" type="text" class="text" /></div>
            </li>
EOL;
            $ret .= $item;
        }

        return $ret;
    }

    /**
     * Renders default params
     * Default params are params which are not in path and required (moduleName,controller,action)
     * @param SGL_Routes_Route $route
     */
    public function renderRouteDefaultParameters(SGL_Routes_Route $route)
    {
        $ret = '';

        $aDefaultParams = array_diff(
            $route->getParams(false),
            $route->getPathParams()
        );

        foreach ($aDefaultParams as $name => $value) {
            $item = <<<EOL
        	<li>
        	<label>{$name}</label>
        	<div>
        	<input name="route[{$name}]" value="{$value}" type="text" class="text dynamic" /><a href="" class="remove-parameter">[ - ]</a>
        	</div>
        	</li>
EOL;
            $ret .= $item;
        }

        return $ret;
    }

    public function renderRouteParametersCompact($route)
    {
        if (!($route instanceof SGL_Routes_Route)) {
            return '';
        }

        $aTmp = array();

        $aParams = $route->getParams(false);

        foreach ($aParams as $key => $value) {
        	$aTmp[] = "$key/$value";
        }

        return implode('/',$aTmp);
    }
}
?>