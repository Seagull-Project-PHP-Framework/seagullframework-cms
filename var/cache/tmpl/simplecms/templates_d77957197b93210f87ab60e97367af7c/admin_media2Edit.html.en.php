<form id="media-edit" method="post" class="columns layout-2cols" action="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("updateMedia","media2","media2"));?>">
      
    <div class="title-edit">
        <h1><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("edit media (header)"));?></h1>
        <span>
            <a href="#" class="delete" sgl:redir="<?php echo htmlspecialchars($t->redir);?>" sgl:media_id="<?php echo htmlspecialchars($t->oMedia->media_id);?>"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("delete media (action)"));?></a>
            <img class="ajaxLoader" src="<?php echo htmlspecialchars($t->imagesDir);?>/ajax-loader.gif" alt="" style="display: none;" />
        </span>
    </div>
      
    <div class="c75l">
        <div class="layout-inner">
            <fieldset class="info">
                <ol class="clearfix">
                    <li>
                        <label><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("media icon"));?></label>
                        <div>
                            <img src="<?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo htmlspecialchars($this->plugin("getIconByMimeType",$t->oMedia->mime_type));?>" alt="<?php echo htmlspecialchars($t->oMedia->mime_type);?>" />
                        </div>
                    </li>
                    <li>
                        <label><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("media mime type"));?></label>
                        <div>
                            <?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo htmlspecialchars($this->plugin("getArrayValueByKey",$t->aMimeTypes,$t->oMedia->media_mime_id));?> (<?php echo htmlspecialchars($t->oMedia->mime_type);?>)
                        </div>
                    </li>
                    <li>
                        <label><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("media type"));?></label>
                        <?php if ($t->oMedia->media_type_id)  {?><div>
                            <?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo htmlspecialchars($this->plugin("getArrayValueByKey",$t->aMediaTypes,$t->oMedia->media_type_id));?>
                        </div><?php }?>
                        <?php if (!$t->oMedia->media_type_id)  {?><div>
                            <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("default media type"));?>
                        </div><?php }?>                        
                    </li>
                </ol>
            </fieldset>
            <fieldset class="fields">
                <ol class="clearfix">
                    <li>
                        <label for="media_name"><em>*</em> <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("media name"));?></label>
                        <div>
                            <input id="media_name" class="text" type="text" name="aMedia[name]" value="<?php echo htmlspecialchars($t->oMedia->name);?>" />
                        </div>
                    </li>
                    <li>
                        <label for="media_description"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("media description"));?></label>
                        <div>
                            <textarea id="media_description" name="aMedia[description]" cols="60" rows="10"><?php echo htmlspecialchars($t->oMedia->description);?></textarea>
                        </div>
                    </li>
                </ol>           
            </fieldset>
            <fieldset class="hide">
                <input type="hidden" name="aMedia[media_id]" value="<?php echo htmlspecialchars($t->oMedia->media_id);?>" />
                <input type="hidden" name="redir" value="<?php echo htmlspecialchars($t->redir);?>" />
            </fieldset>            
            <p class="fieldIndent">
                <span class="triggers">
                    <input class="button" type="submit" name="submitted" value="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("update media (button)"));?>" />
                    &nbsp;
                    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("or"));?>
                    &nbsp;
                    <?php if ($t->redir)  {?><a href="<?php echo htmlspecialchars($t->redir);?>"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("cancel"));?></a><?php }?>
                    <?php if (!$t->redir)  {?><a href="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("","media2","media2"));?>"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("cancel"));?></a><?php }?>
                </span>
                <img class="ajaxLoader" src="<?php echo htmlspecialchars($t->imagesDir);?>/ajax-loader.gif" alt="" style="display: none;" />
            </p>
            <p><em>*</em> <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("denotes required field"));?></p>            
        </div>
    </div><!-- item -->
    
    <div class="c25r">

        <?php 
$x = new HTML_Template_Flexy($this->options);
$x->compile('block_media_overview.html');
$_t = function_exists('clone') ? clone($t) : $t;
foreach(get_defined_vars()  as $k=>$v) {
    if ($k != 't') { $_t->$k = $v; }
}
$x->outputObject($_t, $this->elements);
?>
    
    </div>

</form>
