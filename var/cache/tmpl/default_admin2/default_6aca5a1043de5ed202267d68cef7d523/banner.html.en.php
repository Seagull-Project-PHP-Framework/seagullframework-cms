<!-- Header -->
<div id="header">

    <!-- Site name -->
    <a id="logo" href="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("","admin","admin"));?>"><?php echo htmlspecialchars($t->conf['site']['name']);?></a>

    <p id="greeting">
        <a href="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'getHomePageLink'))) echo $t->getHomePageLink();?>"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("go to home page"));?></a>
        &nbsp;&nbsp;|&nbsp;&nbsp;
        <a href="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("logout","login2","user2"));?>"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("logout"));?></a>
    </p>

    <!-- begin: main navigation #nav -->
    <div id="nav">
        <!-- skiplink anchor: navigation -->
        <a id="navigation" name="navigation"></a>

        <div id="nav_main">
        <?php if ($this->options['strict'] || (is_array($t->blocksAdminNavPri)  || is_object($t->blocksAdminNavPri))) foreach($t->blocksAdminNavPri as $key => $valueObj) {?>
            <?php echo $valueObj->content;?>
        <?php }?>
        </div>

        <div id="nav_sub">
            <div class="inner">
            <?php if ($this->options['strict'] || (is_array($t->blocksAdminNavSec)  || is_object($t->blocksAdminNavSec))) foreach($t->blocksAdminNavSec as $key => $valueObj) {?>
                <?php echo $valueObj->content;?>
            <?php }?>
            </div>
        </div>

        <div id="help" class="floatbox" style="display: none;">
            <div class="inner"></div>
            <a class="trigger" href="#"><span><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("help panel"));?></span></a>
        </div>
    </div>
    <!-- end: main navigation -->

    <div id="langSwitcher">
        <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'getLoggedOnUserString'))) echo $t->getLoggedOnUserString($t->loggedOnUser);?>
        &nbsp;
        <select name="lang">
            <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateLanguageSelect'))) echo $t->generateLanguageSelect($t->currLang);?>
        </select>
    </div>

    <div id="message" style="display: none;"><p>message placeholder</p></div>
    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'msgGet'))) echo $t->msgGet();?>

</div>
<!-- END: header -->
