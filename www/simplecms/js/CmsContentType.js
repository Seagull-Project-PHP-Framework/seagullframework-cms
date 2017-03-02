if (typeof SimpleCms == 'undefined') { SimpleCms = {}; }

/**
 * Content type management.
 *
 * @package simplecms
 * @author Dmitri Lakachauskis <lakiboy83@gmail.com>
 */
SimpleCms.ContentType =
{
    init: function() {
        var _self = this;

        $('a#content-type-add').click(function() {
            // to edit mode
            var _elem = this;
            $(_elem).hide().next('.ajaxLoader').show();

            $.ajax({
                dataType: 'json',
                url: SGL2.Util.makeUrl({module: 'simplecms', action: 'addContentType'}),
                success: function(r) {
                    if (r.isAdded) {
                        $('#content-type-list').prepend(r.html);

                        // init
                        _self.initViewTriggers(r.contentTypeId)
                            .initEditTriggers(r.contentTypeId)
                            .Manager.getEditor(r.contentTypeId).start();
                    }
                    // to view mode
                    $(_elem).show().next('.ajaxLoader').hide();
                }
            });
            return false;
        });

        $('#content-type-list .zebra-item').each(function(i, elem) {
            _self.initViewTriggers(elem.id.split('_')[1]);
        });
    },

    initViewTriggers: function(id) {
        var _self = this;

        // delete content type
        $('#content-type-item-delete_' + id).click(function() {
            if (confirm('you sure to delete selected content type'.translate())) {
                 _self.Manager.getViewer(id).lock();
                $.ajax({
                    url: SGL2.Util.makeUrl({module: 'simplecms', action: 'deleteContentType'}),
                    data: {id: id},
                    type: 'post',
                    success: function() {
                        _self.Manager.removeItem(id);
                    }
                });
            }
            return false;
        });

        // show attribs
        $('#content-type-item-trigger_' + id).click(function() {
            var v = _self.Manager.getViewer(id);
            if (v.isEnabled()) {
                v.toggle();
            } else {
                if (!v.isLocked()) {
                    v.lock();
                    $.ajax({
                        url: SGL2.Util.makeUrl({
                            module: 'simplecms',
                            action: 'getContentTypeAttributes',
                            type: 'view'
                        }),
                        data: {id: id},
                        dataType: 'json',
                        success: function(r) {
                            _self.Manager.attachViewerHtml(id, r.html);
                            v.unlock();
                        }
                    });
                }
            }
            return false;
        });

        // show edit form
        $('#content-type-item-edit_' + id).click(function() {
            var v = _self.Manager.getViewer(id);
            if (v.getEditor().isEnabled()) {
                v.hide().getEditor().show();
            } else {
                if (!v.isLocked()) {
                    v.lock();
                    $.ajax({
                        url: SGL2.Util.makeUrl({
                            module: 'simplecms',
                            action: 'getContentTypeAttributes',
                            type: 'edit'
                        }),
                        data: {id: id},
                        dataType: 'json',
                        success: function(r) {
                            _self.Manager.attachEditorHtml(id, r.html);
                            _self.initEditTriggers(id);
                            v.hide().unlock().getEditor().start();
                        }
                    });
                }
            }
            return false;
        });

        return this;
    },

    initEditTriggers: function(id) {
        var _self = this;

        // hide edit form
        $('#content-type-item-cancel_' + id).click(function() {
            _self.Manager.getEditor(id).hide().getViewer().show();
            return false;
        });

        $('#content-type-item-form_' + id).ajaxForm({
            dataType: 'json',
            beforeSubmit: function(elems, f) {
                var ret = true, contentsExist = false, msg;
                for (var i in elems) {
                    // we don't check system fields
                    if (elems[i].name == 'submitted' || elems[i].name == 'id'
                        || elems[i].name == 'attr[list_id][]'
                        || elems[i].name == 'attr[id][]'
                        || elems[i].name == 'updateMessage')
                    {
                        if (elems[i].name == 'updateMessage') {
                            msg = elems[i].value;
                        }
                        continue;
                    }
                    // regular fields must not be empty
                    if (!$.trim(elems[i].value)) {
                        var msg = 'fill in required data'.translate().toString();
                        SGL2.showMessage('#message', msg, SGL_MSG_ERROR, 1);
                        ret = false;
                        break;
                    }
                    // content type name check
                    if (elems[i].name == 'name' && elems[i].value.match(/[^a-z0-9_ ]/i)) {
                        var msg = 'content type name error'.translate().toString();
                        SGL2.showMessage('#message', msg, SGL_MSG_ERROR, 2);
                        ret = false;
                        break;
                    }
                }
                if (ret && contentsExist) {
                    ret = confirm(msg);
                }
                if (ret) {
                    _self.Manager.getEditor(id).lock();
                }
                return ret;
            },
            success: function(r, msg, f) {
                if (r.validated) {

                    // insert new attribute IDs
                    $('#content-type-item-form_' + id + ' table input[type="hidden"][value=""]').each(function(i) {
                        $(this).val(r.aNewAttrIds[i]);
                    });

                    $('#content-type-item-view_' + id).remove();
                    $(f).before(r.html);

                    _self.Manager.getEditor(id).hide().unlock()
                        .getViewer().updateName(r.contentTypeName).show();
                } else {
                    _self.Manager.getEditor(id).unlock();
                }
            }
        });

        $('#content-type-item-form_' + id + ' a.addfield').click(function() {
            // to edit mode
            var _elem = this;
            $(_elem).hide().next('.ajaxLoader').show();
            $.ajax({
                url: SGL2.Util.makeUrl({module: 'simplecms', action: 'getContentTypeEditAttribute'}),
                dataType: 'json',
                success: function(r) {
                    $('#content-type-item-form_' + id + ' tbody').append(r.html);
                    _self.initAttribEditTriggers(id);
                    // to view mode
                    $(_elem).show().next('.ajaxLoader').hide();
                }
            })
            return false;
        });

        this.initAttribEditTriggers(id);

        return this;
    },

    initAttribEditTriggers: function(id) {
        $('#content-type-item-form_' + id + ' table a.deleteattr')
            .unbind('click').click(function()
        {
            if (confirm('sure you want to delete selected attribute'.translate())) {
                $(this).parents('tr').eq(0).remove();
            }
            return false;
        });
        $('#content-type-item-form_' + id + ' table select.types')
            .unbind('change').change(function()
        {
            var v = $(this).val();
            if (v == SGL_ATTR_TYPE_CHECKBOX || v == SGL_ATTR_TYPE_COMBO
                || v == SGL_ATTR_TYPE_RADIO)
            {
                $(this).next('select.list').show();
            } else if ($(this).next('select.list').css('display') != 'none')  {
                $(this).next('select.list').hide();
            }
        });
        $('#content-type-item-form_' + id + ' table input.alias')
            .unbind('keyup').keyup(function()
        {
            var alias = $(this).val().replace(/[^a-zA-Z1-9_ ]/g, '');

            // update attribute name only for new attributes
            if (!$(this).prev('input').val()) {
                var name  = $.map(alias.split(' '), function(n, i) {
                    if (i != 0) {
                        n = n.charAt(0).toUpperCase() + n.substring(1).toLowerCase();
                    } else {
                        n = n.charAt(0).toLowerCase() + n.substring(1).toLowerCase();
                    }
                    return n;
                }).join('');

                // fixme - too long
                $(this).val(alias).parent().next('td').children().eq(0).val(name);
            } else {
                $(this).val(alias);
            }
        });
    },

    Manager: {
        aEditors: {},
        aViewers: {},

        getEditor: function(id) {
            if (typeof this.aEditors[id] == 'undefined') {
                this.aEditors[id] = new SimpleCms.ContentType.Editor(id, this);
            }
            return this.aEditors[id];
        },

        getViewer: function(id) {
            if (typeof this.aViewers[id] == 'undefined') {
                this.aViewers[id] = new SimpleCms.ContentType.Viewer(id, this);
            }
            return this.aViewers[id];
        },

        removeItem: function(id) {
            $('#content-type-item_' + id).fadeOut('slow');
        },

        attachViewerHtml: function(id, html) {
            $('#content-type-item_' + id).append(html);
        },

        attachEditorHtml: function(id, html) {
            $('#content-type-item_' + id).append(html);
        }
    }
}

