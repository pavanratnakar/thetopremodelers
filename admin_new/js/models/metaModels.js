window.Meta = Backbone.Model.extend({
    urlRoot: "api/metas",
    initialize: function () {
        this.validators = {};

        this.validators.title = function (value) {
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
    getMatching: function () {
        var matching = {};
        if (this.get('matching')) {
            matching = jQuery.parseJSON(this.get('matching') + '');
        }
        return matching;
    },
    defaults: {
        id: null,
        title: "",
        description: "",
        keywords: "",
        matching: ""
    }
});

window.MetaCollection = Backbone.Collection.extend({
    model: Meta,
    url: "api/metas"
});