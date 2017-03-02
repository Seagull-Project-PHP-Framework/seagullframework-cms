<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo htmlspecialchars($t->currLang);?>" lang="<?php echo htmlspecialchars($t->currLang);?>" dir="<?php echo htmlspecialchars($t->langDir);?>">
<head>
    <title><?php echo htmlspecialchars($t->conf['site']['name']);?> :: <?php echo htmlspecialchars($t->currentSectionName);?></title>

    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo htmlspecialchars($t->charset);?>" />
    <meta http-equiv="Content-Language" content="<?php echo htmlspecialchars($t->currLang);?>" />
    <meta name="keywords" content="<?php echo htmlspecialchars($t->conf['site']['keywords']);?>" />
    <meta name="description" content="<?php echo htmlspecialchars($t->conf['site']['description']);?>" />
    <meta name="rating" content="General" />
    <meta name="generator" content="Seagull PHP Framework" />
    <?php if (!$t->conf['debug']['production'])  {?><meta name="robots" content="noindex,nofollow" /><?php }?>
    <?php if ($t->conf['debug']['production'])  {?><meta name="robots" content="index,follow" /><?php }?>
    <?php if (!$t->conf['debug']['production'])  {?><meta name="googlebot" content="noindex,nofollow" /><?php }?>
    <?php if ($t->conf['debug']['production'])  {?><meta name="googlebot" content="index,follow" /><?php }?>
    <meta name="copyright" content="Copyright (c) 2008 <?php echo htmlspecialchars($t->conf['site']['name']);?>" />

    <link rel="shortcut icon" type="images/x-icon" href="<?php echo htmlspecialchars($t->webRoot);?>/themes/<?php echo htmlspecialchars($t->theme);?>/images/favicon.ico" />
    <link rel="help" href="http://trac.seagullproject.org" title="Seagull Documentation" />

    <?php echo $t->scriptOpen;?>
        var SGL_JS_WEBROOT          = "<?php echo htmlspecialchars($t->webRoot);?>";
        var SGL_JS_WINHEIGHT        = <?php echo htmlspecialchars($t->conf['popup']['winHeight']);?>;
        var SGL_JS_WINWIDTH         = <?php echo htmlspecialchars($t->conf['popup']['winWidth']);?>;
        var SGL_JS_SESSID           = "<?php echo htmlspecialchars($t->sessID);?>";
        var SGL_JS_CURRURL          = "<?php echo htmlspecialchars($t->currUrl);?>";
        var SGL_JS_THEME            = "<?php echo htmlspecialchars($t->theme);?>";
        var SGL_JS_ADMINGUI         = "0";
        var SGL_JS_DATETEMPLATE     = "<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'getDateFormat'))) echo htmlspecialchars($t->getDateFormat());?>";
        var SGL_JS_URL_STRATEGY     = "<?php echo htmlspecialchars($t->conf['site']['outputUrlHandler']);?>";
        var SGL_JS_FRONT_CONTROLLER = "<?php echo htmlspecialchars($t->conf['site']['frontScriptName']);?>";
        <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'getExportedJsVars'))) echo $t->getExportedJsVars();?>
    <?php echo $t->scriptClose;?>

    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeCssOptimizerLink'))) echo $t->makeCssOptimizerLink();?>
    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeJsOptimizerLink'))) echo $t->makeJsOptimizerLink();?>

    <?php echo $t->scriptOpen;?>

    <?php if ($t->onReadyDom)  {?>
        if (typeof SGL2 != 'undefined') {
            $(document).ready(function() {
                <?php if ($this->options['strict'] || (is_array($t->onReadyDom)  || is_object($t->onReadyDom))) foreach($t->onReadyDom as $eventHandler) {?>
                <?php echo htmlspecialchars($eventHandler);?>;
                <?php }?>
            });
        } else if (typeof SGL != 'undefined') {
            <?php if ($this->options['strict'] || (is_array($t->onReadyDom)  || is_object($t->onReadyDom))) foreach($t->onReadyDom as $eventHandler) {?>
            SGL.ready("<?php echo htmlspecialchars($eventHandler);?>");
            <?php }?>
        }
    <?php }?>

    <?php if ($t->onLoad)  {?>
    window.onload = function() {
        <?php if ($this->options['strict'] || (is_array($t->onLoad)  || is_object($t->onLoad))) foreach($t->onLoad as $eventHandler) {?>
        <?php echo htmlspecialchars($eventHandler);?>;
        <?php }?>
    }
    <?php }?>
    <?php if ($t->onUnload)  {?>
    window.onunload = function() {
        <?php if ($this->options['strict'] || (is_array($t->onUnload)  || is_object($t->onUnload))) foreach($t->onUnload as $eventHandler) {?>
        <?php echo htmlspecialchars($eventHandler);?>;
        <?php }?>
    }
    <?php }?>
    <?php echo $t->scriptClose;?>
</head>

<body id="page-home">

    <div id="wrapper-outer">
