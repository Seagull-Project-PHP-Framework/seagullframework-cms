<?php if ($t->categoriesEnabled)  {?><div id="content-categories" class="block-helper">
    <h2><a href="#"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("content categories (header)"));?></a></h2>
    <div class="inner fieldsetlike">
        <select multiple="multiple" name="content[aClassifiers][categories][]">
            <?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo $this->plugin("renderCategoriesSelect",$t->oContent->langCode,$t->oContent->aClassifiers['categories']);?>
        </select>
    </div>
</div><?php }?>
