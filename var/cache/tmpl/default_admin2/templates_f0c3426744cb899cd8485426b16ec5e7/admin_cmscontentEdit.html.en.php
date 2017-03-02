<form id="content-container" class="columns layout-2cols" method="post" action="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("updateContent","simplecms","simplecms"));?>">

    <?php if ($t->isEdit)  {?>
    <div class="title-edit-simple">
        <h1><?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo htmlspecialchars($this->plugin("getContentTitle",$t->oContent));?>&nbsp;</h1>
        <span>
            <a href="#" class="delete" sgl:redir="<?php echo htmlspecialchars($t->redir);?>" sgl:content_id="<?php echo htmlspecialchars($t->oContent->id);?>" sgl:content_lang="<?php echo htmlspecialchars($t->oContent->langCode);?>"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("delete content"));?></a>
            <img class="ajaxLoader" src="<?php echo htmlspecialchars($t->imagesDir);?>/ajax-loader.gif" alt="" style="display: none;" />
        </span>
    </div>
    <?php } else {?>
    <?php if (!$t->isNew)  {?><h1 class="title-single"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("create new language version (header)"));?></h1><?php }?>
    <?php if ($t->isNew)  {?><h1 class="title-single"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("create content (header)"));?></h1><?php }?>
    <?php }?>

    <div class="c75l item">
        <div class="layout-inner">

            <fieldset class="hide">

                <input id="content_type-name" type="hidden" name="type" value="<?php echo htmlspecialchars($t->oContent->typeName);?>" />

                <?php if (!$t->isNew)  {?>
                <!-- This is predefined when creating new language versions -->
                <input type="hidden" name="content[id]" value="<?php echo htmlspecialchars($t->oContent->id);?>" />
                <input type="hidden" name="content[lang]" value="<?php echo htmlspecialchars($t->oContent->langCode);?>" />

                <?php if (!$t->isEdit)  {?>
                <!-- We don't need type_id for existing content -->
                <input type="hidden" name="content[type_id]" value="<?php echo htmlspecialchars($t->oContent->typeId);?>" />
                <?php }?>

                <?php }?>

                <?php if ($t->isEdit)  {?>
                <!-- Content name is only known for existing content -->
                <input type="hidden" name="content[name]" value="<?php echo htmlspecialchars($t->oContent->name);?>" />

                <!--
                <input type="hidden" name="content[version]" value="{oContent.version}" />
                <input type="hidden" name="content[is_current]" value="{oContent.isCurrent}" />
                -->
                <?php }?>

                <input type="hidden" name="redir" value="<?php echo htmlspecialchars($t->redir);?>" />
            </fieldset>

			<div id="content-panel-tabs">
	            <?php if ($t->isEdit)  {?><ul id="content-panel-trigger">
	                <li>
	                    <a href="#content-panel-main"><span><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("content main tab"));?></span></a>
	                </li>
	                <li>
	                    <a href="#content-panel-template"><span><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("content template tab"));?></span></a>
	                </li>
	                <li>
	                    <a href="#content-panel-associations"><span><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("content associate tab"));?></span></a>
	                </li>
	            </ul><?php }?>
	
	            <div id="content-panel-main">
	                <fieldset class="info">
	                    <ol class="clearfix">
	
	                        <!-- Only when creating new content it is possible to change language -->
	                        <?php if (!$t->isNew)  {?><li>
	                            <label><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("content language"));?></label>
	                            <div>
	                                <?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo $this->plugin("getContentLangIcon",$t->oContent->langCode);?>
	                                <?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo htmlspecialchars($this->plugin("getArrayValueByKey",$t->aLangs,$t->cLang));?>
	                            </div>
	                        </li><?php }?>
	
	                        <!-- Version number is visible only for existing content -->
	                        <?php if ($t->isEdit)  {?><li>
	                            <label><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("current content version"));?></label>
	                            <div>
	                                <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("content version %version%","vprintf",$t->oContent));?>
	                            </div>
	                        </li><?php }?>
	
	                        <?php if ($t->isNew)  {?>
	                        <li>
	                            <label for="content_lang"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("content language"));?></label>
	                            <div>
	                                <select id="content_lang" name="content[lang]">
	                                    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateSelect'))) echo $t->generateSelect($t->aLangs,$t->cLang);?>
	                                </select>
	                            </div>
	                        </li>
	                        <li>
	                            <label for="content_type"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("content type"));?></label>
	                            <div>
	                                <select id="content_type" name="content[type_id]">
	                                <?php if ($this->options['strict'] || (is_array($t->aContentTypes)  || is_object($t->aContentTypes))) foreach($t->aContentTypes as $typeId => $typeName) {?>
	                                    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'assign'))) echo htmlspecialchars($t->assign($t->typeId2,$typeId));?>
	                                    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'isEqual'))) if (!$t->isEqual($typeId,$t->type)) { ?><option value="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("action|add||type|typeId2||cLang|cLang"));?>"><?php echo htmlspecialchars($typeName);?></option><?php }?>
	                                    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'isEqual'))) if ($t->isEqual($typeId,$t->type)) { ?><option selected="selected" value="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("action|add||type|typeId2||cLang|cLang"));?>"><?php echo htmlspecialchars($typeName);?></option><?php }?>
	                                <?php }?>
	                                </select>
	                                <input type="hidden" name="content[type_id]" value="<?php echo htmlspecialchars($t->type);?>" />
	                            </div>
	                        </li>
	                        <li>
	                            <label for="content_status"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("content status"));?></label>
	                            <div>
	                                <select id="content_status" name="content[status]">
	                                    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateSelect'))) echo $t->generateSelect($t->aStatuses,$t->oContent->status);?>
	                                </select>
	                            </div>
	                        </li>
	                        <?php }?>
	                    </ol>
	                </fieldset>
	                <fieldset class="fields">
	                    <?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo $this->plugin("renderContentForEdit",$t->oContent);?>
	                </fieldset>
	            </div>
	            <?php if ($t->isEdit)  {?><div id="content-panel-template">
	                <fieldset class="fields">
	                    <ol class="onTop">
	                        <li>
	                            <label for="content_template"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("content template"));?></label>
	                            <div>
	                                <textarea id="content_template" name="content[template]" cols="40" rows="20"><?php echo htmlspecialchars($t->contentTemplate);?></textarea>
	                            </div>
	                        </li>
	                    </ol>
	                </fieldset>
	            </div><?php }?>
	            <?php if ($t->isEdit)  {?><div id="content-panel-associations">
	                <fieldset class="fields">
	                    <ol class="clearfix">
	                        <li>
	                            <label><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("assoc content language"));?></label>
	                            <div>
	                                <?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo $this->plugin("getContentLangIcon",$t->oContent->langCode);?>
	                                <?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo htmlspecialchars($this->plugin("getArrayValueByKey",$t->aLangs,$t->cLang));?>
	                                <input id="assoc-lang-id" type="hidden" name="assoc_langId" value="<?php echo htmlspecialchars($t->oContent->langCode);?>" />
	                            </div>
	                        </li>
	                        <li>
	                            <label for="assoc_content-type"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("assoc content type"));?></label>
	                            <div>
	                                <select id="assoc_content-type" name="assoc_contentTypeId">
	                                    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateSelect'))) echo $t->generateSelect($t->aContentTypes,$t->oContent->typeId);?>
	                                </select>
	                            </div>
	                        </li>
	                        <li>
	                            <label for="assoc_content-value"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("assoc content value"));?></label>
	                            <div>
	                                <input id="assoc_content-value" class="text" type="text" name="assoc[content_value]" value="" />
	                                <p class="comment">
	                                    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("start typing to get available contents"));?>
	                                </p>
	                            </div>
	                        </li>
	                    </ol>
	                </fieldset>
	                <fieldset id="assoc-content-ids" class="hide">
	                <?php if ($this->options['strict'] || (is_array($t->aAssocContents)  || is_object($t->aAssocContents))) foreach($t->aAssocContents as $oAssocContent) {?>
	                    <input id="assoc_content_<?php echo htmlspecialchars($oAssocContent->id);?>" type="hidden" name="assocs[]" value="<?php echo htmlspecialchars($oAssocContent->id);?>" />
	                <?php }?>
	                </fieldset>
	                <fieldset class="info">
	                    <ol class="clearfix">
	                        <li>
	                            <label><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("current assoc contents"));?></label>
	                            <div id="content-assocs-container">
	                                <!--
	                                <table class="full">
	                                    <tbody>
	                                    {foreach:aAssocContents,oAssocContent}
	                                        <tr>
	                                            <td>
	                                                {this.plugin(#getContentTitle#,oAssocContent)}
	                                            </td>
	                                            <td>
	                                                <a id="assoc-content_{oAssocContent.id}" href="#">{tr(#remove assoc content#)}</a>
	                                            </td>
	                                        </tr>
	                                    {end:}
	                                    {if:!oAssocContent}
	                                        <tr>
	                                            <td class="2">{tr(#no page content set#)}</td>
	                                        </tr>
	                                    {end:}
	                                    </tbody>
	                                </table>
	                                -->
	
	                                <?php if ($this->options['strict'] || (is_array($t->aAssocContents)  || is_object($t->aAssocContents))) foreach($t->aAssocContents as $oAssocContent) {?><p>
	                                    <?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo htmlspecialchars($this->plugin("getContentTitle",$oAssocContent));?> | <?php echo htmlspecialchars($oAssocContent->typeName);?>
	                                    &nbsp;
	                                    <a id="assoc-content_<?php echo htmlspecialchars($oAssocContent->id);?>" class="del" href="#"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("remove assoc content"));?></a>
	                                </p><?php }?>
	
	                                <?php if (!$t->aAssocContents)  {?><p><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("no assoc content set"));?></p><?php }?>
	                                <?php if ($t->aAssocContents)  {?><p style="display: none;"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("no assoc content set"));?></p><?php }?>
	                            </div>
	                        </li>
	                    </ol>
	                </fieldset>
	            </div><?php }?>
			</div>
			
            <p class="fieldIndent">
                <span class="triggers">
                    <?php if ($t->isEdit)  {?>
                    <?php if ($t->oContent->isCurrent)  {?><input class="button" type="submit" name="submitted" value="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("update content (button)"));?>" /><?php }?>
                    <?php if (!$t->oContent->isCurrent)  {?><input class="button" type="submit" name="submitted" value="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("revert content (button)"));?>" /><?php }?>
                    &nbsp;&nbsp;
                    <?php if ($t->oContent->isCurrent)  {?><input class="button" type="submit" name="submittedContinue" value="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("update content and continue (button)"));?>" /><?php }?>
                    <?php if (!$t->oContent->isCurrent)  {?><input class="button" type="submit" name="submittedContinue" value="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("revert content and continue (button)"));?>" /><?php }?>
                    <?php } else {?>
                    <input class="button" type="submit" name="submitted" value="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("add content (button)"));?>" />
                    &nbsp;&nbsp;
                    <input class="button" type="submit" name="submittedContinue" value="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("add content and continue (button)"));?>" />
                    <?php }?>
                    &nbsp;
                    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("or"));?>
                    &nbsp;
                    <?php if ($t->redir)  {?><a href="<?php echo htmlspecialchars($t->redir);?>"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("cancel"));?></a><?php }?>
                    <?php if (!$t->redir)  {?><a href="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("","cmscontent","simplecms"));?>"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("cancel"));?></a><?php }?>
                </span>
                <img class="ajaxLoader" src="<?php echo htmlspecialchars($t->imagesDir);?>/ajax-loader.gif" alt="" style="display: none;" />
            </p>
        </div>
    </div><!-- item -->

    <div class="c25r">

        <?php 
