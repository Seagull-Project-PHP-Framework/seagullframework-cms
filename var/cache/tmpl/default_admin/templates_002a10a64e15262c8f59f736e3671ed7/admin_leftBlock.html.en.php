            <div id="left-column">
                <?php if ($this->options['strict'] || (is_array($t->blocksAdminNav)  || is_object($t->blocksAdminNav))) foreach($t->blocksAdminNav as $key => $valueObj) {?>
                <div class="block container">
                    <div class="header">
                        <?php echo $valueObj->title;?>
                    </div>
                    <div class="content" id="nav">
                        <?php echo $valueObj->content;?>
                    </div>
                    <div class="footer">
                    </div>
                </div>
                <?php }?>
            </div> <!-- end of #left-column -->
