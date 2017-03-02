if (typeof Media2 == 'undefined') { Media2 = {}; }

/**
 * Media edit.
 *
 * @package media2
 * @author Dmitri Lakachauskis <lakiboy83@gmail.com>
 */
Media2.Edit =
{
    init: function() {
        $('#media-edit').ajaxForm({
            beforeSubmit: function(elems, f) {
                if ($.trim($(elems[0]).val())) {
                    // edit mode
                    SGL2.Util.disableSubmit('input, textarea', f);
                    $('.triggers', f).hide().next('.ajaxLoader').show();
                } else {
                    var msg = 'fill in required data'.translate().toString();
                    SGL2.showMessage('#message', msg, SGL_MSG_ERROR, 1);
                    return false;
                }
            },
            success: function(r, msg, f) {
            	document.location.href = r.redir;
            }
        });
        $('#media-edit a.delete').click(function() {
        	if (confirm('are you sure you want to delete selected media'.translate())) {
                $(this).hide().next('.ajaxLoader').show();
                $.ajax({
                    url: SGL2.Util.makeUrl({module: 'media2', action: 'deleteMediaById'}),
                    data: {mediaId: $(this).attr('sgl:media_id'), redir: $(this).attr('sgl:redir')},
                    success: function(r) {
                        document.location.href = r.redir;
                    }
                });
        	}
            return false;        	
         });        
    }
}