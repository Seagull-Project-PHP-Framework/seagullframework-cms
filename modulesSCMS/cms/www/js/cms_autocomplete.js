
/**
 * Disables content name field and generates it's value automatically
 * according to supplied set of fields.
 *
 * @package cms
 * @author Dmitri Lakachauskis <lakiboy83@gmail.com>
 */
cms.autoComplete =
{
    /**
     * ID of content name field.
     */
    nameId: 'contentName',

    /**
     * Form elements' prefix.
     */
    prefix: 'frmFieldName_',

    /**
     * Separators.
     */
    sepStart: '',
    sepEnd: '',
    sepMiddle: ' - ',

    /**
     * Fields to use for content name auto generation.
     */
    aFields: [],

    init: function(oOpts) {
        var _self = this;

        $.extend(this, oOpts || {});

        if (this.aFields.length) {
            $('#' + this.nameId).attr('disabled', 'disabled');
        }

        $.each(this.aFields, function() {
            var fieldId = '#' + _self.prefix + this;
            if ($(fieldId)) {
                $(fieldId).bind('keyup', function() {
                    _self.generate();
                });
                $(fieldId).bind('change', function() {
                    _self.generate();
                    cms.content.editor.checkContentNameUniqueness();
                });
            }
        });
        $('#contentEditForm').submit(function() {
            if (_self.aFields.length) {
                $('#' + _self.nameId).attr('disabled', null);
            }
        });

        this.generate();
    },

    add: function(fieldName) {
        this.aFields.push(fieldName);
    },

    update: function(string) {
        $('#' + this.nameId).val(string);
    },

    sanitize: function(string) {
        return string.replace(/[^a-z_0-9& :]/i, '');
    },

    generate: function() {
        var _self = this;

        var string = '';
        $.each(this.aFields, function(i) {
            var fieldId = '#' + _self.prefix + this;
            var val = $(fieldId).length ? $.trim($(fieldId).val()) : this;
            val = _self.sanitize(val);
            if (val) {
                if (!i && _self.sepStart) {
                    string += _self.sepStart;
                } else if (i && _self.sepMiddle) {
                    string += _self.sepMiddle;
                }
                string += val;
            }
        });
        if (string && this.sepEnd) {
            string += this.sepEnd;
        }
        if (string) {
            this.update(string);
        }
    }
}