<!-- Filter -->
<div id="export-config" class="block-helper block-item-list">
    <h2><a href="#"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("export config (header)"));?></a></h2>
    <form class="inner" method="post" action="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("export","cmsexporter","simplecms"));?>">

        <!-- default values -->
        <input type="hidden" name="config[replace_ids]" value="0" />
        <input type="hidden" name="config[data]" value="0" />
        <input type="hidden" name="config[links]" value="0" />
        <input type="hidden" name="config[attrib_lists]" value="0" />
        <input type="hidden" name="config[categories]" value="0" />

        <ul>
            <li>
                <label for="config_content-type"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("config content type"));?></label>
                <select id="config_content-type" name="contentTypeId">
                    <option value=""><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("all"));?></option>
                    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateSelect'))) echo $t->generateSelect($t->aContentTypes,$t->contentTypeId);?>
                </select>
            </li>
            <li>
                <?php if ($t->config['replace_ids'])  {?><input id="config_replace-ids" type="checkbox" name="config[replace_ids]" value="1" checked="checked" /><?php }?>
                <?php if (!$t->config['replace_ids'])  {?><input id="config_replace-ids" type="checkbox" name="config[replace_ids]" value="1" /><?php }?>
                <label for="config_replace-ids"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("config replace real ids with sgl id"));?></label>
            </li>
            <li>
                <?php if ($t->config['data'])  {?><input id="config_data" type="checkbox" name="config[data]" value="1" checked="checked" /><?php }?>
                <?php if (!$t->config['data'])  {?><input id="config_data" type="checkbox" name="config[data]" value="1" /><?php }?>
                <label for="config_data"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("config include data"));?></label>
            </li>
            <li>
                <?php if ($t->config['categories'])  {?><input id="config_categories" type="checkbox" name="config[categories]" value="1" checked="checked" /><?php }?>
                <?php if (!$t->config['categories'])  {?><input id="config_categories" type="checkbox" name="config[categories]" value="1" /><?php }?>
                <label for="config_categories"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("config include categories"));?></label>
            </li>
            <!--li>
                <input id="config_links" type="checkbox" name="config[links]"
                    value="1" checked="checked" flexy:if="config[links]" />
                <input id="config_links" type="checkbox" name="config[links]"
                    value="1" flexy:if="!config[links]" />
                <label for="config_links">{translate(#config include links#)}</label>
            </li-->
            <li>
                <?php if ($t->config['attrib_lists'])  {?><input id="config_attrib-lists" type="checkbox" name="config[attrib_lists]" value="1" checked="checked" /><?php }?>
                <?php if (!$t->config['attrib_lists'])  {?><input id="config_attrib-lists" type="checkbox" name="config[attrib_lists]" value="1" /><?php }?>
                <label for="config_attrib-lists"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("config include attribute lists"));?></label>
            </li>
        </ul>

        <p>
            <input class="button" type="submit" name="submitted" value="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("do export (action)"));?>" />
        </p>
    </form>
</div>
