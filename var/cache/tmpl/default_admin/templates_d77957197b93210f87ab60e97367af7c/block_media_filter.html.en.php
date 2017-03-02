<!-- Filter -->
<div id="media-filter" class="block-helper block-icon block-icon-search block-item-list">
    <h2><a href="#"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("media filter (header)"));?></a></h2>
    <div class="inner">
        <ul>
            <li>
                <label for="filter_mime"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("filter by mime type"));?></label>
                <select id="filter_mime" name="media_mime">
                    <?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo $this->plugin("generateFilterSelect",$t->aMimeTypes,$t->mimeTypeId,"mimeTypeId",1);?>
                </select>                       
            </li>
            <li>
                <label for="filter_type"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("filter by media type"));?></label>
                <select id="filter_type" name="media_type">
                    <?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo $this->plugin("generateFilterSelect",$t->aMediaTypes,$t->mediaTypeId,"mediaTypeId",1);?>
                </select> 
            </li>
        </ul>
    </div>
</div>
