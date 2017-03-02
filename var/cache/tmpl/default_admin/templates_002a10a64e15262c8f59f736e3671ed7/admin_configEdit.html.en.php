<script type="text/javascript">
function toggleTransElements() {
    document.getElementById('transContainer').disabled = true;
}
</script>

<div id="manager-actions">
    <span><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Action"));?>: &nbsp;</span>
    <a class="action save" href="javascript:formSubmit('configuration','submitted',1,1)"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Save"));?></a>
    <a class="action undo" href="javascript:formReset('configuration')"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Reset"));?></a>
    <a class="action cancel" href="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("","config","default"));?>"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Cancel"));?></a>
</div>
<div id="content">
    <div id="content-header">
        <h2><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate($t->pageTitle));?></h2>
        <div class="message"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'msgGet'))) echo htmlspecialchars($t->msgGet());?></div>
    </div>

    <form name="configuration" id="configuration" method="post">
        <p>
            <input type="hidden" name="action" value="update" />
        </p>

        <div id="optionsLinks"></div>

        <fieldset class="main options" id="generalSiteOptions">
            <h3><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("General"));?></h3>
            <p>
                <label for="conf[site][baseUrl]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Base URL"));?></label>
                <?php if ($t->error['baseUrl'])  {?><span class="error"><?php echo htmlspecialchars($t->error['baseUrl']);?></span><?php }?>
                <input type="text" class="longText" name="conf[site][baseUrl]" id="conf[site][baseUrl]" value="<?php echo htmlspecialchars($t->conf['site']['baseUrl']);?>" />
            </p>
            <p>
                <label for="conf[site][name]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Site name"));?></label>
                <input type="text" class="longText" name="conf[site][name]" id="conf[site][name]" value="<?php echo htmlspecialchars($t->conf['site']['name']);?>" />
            </p>
            <p>
                <label class="tipOwner" for="conf[site][showLogo]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Show logo"));?>
                <span class="tipText"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("If path to image is specified, image will be shown; if left blank, Site name from above will appear as text"));?></span>
                </label>
                <?php if ($t->error['showLogo'])  {?><span class="error"><?php echo htmlspecialchars($t->error['showLogo']);?></span><?php }?>
                <input type="text" class="longText" name="conf[site][showLogo]" id="conf[site][showLogo]" value="<?php echo htmlspecialchars($t->conf['site']['showLogo']);?>" />
            </p>
            <p>
                <label for="conf[site][description]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Description"));?></label>
                <input type="text" class="longText" name="conf[site][description]" id="conf[site][description]" value="<?php echo htmlspecialchars($t->conf['site']['description']);?>" />
            </p>
            <p>
                <label for="conf[site][keywords]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Keywords"));?></label>
                <input type="text" class="longText" name="conf[site][keywords]" id="conf[site][keywords]" value="<?php echo htmlspecialchars($t->conf['site']['keywords']);?>" />
            </p>
            <p>
                <label for="conf[site][compression]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Gzip compression"));?></label>
                <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateRadioPair'))) echo $t->generateRadioPair("conf[site][compression]",$t->conf['site']['compression']);?>
            </p>
            <p>
                <label for="conf[site][outputBuffering]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Output buffering"));?></label>
                <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateRadioPair'))) echo $t->generateRadioPair("conf[site][outputBuffering]",$t->conf['site']['outputBuffering']);?>
            </p>
            <p>
                <label class="tipOwner" for="conf[site][banIpEnabled]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Enable IP banning"));?>
                <span class="tipText"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Handy if you dont have access to Apache configuration"));?></span>
                </label>
                <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateRadioPair'))) echo $t->generateRadioPair("conf[site][banIpEnabled]",$t->conf['site']['banIpEnabled']);?>
            </p>
            <p>
                <label for="conf[site][denyList]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Deny list"));?></label>
                <input type="text" class="longText" name="conf[site][denyList]" id="conf[site][denyList]" value="<?php echo htmlspecialchars($t->conf['site']['denyList']);?>" />
            </p>
            <p>
                <label for="conf[site][allowList]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Allow list"));?></label>
                <input type="text" class="longText" name="conf[site][allowList]" id="conf[site][allowList]" value="<?php echo htmlspecialchars($t->conf['site']['allowList']);?>" />
            </p>
            <p>
                <label class="tipOwner" for="conf[site][safeDelete]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Enable Safe deleting"));?>
                <span class="tipText"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("This way no content items are really deleting from DB, just marked as deleted"));?></span>
                </label>
                <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateRadioPair'))) echo $t->generateRadioPair("conf[site][safeDelete]",$t->conf['site']['safeDelete']);?>
            </p>
            <p>
                <label class="tipOwner" for="conf[site][tidyhtml]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Enable Tidy html cleaning"));?>
                <span class="tipText"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Requires the tidy extension to be installed"));?></span>
                </label>
                <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateRadioPair'))) echo $t->generateRadioPair("conf[site][tidyhtml]",$t->conf['site']['tidyhtml']);?>
            </p>
            <p>
                <label class="tipOwner" for="conf[site][templateEngine]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Template Engine"));?>
                <span class="tipText"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Seagull allows you to use the template engine of your choice"));?></span>
                </label>
                <?php if ($t->error['templateEngine'])  {?><span class="error"><?php echo htmlspecialchars($t->error['templateEngine']);?></span><?php }?>
                <select name="conf[site][templateEngine]">
                    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateSelect'))) echo $t->generateSelect($t->aTemplateEngines,$t->conf['site']['templateEngine']);?>
                </select>
            </p>
            <p>
                <label class="tipOwner" for="conf[site][outputUrlHandler]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Output URL handler"));?>
                <span class="tipText"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("What format would you like your output URLs, Seagull Search Engine Friendly is the default"));?></span>
                </label>
                <?php if ($t->error['outputUrlHandler'])  {?><span class="error"><?php echo htmlspecialchars($t->error['outputUrlHandler']);?></span><?php }?>
                <select name="conf[site][outputUrlHandler]">
                    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateSelect'))) echo $t->generateSelect($t->aUrlHandlers,$t->conf['site']['outputUrlHandler']);?>
                </select>
            </p>
            <p>
                <label class="tipOwner" for="conf[site][inputUrlHandlers]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Input URL handlers"));?>
                <span class="tipText"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Define the URL handlers that will be run on incoming requests"));?></span>
                </label>
                <input type="text" name="conf[site][inputUrlHandlers]" id="conf[site][inputUrlHandlers]" value="<?php echo htmlspecialchars($t->conf['site']['inputUrlHandlers']);?>" />
            </p>
            <p>
                <label class="tipOwner" for="conf[site][blocksEnabled]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Enable Blocks"));?>
                <span class="tipText"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("You can turn the blocks off globally"));?></span>
                </label>
                <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateRadioPair'))) echo $t->generateRadioPair("conf[site][blocksEnabled]",$t->conf['site']['blocksEnabled']);?>
            </p>
            <p>
                <label class="tipOwner" for="conf[site][rolesHaveAdminGui]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Roles that can access the admin GUI"));?>
                <span class="tipText"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("List role constants separated by commas, ie, SGL_ADMIN,SGL_FOO"));?></span>
                </label>
                <input type="text" class="longText" name="conf[site][rolesHaveAdminGui]" id="conf[site][rolesHaveAdminGui]" value="<?php echo htmlspecialchars($t->conf['site']['rolesHaveAdminGui']);?>" />
            </p>
            <p>
                <label class="tipOwner" for="conf[site][defaultArticleViewType]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Default article view type"));?>
                <span class="tipText"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("This options allows you to change the default type of article displayed. Default Article View Type: Html Articles (2)"));?></span>
                </label>
                <input type="text" name="conf[site][defaultArticleViewType]" id="conf[site][defaultArticleViewType]" value="<?php echo htmlspecialchars($t->conf['site']['defaultArticleViewType']);?>" />
            </p>
            <p>
                <label class="tipOwner" for="conf[site][frontScriptName]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Front controller script name"));?>
                <span class="tipText"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("The name of your Seagull index file"));?></span>
                </label>
                <input type="text" class="longText" name="conf[site][frontScriptName]" id="conf[site][frontScriptName]" value="<?php echo htmlspecialchars($t->conf['site']['frontScriptName']);?>" />
            </p>
            <p>
                <label class="tipOwner" for="conf[site][defaultModule]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Default module"));?>
                <span class="tipText"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("This is the module that will be loaded if none are specified, ie, when you call index.php"));?></span>
                </label>
                <input type="text" name="conf[site][defaultModule]" id="conf[site][defaultModule]" value="<?php echo htmlspecialchars($t->conf['site']['defaultModule']);?>" />
            </p>
            <p>
                <label class="tipOwner" for="conf[site][defaultManager]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Default manager"));?>
                <span class="tipText"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("This is the manager class that will be loaded if none are specified"));?></span>
                </label>
                <input type="text" name="conf[site][defaultManager]" id="conf[site][defaultManager]" value="<?php echo htmlspecialchars($t->conf['site']['defaultManager']);?>" />
            </p>
            <p>
                <label class="tipOwner" for="conf[site][defaultParams]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Default params"));?>
                <span class="tipText"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Use these params to specify, eg, a static article for your homepage"));?></span>
                </label>
                <input type="text" name="conf[site][defaultParams]" id="conf[site][defaultParams]" value="<?php echo htmlspecialchars($t->conf['site']['defaultParams']);?>" />
            </p>
            <p>
                <label class="tipOwner" for="conf[site][loginTarget]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Login target"));?>
                <span class="tipText"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("login target comment"));?></span>
                </label>
                <input type="text" name="conf[site][loginTarget]" id="conf[site][loginTarget]" value="<?php echo htmlspecialchars($t->conf['site']['loginTarget']);?>" />
            </p>
            <p>
                <label class="tipOwner" for="conf[site][logoutTarget]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Logout target"));?>
                <span class="tipText"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("logout target comment"));?></span>
                </label>
                <input type="text" name="conf[site][logoutTarget]" id="conf[site][logoutTarget]" value="<?php echo htmlspecialchars($t->conf['site']['logoutTarget']);?>" />
            </p>
            <p>
                <label class="tipOwner" for="conf[site][masterTemplate]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Default master template"));?>
                <span class="tipText"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("This is the master template that will be loaded"));?></span>
                </label>
                <?php if ($t->error['masterTemplate'])  {?><span class="error"><?php echo htmlspecialchars($t->error['masterTemplate']);?></span><?php }?>
                <input type="text" name="conf[site][masterTemplate]" id="conf[site][masterTemplate]" value="<?php echo htmlspecialchars($t->conf['site']['masterTemplate']);?>" />
            </p>
            <p>
                <label class="tipOwner" for="conf[site][masterLayout]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Default master css layout"));?>
                <span class="tipText"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("This is the master css layout that will be loaded"));?></span>
                </label>
                <?php if ($t->error['masterLayout'])  {?><span class="error"><?php echo htmlspecialchars($t->error['masterLayout']);?></span><?php }?>
                <input type="text" name="conf[site][masterLayout]" id="conf[site][masterLayout]" value="<?php echo htmlspecialchars($t->conf['site']['masterLayout']);?>" />
            </p>
            <p>
                <label class="tipOwner" for="conf[site][wysiwygEditor]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Editor type"));?>
                <span class="tipText"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Currently supported editors are xinha, fck and htmlarea, and you must have the relevant libs in your www dir"));?></span>
                </label>
                <select name="conf[site][wysiwygEditor]">
                    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateSelect'))) echo $t->generateSelect($t->aWysiwygEditor,$t->conf['site']['wysiwygEditor']);?>
                </select>
            </p>
            <?php if (!$t->isMinimalInstall)  {?>
            <p>
                <label class="tipOwner" for="conf[site][extendedLocale]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Extended locale support"));?>
                <span class="tipText"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("locale support info"));?></span>
                </label>
                <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateRadioPair'))) echo $t->generateRadioPair("conf[site][extendedLocale]",$t->conf['site']['extendedLocale']);?>
            </p>
            <p>
                <label class="tipOwner" for="conf[site][localeCategory]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Locale category"));?>
                <span class="tipText"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("locale support info"));?></span>
                </label>
                <input type="text" name="conf[site][localeCategory]" id="conf[site][localeCategory]" value="<?php echo htmlspecialchars($t->conf['site']['localeCategory']);?>" />
            </p>
            <?php }?>
            <p>
                <label for="conf[site][adminGuiTheme]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Admin GUI theme"));?></label>
                <input type="text" name="conf[site][adminGuiTheme]" id="conf[site][adminGuiTheme]" value="<?php echo htmlspecialchars($t->conf['site']['adminGuiTheme']);?>" />
            </p>
            <p>
                <label for="conf[site][defaultTheme]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Default theme"));?></label>
                <input type="text" name="conf[site][defaultTheme]" id="conf[site][defaultTheme]" value="<?php echo htmlspecialchars($t->conf['site']['defaultTheme']);?>" />
            </p>
            <p>
                <label for="conf[site][filterChain]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Custom filter chain"));?></label>
                <textarea class="longText" name="conf[site][filterChain]" id="conf[site][filterChain]"><?php echo htmlspecialchars($t->conf['site']['filterChain']);?></textarea>
            </p>
            <p>
                <label for="conf[site][customOutputClassName]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Custom output class"));?></label>
                <input type="text" name="conf[site][customOutputClassName]" id="conf[site][customOutputClassName]" value="<?php echo htmlspecialchars($t->conf['site']['customOutputClassName']);?>" />
            </p>
            <p>
                <label for="conf[site][broadcastMessage]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Broadcast message"));?></label>
                <input type="text" class="longText" name="conf[site][broadcastMessage]" id="conf[site][broadcastMessage]" value="<?php echo htmlspecialchars($t->conf['site']['broadcastMessage']);?>" />
            </p>
	        <p>
                <label for="conf[site][maintenanceMode]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Maintenance mode"));?></label>
                <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateRadioPair'))) echo $t->generateRadioPair("conf[site][maintenanceMode]",$t->conf['site']['maintenanceMode']);?>
            </p>
			<p>
                <label class="tipOwner" for="conf[site][adminKey]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Admin key"));?>
                	<span class="tipText"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("tipAdminKey"));?></span>
				</label>
				<input type="text" name="conf[site][adminKey]" id="conf[site][adminKey]" value="<?php echo htmlspecialchars($t->conf['site']['adminKey']);?>" />
            </p>
            <p>
                <label class="tipOwner" for="conf[site][globalJavascriptFiles]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Global Javascript Files"));?>
                <span class="tipText"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("globalJavascriptFiles"));?></span>
                </label>
                <input type="text" class="longText" name="conf[site][globalJavascriptFiles]" id="conf[site][globalJavascriptFiles]" value="<?php echo htmlspecialchars($t->conf['site']['globalJavascriptFiles']);?>" />
            </p>
            <p>
                <label class="tipOwner" for="conf[site][globalJavascriptOnReadyDom]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Global Javascript OnReadyDOM"));?>
                <span class="tipText"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("globalJavascriptOnReadyDom"));?></span>
                </label>
                <input type="text" class="longText" name="conf[site][globalJavascriptOnReadyDom]" id="conf[site][globalJavascriptOnReadyDom]" value="<?php echo htmlspecialchars($t->conf['site']['globalJavascriptOnReadyDom']);?>" />
            </p>
            <p>
                <label class="tipOwner" for="conf[site][globalJavascriptOnload]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Global Javascript Onload"));?>
                <span class="tipText"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("globalJavascriptOnload"));?></span>
                </label>
                <input type="text" class="longText" name="conf[site][globalJavascriptOnload]" id="conf[site][globalJavascriptOnload]" value="<?php echo htmlspecialchars($t->conf['site']['globalJavascriptOnload']);?>" />
            </p>
            <p>
                <label class="tipOwner" for="conf[site][globalJavascriptOnUnload]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Global Javascript OnUnload"));?>
                <span class="tipText"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("globalJavascriptOnUnload"));?></span>
                </label>
                <input type="text" class="longText" name="conf[site][globalJavascriptOnUnload]" id="conf[site][globalJavascriptOnUnload]" value="<?php echo htmlspecialchars($t->conf['site']['globalJavascriptOnUnload']);?>" />
            </p>
            <p>
                <label class="tipOwner" for="conf[site][customRebuildTasks]">Custom rebuild tasks
                <span class="tipText">Add a comma separated list of additional tasks here to be executed during a Seagull rebuild, SGL_Task_LoadMyData, SGL_Task_DoFoo</span>
                </label>
                <input type="text" class="longText" name="conf[site][customRebuildTasks]" id="conf[site][customRebuildTasks]" value="<?php echo htmlspecialchars($t->conf['site']['customRebuildTasks']);?>" />
            </p>

            <h3 class="show"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Paths"));?></h3>
            <p>
                <label for="conf[path][installRoot]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Install Root"));?></label>
                <?php if ($t->error['installRoot'])  {?><span class="error"><?php echo htmlspecialchars($t->error['installRoot']);?></span><?php }?>
                <input type="text" class="longText" name="conf[path][installRoot]" id="conf[path][installRoot]" value="<?php echo htmlspecialchars($t->conf['path']['installRoot']);?>" />
            </p>
            <p>
                <label for="conf[path][webRoot]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Web Root"));?></label>
                <?php if ($t->error['webRoot'])  {?><span class="error"><?php echo htmlspecialchars($t->error['webRoot']);?></span><?php }?>
                <input type="text" class="longText" name="conf[path][webRoot]" id="conf[path][webRoot]" value="<?php echo htmlspecialchars($t->conf['path']['webRoot']);?>" />
            </p>
            <p>
                <label for="conf[path][additionalIncludePath]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Additional Include Paths"));?></label>
                <input type="text" class="longText" name="conf[path][additionalIncludePath]" id="conf[path][additionalIncludePath]" value="<?php echo htmlspecialchars($t->conf['path']['additionalIncludePath']);?>" />
            </p>
            <p>
                <label for="conf[path][moduleDirOverride]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Module Directory Override"));?></label>
                <input type="text" class="longText" name="conf[path][moduleDirOverride]" id="conf[path][moduleDirOverride]" value="<?php echo htmlspecialchars($t->conf['path']['moduleDirOverride']);?>" />
            </p>
            <p>
                <label class="tipOwner" for="conf[path][uploadDirOverride]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Upload Directory Override"));?>
                <span class="tipText">provide path from sgl root, including initial slash and omitting final slash, eg: "/path/to/dir"</span></label>
                <input type="text" class="longText" name="conf[path][uploadDirOverride]" id="conf[path][uploadDirOverride]" value="<?php echo htmlspecialchars($t->conf['path']['uploadDirOverride']);?>" />
            </p>
            <p>
                <label class="tipOwner" for="conf[path][pathToCustomConfigFile]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Custom Config File"));?>
                <span class="tipText">If you want to include a custom config file for constants, etc, do it here</span></label>
                <input type="text" class="longText" name="conf[path][pathToCustomConfigFile]" id="conf[path][pathToCustomConfigFile]" value="<?php echo htmlspecialchars($t->conf['path']['pathToCustomConfigFile']);?>" />
            </p>
            <p>
                <label for="conf[path][tmpDirOverride]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Temp directory override"));?></label>
                <input type="text" name="conf[path][tmpDirOverride]" id="conf[path][tmpDirOverride]" value="<?php echo htmlspecialchars($t->conf['path']['tmpDirOverride']);?>" />
            </p>
        </fieldset>

        <fieldset class="main options" id="sessionOptions">
            <h3><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Session"));?></h3>
            <p>
                <label class="tipOwner" for="conf[session][handler]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Session handler"));?>
                <span class="tipText"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Use the database session handler if youre running a load-balanced environment"));?></span>
                </label>
                <select name="conf[session][handler]]">
                    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateSelect'))) echo $t->generateSelect($t->aSessHandlers,$t->conf['session']['handler']);?>
                </select>
            </p>
            <p>
                <label class="tipOwner" for="conf[session][maxLifetime]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Session max lifetime (secs)"));?>
                <span class="tipText"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Zero means until the browser is closed"));?></span>
                </label>
                <input type="text" name="conf[session][maxLifetime]" id="conf[session][maxLifetime]" value="<?php echo htmlspecialchars($t->conf['session']['maxLifetime']);?>" />
            </p>
            <p>
                <label class="tipOwner" for="conf[session][extended]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Extended Session"));?>
                <span class="tipText"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Enables extended session API when using database sessions. This allows the site to enforce one session per user."));?></span>
                </label>
                <?php if ($t->error['extendedSession'])  {?><span class="error"><?php echo htmlspecialchars($t->error['extendedSession']);?></span><?php }?>
                <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateRadioPair'))) echo $t->generateRadioPair("conf[session][extended]",$t->conf['session']['extended']);?>
            </p>
            <p>
                <label class="tipOwner" for="conf[session][singleUser]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Enforce Single User"));?>
                <span class="tipText"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Enforces one session per user on this site (requires database session handling, and extended session to be on)."));?></span>
                </label>
                <?php if ($t->error['singleUser'])  {?><span class="error"><?php echo htmlspecialchars($t->error['singleUser']);?></span><?php }?>
                <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateRadioPair'))) echo $t->generateRadioPair("conf[session][singleUser]",$t->conf['session']['singleUser']);?>
            </p>
            <p>
                <label class="tipOwner" for="conf[session][allowedInUri]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Allow Session in URL"));?>
                <span class="tipText"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("If users have cookies disabled, this will allow them to use sessions with Seagull"));?></span>
                </label>
                <?php if ($t->error['sessionInUri'])  {?><span class="error"><?php echo htmlspecialchars($t->error['sessionInUri']);?></span><?php }?>
                <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateRadioPair'))) echo $t->generateRadioPair("conf[session][allowedInUri]",$t->conf['session']['allowedInUri']);?>
            </p>
            <p>
                <label for="conf[session][permsRetrievalMethod]">
                    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Permissions retrieval method"));?>
                </label>
                <input id="conf[session][permsRetrievalMethod]" type="text" name="conf[session][permsRetrievalMethod]" value="<?php echo htmlspecialchars($t->conf['session']['permsRetrievalMethod']);?>" />
            </p>
        </fieldset>
        <fieldset class="main options" id="navigationOptions">
            <h3><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Navigation"));?></h3>
            <p>
                <label class="tipOwner" for="conf[navigation][enabled]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Enable Navigation"));?>
                <span class="tipText"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Disable navigation altogether with this switch"));?></span>
                </label>
                <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateRadioPair'))) echo $t->generateRadioPair("conf[navigation][enabled]",$t->conf['navigation']['enabled']);?>
            </p>
            <p>
                <label class="tipOwner" for="conf[navigation][driver]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Navigation driver"));?>
                <span class="tipText"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Use this option to choose from various menu types - currently only 1 provided"));?></span>
                </label>
                <select name="conf[navigation][driver]" id="conf[navigation][driver]">
                    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateSelect'))) echo $t->generateSelect($t->aNavDrivers,$t->conf['navigation']['driver']);?>
                </select>
            </p>
            <p>
                <label class="tipOwner" for="conf[navigation][renderer]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Navigation Html renderer"));?>
                <span class="tipText"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Use this option to choose from various menu types - currently only 1 provided"));?></span>
                </label>
                <select name="conf[navigation][renderer]" id="conf[navigation][renderer]">
                    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateSelect'))) echo $t->generateSelect($t->aNavRenderers,$t->conf['navigation']['renderer']);?>
                </select>
            </p>
        </fieldset>

        <fieldset class="main options" id="debuggingOptions">
            <h3><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Debug"));?></h3>
            <p>
                <label class="tipOwner" for="conf[debug][authorisationEnabled]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Enable authorisation"));?>
                <span class="tipText"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Debugging easier when this is disabled"));?></span>
                </label>
                <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateRadioPair'))) echo $t->generateRadioPair("conf[debug][authorisationEnabled]",$t->conf['debug']['authorisationEnabled']);?>
            </p>
            <p>
                <label class="tipOwner" for="conf[debug][customErrorHandler]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Enable custom error handler"));?>
                <span class="tipText"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Customise the way errors are handled"));?></span>
                </label>
                <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateRadioPair'))) echo $t->generateRadioPair("conf[debug][customErrorHandler]",$t->conf['debug']['customErrorHandler']);?>
            </p>
            <p>
                <label class="tipOwner" for="conf[debug][sessionDebugAllowed]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Enable debug session"));?>
                <span class="tipText"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("If your IP appears in the TRUSTED_IPS array, you will be able to view system errors on screen even in production mode (see below)"));?></span>
                </label>
                <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateRadioPair'))) echo $t->generateRadioPair("conf[debug][sessionDebugAllowed]",$t->conf['debug']['sessionDebugAllowed']);?>
            </p>
            <p>
                <label class="tipOwner" for="conf[debug][enableDebugBlock]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Enable debug block"));?>
                <span class="tipText"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Your database can be dropped if this block is enabled"));?></span>
                </label>
                <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateRadioPair'))) echo $t->generateRadioPair("conf[debug][enableDebugBlock]",$t->conf['debug']['enableDebugBlock']);?>
            </p>
            <p>
                <label class="tipOwner" for="conf[debug][infoBlock]">Enable overlay debug block
                <span class="tipText">these two blocks will soon be merged</span>
                </label>
                <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateRadioPair'))) echo $t->generateRadioPair("conf[debug][infoBlock]",$t->conf['debug']['infoBlock']);?>
            </p>
            <p>
                <label class="tipOwner" for="conf[debug][production]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Production website"));?>
                <span class="tipText"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Setting this to true will disable all screen-based error messages"));?></span>
                </label>
                <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateRadioPair'))) echo $t->generateRadioPair("conf[debug][production]",$t->conf['debug']['production']);?>
            </p>
            <p>
                <label for="conf[debug][showBacktrace]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Show backtrace"));?></label>
                <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateRadioPair'))) echo $t->generateRadioPair("conf[debug][showBacktrace]",$t->conf['debug']['showBacktrace']);?>
            </p>
            <p>
                <label class="tipOwner" for="conf[debug][profiling]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Enable Profiling"));?>
                <span class="tipText"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Requires to Xdebug extension to be installed"));?></span>
                </label>
                <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateRadioPair'))) echo $t->generateRadioPair("conf[debug][profiling]",$t->conf['debug']['profiling']);?>
            </p>
            <p>
                <label class="tipOwner" for="conf[debug][emailAdminThreshold]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Email admin threshold"));?>
                <span class="tipText"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Errors must be >= this level before they are emailed to the site admin"));?></span>
                </label>
                <select name="conf[debug][emailAdminThreshold]" id="conf[debug][emailAdminThreshold]">
                    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateSelect'))) echo $t->generateSelect($t->aEmailThresholds,$t->conf['debug']['emailAdminThreshold']);?>
                </select>
            </p>
            <p>
                <label class="tipOwner" for="conf[debug][showBugReporterLink]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Show debug reporting link"));?>
                <span class="tipText"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Send feedback to project for bugs"));?></span>
                </label>
                <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateRadioPair'))) echo $t->generateRadioPair("conf[debug][showBugReporterLink]",$t->conf['debug']['showBugReporterLink']);?>
            </p>
            <p>
                <label class="tipOwner" for="conf[debug][showUntranslated]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Mark words which were not translated"));?>
                <span class="tipText"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Words which system was unable to translate will be enclosed in \"> <\" marks"));?></span>
                </label>
                <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateRadioPair'))) echo $t->generateRadioPair("conf[debug][showUntranslated]",$t->conf['debug']['showUntranslated']);?>
            </p>
            <p>
                <label class="tipOwner" for="conf[debug][dataObject]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("DataObject debug level"));?></label>
                <select name="conf[debug][dataObject]" id="conf[debug][dataObject]">
                    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateSelect'))) echo $t->generateSelect($t->aDbDoDebugLevels,$t->conf['debug']['dataObject']);?>
                </select>
            </p>
        </fieldset>

        <fieldset class="main options" id="cachingOptions">
            <h3><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Caching"));?></h3>
            <p>
                <label class="tipOwner" for="conf[cache][enabled]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Enable global caching"));?>
                <span class="tipText"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("It is recommended to disable this while developing"));?></span>
                </label>
                <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateRadioPair'))) echo $t->generateRadioPair("conf[cache][enabled]",$t->conf['cache']['enabled']);?>
            </p>
            <p>
                <label class="tipOwner" for="conf[cache][lifetime]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Cache lifetime (secs)"));?>
                <span class="tipText"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Default is 24 hours"));?></span>
                </label>
                <input type="text" name="conf[cache][lifetime]" id="conf[cache][lifetime]" value="<?php echo htmlspecialchars($t->conf['cache']['lifetime']);?>" />
            </p>
            <p>
                <label class="tipOwner" for="conf[cache][libCacheEnabled]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Enable library caching"));?>
                <span class="tipText"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("It is recommended to disable this while developing"));?></span>
                </label>
                <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateRadioPair'))) echo $t->generateRadioPair("conf[cache][libCacheEnabled]",$t->conf['cache']['libCacheEnabled']);?>
            </p>
            <p>
                <label class="tipOwner" for="conf[cache][cleaningFactor]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Cleaning factor"));?>
                    <span class="tipText"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Cleaning factor tip"));?></span>
                </label>
                <input type="text" name="conf[cache][cleaningFactor]" id="conf[cache][cleaningFactor]" value="<?php echo htmlspecialchars($t->conf['cache']['cleaningFactor']);?>" />
            </p>
            <p>
                <label class="tipOwner" for="conf[cache][readControl]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Read control"));?>
                    <span class="tipText"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Read control tip"));?></span>
                </label>
                <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateRadioPair'))) echo $t->generateRadioPair("conf[cache][readControl]",$t->conf['cache']['readControl']);?>
            </p>
            <p>
                <label class="tipOwner" for="conf[cache][writeControl]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Write control"));?>
                    <span class="tipText"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Write control tip"));?></span>
                </label>
                <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateRadioPair'))) echo $t->generateRadioPair("conf[cache][writeControl]",$t->conf['cache']['writeControl']);?>
            </p>
            <p>
                <label for="conf[cache][javascript]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Cache JavaScript"));?></label>
                <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateRadioPair'))) echo $t->generateRadioPair("conf[cache][javascript]",$t->conf['cache']['javascript']);?>
            </p>
        </fieldset>

        <fieldset class="main options" id="databaseOptions">
            <h3><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("DB"));?></h3>
            <p>
                <label class="tipOwner" for="conf"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Type"));?>
                <span class="tipText"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Make sure you load the relevant schema"));?></span>
                </label>
                <select name="conf[db][type]" id="conf[db][type]">
                    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateSelect'))) echo $t->generateSelect($t->aDbTypes,$t->conf['db']['type']);?>
                </select>
            </p>
            <p>
                <label class="tipOwner" for="conf[db][mysqlDefaultStorageEngine]">
                    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("MySQL Default Storage Engine"));?>
                    <span class="tipText"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Default MySQL storage engine for all tables in database"));?></span>
                </label>
                <select name="conf[db][mysqlDefaultStorageEngine]" id="conf[db][mysqlDefaultStorageEngine]">
                    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateSelect'))) echo $t->generateSelect($t->aMysqlEngines,$t->conf['db']['mysqlDefaultStorageEngine']);?>
                </select>
            </p>
            <p>
                <label for="conf[db][sepTableForEachSequence]">
                    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Separate table for each sequence"));?>
                </label>
                <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateRadioPair'))) echo $t->generateRadioPair("conf[db][sepTableForEachSequence]",$t->conf['db']['sepTableForEachSequence']);?>
            </p>
            <p>
                <label for="conf"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Host"));?></label>
                <input type="text" name="conf[db][host]" value="<?php echo htmlspecialchars($t->conf['db']['host']);?>" />
            </p>
            <p>
                <label for="conf"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Port"));?></label>
                <input type="text" name="conf[db][port]" value="<?php echo htmlspecialchars($t->conf['db']['port']);?>" />
            </p>
            <p>
                <label for="conf"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Protocol"));?></label>
                <input type="text" name="conf[db][protocol]" value="<?php echo htmlspecialchars($t->conf['db']['protocol']);?>" />
            </p>
            <p>
                <label for="conf"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Socket"));?></label>
                <input type="text" name="conf[db][socket]" value="<?php echo htmlspecialchars($t->conf['db']['socket']);?>" />
            </p>
            <p>
                <label for="conf"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("DB username"));?></label>
                <input type="text" name="conf[db][user]" value="<?php echo htmlspecialchars($t->conf['db']['user']);?>" />
            </p>
            <p>
                <label for="conf"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("DB password"));?></label>
                <input type="password" name="conf[db][pass]" value="<?php echo htmlspecialchars($t->conf['db']['pass']);?>" />
            </p>
            <p>
                <label for="conf"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("DB name"));?></label>
                <input type="text" name="conf[db][name]" value="<?php echo htmlspecialchars($t->conf['db']['name']);?>" />
            </p>
            <p>
                <label for="conf"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("DB charset"));?></label>
                <input type="text" name="conf[db][charset]" value="<?php echo htmlspecialchars($t->conf['db']['charset']);?>" />
            </p>
            <p>
                <label for="conf"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("DB collation"));?></label>
                <input type="text" name="conf[db][collation]" value="<?php echo htmlspecialchars($t->conf['db']['collation']);?>" />
            </p>
            <p>
                <label class="tipOwner" for="conf_db_prefix"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Table prefix"));?>
                    <span class="tipText"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("This is used to prefix all tables in database, change with caution"));?></span>
                </label>
                <?php if ($t->error['db']['prefix'])  {?><span class="error"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate($t->error['db']['prefix']));?></span><?php }?>
                <input id="conf_db_prefix" type="text" name="conf[db][prefix]" value="<?php echo htmlspecialchars($t->conf['db']['prefix']);?>" />
            </p>
            <p>
                <label class="tipOwner" for="conf[db][postConnect]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Post-connection query"));?>
                <span class="tipText"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("This query is used to set the default character set for the current connection (MySQL 4.1 or higher). For example: SET NAMES utf8"));?></span>
                </label>
                <input type="text" class="longText" name="conf[db][postConnect]" id="conf[db][postConnect]" value="<?php echo htmlspecialchars($t->conf['db']['postConnect']);?>" />
            </p>
            <h3><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Database Table Mappings"));?></h3>
            <?php if ($this->options['strict'] || (is_array($t->conf['table'])  || is_object($t->conf['table']))) foreach($t->conf['table'] as $key => $var) {?><p>
                <label for="conf[table][<?php echo htmlspecialchars($key);?>]"><?php echo htmlspecialchars($key);?></label>
                <input type="text" name="conf[table][<?php echo htmlspecialchars($key);?>]" id="conf[table][<?php echo htmlspecialchars($key);?>]" value="<?php echo htmlspecialchars($var);?>" />
            </p><?php }?>
        </fieldset>

        <fieldset class="main options" id="loggingOptions">
            <h3><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Logs"));?></h3>
            <p>
                <label class="tipOwner" for="conf[log][enabled]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Enable logs"));?>
                <span class="tipText"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("It is recommended to disable logging if you are running < PHP 4.3.x"));?></span>
                </label>
                <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateRadioPair'))) echo $t->generateRadioPair("conf[log][enabled]",$t->conf['log']['enabled']);?>
            </p>
            <p>
                <label class="tipOwner" for="conf[log][type]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Type"));?>
                <span class="tipText"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("If sql is used, use log_table as the log table name below"));?></span>
                </label>
                <select name="conf[log][type]" id="conf[log][type]">
                    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateSelect'))) echo $t->generateSelect($t->aLogTypes,$t->conf['log']['type']);?>
                </select>
            </p>
            <p>
                <label class="tipOwner" for="conf[log][name]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Log name"));?>
                <span class="tipText"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Use an absolute path or one relative to the Seagull root dir"));?></span>
                </label>
                <input type="text" name="conf[log][name]" value="<?php echo htmlspecialchars($t->conf['log']['name']);?>" />
            </p>
            <p>
                <label for="conf[log][priority]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Priority"));?></label>
                <select name="conf[log][priority]" id="conf[log][priority]">
                    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateSelect'))) echo $t->generateSelect($t->aLogPriorities,$t->conf['log']['priority']);?>
                </select>
            </p>
            <p>
                <label for="conf[log][ident]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Identifier"));?></label>
                <input type="text" name="conf[log][ident]" id="conf[log][ident]" value="<?php echo htmlspecialchars($t->conf['log']['ident']);?>" />
            </p>
            <p>
                <label for="conf[log][paramsUsername]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Target username"));?></label>
                <input type="text" name="conf[log][paramsUsername]" id="conf[log][paramsUsername]" value="<?php echo htmlspecialchars($t->conf['log']['paramsUsername']);?>" />
            </p>
            <p>
                <label for="conf[log][paramsPassword]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Target password"));?></label>
                <input type="text" name="conf[log][paramsPassword]" id="conf[log][paramsPassword]" value="<?php echo htmlspecialchars($t->conf['log']['paramsPassword']);?>" />
            </p>
            <p>
                <label class="tipOwner" for="conf[log][showErrors]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Show errors"));?>
                <span class="tipText"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Select No if you do not want errors displayed in the logs"));?></span>
                </label>
                <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateRadioPair'))) echo $t->generateRadioPair("conf[log][showErrors]",$t->conf['log']['showErrors']);?>
            </p>
        </fieldset>

        <fieldset class="main options" id="mtaOptions">
            <h3><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("MTA"));?></h3>
            <p>
                <label class="tipOwner" for="conf[mta][backend]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Backend"));?>
                <span class="tipText"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("PEAR::Mail backend"));?></span>
                </label>
                <?php if ($t->error['mtaBackend'])  {?><span class="error"><?php echo htmlspecialchars($t->error['mtaBackend']);?></span><?php }?>
                <select name="conf[mta][backend]" id="conf[mta][backend]">
                    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateSelect'))) echo $t->generateSelect($t->aMtaBackends,$t->conf['mta']['backend']);?>
                </select>
            </p>
            <p>
                <label class="tipOwner" for="conf[mta][sendmailPath]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Sendmail path"));?>
                <span class="tipText"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Mandatory if you use Sendmail as Backend"));?></span>
                </label>
                <?php if ($t->error['sendmailPath'])  {?><span class="error"><?php echo htmlspecialchars($t->error['sendmailPath']);?></span><?php }?>
                <input type="text" name="conf[mta][sendmailPath]" id="conf[mta][sendmailPath]" value="<?php echo htmlspecialchars($t->conf['mta']['sendmailPath']);?>" />
            </p>
            <p>
                <label class="tipOwner" for="conf[mta][sendmailArgs]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Sendmail arguments"));?>
                <span class="tipText"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Optional if you use Sendmail as Backend"));?></span>
                </label>
                <?php if ($t->error['sendmailArgs'])  {?><span class="error"><?php echo htmlspecialchars($t->error['sendmailArgs']);?></span><?php }?>
                <input type="text" name="conf[mta][sendmailArgs]" id="conf[mta][sendmailArgs]" value="<?php echo htmlspecialchars($t->conf['mta']['sendmailArgs']);?>" />
            </p>
            <p>
                <label class="tipOwner" for="conf[mta][smtpHost]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("SMTP host"));?>
                <span class="tipText"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Optional if you use SMTP as Backend. Default: localhost"));?></span>
                </label>
                <input type="text" name="conf[mta][smtpHost]" id="conf[mta][smtpHost]" value="<?php echo htmlspecialchars($t->conf['mta']['smtpHost']);?>" />
            </p>
            <p>
                <label class="tipOwner" for="conf[mta][smtpPort]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("SMTP port"));?>
                <span class="tipText"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Optional if you use SMTP as Backend. Default: 25"));?></span>
                </label>
                <input type="text" name="conf[mta][smtpPort]" id="conf[mta][smtpPort]" value="<?php echo htmlspecialchars($t->conf['mta']['smtpPort']);?>" />
            </p>
            <p>
                <label class="tipOwner" for="conf[mta][smtpAuth]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Use SMTP authentication"));?>
                <span class="tipText"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("PEAR::Mail supported SMTP authentication methods"));?></span>
                </label>
                <select name="conf[mta][smtpAuth]" id="conf[mta][smtpAuth]">
                    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateSelect'))) echo $t->generateSelect($t->aMtaAuthentication,$t->conf['mta']['smtpAuth']);?>
                </select>
            </p>
            <p>
                <label class="tipOwner" for="conf[mta][smtpUsername]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("SMTP username"));?>
                <span class="tipText"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Mandatory if you use SMTP as Backend and SMTP authentication is enabled"));?></span>
                </label>
                <?php if ($t->error['smtpUsername'])  {?><span class="error"><?php echo htmlspecialchars($t->error['smtpUsername']);?></span><?php }?>
                <input type="text" name="conf[mta][smtpUsername]" id="conf[mta][smtpUsername]" value="<?php echo htmlspecialchars($t->conf['mta']['smtpUsername']);?>" />
            </p>
             <p>
                <label class="tipOwner" for="conf[mta][smtpPassword]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("SMTP password"));?>
                <span class="tipText"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Mandatory if you use SMTP as Backend and SMTP authentication is enabled"));?></span>
                </label>
                <?php if ($t->error['smtpPassword'])  {?><span class="error"><?php echo htmlspecialchars($t->error['smtpPassword']);?></span><?php }?>
                <input type="password" name="conf[mta][smtpPassword]" id="conf[mta][smtpPassword]" value="<?php echo htmlspecialchars($t->conf['mta']['smtpPassword']);?>" />
            </p>
        </fieldset>

        <fieldset class="main options" id="emailOptions">
            <h3><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Email"));?></h3>
            <p>
                <label class="tipOwner" for="conf[email][admin]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Admin contact"));?>
                <span class="tipText"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Error messages get sent here"));?></span>
                </label>
                <input type="text" class="longText" name="conf[email][admin]" id="conf[email][admin]" value="<?php echo htmlspecialchars($t->conf['email']['admin']);?>" />
            </p>
            <p>
                <label for="conf[email][support]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Support contact"));?></label>
                <input type="text" class="longText" name="conf[email][support]" id="conf[email][support]" value="<?php echo htmlspecialchars($t->conf['email']['support']);?>" />
            </p>
            <p>
                <label class="tipOwner" for="conf[email][info]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Info contact"));?>
                <span class="tipText"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Contact us enquiries get sent here"));?></span>
                </label>
                <input type="text" class="longText" name="conf[email][info]" id="conf[email][info]" value="<?php echo htmlspecialchars($t->conf['email']['info']);?>" />
            </p>
        </fieldset>

        <fieldset class="main options" id="popupWindowOptions">
            <h3><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Popup"));?></h3>
            <p>
                <label for="conf[popup][winHeight]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Default popup window height"));?></label>
                <input type="text" name="conf[popup][winHeight]" id="conf[popup][winHeight]" value="<?php echo htmlspecialchars($t->conf['popup']['winHeight']);?>" />
            </p>
            <p>
                <label for="conf[popup][winWidth]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Default popup window width"));?></label>
                <input type="text" name="conf[popup][winWidth]" id="conf[popup][winWidth]" value="<?php echo htmlspecialchars($t->conf['popup']['winWidth']);?>" />
            </p>
        </fieldset>

        <fieldset class="main options" id="translationOptions">
            <h3><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Translation"));?></h3>
            <p>
                <label class="tipOwner" for="conf[translation][container]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Container"));?>
                    <span class="tipText"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Coming Soon - The ability to switch between translation storage containers."));?></span>
                </label>
                <select name="conf[translation][container]" id="transContainer">
                    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateSelect'))) echo $t->generateSelect($t->aTranslationContainers,$t->conf['translation']['container']);?>
                </select>
                <input type="hidden" name="conf[translation][container]" value="<?php echo htmlspecialchars($t->conf['translation']['container']);?>" />
            </p>
            <p>
                <label class="tipOwner" for="conf[translation][fallbackLang]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Fallback Language"));?>
                    <span class="tipText"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Language to use when the current language does not have a translation."));?></span>
                </label>
                <select name="conf[translation][fallbackLang]" id="transFallbackLang">
                    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateSelect'))) echo $t->generateSelect($t->aInstalledLangs,$t->conf['translation']['fallbackLang']);?></select>
                <input type="hidden" name="conf[translation][installedLanguages]" value="<?php echo htmlspecialchars($t->conf['translation']['installedLanguages']);?>" />
            </p>
            <p>
                <label class="tipOwner" for="conf[translation][addMissingTrans]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Add Missing Translations"));?>
                    <span class="tipText"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Add missing translations to the database."));?></span>
                </label>
                <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateRadioPair'))) echo $t->generateRadioPair("conf[translation][addMissingTrans]",$t->conf['translation']['addMissingTrans']);?>
            </p>
            <p>
                <label for="conf[translation][languageAutoDiscover]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Language auto discover"));?></label>
                <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateRadioPair'))) echo $t->generateRadioPair("conf[translation][languageAutoDiscover]",$t->conf['translation']['languageAutoDiscover']);?>
            </p>
            <p>
            	<label for="conf[translation][defaultLangBC]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Translation from default module (BC)"));?></label>
                <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateRadioPair'))) echo $t->generateRadioPair("conf[translation][defaultLangBC]",$t->conf['translation']['defaultLangBC']);?>
            </p>
        </fieldset>

        <fieldset class="main options" id="cookieOptions">
            <h3><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Cookie"));?></h3>
            <p>
                <label class="tipOwner" for="conf[cookie][name]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Name"));?>
                <span class="tipText"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("This will be your session identifier"));?></span>
                </label>
                <input type="text" name="conf[cookie][name]" id="conf[cookie][name]" value="<?php echo htmlspecialchars($t->conf['cookie']['name']);?>" />
            </p>
            <p>
                <label for="conf[cookie][path]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Path"));?></label>
                <input type="text" name="conf[cookie][path]" id="conf[cookie][path]" value="<?php echo htmlspecialchars($t->conf['cookie']['path']);?>" />
            </p>
            <p>
                <label for="conf[cookie][domain]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Domain"));?></label>
                <input type="text" name="conf[cookie][domain]" id="conf[cookie][domain]" value="<?php echo htmlspecialchars($t->conf['cookie']['domain']);?>" />
            </p>
            <p>
                <label for="conf[cookie][secure]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Secure"));?></label>
                <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateRadioPair'))) echo $t->generateRadioPair("conf[cookie][secure]",$t->conf['cookie']['secure']);?>
            </p>
            <p>
                <label for="conf[cookie][rememberMeEnabled]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Enable persistent logins"));?></label>
                <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateRadioPair'))) echo $t->generateRadioPair("conf[cookie][rememberMeEnabled]",$t->conf['cookie']['rememberMeEnabled']);?>
            </p>
        </fieldset>

        <fieldset class="main options" id="censorship">
            <h3><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Censorship"));?></h3>
            <p>
                <label for="conf[censor][mode]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Mode"));?></label>
                <select name="conf[censor][mode]" id="conf[censor][mode]">
                    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateSelect'))) echo $t->generateSelect($t->aCensorModes,$t->conf['censor']['mode']);?>
                </select>
            </p>
            <p>
                <label for="conf[censor][replaceString]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Replace word with"));?></label>
                <input type="text" name="conf[censor][replaceString]" id="conf[censor][replaceString]" value="<?php echo htmlspecialchars($t->conf['censor']['replaceString']);?>" />
            </p>
            <p>
                <label for="conf[censor][badWords]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Disallowed words"));?></label>
                <textarea class="longText" name="conf[censor][badWords]" id="conf[censor][badWords]"><?php echo htmlspecialchars($t->conf['censor']['badWords']);?></textarea>
            </p>
        </fieldset>

        <fieldset class="main options" id="p3pPrivacyPolicy">
            <h3><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("P3P"));?></h3>
            <p>
                <label for="conf[p3p][policies]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Policies"));?></label>
                <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateRadioPair'))) echo $t->generateRadioPair("conf[p3p][policies]",$t->conf['p3p']['policies']);?>
            </p>
            <p>
                <label for="conf[p3p][policyLocation]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Policy location"));?></label>
                <input type="text" class="longText" name="conf[p3p][policyLocation]" id="conf[p3p][policyLocation]" value="<?php echo htmlspecialchars($t->conf['p3p']['policyLocation']);?>" />
            </p>
            <p>
                <label for="conf[p3p][compactPolicy]"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Compact policy"));?></label>
                <input type="text" class="longText" name="conf[p3p][compactPolicy]" id="conf[p3p][compactPolicy]" value="<?php echo htmlspecialchars($t->conf['p3p']['compactPolicy']);?>" />
            </p>
        </fieldset>
    </form>
    <div class="spacer"></div>
</div>
<script type="text/javascript">
    createAvailOptionsLinks('configuration','h3');
</script>
