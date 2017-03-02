if (typeof SimpleCms == 'undefined') { SimpleCms = {}; }

/**
 * Suggest user.
 *
 * @package simplecms
 * @author Andrey Baigozin <a.baigozin@gmail.com>
 */
SimpleCms.Activity =
{
    init: function() {
        $('#content-activity-filter input.text').suggest(
            makeUrl({module: 'simplecms', action: 'matchUsersByPattern'}), {
            dataType: 'html',
            onSelect: function() {
                // Removing firstname and lastname
                var username = this.value.substr(0, this.value.indexOf(' '));
                $(this).val(username).parents('form').eq(0).submit();
            }
        });
    }
}
