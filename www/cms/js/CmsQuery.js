CmsQuery = {

    // are populated during init
    urlAddQueryFilter: '',
    urlGetAttributes: '',
    urlGetQueryResult: '',

    // available filters
    aFilterOptions: {
        status: {
            name: 'Status',
            isActive: false
        },
        contentType: {
            name: 'Content type',
            isActive: false
        },
        createdBy: {
            name: 'Created by',
            isActive: false
        },
        contentName: {
            name: 'Content name',
            isActive: false
        }
    },

    addFilter: function() {
        var filterName = $('#filterOptions').fieldValue()[0];
        if (filterName) {
            this.initFilter(filterName);
            for (var i = 0; i < $('#filterOptions')[0].options.length; i++) {
                var option = $('#filterOptions')[0].options[i];
                if (filterName == option.value) {
                    $(option).remove();
                }
                if (!$('#filterOptions')[0].options.length) {
                    $('#filterOptionsContainer').hide();
                }
            }
        }
    },

    removeFilter: function(removeTrigger) {
        var filterName = removeTrigger.id.split('_')[1];

        // remove from panel
        var filterContainerName = filterName + 'Filter_container';
        $('#' + filterContainerName).remove();

        $('#filterOptionsContainer').show();
        this._prepareFilter(filterName);
    },

    initFilters: function(filters) {
        // ajax links
        this.urlAddQueryFilter = $('#ajaxAddQueryFilterUrl').fieldValue()[0];
        this.urlGetAttributes = $('#ajaxGetAttributesUrl').fieldValue()[0];
        this.urlGetQueryResult = $('#ajaxGetQueryResultUrl').fieldValue()[0];

        for (var filterName in this.aFilterOptions) {
            // init filter if it is not active
            if (!this.aFilterOptions[filterName].isActive) {
                var init = false;
                for (var i = 0; i < arguments.length && (init == false); i++) {
                    if (arguments[i] == filterName) {
                        init = true;
                    }
                }
                if (!init) {
                    this._prepareFilter(filterName);
                } else {
                    this.initFilter(filterName);
                }
            }
        }
        
        $('#filterPanel').ajaxForm({
            url : this.urlGetQueryResult,
            dataType : 'html',
            beforeSubmit: function(formData, jqForm, options) {
                var status,contentType,createdBy;

                for (var entryIndex in formData) {
                    if (formData[entryIndex].name == 'statusFilter') {
                        formData.push({name:'status', value:formData[entryIndex].value});
                    }
                    if (formData[entryIndex].name == 'contentTypeFilter') {
                        formData.push({name:'contentType', value:formData[entryIndex].value});
                    }
                    if (formData[entryIndex].name == 'createdByFilter') {
                        formData.push({name:'createdBy', value:formData[entryIndex].value});
                    }
                }
                
		        $('#attributeFilterPanel input.attributeFilter_value').each(function(){
                    formData.push( { name: 'aAttributes['+$(this).attr('id').split('_')[1]+']', value: $(this).fieldValue()[0] } );
		        });
	        },
            success : function(response,status) {
                $('#contentList-items').empty().append(response);
                $("#contentListTable").trigger("update");
                $('#contentListTable').show();
            }
        });
        
        $("#contentListTable").tablesorter({
            headers: { 
                0: { sorter: false }, 
                6: { sorter: false }, 
                7: { sorter: false }, 
            }, 
            widgets: ['zebra']
        }); 
    },

    initFilter: function(filterName) {
        // turn on filter
        this.aFilterOptions[filterName].isActive = true;

        var filterContainerName = filterName + 'Filter_container';
        $('#filterParameters').append('<div id="'+filterContainerName+'" class="filterContainer"></div>');

        $.ajax({
            type        : 'post',
            dataType    : 'html',
            url         : this.urlAddQueryFilter,
            data : {
                filterName: filterName
            },
            success : function(response,status){
                $('#' + filterContainerName).append(response);
                if (filterName == 'contentType') {
                CmsQuery.initAttributes()
                }
            }
        });
    },

    _prepareFilter: function(filterName) {
        // turn off filter
        this.aFilterOptions[filterName].isActive = false;
        // add node
        $('#filterOptions').append('<option value="' + filterName + '">' + this.aFilterOptions[filterName].name + '</option>');
    },

    initAttributes: function() {
        var contentTypeId = $('#contentTypeFilter').fieldValue()[0];
        
        $.ajax({
            type        : 'post',
            dataType    : 'json',
            url         : this.urlGetAttributes,
            data : {
                contentTypeId: contentTypeId
            },
            success : function(response,status){
                $('#attributesFilter').empty();
                $('#attributeFilterPanel').empty();
                for (var i = 0; i < response.length; i++) {
                    var oAttr = response[i];
                    $('#attributesFilter').append('<option value="' + oAttr.attr_name + '">' + oAttr.attr_alias + '</option>');
                }
            }
        });
    },

    addAttributeFilter: function() {
        // no more attributes left
        if (!$('#attributesFilter')[0].options.length) {
            return false;
        }

        var attrName  = $('#attributesFilter').fieldValue()[0];
        var attrLabel = $('#attributesFilter').children("[@selected]").text();
        var attrId    = 'attributesFilter_' + attrName;
        var wrapperId = 'attributeContainer_' + attrName;

        // remove from list
        $('#attributesFilter').children("[@selected]").remove();

        // build attribute html
        var label = '<label for="'+attrId+'">'+attrLabel+'</label>' 
        var input = '<input id="'+attrId+'" class="attributeFilter_value" name="'+attrId+'" type="text" value="" />'
        var button = '<input class="remove" name="removeAttribute" type="button" value="Remove" />';
        // onclick:"CmsQuery.removeAttributeFilter("' + wrapperId + '", "' + attrName + '", "' + attrLabel + '")"
        var wrapper = '<div id="'+wrapperId+'" class="attributeContainer"></div>'; 
        
        $('#attributeFilterPanel').append(wrapper);

        $('#'+wrapperId)
            .append(label)
            .append(input)
            .append(button);
            
        $('input[@name=removeAttribute]').click(function(event){
            var $container = $(event.target).parents('.attributeContainer');
            var containerId = $container.attr('id');
            var attrLabel = $container.find('label').text();
            var attrName = containerId.split('_')[1];

            CmsQuery.removeAttributeFilter(containerId, attrName, attrLabel);
        });
        
     },

     removeAttributeFilter: function(containerId, attrName, attrLabel) {
         $('#'+containerId).remove();
         $('#attributesFilter').append('<option value="' + attrName + '">' + attrLabel + '</option>');
     }
}