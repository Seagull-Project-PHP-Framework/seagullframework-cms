
/**
 * Common context menu.
 *
 * @package SGL2
 */

SGL2.ContextMenu = function(openName, closeName, containerName) {
	this.openName      = openName;
	this.closeName     = closeName;
	this.containerName = containerName;
}
SGL2.ContextMenu.prototype =
{
	closeAll: function() {
		var _self = this;
	    $('.' + this.closeName).each(function(i, elem) {
	    	_self.close(elem.id.split('_')[1]);
	    });
	},

	open: function(id) {
		$('#' + this.openName + '_' + id).hide();
		$('#' + this.closeName + '_' + id).show();
		$('#' + this.containerName + '_' + id).show();
	},

	close: function(id) {
		$('#' + this.closeName + '_' + id).hide();
		$('#' + this.openName + '_' + id).show();
		$('#' + this.containerName + '_' + id).hide();
	}
}

/**
 * Common Ajax setup.
 */
$.ajaxSetup({
    type: 'post',
    dataType: 'json'
});

CmsContent = {};

/**
 * Context status menu.
 */
CmsContent.ContextStatus = function(openName, closeName, containerName) {
	this.openName      = openName;
	this.closeName     = closeName;
	this.containerName = containerName;
}
CmsContent.ContextStatus.prototype =
{
    toEditMode: function(id) {
        this.close(id);
        $('#' + this.openName + '_' + id).parents('.triggers').eq(0).hide().next('.ajaxLoader').show();
    },

    toViewMode: function(id) {
        $('#' + this.openName + '_' + id).parents('.triggers').eq(0).show().next('.ajaxLoader').hide();
    },

    updateStatus: function(id, status) {
        $('#status-current_' + id).attr('class', 'status-current status-' + status);
    }
}
$.extend(CmsContent.ContextStatus.prototype, SGL2.ContextMenu.prototype);

