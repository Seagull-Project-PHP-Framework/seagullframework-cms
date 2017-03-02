<ul class="clearfix">
<?php if ($this->options['strict'] || (is_array($t->aSections)  || is_object($t->aSections))) foreach($t->aSections as $key => $section) {?>
    <?php if ($section['is_current'])  {?><li class="current">
        <a href="<?php echo htmlspecialchars($section['url']);?>"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr($section['title']));?></a>
    </li><?php }?>
    <?php if (!$section['is_current'])  {?><li>
        <a href="<?php echo htmlspecialchars($section['url']);?>"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr($section['title']));?></a>
    </li><?php }?>
<?php }?>
</ul>
