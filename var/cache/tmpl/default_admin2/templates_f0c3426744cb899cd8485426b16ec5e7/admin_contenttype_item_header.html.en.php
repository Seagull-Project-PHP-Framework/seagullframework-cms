<h2 id="content-type-item-header_<?php echo htmlspecialchars($t->contentTypeId);?>" class="clearfix" <?php echo $t->style;?>>
    <?php if ($t->contentTypeName)  {?>
        <a id="content-type-item-trigger_<?php echo htmlspecialchars($t->contentTypeId);?>" href="#"><?php echo htmlspecialchars($t->contentTypeName);?></a>
    <?php } else {?>
        <a id="content-type-item-trigger_<?php echo htmlspecialchars($t->contentTypeId);?>" href="#"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("content type name not specified"));?></a>
    <?php }?>
    <em>(<?php echo htmlspecialchars($t->contentTypeId);?>)</em>
    <span class="actions clearfix">
        <a id="content-type-item-edit_<?php echo htmlspecialchars($t->contentTypeId);?>" href="#" title="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("edit content type (action)"));?>">
            <img src="<?php echo htmlspecialchars($t->webRoot);?>/themes/<?php echo htmlspecialchars($t->theme);?>/images/icons/page_edit.gif" alt="" /></a>
        <a id="content-type-item-delete_<?php echo htmlspecialchars($t->contentTypeId);?>" href="#" class="last" title="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("delete content type"));?>">
            <img src="<?php echo htmlspecialchars($t->webRoot);?>/themes/<?php echo htmlspecialchars($t->theme);?>/images/icons/action_stop.gif" alt="" /></a>
        <img class="ajaxLoader" src="<?php echo htmlspecialchars($t->webRoot);?>/themes/<?php echo htmlspecialchars($t->theme);?>/images/ajax-loader.gif" alt="" style="display: none;" />
    </span>
</h2>
