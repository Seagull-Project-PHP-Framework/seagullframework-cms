<?php if ($this->options['strict'] || (is_array($t->aPagedData['data'])  || is_object($t->aPagedData['data']))) foreach($t->aPagedData['data'] as $key => $oRoute) {?><tr <?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo $this->plugin("getContentRowStyle");?>>
    <input type="hidden" name="routeId" value="<?php echo htmlspecialchars($oRoute->route_id);?>" />
    <td>
        <?php 
if (!isset($this->elements['tmpId1']->attributes['value'])) {
    $this->elements['tmpId1']->attributes['value'] = '';
    $this->elements['tmpId1']->attributes['value'] .=  htmlspecialchars($oRoute->route_id);
}
$_attributes_used = array('value');

                $element = $this->elements['tmpId1'];
                if (isset($this->elements['frmAction[]'])) {
                    $element = $this->mergeElement($element,$this->elements['frmAction[]']);
                }
                echo  $element->toHtml();
if (isset($_attributes_used)) {  foreach($_attributes_used as $_a) {
    unset($this->elements['tmpId1']->attributes[$_a]);
}}
?>
    </td>
    <td>
        <a href="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("edit","route","page",$t->aPagedData['data'],"routeId|route_id",$key));?>"><?php echo htmlspecialchars($oRoute->route);?></a>
        
    </td>
    <td>
        <?php if ($oRoute->description)  {?><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'summarise'))) echo htmlspecialchars($t->summarise($oRoute->description,40,1));?><?php } else {?><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("no description"));?><?php }?>        
    </td>
    <td>
        <?php if ($oRoute->is_active)  {?>
        <input type="checkbox" name="isActive" checked="checked" />
        <?php } else {?>
        <input type="checkbox" name="isActive" />
        <?php }?>
    </td>
</tr><?php }?>
<?php if (!$t->aPagedData['data'])  {?><tr>
    <td colspan="4">
        <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("no urls found"));?>
    </td>
</tr><?php }?>
