<form id="category-container" class="columns layout-2cols" method="post" action="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("updateCategory","simplecategory","simplecategory"));?>">

    <div class="title-edit">
        <h1><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("category manager (header)"));?></h1>
        <span>
            <a id="category-add" href="#" class="add"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("add root category (action)"));?></a>
            <img class="ajaxLoader" src="<?php echo htmlspecialchars($t->imagesDir);?>/ajax-loader.gif" alt="" style="display: none;" />
        </span>
    </div>

    <div class="c25l item">

        <div id="category-nav">
            <p class="clearfix">
                <select id="category-nav-lang" name="lang">
                    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateSelect'))) echo $t->generateSelect($t->aLangs,$t->currLang);?>
                </select>
                <img class="ajaxLoader" src="<?php echo htmlspecialchars($t->imagesDir);?>/ajax-loader.gif" alt="" style="display: none;" />
            </p>
            <?php 
$x = new HTML_Template_Flexy($this->options);
$x->compile('simplecategory_tree.html');
$_t = function_exists('clone') ? clone($t) : $t;
foreach(get_defined_vars()  as $k=>$v) {
    if ($k != 't') { $_t->$k = $v; }
}
$x->outputObject($_t, $this->elements);
?>
        </div><!-- category tree -->

    </div><!-- tree -->

    <div class="c75r item">
        <div id="category-edit" class="layout-inner">

            <p><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("select category from nav for edit"));?></p>

        </div>
    </div><!-- category's data -->

</form><!-- category container -->
