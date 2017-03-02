<?php 
$x = new HTML_Template_Flexy($this->options);
$x->compile('header.html');
$_t = function_exists('clone') ? clone($t) : $t;
foreach(get_defined_vars()  as $k=>$v) {
    if ($k != 't') { $_t->$k = $v; }
}
$x->outputObject($_t, $this->elements);
?>
<?php 
$x = new HTML_Template_Flexy($this->options);
$x->compile('banner.html');
$_t = function_exists('clone') ? clone($t) : $t;
foreach(get_defined_vars()  as $k=>$v) {
    if ($k != 't') { $_t->$k = $v; }
}
$x->outputObject($_t, $this->elements);
?>

        <div id="wrapper-inner">
            <div id="content" class="clearfix">

                <div id="main">
                    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'outputBody'))) echo htmlspecialchars($t->outputBody());?>

                    <hr />
                </div><!-- end main -->

                <div id="sub">
                    <div class="inner">
                        <?php 
$x = new HTML_Template_Flexy($this->options);
$x->compile('blocksRight.html');
$_t = function_exists('clone') ? clone($t) : $t;
foreach(get_defined_vars()  as $k=>$v) {
    if ($k != 't') { $_t->$k = $v; }
}
$x->outputObject($_t, $this->elements);
?>
                    </div>
                </div><!-- end sub -->

                <div id="local">
                    <?php 
$x = new HTML_Template_Flexy($this->options);
$x->compile('blocksLeft.html');
$_t = function_exists('clone') ? clone($t) : $t;
foreach(get_defined_vars()  as $k=>$v) {
    if ($k != 't') { $_t->$k = $v; }
}
$x->outputObject($_t, $this->elements);
?>

                    <hr />
                </div><!-- end local -->

                <?php 
$x = new HTML_Template_Flexy($this->options);
$x->compile('blocksTop.html');
$_t = function_exists('clone') ? clone($t) : $t;
foreach(get_defined_vars()  as $k=>$v) {
    if ($k != 't') { $_t->$k = $v; }
}
$x->outputObject($_t, $this->elements);
?>

            </div><!-- end content -->
        </div><!-- end wrapper-inner -->

<?php 
$x = new HTML_Template_Flexy($this->options);
$x->compile('footer.html');
$_t = function_exists('clone') ? clone($t) : $t;
foreach(get_defined_vars()  as $k=>$v) {
    if ($k != 't') { $_t->$k = $v; }
}
$x->outputObject($_t, $this->elements);
?>