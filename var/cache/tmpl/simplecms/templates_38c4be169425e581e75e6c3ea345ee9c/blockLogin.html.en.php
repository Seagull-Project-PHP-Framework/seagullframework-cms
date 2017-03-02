<form id="block-login" method="post" action="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("login","login","user"));?>">
    <fieldset class="lastChild noLegend">
        <ol class="onTop">
            <li>
                <label for="frm_username_block">
                    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Username"));?>
                </label>
                <input id="frm_username_block" class="text" type="text" name="frmUsername" value="<?php echo htmlspecialchars($t->username);?>" />
            </li>
            <li>
                <label for="frm_password_block"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Password"));?></label>
                <input id="frm_password_block" class="text" type="password" name="frmPassword" value="<?php echo htmlspecialchars($t->password);?>" />
            </li>
        </ol>
    </fieldset>
    <p>
        <input class="submit" type="submit" name="submitted" value="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("login"));?>" />
    </p>
    <?php if ($t->conf['RegisterMgr']['enabled'])  {?><p>
        <a href="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("","register","user"));?>"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Not Registered"));?></a>
    </p><?php }?>
    <p>
        <a href="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("","password","user"));?>"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Forgot Password"));?></a>
    </p>
</form>
