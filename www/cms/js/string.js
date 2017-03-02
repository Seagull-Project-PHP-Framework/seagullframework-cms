/**
 * Object creation until we have a sgl.namespace('sgl.subpackage') method
 */
if (typeof(sgl) == "undefined") { sgl = {}; }
if (typeof(sgl.string) == "undefined") { sgl.string = {}; }

/**
 * Translation class
 */
sgl.string.Translation = {
    lang: '', charset: '', aDictionaries: [], aTranslations: {},
    options: {
        'lang'      : 'en',
        'charset'   : 'utf-8',
        'defaultDictionary': true,
        'dictionaries': []
    },

    /**
     * Initialise the Translation object with options
     * and get the translations
     */
    init: function(options) {
        // override default options
        if (typeof options != "undefined") {
            this.options = $.extend(this.options, options); 
        }
        this.lang = this.options.lang;
        this.charset = this.options.charset;
        this.options.defaultDictionary ? this.aDictionaries.push('default') : '';
        
        if (this.options.dictionaries.length) {
            $.each(this.options.dictionaries,function(i, val) {
                sgl.string.Translation.aDictionaries.push(val);
            });
        }
        this.getTranslations();
    },

    checkForjQuery: function() { /* TODO: move to sgl.util */
        if((typeof jQuery=='undefined')) {
            throw("sgl.Translation requires the jQuery JavaScript framework");
        }
    },

    getFullLang: function() {
        return this.lang +'-' +this.charset;
    },

    getLang: function() {
        return this.lang;
    },

    setLang: function(language) {
        this.lang = language;
    },

    getCharset: function() {
        return this.charset;
    },

    setCharset: function(charset) {
        this.charset = charset;
    },

    getDictionaries: function() {
        var dictionaryList = '';
        $.each(this.aDictionaries,function(i,dict) {
            dictionaryList += (dictionaryList.length > 0)
                ? ',' +dict
                : dict;
        });
        return dictionaryList;
    },

    getTranslations: function() {
        // Ensure jQuery library is loaded
        this.checkForjQuery();
        
        var dictionaryList = this.getDictionaries();
        if (!dictionaryList.length) return;
        
        $.ajax({
            type : "POST",
            url : makeUrl({module: "cms", action: "getTranslations"}),
            data : {
                "lang": this.getFullLang(),
                "dictionary": dictionaryList
            },
            dataType : "json",
            success : this.loadTranslations
        });
    },

    loadTranslations: function(response) {
        if (response.status) {
            var fullLang = sgl.string.Translation.getFullLang();
            sgl.string.Translation.aTranslations = response.translations;
        }
        sgl.string.Translation.ready = true;
    },

    /**
     * Main function to retrieve a translated string
     */
    get: function(word) {
        if (typeof word == 'undefined') return;
        var fullLang = this.lang +'-' +this.charset;

        // Ensure translations are loaded before requesting
        if (!this.aTranslations) throw("No language dictionnary available");
        return (this.aTranslations[word])
            ? this.aTranslations[word]
            : '>' +word +'<';
    }
}
/**
 * Translation function
 * @param   String  word
 * @return  String  translated word
 */
 
sgl.string.translate = function(word) {
    return sgl.string.Translation.get(word);
}

/**
 * Alias for the sgl.string.translate() method
 * Simply call $T('string to translate');
 */
var $T = sgl.string.Translation.get;

/**
 * Extending the String.prototype Object with sgl.string.translate method
 * to allow native translation on strings
 * E.g : 'string to translate'.translate();
 */
$.extend(String.prototype, {
    translate: function() {
        return sgl.string.translate(this);
    }
});
