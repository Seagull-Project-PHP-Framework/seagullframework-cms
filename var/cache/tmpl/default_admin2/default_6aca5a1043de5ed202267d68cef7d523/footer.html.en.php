
        <!-- Footer -->
        <div id="footer">
        
            <div class="subcolumns">
                <p class="copyright c50l">
                    <a href="<?php echo htmlspecialchars($t->webRoot);?>"><?php echo htmlspecialchars($t->conf['site']['name']);?></a>
                    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'isAdmin'))) if ($t->isAdmin()) { ?>v<?php echo htmlspecialchars($t->versionAPI);?><?php }?> - &copy;
                    2003-<script type="text/javascript">(function() { document.write((new Date()).getFullYear()); })();</script>
                
                    <br /><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'getFooterPoweredByString'))) echo $t->getFooterPoweredByString();?>
                </p>
                <p class="nav c50r">
                    <a href="http://beta.dorisapp.com"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("todo management"));?></a>
                </p>
            </div>
            
        </div><!-- Footer -->

    </div><!-- page -->
</div><!-- page margins -->

</body>
</html>