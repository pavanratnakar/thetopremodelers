window.ContractorReviewView = Backbone.View.extend({
    initialize: function (e) {
        this.placeModel = e.placeModel;
    },
    render: function () {
        $(this.el).html(this.template(_.extend(this.model.toJSON(), {
            placeModel: this.placeModel.models
        })));
        return this;
    },
    events: {
        "change": "change",
        "click .save": "beforeSave",
        "click .delete": "deleteReview"
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
        this.saveReview();
        return false;
    },
    saveReview: function () {
        var self = this;

        this.model.save(null, {
            success: function (model) {
                self.render();
                app.navigate('contractorReview/' + model.contractorRating_id, false);
                utils.showAlert('Success!', 'Review saved successfully', 'alert-success');
            },
            error: function () {
                utils.showAlert('Error', 'An error occurred while trying to delete this item', 'alert-error');
            }
        });
    },
    deleteReview: function () {
        this.model.destroy({
            success: function () {
                alert('Review deleted successfully');
                window.history.back();
            }
        });
        return false;
    }
});