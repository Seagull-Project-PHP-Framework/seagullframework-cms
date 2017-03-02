<div id="content-type-stats" class="block-helper">
    <h2><a href="#"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("content types usage (header)"));?></a></h2>
    <div class="inner">
        <p><em><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("content types usage total content items"));?></em></p>
        <ul>
            <?php if ($this->options['strict'] || (is_array($t->aStats)  || is_object($t->aStats))) foreach($t->aStats as $aStat) {?><li>
                <?php echo htmlspecialchars($aStat['name']);?>: <?php echo htmlspecialchars($aStat['total']);?>
            </li><?php }?>
        </ul>
    </div>
</div>
