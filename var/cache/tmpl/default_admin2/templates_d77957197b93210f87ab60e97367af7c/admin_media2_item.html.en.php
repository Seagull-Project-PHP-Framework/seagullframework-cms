<div <?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo $this->plugin("getContentRowStyle","item",4);?> id="media-item_<?php echo htmlspecialchars($t->oMedia->media_id);?>">
    <?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) if ($this->plugin("isImageMimeType",$t->oMedia->mime_type)) { ?>
    <a class="preview" href="<?php echo htmlspecialchars($t->webRoot);?>/media2/img.php?path=<?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo htmlspecialchars($this->plugin("getImagePath",$t->oMedia));?>" title="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("click to enlarge"));?>">
        <img class="image" src="<?php echo htmlspecialchars($t->webRoot);?>/media2/img.php?path=<?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo htmlspecialchars($this->plugin("getImagePath",$t->oMedia,"small"));?>" alt="" /></a>
    <?php } else {?>
    <a href="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("download","media2","media2",$t->aMedias,"mediaId|media_id",$t->k));?>" title="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("click to download"));?>">
        <img class="image" src="<?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo htmlspecialchars($this->plugin("getIconByMimeType",$t->oMedia->mime_type));?>" alt="" /></a>
    <?php }?>
    <a href="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("edit","media2","media2",$t->aMedias,"mediaId|media_id",$t->k));?>" class="descr">
        <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'summarise'))) echo htmlspecialchars($t->summarise($t->oMedia->name,15,1));?></a>
    <span class="triggers clearfix">
        <!--
        {if:this.plugin(#isImageMimeType#,oMedia.mime_type)}
        <a class="preview" href="{webRoot}/media2/img.php?path={this.plugin(#getImagePath#,oMedia)}">View</a>
        {else:}
        <a href="{makeUrl(#download#,#media2#,#media2#,aMedias,#mediaId|media_id#,k)}">DL</a>
        {end:}
        -->
        <a class="editItem" href="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("edit","media2","media2",$t->aMedias,"mediaId|media_id",$t->k));?>" title="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("edit media (action)"));?>">
            <img src="<?php echo htmlspecialchars($t->webRoot);?>/themes/<?php echo htmlspecialchars($t->theme);?>/images/icons/page_edit.gif" alt="" /></a>
        <a class="deleteItem" sgl:media_id="<?php echo htmlspecialchars($t->oMedia->media_id);?>" href="#" title="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("delete media (action)"));?>">
            <img src="<?php echo htmlspecialchars($t->webRoot);?>/themes/<?php echo htmlspecialchars($t->theme);?>/images/icons/action_stop.gif" alt="" /></a>
    </span>
    <img style="display: none;" class="ajaxLoader" src="<?php echo htmlspecialchars($t->webRoot);?>/themes/<?php echo htmlspecialchars($t->theme);?>/images/ajax-loader.gif" alt="" />
</div>
