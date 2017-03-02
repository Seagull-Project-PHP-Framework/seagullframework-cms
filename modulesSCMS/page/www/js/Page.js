if (typeof Page == 'undefined') { Page = {}; }

Page.List =
{
    Filter: $.extend(CmsContent.List.Filter,
    {
        loadData: function(pageId) {
            var self = this;
            self.lock();
            $.ajax({
                url: SGL2.Util.makeUrl({
                    module: 'page',
                    manager: 'page', // we need to keep this!!!
                    action: 'getPageFilteredList'
                }),
                data: {
                    siteId: $('#filter_site_value').val(),
                    parentId: $('#filter_parent_value').val(),
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

        initDataTriggers: function() {
            return this;
        }
    }),

    init: function() {

	    // blocks
	    $('#page-filter select').change(function() {
	    	document.location.href = $(this).val();
	    });
	    $('#page-create .button').click(function() {
	    	document.location.href = $('#page-create select').val();
	    	return false;
	    });

        // list filter
        this.Filter.init('#page-list').initDataTriggers().initPageTriggers();
    }
};

Page.Edit =
{
    init: function() {
        $('div#page-panel-tabs').tabs();

        // for page creation only
        if ($('select#page_site').length) {
            $('select#page_site, select#page_language').change(function() {
                document.location.href = $(this).val();
            });
            $('#page_title').keyup(function() {
                var route = $(this).val()
                    .replace(/\s/g, '-')
                    .replace(/[^\/_a-z0-9]/ig, '-').toLowerCase();
                route = route ? '/' + route : '';
                $('#page_route').val(route);
                $('#page-route-container span.route').text(route);
                $('span.page-title').text($(this).val());
            });

        // for page edit
        } else {

            // tree
            $('#page-tree').simpleTree({
                // custom option
                baseUrl: SGL_WEBROOT + '/admin/images/jquery/simpletree/',

                // tree params
                autoclose: false,
                docToFolderConvert: true,

                // callbacks
                afterClick: function($node) {
                    $('#page-tree h2 img').show();
                    var pageId = $node.attr('id').split('_')[1];
                    // fixme: URL hack
                    document.location.href = SGL2.Util.makeUrl({
                        module: 'page',
                        manager: 'edit'
                    }) + pageId + '/' + $('#page_language').val() + '/';
                },
                afterMove: function($parent, $elem, pos) {
                    // to edit mode
                    $('#page-tree h2 img').show();

                    var parentId = $parent.attr('id').split('_');
                    parentId = parentId.length == 1 ? 0 : parentId[1];
                    $.ajax({
                        url: SGL2.Util.makeUrl({module: 'page', action: 'movePage'}),
                        data: {
                            siteId: $('#page_site').val(),
                            pageId: $elem.attr('id').split('_')[1],
                            parentId: parentId,
                            orderId: pos
                        },
                        method: 'post',
                        success: function(r) {

                            // update path
                            if ($elem.attr('id').split('_')[1] == $('#page_page-id').val()) {

                                // build path
                                var aPath = [], oNode, str = '';
                                if ($elem.parents('li').length) {
                                    aPath = $.makeArray($elem.parents('li'));
                                }
                                aPath.unshift($elem[0]);

                                // create new path string
                                while (aPath.length && (oNode = aPath.pop())) {
                                    if (str) {
                                        str += '&nbsp;&nbsp;&gt;&nbsp;&nbsp;';
                                    }
                                    str += $('span', oNode).eq(0).text();
                                }

                                // update current path
                                $('#page-path-container div').html(str);
                            }

                            // to view mode
                            $('#page-tree h2 img').hide();
                        }
                    });
                }
            });

            // maintain tree state
            (function() {
                var pageId = $('#page_page-id').val(), $oNode, parent, aParents;
                $oNode = $('#page-item_' + pageId);

                // open all parent nodes
                var aParents = $.makeArray($oNode.parents('li'));
                while (aParents.length && (parent = aParents.pop())) {
                    if ($(parent).hasClass('folder-close')) {
                        $(parent).removeClass('folder-close').addClass('folder-open');
                        $('ul', parent).eq(0).show();
                    }
                    if ($(parent).hasClass('folder-close-last')) {
                        $(parent).removeClass('folder-close-last').addClass('folder-open-last');
                        $('ul', parent).eq(0).show();
                    }
                }
                // make current node active
                $oNode.find('span.text').eq(0).removeClass('text').addClass('active');
            })();

            // delete page
            $('a.page-delete').click(function() {
                if (confirm('confirm page deletion'.translate())) {
                    var elem = this;
                    $(elem).hide().next('.ajaxLoader').show();
                    $.ajax({
                        url: SGL2.Util.makeUrl({module: 'page', action: 'deletePage'}),
                        data: {pageId: elem.id.split('_')[1]},
                        dataType: 'json',
                        success: function(r) {
                            if (r.redir) {
                                document.location.href = r.redir;
                            } else {
                                $(elem).show().next('.ajaxLoader').hide();
                            }
                        }
                    });
                }
                return false;
            });
            
            $('#page_title').change(function() {
                $('span.page-title').text($(this).val());
            });
            

            // switch language
            $('#page_language').change(function() {
                var elem = this;
                // to edit mode
                $(elem).next('.ajaxLoader').show();
                $.ajax({
                    url: SGL2.Util.makeUrl({module: 'page', action: 'getPageData'}),
                    data: {pageId: $('#page_page-id').val(), langId: $(elem).val()},
                    success: function(r) {
                        var langCode = $(elem).val(), langText, langImg;

                        // update field values
                        $('#page_title').val('').val(r.oPage.title);
                        $('span.page-title').text(r.oPage.title);
                        $('#page_meta-desc').val('').val(r.oPage.meta_desc);
                        $('#page_meta-key').val('').val(r.oPage.meta_key);

                        // update content language
                        langText = $('option[value="' + langCode + '"]', elem).text();
                        langImg  = SGL_WEBROOT + '/themes/' + SGL_THEME + '/images/icons/flags/' + langCode + '.gif';

                        $('#content-language-container div img')
                            .attr('src', langImg)
                            .attr('alt', langText)
                            .attr('title', langText);
                        $('#content-language-container div span').text(langText);

                        // to view mode
                        $(elem).next('.ajaxLoader').hide();
                    }
                });
            });
        }

        // update full page URL on route change
        $('#page_route').keyup(function() {
            var route = $(this).val()
                .replace(/\s/g, '-')
                .replace(/[^\/_a-z0-9]/ig, '-').toLowerCase();
            $('#page-route-container span.route').text($(this).val(route).val());
        });

        // remove linked content
        $('#page-content-container a').click(function() {
            $('#page_content-id').val('');
            $(this).hide().prev('span').text('no page content set'.translate());
            return false;
        });

        // associate content
        $('input#content_value').suggest(
            SGL2.Util.makeUrl({module: 'simplecms', action: 'matchContentsByPattern'}), {
            dataType: 'html',
            appendTo: $('#content_value').parent(),
            params: ['input[name="content[langId]"]', '#content_type'],
            onSelect: function() {
                var aSearchParts = this.value.split('('), id, title;

                // extract content ID and title
                id    = aSearchParts.pop();
                id    = id.substring(0, id.length - 1);
                title = $.trim(aSearchParts.join('('));

                $('#page-content-container span').text(this.value);
                $('#page-content-container a').show();
                $('#page_content-id').val(id);
                
                // update route params
                $('input[name="route[__params]"]').val('frmContentId/' + id);

                // update current value
                this.value = title;
            }
        });

        // insert/update
        $('#page-container').ajaxForm({
            beforeSubmit: function(elems, f) {
                // to edit mode
                SGL2.Util.disableSubmit('input, select', f);
                $('.triggers').hide().next('.ajaxLoader').show();
            },
            data: {adminGuiAllowed: 1},
            dataType: 'json',
            success: function(r, msg, f) {
                if (r.redir) {
                    document.location.href = r.redir;
                } else {
                    // to view mode
                    SGL2.Util.enableSubmit('input, select', f);
                    $('.triggers').show().next('.ajaxLoader').hide();
                }
            }
        });
        
		// route widget setup
        $('.route-widget-container').modulematrix();

		$('div.route-widget-controller a').click(function(){
	    	$('div.route-widget-controller a,div.route-widget').toggle();
	    	return false;
	    });
    }
};
