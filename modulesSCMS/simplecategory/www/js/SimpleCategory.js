if (typeof SimpleCategory == 'undefined') { SimpleCategory = {}; }

/**
 * Categories management.
 *
 * @package simplecategory
 * @author Dmitri Lakachauskis <lakiboy83@gmail.com>
 */
SimpleCategory.List =
{
    init: function() {
        var self = this;

        // tree refresh
        $('#category-nav-lang').change(function() {
            self.Tree.lock().refresh();
        });

        // category update
        $('#category-container').ajaxForm({
            beforeSubmit: function(elems, f) {
                self.Category.lock();
            },
            data: {adminGuiAllowed: 1},
            dataType: 'json',
            success: function(r, msg, f) {
                // new records were added, refresh
                if (r.isNew) {
                    self.Tree.lock().refresh();
                // old record was updated, update name
                } else {
                    $('#category_' + r.categoryId + ' span').eq(0).html(r.name);
                }
                $('#category-edit').html(r.html);
                self.Category.init().unlock();
            }
        });

        // add root category
        $('#category-add').click(function() {
            self.Category.loadNew(0, $('#category-nav-lang').val());
            return false;
        });

        this.Tree.init();
    },

    Category: {
        init: function() {
            var self = this;
            $('#category_language-id').change(function() {
                self.load($('#category_id').val(), $(this).val(), 'category');
            });
            return this;
        },

        load: function(id, langId, type) {
            var self = this;
            if (type == 'category') {
                self.lock();
            } else {
                SimpleCategory.List.Tree.lock();
            }
            $.ajax({
                url: SGL2.Util.makeUrl({
                    module: 'simplecategory',
                    action: 'getCategoryEditScreen',
                    categoryId: id,
                    langId: langId,
                    adminGuiAllowed: 1
                }),
                dataType: 'json',
                success: function(r) {
                    $('#category-edit').html(r.html);
                    self.init();

                    if (type == 'category') {
                        self.unlock();
                    } else {
                        SimpleCategory.List.Tree.unlock();
                    }
                }
            });
            return this;
        },

        loadNew: function(parentId, langId) {
            SimpleCategory.List.Tree.lock();
            $.ajax({
                url: SGL2.Util.makeUrl({
                    module: 'simplecategory',
                    action: 'getCategoryAddScreen',
                    parentId: parentId,
                    langId: langId
                }),
                dataType: 'json',
                success: function(r) {
                    $('#category-edit').html(r.html);
                    SimpleCategory.List.Tree.unlock();
                }
            });
            return this;
        },

        lock: function() {
            SGL2.Util.disableSubmit('input, select', '#category-container');
            $('.triggers', '#category-container').hide().next('.ajaxLoader').show();
            return this;
        },

        unlock: function() {
            SGL2.Util.enableSubmit('input, select', '#category-container');
            $('.triggers', '#category-container').show().next('.ajaxLoader').hide();
            return this;
        }
    },

    Tree: {
        init: function() {
            var self = this;
            $('#category-nav').simpleTree({
                // custom option
                baseUrl: SGL_WEBROOT + '/admin/images/jquery/simpletree/',

                // tree params
                autoclose: false,
                docToFolderConvert: true,

                // callbacks
                afterClick: function($node) {
                    var c = SimpleCategory.List.Category;
                    c.load($node.attr('id').split('_')[1], $('#category-nav-lang').val(), 'tree');
                },
                afterMove: function($parent, $elem, pos) {
                    self.lock();

                    var parentId = $parent.attr('id').split('_');
                    parentId = parentId.length == 1 ? 0 : parentId[1];
                    $.ajax({
                        url: SGL2.Util.makeUrl({module: 'simplecategory', action: 'moveCategory'}),
                        data: {
                            categoryId: $elem.attr('id').split('_')[1],
                            parentId: parentId,
                            orderId: pos
                        },
                        method: 'post',
                        success: function(r) {
                            self.unlock();
                        }
                    });
                }
            });

            $('#category-nav a.del').click(function() {
                if (confirm('confirm category deletion'.translate())) {
                    self.lock();
                    $.ajax({
                        url: SGL2.Util.makeUrl({module: 'simplecategory', action: 'deleteCategory'}),
                        data: {categoryId: this.id.split('_')[1]},
                        success: function(r) {
                            self.refresh();
                        }
                    });
                }
                return false;
            });

            $('#category-nav a.add').click(function() {
                var c = SimpleCategory.List.Category;
                c.loadNew(this.id.split('_')[1], $('#category-nav-lang').val());
                return false;
            });

            return this;
        },

        refresh: function() {
            var self = this;
            $.ajax({
                url: SGL2.Util.makeUrl({
                    module: 'simplecategory',
                    action: 'refreshCategoryTree',
                    langId: $('#category-nav-lang').val()
                }),
                dataType: 'json',
                success: function(r) {
                    $('#category-nav ul.simpleTree').remove();
                    $('#category-nav').append(r.html);
                    self.init().unlock();
                }
            });
            return this;
        },

        lock: function() {
            SGL2.Util.disableSubmit('#category-nav-lang');
            $('#category-nav-lang').next('.ajaxLoader').show();
            return this;
        },

        unlock: function() {
            SGL2.Util.enableSubmit('#category-nav-lang');
            $('#category-nav-lang').next('.ajaxLoader').hide();
            return this;
        }
    }
}