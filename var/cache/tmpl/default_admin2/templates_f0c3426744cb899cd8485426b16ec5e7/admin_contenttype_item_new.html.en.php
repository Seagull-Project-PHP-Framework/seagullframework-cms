<div id="content-type-item_<?php echo htmlspecialchars($t->contentTypeId);?>" class="zebra-item">
    <?php 
$x = new HTML_Template_Flexy($this->options);
$x->compile('admin_contenttype_item_header.html');
$_t = function_exists('clone') ? clone($t) : $t;
foreach(get_defined_vars()  as $k=>$v) {
    if ($k != 't') { $_t->$k = $v; }
}
$x->outputObject($_t, $this->elements);
?>
    <?php echo $t->form;?>
</div>
