<div id="content-overview" class="block-helper block-icon block-icon-note block-overview">
    <h2><a href="#"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("content overview (header)"));?></a></h2>
    <div class="inner fieldsetlike">
        <dl class="clearfix">
            <dt><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("content type"));?></dt>
            <dd><?php echo htmlspecialchars($t->oContent->typeName);?></dd>
            <?php if ($t->isEdit)  {?>
            <dt><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("content author"));?></dt>
            <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'assign'))) echo htmlspecialchars($t->assign($t->userId,$t->oContent->createdById));?>
            <dd><a href="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("manager|cmsactivity||userId|userId"));?>"><?php echo htmlspecialchars($t->oContent->createdByName);?></a></dd>
            <dt><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("content creation date"));?></dt>
            <dd><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'formatDateTime2Pretty'))) echo htmlspecialchars($t->formatDateTime2Pretty($t->oContent->dateCreated));?></dd>
            <dt><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("content modification date"));?></dt>
            <dd><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'formatDateTime2Pretty'))) echo htmlspecialchars($t->formatDateTime2Pretty($t->oContent->lastUpdated));?></dd>
            <?php }?>
        </dl>
    </div>
</div>
