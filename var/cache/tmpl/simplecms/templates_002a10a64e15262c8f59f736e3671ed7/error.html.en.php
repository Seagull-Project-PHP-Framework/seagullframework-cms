<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'isEqual'))) if ($t->isEqual($t->aError['level'],-404)) { ?>

<!-- Deal with "page not found" error types -->
<h1>Page not found</h1>

<!-- Production message -->
<?php if ($t->conf['debug']['production'])  {?><p>
    We are sorry, the page you requested could not be found.
</p><?php }?>

<?php } else {?>

<h1>Oops!</h1>

<!-- Production message -->
<?php if ($t->conf['debug']['production'])  {?><p>
    It seems an error has occured, the page you are trying to reach is not
    accessible. We'll do our best to fix this soon.
</p><?php }?>

<?php }?>

<!-- Debug message -->
<?php if (!$t->conf['debug']['production'])  {?>
<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'msgGet'))) echo htmlspecialchars($t->msgGet());?>
<?php }?>