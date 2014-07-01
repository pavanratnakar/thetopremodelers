window.ContractorMapping = Backbone.Model.extend({
    urlRoot: "api/contractorMapping",
    initialize: function () {
        this.validators = {};

        this.validators.contractor_id = function (value) {
            return value.length > 0 ? {isValid: true} : {isValid: false, message: "You must enter a Contractor"};
        };

        this.validators.categorySection_id = function (value) {
            return value.length > 0 ? {isValid: true} : {isValid: false, message: "You must enter a Category Section Mapping"};
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
        contractorMapping_id: null,
        contractor_id: "",
        categorySection_id: "",
        active: 1,
        delete_flag: 0
    }
});

window.ContractorMappingCollection = Backbone.Collection.extend({
    model: ContractorMapping,
    url: "api/ContractorMappings"
});