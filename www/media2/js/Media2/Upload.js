if (typeof Media2 == 'undefined') { Media2 = {}; }

/**
 * Media upload.
 *
 * @package media2
 * @author Dmitri Lakachauskis <lakiboy83@gmail.com>
 */
Media2.Upload =
{
    disableSubmit: function(f) {
        SGL2.Util.disableSubmit('input[type!="file"]', f);
        $('.triggers', f).hide().next('.ajaxLoader').show();
    },

    enableSubmit: function(f) {
    	$('.triggers', f).show().next('.ajaxLoader').hide();
        SGL2.Util.enableSubmit('input[type!="file"]', f);
    },

    postUpload: function(mediaId, redir) {
	    $.ajax({
	    	type: 'get',
	        url: makeUrl({module: 'media2', action: 'getMediaEditScreen', mediaId: mediaId}),
	        success: function(r) {
	            $('#media_upload').remove();
	            $('#media-wrapper').html(r.html);
	            Media2.Edit.init();
	        }
	    });
    },

    init: function() {
        var _self = this;

        $('#media-upload input[type="button"]').click(function() {
        	
        	// show error message
            function showError(msg) {
                SGL2.showMessage('#message', msg, SGL_MSG_ERROR, 1);
            }
            
            // no file specified
            if (!$('#media_upload').val()) {
                showError('fill in required data'.translate().toString());
                
            // upload routine
            } else {
                var _f = $('#media-upload');

                // edit mode
                _self.disableSubmit(_f);
                
                // upload file
                $.ajaxFileUpload({
                    fileElementId: 'media_upload',
                    url: SGL2.Util.makeUrl({
                    	module: 'media2',
                    	manager: 'mediauploader',
                    	typeId: $('#media_type').val()
                    }),
                    secureuri: false,
                    dataType: 'json',
                    success: function(r) {
                        if (typeof r.aMsg == 'undefined' && r.isUploaded) {
                            _self.postUpload(r.mediaId, $('#media_redir').val());
                        } else {
                    	    _self.enableSubmit(_f);
                        }
                    },
                    error: function(r, status, e) {
                        showError(e);
                        _self.enableSubmit(_f);
                    }
                });
            }
            return false; // prevent submit
        });
    },
    
    initSimple: function() {
    	// redirect when in "media browser" mode
    	this.postUpload = function(mediaId, redir) {
    		document.location.href = redir;
    	}
    }
}