<!--
  --  REMINDER : {id} is a shortcut for {oContentType.id} --
-->

<div class="contentType closed" id="contentType_<?php echo htmlspecialchars($t->id);?>">
    <div id="contentType_<?php echo htmlspecialchars($t->id);?>_view">
        <h2>
            <span id="contentType_<?php echo htmlspecialchars($t->id);?>_viewInfo" onclick="cms.contentType.toggleContentType(<?php echo htmlspecialchars($t->id);?>);"><?php echo htmlspecialchars($t->oContentType->typeName);?></span>&nbsp;
            <a class="action editLink" href="#" onclick="cms.contentType.editor.edit(<?php echo htmlspecialchars($t->id);?>);return false;">rename</a>
            <a class="action deleteLink" href="#" onclick="cms.contentType.deleteContentType(<?php echo htmlspecialchars($t->id);?>);return false;">
                <img src="<?php echo htmlspecialchars($t->webRoot);?>/cms/images/trash.gif" alt="delete" />
            </a>
        </h2>
    </div>
    <div id="contentType_<?php echo htmlspecialchars($t->id);?>_edit" style="display:none">
         <!-- CONTENT TYPE EDIT FORM -->
        <form class="contentTypeEditForm" id="contentType_<?php echo htmlspecialchars($t->id);?>_editForm">
            <input type="hidden" name="contentTypeId" value="<?php echo htmlspecialchars($t->id);?>" />

            <div id="contentType_<?php echo htmlspecialchars($t->id);?>_errorMsg" style="display:none"></div>
            <input type="text" class="contentType_name" name="contentType[name]" id="contentType_<?php echo htmlspecialchars($t->id);?>_name" value="<?php echo htmlspecialchars($t->oContentType->typeName);?>" />
            <p class="submit">
                <input type="submit" value="Save name" />&nbsp;
                <span>or</span>&nbsp;
                <a href="#" class="action" onclick="cms.contentType.editor.cancel(<?php echo htmlspecialchars($t->id);?>);return false;">Cancel</a>
            </p>
        </form>
    </div>
</div>