CmsContent.List =
{
    Filter:
    {
        getLoaderTopCoord: function() {
            var rowsCount = $(this.id + ' tbody tr').length, rowHeight = 28, ret;
            if (rowsCount == 1) {
                ret = rowHeight;
            } else if (rowsCount > 10) {
                ret = rowHeight * 5;
            } else {
                ret = rowHeight * parseInt(rowsCount / 2);
            }
            return ret;
        },

        lock: function() {
            $(this.id).addClass('locked');
            $(this.id + '-loader').css('top', this.getLoaderTopCoord() + 'px').show();
            return this;
        },

        unlock: function() {
            $(this.id).removeClass('locked');
            $(this.id + '-loader').hide();
            return this;
        },

        updateSorting: function(sortBy, sortOrder) {
            $('#filter_sortBy_value').val(sortBy);
            $('#filter_sortOrder_value').val(sortOrder);
            return this;
        },

        loadData: function(pageId) {
            var self = this;
            self.lock();
            $.ajax({
                url: SGL2.Util.makeUrl({
                    module: 'simplecms',
                    manager: 'cmscontent', // we need to keep this!!!
                    action: 'getContensFilteredList'
                }),
                data: {
                    typeId: $('#filter_type_value').val(),
                    status: $('#filter_status_value').val(),
                    langId: $('#filter_lang_value').val(),
                    resPerPage: $('#filter_resPerPage_value').val(),
                    sortBy: $('#filter_sortBy_value').val(),
                    sortOrder: $('#filter_sortOrder_value').val(),
                    pageId: (typeof pageId != 'undefined' ? pageId : 1),
                    adminGuiAllowed: 1
                },
                dataType: 'json',
                success: function(r) {
                    $(self.id + ' tbody').html(r.html);
                    $(self.id + '-pager').html(r.pagerLinks);
                    self.initDataTriggers().initPageTriggers().unlock();
                }
            });
            return this;
        },

        updateHeaders: function($elem, sortOrder) {
            $(this.id + ' th.sort').removeClass('sort-up').removeClass('sort-down');
            $elem.parent().addClass(sortOrder == 'asc' ? 'sort-up' : 'sort-down');
            return this;
        },

        getSortOrder: function($elem) {
            var ret = 'asc';
            if ($elem.parent().hasClass('sort-up')) {
                ret = 'desc';
            }
            return ret;
        },

        initPageTriggers: function() {
            var self = this;
            $(this.id + '-pager a').click(function() {
                self.loadData($(this).attr('href').substring(1));
                return false;
            });
            return this;
        },

        initDataTriggers: function() {
            var _self = CmsContent.List;

            _self.ContextMenuStatus = new CmsContent.ContextStatus(
                'status-trigger-open',
                'status-trigger-close',
                'status-wrapper'
            );
            _self.ContextMenuVersion = new SGL2.ContextMenu(
                'version-trigger-open',
                'version-trigger-close',
                'version-wrapper'
            );
            _self.ContextMenuLanguage = new SGL2.ContextMenu(
                'lang-trigger-open',
                'lang-trigger-close',
                'lang-wrapper'
            );

            // status context menu controllers
            $(this.id + ' .status-trigger-open').click(function() {
                _self.ContextMenuStatus.closeAll();
                _self.ContextMenuStatus.open(this.id.split('_')[1]);
                return false;
            });
            $(this.id + ' .status-trigger-close').click(function() {
                _self.ContextMenuStatus.close(this.id.split('_')[1]);
                return false;
            });
            $(this.id + ' .status-container a').click(function() {
                var id    = $(this).attr('sgl:content_id');
                var _elem = this;

                // to edit mode
                _self.ContextMenuStatus.toEditMode(id);

                $.ajax({
                    url: SGL2.Util.makeUrl({action: 'updateContentStatus', manager: 'simplecms', module: 'simplecms'}),
                    data: {contentId: id, status: $(this).attr('sgl:status_id'), cLang: $(this).attr('sgl:lang_id')},
                    success: function(r) {
                        _self.ContextMenuStatus.updateStatus(id, $(_elem).parent().attr('class'));
                        _self.ContextMenuStatus.toViewMode(id);
                    }
                });
                return false;
            });

            // version context menu controllers
            $(this.id + ' .version-trigger-open').click(function() {
                _self.ContextMenuVersion.closeAll();
                _self.ContextMenuVersion.open(this.id.split('_')[1]);
                return false;
            });
            $(this.id + ' .version-trigger-close').click(function() {
                _self.ContextMenuVersion.close(this.id.split('_')[1]);
                return false;
            });

            // language context menu controllers
            $(this.id + ' .lang-trigger-open').click(function() {
                _self.ContextMenuLanguage.closeAll();
                _self.ContextMenuLanguage.open(this.id.split('_')[1]);
                return false;
            });
            $(this.id + ' .lang-trigger-close').click(function() {
                _self.ContextMenuLanguage.close(this.id.split('_')[1]);
                return false;
            });
            return this;
        },

        init: function(id) {
            var self = this;
            self.id = id;
            $(id + ' th.sort a').click(function() {
                var elem = $(this), sortBy, sortOrder;

                // identify current sort order
                sortBy    = elem.attr('sgl:sort_by');
                sortOrder = self.getSortOrder(elem);

                self
                    .updateSorting(sortBy, sortOrder)
                    .updateHeaders(elem, sortOrder)
                    .loadData();

                return false;
            });
            return this;
        }
    },

    init: function() {

	    // blocks
	    $('#content-filter select').change(function() {
	    	document.location.href = $(this).val();
	    });
	    $('#content-create .button').click(function() {
	    	document.location.href = $('#content-create select').val();
	    	return false;
	    });

        // list filter
        this.Filter.init('#content-list').initDataTriggers().initPageTriggers();
    }
}

