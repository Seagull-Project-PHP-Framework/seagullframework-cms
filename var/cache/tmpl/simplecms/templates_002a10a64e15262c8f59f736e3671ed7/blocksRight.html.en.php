            <?php if ($this->options['strict'] || (is_array($t->blocksRight)  || is_object($t->blocksRight))) foreach($t->blocksRight as $key => $valueObj) {?>
                <div class="block <?php echo htmlspecialchars($valueObj->body_class);?>">
                    <h2><?php echo htmlspecialchars($valueObj->title);?></h2>
                    <div class="content">
                        <?php echo $valueObj->content;?>
                    </div>
                </div>
            <?php }?>