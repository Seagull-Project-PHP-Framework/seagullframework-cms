<table id="content-type-item-view_<?php echo htmlspecialchars($t->contentTypeId);?>" class="full">
    <tbody>
    <?php if ($this->options['strict'] || (is_array($t->aAttribs)  || is_object($t->aAttribs))) foreach($t->aAttribs as $oAttr) {?>
        <tr <?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo $this->plugin("getContentRowStyle");?>>
            <td class="cell-10"><em><?php echo htmlspecialchars($oAttr->attr_id);?></em></td>
            <td class="cell-40"><?php echo htmlspecialchars($oAttr->attr_alias);?></td>
            <td class="cell-40">
                <?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo htmlspecialchars($this->plugin("getArrayValueByKey",$t->aAttribTypes,$oAttr->attr_type_id));?>
                <?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) if ($this->plugin("attribTypeIsList",$oAttr->attr_type_id)) { ?>
                    <?php if ($oAttr->attr_params['attributeListId'])  {?>
                <em>(<?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo htmlspecialchars($this->plugin("getArrayValueByKey",$t->aLists,$oAttr->attr_params['attributeListId']));?>)</em>
                    <?php } else {?>
                <em>(<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("list is not defined"));?>)</em>
                    <?php }?>
                <?php }?>
            </td>
        </tr>
    <?php }?>
    </tbody>
</table>