$x = new HTML_Template_Flexy($this->options);
$x->compile('block_content_preview.html');
$_t = function_exists('clone') ? clone($t) : $t;
foreach(get_defined_vars()  as $k=>$v) {
    if ($k != 't') { $_t->$k = $v; }
}
$x->outputObject($_t, $this->elements);
?>
        <?php if (!$t->isNew)  {?>
        <?php 
$x = new HTML_Template_Flexy($this->options);
$x->compile('block_content_overview.html');
$_t = function_exists('clone') ? clone($t) : $t;
foreach(get_defined_vars()  as $k=>$v) {
    if ($k != 't') { $_t->$k = $v; }
}
$x->outputObject($_t, $this->elements);
?>
        <?php 
$x = new HTML_Template_Flexy($this->options);
$x->compile('block_content_status.html');
$_t = function_exists('clone') ? clone($t) : $t;
foreach(get_defined_vars()  as $k=>$v) {
    if ($k != 't') { $_t->$k = $v; }
}
$x->outputObject($_t, $this->elements);
?>
        <?php }?>
        <?php 
$x = new HTML_Template_Flexy($this->options);
$x->compile('block_content_category.html');
$_t = function_exists('clone') ? clone($t) : $t;
foreach(get_defined_vars()  as $k=>$v) {
    if ($k != 't') { $_t->$k = $v; }
}
$x->outputObject($_t, $this->elements);
?>
        <?php if ($t->isEdit)  {?>
        <?php 
$x = new HTML_Template_Flexy($this->options);
$x->compile('block_content_version.html');
$_t = function_exists('clone') ? clone($t) : $t;
foreach(get_defined_vars()  as $k=>$v) {
    if ($k != 't') { $_t->$k = $v; }
}
$x->outputObject($_t, $this->elements);
?>
        <?php }?>
        <?php if (!$t->isNew)  {?>
        <?php 
$x = new HTML_Template_Flexy($this->options);
$x->compile('block_content_langs.html');
$_t = function_exists('clone') ? clone($t) : $t;
foreach(get_defined_vars()  as $k=>$v) {
    if ($k != 't') { $_t->$k = $v; }
}
$x->outputObject($_t, $this->elements);
?>
        <?php }?>

    </div>

</form><!-- content-container -->