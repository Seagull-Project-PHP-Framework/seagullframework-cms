<div id="media-overview" class="block-helper block-icon block-icon-note block-overview">
    <h2><a href="#"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("media overview (header)"));?></a></h2>
    <div class="inner fieldsetlike">
        <dl class="clearfix">
            <dt><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("file size"));?></dt>
            <dd><?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo htmlspecialchars($this->plugin("formatFileSize",$t->oMedia->file_size));?> </dd>
            <dt><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("media uploader"));?></dt>
            <dd><?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo htmlspecialchars($this->plugin("getUserFullName",$t->oMedia->created_by));?></dd>
            <dt><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("media modifier"));?></dt>
            <dd><?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo htmlspecialchars($this->plugin("getUserFullName",$t->oMedia->updated_by));?></dd>
            <dt><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("media creation date"));?></dt>
            <dd><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'formatDateTime2Pretty'))) echo htmlspecialchars($t->formatDateTime2Pretty($t->oMedia->date_created));?></dd>
            <dt><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("media modification date"));?></dt>
            <dd><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'formatDateTime2Pretty'))) echo htmlspecialchars($t->formatDateTime2Pretty($t->oMedia->last_updated));?></dd>
        </dl>
    </div>
</div>
