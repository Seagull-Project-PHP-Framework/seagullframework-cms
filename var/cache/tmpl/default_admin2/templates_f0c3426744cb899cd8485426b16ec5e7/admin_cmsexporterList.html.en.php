<div id="export-container" class="columns layout-2cols">

    <div class="title-edit-simple">
        <h1><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("cms data export (header)"));?></h1>
    </div>

    <div class="c75l list">
        <div class="layout-inner">

            <fieldset id="export-data">
                <p>
                    <textarea id="export-data-text" rows="15" cols="50"><?php echo htmlspecialchars($t->sglExport);?></textarea>
                </p>
                <p>
                    <input id="export-data-trigger" class="button" type="button" name="test" value="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("select result (button)"));?>" />
                </p>
            </fieldset>

        </div><!-- inner -->
    </div><!-- list -->

    <div class="c25r">

        <?php 
$x = new HTML_Template_Flexy($this->options);
$x->compile('block_export_config.html');
$_t = function_exists('clone') ? clone($t) : $t;
foreach(get_defined_vars()  as $k=>$v) {
    if ($k != 't') { $_t->$k = $v; }
}
$x->outputObject($_t, $this->elements);
?>

    </div>

</div>
