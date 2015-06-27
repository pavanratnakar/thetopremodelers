window.MetaView = Backbone.View.extend({
    initialize: function () {
        this.render();
    },
    render: function () {
        var placeModel = (this.options.placeModel && this.options.placeModel.toJSON()) || {},
            categoryModel = (this.options.categoryModel && this.options.categoryModel.toJSON()) || {};

        $(this.el).html(this.template({
            model: this.model,
            placeModel: placeModel,
            categoryModel: categoryModel
        }));
        return this;
    },
    events: {
        "change": "change",
        "click .save" : "beforeSave",
        "click .delete" : "deleteMeta"
    },
    change: function (event) {
        // Remove any existing alert message
        utils.hideAlert();

        // Apply the change to the model
        var target = event.target;
        var change = {};
        change[target.name] = target.value;
        this.model.set(change);

        // Run validation rule (if any) on changed item
        var check = this.model.validateItem(target.id);
        if (check.isValid === false) {
            utils.addValidationError(target.id, check.message);
        } else {
            utils.removeValidationError(target.id);
        }
    },
    beforeSave: function (e) {
        var self = this;
        var check = this.model.validateAll();
        var matching = {};
        if (check.isValid === false) {
            utils.displayValidationErrors(check.messages);
            return false;
        }
        if ($('#category_id').val()) {
            matching.categoryId = $('#category_id').val();
        }
        if ($('#place_id').val()) {
            matching.placeId = $('#place_id').val();
        }
        this.model.set('matching', JSON.stringify(matching));
        this.saveMeta();
        return false;
    },
    saveMeta: function () {
        var self = this;
        this.model.save(null, {
            success: function (model) {
                self.render();
                app.navigate('metas/' + model.id, false);
                utils.showAlert('Success!', 'Meta saved successfully', 'alert-success');
            },
            error: function () {
                utils.showAlert('Error', 'An error occurred while trying to delete this item', 'alert-error');
            }
        });
    },
    deleteMeta: function () {
        this.model.destroy({
            success: function () {
                alert('Meta deleted successfully');
                window.history.back();
            }
        });
        return false;
    }
});