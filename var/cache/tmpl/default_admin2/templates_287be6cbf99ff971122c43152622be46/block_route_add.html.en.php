<div id="route-create" class="block-helper block-icon block-icon-new block-input">
    <h2><a href="#"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("create url (header)"));?></a></h2>
    <form class="inner" method="post" action="">
        <fieldset>
            <select>
            <?php if ($this->options['strict'] || (is_array($t->aSites)  || is_object($t->aSites))) foreach($t->aSites as $siteId => $siteName) {?>
                <option value="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("action|add"));?><?php echo htmlspecialchars($siteId);?>"><?php echo htmlspecialchars($siteName);?></option>
            <?php }?>
            </select>
            <input class="button" type="button" name="submitted" value="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("create url (action)"));?>" />
        </fieldset>
    </form>
</div>
