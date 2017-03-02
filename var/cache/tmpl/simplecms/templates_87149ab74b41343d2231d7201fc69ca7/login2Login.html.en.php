<form id="loginUser" action="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("login","user2","user2"));?>" method="post">

    <!-- Intro text -->
    <p><strong><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("auth required"));?></strong></p>

    <fieldset>
        <h2><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("login (header)"));?></h2>
        <ol class="clearfix">
            <li>
                <label for="user_username">
                    <em>*</em> <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("username"));?>
                </label>
                <div>
                    <?php if ($t->error['username'])  {?><p class="error">
                        <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr($t->error['username']));?>
                    </p><?php }?>
                    <input id="user_username" class="text" type="text" name="user[username]" value="<?php echo htmlspecialchars($t->username);?>" />
                </div>
            </li>
            <li>
                <label for="user_password">
                    <em>*</em> <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("password"));?>
                </label>
                <div>
                    <?php if ($t->error['password'])  {?><p class="error">
                        <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr($t->error['password']));?>
                    </p><?php }?>
                    <input id="user_password" class="text" type="password" name="user[password]" value="<?php echo htmlspecialchars($t->password);?>" />
                </div>
            </li>
            <?php if ($t->conf['cookie']['rememberMeEnabled'])  {?>
            <li>
                <input type="hidden" name="user[rememberme]" value="0" />
                <label for="user_rememberme"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("remember me"));?></label>
                <div>
                    <input id="user_rememberme" type="checkbox" name="user[rememberme]" value="1" checked="checked" />
                </div>
            </li>
            <?php }?>
        </ol>
    </fieldset>
    <fieldset class="hide">
        <input type="hidden" name="redir" value="<?php echo htmlspecialchars($t->redir);?>" />
    </fieldset>    

    <div class="fieldIndent">
        <p>
            <input class="button" type="submit" name="submitted" value="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("login (button)"));?>" />
            <img class="ajaxLoader" src="<?php echo htmlspecialchars($t->imagesDir);?>/ajax-loader.gif" alt="" style="display: none;" />
        </p>
        <p>
            <a href="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("register","login2","user2"));?>"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("not registered"));?></a>
            <br />
            <a href="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("","passwordrecovery","user2"));?>"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("forgot password"));?></a>
        </p>
    </div>
    <p class="required"><em>*</em> <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("denotes required field"));?></p>

</form><!-- END loginUser -->
