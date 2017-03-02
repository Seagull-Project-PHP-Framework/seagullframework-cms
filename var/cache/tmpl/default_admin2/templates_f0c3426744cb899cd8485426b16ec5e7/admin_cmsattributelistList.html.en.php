<div id="attrib-list-container" class="columns layout-2cols">

    <div class="title-edit-simple">
        <h1><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("attribute lists (header)"));?></h1>
        <span>
            <a id="attrib-list-add" href="#"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("add attribute list (action)"));?></a>
            <img class="ajaxLoader" src="<?php echo htmlspecialchars($t->webRoot);?>/themes/<?php echo htmlspecialchars($t->theme);?>/images/ajax-loader.gif" alt="" style="display: none;" />
        </span>
    </div>

    <div class="c75l list">
        <div class="layout-inner">

            <p class="help">
                <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("attribute list help"));?>
            </p>

            <div id="attrib-list" class="zebra-container">
            <?php if ($this->options['strict'] || (is_array($t->aAttrLists)  || is_object($t->aAttrLists))) foreach($t->aAttrLists as $oAttr) {?>
                <?php 
$x = new HTML_Template_Flexy($this->options);
$x->compile('admin_attribute_item_full.html');
$_t = function_exists('clone') ? clone($t) : $t;
foreach(get_defined_vars()  as $k=>$v) {
    if ($k != 't') { $_t->$k = $v; }
}
$x->outputObject($_t, $this->elements);
?>
            <?php }?>
            </div>

            <?php if (!$t->aAttrLists)  {?><p class="notfound">
                <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("no attribute lists found"));?>
            </p><?php }?>

        </div><!-- inner -->
    </div><!-- list -->

    <div class="c25r">
    </div>

</div>
