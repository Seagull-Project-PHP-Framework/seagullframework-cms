<div id="media-wrapper">

    <form id="media-upload" method="post" class="columns layout-2cols" action="">
        <?php 
$x = new HTML_Template_Flexy($this->options);
$x->compile('admin_media2_uploadBlock.html');
$_t = function_exists('clone') ? clone($t) : $t;
foreach(get_defined_vars()  as $k=>$v) {
    if ($k != 't') { $_t->$k = $v; }
}
$x->outputObject($_t, $this->elements);
?>
        <div class="c25r"></div>
    </form>
    
</div>
