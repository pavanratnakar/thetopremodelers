window.ContractorReview = Backbone.Model.extend({
    urlRoot: "api/contractorReview",
    initialize: function () {
        this.validators = {};

        this.validators.score = function (value) {
            return value.length > 0 ? {isValid: true} : {isValid: false, message: "You must enter a score"};
        };

        this.validators.review = function (value) {
            return value.length > 0 ? {isValid: true} : {isValid: false, message: "You must enter a review"};
        };

        this.validators.contractor_id = function (value) {
            return value.length > 0 ? {isValid: true} : {isValid: false, message: "You must enter a contractor"};
        };

        this.validators.person = function (value) {
            return value.length > 0 ? {isValid: true} : {isValid: false, message: "You must enter a person"};
        };

        this.validators.place_id = function (value) {
            return value.length > 0 ? {isValid: true} : {isValid: false, message: "You must enter a place_id"};
        };

        this.validators.project = function (value) {
            return value.length > 0 ? {isValid: true} : {isValid: false, message: "You must enter a project"};
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
        contractorRating_id: null,
        score: "",
        review: "",
        contractor_id: "",
        timestamp: "",
        person: "",
        place_id: "",
        project: ""
    }
});

window.ContractorReviewCollection = Backbone.Collection.extend({
    id: null,
    model: ContractorReview,
    url: function() {
        return "api/contractorReviews/" + this.id;
    },
    initialize: function(models, options) {
        this.id = options.id;
    }
});