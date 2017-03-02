<h1 class="pageTitle">
        <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate($t->pageTitle));?> <?php if ($t->mode)  {?>:: <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate($t->mode));?><?php }?>
</h1>
<div class="message"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'msgGet'))) echo htmlspecialchars($t->msgGet());?></div>
<table class="wide">
    <?php if ($t->wizardData)  {?>
    <tr>
        <td align="left">
            <table border=0>
                <thead><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Verify data you entered"));?></thead>
                    <?php if ($this->options['strict'] || (is_array($t->wizardData)  || is_object($t->wizardData))) foreach($t->wizardData as $key => $val) {?><tr>
                        <th><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'humanise'))) echo htmlspecialchars($t->humanise($key));?></th><td><?php echo htmlspecialchars($val);?></td>
                    </tr><?php }?>
            </table>
        </td>
    </tr>
    <?php }?>
</table>
<?php echo $t->wizardOutput;?>
