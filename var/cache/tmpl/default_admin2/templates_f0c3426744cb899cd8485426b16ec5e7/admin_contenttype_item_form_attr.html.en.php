<tr>
    <td class="cell-30">
        <?php if ($t->oAttr->attr_name)  {?><input type="hidden" name="attr[id][]" value="<?php echo htmlspecialchars($t->oAttr->attr_id);?>" /><?php }?>
        <?php if (!$t->oAttr->attr_name)  {?><input type="hidden" name="attr[id][]" value="" /><?php }?>
        <input class="text alias" type="text" name="attr[alias][]" value="<?php echo htmlspecialchars($t->oAttr->attr_alias);?>" />
    </td>
    <td class="cell-30">
        <input class="text readonly" type="text" name="attr[name][]" value="<?php echo htmlspecialchars($t->oAttr->attr_name);?>" readonly="readonly" />
    </td>
    <td class="cell-30">
        <select class="types" name="attr[type_id][]">
            <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateSelect'))) echo $t->generateSelect($t->aAttribTypes,$t->oAttr->attr_type_id);?>
        </select>

        <?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) if ($this->plugin("attribTypeIsList",$t->oAttr->attr_type_id)) { ?>
        &nbsp;
        <select class="list" name="attr[list_id][]">
            <option value=""><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("select attribute list"));?></option>
            <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateSelect'))) echo $t->generateSelect($t->aLists,$t->oAttr->attr_params['attributeListId']);?>
        </select>
        <?php } else {?>
        &nbsp;
        <select class="list" name="attr[list_id][]" style="display: none">
            <option value=""><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("select attribute list"));?></option>
            <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateSelect'))) echo $t->generateSelect($t->aLists);?>
        </select>
        <?php }?>
    </td>
    <td class="cell-10 cell-right">
        <a href="#" class="deleteattr" title="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("delete content type attribute"));?>">
            <img src="<?php echo htmlspecialchars($t->webRoot);?>/themes/<?php echo htmlspecialchars($t->theme);?>/images/icons/action_stop.gif" alt="" /></a>
        <img class="ajaxLoader" src="<?php echo htmlspecialchars($t->webRoot);?>/themes/<?php echo htmlspecialchars($t->theme);?>/images/ajax-loader.gif" alt="" style="display: none;" />
    </td>
</tr>
