window.Section = Backbone.Model.extend({
    urlRoot: "api/section",
    initialize: function () {
        this.validators = {};

        this.validators.section_id = function (value) {
            return value.length > 0 ? {isValid: true} : {isValid: false, message: "You must enter a id"};
        };

        this.validators.section_date = function (value) {
            return value.length > 0 ? {isValid: true} : {isValid: false, message: "You must enter a date"};
        };

        this.validators.section_name = function (value) {
            return value.length > 0 ? {isValid: true} : {isValid: false, message: "You must enter a name"};
        };

        this.validators.section_title = function (value) {
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
        section_id: null,
        section_date: "",
        section_name: "",
        section_title: ""
    }
});

window.SectionCollection = Backbone.Collection.extend({
    model: Section,
    url: "api/sections"
});