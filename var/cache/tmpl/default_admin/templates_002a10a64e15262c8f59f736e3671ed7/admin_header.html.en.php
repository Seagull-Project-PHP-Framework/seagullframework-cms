<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php echo htmlspecialchars($t->currLang);?>" xml:lang="<?php echo htmlspecialchars($t->currLang);?>" dir="<?php echo htmlspecialchars($t->langDir);?>">
<head>
    <title><?php echo htmlspecialchars($t->conf['site']['name']);?> :: <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo $t->translate($t->pageTitle);?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo htmlspecialchars($t->charset);?>" />
    <meta http-equiv="Content-Language" content="<?php echo htmlspecialchars($t->currLang);?>" />
    <meta name="keywords" content="<?php echo htmlspecialchars($t->conf['site']['keywords']);?>" />
    <meta name="description" content="<?php echo htmlspecialchars($t->conf['site']['description']);?>" />
    <meta name="robots" content="ALL" />
    <meta name="copyright" content="Copyright (c) 2008 Seagull Framework, Demian Turner, and the respective authors" />
    <meta name="rating" content="General" />
    <meta name="generator" content="Seagull Framework v<?php echo htmlspecialchars($t->versionAPI);?>" />
    <link rel="help" href="http://trac.seagullproject.org" title="Seagull Documentation." />

    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeCssOptimizerLink'))) echo $t->makeCssOptimizerLink("","core.php,block.php,blockStyle.php,tools.css","vars.php");?>

    <?php if ($t->conf['debug']['production'])  {?>
    <link rel="stylesheet" type="text/css" media="screen" href="<?php echo htmlspecialchars($t->webRoot);?>/themes/default_admin/css/warning.css" />
    <?php }?>

    <?php echo $t->scriptOpen;?>
        // bc
        var SGL_JS_WEBROOT          = "<?php echo htmlspecialchars($t->webRoot);?>";
        var SGL_JS_SESSID           = "<?php echo htmlspecialchars($t->sessID);?>";
        var SGL_JS_CURRURL          = "<?php echo htmlspecialchars($t->currUrl);?>";
        var SGL_JS_THEME            = "<?php echo htmlspecialchars($t->theme);?>";
        var SGL_JS_ADMINGUI         = "1";
        var SGL_JS_URL_STRATEGY     = "<?php echo htmlspecialchars($t->conf['site']['outputUrlHandler']);?>";
        var SGL_JS_FRONT_CONTROLLER = "<?php echo htmlspecialchars($t->conf['site']['frontScriptName']);?>";
        var SGL_JS_DATETEMPLATE     = "<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'getDateFormat'))) echo htmlspecialchars($t->getDateFormat());?>";

        <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'getExportedJsVars'))) echo $t->getExportedJsVars();?>
    <?php echo $t->scriptClose;?>

    <script type="text/javascript" src="<?php echo htmlspecialchars($t->webRoot);?>/themes/<?php echo htmlspecialchars($t->theme);?>/js/mainAdmin.js"></script>
    <script type="text/javascript" src="<?php echo htmlspecialchars($t->webRoot);?>/themes/<?php echo htmlspecialchars($t->theme);?>/js/mainPublic.js"></script>
    <script type="text/javascript" src="<?php echo htmlspecialchars($t->webRoot);?>/themes/<?php echo htmlspecialchars($t->theme);?>/js/hack-IE-hover.js"></script>

    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeJsOptimizerLink'))) echo $t->makeJsOptimizerLink();?>

    <?php echo $t->scriptOpen;?>

    // onload event
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

    <?php if ($t->wysiwyg)  {?>
    <?php if ($t->wysiwyg_fck)  {?>
    <script type="text/javascript" src="<?php echo htmlspecialchars($t->webRoot);?>/wysiwyg/fckeditor/fckeditor.js"></script>
    <script type="text/javascript">
        var oFCKEditors = new Array;

        /* initalises an instance of FCK and returns the object. */
        function fck_add(id)
        {
            i = oFCKEditors.length;
            oFCKEditors[i] = new FCKeditor(id, '100%', 300);
            oFCKEditors[i].ToolbarSet = 'Basic' ;
            oFCKEditors[i].BasePath = SGL_JS_WEBROOT + "/wysiwyg/fckeditor/";
            oFCKEditors[i].Config["CustomConfigurationsPath"] = SGL_JS_WEBROOT + "/js/SglFckconfig.js"  ;
            oFCKEditors[i].ReplaceTextarea();
        }
        function fck_init()
        {
            if( document.getElementsByTagName ) {
                areas = document.getElementsByTagName('textarea');

                for( var i=0; i<areas.length; i++ ){
                    if( areas[i].className.match("wysiwyg") ) {
                        fck_add(areas[i].id);
                    }
                    else if( areas[i].id.match('frmBodyName') ) {
                        /* fallback for old templates */
                        fck_add('frmBodyName');
                    }
               }
            }
        }
    </script>
    <?php }?>
    <?php }?>
</head>
<body>
