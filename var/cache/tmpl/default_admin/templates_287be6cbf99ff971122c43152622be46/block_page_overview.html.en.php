<div id="page-overview" class="block-helper block-icon block-icon-note block-overview">
    <h2><a href="#"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("page overview (header)"));?></a></h2>
    <div class="inner fieldsetlike">
        <dl class="clearfix">
            <!--dt>{tr(#page site#)}</dt>
            <dd>{summarise(oSite.name,#17#,#1#)}</dd>
            <dt>{tr(#page total comments#)}</dt>
            <dd>0</dd-->
            <dt><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("page author"));?></dt>
            <dd><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'summarise'))) echo htmlspecialchars($t->summarise($t->oCreatedByUser->username,17,1));?></dd>
            <dt><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("page editor"));?></dt>
            <dd><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'summarise'))) echo htmlspecialchars($t->summarise($t->oUpdatedByUser->username,17,1));?></dd>
            <dt><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("page creation date"));?></dt>
            <dd><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'formatDateTime2Pretty'))) echo htmlspecialchars($t->formatDateTime2Pretty($t->oPage->date_created));?></dd>
            <dt><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("page modification date"));?></dt>
            <dd><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'formatDateTime2Pretty'))) echo htmlspecialchars($t->formatDateTime2Pretty($t->oPage->last_updated));?></dd>
        </dl>
    </div>
</div>
