<!-- Filter -->
<div id="route-filter" class="block-helper block-icon block-icon-search block-item-list">
    <h2><a href="#"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("url filter (header)"));?></a></h2>
    <form id="frmRouteFilter" action="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("getRoutes","page","page"));?>" method="get">
    <div class="inner">
        <?php 
if (!isset($this->elements['pageID']->attributes['value'])) {
    $this->elements['pageID']->attributes['value'] = '';
    $this->elements['pageID']->attributes['value'] .=  htmlspecialchars($t->pageID);
}
$_attributes_used = array('value');
echo $this->elements['pageID']->toHtml();
if (isset($_attributes_used)) {  foreach($_attributes_used as $_a) {
    unset($this->elements['pageID']->attributes[$_a]);
}}
?>
        <?php 
if (!isset($this->elements['filter[sortBy]']->attributes['value'])) {
    $this->elements['filter[sortBy]']->attributes['value'] = '';
    $this->elements['filter[sortBy]']->attributes['value'] .=  htmlspecialchars($t->sortBy);
}
$_attributes_used = array('value');
echo $this->elements['filter[sortBy]']->toHtml();
if (isset($_attributes_used)) {  foreach($_attributes_used as $_a) {
    unset($this->elements['filter[sortBy]']->attributes[$_a]);
}}
?>
        <?php 
if (!isset($this->elements['filter[sortOrder]']->attributes['value'])) {
    $this->elements['filter[sortOrder]']->attributes['value'] = '';
    $this->elements['filter[sortOrder]']->attributes['value'] .=  htmlspecialchars($t->sortOrder);
}
$_attributes_used = array('value');
echo $this->elements['filter[sortOrder]']->toHtml();
if (isset($_attributes_used)) {  foreach($_attributes_used as $_a) {
    unset($this->elements['filter[sortOrder]']->attributes[$_a]);
}}
?>
        <ul>
            <li>
                <label for="filter_url"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("filter by url"));?></label>
                <input id="filter_url" name="filter[route]" value="<?php echo htmlspecialchars($t->filter['route']);?>">
            </li>
            <li>
                <label for="filter_site"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("filter by site"));?></label>
                <select id="filter_site" name="filter[siteId]">
                    <option value=""><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("all"));?></option>
                    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateSelect'))) echo $t->generateSelect($t->aSites,$t->siteId);?>
                </select>
            </li>
            <li>
                <label for="filter_status"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("filter by status"));?></label>
                <select id="filter_status" name="filter[isActive]">
                    <option value=""><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("all"));?></option>
                    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateSelect'))) echo $t->generateSelect($t->aStatuses,$t->isActive);?>
                </select>
            </li>
            <li>
                <label for="filter_resPerPage"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("filter results per page"));?></label>
                <select id="filter_resPerPage" name="filter[resPerPage]">
                    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateSelect'))) echo $t->generateSelect($t->aResPerPage,$t->resPerPage);?>
                </select>
            </li>
            <li>
                <input type="submit" value="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("apply"));?>" />
            </li>
        </ul>
    </div>
    </form>
</div>
