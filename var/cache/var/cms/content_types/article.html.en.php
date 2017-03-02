<div class="article">
    <div class="field">
        <h3><?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo htmlspecialchars($this->plugin("renderAttributeAlias",$t->oContent,"introduction"));?></h3>
        <p><?php echo htmlspecialchars($t->oContent->introduction);?></p>
    </div>
    <div class="field">
        <h3><?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo htmlspecialchars($this->plugin("renderAttributeAlias",$t->oContent,"body"));?></h3>
        <?php echo $t->oContent->body;?>
    </div>
    <div class="field">
        <h3><?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo htmlspecialchars($this->plugin("renderAttributeAlias",$t->oContent,"isPublished"));?></h3>
        <p><?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo htmlspecialchars($this->plugin("renderListAttribValue",$t->oContent,"isPublished"));?></p>
    </div>
</div>