window.ContractorView = Backbone.View.extend({
    initialize: function () {
        this.render();
    },
    render: function () {
        $(this.el).html(this.template({
            contractor: this.model.toJSON(),
            mapping: this.model.mappingGroupedByPlace()
        }));
        return this;
    },
    events: {
        "change": "change",
        "click .save": "beforeSave",
        "click .delete": "deleteContractor"
    },
    change: function (event) {
        // Remove any existing alert message
        utils.hideAlert();

        // Apply the change to the model
        var target = event.target,
            change = {};

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
    beforeSave: function () {
        var self = this,
            check = this.model.validateAll();

        if (check.isValid === false) {
            utils.displayValidationErrors(check.messages);
            return false;
        }
        this.saveContractor();
        return false;
    },
    saveContractor: function () {
        var self = this;
        this.model.save(null, {
            success: function (model) {
                self.render();
                app.navigate('contractors/' + model.id, true);
                utils.showAlert('Success!', 'Contractor saved successfully', 'alert-success');
            },
            error: function () {
                utils.showAlert('Error', 'An error occurred while trying to delete this item', 'alert-error');
            }
        });
    },
    deleteContractor: function () {
        this.model.destroy({
            success: function () {
                alert('Contractor deleted successfully');
                window.history.back();
            }
        });
        return false;
    },
    dropHandler: function (event) {
        event.stopPropagation();
        event.preventDefault();
        var e = event.originalEvent;
        e.dataTransfer.dropEffect = 'copy';
        this.pictureFile = e.dataTransfer.files[0];

        // Read the image file from the local file system and display it in the img tag
        var reader = new FileReader();
        reader.onloadend = function () {
            $('#picture').attr('src', reader.result);
        };
        reader.readAsDataURL(this.pictureFile);
    }
});