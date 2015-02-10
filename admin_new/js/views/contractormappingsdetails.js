window.ContractorMappingsView = Backbone.View.extend({
    initialize: function (e) {
        e = e || {};
        this.contractorModel = e.contractorModel || null;
        this.placeModel = e.placeModel;
        this.sectionModel = e.sectionModel;
    },
    render: function () {
        $(this.el).html(this.template(_.extend(
            this.model.toJSON(),
            {
                placeModel: this.placeModel.models
            },
            {
                sectionModel: this.sectionModel.models
            },
            {
                contractorModel: this.contractorModel
            },
            this.contractorModel.mappingGroupedByPlace
        )));
        return this;
    },
    events: {
        "click .delete": "deleteMapping",
        "change .mapping-check": "mappingCheckChange",
        "change .place-check": "placeCheckChange"
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
                utils.showAlert('Error', 'An error occurred while trying to save this item', 'alert-error');
            }
        });
    },
    deleteMapping: function () {
        this.model.destroy({
            success: function () {
                utils.showAlert('Success!', 'Mapping deleted successfully', 'alert-success');
                window.history.back();
            }
        });
        return false;
    },
    mappingClick: function(target) {
        var section_id = target.data('secid'),
            contractor_id = this.contractorModel.get('contractor_id'),
            place_id = target.data('placeid'),
            contractorMapping_id = target.data('mapid');

        var cm = new ContractorMapping({
            section_id: section_id,
            place_id: place_id,
            contractor_id: contractor_id,
            active: 1,
            delete_flag: 0
        });
        if (contractorMapping_id) {
            cm.set('id', contractorMapping_id);
            cm.set('contractorMapping_id', contractorMapping_id);
        }

        if (target.is(':checked')) {
            // add
            cm.save(null, {
                success: function(model){
                    target.data('mapid', model.get('id'));
                    utils.showAlert('Success!', 'Mapping saved successfully', 'alert-success');
                },
                error: function(){
                    utils.showAlert('Error', 'An error occurred while trying to save this item', 'alert-error');
                }
            });
        } else {
            // delete
            cm.destroy({
                success: function () {
                    target.data('mapid', '');
                    utils.showAlert('Success!', 'Mapping deleted successfully', 'alert-success');
                }
            });
        }
    },
    mappingCheckChange: function(e) {
        this.mappingClick(e.target);
    },
    placeCheckChange: function (e) {
        var t = this,
            target = $(e.target);

        if ($(e.target).is(':checked')) {
            $(e.target).closest('div').find('input[type="checkbox"]').each(function(i) { //loop through each checkbox
                if (!this.checked) {
                    this.checked = true;
                    t.mappingClick($(this));
                }
            });
        }
    }
});