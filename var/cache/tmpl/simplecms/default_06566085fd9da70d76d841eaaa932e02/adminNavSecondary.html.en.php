<?php if ($this->options['strict'] || (is_array($t->aSections)  || is_object($t->aSections))) foreach($t->aSections as $section) {?>

    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'isEqual'))) if ($t->isEqual($section['level'],0)) { ?>
        <?php if ($section['is_current'])  {?>
            <?php if ($section['sub'])  {?>
    <ul class="clearfix">
    <?php if ($section['sub'])  {?><?php if ($this->options['strict'] || (isset($t->renderer) && method_exists($t->renderer, 'toHtml'))) echo $t->renderer->toHtml($section['sub']);?><?php }?>
    </ul>
            <?php }?>
        <?php }?>
    <?php }?>

    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'isEqual'))) if ($t->isEqual($section['level'],1)) { ?>
        <?php if ($t->notfirst)  {?>
    <?php if ($section['is_current'])  {?><li>
        <a href="<?php echo htmlspecialchars($section['url']);?>"><strong><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr($section['title']));?></strong></a>
    </li><?php }?>
    <?php if (!$section['is_current'])  {?><li>
        <a href="<?php echo htmlspecialchars($section['url']);?>"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr($section['title']));?></a>
    </li><?php }?>
        <?php } else {?>
    <?php if ($section['is_current'])  {?><li class="first">
        <a href="<?php echo htmlspecialchars($section['url']);?>"><strong><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr($section['title']));?></strong></a>
    </li><?php }?>
    <?php if (!$section['is_current'])  {?><li class="first">
        <a href="<?php echo htmlspecialchars($section['url']);?>"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr($section['title']));?></a>
    </li><?php }?>
        <?php }?>
        <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'assign'))) echo htmlspecialchars($t->assign($t->notFirst,1));?>
    <?php }?>

<?php }?>
