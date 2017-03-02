<div id="block-logout">
    <p>
        <strong><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("user"));?>:</strong>
        <em><?php echo htmlspecialchars($t->loggedOnUser);?></em>
    </p>
    <p>
        <strong><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("session started at"));?>:</strong>
        <em><?php echo htmlspecialchars($t->loggedOnSince);?></em>
    </p>
    <p>
        <a href="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("logout","login","user"));?>"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("logout","ucfirst"));?></a>
    </p>
</div>
