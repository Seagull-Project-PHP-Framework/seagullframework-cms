<script type="text/javascript">
function switchDisplayType()
{
    var status = document.getElementById('displayDeRegisteredModules').checked;
    if (status) {
        var url = document.getElementById('linkDeregisteredModules').innerHTML;
    } else {
        var url = document.getElementById('linkRegisteredModules').innerHTML;
    }
    document.location.href = unescape(url);
}
</script>

<div id="manager-actions">
    <span><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Action"));?>: &nbsp;</span>
    <a class="action reorder" href="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("list","module","default"));?>"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Refresh Module Listing"));?></a>
</div>
<div id="content">
    <div id="content-header">
        <h2><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate($t->pageTitle));?></h2>
        <div class="message"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'msgGet'))) echo htmlspecialchars($t->msgGet());?></div>
    </div>

    <form style="float: none;" id="moduleSwitcher" action="">
        <h3><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Module list"));?></h3>
        <p><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Below is a list"));?></p>
        <fieldset class="hide">
            <p id="linkRegisteredModules"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo urlencode($t->makeUrl("","module","default","","displayDeRegisteredModules|0"));?></p>
            <p id="linkDeregisteredModules"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo urlencode($t->makeUrl("","module","default","","displayDeRegisteredModules|1"));?></p>
        </fieldset>
        <fieldset class="noBorder">
            <input id="displayDeRegisteredModules" type="checkbox" name="displayDeRegisteredModules" onclick="switchDisplayType()" <?php echo htmlspecialchars($t->deRegisteredModulesChecked);?> />
            <label for="displayDeRegisteredModules"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("show uninstalled modules"));?></label>
        </fieldset>
    </form>

    <table class="full">
        <thead>
            <tr class="infos">
                <td class="right" colspan="8">&nbsp;</td>
            </tr>
            <tr>
                <th width="5%">&nbsp;</th><!--IMAGE-->
                <th width="5%" class="left"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Title"));?></th>
                <th width="10%"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("action","ucfirst"));?></th>
                <th width="10%"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Reload"));?></th>
                <th width="55%" class="left"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Description"));?></th>
                <th width="5%"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Maintainer"));?></th>
                <th width="5%"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("License"));?></th>
                <th width="5%"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("State"));?></th>
            </tr>
        </thead>

        <tbody>
        <?php if ($t->aModules)  {?>
            <?php if ($this->options['strict'] || (is_array($t->aModules)  || is_object($t->aModules))) foreach($t->aModules as $key => $oValue) {?><tr class="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'switchRowClass'))) echo htmlspecialchars($t->switchRowClass());?>">
                <?php if ($oValue->isInstalled)  {?>

                <!--MODULE IMAGE-->

                <td><img src="<?php echo htmlspecialchars($t->webRoot);?>/themes/<?php echo htmlspecialchars($t->theme);?>/images/16/module_<?php echo htmlspecialchars($oValue->name);?>.gif" alt="" /></td>

                <!--EDIT MODULE LINK-->
                <td class="left">
                    <a href="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("","moduleconfig","default",$t->aModules,"frmModule|name",$key));?>" title="edit config for <?php echo htmlspecialchars($oValue->title);?>"><?php echo htmlspecialchars($oValue->title);?></a>
                </td>

                <!--ACTION-->
                <td>
                    <a href="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("uninstall","module","default",$t->aModules,"frmModuleId|module_id",$key));?>" title="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("uninstall"));?> <?php echo htmlspecialchars($oValue->title);?>" onclick="return confirmDeleteWithMsg('<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("module deregister msg"));?>')">
                        <img src="<?php echo htmlspecialchars($t->webRoot);?>/themes/<?php echo htmlspecialchars($t->theme);?>/images/16/edit_remove.png" alt="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("uninstall"));?>" />
                    </a>
                </td>

                <!--RELOAD -->
                <td>
                    <a href="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("reinstall","module","default",$t->aModules,"frmModuleId|module_id",$key));?>" title="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("reload"));?> <?php echo htmlspecialchars($oValue->title);?>" onclick="return confirmDeleteWithMsg('<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("module reload msg"));?>')">
                        <img src="<?php echo htmlspecialchars($t->webRoot);?>/themes/<?php echo htmlspecialchars($t->theme);?>/images/22/action_reorder.gif" alt="" />
                </td>

                <!--DESCRIPTION-->
                <td class="left desc"><?php echo htmlspecialchars($oValue->description);?></td>

                <!--MAINTAINER-->
                <td class="left desc"><?php echo htmlspecialchars($oValue->maintainers);?></td>

                <!--LICENSE-->
                <td class="left desc"><?php echo htmlspecialchars($oValue->license);?></td>

                <!--STATE-->
                <td class="left desc"><?php echo htmlspecialchars($oValue->state);?></td>

                <?php } else {?>

                <!--MODULE IMAGE-->
                <td><img src="<?php echo htmlspecialchars($t->webRoot);?>/themes/<?php echo htmlspecialchars($t->theme);?>/images/16/module_<?php echo htmlspecialchars($oValue->name);?>_disabled.gif" alt="" /></td>

                <!--EDIT MODULE LINK-->
                <td class="left">
                    <a class="disabled" href="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("","moduleconfig","default",$t->aModules,"frmModule|name",$key));?>"><?php echo htmlspecialchars($oValue->title);?></a>
                </td>

                <!--ACTION-->
                <td>
                    <!--INSTALL-->
                    <a href="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("install","module","default",$t->aModules,"frmModuleName|name",$key));?>" title="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("install"));?> <?php echo htmlspecialchars($oValue->title);?>">
                        <img src="<?php echo htmlspecialchars($t->webRoot);?>/themes/<?php echo htmlspecialchars($t->theme);?>/images/16/edit_add.png" alt="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("install"));?>" />
                    </a>
                    &nbsp;
                    <!--DELETE-->
                    <a href="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("delete","module","default",$t->aModules,"frmModuleName|name",$key));?>" title="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("remove"));?> <?php echo htmlspecialchars($oValue->title);?>" onclick="return confirmDeleteWithMsg('<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("module deletion msg"));?>')">
                        <img src="<?php echo htmlspecialchars($t->webRoot);?>/themes/<?php echo htmlspecialchars($t->theme);?>/images/16/action_disable.gif" alt="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("remove"));?>" />
                    </a>
                </td>

                <!--RELOAD -->
                <td>&nbsp;</td>

                <!--DESCRIPTION-->
                <td class="left desc disabled"><?php echo htmlspecialchars($oValue->description);?></td>

                <!--MAINTAINER-->
                <td class="left desc"><?php echo htmlspecialchars($oValue->maintainers);?></td>

                <!--LICENSE-->
                <td class="left desc"><?php echo htmlspecialchars($oValue->license);?></td>

                <!--STATE-->
                <td class="left desc"><?php echo htmlspecialchars($oValue->state);?></td>
                <?php }?>
            </tr><?php }?>
        <?php } else {?>
            <tr>
                <td colspan="7"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("no results found"));?></td>
            </tr>
        <?php }?>
        </tbody>
    </table>
</div>
