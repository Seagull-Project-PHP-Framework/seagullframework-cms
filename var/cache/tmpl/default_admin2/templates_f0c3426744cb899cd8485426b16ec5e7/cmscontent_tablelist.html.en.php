<?php if ($this->options['strict'] || (is_array($t->aContents)  || is_object($t->aContents))) foreach($t->aContents as $key => $oContent) {?><tr <?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo $this->plugin("getContentRowStyle");?>>
    <td>
        <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'assign'))) echo htmlspecialchars($t->assign($t->contentId,$oContent->id));?>
        <a href="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("action|edit||contentId|contentId||cLang|cLang"));?>" title="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("edit latest content version"));?>">
            <?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo htmlspecialchars($this->plugin("getContentTitle",$oContent));?></a>
    </td>
    <!--td>{oContent.id}</td-->
    <!--td class="version-cell context-menu-wrapper">

        <div class="triggers floatbox">
            {assign(contentId,oContent.id)}
            <a class="version-current"
               href="{makeUrl(#action|edit||contentId|contentId||cLang|cLang#)}" title="{tr(#edit latest content version#)}">
                <img src="{webRoot}/themes/{theme}/images/icons/page_edit.gif" alt="" /></a>

            {if:!isEqual(oContent.version,#1#)}
            <a id="version-trigger-open_{oContent.id}" class="trigger-open version-trigger-open" href="#" title="{tr(#show version list#)}">
                <img src="{webRoot}/themes/{theme}/images/icons/arrow_down.gif" alt="" /></a>
            <a id="version-trigger-close_{oContent.id}" class="trigger-close version-trigger-close" href="#" title="{tr(#close version list#)}" style="display: none">
                <img src="{webRoot}/themes/{theme}/images/icons/arrow_up.gif" alt="" /></a>
            {end:}
        </div>

        <div id="version-wrapper_{oContent.id}" class="context-menu" style="display: none;"
             flexy:if="!isEqual(oContent.version,#1#)">
            {this.plugin(#renderContentEditVersionList#,oContent,cLang):h}
        </div>
    </td-->
    <td class="lang-cell context-menu-wrapper">

        <div class="triggers floatbox">
            <span class="lang-current"><?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo $this->plugin("getContentLangIcon",$oContent->langCode);?></span>
            <a id="lang-trigger-open_<?php echo htmlspecialchars($oContent->id);?>" class="trigger-open lang-trigger-open" href="#" title="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("show language list"));?>">
                <img src="<?php echo htmlspecialchars($t->webRoot);?>/themes/<?php echo htmlspecialchars($t->theme);?>/images/icons/arrow_down.gif" alt="" /></a>
            <a id="lang-trigger-close_<?php echo htmlspecialchars($oContent->id);?>" class="trigger-close lang-trigger-close" href="#" title="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("hide language list"));?>" style="display: none">
                <img src="<?php echo htmlspecialchars($t->webRoot);?>/themes/<?php echo htmlspecialchars($t->theme);?>/images/icons/arrow_up.gif" alt="" /></a>
        </div>

        <div id="lang-wrapper_<?php echo htmlspecialchars($oContent->id);?>" class="context-menu" style="display: none;">
            <?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo $this->plugin("renderContentEditLangList",$oContent,$t->aLangs);?>
        </div>
    </td>
    <td><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'formatDate2Pretty'))) echo htmlspecialchars($t->formatDate2Pretty($oContent->lastUpdated));?></td>
    <td><a href="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("","cmsactivity","simplecms",$t->aContents,"userId|createdById",$key));?>"><?php echo htmlspecialchars($oContent->createdByName);?></a></td>
    <td class="status-cell context-menu-wrapper">

        <div class="triggers floatbox">
            <span id="status-current_<?php echo htmlspecialchars($oContent->id);?>" class="status-current status-<?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo htmlspecialchars($this->plugin("getArrayValueByKey",$t->aStatusesData,$oContent->status,"className"));?>"></span>
            <a id="status-trigger-open_<?php echo htmlspecialchars($oContent->id);?>" class="trigger-open status-trigger-open" href="#" title="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("show status list"));?>">
                <img src="<?php echo htmlspecialchars($t->webRoot);?>/themes/<?php echo htmlspecialchars($t->theme);?>/images/icons/arrow_down.gif" alt="" /></a>
            <a id="status-trigger-close_<?php echo htmlspecialchars($oContent->id);?>" class="trigger-close status-trigger-close" href="#" title="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("hide status list"));?>" style="display: none">
                <img src="<?php echo htmlspecialchars($t->webRoot);?>/themes/<?php echo htmlspecialchars($t->theme);?>/images/icons/arrow_up.gif" alt="" /></a>
        </div>
        <img class="ajaxLoader" src="<?php echo htmlspecialchars($t->webRoot);?>/themes/<?php echo htmlspecialchars($t->theme);?>/images/ajax-loader.gif" alt="" style="display: none;" />

        <div id="status-wrapper_<?php echo htmlspecialchars($oContent->id);?>" class="context-menu" style="display: none;">
            <ul class="status-container">
            <?php if ($this->options['strict'] || (is_array($t->aStatusesData)  || is_object($t->aStatusesData))) foreach($t->aStatusesData as $key => $aStatus) {?>
                <li class="<?php echo htmlspecialchars($aStatus['className']);?>">
                    <a href="#" sgl:status_id="<?php echo htmlspecialchars($aStatus['status_id']);?>" sgl:content_id="<?php echo htmlspecialchars($oContent->id);?>" sgl:lang_id="<?php echo htmlspecialchars($t->cLang);?>"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr($aStatus['name']));?></a>
                </li>
            <?php }?>
            </ul>
        </div>
    </td>
</tr><?php }?>
<?php if (!$t->aContents)  {?><tr>
    <td colspan="5">
        <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("no contents found"));?>
    </td>
</tr><?php }?>
