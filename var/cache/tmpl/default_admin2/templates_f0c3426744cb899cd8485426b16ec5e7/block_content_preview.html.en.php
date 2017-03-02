<div id="content-preview" class="block-helper">
    <h2><a href="#"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("content preview (header)"));?></a></h2>
    <div class="inner">
        <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'assign'))) echo htmlspecialchars($t->assign($t->cVersion,$t->oContent->version));?>
        <input id="content-preview-trigger" class="button" type="button" name="preview" value="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("preview content (button)"));?>" sgl:link="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("","cmscontentview","simplecms"));?>" />
    </div>
</div>
