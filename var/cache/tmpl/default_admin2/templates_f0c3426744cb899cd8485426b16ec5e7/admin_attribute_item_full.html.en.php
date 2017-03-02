<div id="attrib-list-item_<?php echo htmlspecialchars($t->oAttr->id);?>" class="zebra-item">

    <?php 
$x = new HTML_Template_Flexy($this->options);
$x->compile('admin_attribute_item_header.html');
$_t = function_exists('clone') ? clone($t) : $t;
foreach(get_defined_vars()  as $k=>$v) {
    if ($k != 't') { $_t->$k = $v; }
}
$x->outputObject($_t, $this->elements);
?>
    <?php if (!$t->isAdd)  {?>
    <?php 
$x = new HTML_Template_Flexy($this->options);
$x->compile('admin_attribute_item_view.html');
$_t = function_exists('clone') ? clone($t) : $t;
foreach(get_defined_vars()  as $k=>$v) {
    if ($k != 't') { $_t->$k = $v; }
}
$x->outputObject($_t, $this->elements);
?>
    <?php }?>
    <?php 
$x = new HTML_Template_Flexy($this->options);
$x->compile('admin_attribute_item_form.html');
$_t = function_exists('clone') ? clone($t) : $t;
foreach(get_defined_vars()  as $k=>$v) {
    if ($k != 't') { $_t->$k = $v; }
}
$x->outputObject($_t, $this->elements);
?>

</div>
