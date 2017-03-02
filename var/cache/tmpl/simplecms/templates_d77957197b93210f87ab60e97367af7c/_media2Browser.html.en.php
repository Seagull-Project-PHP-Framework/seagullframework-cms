<div id="media-manager-tabs">
    <ul>
        <li><a href="#media-browser"><span><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("browse"));?></span></a></li>
        <li><a href="#media-uploader"><span><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("upload"));?></span></a></li>
    </ul>
    <a href="#" class="dialog-close ui-dialog-titlebar-close ui-corner-all" role="button" unselectable="on" style="-moz-user-select: none;"><span class="ui-icon ui-icon-closethick" unselectable="on" style="-moz-user-select: none;">close</span></a>    
    <div id="media-browser">
		<div id="media-filter" class="clearfix">
		    <!-- Filter -->
		    <form id="frmMediaFilter" action="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("getMediaBrowserView","media2","media2"));?>" method="get">
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
			    <label for="filter_mime"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("filter by mime type"));?></label>
			    <select id="filter_mime" name="filter[mimeTypeId]">
			        <option value=""><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("all"));?></option>
			        <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateSelect'))) echo $t->generateSelect($t->aMimeTypes,$t->mimeTypeId);?>
			    </select>
			    &nbsp;
			    <label for="filter_type"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("filter by media type"));?></label>
			    <select id="filter_type" name="filter[mediaTypeId]">
		            <option value=""><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("all"));?></option>
		            <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateSelect'))) echo $t->generateSelect($t->aMediaTypes,$t->mediaTypeId);?>
			    </select>
		    </form>
		</div>     
		<input id="resultsPerPage" type="hidden" value="<?php echo htmlspecialchars($t->resPerPage);?>" />
		<div id="media-list-container" class="clearfix">
		    <ul id="media-list" class="clearfix">
		        <?php 
$x = new HTML_Template_Flexy($this->options);
$x->compile('_media2BrowserList.html');
$_t = function_exists('clone') ? clone($t) : $t;
foreach(get_defined_vars()  as $k=>$v) {
    if ($k != 't') { $_t->$k = $v; }
}
$x->outputObject($_t, $this->elements);
?>
		    </ul>
	        <?php if (!$t->aPagedData['data'])  {?><p class="media-list-empty">
	            <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("no media found"));?>
	        </p><?php }?>
		    <div id="pager-links">
		        <?php echo $t->pagerLinks;?>
		    </div>
		</div>
		<div class="media-list-ajaxloading" style="display: none;">
		  <img class="ajaxLoader" src="<?php echo htmlspecialchars($t->imagesDir);?>/ajax-loader.gif" alt="" style="margin-top:170px" />
        </div>
        <div class="button-bar">     
			<button type="button" class="select-media ui-state-default ui-corner-all">Ok</button>
			<button type="button" class="dialog-close ui-state-default ui-corner-all">Cancel</button>
        </div>
	</div>
    <div id="media-uploader">
        <form id="frmMediaUpload">
        <?php if (!$t->aMediaTypes)  {?><fieldset class="hide">
            <input id="media_type" type="hidden" name="mediaTypeId" value="0" />
        </fieldset><?php }?>                   
        <?php if ($t->aMediaTypes)  {?><fieldset class="info">
            <ol class="clearfix">
                <li>
                    <label for="media_type"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("media type"));?></label>
                    <div>
                        <select id="media_type" name="mediaTypeId">
                            <option value=""><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("default media type"));?></option>
                            <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateSelect'))) echo $t->generateSelect($t->aMediaTypes);?>
                        </select>
                        <p class="comment"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("media type select hint"));?></p>
                    </div>
                </li>
            </ol>           
        </fieldset><?php }?>
        <fieldset class="fields">
            <ol class="clearfix">
                <li>
                    <label for="media_upload"><em>*</em> <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("select file to upload"));?></label>
                    <div>
                        <input id="media_upload" type="file" name="filedata" />
                    </div>
                </li>
            </ol>           
        </fieldset>
        <fieldset class="hide">
            <input id="media_redir" type="hidden" name="redir" value="<?php echo htmlspecialchars($t->redir);?>" />
        </fieldset>           
        <p class="fieldIndent">
            <span class="triggers">
                <input class="button" type="submit" name="submitted" value="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("upload media (button)"));?>" />
                &nbsp;
                <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("or"));?>
                &nbsp;
                <?php if ($t->redir)  {?><a href="<?php echo htmlspecialchars($t->redir);?>"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("cancel"));?></a><?php }?>
                <?php if (!$t->redir)  {?><a href="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("","media2","media2"));?>"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("cancel"));?></a><?php }?>
            </span>
            <img class="ajaxLoader" src="<?php echo htmlspecialchars($t->imagesDir);?>/ajax-loader.gif" alt="" style="display: none;" />
        </p>
        <p><em>*</em> <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("denotes required field"));?></p>
        </form>            
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    Media2.Browser.init();
});
</script>
<style>

</style>