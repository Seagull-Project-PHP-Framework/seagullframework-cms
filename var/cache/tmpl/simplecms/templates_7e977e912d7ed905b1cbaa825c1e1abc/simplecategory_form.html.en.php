<div>
    <fieldset class="hide">
        <input id="category_id" type="hidden" name="categoryId" value="<?php echo htmlspecialchars($t->oCategory->category2_id);?>" />
        <input type="hidden" name="category[is_active]" value="0" />
        <input type="hidden" name="redir" value="<?php echo htmlspecialchars($t->redir);?>" />

        <?php if (!$t->isEdit)  {?>
        <input type="hidden" name="category[language_id]" value="<?php echo htmlspecialchars($t->oCategory->language_id);?>" />
        <input type="hidden" name="category[parent_id]" value="<?php echo htmlspecialchars($t->oCategory->parent_id);?>" />
        <?php }?>
    </fieldset>
    <fieldset class="info">
        <ol class="clearfix">
            <li>
                <label><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("category path"));?></label>
                <div>
                    <?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo $this->plugin("renderCategoryPath",$t->aPath);?>
                    <?php if (!$t->isEdit)  {?>
                    <?php if ($t->oCategory->parent_id)  {?>&nbsp;&gt;&nbsp;&nbsp;<?php }?><strong><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("new category"));?></strong>
                    <?php }?>
                </div>
            </li>
            <li>
                <label><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("category current language"));?></label>
                <div>
                    <?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo $this->plugin("getContentLangIcon",$t->oCategory->language_id);?>
                </div>
            </li>
        </ol>
    </fieldset>
    <fieldset class="fields">
        <ol>
            <!--li>
                <label for="category_parent-id">{tr(#category parent#)}</label>
                <div>
                {if:isEdit}
                    show parent
                {else:}
                    {if:!oCategory.parent_id}
                    {tr(#top level category#)}
                    {end:}
                {end:}
                </div>
            </li-->
            <?php if ($t->isEdit)  {?><li>
                <label for="category_language-id"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("category language"));?></label>
                <div>
                    <select id="category_language-id" name="category[language_id]">
                        <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateSelect'))) echo $t->generateSelect($t->aLangs,$t->oCategory->language_id);?>
                    </select>
                </div>
            </li><?php }?>
            <li>
                <label for="category_name"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("category name"));?></label>
                <div>
                    <input id="category_name" class="text" type="text" name="category[name]" value="<?php echo htmlspecialchars($t->oCategory->name);?>" />
                </div>
            </li>
            <li>
                <label for="category_description"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("category description"));?></label>
                <div>
                    <textarea id="category_description" name="category[description]" rows="5" cols="20"><?php echo htmlspecialchars($t->oCategory->description);?></textarea>
                </div>
            </li>
            <li>
                <label for="category_is-active"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("category is active"));?></label>
                <div>
                    <?php if ($t->oCategory->is_active)  {?><input id="category_is-active" type="checkbox" name="category[is_active]" value="1" checked="checked" /><?php }?>
                    <?php if (!$t->oCategory->is_active)  {?><input id="category_is-active" type="checkbox" name="category[is_active]" value="1" /><?php }?>
                </div>
            </li>
        </ol>
    </fieldset>
    <p class="fieldIndent">
        <span class="triggers">
            <?php if ($t->isEdit)  {?>
            <?php if (!$t->oCategory->parent_id)  {?><input class="button" type="submit" name="submitted" value="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("update root category (button)"));?>" /><?php }?>
            <?php if ($t->oCategory->parent_id)  {?><input class="button" type="submit" name="submitted" value="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("update category (button)"));?>" /><?php }?>
            <?php } else {?>
            <?php if (!$t->oCategory->parent_id)  {?><input class="button" type="submit" name="submitted" value="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("add root category (button)"));?>" /><?php }?>
            <?php if ($t->oCategory->parent_id)  {?><input class="button" type="submit" name="submitted" value="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("add category (button)"));?>" /><?php }?>
            <?php }?>

            <?php if ($t->redir)  {?>
            &nbsp;
            <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("or"));?>
            &nbsp;
            <a href="<?php echo htmlspecialchars($t->redir);?>"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("cancel"));?></a>
            <?php }?>
        </span>
        <img class="ajaxLoader" src="<?php echo htmlspecialchars($t->imagesDir);?>/ajax-loader.gif" alt="" style="display: none;" />
    </p>
</div>
