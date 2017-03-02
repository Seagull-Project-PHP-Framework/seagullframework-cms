<div id="content-activity-filter" class="block-helper block-icon block-icon-search block-input widget-item" sgl:widget="admin_dashboard-content_activity_search">
    <h2 class="widget-header"><a href="#"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("search user (header)"));?></a></h2>
    <form class="inner" method="post" action="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("search","cmsactivity","simplecms"));?>">
        <fieldset>
            <ol class="clearfix">
                <li>
                    <div>
                        <input class="text" type="text" name="search" value="" />
                        <!--input class="button" type="submit" name="submitted" value="{tr(#search user (action)#)}" /-->
                        <p class="comment"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("start typing to get user list"));?></p>
                    </div>                
                </li>
            </ol>
        </fieldset>
    </form>
</div>
