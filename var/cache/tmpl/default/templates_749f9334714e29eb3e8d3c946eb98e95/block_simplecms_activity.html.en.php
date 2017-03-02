<div id="dashboard-activity" class="block-helper block-item-list widget-item" sgl:widget="admin_dashboard-content_activity">
    <h2 class="widget-header"><a href="#"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("latest content activity (header)"));?></a></h2>
    <div class="inner">
    
        <?php if ($this->options['strict'] || (is_array($t->aActivity)  || is_object($t->aActivity))) foreach($t->aActivity as $date => $aItems) {?><div class="date-container">
            <h3><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'formatDate2Pretty'))) echo htmlspecialchars($t->formatDate2Pretty($date));?></h3>
            <ul>
                <?php if ($this->options['strict'] || (is_array($aItems)  || is_object($aItems))) foreach($aItems as $oItem) {?><li>
                    <em><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'formatTime'))) echo htmlspecialchars($t->formatTime($oItem->last_updated));?></em>
                    <span><?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo $this->plugin("getContentLangIcon",$oItem->language_id);?></span>
                    <?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo $this->plugin("getContentActivityMessage",$oItem,$t->aContentTypes,"",1);?>
                </li><?php }?>
            </ul>
        </div><?php }?>
        
        <a href="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("","cmsactivity","simplecms"));?>"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("view all activity"));?></a>
        
    </div>
</div>
