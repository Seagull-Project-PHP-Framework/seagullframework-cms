<?php if ($t->isAdd)  {?>
<table id="attrib-list-item-view_<?php echo htmlspecialchars($t->oAttr->id);?>" class="full" style="display: none;">
<?php } else {?>
<table id="attrib-list-item-view_<?php echo htmlspecialchars($t->oAttr->id);?>" class="full">
<?php }?>
    <tbody>
    <?php if ($this->options['strict'] || (is_array($t->oAttr->fields)  || is_object($t->oAttr->fields))) foreach($t->oAttr->fields as $fieldKey => $fieldVal) {?>
        <tr <?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo $this->plugin("getContentRowStyle");?>>
            <td class="cell-40"><?php echo htmlspecialchars($fieldKey);?></td>
            <td class="cell-10 cell-center">=&gt;</td>
            <td class="cell-40"><?php echo htmlspecialchars($fieldVal);?></td>
            <td class="cell-10 cell-right">
                <!--
                <a href="#" class="deleteField" title="{tr(#delete attribute list field#)}"
                   sgl:list_id="{oAttr.id}" sgl:field_id="{fieldKey:u}">
                    <img src="{webRoot}/themes/{theme}/images/icons/action_stop.gif" alt="" /></a>
                <img class="ajaxLoader" src="{webRoot}/themes/{theme}/images/ajax-loader.gif"
                     alt="" style="display: none" />
                -->
                &nbsp;
            </td>
        </tr>
    <?php }?>
    <?php if (!$t->oAttr->fields)  {?>
        <tr>
            <td><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("no fields specified"));?></td>
        </tr>
    <?php }?>
    </tbody>
</table>
