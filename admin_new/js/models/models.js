window.Review = Backbone.Model.extend({
    urlRoot: "api/reviews",
    initialize: function () {
        this.validators = {};

        this.validators.project = function (value) {
            return value.length > 0 ? {isValid: true} : {isValid: false, message: "You must enter a title"};
        };

        this.validators.region = function (value) {
            return value.length > 0 ? {isValid: true} : {isValid: false, message: "You must enter a region"};
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
        id: null,
        project: "",
        date: "",
        region: "",
        description: "",
        rating: 0
    }
});

window.ReviewCollection = Backbone.Collection.extend({
    model: Review,
    url: "api/reviews"
});


window.Contractor = Backbone.Model.extend({
    urlRoot: "api/contractors",
    initialize: function () {
        this.validators = {};

        this.validators.contractor_title = function (value) {
            return value.length > 0 ? {isValid: true} : {isValid: false, message: "You must enter a title"};
        };

        this.validators.contractor_description = function (value) {
            return value.length > 0 ? {isValid: true} : {isValid: false, message: "You must enter a descrption"};
        };

        this.validators.contractor_address = function (value) {
            return value.length > 0 ? {isValid: true} : {isValid: false, message: "You must enter a address"};
        };

        this.validators.contractor_name = function (value) {
            return value.length > 0 ? {isValid: true} : {isValid: false, message: "You must enter a name"};
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
        id: null,
        contractor_title: "",
        contractor_description: "",
        contractor_phone: "",
        contractor_address: "",
        contractor_name: ""
    }
});

window.ContractorCollection = Backbone.Collection.extend({
    model: Contractor,
    url: "api/contractors"
});