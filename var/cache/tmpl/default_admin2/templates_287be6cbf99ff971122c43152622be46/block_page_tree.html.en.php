<div id="page-tree" class="block-helper">
    <h2>
        <a href="#"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("page tree (header)"));?></a>
        <img class="ajaxLoader" src="<?php echo htmlspecialchars($t->imagesDir);?>/ajax-loader.gif" alt="" style="display: none;" />
    </h2>
    <div class="inner fieldsetlike">
        <ul class="simpleTree">
            <li class="root">
                <span class="text"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("top level page"));?></span>
                <!--span class="text">{tr(#page tree#)}</span-->
                <?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo $this->plugin("renderPageNav",$t->aTree);?>
            </li>
        </ul>
    </div>
</div>
