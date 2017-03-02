<!-- Filter -->
<div id="content-filter" class="block-helper block-icon block-icon-search block-item-list">
    <h2><a href="#"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("content filter (header)"));?></a></h2>
    <div class="inner">

        <?php 
if (!isset($this->elements['type']->attributes['value'])) {
    $this->elements['type']->attributes['value'] = '';
    $this->elements['type']->attributes['value'] .=  htmlspecialchars($t->type);
}
$_attributes_used = array('value');
echo $this->elements['type']->toHtml();
if (isset($_attributes_used)) {  foreach($_attributes_used as $_a) {
    unset($this->elements['type']->attributes[$_a]);
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
if (!isset($this->elements['lang']->attributes['value'])) {
    $this->elements['lang']->attributes['value'] = '';
    $this->elements['lang']->attributes['value'] .=  htmlspecialchars($t->cLang);
}
$_attributes_used = array('value');
echo $this->elements['lang']->toHtml();
if (isset($_attributes_used)) {  foreach($_attributes_used as $_a) {
    unset($this->elements['lang']->attributes[$_a]);
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
                <label for="filter_type"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("filter by type"));?></label>
                <select id="filter_type" name="type">
                    <?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo $this->plugin("generateFilterSelect",$t->aContentTypes,$t->type,"type",1);?>
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
                <select id="filter_lang" name="lang">
                    <?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo $this->plugin("generateFilterSelect",$t->aLangs,$t->cLang,"cLang");?>
                </select>
            </li>
            <li>
                <label for="filter_resPerPage"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("filter results per page"));?></label>
                <select id="filter_resPerPage" name="resPerPage">
                    <?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo $this->plugin("generateFilterSelect",$t->aResPerPage,$t->resPerPage,"resPerPage");?>
                </select>
            </li>
            <!--li>
                <label for="filter_field">{tr(#order by field#)}</label>
                <select id="filter_field" name="sortBy" flexy:ignore>
                    {this.plugin(#generateFilterSelect#,aSortFields,sortBy,#sortBy#):h}
                </select>
                <select name="sortOrder" flexy:ignore>
                    {this.plugin(#generateFilterSelect#,aSortOrder,sortOrder,#sortOrder#):h}
                </select>
            </li-->
        </ul>
    </div>
</div>
