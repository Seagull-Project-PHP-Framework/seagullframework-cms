<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo htmlspecialchars($t->currLang);?>" lang="<?php echo htmlspecialchars($t->currLang);?>" dir="<?php echo htmlspecialchars($t->langDir);?>">
<head>
    <title><?php echo htmlspecialchars($t->conf['site']['name']);?></title>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="Content-Language" content="<?php echo htmlspecialchars($t->currLang);?>" />
    <meta name="rating" content="General" />
    <meta name="generator" content="Seagull PHP Framework" />
    <meta name="copyright" content="Copyright (c) 2009 <?php echo htmlspecialchars($t->conf['site']['name']);?>" />

    <?php echo $t->scriptOpen;?>
        <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'getExportedJsVars'))) echo $t->getExportedJsVars();?>
    <?php echo $t->scriptClose;?>

    <?php if ($t->myComment)  {?> ---------------------------------------------------- <?php }?>
    <?php if ($t->myComment)  {?> --- Application wide + manager specific JS + CSS --- <?php }?>
    <?php if ($t->myComment)  {?> ---------------------------------------------------- <?php }?>

    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeCssOptimizerLink'))) echo $t->makeCssOptimizerLink("","main.css");?>
    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeJsOptimizerLink'))) echo $t->makeJsOptimizerLink();?>

    <?php if ($t->myComment)  {?> --------------------------- <?php }?>
    <?php if ($t->myComment)  {?> --- Onload events setup --- <?php }?>
    <?php if ($t->myComment)  {?> --------------------------- <?php }?>

    <?php echo $t->scriptOpen;?>
    <?php if ($t->onReadyDom)  {?>
        $(document).ready(function() {
            <?php if ($this->options['strict'] || (is_array($t->onReadyDom)  || is_object($t->onReadyDom))) foreach($t->onReadyDom as $eventHandler) {?>
            <?php echo htmlspecialchars($eventHandler);?>;
            <?php }?>
        });
    <?php }?>
    <?php if ($t->onLoad)  {?>
        window.onload = function() {
            <?php if ($this->options['strict'] || (is_array($t->onLoad)  || is_object($t->onLoad))) foreach($t->onLoad as $eventHandler) {?>
            <?php echo htmlspecialchars($eventHandler);?>;
            <?php }?>
        }
    <?php }?>
    <?php echo $t->scriptClose;?>

    <?php if ($t->myComment)  {?> --------------------- <?php }?>
    <?php if ($t->myComment)  {?> --- WYSIWYG setup --- <?php }?>
    <?php if ($t->myComment)  {?> --------------------- <?php }?>

    <?php if ($t->wysiwyg)  {?>
    <?php if ($t->wysiwyg_fck)  {?>
    <script type="text/javascript" src="<?php echo htmlspecialchars($t->webRoot);?>/fckeditor/fckeditor.js"></script>
    <?php echo $t->scriptOpen;?>
        oFCKEditors = new Array;

        // initalises an instance of FCK
        function fck_add(id) {
            var i = oFCKEditors.length;

            // width/height and toolbar must be customized
            oFCKEditors[i] = new FCKeditor(id, 460, 300);
            oFCKEditors[i].ToolbarSet = '<?php echo htmlspecialchars($t->wysiwygToolbarType);?>';

            oFCKEditors[i].BasePath = SGL_WEBROOT + '/fckeditor/';
            oFCKEditors[i].Config['CustomConfigurationsPath'] = SGL_WEBROOT + '/admin/js/FckConfig.js';
            oFCKEditors[i].ReplaceTextarea();
        }

        function fck_init() {
            if (document.getElementsByTagName) {
                var aAreas = document.getElementsByTagName('textarea');
                for (var i = 0; i < aAreas.length; i++) {
                    if (aAreas[i].className.match('wysiwyg')) {
                        fck_add(aAreas[i].id);
                    } else if (aAreas[i].id.match('frmBodyName')) {
                        // fallback for old templates
                        fck_add('frmBodyName');
                    }
                }
            }
        }
    <?php echo $t->scriptClose;?>
    <?php }?>
    <?php }?>

    <?php if ($t->myComment)  {?> --------------------------------------- <?php }?>
    <?php if ($t->myComment)  {?> --- Application wide JS translation --- <?php }?>
    <?php if ($t->myComment)  {?> --------------------------------------- <?php }?>

    <script type="text/javascript" src="<?php echo htmlspecialchars($t->webRoot);?>/admin/js/Localisation/<?php echo htmlspecialchars($t->currFullLang);?>.js?rev=<?php echo htmlspecialchars($t->rev);?>"></script>

    <?php if ($t->myComment)  {?> ------------------------------------- <?php }?>
    <?php if ($t->myComment)  {?> --- Application wide IE 6/7 fixes --- <?php }?>
    <?php if ($t->myComment)  {?> ------------------------------------- <?php }?>

    <?php echo $t->commentOpen;?>[if lte IE 7]>
    <link rel="stylesheet" type="text/css" href="<?php echo htmlspecialchars($t->webRoot);?>/themes/<?php echo htmlspecialchars($t->theme);?>/css/patches/main.css?rev=<?php echo htmlspecialchars($t->rev);?>" />
    <![endif]<?php echo $t->commentClose;?>

    <script type="text/javascript">
    $(document).ready(function() {
        $('#langSwitcher select').change(function() {
            document.location.href = $(this).val();
        });
    });
    </script>
</head>