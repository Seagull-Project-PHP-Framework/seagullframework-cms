/**
 * Object creation until we have a sgl.namespace('sgl.subpackage') method
 */
if (typeof(cms) == "undefined") { cms = {}; }


// We're gonna use translation sot let's initialize it
sgl.string.Translation.init({
    lang: $('html').attr('lang'),
    dictionaries: ['cms']
});

cms.category = {
    init: function(category_id) {
        if (category_id) {
            $('#'+ category_id).addClass('open').parents('li').addClass('open');
        }
        cms.category.widget.init();
        if (category_id) {
            $('#'+ category_id).children('span').addClass('active');
        }
    },
    
    initLangSwitcher: function(){
        $("#cLang").change(function() {
	        var selectedLang = $(this).val();
	        if (!selectedLang) {
	            return false;
	        } else {
		        var target,url = makeUrl({
			        module: "cms",
			        manager: "category",
			        frmCatID: $('input[@name="category[category_id]"]').attr('value')
		        });
		        target = url + 'cLang/' +selectedLang +'/';
		        window.location.href = target;
	        }
        });
    },
    
    widget : {
        init: function(){
	        $('.simpleTree').simpleTree({
	            autoclose:          true,
	            docToFolderConvert: true,
	            afterClick:         cms.category.widget.afterClick,
	            afterMove:          cms.category.widget.afterMove
	        });
        },
        afterClick: function(node){
            $.ajax({
                type : 'post',
                dataType: 'json',
                url : makeUrl({module: "cms", action: "outputCategoryEdit"}),
                data : {
                    "frmCatID": $(node).attr('id'),
                    "cLang": $('input[@name="cLang"]').attr('value')
                },
                beforeSend : function(){
                    $('#categoryDetails h3').addClass('loading');
                },
                success : function(response,status){
                    $('#categoryDetails').html(response);
                    $('a.addcat').attr('href',makeUrl({
                       module: "cms",
                       manager: "category",
                       action: "insert",
                       frmCatID: $('input[@name="category[category_id]"]').attr('value')
                    }));
                    cms.category.initLangSwitcher();
                }
            });
        },
        afterMove: function(destination, source, pos){
            var frmCatID = $(source).attr('id'),targetId,position;
            // its first child
            if (pos == 0) {
               if ($(source).next().next().length) {
                   position = 'BE';
                   targetId = $(source).next().next().attr('id');
               } else {
                   position = 'SUB';
                   targetId = $(destination).attr('id');
               }
            } else {
               if ($(source).prev().prev().length) {
                   position = 'AF';
                   targetId = $(source).prev().prev().attr('id');
               }
            }
            $.ajax({
                type : 'post',
                dataType : 'json',
                url : makeUrl({module: "cms", action: "reorderCategory"}),
                data : {
                    "frmCatID": frmCatID,
                    "targetId": targetId,
                    "position": position
                },
                success : function(response,status){
                    if (response.status) {
                        $.ajax({
                            type : 'post',
                            dataType: 'json',
                            url : makeUrl({module: "cms", action: "outputCategoryEdit"}),
                            data : {
                                "frmCatID": response.category_id,
                                "cLang"   : $('input[@name="cLang"]').attr('value')
                            },
                            success : function(response,status){
                                $('#categoryDetails').html(response);
                                cms.category.initLangSwitcher();
                            }
                        });
                        cms.showMessage(response.message,1);
                    } else {
                        cms.showMessage(response.message,0);
                    }
                }
            });
        }
    }
};