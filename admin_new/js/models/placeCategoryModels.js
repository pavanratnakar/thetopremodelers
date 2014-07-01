window.PlaceCategory = Backbone.Model.extend({
    urlRoot: "api/placeCategory",
    initialize: function () {
        this.validators = {};

        this.validators.placeCategory_id = function (value) {
            return value.length > 0 ? {isValid: true} : {isValid: false, message: "You must enter a id"};
        };

        this.validators.category_id = function (value) {
            return value.length > 0 ? {isValid: true} : {isValid: false, message: "You must enter a cateogory id"};
        };

        this.validators.place_id = function (value) {
            return value.length > 0 ? {isValid: true} : {isValid: false, message: "You must enter a place id"};
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
        placeCategory_id: null,
        category_id: "",
        place_id: "",
        active: "",
        delete_flag: ""
    }
});

window.PlaceCategoryCollection = Backbone.Collection.extend({
    id: null,
    model: PlaceCategory,
    url: function() {
        return "api/placeCategories/" + this.id;
    },
    initialize: function(models, options) {
        this.id = options.id;
    }
});