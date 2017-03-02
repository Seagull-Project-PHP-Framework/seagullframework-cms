        <div id="header">
            <div class="wrap-left">
                <div class="wrap-right">

                    <!-- Logo -->
                    <h1>
                        <a href="<?php echo htmlspecialchars($t->webRoot);?>/<?php echo htmlspecialchars($t->conf['site']['frontScriptName']);?>" title="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Home"));?>">
                            <span><?php echo htmlspecialchars($t->conf['site']['name']);?></span></a>
                    </h1>

                    <!-- Bug reporter -->
                    <?php if ($t->conf['debug']['showBugReporterLink'])  {?><a id="bugReporter" class="replace" href="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("","bug","default"));?>" title="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("send bug report"));?>">
                        <span><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("send bug report"));?></span></a><?php }?>

                    <div id="message" style="display: none;"><p><!-- place holder --></p></div>

                </div>
            </div>

            <hr />
        </div><!-- end header -->
