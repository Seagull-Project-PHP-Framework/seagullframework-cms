<?php if ($t->isAdd)  {?>
<h2 id="attrib-list-item-header_<?php echo htmlspecialchars($t->oAttr->id);?>" class="clearfix" style="display: none;">
<?php } else {?>
<h2 id="attrib-list-item-header_<?php echo htmlspecialchars($t->oAttr->id);?>" class="clearfix">
<?php }?>
    <a class="name" name="<?php echo htmlspecialchars($t->oAttr->id);?>">
        <?php if ($t->oAttr->name)  {?><?php echo htmlspecialchars($t->oAttr->name);?><?php } else {?><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("attribute name not specified"));?><?php }?></a>
    <em>(<?php echo htmlspecialchars($t->oAttr->id);?>)</em>
    <span class="actions clearfix">
        <a id="attrib-list-item-edit_<?php echo htmlspecialchars($t->oAttr->id);?>" href="#" title="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("edit attribute list"));?>">
            <img src="<?php echo htmlspecialchars($t->webRoot);?>/themes/<?php echo htmlspecialchars($t->theme);?>/images/icons/page_edit.gif" alt="" /></a>
        <a id="attrib-list-item-delete_<?php echo htmlspecialchars($t->oAttr->id);?>" class="last" href="#" title="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("delete attribute list"));?>">
            <img src="<?php echo htmlspecialchars($t->webRoot);?>/themes/<?php echo htmlspecialchars($t->theme);?>/images/icons/action_stop.gif" alt="" /></a>
        <img class="ajaxLoader" src="<?php echo htmlspecialchars($t->webRoot);?>/themes/<?php echo htmlspecialchars($t->theme);?>/images/ajax-loader.gif" alt="" style="display: none;" />
    </span>
</h2>
