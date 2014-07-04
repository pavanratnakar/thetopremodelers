window.ContractorMappingView = Backbone.View.extend({
    initialize: function (e) {
        e = e || {};
        this.placeModel = e.placeModel || null;
        this.placeCategoryModel = e.placeCategoryModel || null;
        this.categorySectionModel = e.categorySectionModel || null;

        this.placeModels = e.placeModels || null;
        this.placeCategoryModels = e.placeCategoryModels || null;
        this.categorySectionModels = e.categorySectionModels || null;
    },
    render: function () {
        $(this.el).html(this.template(_.extend(
            this.model.toJSON(),{
                placeModels: this.placeModels && this.placeModels.models
            },{
                placeCategoryModels: this.placeCategoryModels && this.placeCategoryModels.models
            },{
                categorySectionModels: this.categorySectionModels && this.categorySectionModels.models
            },{
                placeModel: this.placeModel
            },{
                placeCategoryModel: this.placeCategoryModel
            },{
                categorySectionModel: this.categorySectionModel
            }
        )));
        return this;
    },
    events: {
        "change": "change",
        "click .save": "beforeSave",
        "click .delete": "deleteMapping",
        "change #place_id": "placeChange",
        "change #placeCategory_id": "placeCategoryChange"
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
    placeChange: function(){
        var n = $(this.el).find('#placeCategory_id'),
            m = this.placeCategoryModels;

        m.id = $(this.el).find('#place_id').val();
        m.fetch({
            success: function(){
                n.empty();
                _.each(m.models, function(placeCategory) {
                    n.append($("<option></option>").attr("value", placeCategory.attributes.placeCategory_id).text(placeCategory.attributes.category_title));
                });
            }
        });
    },
    placeCategoryChange: function(){
        var n = $(this.el).find('#categorySection_id'),
            m = this.categorySectionModels;

        m.id = $(this.el).find('#placeCategory_id').val();
        m.fetch({
            success: function(){
                n.empty();
                _.each(m.models, function(categorySection) {
                    n.append($("<option></option>").attr("value", categorySection.attributes.categorySection_id).text(categorySection.attributes.section_title));
                });
            }
        });
    }
});