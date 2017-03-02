<?php if ($t->aMedias)  {?><div id="dashboard-medialist" class="block-helper block-item-list widget-item" sgl:widget="admin_dashboard-media_list">
    <h2 class="widget-header"><a href="#"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("latest media (header)"));?></a></h2>
    <div class="inner">
        <ul>
            <?php if ($this->options['strict'] || (is_array($t->aMedias)  || is_object($t->aMedias))) foreach($t->aMedias as $k => $oMedia) {?><li>
                <!-- img src="{this.plugin(#getIconByMimeType#,oMedia.mime_type)}"
                     class="image" alt="" /-->
                <strong><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'formatDateTime2Pretty'))) echo htmlspecialchars($t->formatDateTime2Pretty($oMedia->date_created));?></strong>
                <a href="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("edit","media2","media2",$t->aMedias,"mediaId|media_id",$k));?>">
                    <?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo htmlspecialchars($this->plugin("getArrayValueByKey",$t->aMimeTypes,$oMedia->media_mime_id));?></a>
                (<?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo htmlspecialchars($this->plugin("formatFileSize",$oMedia->file_size));?>)
                    
                <!--
                {if:!oMedia.media_type_id}
                {tr(#default media type#)}
                {else:}
                {this.plugin(#getArrayValueByKey#,aMediaTypes,oMedia.media_type_id)}
                {end:}
                -->
                
            </li><?php }?>
        </ul>
        <p>
            <a href="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("","media2","media2"));?>"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("view all media"));?></a>
        </p>
    </div>
</div><?php }?>
