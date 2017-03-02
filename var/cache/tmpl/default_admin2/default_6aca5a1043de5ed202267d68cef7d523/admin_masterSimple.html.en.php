<?php 
$x = new HTML_Template_Flexy($this->options);
$x->compile('header_head.html');
$_t = function_exists('clone') ? clone($t) : $t;
foreach(get_defined_vars()  as $k=>$v) {
    if ($k != 't') { $_t->$k = $v; }
}
$x->outputObject($_t, $this->elements);
?>
<body class="simple">

    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'outputBody'))) echo $t->outputBody();?>  

</body>
</html>