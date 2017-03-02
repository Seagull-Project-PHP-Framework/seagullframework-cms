<!-- Filter -->
<div id="content-filter" class="block-helper block-icon block-icon-search block-item-list widget-item" sgl:widget="admin_dashboard-content_filter">
    <h2 class="widget-header"><a href="#"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("content filter (header)"));?></a></h2>
    <form class="inner" action="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("","cmscontent","simplecms"));?>" method="post">
        <ul>
            <li>
                <label for="filter_type"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("filter by type"));?></label>
                <select id="filter_type" name="type">
                    <option value="all"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("all"));?></option>
                    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateSelect'))) echo $t->generateSelect($t->aContentTypes);?>
                </select>                       
            </li>
            <li>
                <label for="filter_status"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("filter by status"));?></label>
                <select id="filter_status" name="status">
                    <option value="all"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("all"));?></option>                
                    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateSelect'))) echo $t->generateSelect($t->aStatuses);?>
                </select> 
            </li>
            <li>
                <label for="filter_lang"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("filter by lang"));?></label>
                <select id="filter_lang" name="cLang">
                    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateSelect'))) echo $t->generateSelect($t->aLangs);?>
                </select>                 
            </li>
        </ul>
        <p>
            <input class="submit" type="submit" name="isSubmitted" value="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("search (action)"));?>" />
        </p>
    </form>
</div>

