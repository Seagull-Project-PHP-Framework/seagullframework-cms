<div id="content-header">
	<h2><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate($t->pageTitle));?></h2>
	<div id="ajaxMessage" class="message"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'msgGet'))) echo $t->msgGet();?></div>
</div>

<form id="frmUser" method="post" action="">
    <fieldset class="hide">
        <input id="usernameWrongFormatMsg" type="hidden" name="usernameWrongFormatMsg" value="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("username min length"));?>" />
        <input id="ajaxProviderIsUniqueUsernameUrl" type="hidden" name="usernameWrongFormatMsg" value="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("isUniqueUsername","","user"));?>" />
    <?php if ($t->isAdd)  {?>
        <input type="hidden" name="action" value="insert" />
        <?php if ($t->redir)  {?>
        <input type="hidden" name="redir" value="<?php echo htmlspecialchars($t->redir);?>" />
        <?php }?>
    <?php } else {?>
        <input type="hidden" name="action" value="update" />
        <input type="hidden" name="user[usr_id]" value="<?php echo htmlspecialchars($t->user->usr_id);?>" />
        <input type="hidden" name="user[role_id_orig]" value="<?php echo htmlspecialchars($t->user->role_id);?>" />
        <input type="hidden" name="user[organisation_id_orig]" value="<?php echo htmlspecialchars($t->user->organisation_id);?>" />
        <input type="hidden" name="user[username_orig]" value="<?php echo htmlspecialchars($t->user->username_orig);?>" />
        <input type="hidden" name="user[email_orig]" value="<?php echo htmlspecialchars($t->user->email_orig);?>" />
    <?php }?>
    </fieldset>

    <fieldset>
        <legend><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Personal Details"));?></legend>
        <ol class="clearfix">
            <li>
                <label for="user_username">
                    <em>*</em> <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Username"));?>
                </label>
                <div>
                    <?php if ($t->error['username'])  {?><p class="error">
                        <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate($t->error['username']));?>
                    </p><?php }?>
                    <input id="user_username" class="text" type="text" name="user[username]" value="<?php echo htmlspecialchars($t->user->username);?>" />
                    &nbsp;
                    <input type="button" name="availabilityCheck" value="Check Availability" onclick="UserRegister.isUniqueUsername('user_username')" />
                </div>
            </li>
            <li>
                <label for="user_first-name"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("First Name"));?></label>
                <div>
                    <input id="user_first-name" class="text" type="text" name="user[first_name]" value="<?php echo htmlspecialchars($t->user->first_name);?>" />
                </div>
            </li>
            <li>
                <label for="user_last-name"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Last Name"));?></label>
                <div>
                    <input id="user_last-name" class="text" type="text" name="user[last_name]" value="<?php echo htmlspecialchars($t->user->last_name);?>" />
                </div>
            </li>
            <?php if ($t->isAdd)  {?>
            <li>
                <label for="user_passwd">
                    <em>*</em> <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Password"));?>
                </label>
                <div>
                    <?php if ($t->error['passwd'])  {?><p class="error">
                        <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate($t->error['passwd']));?>
                    </p><?php }?>
                    <input id="user_passwd" class="text" type="password" name="user[passwd]" value="<?php echo htmlspecialchars($t->user->passwd);?>" />
                </div>
            </li>
            <li>
                <label for="user_password-confirm">
                    <em>*</em> <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Confirm Password"));?>
                </label>
                <div>
                    <?php if ($t->error['password_confirm'])  {?><p class="error">
                        <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate($t->error['password_confirm']));?>
                    </p><?php }?>
                    <input id="user_password-confirm" class="text" type="password" name="user[password_confirm]" value="<?php echo htmlspecialchars($t->user->password_confirm);?>" />
                </div>
            </li>
            <?php }?>
        </ol>
    </fieldset>

    <fieldset class="hr">
        <legend><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Contact"));?></legend>
        <ol class="clearfix">
            <li>
                <label for="user_email"><em>*</em> <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Email"));?></label>
                <div>
                    <?php if ($t->error['email'])  {?><p class="error">
                        <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate($t->error['email']));?>
                    </p><?php }?>
                    <input id="user_email" class="text" type="text" name="user[email]" value="<?php echo htmlspecialchars($t->user->email);?>" />
                </div>
            </li>
            <li>
                <label for="user_telephone"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Telephone"));?></label>
                <div>
                    <input id="user_telephone" class="text" type="text" name="user[telephone]" value="<?php echo htmlspecialchars($t->user->telephone);?>" />
                </div>
            </li>
            <li>
                <label for="user_mobile"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Mobile"));?></label>
                <div>
                    <input id="user_mobile" class="text" type="text" name="user[mobile]" value="<?php echo htmlspecialchars($t->user->mobile);?>" />
                </div>
            </li>
        </ol>
    </fieldset>

    <fieldset class="hr">
        <legend><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Location"));?></legend>
        <ol class="clearfix">
            <li>
                <label for="user_addr-1">
                    <em>*</em> <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Address 1"));?>
                </label>
                <div>
                    <?php if ($t->error['addr_1'])  {?><p class="error">
                        <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate($t->error['addr_1']));?>
                    </p><?php }?>
                    <input id="user_addr-1" class="text" type="text" name="user[addr_1]" value="<?php echo htmlspecialchars($t->user->addr_1);?>" />
                </div>
            </li>
            <li>
                <label for="user_addr-2"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Address 2"));?></label>
                <div>
                    <input id="user_addr-2" class="text" type="text" name="user[addr_2]" value="<?php echo htmlspecialchars($t->user->addr_2);?>" />
                </div>
            </li>
            <li>
                <label for="user_addr-3"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Address 3"));?></label>
                <div>
                    <input id="user_addr-3" class="text" type="text" name="user[addr_3]" value="<?php echo htmlspecialchars($t->user->addr_3);?>" />
                </div>
            </li>
            <li>
                <label for="user_city"><em>*</em> <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("City"));?></label>
                <div>
                    <?php if ($t->error['city'])  {?><p class="error">
                        <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate($t->error['city']));?>
                    </p><?php }?>
                    <input id="user_city" class="text" type="text" name="user[city]" value="<?php echo htmlspecialchars($t->user->city);?>" />
                </div>
            </li>
            <li>
                <label for="user_region">
                    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("County/State/Province"));?>
                </label>
                <div>
                    <select id="user_region" name="user[region]">
                        <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateSelect'))) echo $t->generateSelect($t->states,$t->user->region);?>
                    </select>
                </div>
            </li>
            <li>
                <label for="user_post-code">
                    <em>*</em> <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("ZIP/Postal Code"));?>
                </label>
                <div>
                    <?php if ($t->error['post_code'])  {?><p class="error">
                        <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate($t->error['post_code']));?>
                    </p><?php }?>
                    <input id="user_post-code" class="text" type="text" name="user[post_code]" value="<?php echo htmlspecialchars($t->user->post_code);?>" />
                </div>
            </li>
            <li>
                <label for="user_country">
                    <em>*</em> <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Country"));?>
                </label>
                <div>
                    <?php if ($t->error['country'])  {?><p class="error">
                        <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate($t->error['country']));?>
                    </p><?php }?>
                    <select id="user_country" name="user[country]">
                        <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateSelect'))) echo $t->generateSelect($t->countries,$t->user->country);?>
                    </select>
                </div>
            </li>
        </ol>
    </fieldset>

    <fieldset class="lastChild hr">
        <legend><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Security"));?></legend>
        <ol class="clearfix">
            <li>
                <label for="user_security-question">
                    <em>*</em> <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Security question"));?>
                </label>
                <div>
                    <?php if ($t->error['security_question'])  {?><p class="error">
                        <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate($t->error['security_question']));?>
                    </p><?php }?>
                    <select id="user_security-question" name="user[security_question]">
                        <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateSelect'))) echo $t->generateSelect($t->aSecurityQuestions,$t->user->security_question);?>
                    </select>
                </div>
            </li>
            <li>
                <label for="user_security-answer">
                    <em>*</em> <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Answer"));?>
                </label>
                <div>
                    <?php if ($t->error['security_answer'])  {?><p class="error">
                        <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate($t->error['security_answer']));?>
                    </p><?php }?>
                    <input id="user_security-answer" class="text" type="text" name="user[security_answer]" value="<?php echo htmlspecialchars($t->user->security_answer);?>" />
                </div>
            </li>
			<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'isAdmin'))) if ($t->isAdmin()) { ?> 
			<li> 
				<label for="user_role-id"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Role"));?></label> 
				<div>
					<select id="user_role-id" name="user[role_id]">
						<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateSelect'))) echo $t->generateSelect($t->aRoles,$t->user->role_id);?>
					</select>
				</div>
			</li>
			<li> 
				<label for="user_is-acct-active"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Is Active?"));?></label>
				<div>
					<input id="user_is-acct-active" type="checkbox" name="user[is_acct_active]" value="1" <?php echo htmlspecialchars($t->isAcctActive);?> /> <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("check if active"));?>
				</div>
			</li>
			<?php }?>
        </ol>
    </fieldset>

    <div class="fieldIndent">
    <?php if ($t->isAdd)  {?>
        <input class="submit" type="submit" name="submitted" value="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Register"));?>" />
    <?php } else {?>
        <input class="submit" type="submit" name="submitted" value="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Save"));?>" />
        <input class="button" type="button" name="cancelled" value="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Cancel"));?>" onclick="document.location.href='<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("summary","account","user"));?>'" />
    <?php }?>
    </div>
    <p><em>*</em> <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("denotes required field"));?></p>
