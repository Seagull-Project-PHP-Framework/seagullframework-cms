
        <li class="attribute" id="attrib_<?php echo htmlspecialchars($t->oAttrib->id);?>">
            <div id="attrib_<?php echo htmlspecialchars($t->oAttrib->id);?>_view">
                <span id="attrib_<?php echo htmlspecialchars($t->oAttrib->id);?>_viewInfo"><?php echo htmlspecialchars($t->oAttrib->alias);?>&nbsp;(<?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo htmlspecialchars($this->plugin("attrTypeConstantToString",$t->oAttrib->typeId));?>)</span>
                <span class="attributeActions">
                    <a href="#" class="action editLink" onclick="cms.contentType.attributeEditor.edit(<?php echo htmlspecialchars($t->oAttrib->id);?>);return false;">edit</a>
                    <a href="#" class="action deleteLink" onclick="cms.contentType.deleteAttribute(<?php echo htmlspecialchars($t->oAttrib->id);?>);return false;">
                        <img src="<?php echo htmlspecialchars($t->webRoot);?>/cms/images/trash.gif" alt="delete" />
                    </a>
                </span>
            </div>
            <div id="attrib_<?php echo htmlspecialchars($t->oAttrib->id);?>_edit" style="display:none">

                <!-- ATTRIBUTE EDIT FORM -->
                <form class="attributeEditForm" id="attrib_<?php echo htmlspecialchars($t->oAttrib->id);?>_editForm">
                    <?php 
if (!isset($this->elements['attributeId']->attributes['value'])) {
    $this->elements['attributeId']->attributes['value'] = '';
    $this->elements['attributeId']->attributes['value'] .=  htmlspecialchars($t->oAttrib->id);
}
$_attributes_used = array('value');
echo $this->elements['attributeId']->toHtml();
if (isset($_attributes_used)) {  foreach($_attributes_used as $_a) {
    unset($this->elements['attributeId']->attributes[$_a]);
}}
?>
                    <ul>
                        <li id="attrib_<?php echo htmlspecialchars($t->oAttrib->id);?>_errorMsg" style="display:none"></li>
                        <li>
                            <label>Alias</label>
                            <input type="text" name="contentType[attributes][<?php echo htmlspecialchars($t->oAttrib->id);?>][fieldAlias]" id="attrib_<?php echo htmlspecialchars($t->oAttrib->id);?>_alias" value="<?php echo htmlspecialchars($t->oAttrib->alias);?>" onblur="cms.contentType.camelizeAttributeName(attrib_<?php echo htmlspecialchars($t->oAttrib->id);?>_name, this.value)" />
                        </li>
                        <li>
                            <label>Name</label>
                            <input type="text" name="contentType[attributes][<?php echo htmlspecialchars($t->oAttrib->id);?>][fieldName]" id="attrib_<?php echo htmlspecialchars($t->oAttrib->id);?>_name" value="<?php echo htmlspecialchars($t->oAttrib->name);?>" />
                        </li>
                        <li>
                            <label>Type</label>
                            <select name="contentType[attributes][<?php echo htmlspecialchars($t->oAttrib->id);?>][fieldType]" class="attrTypeSelect" id="attrib_<?php echo htmlspecialchars($t->oAttrib->id);?>_type">
                                    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateSelect'))) echo $t->generateSelect($t->attrTypes,$t->oAttrib->typeId);?>
                            </select>
                        </li>
                        <!-- PARAMETERS -->
                        <li class="attributeParams" id="attrib_<?php echo htmlspecialchars($t->oAttrib->id);?>_params" style="display:none">
                            <span>Parameters</span>
                            <div>
                                <!-- LIST TYPE -->
                                <div class="attributeField attrListType" id="attrib_<?php echo htmlspecialchars($t->oAttrib->id);?>_typeList">
                                    <label>Select a list</label>
                                    <select name="contentType[attributes][<?php echo htmlspecialchars($t->oAttrib->id);?>][fieldParams][attributeListId]">
                                        <option value="">Choose ..</option>
                                        <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateSelect'))) echo $t->generateSelect($t->aListTypes,$t->oAttrib->params['attributeListId']);?>
                                    </select>
                                </div>
                                <!-- VISIBILITY -->
                                <!--<div style="clear:both">
                                    <label>Attribute is visible</label>
                                    <input type="radio"
                                           name="contentType[attributes][{oAttrib.id}][fieldParams][isVisible]"
                                           value="1" {this.plugin(#checkRadio#,oAttrib.params.isVisible,#1#)} /> yes
                                    <input type="radio"
                                           name="contentType[attributes][{oAttrib.id}][fieldParams][isVisible]"
                                           value="0" {this.plugin(#checkRadio#,oAttrib.params.isVisible,#0#)} /> no
                                </div>-->
                            </div>
                        </li>
                    </ul>
                    <p class="submit">
                        <input type="submit" value="Save this attribute" />&nbsp;
                        <span>or</span>&nbsp;
                        <a href="#" class="action" onclick="cms.contentType.attributeEditor.cancel(<?php echo htmlspecialchars($t->oAttrib->id);?>);return false;">Cancel</a>
                    </p>
                </form>
    
            </div>
        </li>
