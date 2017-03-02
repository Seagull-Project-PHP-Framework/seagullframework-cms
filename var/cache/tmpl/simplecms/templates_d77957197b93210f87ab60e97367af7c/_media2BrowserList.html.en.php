        <?php if ($this->options['strict'] || (is_array($t->aPagedData['data'])  || is_object($t->aPagedData['data']))) foreach($t->aPagedData['data'] as $k => $oMedia) {?><li>
            <?php 
if (!isset($this->elements['mediaId']->attributes['value'])) {
    $this->elements['mediaId']->attributes['value'] = '';
    $this->elements['mediaId']->attributes['value'] .=  htmlspecialchars($oMedia->media_id);
}
$_attributes_used = array('value');
echo $this->elements['mediaId']->toHtml();
if (isset($_attributes_used)) {  foreach($_attributes_used as $_a) {
    unset($this->elements['mediaId']->attributes[$_a]);
}}
?>
	        <?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) if ($this->plugin("isImageMimeType",$oMedia->mime_type)) { ?>
	        <a class="preview" href="<?php echo htmlspecialchars($t->webRoot);?>/media2/img.php?path=<?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo htmlspecialchars($this->plugin("getImagePath",$oMedia));?>" title="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("click to enlarge"));?>">
	            <img class="image" src="<?php echo htmlspecialchars($t->webRoot);?>/media2/img.php?path=<?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo htmlspecialchars($this->plugin("getImagePath",$oMedia,"small"));?>" alt="" /></a>
	        <?php } else {?>
	        <a href="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("download","media2","media2",$t->aMedias,"mediaId|media_id",$k));?>" title="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("click to download"));?>">
	            <img class="image" src="<?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo htmlspecialchars($this->plugin("getIconByMimeType",$oMedia->mime_type));?>" alt="" /></a>
	        <?php }?>
	        <span><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'summarise'))) echo htmlspecialchars($t->summarise($oMedia->name,15,1));?></span>
        </li><?php }?>
