        <div id="footer">
            <div class="wrap-left">
                <div class="wrap-right">
                    <p>
                        <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Powered by"));?>
                        <a href="http://seagullproject.org/" title="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Seagull PHP Framework"));?>">
                            <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Seagull PHP Framework"));?></a>
                        <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'isAdmin'))) if ($t->isAdmin()) { ?>v<?php echo htmlspecialchars($t->versionAPI);?><?php }?> - &copy;
                        <a href="http://seagullsystems.com/" title="Seagull Systems">Seagull Systems</a>
                        2003-2007
                    </p>
                    <?php if ($t->showExecutionTimes)  {?><p>
                        <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Execution Time"));?> = <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'getExecutionTime'))) echo htmlspecialchars($t->getExecutionTime());?> <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("ms"));?>
                        <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'getQueryCount'))) echo htmlspecialchars($t->getQueryCount());?> <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("queries"));?>
                    </p><?php }?>
                    <?php if ($t->conf['debug']['profiling'])  {?><p>
                        <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'getMemoryUsage'))) echo htmlspecialchars($t->getMemoryUsage());?> kb <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("allocated"));?>
                    </p><?php }?>
                </div>
            </div>
        </div><!-- end footer -->

    </div><!-- end wrapper-outer -->

    <?php if ($t->conf['debug']['infoBlock'])  {?>
    <?php 
$x = new HTML_Template_Flexy($this->options);
$x->compile('debug.html');
$_t = function_exists('clone') ? clone($t) : $t;
foreach(get_defined_vars()  as $k=>$v) {
    if ($k != 't') { $_t->$k = $v; }
}
$x->outputObject($_t, $this->elements);
?>
    <?php }?>

    <?php if ($t->conf['site']['broadcastMessage'])  {?><div id="broadcastMessage">
        <?php echo htmlspecialchars($t->conf['site']['broadcastMessage']);?>
        <a href="#" title="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("close"));?>">X</a>
    </div><?php }?>

</body>
</html>