</form>

<script type="text/javascript">
/**
 * @todo factor out
 */
UserRegister = {

    // predefined containers
    messageContainer: 'ajaxMessage',
    messageWrongUserFormat: $F('usernameWrongFormatMsg'),
    urlIsUniqueUsername: $F('ajaxProviderIsUniqueUsernameUrl'),

    isUniqueUsername: function(username) {
        if (!UserValidator.isValidUsername($F(username))) {
            this._showMessage('error', this.messageWrongUserFormat);
        } else {
            new Ajax.Request(this.urlIsUniqueUsername, {
                    method: 'post',
                    parameters: {username: $F(username)},
                    onSuccess: this._showResults
                });
        }
        return false;
    },

    _showMessage: function(messageType, messageText) {
        var innerDiv = document.createElement('div');
        $(innerDiv).toggleClassName(messageType + 'Message').update(messageText);
        $(this.messageContainer).show().update('').appendChild(innerDiv)
        // Opacity effect is used, because we don't want screen to jump
        new Effect.Opacity(this.messageContainer, {
                duration: 3,
                from: 1.0,
                to: 0
            });
    },

    _showResults: function(transport) {
        var result = eval('(' + transport.responseText + ')');
        UserRegister._showMessage(result.type, result.message);
    }
}

UserValidator = {
    isValidUsername: function(value) {
        if (value == '') {
            return false;
        }
        return value.match('^[a-zA-Z0-9]{5,}$');
    }
}
</script>
