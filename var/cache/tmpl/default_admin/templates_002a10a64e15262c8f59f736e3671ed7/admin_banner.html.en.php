<!--  Main wrapper holding the page -->
<div id="outer-wrapper">
    <!-- Logo and header -->
    <div id="header">
        <div id="left">
            <h1><a href="<?php echo htmlspecialchars($t->webRoot);?>/<?php echo htmlspecialchars($t->conf['site']['frontScriptName']);?>" title="Back to the frontend home page"><span><?php echo htmlspecialchars($t->conf['site']['name']);?></span></a></h1>
        </div>
        <div id="right">
            <span class="info">
                <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("currently_logged_on_as"));?> : <a href="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("view","profile","user","","frmUserID|loggedOnUserID"));?>" title="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("user profile"));?>"><?php echo htmlspecialchars($t->loggedOnUser);?></a>
            </span>
            <span class="info">
                <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("session started at"));?> : <?php echo htmlspecialchars($t->loggedOnSince);?>
            </span>
            <span class="info">
                <a href="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("logout","login","user"));?>" title="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("logout"));?>"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("logout"));?></a>
            </span>
            <?php if ($t->conf['debug']['showBugReporterLink'])  {?><span class="info">
                <a id="bug-report" href="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("","bug","default"));?>" title="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("send bug report"));?>"><img src="<?php echo htmlspecialchars($t->webRoot);?>/themes/<?php echo htmlspecialchars($t->theme);?>/images/bug.gif" alt="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("send bug report"));?>" /></a>
            </span><?php }?>
        </div>
    </div> <!-- end of #header -->

    <!-- Wrapper for rest of page -->
    <div id="inner-wrapper">
        <div id="container">
