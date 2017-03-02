/*
 * SGL ModuleMatrix widget
 *
 * Depends:
 *	ui.core.js
 */
(function($) {

$.widget("ui.modulematrix", {
	_init: function() {
		var self = this;
		
		$(this.options.selectors.module,this.element).addClass('mm-module');
		$(this.options.selectors.manager,this.element).addClass('mm-manager');
		$(this.options.selectors.action,this.element).addClass('mm-action');
		
	    $('.mm-module,.mm-manager',this.element).change(function(e) {
	    	self._load($(e.target));
	    	return false;
	    });
	    
	},
	_load: function($trigger) {
   		var self = this, moduleChange = false;
   		
   		if ($trigger.hasClass('mm-module')) {
   			moduleChange = true;
   		}
   		
   		var ajaxOptions = $.extend({
            data: {
	            module: $('.mm-module',self.element).val(),
	            manager: $('.mm-manager',self.element).val()
   			},
            success: function(r) {
              	if (moduleChange) {
               		$('.mm-manager',self.element).empty();
               		for (var managerName in r.aManagers) {
               			$('.mm-manager',self.element).append(
            				$("<option></option>")
            					.attr("value",managerName)
            					.text(r.aManagers[managerName])
            			);
               		}
               	}
               	
            	$('.mm-action',self.element).empty();
            	for (var actionName in r.aActions) {
            		$('.mm-action',self.element).append(
        				$("<option></option>")
        					.attr("value",actionName)
        					.text(r.aActions[actionName])
        			);
            	}
            }
   		},this.options.ajaxOptions);

   		$.ajax(ajaxOptions);
	}
});

$.extend($.ui.modulematrix, {
	version: '1.0.0',
	defaults: {
		ajaxOptions: {
			url : SGL2.Util.makeUrl({module: 'page', action: 'getRouteWidgetData'}),
            dataType: 'json'
		},
		selectors:{
			module: 'select[name="route[moduleName]"]',
			manager: 'select[name="route[controller]"]',
			action: 'select[name="route[action]"]'
		}
	}
});

})(jQuery);
