<h1><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate($t->pageTitle));?></h1>
<div class="message"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'msgGet'))) echo $t->msgGet();?></div>

<form id="frmLogin" method="post" action="">
    <fieldset class="hide">
        <input type="hidden" name="action" value="login" />
        <input type="hidden" name="redir" value="<?php echo htmlspecialchars($t->redir);?>" />
    </fieldset>
    <fieldset class="lastChild">
        <legend><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Authorisation Required"));?></legend>
        <ol class="clearfix">
            <li>
                <label for="frm_username">
                    <em>*</em> <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Username"));?>
                </label>
                <div>
                    <?php if ($t->error['username'])  {?><p class="error">
                        <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate($t->error['username']));?>
                    </p><?php }?>
                    <input id="frm_username" class="text" type="text" name="frmUsername" value="<?php echo htmlspecialchars($t->username);?>" />
                </div>
            </li>
            <li>
                <label for="frm_password">
                    <em>*</em> <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Password"));?>
                </label>
                <div>
                    <?php if ($t->error['password'])  {?><p class="error">
                        <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate($t->error['password']));?>
                    </p><?php }?>
                    <input id="frm_password" class="text" type="password" name="frmPassword" value="<?php echo htmlspecialchars($t->password);?>" />
                </div>
            </li>
            <?php if ($t->conf['cookie']['rememberMeEnabled'])  {?>
            <li>
                <label for="frm_extendedLifetime"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Remember me"));?></label>
                <div>
                    <input id="frm_extendedLifetime" type="checkbox" name="frmExtendedLifetime" value="1" />
                </div>
            </li>
            <?php }?>
        </ol>
    </fieldset>
    <div class="fieldIndent">
        <p>
            <input class="submit" type="submit" name="submitted" value="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Login"));?>" />
        </p>
        <?php if ($t->conf['RegisterMgr']['enabled'])  {?><p>
            <a href="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("","register","user"));?>"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Not Registered"));?></a>
        </p><?php }?>
        <p>
            <a href="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("","password","user"));?>"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Forgot Password"));?></a>
        </p>
    </div>
    <p><em>*</em> <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("denotes required field"));?></p>
</form>
