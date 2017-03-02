<script type="text/javascript">
$(document).ready(function(){
    $('.contentTypeEditForm').ajaxForm({
        url :         makeUrl({module: 'cms', action: 'updateName'}),
        type:         'post',
        dataType :    'json',
        beforeSubmit: cms.contentType.editor.validate,
        success:      cms.contentType.editor.save
    });
    $('#newContentTypeForm').ajaxForm({
        url :         makeUrl({module: 'cms', action: 'addContentType'}),
        dataType :    'json',
        beforeSubmit: cms.contentType.creator.validate,
        success:      cms.contentType.creator.save
    });
});
</script>
<div id="manager-actions">
    <span><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Action"));?>: &nbsp;</span>
    <a class="action add" href="#" onclick="cms.contentType.creator.toggle();return false;"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("new content type","ucfirst"));?></a>
</div>
<div id="content">
    <div id="content-header">
        <h2><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Content Type Manager"));?></h2>
        <div class="message" id="ajaxMessage"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'msgGet'))) echo htmlspecialchars($t->msgGet());?></div>
    </div>

    <div id="screenIntro">
        <p class="help">
            <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("content type intro"));?>
        </p>
    </div>

    <div class="fieldsetlike">
        <div class="cmsBox clearfix" id="newAttribListBox" style="display:none">
            <?php 
$x = new HTML_Template_Flexy($this->options);
$x->compile('contentType_newAttribListForm.html');
$_t = function_exists('clone') ? clone($t) : $t;
foreach(get_defined_vars()  as $k=>$v) {
    if ($k != 't') { $_t->$k = $v; }
}
$x->outputObject($_t, $this->elements);
?>
        </div>
    </div>

    <div class="clearfix" id="newContentType">
            <?php 
$x = new HTML_Template_Flexy($this->options);
$x->compile('contentType_newContentTypeForm.html');
$_t = function_exists('clone') ? clone($t) : $t;
foreach(get_defined_vars()  as $k=>$v) {
    if ($k != 't') { $_t->$k = $v; }
}
$x->outputObject($_t, $this->elements);
?>
    </div>

    <?php if ($t->aContentTypes)  {?> <!-- SHOW CONTENT TYPEs -->
        <div id="contentTypeList">
        <?php if ($this->options['strict'] || (is_array($t->aContentTypes)  || is_object($t->aContentTypes))) foreach($t->aContentTypes as $id => $oContentType) {?>
            <?php 
$x = new HTML_Template_Flexy($this->options);
$x->compile('contentType_contentType.html');
$_t = function_exists('clone') ? clone($t) : $t;
foreach(get_defined_vars()  as $k=>$v) {
    if ($k != 't') { $_t->$k = $v; }
}
$x->outputObject($_t, $this->elements);
?>
        <?php }?>
        </div>
        <?php } else {?> <!-- NO CONTENT TYPES -->
            <p><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("No content types were found"));?></p>
        <?php }?>
	<div class="clear"></div>
</div>