if (typeof cms == 'undefined') { var cms = {};}

// define Constants
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
    ATTR_TYPE_RADIO     : 11,

    showMessage: function(message, type) {
        jqMessage = $('#content-header > .message');
        if (jqMessage.length > 0) {
            jqMessage.hide();
            switch (type) {
            case 0:
                jqMessage.html('<div class="errorMessage">' + message +'</div>');
                break;
            case 1:
                jqMessage.html('<div class="infoMessage">' + message +'</div>');
                break;
            case 2:
            default:
                jqMessage.html('<div class="warningMessage">' + message +'</div>');
            }
            jqMessage.fadeIn('slow');
        } else {
            alert(message);
        }
    }
});

//  EXTENDING STRING OBJECT WITH sglCamelize METHOD
$.extend(String.prototype, {
    sglCamelize: function() {
        var oNoIllegalChars = this.replace(/[^a-zA-Z1-9_ ]/g, "");
        var oStringList = oNoIllegalChars.split(' ');
        if (oStringList.length == 1) {
            return oStringList[0].charAt(0).toLowerCase() + oStringList[0].substring(1).toLowerCase();
        }
        //  Always lower case first character
        var camelizedString = oStringList[0].charAt(0).toLowerCase() + oStringList[0].substring(1);
        //  Then upper case first character of other parts
        for (var i = 1, len = oStringList.length; i < len; i++) {
            var s = oStringList[i];
            camelizedString += s.charAt(0).toUpperCase() + s.substring(1).toLowerCase();
        }

        return camelizedString;
    },
    ucFirst: function() {
        return this.substr(0,1).toUpperCase() + this.substr(1,this.length);
    }
});

if (!Array.prototype.indexOf)
{
  Array.prototype.indexOf = function(elt /*, from*/)
  {
    var len = this.length;

    var from = Number(arguments[1]) || 0;
    from = (from < 0)
         ? Math.ceil(from)
         : Math.floor(from);
    if (from < 0)
      from += len;

    for (; from < len; from++)
    {
      if (from in this &&
          this[from] === elt)
        return from;
    }
    return -1;
  };
}

/*
var sglBroker = {};
$.extend(sglBroker, Event.Broker);
*/

/**
 * Extending Element to manage elements that have an (in)active status
 *
 * @param string    element  Element holding the (in)active status info
 * @param bool      activate Current status of element
 */
/*
Element.activate = function(element, activate) {
    element = $(element)

    var inactive_text = element['sgl:inactive_text']
    if(!inactive_text) {
        element['sgl:inactive_text'] = element.innerHTML
        inactive_text = element.innerHTML
    }

    var active_text = element.getAttribute('sgl:active_text') || element.innerHTML
    var active_class = element.getAttribute('sgl:active_class') || element.className

    if(!activate) {
        element.innerHTML = inactive_text
        if(active_class) {
            Element.removeClassName(element, active_class)
        }
        element['sgl:active'] = false
    } else {
        element.innerHTML = active_text
        if(active_class) {
            Element.addClassName(element, active_class)
        }
        element['sgl:active'] = true
    }
}
*/
