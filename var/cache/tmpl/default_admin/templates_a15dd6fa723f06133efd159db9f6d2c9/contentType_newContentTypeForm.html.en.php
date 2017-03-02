<!-- CONTENT TYPE ADD FORM -->
<form class="contentTypeEditForm" id="newContentTypeForm" style="display:none">
    <div id="newContentType_errorMsg" style="display:none"></div>
    <fieldset>
    <input type="text" class="contentType_name" name="contentType[name]" id="contentType_name" value="" />
    <p class="submit">
        <p class="help">After you create this content type, you can add as many attributes as you want.</p>
        <input type="submit" value="Create" />&nbsp;
        <span>or</span>&nbsp;
        <a href="#" class="action" onclick="cms.contentType.creator.cancel();return false;"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Cancel"));?></a>
    </p>
    </fieldset>
</form>