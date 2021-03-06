<div id="content-container" class="columns layout-2cols">

    <div class="c75l list">
        <div class="layout-inner">

            <table id="content-list" class="full">
                <thead>
                    <tr>
                        <th width="46%"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("title (th)"));?></th>
                        <!--<th>{tr(#content id (th)#)}</th>-->
                        <!--th>&nbsp;</th-->
                        <th width="10%"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("language (th)"));?></th>
                        <th width="20%" <?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo $this->plugin("getColumnSortIcon","last_updated",$t->sortBy,$t->sortOrder);?>>
                            <a sgl:sort_by="last_updated" href="#"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("last updated (th)"));?></a>
                        </th>
                        <th width="15%" <?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo $this->plugin("getColumnSortIcon","username",$t->sortBy,$t->sortOrder);?>>
                            <a sgl:sort_by="username" href="#"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("author (th)"));?></a>
                        </th>
                        <th width="9%" <?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo $this->plugin("getColumnSortIcon","status",$t->sortBy,$t->sortOrder);?>>
                            <a sgl:sort_by="status" href="#"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("status (th)"));?></a>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
$x = new HTML_Template_Flexy($this->options);
$x->compile('cmscontent_tablelist.html');
$_t = function_exists('clone') ? clone($t) : $t;
foreach(get_defined_vars()  as $k=>$v) {
    if ($k != 't') { $_t->$k = $v; }
}
$x->outputObject($_t, $this->elements);
?>
                </tbody>
            </table><!-- full -->

            <img id="content-list-loader" src="<?php echo htmlspecialchars($t->webRoot);?>/themes/<?php echo htmlspecialchars($t->theme);?>/images/ajax-loader_2.gif" alt="" style="display: none;" />

            <p id="content-list-pager" class="pager"><?php echo $t->pagerLinks;?></p>

        </div><!-- inner -->
    </div><!-- list -->

    <div class="c25r">

        <?php 
$x = new HTML_Template_Flexy($this->options);
$x->compile('block_content_add.html');
$_t = function_exists('clone') ? clone($t) : $t;
foreach(get_defined_vars()  as $k=>$v) {
    if ($k != 't') { $_t->$k = $v; }
}
$x->outputObject($_t, $this->elements);
?>
        <?php 
$x = new HTML_Template_Flexy($this->options);
$x->compile('block_content_filter.html');
$_t = function_exists('clone') ? clone($t) : $t;
foreach(get_defined_vars()  as $k=>$v) {
    if ($k != 't') { $_t->$k = $v; }
}
$x->outputObject($_t, $this->elements);
?>
        <?php 
$x = new HTML_Template_Flexy($this->options);
$x->compile('block_content_filter_statuses.html');
$_t = function_exists('clone') ? clone($t) : $t;
foreach(get_defined_vars()  as $k=>$v) {
    if ($k != 't') { $_t->$k = $v; }
}
$x->outputObject($_t, $this->elements);
?>

    </div>

</div><!-- content-container -->
