<div id="page-container" class="columns layout-2cols">

    <div class="title-edit">
        <h1><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("routes manager (header)"));?></h1>
    </div>

    <div class="c75l list">
        <div class="layout-inner">
            <form id="frmRouteList" action="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("getRoutes","page","page"));?>" method="get">
            <table id="route-list" class="full">
                <thead>
                    <tr>
                        <th width="3%">
                            <input type="checkbox" name="checkAll" id="checkAll" />
                        </th>
                        <th width="45%" <?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo $this->plugin("getColumnSortIcon","route",$t->sortBy,$t->sortOrder);?>>
                            <a sgl:sort_by="route" href="#"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("name (th)"));?></a>
                        </th>
                        <th width="52%" <?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo $this->plugin("getColumnSortIcon","description",$t->sortBy,$t->sortOrder);?>>
                            <a sgl:sort_by="description" href="#"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("description (th)"));?></a>
                        </th>
                        <th width="5%">
                            <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("active (th)"));?>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
$x = new HTML_Template_Flexy($this->options);
$x->compile('route_tablelist.html');
$_t = function_exists('clone') ? clone($t) : $t;
foreach(get_defined_vars()  as $k=>$v) {
    if ($k != 't') { $_t->$k = $v; }
}
$x->outputObject($_t, $this->elements);
?>
                </tbody>
                <tfoot>
                    <tr>
                    <td colspan="4">
                    <button class="delete-routes"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("delete selected"));?></button>
                    </td>
                    </tr>
                </tfoot>
            </table><!-- full -->
            </form>
            <img id="route-list-loader" src="<?php echo htmlspecialchars($t->webRoot);?>/themes/<?php echo htmlspecialchars($t->theme);?>/images/ajax-loader_2.gif" alt="" style="display: none;" />

            <p id="route-list-pager" class="pager"><?php echo $t->pagerLinks;?></p>
        </div><!-- inner -->
    </div><!-- list -->

    <div class="c25r">
        <?php 
$x = new HTML_Template_Flexy($this->options);
$x->compile('block_route_add.html');
$_t = function_exists('clone') ? clone($t) : $t;
foreach(get_defined_vars()  as $k=>$v) {
    if ($k != 't') { $_t->$k = $v; }
}
$x->outputObject($_t, $this->elements);
?>
        <?php 
$x = new HTML_Template_Flexy($this->options);
$x->compile('block_route_filter.html');
$_t = function_exists('clone') ? clone($t) : $t;
foreach(get_defined_vars()  as $k=>$v) {
    if ($k != 't') { $_t->$k = $v; }
}
$x->outputObject($_t, $this->elements);
?>

    </div>

</div>

