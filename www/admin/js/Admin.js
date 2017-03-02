
/**
 * Common Ajax setup.
 */
$.ajaxSetup({
    type: 'post',
    dataType: 'json'
});

Admin = {};

Admin.Dashboard =
{
    init: function() {
        $('#content-create .button').click(function() {
    	   document.location.href = $('#content-create select').val();
    	   return false;
        });
    }
}

Admin.Frontend =
{
    init: function() {
        var div, p, url;

        // route should be setup
        url = SGL2.Util.makeUrl({module: 'admin', manager: 'dashboard'});

        // create dynamic helper
        p = document.createElement('p');
        $(p).html('<a href="' + url + '">' + 'admin area'.translate() + '</a>');
        div = document.createElement('div');
        $(div).attr('id', 'frontend-helper-panel').append(p);
        $(document.body).append(div);
    }
};

Admin.Backend =
{
    init: function() {
        // init help system
        if ($('.help').length) {
            // put help text
            $('#help').show().find('.inner').html($('.help').hide().html());
            // show/hide text box
            $('#help a.trigger').toggle(function() {
                $('#help .inner').slideDown();
            }, function() {
                $('#help .inner').slideUp();
            });
        }

        // init blocks
        this.Block.init();
    },

    Block:
    {
        init: function() {
            if ($('.block-helper').length) {
                // maintain blocks state
                $('.block-helper').each(this.maintain);
                // show hide blocks
                $('.block-helper h2 a').click(this.toggle);
            }
        },

        toggle: function() {
            var block = $(this).parent().parent(), c;
            if (block.attr('id')) {
                $('.inner', block).toggle();
                c = $.cookie('cms_blocks_' + SGL_USERID) || '';

                // add new block entry to cookie
                if (c.indexOf(block.attr('id') + ';') == -1) {
                    c = c + block.attr('id') + ';1;';
                // block was hidden, mark as shown
                } else if (c.indexOf(block.attr('id') + ';1;') != -1) {
                    c = c.replace(block.attr('id') + ';1;', block.attr('id') + ';0;');
                // block was shown, mark as hidden
                } else {
                    c = c.replace(block.attr('id') + ';0;', block.attr('id') + ';1;');
                }

                $.cookie('cms_blocks_' + SGL_USERID, c, {path: '/', expires: 365});
            }
            return false;
        },

        maintain: function() {
            var block = $(this), c;
            if (block.attr('id')) {
                c = $.cookie('cms_blocks_' + SGL_USERID) || '';
                // hide block
                if (c.indexOf(block.attr('id') + ';1;') != -1) {
                    $('.inner', block).hide();
                }
            }
        }
    }
};