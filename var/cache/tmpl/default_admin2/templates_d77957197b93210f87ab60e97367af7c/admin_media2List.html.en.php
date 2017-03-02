<div id="media-container" class="columns layout-2cols">

    <div class="title-edit-simple">
        <h1><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("media list"));?></h1>
        <span>
            <a class="upload" href="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("upload","media2","media2"));?>"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("upload media"));?></a>                        
        </span>
    </div>
    
    <div class="c75l list">
        <div class="layout-inner">
        
            <?php if ($t->aMedias)  {?><div id="media-list" class="clearfix">
                <?php if ($this->options['strict'] || (is_array($t->aMedias)  || is_object($t->aMedias))) foreach($t->aMedias as $k => $oMedia) {?>
                <?php 
$x = new HTML_Template_Flexy($this->options);
$x->compile('admin_media2_item.html');
$_t = function_exists('clone') ? clone($t) : $t;
foreach(get_defined_vars()  as $k=>$v) {
    if ($k != 't') { $_t->$k = $v; }
}
$x->outputObject($_t, $this->elements);
?>
                <?php }?>
            </div><?php }?>
            
            <?php if ($t->pagerLinks)  {?><p class="pager"><?php echo $t->pagerLinks;?></p><?php }?>
            
            <?php if (!$t->aMedias)  {?><p>
                <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("no media found"));?>
            </p><?php }?>
                
        </div><!-- inner -->
    </div><!-- list -->
    
    <div class="c25r">
    
        <?php 
$x = new HTML_Template_Flexy($this->options);
$x->compile('block_media_filter.html');
$_t = function_exists('clone') ? clone($t) : $t;
foreach(get_defined_vars()  as $k=>$v) {
    if ($k != 't') { $_t->$k = $v; }
}
$x->outputObject($_t, $this->elements);
?>
       
    </div>    

</div>
