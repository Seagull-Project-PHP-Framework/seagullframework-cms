<!-- Statuses -->
<div id="content-status-legend" class="block-helper">
    <h2><a href="#"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("statuses (header)"));?></a></h2>
    <div class="inner">
        <ul class="status-container">
        <?php if ($this->options['strict'] || (is_array($t->aStatusesData)  || is_object($t->aStatusesData))) foreach($t->aStatusesData as $key => $aStatus) {?>
            <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'assign'))) echo htmlspecialchars($t->assign($t->statusId,$aStatus['status_id']));?>
            <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'isEqual'))) if ($t->isEqual($aStatus['status_id'],$t->status)) { ?><li class="<?php echo htmlspecialchars($aStatus['className']);?>">
                <a href="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("module|simplecms||manager|cmscontent||type|type||status|statusId||cLang|cLang||resPerPage|resPerPage"));?>"><strong><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr($aStatus['name']));?></strong></a>
            </li><?php }?>
            <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'isEqual'))) if (!$t->isEqual($aStatus['status_id'],$t->status)) { ?><li class="<?php echo htmlspecialchars($aStatus['className']);?>">
                <a href="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("module|simplecms||manager|cmscontent||type|type||status|statusId||cLang|cLang||resPerPage|resPerPage"));?>"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr($aStatus['name']));?></a>
            </li><?php }?>
        <?php }?>
        </ul>
    </div>
</div>
