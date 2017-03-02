CmsAttrib = {

    // dom names
    selectId: null,
    fieldKeyId: 'cms_fieldKey',
    fieldNameId: 'cms_fieldValue',

    devider: ' => ',

    init: function(selectId) {
        // select id container
        this.selectId = selectId;

        // init listeners
        var options = document.getElementById(this.selectId).options;
        for (var i = 0, len = options.length; i < len; i++) {
            options[i].onclick = CmsAttrib.updateCurrentField;
        }
    },

    /**
     * @static
     */
    addField: function() {
        // genarate name
        var fieldKey  = document.getElementById(CmsAttrib.fieldKeyId).value;
        var fieldName = document.getElementById(CmsAttrib.fieldNameId).value;
        var value     = fieldKey + CmsAttrib.devider + fieldName;

        // create options
        var opt     = new Option(value, value);
        var options = document.getElementById(CmsAttrib.selectId).options;
        options.add(opt);
        document.getElementById(CmsAttrib.selectId).selectedIndex = options.length - 1;
    },

    /**
     * @static
     */
    updateListItem: function() {
        var options = document.getElementById(CmsAttrib.selectId).options;
        var currentIndex = document.getElementById(CmsAttrib.selectId).selectedIndex;

        if (currentIndex == -1) {
            return;
        }

        // update fields
        var fieldKey  = document.getElementById(CmsAttrib.fieldKeyId).value;
        var fieldName = document.getElementById(CmsAttrib.fieldNameId).value;

        // update options
        options[currentIndex].value = fieldKey + CmsAttrib.devider + fieldName;
        options[currentIndex].innerHTML = options[currentIndex].value;
    },

    /**
     * @static
     */
    clearAll: function() {
        document.getElementById(CmsAttrib.selectId).selectedIndex = -1;

        // update fields
        document.getElementById(CmsAttrib.fieldKeyId).value = '';
        document.getElementById(CmsAttrib.fieldNameId).value = '';
    },

    /**
     * @static
     */
    updateCurrentField: function() {
        var options = document.getElementById(CmsAttrib.selectId).options;
        var currentIndex = document.getElementById(CmsAttrib.selectId).selectedIndex;
        var currentValue = options[currentIndex].value.split(CmsAttrib.devider);

        // update fields
        document.getElementById(CmsAttrib.fieldKeyId).value = currentValue[0];
        document.getElementById(CmsAttrib.fieldNameId).value = currentValue[1];
    },

    /**
     * @static
     */
    prepareFields: function() {
        var options = document.getElementById(CmsAttrib.selectId).options;
        var values  = '';
        for (var i = 0, len = options.length; i < len; i++) {
            values += escape(options[i].value) + ',';
        }
        if (values) {
            // update hidden field
            document.frmBlockMgr['fields'].value = values.substr(0, values.length-1);
        }
     }
}