            <?php if ($this->options['strict'] || (is_array($t->blocksLeft)  || is_object($t->blocksLeft))) foreach($t->blocksLeft as $key => $valueObj) {?>
                <div class="block <?php echo htmlspecialchars($valueObj->body_class);?>">
                    <h2><?php echo htmlspecialchars($valueObj->title);?></h2>
                    <div class="content">
                        <?php echo $valueObj->content;?>
                    </div>
                </div>
            <?php }?>