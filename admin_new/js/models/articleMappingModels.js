window.ArticleMapping = Backbone.Model.extend({
    urlRoot: "api/articleMapping",
    initialize: function () {
        this.validators = {};

        this.validators.article_id = function (value) {
            return value.length > 0 ? {isValid: true} : {isValid: false, message: "You must enter a Artcile Id"};
        };

        this.validators.category_id = function (value) {
            return value.length > 0 ? {isValid: true} : {isValid: false, message: "You must enter a Category Id"};
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
        article_id: null,
        category_id: null
    }
});

window.ArticleMappingCollection = Backbone.Collection.extend({
    model: ArticleMapping,
    url: "api/ArticleMappings"
});