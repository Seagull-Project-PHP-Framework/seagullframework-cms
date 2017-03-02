<div id="page-create" class="block-helper block-icon block-icon-new block-input">
    <h2><a href="#"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("create page (header)"));?></a></h2>
    <form class="inner" method="post" action="">
        <fieldset>
            <select>
            <?php if ($this->options['strict'] || (is_array($t->aPageTypes)  || is_object($t->aPageTypes))) foreach($t->aPageTypes as $typeId => $typeName) {?>
                <option value="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("action|add||langId|langId||siteId|siteId"));?>"><?php echo htmlspecialchars($typeName);?></option>
            <?php }?>
            </select>
            <input class="button" type="button" name="submitted" value="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("create page (action)"));?>" />
        </fieldset>
    </form>
</div>
