/**
 * Object creation until we have a sgl.namespace('sgl.subpackage') method
 */
if (typeof(cms) == "undefined") { cms = {}; }


// We're gonna use translation sot let's initialize it
sgl.string.Translation.init({
    lang: $('html').attr('lang'),
    dictionaries: ['cms']
});

$.extend(cms,{
    ATTR_TYPE_TEXT      : 1,
    ATTR_TYPE_LARGETEXT : 2,
    ATTR_TYPE_RICHTEXT  : 3,
    ATTR_TYPE_INT       : 4,
    ATTR_TYPE_FLOAT     : 5,
    ATTR_TYPE_URL       : 6,
    ATTR_TYPE_FILE      : 7,
    ATTR_TYPE_CHOICE    : 8,
    ATTR_TYPE_DATE      : 9,
    ATTR_TYPE_LIST      : 10,
    ATTR_TYPE_RADIO     : 11
});

cms.contentType = {
    aOpenedItems: [],
    aLoadedItems: [],

    /* Managing content types items */

    creator: {
        toggle: function() {
            $('#newContentTypeForm').toggle();
        },

        cancel: function() {
            $('#newContentType_errorMsg').hide();
            $('#newContentTypeForm').resetForm();
            $('#newContentTypeForm').toggle();
        },

        validate: function(formData, jqForm, options) {
            var form = jqForm[0];
            var contentTypeName = $('input[@id=contentType_name]').fieldValue();

            $('#newContentType_errorMsg').hide();
            if(!contentTypeName[0]){
                $('#newContentType_errorMsg').text('Please provide a name'.translate()).css('color','red').show();
                return false;
            }
        },
        
        save: function(response,status) {
            if (response.status == -1) {
                $('#newContentType_errorMsg').text(response.message.translate()).css('color','red').show();
            } else {
                var contentTypeId = response.id;
                $('#contentTypeList').prepend(response.html);
			    $('#contentType_' + contentTypeId + '_editForm').ajaxForm({
			        url :         makeUrl({module: 'cms', action: 'updateName'}),
			        dataType :    'json',
			        beforeSubmit: cms.contentType.editor.validate,
			        success:      cms.contentType.editor.save
			    });
                cms.contentType.creator.toggle();
                $('#newContentTypeForm').resetForm();
                cms.contentType.toggleContentType(contentTypeId);
            }            
        }
    },

    editor: {
        edit: function(contentTypeId) {
            $('#contentType_' +contentTypeId +'_view').hide();
            $('#contentType_' +contentTypeId +'_edit').show();
            // Hide attributes
            if (cms.contentType.aOpenedItems.indexOf(contentTypeId) != -1) {
                $('#contentType_' +contentTypeId +'_attributes').hide();
            }
            return false;
        },

        cancel: function(contentTypeId) {
            $('#contentType_' +contentTypeId +'_errorMsg').hide();
            $('#contentType_' +contentTypeId +'_editForm').resetForm();
            $('#contentType_' +contentTypeId +'_edit').hide();
            $('#contentType_' +contentTypeId +'_view').show();
            // Show back attributes if opened previously
            if (cms.contentType.aOpenedItems.indexOf(contentTypeId) != -1) {
                $('#contentType_' +contentTypeId +'_attributes').show();
            }
            return false;
        },

        validate: function(formData, jqForm, options) {
            var form = jqForm[0];
            var contentTypeId = form.contentTypeId.value;
            var contentTypeName = $('input[@id=contentType_'+contentTypeId+'_name]').fieldValue();

            $('#contentType_'+contentTypeId+'_errorMsg').hide();
            if(!contentTypeName[0]){
                $('#contentType_'+contentTypeId+'_errorMsg').text('Please provide a name'.translate()).css('color','red').show();
                return false;
            }
        },

        save: function(response,status) {
            if (response.status == -1) {
                var contentTypeId = response.contentTypeId;
                $('#contentType_'+contentTypeId+'_errorMsg').text(response.message.translate()).css('color','red').show();
            } else {
                var contentTypeId = parseInt(response.contentType.typeId);
                $('#contentType_' +contentTypeId +'_viewInfo').text(response.contentType.typeName);
                $('#contentType_' +contentTypeId +'_edit').hide();
                $('#contentType_' +contentTypeId +'_view').show();
                // Show back attributes if opened previously
                if (cms.contentType.aOpenedItems.indexOf(contentTypeId) != -1) {
                    $('#contentType_' +contentTypeId +'_attributes').show();
                }
            }            
        }
    },

    deleteContentType: function(contentTypeId) {
        if (ok = confirm('Do you really want to remove this content type?'.translate())) {
            $.ajax({
                type : 'post',
                url : makeUrl({module: "cms", action: "deleteContentType"}),
                data : {
                    "contentTypeId": contentTypeId
                },
                success : function(response,status){
                    $('#contentType_' + contentTypeId).fadeOut(500);
                }
            });
        }
        return false;
    },

    /*
     * Open/close a contentType to show/hide its attributes
     */
    toggleContentType: function(contentTypeId) {
        $('#contentType_' +contentTypeId +'_view').parent().toggleClass('closed').toggleClass('opened');
        // We check for visibility before the effect happens (because of its duration)
        if ($('contentType_' +contentTypeId +'_attributes').is(':visible')) {
            this.aOpenedItems = this.aOpenedItems.without(contentTypeId);
            $('#contentType_' +contentTypeId +'_attributes').hide();
        } else  {
            // simple case, contentType has been previsously loaded,
            // just show it again
            if (this.aLoadedItems.indexOf(contentTypeId) != -1) {
                $('#contentType_' +contentTypeId +'_attributes').toggle();
            } else {
                $.ajax({
                    type : 'post',
                    url : makeUrl({module: "cms", action: "outputContentTypeAttributes", 'contentTypeId': contentTypeId}),
                    dataType : "html",
                    beforeSend: function(){
                        $('#contentType_' +contentTypeId).addClass('loading');
                    },
                    success : function(response,status){
                        $('#contentType_' +contentTypeId).append(response);
                        // set up attribute edit forms
                        $('#contentType_' + contentTypeId + '_attributes form.attributeEditForm').ajaxForm({
					        url : makeUrl({module: 'cms', action: 'updateAttribute'}),
					        type: 'post',
					        dataType : 'json',
					        beforeSubmit: cms.contentType.attributeEditor.validate,
					        success: cms.contentType.attributeEditor.save
					    });
                        // set up new attribute form
                        $('#contentType_' + contentTypeId + '_attributes .newAttribute form.attributeEditForm').ajaxForm({
					        url : makeUrl({module: 'cms', action: 'addAttribute'}),
					        type: 'post',
					        dataType : 'json',
					        beforeSubmit: cms.contentType.attributeCreator.validate,
					        success: cms.contentType.attributeCreator.save
					    });
                        // add event handler for parameters					    
					    $('#contentType_' + contentTypeId + '_attributes .attrTypeSelect').change(function(event){
					        cms.contentType.parameter.mantainState(event.target);
					    });
					    
                        $('#contentType_' +contentTypeId).removeClass('loading');
                    }
                });
                // flag as loaded
                this.aLoadedItems.push(contentTypeId);
            }
            this.aOpenedItems.push(contentTypeId);
        } 
    },

    /* Managing attributes */

    attributeCreator: {
        create: function(contentTypeId) {
            $('#contentType_' +contentTypeId +'_newAttributeLink').hide();
            $('#contentType_' +contentTypeId +'_newAttributeForm').show();
            return false;
        },

        cancel: function(contentTypeId) {
            $('#contentType_' +contentTypeId +'_newAttribute_errorMsg').hide();
            $('#contentType_' +contentTypeId +'_newAttributeForm').resetForm();
            $('#contentType_' +contentTypeId +'_newAttributeForm').hide();
            $('#contentType_' +contentTypeId +'_newAttributeLink').show();
            return false;
        },

        validate: function(formData, jqForm, options) {
            var form = jqForm[0];
            var contentTypeId  = form.contentTypeId.value;
            var attributeName  = $('input[@id=contentType_'+contentTypeId+'_newAttribute_name]').fieldValue();
            var attributeAlias = $('input[@id=contentType_'+contentTypeId+'_newAttribute_alias]').fieldValue();
            
            $('#contentType_' +contentTypeId +'_newAttribute_errorMsg').hide();
            if(!attributeName[0] || !attributeAlias[0]){
                $('#contentType_' +contentTypeId +'_newAttribute_errorMsg').text('Please provide a name and alias'.translate()).css('color','red').show();
                return false;
            }
        },

        save: function(response,status) {
            if (response.status == -1) {
                var contentTypeId = response.contentTypeId;
                $('#contentType_'+contentTypeId+'_newAttribute_errorMsg').text(response.message.translate()).css('color','red').show().fadeOut(2500);
            } else {
                var contentTypeId = response.contentTypeId;
                $('#contentType_' + contentTypeId +'_newAttribute').before(response.html);
                $('#attrib_' + response.attribId + '_editForm').ajaxForm({
                    url : makeUrl({module: 'cms', action: 'updateAttribute'}),
                    type: 'post',
                    dataType : 'json',
                    beforeSubmit: cms.contentType.attributeEditor.validate,
                    success: cms.contentType.attributeEditor.save
                });
                // bind event handler for parameters                     
                $('#attrib_' + response.attribId + '_editForm .attrTypeSelect').change(function(event){
                    cms.contentType.parameter.mantainState(event.target);
                });
                $('#contentType_' + contentTypeId +'_newAttributeForm').resetForm();
            }            
        } 
    },

    attributeEditor: {
        edit: function(attribId) {
            $('#attrib_' + attribId + '_view').hide();
            $('#attrib_' + attribId + '_edit').show();
            $('#attrib_' + attribId + ' .attrTypeSelect').trigger('change');
            return false;
        },

        cancel: function(attribId) {
            $('#attrib_' +attribId +'_errorMsg').hide();
            $('#attrib_' +attribId +'_editForm').resetForm();
            $('#attrib_' +attribId +'_edit').hide();
            $('#attrib_' +attribId +'_view').show();
            return false;
        },

        validate: function(formData, jqForm, options) {
            var form = jqForm[0];
            var attributeId = form.attributeId.value;
            var attributeName = $('input[@id=attrib_'+attributeId+'_name]').fieldValue();
            
            $('#attrib_'+attributeId+'_errorMsg').hide();
            if(!attributeName[0]){
                $('#attrib_'+attributeId+'_errorMsg').text('Please provide a name and alias'.translate()).css('color','red').show();
                return false;
            }
        },

        save: function(response,status) {
            if (response.status == -1) {
                var attributeId = response.attributeId;
                $('#attrib_'+attributeId+'_errorMsg').text(response.message.translate()).css('color','red').show();
            } else {
                var infoString = response.alias +" (" +response.typeName +")";
                $('#attrib_' +response.id +'_viewInfo').text(infoString);
                // Set view mode back
                $('#attrib_' +response.id +'_edit').hide();
                $('#attrib_' +response.id +'_view').show();
            }            
        }
    },

    deleteAttribute: function(attribId) {
        if (ok = confirm('Do you really want to remove this attribute?'.translate())) {
            $.ajax({
                type : 'post',
                url : makeUrl({module: "cms", action: "deleteAttribute"}),
                data : {
			        "attributeId": attribId
			    },
                success : function(response,status){
                    $('#attrib_' + attribId).fadeOut(500);
                }
            });
        }
        return false;
    },

    parameter: {

        mantainState: function(element) {
            typeId  = parseInt($(element).fieldValue()[0]);
            if (this.parametersAllowed(typeId)) {
                $(element).parent().siblings('.attributeParams').find('.attrListType').removeAttr('disabled');
                $(element).parent().siblings('.attributeParams').show();
            } else {
                $(element).parent().siblings('.attributeParams').find('.attrListType').attr('disabled','disabled');
                $(element).parent().siblings('.attributeParams').hide();
            }
        },
        
        parametersAllowed: function(attributeType) {
            var aDisallowedTypes = [cms.ATTR_TYPE_LIST,cms.ATTR_TYPE_CHOICE,cms.ATTR_TYPE_RADIO];
            return (aDisallowedTypes.indexOf(attributeType) != -1) ? true : false;
        }
        
    },

    camelizeAttributeName: function(nameField, aliasValue) {
        $(nameField).attr('value',aliasValue.sglCamelize());
    },


    /* Managing Attribute lists */
    attributeList: {
        toggle: function() {
            $('#newAttribListBox').toggle();
        },
        save: function() {
            if (cms.contentType.attributeList.validate()) {
                var url = makeUrl({module: "cms", action: "addAttribList"});
                var params = Form.serialize($('newAttribList')) + '&elemList[elems]=' + aElems;
                new Ajax.Request(
                    url,
                    {asynchronous:true,
                     method:'post',
                     parameters: params,
                     onSuccess:cms.contentType.attributeList.handlerFunc}
                );
            }
        },

        validate: function() {
            if (!Field.present('elemList_name')) {
                alert('You must enter a name to create an attribute list');
                Field.focus('elemList_name');
                return false;
            }
            if (aElems == '') {
                alert('You must enter at least one attribute pair');
                Field.focus('listElementName');
                return false;
            }
            return true;
        },

        handlerFunc: function(t) {
            Element.update($('response'), t.responseText)
            Element.show('response');
            //Effect.Fade('response', {duration: 3.0});
        }
    }
};
