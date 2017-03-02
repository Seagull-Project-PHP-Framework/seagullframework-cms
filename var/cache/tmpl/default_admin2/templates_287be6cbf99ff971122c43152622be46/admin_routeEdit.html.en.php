<form id="route-container" class="columns layout-2cols sgl-form" method="post" action="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("updateRoute","page","page"));?>">

    <div class="c75l item">
        <div class="layout-inner">
            <div style="margin-bottom:20px">
            <p style="font-size: 1.2em"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("enter route"));?></p>
            <input name="route[path]" value="<?php echo htmlspecialchars($t->route->path);?>" class="text path" type="text" />
            <button class="process-path"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("process (button)"));?></button>
            </div>

            <fieldset style="display: none;">
                <input name="routeId" value="<?php echo htmlspecialchars($t->route->route_id);?>" type="hidden" />
                <input name="route[site_id]" value="<?php echo htmlspecialchars($t->route->site_id);?>" type="hidden" />
                <input name="route[page_id]" value="<?php echo htmlspecialchars($t->route->page_id);?>" type="hidden" />
                <input name="route[is_active]" value="<?php echo htmlspecialchars($t->route->is_active);?>" type="hidden" />
                
                <input type="hidden" name="redir" value="<?php echo htmlspecialchars($t->redir);?>" />
            </fieldset>

	        <fieldset class="fields">
	             <legend><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("url params"));?></legend>
                 <ol class="clearfix custom-values">
                    <?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo $this->plugin("renderRoutePathParameters",$t->route);?>
	             </ol>
	        </fieldset>
	        <fieldset class="fields">
	             <legend><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("linked module"));?></legend>
                 <ol id="module-matrix" class="clearfix default-values">
                     <li class="required">
	                     <label><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("module","ucfirst"));?></label>
	                     <div>
	                     <select name="route[moduleName]">
	                     <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateSelect'))) echo $t->generateSelect($t->aModules,$t->route->moduleName);?>
	                     </select>
	                     </div>
                     </li>
                     <li class="required">
	                     <label><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("manager","ucfirst"));?></label>
	                     <div>
	                     <select name="route[controller]">
	                     <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateSelect'))) echo $t->generateSelect($t->aManagers,$t->route->controller);?>
	                     </select>
	                     </div>
                     </li>
                     <li class="required">
	                     <label><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("action","ucfirst"));?></label>
	                     <div>
	                     <select name="route[action]">
	                     <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'generateSelect'))) echo $t->generateSelect($t->aActions,$t->route->action);?>
	                     </select>
	                     </div>
                     </li>
                     <?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo $this->plugin("renderRouteDefaultParameters",$t->route);?>
  	             </ol>
                 <hr />
	             <ol>
                     <li>
                         <label><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("add parameter"));?></label>
                         <div>
                            <input name="parameterName" class="text" type="text" style="width: 100px;" />
                            <button class="add-parameter"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("add"));?></button>
                         </div>
                     </li>
                 </ol>

	        </fieldset>
	        <fieldset class="fields">
	             <legend><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("extra"));?></legend>
                 <ol class="clearfix">
	             <li>
		             <label><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("description"));?></label>
		             <div>
		             <textarea name="route[description]" rows="5" cols="40"><?php echo $t->route->description;?></textarea>
		             </div>
	             </li>
	             </ol>
	        </fieldset>
            <p class="fieldIndent">
                <span class="triggers">
                    <?php if ($t->isEdit)  {?>
                    <input class="button" type="submit" name="submitted" value="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("update url (button)"));?>" />
                    &nbsp;&nbsp;
                    <input class="button" type="submit" name="submittedContinue" value="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("update url and continue (button)"));?>" />
                    <?php } else {?>
                    <input class="button" type="submit" name="submitted" value="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("add url (button)"));?>" />
                    &nbsp;&nbsp;
                    <input class="button" type="submit" name="submittedContinue" value="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("add url and continue (button)"));?>" />
                    <?php }?>
                    &nbsp;
                    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("or"));?>
                    &nbsp;
                    <?php if ($t->redir)  {?><a href="<?php echo htmlspecialchars($t->redir);?>"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("cancel"));?></a><?php }?>
                    <?php if (!$t->redir)  {?><a href="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("","route","page"));?>"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'tr'))) echo htmlspecialchars($t->tr("cancel"));?></a><?php }?>
                </span>
                <img class="ajaxLoader" src="<?php echo htmlspecialchars($t->imagesDir);?>/ajax-loader.gif" alt="" style="display: none;" />
            </p>
        </div>
    </div><!-- item -->

    <div class="c25r">

    </div>

</form><!-- route-container -->
<style>
span.ui-icon {
float:left;
margin:0 4px;
}
</style>