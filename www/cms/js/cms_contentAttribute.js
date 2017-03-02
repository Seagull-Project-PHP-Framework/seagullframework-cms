/**
 * Object creation until we have a sgl.namespace('sgl.subpackage') method
 */
if (typeof(cms) == "undefined") { cms = {}; }

sgl.string.Translation.init({
    lang: $('html').attr('lang'),
    dictionaries: ['cms']
});

cms.contentAttribute = {
    init: function() {
        cms.contentAttribute.creator.init();
        
        $('input[@name=attributeListId]').each(function(){
            cms.contentAttribute.editor.init(this.value);
        });
        
        $('#attributeList').accordion({
            header: 'div.accordionItemHead',
            active: false,
            alwaysOpen: false,
            animated: false,
            selectedClass: "opened",
            autoHeight: false
        });
        
    },
    deleteAttributeList: function(attributeListId){
        if (ok = confirm('Do you really want to remove this attribute list?'.translate())) {
            $.ajax({
                type : 'post',
                dataType : 'json',
                url : makeUrl({module: "cms", action: "deleteAttributeList"}),
                data : {
                    attributeListId: attributeListId
                },
                beforeSend : function(){
                    $('#attributeList').accordion( "disable" );
                },
                success : function(response,status){
                    if (response.status == -1) {
                        cms.showMessage(response.message.translate(),0);
                    } else {
                        $('input[@name=attributeListId][@value='+response.attributeListId+']')
                            .parents('div.accordionItem')
                            .fadeOut(500).remove();
                        $('#attributeList').accordion( "enable" );
                        cms.showMessage(response.message.translate(),1);
                    }            
                }
            });
        }
        return false;
    },
    creator: {
        toggle: function() {
            $('#newAttribListBox').toggle();
            $('#newAttribListBox').find('input[@name=name]')[0].focus();
        },
        cancel: function(cancelButton) {
            cms.contentAttribute.creator.toggle();
            $('#newAttribListBox')
                .find('form')
                .resetForm()
                .find('.error:first')
                .hide();
        },
        init: function() {
            jqForm = $('#newAttribListBox form');
            cms.contentAttribute.attributeList.init(jqForm);
            jqForm.find('input[@name=cancel]').click(function(event){
                cms.contentAttribute.creator.cancel(event.target);
            });
            jqForm.ajaxForm({
                url :         makeUrl({module: 'cms', action: 'addAttributeList'}),
                dataType :    'json',
                beforeSubmit: cms.contentAttribute.creator.beforeSubmit,
                success:      cms.contentAttribute.creator.success
            });
        },
        // beforeSubmit callback of form plugin
        beforeSubmit: function(formData, jqForm, options) {
            var listName = jqForm.find('input[@name=name]').fieldValue()[0];
            if (!listName) {
                jqForm.find('.error:first').text('Please provide list name'.translate()).css('color','red').show();
                return false;
            }
            
            if (jqForm.find('select[@name=listItems]').children().length == 0) {
                jqForm.find('.error:first').text('Please provide attribute list items'.translate()).css('color','red').show();
                return false;
            }
            jqForm.find('select[@name=listItems]').children().each(function(){
                formData.push( { name: 'aAttribList['+$(this).attr('value')+']', value: $(this).text() } );
            });
            
        },
        // success callback of form plugin
        success: function(response,status) {
            if (response.status == -1) {
                cms.showMessage(response.message.translate(),0);
            } else {
                $('#attributeList')
                    .prepend(response.html)
                    .accordion({
                        header: 'div.accordionItemHead',
                        active: false,
                        alwaysOpen: false,
                        animated: false,
                        selectedClass: "opened",
                        autoHeight: false
                    })
                    .accordion("activate",0);
                    
                cms.contentAttribute.editor.init(response.attributeListId);
                cms.contentAttribute.creator.cancel();
                cms.showMessage(response.message.translate(),1);
            }            
        }
    },
    editor: {
        init: function(attributeListId) {
            jqForm = $('input[@name=attributeListId][@value='+attributeListId+']').parents('form');
            cms.contentAttribute.attributeList.init(jqForm);
            
            jqForm.find('input[@name=cancel]').click(function(event){
                cms.contentAttribute.editor.cancel(attributeListId);
            });
            
            jqForm.ajaxForm({
                url :         makeUrl({module: 'cms', action: 'updateAttributeList'}),
                dataType :    'json',
                beforeSubmit: cms.contentAttribute.editor.beforeSubmit,
                success:      cms.contentAttribute.editor.success
            });
        },
        // beforeSubmit callback of form plugin
        beforeSubmit: function(formData, jqForm, options) {
            var listName = jqForm.find('input[@name=name]').fieldValue()[0];
            if (!listName) {
                jqForm.find('.error:first').text('Please provide list name'.translate()).css('color','red').show();
                return false;
            }
            if (jqForm.find('select[@name=listItems]').children().length == 0) {
                jqForm.find('.error:first').text('Please provide attribute list items'.translate()).css('color','red').show();
                return false;
            }
            jqForm.find('select[@name=listItems]').children().each(function(){
                formData.push( { name: 'aAttribList['+$(this).attr('value')+']', value: $(this).text() } );
            });
        },
        // success callback of form plugin
        success: function(response,status) {
            if (response.status == -1) {
                cms.showMessage(response.message.translate(),0);
            } else {
                // update list name
                var jqForm = $('input[@name=attributeListId][@value='+response.attributeListId+']')
                .parents('form');
                var listName = jqForm.find('input[@name=name]').fieldValue()[0];
                jqForm.parents('.accordionItem').find('.accordionItemHead h2 span').text(listName);            

                cms.showMessage(response.message.translate(),1);
            }            
        },
        cancel: function(attributeListId) {
            $('input[@name=attributeListId][@value='+attributeListId+']')
                .parents('form')
                .resetForm()
                .find('.error:first')
                .hide()
                .parents('#attributeList')
                    .accordion('activate',false);
        }
    },    
    // todo: make a jQuery plugin of this
    attributeList: {
        init: function(jqForm){
            jqForm.find('input[@name=listItemAdd]').click(function(event){
                cms.contentAttribute.attributeList.add(event.target);
            });
            jqForm.find('a[@name=listItemMoveUp]').click(function(event){
                cms.contentAttribute.attributeList.move(event.target,-1);
                return false;
            });
            jqForm.find('a[@name=listItemMoveDown]').click(function(event){
                cms.contentAttribute.attributeList.move(event.target,1);
                return false;
            });
            jqForm.find('input[@name=listItemRemove]').click(function(event){
                cms.contentAttribute.attributeList.remove(event.target);
            });
            jqForm.find('select[@name=listItems]').change(function(event){
                cms.contentAttribute.attributeList.focus(event.target);
            });
            jqForm.find('input[@name=fieldKey]').change(function(event){
                cms.contentAttribute.attributeList.keyFieldChange(event.target);
            });
            jqForm.find('input[@name=fieldValue]').blur(function(event){
                cms.contentAttribute.attributeList.valueFieldBlur(event.target);
            });
        },
        move: function(moveButton,delta) {
            var aAttribList,selectedItem,itemSelect = $(moveButton).parents('form:first').find('select[@name=listItems]');
            selectedItem = itemSelect.fieldValue()[0];
            if (!selectedItem) {
                alert('Please select an item to move'.translate());
                return false;
            }
            if (delta == 1) {
                itemSelect.children('[@selected]').insertAfter(itemSelect.children('[@selected]').next());
            } else {
                itemSelect.children('[@selected]').insertBefore(itemSelect.children('[@selected]').prev());
            }
        },
        
        add: function(addButton) {
            var $form = $(addButton).parents('form:first');
            if (!this.validate($form)) {
                $form.find('.error:first').text('Please provide field Key and Value'.translate()).css('color','red').show();
                return false;
            }
            $form.find('.error:first').hide();

            var attrKey = $form.find('input[@name=fieldKey]').fieldValue()[0];
            var attrName = $form.find('input[@name=fieldValue]').fieldValue()[0];
            var itemSelect = $form.find('select[@name=listItems]');
            
            itemSelect.addOption(attrKey,attrName,false);
            
            $form.find('input[@name=fieldValue]').attr('value','');
            $form.find('input[@name=fieldKey]').attr('value','');
            
            $form.find('input[@name=fieldKey]').trigger('change');
            
            $form.find('input[@name=fieldValue]')[0].focus();
            return true;
        },
        
        remove: function(removeButton) {
            var $form = $(removeButton).parents('form:first'), $list = $form.find('select[@name=listItems]');
            
            if (!$list.fieldValue().length) {
                alert('Please select an item to remove'.translate());
                return true;
            }
            
            if (ok = confirm('Are you sure you want to remove this item?'.translate())) {
                if ($list.fieldValue().length) {
                    $list.removeOption($list.fieldValue());
	                $form.find('input[@name=fieldKey]').val('').trigger('change');
	                $form.find('input[@name=fieldValue]').val('');
                }
            }
            return true;
        },
        
        focus: function(selectElement){
            var form = $(selectElement).parents('form:first');
            var selectedFieldKey = $(selectElement).fieldValue()[0];
            var selectedFieldName = $(selectElement).children("[@selected]").text();
            if (!selectedFieldKey) {
                return false;
            }
            form.find('input[@name=fieldKey]').val(selectedFieldKey).trigger('change');
            form.find('input[@name=fieldValue]').val(selectedFieldName);
        },

        keyFieldChange: function(keyField){
            var $keyField = $(keyField),$form = $keyField.parents('form:first'),isNewKey = true;
            $form.find('select[@name=listItems]').children().each(function(){
                if ($keyField.fieldValue()[0] == $(this).attr('value')) {
                    isNewKey = false;
                }
            });
            if (isNewKey) {
                $form.find('input[@name=listItemAdd]').attr('value','Add'.translate());
            } else {
                $form.find('input[@name=listItemAdd]').attr('value','Update'.translate());
            }
        },
        valueFieldBlur: function(valueField){
            var $valueField = $(valueField),$form = $valueField.parents('form:first');
            var $keyField = $form.find('input[@name=fieldKey]');
            
            if (!$form.find('input[@name=fieldKey]').attr('value').length) {
                $keyField.attr('value',$valueField.attr('value').sglCamelize());
                $form.find('input[@name=fieldKey]').trigger('change');
            }
        },

        validate: function(form) {
            var attrKey = form.find('input[@name=fieldKey]').fieldValue();
            var attrName = form.find('input[@name=fieldValue]').fieldValue();
            
            if(!attrKey[0] || !attrName[0]){
                return false;
            }
            return true;
        }
    }
};
