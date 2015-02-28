window.Category = Backbone.Model.extend({
    urlRoot: "api/category",
    initialize: function () {
        this.validators = {};

        this.validators.category_id = function (value) {
            return value.length > 0 ? {isValid: true} : {isValid: false, message: "You must enter a id"};
        };

        this.validators.category_title = function (value) {
            return value.length > 0 ? {isValid: true} : {isValid: false, message: "You must enter a date"};
        };

        this.validators.category_name = function (value) {
            return value.length > 0 ? {isValid: true} : {isValid: false, message: "You must enter a name"};
        };

        this.validators.category_value = function (value) {
            return value.length > 0 ? {isValid: true} : {isValid: false, message: "You must enter a title"};
        };

        this.validators.category_order = function (value) {
            return value.length > 0 ? {isValid: true} : {isValid: false, message: "You must enter a title"};
        };

        this.validators.position = function (value) {
            return value.length > 0 ? {isValid: true} : {isValid: false, message: "You must enter a title"};
        };

        this.validators.active = function (value) {
            return value.length > 0 ? {isValid: true} : {isValid: false, message: "You must enter a title"};
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
        category_id: null,
        category_title: "",
        category_name: "",
        category_value: "",
        category_order: "",
        position: "",
        active: ""
    }
});

window.CategoryCollection = Backbone.Collection.extend({
    model: Category,
    url: "api/categories"
});