<!-- Create new content & content type -->
<div id="content-create" class="block-helper block-icon block-icon-new block-input widget-item" sgl:widget="admin_dashboard-content_create">
    <h2 class="widget-header"><a href="#"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("create content (header)"));?></a></h2>
    <form class="inner" method="post" action="">
        <fieldset>
            <select>
            <?php if ($this->options['strict'] || (is_array($t->aContentTypes)  || is_object($t->aContentTypes))) foreach($t->aContentTypes as $typeId => $typeName) {?>
                <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'assign'))) echo htmlspecialchars($t->assign($t->typeId2,$typeId));?>
                <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'isEqual'))) if ($t->isEqual($t->type,$typeId)) { ?><option selected="selected" value="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("module|simplecms||manager|cmscontent||action|add||type|typeId2||cLang|cLang"));?>"><?php echo htmlspecialchars($typeName);?></option><?php }?>
                <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'isEqual'))) if (!$t->isEqual($t->type,$typeId)) { ?><option value="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("module|simplecms||manager|cmscontent||action|add||type|typeId2||cLang|cLang"));?>"><?php echo htmlspecialchars($typeName);?></option><?php }?>
            <?php }?>
            </select>
            <input class="button" type="button" name="submitted" value="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("create content (action)"));?>" />
        </fieldset>
    </form>
</div>
