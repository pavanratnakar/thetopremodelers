window.ArticleMappingsView = Backbone.View.extend({
    initialize: function (e) {
        e = e || {};
        this.articleModel = e.articleModel || null;
        this.categoryModel = e.categoryModel;
    },
    render: function () {
        var check = true;

        $(this.el).html(this.template(_.extend(
            {
                articleModel: this.articleModel.models
            },
            {
                categoryModel: this.categoryModel.models
            }
        )));

        return this;
    },
    events: {
        "change .mapping-check": "mappingCheckChange"
    },
    change: function (event) {
        // Remove any existing alert message
        utils.hideAlert();

        // Apply the change to the model
        var target = event.target,
            change = {},
            check;

        change[target.name] = target.value;
        this.model.set(change);

        // Run validation rule (if any) on changed item
        check = this.model.validateItem(target.id);
        if (check.isValid === false) {
            utils.addValidationError(target.id, check.message);
        } else {
            utils.removeValidationError(target.id);
        }
    },
    beforeSave: function () {
        var self = this,
            check = this.model.validateAll();

        if (check.isValid === false) {
            utils.displayValidationErrors(check.messages);
            return false;
        }
        this.saveMapping();
        return false;
    },
    saveMapping: function () {
        var self = this;

        this.model.save(null, {
            success: function (model) {
                self.render();
                app.navigate('articles/' + model.get('id'), true);
                utils.showAlert('Success!', 'Mapping saved successfully', 'alert-success');
            },
            error: function () {
                utils.showAlert('Error', 'An error occurred while trying to save this item', 'alert-error');
            }
        });
    },
    mappingClick: function(target) {
        var category_id = target.data('catid'),
            article_id = target.data('articleid'),
            id = target.data('mapid');

        var am = new ArticleMapping({
            category_id: category_id,
            article_id: article_id
        });

        if (id) {
            am.set('id', id);
        }
        if (target.is(':checked')) {
            // add
            am.save(null, {
                success: function(model){
                    utils.showAlert('Success!', 'Mapping saved successfully', 'alert-success');
                },
                error: function(){
                    utils.showAlert('Error', 'An error occurred while trying to save this item', 'alert-error');
                }
            });
        } else {
            // delete
            am.destroy({
                success: function () {
                    target.data('mapid', '');
                    utils.showAlert('Success!', 'Mapping deleted successfully', 'alert-success');
                }
            });
        }
    },
    mappingCheckChange: function(e) {
        this.mappingClick($(e.target));
    }
});