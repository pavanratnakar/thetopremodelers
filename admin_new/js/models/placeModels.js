window.Place = Backbone.Model.extend({
    urlRoot: "api/place",
    initialize: function () {
        this.validators = {};

        this.validators.place_title = function (value) {
            return value.length > 0 ? {isValid: true} : {isValid: false, message: "You must enter a title"};
        };

        this.validators.place_name = function (value) {
            return value.length > 0 ? {isValid: true} : {isValid: false, message: "You must enter a name"};
        };

        this.validators.place_geo = function (value) {
            return value.length > 0 ? {isValid: true} : {isValid: false, message: "You must enter a geo"};
        };

        this.validators.place_geo_placename = function (value) {
            return value.length > 0 ? {isValid: true} : {isValid: false, message: "You must enter a geo placename"};
        };

        this.validators.meta_id = function (value) {
            return value.length > 0 ? {isValid: true} : {isValid: false, message: "You must enter a meta id"};
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
        place_id: null,
        place_name: "",
        place_geo: "",
        place_geo_placename: "",
        meta_id: "",
        under: 0,
        active: 0
    }
});

window.PlaceCollection = Backbone.Collection.extend({
    model: Place,
    url: "api/places"
});