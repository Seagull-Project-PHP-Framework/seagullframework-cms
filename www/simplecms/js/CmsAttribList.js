if (typeof SimpleCms == 'undefined') { SimpleCms = {}; }

/**
 * Attribute list management.
 *
 * @package simplecms
 * @author Dmitri Lakachauskis <lakiboy83@gmail.com>
 */
SimpleCms.AttribList =
{
    devider: ' => ',

    init: function() {
        var _self = this;

        $('#attrib-list-add').click(function() {

            // to edit mode
            var _elem = this;
            $(_elem).hide().next('.ajaxLoader').show();

            $.ajax({
                dataType: 'json',
                url: SGL2.Util.makeUrl({module: 'simplecms', action: 'addAttributeList'}),
                success: function(r) {
                    $('#attrib-list').prepend(r.html);
                    _self.initViewTriggers(r.listId)
                        .initEditTriggers(r.listId)
                        .editStart(r.listId);

                    // to view mode
                    $(_elem).show().next('.ajaxLoader').hide();
                }
            });
            return false;
        });

        $('#attrib-list .zebra-item').each(function(i, elem) {
            var id = elem.id.split('_')[1];
            _self.initViewTriggers(id).initEditTriggers(id);
        });
    },

    initViewTriggers: function(id) {
        var _self = this;

        // delete list
        $('#attrib-list-item-delete_' + id).click(function() {
            if (confirm('are you sure you want to delete attribute list'.translate())) {
                // to edit mode
                $(this).hide().prev().hide()
                $(this).next('.ajaxLoader').show();
                $.ajax({
                    type: 'post',
                    url: SGL2.Util.makeUrl({
                        action: 'deleteAttributeList',
                        module: 'simplecms',
                        id: id
                    }),
                    success: function() {
                        $('#attrib-list-item_' + id).fadeOut('slow');
                    }
                });
            }
            return false;
        });

        // show edit form
        $('#attrib-list-item-edit_' + id).click(function() {
            $('#attrib-list-item-header_' + id).hide();
            $('#attrib-list-item-view_' + id).hide();
            $('#attrib-list-item-form_' + id).show();
            _self.editStart(id);
            return false;
        });

        return this;
    },

    initEditTriggers: function(id) {
        var _self = this;

        // hide edit form
        $('#attrib-list-item-cancel_' + id).click(function() {
            $('#attrib-list-item-form_' + id).hide();
            $('#attrib-list-item-header_' + id).show();
            $('#attrib-list-item-view_' + id).show();
            return false;
        });

        // ban regular submit
        $('#attrib-list-item-form_' + id).submit(function() {
            return false;
        });

        $('#attrib-list-item-submit_' + id).click(function() {
            $('#attrib-list-item-form_' + id).ajaxSubmit({
                dataType: 'json',
                beforeSubmit: function(elems, f) {
                    var ret = false;
                    if ($.trim(elems[0].value)) {
                        // to edit mode
                        $('.actions', f).hide().next('.ajaxLoader').show();

                        $('select option', f).each(function(i, elem) {
                            var oFields = {};
                            var aF      = $(elem).text().split(_self.devider);

                            oFields['name']  = 'list[fields][' + aF[0] + ']';
                            oFields['value'] = aF[1];

                            elems.push(oFields);
                        });
                        // update name
                        $('#attrib-list-item-header_' + id + ' a.name').text($.trim(elems[0].value));
                        ret = true;
                    } else {
                        var msg = 'fill in required data'.translate().toString();
                        SGL2.showMessage('#message', msg, SGL_MSG_ERROR, 1);
                    }
                    return ret;
                },
                success: function(r, msg, f) {
                    $('#attrib-list-item-view_' + id).remove();
                    $('#attrib-list-item-header_' + id).after(r.html).show();
                    $('#attrib-list-item-view_' + id).show();

                    $(f).hide();

                    // to view mode
                    $('.actions', f).show().next('.ajaxLoader').hide();
                }
            });
        });

        // copy key's value on focus
        $('#attrib-list-item-field-value_' + id).focus(function() {
            if (!$.trim($(this).val())) {
                $(this).val($('#attrib-list-item-field-key_' + id).val());
            }
        });

        // add / update field
        $('#attrib-list-item-field-value_' + id + ', #attrib-list-item-field-key_' + id).keyup(function(e) {
            _self.updateField(id, e.keyCode == SGL2.Key.ENTER);
        });

        // remove current field
        $('#attrib-list-item-field-delete_' + id).click(function() {
            var val = $('#attrib-fields_' + id).val();
            if (val) {
                $('#attrib-fields_' + id + ' option').each(function(i, elem) {
                    if ($(elem).val() == val) {
                        $(this).remove();
                    }
                });
                _self.cleanup(id);
            }
        });

        // edit current field
        $('#attrib-fields_' + id).change(function() {
            var val = $(this).val();
            if (val) {
                var aF = val.toString().split(_self.devider);

                $('#attrib-list-item-field-key_' + id).val(aF[0]);
                $('#attrib-list-item-field-value_' + id).val(aF[1]);

                _self.editStart(id);
            }
        });

        // add/update new field
        $('#field-add_' + id).click(function() {
            _self.updateField(id, true);
            return false;
        });

        // move fields
        $('#field-up_' + id).click(function() {
            _self.move(id, 'up');
            return false;
        });
        $('#field-down_' + id).click(function() {
            _self.move(id, 'down');
            return false;
        });

        return this;
    },

    /**
     * Editor.
     */

    updateField: function(id, doCleanup) {
        var _self = this;

        var k = $.trim($('#attrib-list-item-field-key_' + id).val());
        var v = $.trim($('#attrib-list-item-field-value_' + id).val());
        var s = k + this.devider + v;

        if (k && v) {
            var currentVal = $('#attrib-fields_' + id).val();

            // add field
            if (!currentVal) {
                $('#attrib-fields_' + id).append(new Option(s));

            // update field
            } else {
                $('#attrib-fields_' + id + ' option').each(function(i, elem) {
                    if ($(elem).val() == currentVal) {
                        $(this).text(s);
                    }
                });
                $('#attrib-fields_' + id).val('');
            }

            _self.removeDupes(id, s);

            // start new field
            if (doCleanup) {
                _self.cleanup(id);
            } else {
                $('#attrib-fields_' + id).val(s);
            }
        }
    },

    move: function(id, dir) {
        var selectbox = document.getElementById('attrib-fields_' + id);
        var index     = selectbox.selectedIndex;
        var lastIndex = selectbox.length - 1;
        if (index != -1) {
            if (!((dir == 'up' && index == 0) || (dir == 'down' && index == lastIndex))) {
                var swapIndex = dir == 'up' ? index - 1 : index + 1;
                var indexCopy = new Option(selectbox[index].value);
                var swapCopy  = new Option(selectbox[swapIndex].value);

                selectbox[index]     = swapCopy;
                selectbox[swapIndex] = indexCopy;

                selectbox.selectedIndex = swapIndex;

                this.editStart(id);
            }
        }
    },

    removeDupes: function(id, currentVal) {
        var deleteNext = false;
        $('#attrib-fields_' + id + ' option').each(function(i, elem) {
            if ($(elem).val() == currentVal) {
                if (deleteNext) {
                    $(elem).remove();
                } else {
                    deleteNext = true;
                }
            }
        });
    },

    cleanup: function(id) {
        $('#attrib-list-item-field-key_' + id).val('');
        $('#attrib-list-item-field-value_' + id).val('');
        this.editStart(id);
    },

    editStart: function(id) {
        $('#attrib-list-item-field-key_' + id).focus();
    }
}