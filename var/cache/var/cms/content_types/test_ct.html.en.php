<div class="testCt">
    <div class="field">
        <h3><?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo htmlspecialchars($this->plugin("renderAttributeAlias",$t->oContent,"foo"));?></h3>
        <p><?php echo htmlspecialchars($t->oContent->foo);?></p>
    </div>
    <div class="field">
        <h3><?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo htmlspecialchars($this->plugin("renderAttributeAlias",$t->oContent,"bar"));?></h3>
        <p><?php echo htmlspecialchars($t->oContent->bar);?></p>
    </div>
    <div class="field">
        <h3><?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo htmlspecialchars($this->plugin("renderAttributeAlias",$t->oContent,"images"));?></h3>
        <p><?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo $this->plugin("renderMediaFile",$t->oContent->images);?></p>
    </div>
</div>
