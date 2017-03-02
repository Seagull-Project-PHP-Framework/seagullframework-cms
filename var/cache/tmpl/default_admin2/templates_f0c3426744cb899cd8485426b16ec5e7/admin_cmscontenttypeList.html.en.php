<div id="content-type-container" class="columns layout-2cols">

    <div class="title-edit-simple">
        <h1><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("content types (header)"));?></h1>
        <span>
            <a id="content-type-add" href="#"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("add content type (action)"));?></a>
            <img class="ajaxLoader" src="<?php echo htmlspecialchars($t->webRoot);?>/themes/<?php echo htmlspecialchars($t->theme);?>/images/ajax-loader.gif" alt="" style="display: none;" />
        </span>
    </div>

    <div class="c75l list">
        <div class="layout-inner">

            <p class="help">
                <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("content type list help"));?>
            </p>

            <div id="content-type-list" class="zebra-container">
            <?php if ($this->options['strict'] || (is_array($t->aContentTypes)  || is_object($t->aContentTypes))) foreach($t->aContentTypes as $contentTypeId => $contentTypeName) {?>

                <div id="content-type-item_<?php echo htmlspecialchars($contentTypeId);?>" class="zebra-item">
                    <?php 
$x = new HTML_Template_Flexy($this->options);
$x->compile('admin_contenttype_item_header.html');
$_t = function_exists('clone') ? clone($t) : $t;
foreach(get_defined_vars()  as $k=>$v) {
    if ($k != 't') { $_t->$k = $v; }
}
$x->outputObject($_t, $this->elements);
?>
                </div>

            <?php }?>
            </div>

            <?php if (!$t->aContentTypes)  {?><p class="notfound">
                <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("no content types found"));?>
            </p><?php }?>

        </div><!-- inner -->
    </div><!-- list -->

    <div class="c25r">

        <?php 
$x = new HTML_Template_Flexy($this->options);
$x->compile('block_contenttype_stats.html');
$_t = function_exists('clone') ? clone($t) : $t;
foreach(get_defined_vars()  as $k=>$v) {
    if ($k != 't') { $_t->$k = $v; }
}
$x->outputObject($_t, $this->elements);
?>

    </div>

</div>
