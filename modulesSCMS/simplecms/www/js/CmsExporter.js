if (typeof SimpleCms == 'undefined') { SimpleCms = {}; }

/**
 * CMS exporter.
 *
 * @package simplecms
 * @author Dmitri Lakachauskis <lakiboy83@gmail.com>
 */
SimpleCms.Exporter =
{
    init: function() {
        $('#export-data-trigger').click(function() {
           $('#export-data-text').select();
        });
    }
}