CmsContent.Edit =
{
    init: function() {
	    var _self = this;

        if (typeof $.fn.tabs != 'undefined') {
        	if ($('div#content-panel-tabs ul#content-panel-trigger').length > 0) {
        		$('div#content-panel-tabs').tabs();
        	}

            this.maintainAssocTriggers();

            // associate content
            $('input#assoc_content-value').suggest(
                SGL2.Util.makeUrl({module: 'simplecms', action: 'matchContentsByPattern'}), {
                dataType: 'html',
                appendTo: $('#assoc_content-value').parent(),
                params: ['#assoc_content-type', '#assoc-lang-id'],
                onSelect: function() {
                    var aSearchParts = this.value.split('('), id, title, input, tpl;

                    // extract content ID and title
                    id    = aSearchParts.pop();
                    id    = id.substring(0, id.length - 1);
                    title = $.trim(aSearchParts.join('('));

                    if (!$('#assoc_content_' + id).length) {
                        tpl = '<p>' + title
                            + '&nbsp;&nbsp;<a id="assoc-content_'
                            + id + '" href="#" class="del">'
                            + 'remove assoc content'.translate()
                            + '</a></p>';

                        // remove default message
                        if ($('#content-assocs-container p').length == 1) {
                            $('#content-assocs-container p').hide();
                        }

                        // create element and append to DOM
                        input = document.createElement('input');
                        $(input)
                            .attr('type', 'hidden')
                            .attr('name', 'assocs[]')
                            .attr('id', 'assoc_content_' + id)
                            .attr('value', id);
                        $('#assoc-content-ids').append(input);
                        $('#content-assocs-container').append(tpl);

                        _self.maintainAssocTriggers();
                    }

                    // cleanup
                    this.value = '';
                }
            });
        }

        $('#content_type').change(function() {
            document.location.href = $(this).val();
        });

        $('#content_template').change(function() {
            $(this).attr('sgl:is_modified', true);
        });

	    // ajax insert/update
        $('#content-container').ajaxForm({
            beforeSubmit: function(elems, f) {

        		// iterate all possible wysiwyg fields
	    		$('textarea.wysiwyg', f).each(function(i, wys) {
	                for (var i = 0; i < elems.length; i++) {
	                	if (elems[i].name == wys.name) {
	                		elems[i].value = FCKeditorAPI.GetInstance(wys.id).GetXHTML(true);
	                		FCKeditorAPI.GetInstance(wys.id).SetHTML('');
	                		return;
	                	}
	                }
	    		});

                SGL2.Util.disableSubmit('input, textara', f);
                $('.triggers', f).hide().next('.ajaxLoader').show();
                return true;
            },
            success: function(r, msg, f) {
//                $('.triggers', f).show().next('.ajaxLoader').hide();
//                SGL2.Util.enableSubmit('input, textara', f);
                document.location.href = r.redir;
            }
        });

        // ajax delete
        $('#content-container .delete').click(function() {
        	if (confirm('sure you want to delete content'.translate())) {
        		$(this).hide().next('.ajaxLoader').show();
        		$.ajax({
        			url: SGL2.Util.makeUrl({module: 'simplecms', action: 'deleteContent'}),
        			data: {
        			    contentId: $(this).attr('sgl:content_id'),
        			    cLang: $(this).attr('sgl:content_lang'),
        			    redir: $(this).attr('sgl:redir')
        			},
        			success: function(r) {
        			    document.location.href = r.redir;
        		    }
        		})
        	}
        	return false;
        });

        // media browser
        $('#content-container .media-select').live('click',function() {
            return _self.initMediaBrowser(this);
        }).bind('selected.SGL2_Media2_Browser', _self.updateMediaField);

        // datepicker
        $('#content-container .datepicker').datepicker({
	        changeFirstDay: false,
	        dateFormat: 'yy-mm-dd'

//                buttonImage: SGL_IMAGESDIR + '/icons/calendar.gif',
//                buttonImageOnly: true
//                altField: '#news_expiration-date',
//                altFormat: 'yy-mm-dd'

        });

        // preview
        $('#content-preview-trigger').click(function() {
            var f, cType, fType, pType, tpl, cf = [], id, val, features;

            SGL2.Util.disableSubmit(this);

            // open new window and focus
            features = 'left=100,top=100,width=500,height=650'
                + ',resizable=yes,titlebar=no,menubar=no,toolbar=no'
                + ',location=no,scrollbars=yes,status=no';
            window.open($(this).attr('action'), 'preview', features).focus();

            // create form
            f = document.createElement('form');
            $(f).attr('method', 'post')
                .attr('action', $(this).attr('sgl:link'))
                .attr('target', 'preview')
                .hide();

            // append outside main form, otherwise main form is submitted
            $('#content-container').before(f);

            // content type
            cType = document.createElement('input');
            $(cType)
                .attr('type', 'text')
                .attr('name', 'content[typeName]')
                .val($('#content_type-name').val());
            $(f).append(cType);

            // append template info
            if ($('#content_template').attr('sgl:is_modified')) {
                tpl = document.createElement('textarea');
                $(tpl)
                    .attr('name', 'content[template]')
                    .val($('#content_template').val());
                $(f).append(tpl);
            }

            // iterate through each form element and create appropriate
            // form field for created hidden form
            $('#content-container fieldset.fields div.field-wrapper').each(function(i, elem) {

                // default values
                id    = '#content_attr_' + $(elem).attr('id').split('_')[1];
                fType = 'input'
                val   = '';
                pType = $(elem).attr('sgl:preview_type');

                // regular input field
                if ($(id).length && !pType) {
                    val = $(id).val();

                // special fields
                } else {

                    // media ID
                    if (pType == 'media') {
                        val = $('input', elem).val();

                    // large text
                    } else if (pType == 'textarea') {
                        val   = $(id).val();
                        fType = 'textarea';

                    // rich text data
                    } else if (pType == 'wysiwyg') {
                        val   = FCKeditorAPI.GetInstance(id.substring(1)).GetXHTML(true);
                        fType = 'textarea';

                    // radio list
                    } else if (pType == 'radio') {
                        $('input[type="radio"]', elem).each(function(j, radio) {
                            if (radio.checked) {
                                val = radio.value;
                            }
                        });

                    // checkbox list
                    } else if (pType == 'checkbox') {
                        $('input[type="checkbox"]', elem).each(function(j, checkbox) {
                            if (checkbox.checked) {
                                if (val) {
                                    val += ';';
                                }
                                val += checkbox.value;
                            }
                        });
                    }
                }

                cf[i] = document.createElement(fType);
                if (fType == 'input') {
                    $(cf[i]).attr('type', fType);
                }
                $(cf[i]).attr('name', 'content[aAttribs][]').val(val);
                $(f).append(cf[i]);
            });

            // submit to newly opened window and remove form from DOM
            $(f).submit().remove();

            SGL2.Util.enableSubmit(this);
        });
    },

    maintainAssocTriggers: function() {
        // remove assoc
        $('#content-assocs-container a.del').unbind('click').click(function() {
            // remove hidden field
            $('#assoc_content_' + this.id.split('_')[1]).remove();
            // remove current element
            $(this).parent().remove();
            // show 'no contents' message
            if ($('#content-assocs-container p').length == 1) {
                $('#content-assocs-container p').show();
            }
            return false;
        });
    },

    initMediaBrowser: function(trigger) {
    	var _self = this;

        //$('#media-browser-dialog').remove();
        
    	if ($('#media-browser-dialog').length == 0) {
    		$('body').append('<div id="media-browser-dialog" title="Media Browser"></div>');
        	$("#media-browser-dialog").dialog({
        		width: 650,
        		height: 500,
        		autoOpen: false,
        		open: function(event, ui) {
	                $.ajax({
	                    url: SGL2.Util.makeUrl({action: 'getMediaBrowserView', manager: 'media2', module: 'media2'}),
	                    data: {
	                		'filter[mediaTypeId]': '', 
	                		'filter[mimeTypeId]': '',
	                		'getBrowser': true
	                	},
	                    success: function(r) {
	                		$("#media-browser-dialog").html(r.html);
	                    }
	                });
        		},
        		modal: true,
        		target: $(trigger).attr('id'),
        		attrId: $(trigger).attr('sgl:attr_id'),
        		resizable: true,
        		bgiframe: true,
        		dialogClass: 'media-browser-overlay'
        	});
    	} else {
    		$("#media-browser-dialog").dialog('option','target',$(trigger).attr('id'));
    		$("#media-browser-dialog").dialog('option','attrId',$(trigger).attr('sgl:attr_id'));
    	}
        
    	$("#media-browser-dialog").dialog('open');
    	
    	return false;
    },
    updateMediaField: function(e,attrId,mediaId){
		var $trigger = $('a.media-select[sgl:attr_id="'+attrId+'"]');

		$trigger.parents('div').eq(0).children('.image').hide()
    	.next('.ajaxLoader').show()
    	.next('.comment').hide();

	    $.ajax({
	        url: SGL2.Util.makeUrl({module: 'simplecms', action: 'renderMediaField'}),
	        data: {
	    	    attrId: $trigger.attr('sgl:attr_id'),
	    	    mediaId: mediaId,
	    	    attrName: $trigger.attr('sgl:attr_name')
	    	},
	        success: function(r) {
	        	// update media field
	            $trigger.parents('div').eq(0).html(r.html);

	            // re-bind
	            $('#media-select_' + $trigger.attr('sgl:attr_id'))
	            	.bind('selected.SGL2_Media2_Browser', CmsContent.Edit.updateMediaField);
	        }
	    });
	}

}