if (typeof Media2 == 'undefined') { Media2 = {}; }

/**
 * Media browser.
 *
 * @package media2
 * @author Laszlo Horvath <pentarim@gmail.com>
 */
Media2.Browser =
{
    init: function() {
	
		$('#media-manager-tabs').tabs();
		
	    $('#media-list li a').live('click',function(event) {
	    	$('#media-list li').removeClass('selected');
	    	$(event.target).parents('li').addClass('selected');
			return false;
		});

	    $('.dialog-close').live('click',function(event) {
	    	$('#media-browser-dialog').dialog('close');
	    	return false;
	    });
	    
	    $('.select-media').click(function(event) {
	    	
	    	$('#media-browser-dialog').dialog('close');
	    	
	    	$('#'+$('#media-browser-dialog').dialog('option','target'))
	    		.trigger('selected.SGL2_Media2_Browser',[
	    		    $('#media-browser-dialog').dialog('option','attrId'),
	    		    $('#media-list li.selected input[name="mediaId"]').val()
	    		]);
	    	
	    	$('#media-list-container').empty();	    	
	    	
	    	return false;
	    });
	    
		$('.ui-state-default').hover(
				   function(){$(this).addClass('ui-state-hover');}, 
				   function(){$(this).removeClass('ui-state-hover');}
		);

		this.Filter.init();
		this.Uploader.init();
    },
    selected: function(mediaId) {
    	if (typeof mediaId == 'undefined') {
    		return $('#media-list li.selected input[name="mediaId"]').val();
    	} else {
    		$('#media-list li input[name="mediaId"][value="'+mediaId+'"]').parents('li').addClass('selected');
    	}
    },
    Uploader: {
        init: function() {
			$('#frmMediaUpload').ajaxForm({
				url:       SGL2.Util.makeUrl({
                	module: 'media2',
                	manager: 'mediauploader',
                	typeId: $('#frmMediaUpload #media_type').val()
                }),
				dataType: 'json',
				success: function(r) {
	                if (typeof r.aMsg == 'undefined' && r.isUploaded) {
	                	$('#media-list-container .media-list-empty').hide();
						$('#media-manager-tabs').tabs('select',0);
						Media2.Browser.Filter.apply();
	                    //_self.postUpload(r.mediaId, $('#media_redir').val());
	                } else {
	            	    //_self.enableSubmit(_f);
	                }
				}
			});
    	}
    },
    Filter: {
        init: function() {
            $('#frmMediaFilter').ajaxForm({
                url:      SGL2.Util.makeUrl({action: 'getMediaBrowserView', manager: 'media2', module: 'media2'}),
                type: 'get',
                dataType: 'json',
                beforeSubmit: function(formData, $jqForm, options){
                	formData.push({
                        name: 'resPerPage',
                        value: $('#resultsPerPage').val()
                    });
            	},
                success:  function(response,status){
            		$('ul#media-list').empty().append(response.html);
            		if (response.pagerLinks) {
            			$('div#pager-links').html(response.pagerLinks);
            			//Media2.Browser.Filter.initTriggers();
            		} else {
            			$('div#pager-links').html('');
            		}
                }
            });
            
            $('#frmMediaFilter select').change(function(event){
            	Media2.Browser.Filter.apply();
            });
            
	        $('a.filter-submit').click(function(event) {
	        	Media2.Browser.Filter.apply();
				return false;
			});

	        // pager links can change so binded by .live
	        $('#pager-links a').live('click',function(event){
            	var pageID = 1;
            	if (/pageID\/(\d+)/.test(event.target.href)) {
            		pageID = event.target.href.match(/pageID\/(\d+)/)[1];
            	}
            	$('#frmMediaFilter input[name=pageID]').attr('value',pageID);
            	Media2.Browser.Filter.apply();
            	return false;
            });
        },
        apply: function() {
        	$('#media-list-container, #media-browser .media-list-ajaxloading').toggle();
        	$('#frmMediaFilter').submit();
        	$('#media-list-container, #media-browser .media-list-ajaxloading').toggle();
        }
    }
    
}