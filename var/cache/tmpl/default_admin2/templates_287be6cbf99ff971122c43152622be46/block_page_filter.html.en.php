<!-- Filter -->
<div id="page-filter" class="block-helper block-icon block-icon-search block-item-list">
    <h2><a href="#"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("page filter (header)"));?></a></h2>
    <div class="inner">

        <?php 
if (!isset($this->elements['siteId']->attributes['value'])) {
    $this->elements['siteId']->attributes['value'] = '';
    $this->elements['siteId']->attributes['value'] .=  htmlspecialchars($t->siteId);
}
$_attributes_used = array('value');
echo $this->elements['siteId']->toHtml();
if (isset($_attributes_used)) {  foreach($_attributes_used as $_a) {
    unset($this->elements['siteId']->attributes[$_a]);
}}
?>
        <?php 
if (!isset($this->elements['parentId']->attributes['value'])) {
    $this->elements['parentId']->attributes['value'] = '';
    $this->elements['parentId']->attributes['value'] .=  htmlspecialchars($t->parentId);
}
$_attributes_used = array('value');
echo $this->elements['parentId']->toHtml();
if (isset($_attributes_used)) {  foreach($_attributes_used as $_a) {
    unset($this->elements['parentId']->attributes[$_a]);
}}
?>
        <?php 
if (!isset($this->elements['langId']->attributes['value'])) {
    $this->elements['langId']->attributes['value'] = '';
    $this->elements['langId']->attributes['value'] .=  htmlspecialchars($t->langId);
}
$_attributes_used = array('value');
echo $this->elements['langId']->toHtml();
if (isset($_attributes_used)) {  foreach($_attributes_used as $_a) {
    unset($this->elements['langId']->attributes[$_a]);
}}
?>
        <?php 
if (!isset($this->elements['status']->attributes['value'])) {
    $this->elements['status']->attributes['value'] = '';
    $this->elements['status']->attributes['value'] .=  htmlspecialchars($t->status);
}
$_attributes_used = array('value');
echo $this->elements['status']->toHtml();
if (isset($_attributes_used)) {  foreach($_attributes_used as $_a) {
    unset($this->elements['status']->attributes[$_a]);
}}
?>
        <?php 
if (!isset($this->elements['resPerPage']->attributes['value'])) {
    $this->elements['resPerPage']->attributes['value'] = '';
    $this->elements['resPerPage']->attributes['value'] .=  htmlspecialchars($t->resPerPage);
}
$_attributes_used = array('value');
echo $this->elements['resPerPage']->toHtml();
if (isset($_attributes_used)) {  foreach($_attributes_used as $_a) {
    unset($this->elements['resPerPage']->attributes[$_a]);
}}
?>
        <?php 
if (!isset($this->elements['sortBy']->attributes['value'])) {
    $this->elements['sortBy']->attributes['value'] = '';
    $this->elements['sortBy']->attributes['value'] .=  htmlspecialchars($t->sortBy);
}
$_attributes_used = array('value');
echo $this->elements['sortBy']->toHtml();
if (isset($_attributes_used)) {  foreach($_attributes_used as $_a) {
    unset($this->elements['sortBy']->attributes[$_a]);
}}
?>
        <?php 
if (!isset($this->elements['sortOrder']->attributes['value'])) {
    $this->elements['sortOrder']->attributes['value'] = '';
    $this->elements['sortOrder']->attributes['value'] .=  htmlspecialchars($t->sortOrder);
}
$_attributes_used = array('value');
echo $this->elements['sortOrder']->toHtml();
if (isset($_attributes_used)) {  foreach($_attributes_used as $_a) {
    unset($this->elements['sortOrder']->attributes[$_a]);
}}
?>

        <ul>
            <li>
                <label for="filter_site"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("filter by site"));?></label>
                <select id="filter_site" name="siteId">
                    <?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo $this->plugin("generateSiteFilterSelect",$t->aSites,$t->siteId);?>
                </select>
            </li>
            <li>
                <label for="filter_parent"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("filter by parent"));?></label>
                <select id="filter_parent" name="parentId">
                    <?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo $this->plugin("renderPageFilterSelect",$t->aTree,$t->parentId);?>
                </select>
            </li>
            <li>
                <label for="filter_status"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("filter by status"));?></label>
                <select id="filter_status" name="status">
                    <?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo $this->plugin("generateFilterSelect",$t->aStatuses,$t->status,"status",1);?>
                </select>
            </li>
            <li>
                <label for="filter_lang"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("filter by lang"));?></label>
                <select id="filter_lang" name="langId">
                    <?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo $this->plugin("generateFilterSelect",$t->aLangs,$t->langId,"langId");?>
                </select>
            </li>
            <li>
                <label for="filter_resPerPage"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("filter results per page"));?></label>
                <select id="filter_resPerPage" name="resPerPage">
                    <?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo $this->plugin("generateFilterSelect",$t->aResPerPage,$t->resPerPage,"resPerPage");?>
                </select>
            </li>
        </ul>
    </div>
</div>
