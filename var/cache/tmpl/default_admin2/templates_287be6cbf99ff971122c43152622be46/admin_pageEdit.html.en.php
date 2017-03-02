<form id="page-container" class="columns layout-2cols sgl-form" method="post" action="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("updatePage","page","page"));?>">

    <?php if ($t->isEdit)  {?>
    <div class="title-edit-simple">
        <h1><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("edit page (header)"));?></h1>
        <span>
            <a id="page-delete_<?php echo htmlspecialchars($t->pageId);?>" href="#" class="page-delete delete"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("delete page (action)"));?></a>
            <img class="ajaxLoader" src="<?php echo htmlspecialchars($t->imagesDir);?>/ajax-loader.gif" alt="" style="display: none;" />
        </span>
    </div>
    <?php } else {?>
    <h1 class="title-single-simple"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("create page (header)"));?></h1>
    <?php }?>

    <div class="c75l item">
        <div class="layout-inner">
            <fieldset class="hide">
                <?php if ($t->isEdit)  {?>
                <input id="page_site" type="hidden" name="page[site_id]" value="<?php echo htmlspecialchars($t->oPage->site_id);?>" />
                <?php }?>
                <input id="page_page-id" type="hidden" name="page[page_id]" value="<?php echo htmlspecialchars($t->pageId);?>" />
                <input type="hidden" name="page[appears_in_nav]" value="0" />
                <input type="hidden" name="page[are_comments_allowed]" value="0" />
                <input type="hidden" name="page[status]" value="0" />
                <input id="page_content-id" type="hidden" name="page[content_id]" value="<?php echo htmlspecialchars($t->oContent->id);?>" />
                <input type="hidden" name="redir" value="<?php echo htmlspecialchars($t->redir);?>" />
            </fieldset>

            <div id="page-panel-tabs">
	            <ul id="page-panel-trigger">
	                <li>
	                    <a href="#page-panel-main"><span><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("page main (tab)"));?></span></a>
	                </li>
	                <li>
	                    <a href="#page-panel-content"><span><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("page content (tab)"));?></span></a>
	                </li>
	                <li>
	                    <a href="#page-panel-layout"><span><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("page layout (tab)"));?></span></a>
	                </li>
	            </ul>
	
	            <div id="page-panel-main">
	                <fieldset class="info">
	                    <ol class="clearfix">
	                        <?php if ($t->isEdit)  {?>
	                        <li>
	                            <label><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("page site"));?></label>
	                            <div>
	                                <?php echo htmlspecialchars($t->oSite->name);?>
	                            </div>
	                        </li>
	                        <li id="page-language-container">
	                            <label for="page_language"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("page language"));?></label>
	                            <div class="lang clearfix">
	                                <select id="page_language" name="page[language_id]">
	                                    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateSelect'))) echo $t->generateSelect($t->aLangs,$t->langId);?>
	                                </select>
	                                <img class="ajaxLoader" src="<?php echo htmlspecialchars($t->imagesDir);?>/ajax-loader.gif" alt="" style="display: none;" />
	                            </div>
	                        </li>
	                        <!--li>
	                            <label>{tr(#page current language#)}</label>
	                            <div>
	                                {this.plugin(#getContentLangIcon#,oPage.language_id):h}
	                                {this.plugin(#getArrayValueByKey#,aLangs,oPage.language_id)}
	                            </div>
	                        </li-->
	                        <?php } else {?>
	                        <li>
	                            <label for="page_site"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("page site"));?></label>
	                            <div>
	                                <select id="page_site" name="page[site_id]">
	                                <?php if ($this->options['strict'] || (is_array($t->aSites)  || is_object($t->aSites))) foreach($t->aSites as $key => $siteName) {?>
	                                    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'assign'))) echo htmlspecialchars($t->assign($t->siteId2,$key));?>
	                                    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'isEqual'))) if (!$t->isEqual($key,$t->siteId)) { ?><option value="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("action|add||siteId|siteId2||langId|langId"));?>"><?php echo htmlspecialchars($siteName);?></option><?php }?>
	                                    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'isEqual'))) if ($t->isEqual($key,$t->siteId)) { ?><option selected="selected" value="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("action|add||siteId|siteId2||langId|langId"));?>"><?php echo htmlspecialchars($siteName);?></option><?php }?>
	                                <?php }?>
	                                </select>
	                                <input type="hidden" name="page[site_id]" value="<?php echo htmlspecialchars($t->siteId);?>" />
	                            </div>
	                        </li>
	                        <li>
	                            <label for="page_language"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("page language"));?></label>
	                            <div>
	                                <select id="page_language" name="page[language_id]">
	                                <?php if ($this->options['strict'] || (is_array($t->aLangs)  || is_object($t->aLangs))) foreach($t->aLangs as $key => $langName) {?>
	                                    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'assign'))) echo htmlspecialchars($t->assign($t->langId2,$key));?>
	                                    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'isEqual'))) if (!$t->isEqual($key,$t->langId)) { ?><option value="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("action|add||siteId|siteId||langId|langId2"));?>"><?php echo htmlspecialchars($langName);?></option><?php }?>
	                                    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'isEqual'))) if ($t->isEqual($key,$t->langId)) { ?><option selected="selected" value="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("action|add||siteId|siteId||langId|langId2"));?>"><?php echo htmlspecialchars($langName);?></option><?php }?>
	                                <?php }?>
	                                </select>
	                                <input type="hidden" name="page[language_id]" value="<?php echo htmlspecialchars($t->langId);?>" />
	                            </div>
	                        </li>
	                        <?php }?>
	                        <?php if (!$t->isEdit)  {?><li>
	                            <label for="page_location"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("page parent location"));?></label>
	                            <div>
	                                <select id="page_location" name="page[parent_id]">
	                                    <?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo $this->plugin("renderPageSelect",$t->aTree);?>
	                                </select>
	                            </div>
	                        </li><?php }?>
	                        <?php if ($t->isEdit)  {?><li id="page-path-container">
	                            <label><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("page location"));?></label>
	                            <div>
	                                <span><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("top level page"));?></span>
	                                <?php if ($t->aPath)  {?>
	                                &nbsp;&gt;&nbsp;&nbsp;<?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo $this->plugin("renderPagePath",$t->aPath);?>
	                                <?php }?>
	                            </div>
	                        </li><?php }?>
	                    </ol>
	                </fieldset>
	                <fieldset class="fields">
	                    <legend><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("page name (legend)"));?></legend>
	                    <ol class="clearfix">
	                        <li>
	                            <label for="page_title"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("page title"));?></label>
	                            <div>
	                                <input id="page_title" class="text" type="text" name="page[title]" value="<?php echo htmlspecialchars($t->oPage->title);?>" />
	                            </div>
	                        </li>
	                        <li id="page-route-container">
	                            <label for="page_route"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("page route"));?></label>
	                            <div>
	                                
	                                <input id="page_route" class="text" type="text" name="route[path]" value="<?php echo htmlspecialchars($t->route->path);?>" />
	                                <p><span><?php echo htmlspecialchars($t->webRoot);?><?php if ($t->conf['site']['frontScriptName'])  {?>/<?php echo htmlspecialchars($t->conf['site']['frontScriptName']);?><?php }?></span><span class="route"><?php echo htmlspecialchars($t->oRoute->route);?></span></p>
	                            </div>
	                        </li>
	                    </ol>
	                </fieldset>
	                <fieldset class="fields">
                        <legend><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("meta info (legend)"));?></legend>
	                    <ol class="clearfix">
	                        <li>
	                            <label for="page_meta-desc"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("page meta description"));?></label>
	                            <div>
	                                <input id="page_meta-desc" class="text" type="text" name="page[meta_desc]" value="<?php echo htmlspecialchars($t->oPage->meta_desc);?>" />
	                            </div>
	                        </li>
	                        <li>
	                            <label for="page_meta-key"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("page meta keywords"));?></label>
	                            <div>
	                                <input id="page_meta-key" class="text" type="text" name="page[meta_key]" value="<?php echo htmlspecialchars($t->oPage->meta_key);?>" />
	                            </div>
	                        </li>
	                        <li>
	                            <label for="page_appears-in-nav"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("page appears in nav"));?></label>
	                            <div>
	                                <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'isEqual'))) if ($t->isEqual($t->oPage->appears_in_nav,1)) { ?><input id="page_appears-in-nav" type="checkbox" name="page[appears_in_nav]" value="1" checked="checked" /><?php }?>
	                                <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'isEqual'))) if (!$t->isEqual($t->oPage->appears_in_nav,1)) { ?><input id="page_appears-in-nav" type="checkbox" name="page[appears_in_nav]" value="1" /><?php }?>
	                            </div>
	                        </li>
	                        <li>
	                            <label for="page_are-comments-allowed"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("page allows comments"));?></label>
	                            <div>
	                                <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'isEqual'))) if ($t->isEqual($t->oPage->are_comments_allowed,1)) { ?><input id="page_are-comments-allowed" type="checkbox" name="page[are_comments_allowed]" value="1" checked="checked" /><?php }?>
	                                <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'isEqual'))) if (!$t->isEqual($t->oPage->are_comments_allowed,1)) { ?><input id="page_are-comments-allowed" type="checkbox" name="page[are_comments_allowed]" value="1" /><?php }?>
	                            </div>
	                        </li>
	                        <li>
	                            <label for="page_status"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("page is active"));?></label>
	                            <div>
	                                <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'isEqual'))) if ($t->isEqual($t->oPage->status,1)) { ?><input id="page_status" type="checkbox" name="page[status]" value="1" checked="checked" /><?php }?>
	                                <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'isEqual'))) if (!$t->isEqual($t->oPage->status,1)) { ?><input id="page_status" type="checkbox" name="page[status]" value="1" /><?php }?>
	                            </div>
	                        </li>
	                    </ol>
	                </fieldset>
	            </div>
	            <div id="page-panel-content">
	            
	                <fieldset class="info">
                        <ol class="clearfix">
                            <li>
                                <label><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("page title"));?></label>
                                <div>
                                    <span class="page-title"><?php echo htmlspecialchars($t->oPage->title);?></span>
                                </div>
                            </li>
                            <li id="content-language-container">
                                <label for="content_language"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("content language"));?></label>
                                <?php if ($t->isEdit)  {?><div>
                                    <input name="content[langId]" value="<?php echo htmlspecialchars($t->oPage->language_id);?>" type="hidden" />
                                    <?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo $this->plugin("getContentLangIcon",$t->oPage->language_id);?>
                                    <span><?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo htmlspecialchars($this->plugin("getArrayValueByKey",$t->aLangs,$t->oPage->language_id));?></span>
                                </div><?php }?>
                                <?php if (!$t->isEdit)  {?><div>
                                    <input name="content[langId]" value="<?php echo htmlspecialchars($t->langId);?>" type="hidden" />
                                    <?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo $this->plugin("getContentLangIcon",$t->langId);?>
                                    <span><?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo htmlspecialchars($this->plugin("getArrayValueByKey",$t->aLangs,$t->langId));?></span>
                                </div><?php }?>
                            </li>
                        </ol>
	                </fieldset>
	                
	                <fieldset class="fields">
	                    <legend><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("linked content (legend)"));?></legend>
	                    <ol class="clearfix">
	                        <li>
	                            <label for="content_type"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("content type"));?></label>
	                            <div>
	                                <select id="content_type" name="content[type]">
	                                    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateSelect'))) echo $t->generateSelect($t->aContentTypes,$t->contentTypeId);?>
	                                </select>
	                            </div>
	                        </li>
	                        <li>
	                            <label for="content_value"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("content value"));?></label>
	                            <div>
	                                <input id="content_value" class="text" type="text" name="content[value]" value="" />
	                                <p class="comment">
	                                    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("start typing to get available contents"));?>
	                                </p>
	                            </div>
	                        </li>
	                    </ol>
	                </fieldset>
	                <fieldset class="info">
	                    <ol class="clearfix">
	                        <li>
	                            <label><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("page current content"));?></label>
	                            <div id="page-content-container">
	                                <span>
	                                <?php if ($t->oContent)  {?>
	                                    <?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo htmlspecialchars($this->plugin("getContentTitle",$t->oContent));?> (<?php echo htmlspecialchars($t->oContent->id);?>)
	                                <?php } else {?>
	                                    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("no page content set"));?>
	                                <?php }?>
	                                </span>
	                                &nbsp;
	                                <?php if ($t->oContent)  {?><a href="#"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("clear current page content"));?></a><?php }?>
	                                <?php if (!$t->oContent)  {?><a href="#" style="display: none;"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("clear current page content"));?></a><?php }?>
	                            </div>
	                        </li>
	                    </ol>
	                </fieldset>
                    <div class="route-widget-container clearfix">
	                    <div class="route-widget-controller">
		                    <a href=""><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("advanced (action)"));?></a>
		                    <a href="" style="display:none;"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("hide (action)"));?></a>
	                    </div>
                        <div class="route-widget" style="display:none;">
                            <input name="route_path_original" type="hidden" value="<?php echo htmlspecialchars($t->route->path);?>" />
                            <fieldset class="fields">
                                <legend><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("module details (legend)"));?></legend>
                                <ol>
                                    <li>
                                        <label><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("module","ucfirst"));?></label>
                                        <div>
                                            <select name="route[moduleName]">
                                                <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateSelect'))) echo $t->generateSelect($t->aModules,$t->route->moduleName);?>
                                            </select>
                                         </div>
                                    </li>
                                    <li>
	                                    <label><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("manager","ucfirst"));?></label>
	                                    <div>
		                                    <select name="route[controller]">
		                                        <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateSelect'))) echo $t->generateSelect($t->aManagers,$t->route->controller);?>
		                                    </select>
	                                    </div>
                                    </li>
                                    <li>
	                                    <label><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("action","ucfirst"));?></label>
	                                    <div>
		                                    <select name="route[action]">
		                                        <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateSelect'))) echo $t->generateSelect($t->aActions,$t->route->action);?>
		                                    </select>
	                                    </div>
                                    </li>
                                    <li>
	                                    <label><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("params","ucfirst"));?></label>
	                                    <div>
    	                                    <input name="route[__params]" type="text" value="<?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo htmlspecialchars($this->plugin("renderRouteParametersCompact",$t->route));?>" />
	                                    </div>
                                    </li>
                                </ol>
                            </fieldset>
                        </div>
                    </div>
	                
	            </div>
	            <div id="page-panel-layout">
	                <fieldset class="info" style="border: 1px solid #eee">
	                    <br /><br /><br /><br /><br />
	                    <br /><br /><br /><br /><br />
	                    <br /><br /><br /><br /><br />
	                </fieldset>
	            </div>
            </div>

            <p class="fieldIndent">
                <span class="triggers">
                    <?php if ($t->isEdit)  {?>
                    <input class="button" type="submit" name="submitted" value="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("update page (button)"));?>" />
                    &nbsp;&nbsp;
                    <input class="button" type="submit" name="submittedContinue" value="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("update page and continue (button)"));?>" />
                    <?php } else {?>
                    <input class="button" type="submit" name="submitted" value="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("add page (button)"));?>" />
                    &nbsp;&nbsp;
                    <input class="button" type="submit" name="submittedContinue" value="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("add page and continue (button)"));?>" />
                    <?php }?>
                    &nbsp;
                    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("or"));?>
                    &nbsp;
                    <?php if ($t->redir)  {?><a href="<?php echo htmlspecialchars($t->redir);?>"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("cancel"));?></a><?php }?>
                    <?php if (!$t->redir)  {?><a href="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("","page","page"));?>"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("cancel"));?></a><?php }?>
                </span>
                <img class="ajaxLoader" src="<?php echo htmlspecialchars($t->imagesDir);?>/ajax-loader.gif" alt="" style="display: none;" />
            </p>
        </div>
    </div><!-- item -->

    <div class="c25r">

        <?php if ($t->isEdit)  {?>
        <?php 
$x = new HTML_Template_Flexy($this->options);
$x->compile('block_page_overview.html');
$_t = function_exists('clone') ? clone($t) : $t;
foreach(get_defined_vars()  as $k=>$v) {
    if ($k != 't') { $_t->$k = $v; }
}
$x->outputObject($_t, $this->elements);
?>
        <?php 
$x = new HTML_Template_Flexy($this->options);
$x->compile('block_page_tree.html');
$_t = function_exists('clone') ? clone($t) : $t;
foreach(get_defined_vars()  as $k=>$v) {
    if ($k != 't') { $_t->$k = $v; }
}
$x->outputObject($_t, $this->elements);
?>
        <?php }?>

    </div>

</form><!-- page-container -->
