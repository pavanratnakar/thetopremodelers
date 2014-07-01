window.CategorySection = Backbone.Model.extend({
    urlRoot: "api/categorySection",
    initialize: function () {
        this.validators = {};

        this.validators.categorySection_id = function (value) {
            return value.length > 0 ? {isValid: true} : {isValid: false, message: "You must enter a id"};
        };

        this.validators.categorysection_order = function (value) {
            return value.length > 0 ? {isValid: true} : {isValid: false, message: "You must enter a cateogory section id"};
        };

        this.validators.placeCategory_id = function (value) {
            return value.length > 0 ? {isValid: true} : {isValid: false, message: "You must enter a place category id"};
        };

        this.validators.section_id = function (value) {
            return value.length > 0 ? {isValid: true} : {isValid: false, message: "You must enter section id"};
        };

        this.validators.active = function (value) {
            return value.length > 0 ? {isValid: true} : {isValid: false, message: "You must enter active"};
        };

        this.validators.delete_flag = function (value) {
            return value.length > 0 ? {isValid: true} : {isValid: false, message: "You must enter delete flag"};
        };
    },
    validateItem: function (key) {
        return (this.validators[key]) ? this.validators[key](this.get(key)) : {isValid: true};
    },
    // TODO: Implement Backbone's standard validate() method instead.
    validateAll: function () {

        var messages = {};

        for (var key in this.validators) {
            if(this.validators.hasOwnProperty(key)) {
                var check = this.validators[key](this.get(key));
                if (check.isValid === false) {
                    messages[key] = check.message;
                }
            }
        }

        return _.size(messages) > 0 ? {isValid: false, messages: messages} : {isValid: true};
    },
    defaults: {
        categorySection_id: null,
        categorysection_order: "",
        placeCategory_id: "",
        section_id: "",
        active: "",
        delete_flag: ""
    }
});

window.CategorySectionCollection = Backbone.Collection.extend({
    id: null,
    model: CategorySection,
    url: function() {
        return "api/categorySections/" + this.id;
    },
    initialize: function(models, options) {
        this.id = options.id;
    }
});