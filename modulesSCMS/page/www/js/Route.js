if (typeof Route == 'undefined') { Route = {}; }

Route.List =
{
	init: function() {
	
	    $('#route-create .button').click(function() {
	    	document.location.href = $('#route-create select').val();
	    	return false;
	    });
    
		$('input#checkAll').change(function(event){
			if ($(event.target).is(':checked')) {
				$('input[name="frmAction[]"]').attr('checked','checked');
			} else {
				$('input[name="frmAction[]"]').attr('checked',false);
			}
		});
		
	    $('.delete-routes').click(function() {
	    	Route.List.actions.deleteRoutes();
	    	return false;
	    });
    
		this.initTriggers();
		
		this.Filter.init();
	},
	initTriggers: function() {
        $('#route-list input[name="isActive"]').change(function(event){
        	var routeId  = $(event.target).parents('tr').find('input[name="routeId"]').val();
        	var isActive = ($(event.target).is(':checked')) ? 1 : 0;
        	Route.List.actions.updateStatus(routeId,isActive);
        });
	},
	_getSelected: function(){
		var aSelected = [];
		$('input[name="frmAction[]"]:checked').each(function(){
			aSelected.push($(this).val());
		});
		return aSelected;
	},
    actions: {
    	updateStatus: function(routeId, isActive){
  			$.ajax({
                type : 'post',
                dataType : 'json',
                url : SGL2.Util.makeUrl({action: 'updateRouteStatus', manager: 'page', module: 'page'}),
                beforeSend: function (XMLHttpRequest) {
  					Route.List.lock();
                },
                data : {
  					'routeId': routeId,
  					'isActive': isActive
                },
                success : function(response,status){
  					Route.List.unlock();
                }
            });
    	},
    	deleteRoutes: function(){
  			$.ajax({
                type : 'post',
                dataType : 'json',
                url : SGL2.Util.makeUrl({action: 'deleteRoutes', manager: 'page', module: 'page'}),
                data : {
  					'aRouteId[]': Route.List._getSelected()
                },
                beforeSend: function (XMLHttpRequest) {
  					Route.List.lock();
                },
                success : function(response,status){
    	        	Route.List.Filter.apply();
  					Route.List.unlock();
                }
            });
    	}
	},
    lock: function() {
        $('#route-list').addClass('locked');
        $('#route-list-loader').css('top', this._getLoaderTopCoord() + 'px').show();
        return this;
    },
    unlock: function() {
        $('#route-list').removeClass('locked');
        $('#route-list-loader').hide();
        return this;
    },
    _getLoaderTopCoord: function() {
        var rowsCount = $('#route-list tbody tr').length, rowHeight = 28, ret;
        if (rowsCount == 1) {
            ret = rowHeight;
        } else if (rowsCount > 10) {
            ret = rowHeight * 5;
        } else {
            ret = rowHeight * parseInt(rowsCount / 2);
        }
        return ret;
    },
	
	Filter: {
        init: function(id) {
	        $('#frmRouteFilter').ajaxForm({
	            url:      SGL2.Util.makeUrl({action: 'getRoutes', manager: 'page', module: 'page'}),
	            type: 'get',
	            dataType: 'json',
	            beforeSubmit: function(formData, $jqForm, options){
	        	Route.List.lock();
	        	},
	            success:  function(response,status){
	        		$('#route-list tbody').empty().append(response.html);
	        		Route.List.initTriggers();
	        		if (response.pagerLinks) {
	        			$('#route-list-pager').html(response.pagerLinks);
	        		} else {
	        			$('#route-list-pager').html('');
	        		}
	        		Route.List.unlock();
	            }
	        });
	        
	        $('#frmRouteFilter select').change(function(event){
	        	// reset page id if perPage has changed
	        	if ($(event.target).attr('name') == 'filter[resPerPage]') {
	            	$('#frmRouteFilter input[name=pageID]').attr('value',1);
	        	}
	        	Route.List.Filter.apply();
	        });

	        // pager links can change so binded by .live
	        $('#route-list-pager a').live('click',function(event){
	        	var pageID = 1;
	        	if (/pageID\/(\d+)/.test(event.target.href)) {
	        		pageID = event.target.href.match(/pageID\/(\d+)/)[1];
	        	}
	        	$('#frmRouteFilter input[name=pageID]').attr('value',pageID);
	        	Route.List.Filter.apply();
	        	return false;
	        });
	    },
	    apply: function() {
	    	$('#frmRouteFilter').submit();
	    }
	}
};

Route.Edit =
{
	init: function() {
		// set up module matrix
		$('#module-matrix').modulematrix();

		this.initTriggers();
	},
	initTriggers: function(){
        $('#route-container .process-path').click(function(event){
        	Route.Edit.actions.processPath($('#route-container input[name="route[path]"]').val());
        	return false;
        });
        
        $('#route-container .add-parameter').click(function(event){
        	Route.Edit.addDefaultParam($('#route-container input[name="parameterName"]').val(),'');
        	$('#route-container input[name="parameterName"]').val('');
        	return false;
        });
        
        $('#route-container .remove-parameter').live('click',function(event){
        	$(event.target).parents('li').remove();
        	return false;
        });
        
        // insert/update
        $('#route-container').ajaxForm({
            beforeSubmit: function(elems, f) {
                // to edit mode
                SGL2.Util.disableSubmit('input, select', f);
                $('.triggers').hide().next('.ajaxLoader').show();
            },
            data: {adminGuiAllowed: 1},
            dataType: 'json',
            success: function(r, msg, f) {
                if (r.redir) {
                    document.location.href = r.redir;
                } else {
                    // to view mode
                    SGL2.Util.enableSubmit('input, select', f);
                    $('.triggers').show().next('.ajaxLoader').hide();
                }
            }
        });
        
	},
	addDefaultParam: function(name, value){
		if ($('#route-container ol.default-values input[name="route['+name+']"]').length == 0) {
			$('#route-container ol.default-values').append('<li><label>'+name+'</label><div><input class="text dynamic" name="route['+name+']" value="'+value+'" /><a class="remove-parameter" href="">[ - ]</a></div></li>');
		}
	},
	setCustomParams: function(params){
		$('#route-container ol.custom-values').empty();
		for (var paramName in params) {
			$('#route-container ol.custom-values').append('<li><label>'+paramName+'</label><div><input class="text" name="route['+paramName+']" value="'+params[paramName]+'" /></div></li>');
		}
	},
	actions: {
		processPath: function(path){
			var aVariables = [];
			// extract parameters from path
			path.replace(/:\(?(\w+)\)?/g, function(a, b){
				aVariables.push(b)
			});
			
			var parameters = new Object();
       		for (var key in aVariables) {
       			// if it already exists get its value
       			var value = ($('input[name="route['+aVariables[key]+']"]').length != 0)
       				? $('input[name="route['+aVariables[key]+']"]').val()
       				: '';
   				parameters[aVariables[key]] = value;
       		}
       		Route.Edit.setCustomParams(parameters);
		}
	}
};