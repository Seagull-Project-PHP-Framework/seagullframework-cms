<div class="title-edit">
    <h1><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("media upload (header)"));?></h1>
    <span>
        <?php if ($t->redir)  {?><a href="<?php echo htmlspecialchars($t->redir);?>"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("go back"));?></a><?php }?>
        <?php if (!$t->redir)  {?><a href="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("","media2","media2"));?>"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("go back to media list"));?></a><?php }?>
    </span>
</div>
  
<div class="c75l">
    <div class="layout-inner">
        <?php if (!$t->aTypes)  {?><fieldset class="hide">
            <input id="media_type" type="hidden" name="typeId" value="0" />
        </fieldset><?php }?>                   
        <?php if ($t->aTypes)  {?><fieldset class="info">
            <ol class="clearfix">
                <li>
                    <label for="media_type"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("media type"));?></label>
                    <div>
                        <select id="media_type" name="typeId">
                            <option value=""><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("default media type"));?></option>
                            <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateSelect'))) echo $t->generateSelect($t->aTypes);?>
                        </select>
                        <p class="comment"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("media type select hint"));?></p>
                    </div>
                </li>
            </ol>           
        </fieldset><?php }?>
        <fieldset class="fields">
            <ol class="clearfix">
                <li>
                    <label for="media_upload"><em>*</em> <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("select file to upload"));?></label>
                    <div>
                        <input id="media_upload" type="file" name="filedata" />
                    </div>
                </li>
            </ol>           
        </fieldset>
        <fieldset class="hide">
            <input id="media_redir" type="hidden" name="redir" value="<?php echo htmlspecialchars($t->redir);?>" />
        </fieldset>           
        <p class="fieldIndent">
            <span class="triggers">
                <input class="button" type="button" name="submitted" value="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("upload media (button)"));?>" />
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
