<!--
  --  REMINDER : {id} is a shortcut for {oContentType.id} --
-->

    <!-- ATTRIBUTES -->
    <ol id="contentType_<?php echo htmlspecialchars($t->id);?>_attributes">
        <?php if ($this->options['strict'] || (is_array($t->oContentType->aAttribs)  || is_object($t->oContentType->aAttribs))) foreach($t->oContentType->aAttribs as $oAttrib) {?>
            <?php 
$x = new HTML_Template_Flexy($this->options);
$x->compile('contentType_attribute.html');
$_t = function_exists('clone') ? clone($t) : $t;
foreach(get_defined_vars()  as $k=>$v) {
    if ($k != 't') { $_t->$k = $v; }
}
$x->outputObject($_t, $this->elements);
?>
        <?php }?>

        <!-- ADD ATTRIBUTE FORM -->
        <li class="newAttribute" id="contentType_<?php echo htmlspecialchars($t->id);?>_newAttribute">
            <form class="attributeEditForm" id="contentType_<?php echo htmlspecialchars($t->id);?>_newAttributeForm" style="display:none">
                <input type="hidden" name="contentTypeId" value="<?php echo htmlspecialchars($t->id);?>" />
                <ul>
                    <li id="contentType_<?php echo htmlspecialchars($t->id);?>_newAttribute_errorMsg" style="display:none"></li>
                    <li>
                        <label>Alias</label>
                        <input type="text" value="" name="newAttribute[fieldAlias]" id="contentType_<?php echo htmlspecialchars($t->id);?>_newAttribute_alias" onblur="cms.contentType.camelizeAttributeName(contentType_<?php echo htmlspecialchars($t->id);?>_newAttribute_name, this.value)" />
                    </li>
                    <li>
                        <label>Name</label>
                        <input type="text" name="newAttribute[fieldName]" id="contentType_<?php echo htmlspecialchars($t->id);?>_newAttribute_name" />
                    </li>
                    <li>
                        <label>Type</label>
                        <select name="newAttribute[fieldType]" class="attrTypeSelect" id="contentType_<?php echo htmlspecialchars($t->id);?>_newAttribute_type">
                                <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateSelect'))) echo $t->generateSelect($t->attrTypes);?>
                        </select>
                    </li>
                    <!-- PARAMETERS -->
                    <li class="attributeParams" id="contentType_<?php echo htmlspecialchars($t->id);?>_newAttribute_params" style="display:none">
                        <span>Parameters</span>
                        <div>
                            <!-- LIST TYPE -->
                            <div class="attributeField attrListType" id="contentType_<?php echo htmlspecialchars($t->id);?>_newAttribute_typeList">
                                <label>Select a list</label>
                                <select name="newAttribute[fieldParams][attributeListId]">
                                    <option value="">Choose ..</option>
                                    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateSelect'))) echo $t->generateSelect($t->aListTypes);?>
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
                    <input type="submit" value="Add this attribute" />&nbsp;
                    <span>or</span>&nbsp;
                    <a href="#" class="action" onclick="cms.contentType.attributeCreator.cancel(<?php echo htmlspecialchars($t->id);?>);return false;">I'm done</a>
                </p>
            </form>
            <a href="#" class="action" id="contentType_<?php echo htmlspecialchars($t->id);?>_newAttributeLink" onclick="cms.contentType.attributeCreator.create(<?php echo htmlspecialchars($t->id);?>);return false;">add an attribute</a>
        </li>
    </ol>