<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo htmlspecialchars($t->currLang);?>" lang="<?php echo htmlspecialchars($t->currLang);?>" dir="<?php echo htmlspecialchars($t->langDir);?>">
<head>
    <title><?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo htmlspecialchars($this->plugin("getContentTitle",$t->oContent));?></title>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="Content-Language" content="<?php echo htmlspecialchars($t->currLang);?>" />

    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeCssOptimizerLink'))) echo $t->makeCssOptimizerLink("","main.css");?>
</head>
<body>
    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'outputBody'))) echo $t->outputBody();?>
</body>
</html>