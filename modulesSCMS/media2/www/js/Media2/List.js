if (typeof Media2 == 'undefined') { Media2 = {}; }

/**
 * Media list.
 *
 * @package media2
 * @author Dmitri Lakachauskis <lakiboy83@gmail.com>
 */
Media2.List =
{
    init: function() {
        var _self = this;

        $('#media-list .item a.deleteItem').click(function() {
        	if (confirm('are you sure you want to delete selected media'.translate())) {
        	    var id = $(this).attr('sgl:media_id');
                $(this).parents('.triggers').eq(0).hide().next('.ajaxLoader').show();
                $.ajax({
                    url: SGL2.Util.makeUrl({module: 'media2', action: 'deleteMediaById'}),
                    data: {mediaId: id},
                    success: function(r) {
                        $('#media-item_' + id).fadeOut('slow');
                    }
                });
        	}
            return false;        	
         });
        // fency zoom
        $.fn.fancyzoom.defaultsOptions.imgDir = SGL_WEBROOT + '/media2/images/fancyzoom/';
        $('#media-list a.preview').fancyzoom();
//        {Speed: 1000}
//        {overlay: 0.8}
        
	    // blocks
	    $('#media-filter select').change(function() {
	    	document.location.href = $(this).val();
	    });        
    },
    
    initSimple: function() {
    	
    	// on hover styles
        $('#media-list .item a.descr').hover(
            function() {
            	var $parent = $(this).parent();
            	if ($parent.hasClass('current')) {
            		$parent.addClass('keep')
            	} else {
            		$parent.addClass('current');
            	}
           	},
            function() {
           		var $parent = $(this).parent();
            	if ($parent.hasClass('keep')) {
            		$parent.removeClass('keep')
            	} else {
            		$parent.removeClass('current');
            	}           		
           	}
        );
        
        // close window
        $('#media-filter a.delete').click(function() {
            window.close();
            return false;
        });
        
        // run action and close window
        $('#media-list a.descr').click(function() {
        	// run callback and focus
        	if (opener && !opener.closed && typeof opener.SGL2.MediaBrowser != 'undefinded') {
        		opener.SGL2.MediaBrowser.callback($(this).attr('sgl:media_id'), name.split('_')[2]);
        		opener.focus();
        	}
        	// close media browser
        	window.close();
            return false;
        });
    }
}