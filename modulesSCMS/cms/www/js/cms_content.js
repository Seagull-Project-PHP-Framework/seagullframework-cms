if (typeof cms == 'undefined') { var cms = {};}

sgl.string.Translation.init({
    lang: $('html').attr('lang'),
    dictionaries: ['cms']
});

/**
 * CONTENT STATIC CLASS METHODS
 **/
cms.content = {
    init: function() {
        cms.content.filter.init();

        $.tablesorter.addParser({
                // set a unique id
                id: 'dates',
                is: function(s) {
                        // return false so this parser is not auto detected
                        return false;
                },
                format: function(s) {
                    // split
                    var a = s.split('.');
                    // glue and return a new date
                    return new Date(a.reverse().join("/")).getTime();
                },
                // set type, either numeric or text
                type: 'numeric'
        });
        $.tablesorter.addWidget({
            id: "hover",
            format: function(table) {
                $("tbody tr", table).each(function() {
                    $(this).hover(
                        function () {
                            var $rows = $(this).children();
                            $rows.each(function(){
                                $(this).addClass("hover");
                            })
                        },
                        function () {
                            var $rows = $(this).children();
                            $rows.each(function(){
                                $(this).removeClass("hover");
                            })
                        }
                    );
                })
            }
        }); 
        $("#contentListTable").tablesorter({
            headers: {
                0: { sorter: false },
                3: { sorter: 'dates' },
                7: { sorter: false }
            },
            sortList: [[3,1]],
            widgets: ['zebra','hover']
        });

    },
    filter: {
        init: function() {
            $('#frmFilterContent').ajaxForm({
                url:      makeUrl({module: 'cms', action: 'outputFilteredContents'}),
                dataType: 'json',
                success:  function(response,status){
                    if (response.status == -1) {
                        cms.showMessage(response.message.translate(),0);
                    } else {
                        $('#contentList tbody').empty().append(response.html);
                        $('#contentList-pager').empty().append(response.pager);

                        $("#contentListTable").trigger("update");
                        cms.showMessage(response.message.translate(),1);
                    }
                }
            });
            $('#pagerOptions').change(function(event){
                $('#contentFilter_resPerPage').attr('value',$(event.target).fieldValue()[0]);
                $('#frmFilterContent').trigger('submit');
            });
        },

        toggle: function() {
            $('#contentListFilter').toggle();
            $('#contentListFilterShowAll').toggle();
            $('.cmsBox').slideToggle(200);
        }
    },

    addContent: function() {
        var contentTypeId = $(":input[@id='comboxContentType-menu']").fieldValue()[0];
        if (typeof contentTypeId == 'undefined' || isNaN(contentTypeId)) {
            alert('An error occured, this is not a valid contentTypeId to create a content');
        } else {
            var addContentUrl = makeUrl({"module": "cms", "manager": "content", "action": "add", "frmContentTypeId": contentTypeId});
            window.location.href = addContentUrl;
        }
    },

    editor: {
        editMode: false,
        contentNameId: 'contentName', // encapsulate content name
        contentNameOriginal: null, // original content name
        aEditZones: [],

        init: function() {
            $('#contentName').change(function(event){
                //cms.contentAttribute.attributeList.clear(event.target);
                cms.content.editor.checkContentNameUniqueness();
            });
        },

        loadRichTextAttributes: function() {
        },

        validateForm: function() {
            var _continue = true;
            return _continue;
        },

        /**
         * Check if content name value is unique.
         */
        checkContentNameUniqueness: function() {
            var contentName = $('#contentName').val();
            var contentNameOriginal = $('#contentNameOriginal').fieldValue()[0];
            var contentId = $('input[@name="content[id]"]').fieldValue()[0];

            // do nothing if content name is empty or unchanged
            if (!contentName || (contentName == contentNameOriginal)) {
                return;
            }

            $.ajax({
                type :      'post',
                dataType :  'json',
                url :       makeUrl({module: 'cms', action: 'isContentNameUnique'}),
                data : {
                    contentName: contentName,
                    contentId: contentId
                },
                success :   function(response,status){
                    if (!response) {
                        $('#contentEditFormTrigger').hide();
                        cms.showMessage('Content name already exists'.translate(),0);
                    } else {
                        $('#contentEditFormTrigger').show();
                    }
                }
            });
        }
    },

    linkEditor: {
        init: function(linkType) {
            this.aLinkTypeSelects = $$('#linkTypePane input.linkTypeSelect');
            this.aLinkResourcePanes = $$('#linkResourcePane div.linkResourcePane');
            $A(this.aLinkTypeSelects).each(function(radioButton) {
                if (radioButton.value == 'type_' +linkType) {
                    // Flag current radio button as checked
                    radioButton.checked = true;
                    this.currentRadioButton = radioButton;
                }
                // Attach radio button onchange event
                Event.observe(radioButton, 'change', cms.content.linkEditor.togglePanes.bind(this, radioButton.id))
            }.bind(this));

            // Attach some events handlers
            Event.observe('linkHref-editField', 'keyup', this.setExternalHref.bindAsEventListener(this));
            Event.observe('linkHref-selectField', 'change', this.setInternalHref.bindAsEventListener(this));

            this.togglePanes(this.currentRadioButton.id);
        },

        togglePanes: function(linkTypeId) {
            $A(this.aLinkTypeSelects).each(function(radioButton) {
                if (radioButton.id == linkTypeId) {
                    radioButton.parentNode.addClassName('selected');
                } else {
                    radioButton.parentNode.removeClassName('selected');
                }
            });
            $A(this.aLinkResourcePanes).each(function(pane) {
                if (pane.id == linkTypeId +'-pane') {
                    pane.show();
                } else {
                    pane.hide();
                }
            });
        },

        setExternalHref: function(event) {
            var element = Event.element(event);
            if (!element.value.match(/^\w+:\/\//)) {
                element.value = 'http://' +element.value;
            }
            $('linkHref-editValue').value = element.value;
        },

        setInternalHref: function(event) {
            var element = Event.element(event);
            var resourceUri = $F(element) || '';
            var frontController = (SGL_JS_FRONT_CONTROLLER) ? SGL_JS_FRONT_CONTROLLER +'/' : '';
            $('linkHref-editValue').value = SGL_JS_WEBROOT +'/' +frontController +resourceUri;
        },

        toggleChildrenPages: function(element) {
            var element = $(element).parentNode;
            if (element.hasClassName('current')) {
                element.removeClassName('current')
            } else {
                element.addClassName('current');
            }
        }
    }
};