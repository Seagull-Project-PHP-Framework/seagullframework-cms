<?php 
$x = new HTML_Template_Flexy($this->options);
$x->compile('admin_header.html');
$_t = function_exists('clone') ? clone($t) : $t;
foreach(get_defined_vars()  as $k=>$v) {
    if ($k != 't') { $_t->$k = $v; }
}
$x->outputObject($_t, $this->elements);
?>
<?php 
$x = new HTML_Template_Flexy($this->options);
$x->compile('admin_banner.html');
$_t = function_exists('clone') ? clone($t) : $t;
foreach(get_defined_vars()  as $k=>$v) {
    if ($k != 't') { $_t->$k = $v; }
}
$x->outputObject($_t, $this->elements);
?>
<?php 
$x = new HTML_Template_Flexy($this->options);
$x->compile('admin_leftBlock.html');
$_t = function_exists('clone') ? clone($t) : $t;
foreach(get_defined_vars()  as $k=>$v) {
    if ($k != 't') { $_t->$k = $v; }
}
$x->outputObject($_t, $this->elements);
?>
            <div id="middle-column">
                <div id="main">
                    <div id="manager-infos">
                        <h1><?php echo htmlspecialchars(ucfirst($t->moduleName));?> / <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate($t->pageTitle));?></h1>
                    </div>
                    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'outputBody'))) echo htmlspecialchars($t->outputBody());?>
                </div> <!-- end of #main -->
            </div> <!-- end of #middle-column -->
<?php 
$x = new HTML_Template_Flexy($this->options);
$x->compile('admin_footer.html');
$_t = function_exists('clone') ? clone($t) : $t;
foreach(get_defined_vars()  as $k=>$v) {
    if ($k != 't') { $_t->$k = $v; }
}
$x->outputObject($_t, $this->elements);
?>
