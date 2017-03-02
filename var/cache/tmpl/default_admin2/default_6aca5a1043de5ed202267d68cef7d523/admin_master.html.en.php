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

        <!-- begin: main content area #main -->
        <div id="main">
        
            <!-- begin: #col3 static column -->
            <div id="col3">
                <div id="col3_content" class="clearfix">
                    
                    <!-- skiplink anchor: Content -->
                    <a id="content" name="content"></a>
                    
                    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'outputBody'))) echo $t->outputBody();?>               
                </div>

                <div id="ie_clearing">&nbsp;</div>
                <!-- End: IE Column Clearing -->
            </div>
            <!-- end: #col3 -->

        </div>
        <!-- end: #main -->

<?php 
$x = new HTML_Template_Flexy($this->options);
$x->compile('footer.html');
$_t = function_exists('clone') ? clone($t) : $t;
foreach(get_defined_vars()  as $k=>$v) {
    if ($k != 't') { $_t->$k = $v; }
}
$x->outputObject($_t, $this->elements);
?>