<div id="content-status" class="block-helper block-dynamic-icon">
    <h2 class="floatbox"><a href="#"><?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo $this->plugin("getContentStatusIcon",$t->oContent);?> <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("status (header)"));?></a></h2>
    <div class="inner">
        <select name="content[status]">
            <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateSelect'))) echo $t->generateSelect($t->aStatuses,$t->oContent->status);?>
        </select>
    </div>
</div>
