<div id="content-view" class="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'camelise'))) echo htmlspecialchars($t->camelise($t->oContent->typeName));?>">

    <!--h1>{oContent.typeName}</h1-->

    <?php if ($this->options['strict'] || (is_array($t->oContent->aAttribs)  || is_object($t->oContent->aAttribs))) foreach($t->oContent->aAttribs as $oAttrib) {?><div class="field">
        <h3><?php echo htmlspecialchars($oAttrib->alias);?></h3>
        <?php if ($oAttrib->value)  {?>

        <!-- Rich text -->
        <?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) if ($this->plugin("attribTypeIsRichtext",$oAttrib->typeId)) { ?>
        <?php echo $oAttrib->value;?>
        <?php } else {?>

        <!-- Attribute list -->
        <?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) if ($this->plugin("attribTypeIsList",$oAttrib->typeId)) { ?>
        <p><?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo htmlspecialchars($this->plugin("renderListAttribValue",$t->oContent,$oAttrib->name));?></p>
        <?php } else {?>

        <!-- Media -->
        <?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) if ($this->plugin("attribTypeIsMedia",$oAttrib->typeId)) { ?>
        <p>
            <?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo $this->plugin("renderMediaFile",$oAttrib->value);?>
        </p>
        <?php } else {?>

        <!-- Date -->
        <?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) if ($this->plugin("attribTypeIsDate",$oAttrib->typeId)) { ?>
        <p>
            <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'formatDate2'))) echo htmlspecialchars($t->formatDate2($oAttrib->value));?>
        </p>
        <?php } else {?>

        <!-- Regular text/value -->
        <p><?php echo htmlspecialchars($oAttrib->value);?></p>
        <?php }?>
        <?php }?>
        <?php }?>
        <?php }?>

        <?php } else {?>
        <p><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("no value set"));?></p>
        <?php }?>
    </div><?php }?>

</div><!-- content-view -->
