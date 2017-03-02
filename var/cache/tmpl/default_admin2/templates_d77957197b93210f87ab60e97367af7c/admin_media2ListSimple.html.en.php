<p id="media-filter" class="clearfix">
    <a class="delete float_right" href="#"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("close window"));?></a>             
    <a class="upload float_right" href="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("uploadSimple","media2","media2"));?>?redir=<?php echo htmlspecialchars($t->redir);?>"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("upload media (short)"));?></a>

    <!-- Filter -->
    <label for="filter_mime"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("filter by mime type"));?></label>
    <select id="filter_mime" name="media_mime">
        <?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo $this->plugin("generateFilterSelect",$t->aMimeTypes,$t->mimeTypeId,"mimeTypeId",1);?>
    </select>
    &nbsp;
    <label for="filter_type"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("filter by media type"));?></label>
    <select id="filter_type" name="media_type">
        <?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo $this->plugin("generateFilterSelect",$t->aMediaTypes,$t->mediaTypeId,"mediaTypeId",1);?>
    </select>
</p>            

<?php if ($t->aMedias)  {?><div id="media-list" class="clearfix">
    <?php if ($this->options['strict'] || (is_array($t->aMedias)  || is_object($t->aMedias))) foreach($t->aMedias as $k => $oMedia) {?>
    
    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'isEqual'))) if ($t->isEqual($t->mediaId,$oMedia->media_id)) { ?>
        <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'assign'))) echo htmlspecialchars($t->assign($t->currentStyle,"item current"));?>
    <?php } else {?>
        <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'assign'))) echo htmlspecialchars($t->assign($t->currentStyle,"item"));?>                
    <?php }?>
    <div <?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo $this->plugin("getContentRowStyle",$t->currentStyle,4);?> id="media-item_<?php echo htmlspecialchars($oMedia->media_id);?>">                
        <?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) if ($this->plugin("isImageMimeType",$oMedia->mime_type)) { ?>
        <a class="preview" href="<?php echo htmlspecialchars($t->webRoot);?>/media2/img.php?path=<?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo htmlspecialchars($this->plugin("getImagePath",$oMedia));?>" title="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("click to enlarge"));?>">
            <img class="image" src="<?php echo htmlspecialchars($t->webRoot);?>/media2/img.php?path=<?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo htmlspecialchars($this->plugin("getImagePath",$oMedia,"small"));?>" alt="" /></a>
        <?php } else {?>
        <a href="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("download","media2","media2",$t->aMedias,"mediaId|media_id",$k));?>" title="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("click to download"));?>">
            <img class="image" src="<?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo htmlspecialchars($this->plugin("getIconByMimeType",$oMedia->mime_type));?>" alt="" /></a>
        <?php }?>
        <a href="#" class="descr accept" sgl:media_id="<?php echo htmlspecialchars($oMedia->media_id);?>">
            <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'summarise'))) echo htmlspecialchars($t->summarise($oMedia->name,15,1));?></a>
    </div>
    
    <?php }?>
</div><?php }?>

<?php if ($t->pagerLinks)  {?><p class="pager"><?php echo $t->pagerLinks;?></p><?php }?>

<?php if (!$t->aMedias)  {?><p>
    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("no media found"));?>
</p><?php }?>
