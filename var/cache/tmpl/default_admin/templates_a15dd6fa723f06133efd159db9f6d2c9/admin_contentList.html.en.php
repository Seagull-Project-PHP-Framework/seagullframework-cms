<div id="content">
    <div id="content-header">
        <h2><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate($t->pageTitle));?></h2>
        <div class="message" id="ajaxMessage"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'msgGet'))) echo htmlspecialchars($t->msgGet());?></div>
    </div>

    <div id="screenIntro">
    <?php if ($t->aContentTypes)  {?>
        <span><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("new content of type:","ucfirst"));?></span>
        <form id="frmNewContent" action="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("add","content","cms"));?>" method="post">
            <select name="frmContentTypeId">
                <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateSelect'))) echo $t->generateSelect($t->aContentTypes,$t->contentTypeId);?>
            </select>&nbsp;
            <input type="submit" value="Create" name="submit" />
        </form>
        <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'hasPerms'))) if ($t->hasPerms("CONTENTTYPEMGR_CMD_ADD")) { ?><p class="help">
            <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("You cannot find the appropriate content type?"));?>&nbsp;
            <!--NEW CONTENT TYPE-->
            <a class="actionAlt" href="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("","contenttype","cms"));?>"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("create a new content type"));?></a>
        </p><?php }?>
    <?php } else {?>
        <p>You still haven't created any content type. <a class="actionAlt" href="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("","contenttype","cms"));?>"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("create a new content type","ucfirst"));?></a> and come back to this page</p>
    <?php }?>
    </div>

    <div id="moduleToolbarHeader">
        <a class="actionAlt" id="contentListFilterShowAll" href="#" onclick="cms.content.filter.toggle()" style="display:none"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Show all"));?></a>
        <a class="actionAlt" id="contentListFilter" href="#" onclick="cms.content.filter.toggle()"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Filter the content"));?></a>
    </div>
    <div id="moduleToolbar">
        <form id="frmFilterContent" action="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("filter","content","cms"));?>" method="post">
            <!-- Content filter BOX -->
            <div class="cmsBox" id="contentFilterBox" style="display: none;">
                <input type="hidden" name="pageId" value="<?php echo htmlspecialchars($t->pageId);?>" id="contentFilter_pageId" />
                <input type="hidden" name="resPerPage" value="<?php echo htmlspecialchars($t->resPerPage);?>" id="contentFilter_resPerPage" />

                <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("By type"));?> :
                <select class="block" name="aFilter[typeId]" id="contentFilter_typeId">
                <option value="0">All</option>
                <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateSelect'))) echo $t->generateSelect($t->aContentTypes,$t->contentFilter['typeId']);?>
                </select>
                <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("By status"));?> :
                <select class="block" name="aFilter[status]" id="contentFilter_status">
                <option value="0">All</option>
                <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateSelect'))) echo $t->generateSelect($t->aStatusTypes,$t->contentFilter['status']);?>
                </select>
                <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("By category"));?> :
                <select name="aFilter[categoryId]" id="contentFilter_categoryId">
                <option value="0">All</option>
                <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateSelect'))) echo $t->generateSelect($t->aElems,$t->contentFilter['categoryId']);?>
                </select>
                &nbsp;
                <input type="submit" value="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Filter"));?>" name="submit" />
            </div>
        </form>
    </div>

    <?php if ($t->aContents)  {?>

    <p id="contentList-pager"><?php echo $t->pageLinks;?></p>
    <form action="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl());?>" method="post" id="frmContentPager">
        <label for="pagerOptions">Results per page:</label>
        <select class="pagerOptions" id="pagerOptions" name="resPerPage" onchange="$('frmContentPager').submit()">
        <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateSelect'))) echo $t->generateSelect($t->aResPerPage,$t->resPerPage);?>
        </select>
    </form>

    <form method="post" action="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("delete","content","cms"));?>" id="contentList" name="contentList">
        <h3><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Content list"));?></h3>
        <fieldset class="noBorder">
            <table id="contentListTable" class="full tablesorter">
                <thead>
                    <?php 
$x = new HTML_Template_Flexy($this->options);
$x->compile('_contentListTableHead.html');
$_t = function_exists('clone') ? clone($t) : $t;
foreach(get_defined_vars()  as $k=>$v) {
    if ($k != 't') { $_t->$k = $v; }
}
$x->outputObject($_t, $this->elements);
?>
                </thead>

                <tbody id="contentList-items">

                    <?php 
$x = new HTML_Template_Flexy($this->options);
$x->compile('contentList_items.html');
$_t = function_exists('clone') ? clone($t) : $t;
foreach(get_defined_vars()  as $k=>$v) {
    if ($k != 't') { $_t->$k = $v; }
}
$x->outputObject($_t, $this->elements);
?>

                     <?php if (!$t->aContents)  {?><tr>
                        <td colspan="8"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("No content found"));?></td>
                    </tr><?php }?>
                </tbody>
                <tfoot>
                    <tr class="tfoot">
	                    <td colspan="99">
	                    <p>
	                    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("With selected content"));?> :
	                    <input class="sgl-button" type="submit" name="deleteButton" value="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("delete"));?>" onClick="return confirmSubmit('content', 'contentList')" />
	                    </p>
	                    </td>
                    </tr>
                </tfoot>
            </table>
        </fieldset>


    </form>
    <?php } else {?>
    <p>No contents were found. To create your first content, simply click on the "New content" button above.</p>
    <?php }?>
    <div id="ajaxIndicator" style="display:none; position:absolute; top:0; right:0">
        <span>Contacting server</span>
    </div>
	<div class="clear"></div>
</div>
<?php echo $t->scriptOpen;?>
$(document).ready(function(){
cms.content.init();
})
<?php echo $t->scriptClose;?>
