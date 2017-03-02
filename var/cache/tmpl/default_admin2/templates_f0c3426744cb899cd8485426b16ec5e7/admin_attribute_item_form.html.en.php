<?php if ($t->isAdd)  {?>
<form id="attrib-list-item-form_<?php echo htmlspecialchars($t->oAttr->id);?>" class="editmode" method="post" action="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("updateAttributeList","simplecms","simplecms"));?>">
<?php } else {?>
<form id="attrib-list-item-form_<?php echo htmlspecialchars($t->oAttr->id);?>" class="editmode" method="post" action="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("updateAttributeList","simplecms","simplecms"));?>" style="display: none;">
<?php }?>
    <div class="subcolumns">
        <fieldset class="c50l newfield">
            <ol class="clearfix">
                <li>
                    <label for="attrib-list-item-field-name_<?php echo htmlspecialchars($t->oAttr->id);?>"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("attribute list name"));?></label>
                    <div>
                        <input id="attrib-list-item-field-name_<?php echo htmlspecialchars($t->oAttr->id);?>" class="text" type="text" name="list[name]" value="<?php echo htmlspecialchars($t->oAttr->name);?>" />
                    </div>
                </li>
                <li>
                    <label for="attrib-list-item-field-key_<?php echo htmlspecialchars($t->oAttr->id);?>"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("list field key"));?></label>
                    <div>
                        <input id="attrib-list-item-field-key_<?php echo htmlspecialchars($t->oAttr->id);?>" class="text" type="text" name="list_field_key" value="" />
                    </div>
                </li>
                <li>
                    <label for="attrib-list-item-field-value_<?php echo htmlspecialchars($t->oAttr->id);?>"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("list field value"));?></label>
                    <div>
                        <input id="attrib-list-item-field-value_<?php echo htmlspecialchars($t->oAttr->id);?>" class="text" type="text" name="list_field_value" value="" />
                    </div>
                </li>
            </ol>
            <p class="fieldIndent">
                <span class="actions">
                    <input id="attrib-list-item-submit_<?php echo htmlspecialchars($t->oAttr->id);?>" class="button" type="button" name="submit" value="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("save list (button)"));?>" />
                    &nbsp;<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("or"));?>&nbsp;
                    <a id="attrib-list-item-cancel_<?php echo htmlspecialchars($t->oAttr->id);?>" href="#"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("cancel"));?></a>
                </span>
                <img class="ajaxLoader" src="<?php echo htmlspecialchars($t->webRoot);?>/themes/<?php echo htmlspecialchars($t->theme);?>/images/ajax-loader.gif" alt="" style="display: none;" />
            </p>
        </fieldset>
        <fieldset class="c50r fields">
            <div class="inner">
                <a id="field-add_<?php echo htmlspecialchars($t->oAttr->id);?>" class="field-add" href="#" title="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("add or update list field"));?>">
                    <img src="<?php echo htmlspecialchars($t->webRoot);?>/themes/<?php echo htmlspecialchars($t->theme);?>/images/icons/action_forward.gif" alt="" /></a>
                <a id="field-up_<?php echo htmlspecialchars($t->oAttr->id);?>" class="field-up" href="#" title="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("move selected field up"));?>">
                    <img src="<?php echo htmlspecialchars($t->webRoot);?>/themes/<?php echo htmlspecialchars($t->theme);?>/images/icons/arrow_up.gif" alt="" /></a>
                <a id="field-down_<?php echo htmlspecialchars($t->oAttr->id);?>" class="field-down" href="#" title="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("move selected field down"));?>">
                    <img src="<?php echo htmlspecialchars($t->webRoot);?>/themes/<?php echo htmlspecialchars($t->theme);?>/images/icons/arrow_down.gif" alt="" /></a>
                <p>
                    <select id="attrib-fields_<?php echo htmlspecialchars($t->oAttr->id);?>" name="list[fields]" multiple="multiple" size="5">
                    <?php if ($this->options['strict'] || (is_array($t->oAttr->fields)  || is_object($t->oAttr->fields))) foreach($t->oAttr->fields as $fieldKey => $fieldValue) {?>
                        <option><?php echo htmlspecialchars($fieldKey);?> =&gt; <?php echo htmlspecialchars($fieldValue);?></option>
                    <?php }?>
                    </select>
                </p>
                <p>
                    <input id="attrib-list-item-field-delete_<?php echo htmlspecialchars($t->oAttr->id);?>" class="button" type="button" name="delete" value="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("delete selected field (button)"));?>" />
                </p>
            </div>
        </fieldset>
    </div>
    <input type="hidden" name="listId" value="<?php echo htmlspecialchars($t->oAttr->id);?>" />
</form>
