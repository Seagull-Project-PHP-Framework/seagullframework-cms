//  Selection object to work with cross-browser selected text
var Selection = Class.create();
Object.extend(Selection.prototype, {
    initialize: function(objWindow, objDocument) {
        this.window   = (typeof objWindow != 'undefined') ? objWindow : window;
        this.document = (typeof objContent != 'undefined') ? objDocument : document;
        this.selection = this._getSelection();
        this.range = this._getRange();
    },

    toString: function() {
        return this.selection.toString();
    },

    getLinkHref: function() {
        var ret = '';
        /* For gecko browsers */
        if (this.selection.getRangeAt) {
            var rngStart = this.range.startContainer;
            var rngEnd   = this.range.endContainer;
            //  Case where range is collapsed
            if (this.range.collapsed) {
                if (this.range.endContainer.parentNode.tagName == 'A') {
                    var aNodeAttributes = $A(this.range.endContainer.parentNode.attributes);
                    this.range.selectNode(this.range.endContainer.parentNode);
                }
            //  Case where full link is selected
            } else if (this.range.commonAncestorContainer.nodeType == 1) {
                var aChildNodes = $A(this.range.commonAncestorContainer.childNodes);
                if (aChildNodes[this.range.startOffset] &&
                    aChildNodes[this.range.startOffset].nodeName == 'A') {
                        var aNodeAttributes = $A(aChildNodes[this.range.startOffset].attributes);
                }
            //  Case where part of link is selected
            } else if (rngStart.parentNode == rngEnd.parentNode &&
                        ( rngStart.parentNode.tagName == 'A' ||
                          rngEnd.parentNode.tagName == 'A')) {
                var aNodeAttributes = $A(this.range.endContainer.parentNode.attributes);
                this.range.selectNode(this.range.endContainer.parentNode);
            }
            
            if (typeof aNodeAttributes != 'undefined') {
                aNodeAttributes.each( function(attribute) {
                    if (attribute.nodeName == 'href') ret = attribute.value;
                });
            }
            
        }
        else if(this.document.selection.createRange) {
            ret = 'MSIE';
        }
        return ret;
    },

    getMediaSrc: function() {
        var ret = '';
        /* For gecko browsers */
        if (this.selection.getRangeAt) {
            var rngStart = this.range.startContainer;
            var rngEnd   = this.range.endContainer;

            //  Case where full img is selected i.e. always
            if (this.range.commonAncestorContainer.nodeType == 1) {
                var aChildNodes = $A(this.range.commonAncestorContainer.childNodes);
                if (aChildNodes[this.range.startOffset] &&
                    aChildNodes[this.range.startOffset].nodeName == 'IMG') {
                        var aNodeAttributes = $A(aChildNodes[this.range.startOffset].attributes);
                }
            }
            
            if (typeof aNodeAttributes != 'undefined') {
                aNodeAttributes.each( function(attribute) {
                    if (attribute.nodeName == 'src') ret = attribute.value;
                });
            }
            
        }
        else if(this.document.selection.createRange) {
            ret = 'MSIE';
        }
        return ret;
    },

    _getSelection: function() {
        if (window.getSelection) {
    		return this.window.getSelection();
    	}
    	else if (document.getSelection)	{
    		return this.document.getSelection();
    	}
    	else if (document.selection) {
    		return this.document.selection.createRange().text;
    	}
    	else return;
    },

    _getRange: function() {
        if (this.selection.getRangeAt) {
            //this._debug(this.selection.getRangeAt(0));
            return this.selection.getRangeAt(0);
        }
        else if(this.document.selection.createRange) {
            this._debug(this.document.selection.createRange());
            return this.document.selection.createRange().htmlText;
        }
    },

    _debug: function(obj) {
        var ret = '';
        for (prop in obj) {
            if (typeof obj[prop] == 'function') {
                continue;
            }
            ret += prop +' => ' +obj[prop] +'\n';
        }
        alert(ret);
    }
});