// --------------------
// --- Declarations ---
// --------------------

SimpleCms.ContentType.Viewer = function(id, m) {
    this.id       = id;
    this.Manager  = m;
    this.locked   = false;
}
SimpleCms.ContentType.Editor = function(id, m) {
    this.id       = id;
    this.Manager  = m;
}

/**
 * Viewer object.
 */
SimpleCms.ContentType.Viewer.prototype =
{
    getEditor: function() {
        return this.Manager.getEditor(this.id);
    },

    isLocked: function() {
        return this.locked;
    },

    isEnabled: function() {
        return $('#content-type-item-view_' + this.id).length;
    },

    hide: function() {
        $('#content-type-item-header_' + this.id).hide();
        $('#content-type-item-view_' + this.id).hide();
        return this;
    },

    show: function() {
        $('#content-type-item-header_' + this.id).show();
        $('#content-type-item-view_' + this.id).show();
        return this;
    },

    toggle: function() {
        $('#content-type-item-view_' + this.id).toggle();
        return this;
    },

    lock: function () {
        $('#content-type-item-edit_' + this.id).hide();
        $('#content-type-item-delete_' + this.id).hide().next('.ajaxLoader').show();
        this.locked = true;
        return this;
    },

    unlock: function() {
        $('#content-type-item-edit_' + this.id).show();
        $('#content-type-item-delete_' + this.id).show().next('.ajaxLoader').hide();
        this.locked = false;
        return this;
    },

    updateName: function(txt) {
        $('#content-type-item-trigger_' + this.id).text(txt);
        return this;
    }
}

/**
 * Editor object.
 */
SimpleCms.ContentType.Editor.prototype =
{
    getViewer: function() {
        return this.Manager.getViewer(this.id);
    },

    isEnabled: function() {
        return $('#content-type-item-form_' + this.id).length;
    },

    show: function() {
        $('#content-type-item-form_' + this.id).show();
        return this;
    },

    hide: function() {
        $('#content-type-item-form_' + this.id).hide();
        return this;
    },

    lock: function() {
        SGL2.Util.disableSubmit('input, select', '#content-type-item-form_' + this.id);
        $('#content-type-item-actions_' + this.id).hide().next('.ajaxLoader').show();
        return this;
    },

    unlock: function() {
        SGL2.Util.enableSubmit('input, select', '#content-type-item-form_' + this.id);
        $('#content-type-item-actions_' + this.id).show().next('.ajaxLoader').hide();
        return this;
    },

    start: function() {
        $('#content-type-item-form_' + this.id + ' input[type="text"]').eq(0).focus();
        return this;
    }
}