window.ContractorMappingsView = Backbone.View.extend({
    initialize: function (e) {
        e = e || {};
        this.contractorModel = e.contractorModel || null;
    },
    render: function () {
        $(this.el).html(this.template(_.extend(
            this.model.toJSON(),
            this.contractorModel.mappingGroupedByPlace
        )));
        return this;
    },
    events: {
        "click .delete": "deleteMapping",
        "change .mapping-check": "mappingCheckChange",
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
                app.navigate('contractors/' + model.get('contractor_id'), true);
                utils.showAlert('Success!', 'Mapping saved successfully', 'alert-success');
            },
            error: function () {
                utils.showAlert('Error', 'An error occurred while trying to delete this item', 'alert-error');
            }
        });
    },
    deleteMapping: function () {
        this.model.destroy({
            success: function () {
                alert('Mapping deleted successfully');
                window.history.back();
            }
        });
        return false;
    },
    mappingCheckChange: function(e) {
        var categorySection_id = $(e.target).data('catsecid'),
            contractor_id = this.contractorModel.get('contractor_id');

        if ($(e.target).is(':checked')) {
            // add
        } else {
            // delete
        }
    }
});