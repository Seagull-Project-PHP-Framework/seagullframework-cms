<?php if ($this->options['strict'] || (is_array($t->aPages)  || is_object($t->aPages))) foreach($t->aPages as $key => $oPage) {?><tr <?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo $this->plugin("getContentRowStyle");?>>
    <td>
        <a href="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("edit","page","page",$t->aPages,"pageId|page_id||langId|language_id",$key));?>" title="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("edit page (action)"));?>">
            <?php if ($oPage->title)  {?><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'summarise'))) echo htmlspecialchars($t->summarise($oPage->title,40,1));?><?php } else {?><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("no page title translation"));?><?php }?></a>
    </td>
    <td class="lang-cell">
        <div class="floatbox">
            <?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo $this->plugin("getContentLangIcon",$oPage->language_id);?>
        </div>
    </td>
    <td><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'formatDate2Pretty'))) echo htmlspecialchars($t->formatDate2Pretty($oPage->last_updated));?></td>
    <td><?php echo htmlspecialchars($oPage->username);?></td>
    <td class="status-cell">
        <div class="floatbox">
        <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'isEqual'))) if ($t->isEqual($oPage->status,1)) { ?>
            <img src="<?php echo htmlspecialchars($t->webRoot);?>/themes/<?php echo htmlspecialchars($t->theme);?>/images/icons/flag_green.gif" alt="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("active (page status)"));?>" title="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("active (page status)"));?>" />
        <?php } else {?>
            <img src="<?php echo htmlspecialchars($t->webRoot);?>/themes/<?php echo htmlspecialchars($t->theme);?>/images/icons/flag_red.gif" alt="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("inactive (page status)"));?>" title="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("inactive (page status)"));?>" />
        <?php }?>
        </div>
    </td>
</tr><?php }?>
<?php if (!$t->aPages)  {?><tr>
    <td colspan="5">
        <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("no pages found"));?>
    </td>
</tr><?php }?>
