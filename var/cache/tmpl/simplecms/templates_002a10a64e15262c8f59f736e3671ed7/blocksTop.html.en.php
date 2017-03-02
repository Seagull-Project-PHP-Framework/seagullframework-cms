        <div id="nav-main">

            <!-- top navigation -->
            <div class="inner clearfix">
                <h2 class="accessibility">Main navigation</h2>
                <?php if ($this->options['strict'] || (is_array($t->blocksMainNav)  || is_object($t->blocksMainNav))) foreach($t->blocksMainNav as $key => $valueObj) {?>
                   <?php echo $valueObj->content;?>
                <?php }?>
            </div>
            <!-- end top navigation -->

            <div id="toolbar" class="clearfix">
                <p id="breadcrumbs">
                <?php if ($this->options['strict'] || (is_array($t->blocksMainBreadcrumb)  || is_object($t->blocksMainBreadcrumb))) foreach($t->blocksMainBreadcrumb as $key => $valueObj) {?>
                    <?php echo $valueObj->content;?>
                <?php }?>
                </p>

                <!-- Lang switcher -->
                <h2 class="accessibility">Language switcher</h2>
                <div id="langSwitcher">
                <?php if ($this->options['strict'] || (is_array($t->blocksBodyTop)  || is_object($t->blocksBodyTop))) foreach($t->blocksBodyTop as $key => $valueObj) {?>
                    <?php echo $valueObj->content;?>
                <?php }?>
                </div>
            </div>

            <hr />
        </div>
