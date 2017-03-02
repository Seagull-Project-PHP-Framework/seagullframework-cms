<ul class="simpleTree">
    <li class="root">
        <span class="text"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("category tree"));?></span>
        <?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo $this->plugin("renderCategoriesNav",$t->aCats);?>
    </li>
</ul>
