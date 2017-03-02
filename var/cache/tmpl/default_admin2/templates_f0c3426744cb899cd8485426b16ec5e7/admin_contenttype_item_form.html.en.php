<form id="content-type-item-form_<?php echo htmlspecialchars($t->contentTypeId);?>" class="editmode" method="post" action="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("updateContentType","simplecms","simplecms"));?>">

    <fieldset class="header">
        <ol class="clearfix">
            <li>
                <label><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("content type name"));?></label>
                <div>
                    <input class="text" type="text" name="name" value="<?php echo htmlspecialchars($t->contentTypeName);?>" />
                </div>
            </li>
        </ol>
        <span class="actions">
            <a class="addfield" href="#"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("add content type attribute (action)"));?></a>
            <img class="ajaxLoader" src="<?php echo htmlspecialchars($t->webRoot);?>/themes/<?php echo htmlspecialchars($t->theme);?>/images/ajax-loader.gif" alt="" style="display: none;" />
        </span>
    </fieldset>

    <table class="full">
        <thead>
            <tr>
                <td>
                    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("content type attribute alias"));?>
                </td>
                <td>
                    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("content type attribute name"));?>
                </td>
                <td>
                    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("content type attribute type"));?>
                </td>
                <td>
                    &nbsp;
                </td>
            </tr>
        </thead>
        <tbody>
        <?php if ($this->options['strict'] || (is_array($t->aAttribs)  || is_object($t->aAttribs))) foreach($t->aAttribs as $k => $oAttr) {?>
            <?php 
$x = new HTML_Template_Flexy($this->options);
$x->compile('admin_contenttype_item_form_attr.html');
$_t = function_exists('clone') ? clone($t) : $t;
foreach(get_defined_vars()  as $k=>$v) {
    if ($k != 't') { $_t->$k = $v; }
}
$x->outputObject($_t, $this->elements);
?>
        <?php }?>
        </tbody>
    </table>

    <p class="submit">
        <span id="content-type-item-actions_<?php echo htmlspecialchars($t->contentTypeId);?>" class="actions">
            <input class="button" type="submit" name="submitted" value="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("save content type (button)"));?>" />
            &nbsp;<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("or"));?>&nbsp;
            <a id="content-type-item-cancel_<?php echo htmlspecialchars($t->contentTypeId);?>" href="#"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("cancel"));?></a>
        </span>
        <img class="ajaxLoader" src="<?php echo htmlspecialchars($t->webRoot);?>/themes/<?php echo htmlspecialchars($t->theme);?>/images/ajax-loader.gif" alt="" style="display: none;" />
    </p>

    <input type="hidden" name="id" value="<?php echo htmlspecialchars($t->contentTypeId);?>" />
    <?php if ($t->conf['CmsContentTypeMgr']['updateContentsOnChange'])  {?><input type="hidden" name="updateMessage" value="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("contents %total% will be updated together with content type","vprintf",$t->aStats));?>" /><?php }?>
    <?php if (!$t->conf['CmsContentTypeMgr']['updateContentsOnChange'])  {?><input type="hidden" name="updateMessage" value="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("contents %total% will not be updated together with content type","vprintf",$t->aStats));?>" /><?php }?>
</form>
