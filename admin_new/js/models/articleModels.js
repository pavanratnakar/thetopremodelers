window.Article = Backbone.Model.extend({
    urlRoot: "api/article",
    initialize: function () {
        this.validators = {};

        this.validators.article_id = function (value) {
            return value.length > 0 ? {isValid: true} : {isValid: false, message: "You must enter a id"};
        };

        this.validators.name = function (value) {
            return value.length > 0 ? {isValid: true} : {isValid: false, message: "You must enter a article id"};
        };

        this.validators.title = function (value) {
            return value.length > 0 ? {isValid: true} : {isValid: false, message: "You must enter a title"};
        };

        this.validators.keywords = function (value) {
            return value.length > 0 ? {isValid: true} : {isValid: false, message: "You must enter a keywords"};
        };

        this.validators.description = function (value) {
            return value.length > 0 ? {isValid: true} : {isValid: false, message: "You must enter a description"};
        };

        this.validators.active = function (value) {
            return value.length > 0 ? {isValid: true} : {isValid: false, message: "You must enter a active"};
        };

        this.validators.content = function (value) {
            return value.length > 0 ? {isValid: true} : {isValid: false, message: "You must enter a content"};
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
    getMappingIdForCategoryId: function (categoryId) {
        var c = false;

        _.some(this.get('mappings'), function (mapping) {
            if (mapping.category_id === categoryId) {
                c = mapping.id;
                return true;
            }
        });
        return c;
    },
    isMappedToCategoryId: function (categoryId) {
        return _.some(this.get('mappings'), function(mapping){
            if (mapping.category_id === categoryId) {
                return true;
            }
            return;
        });
    },
    defaults: {
        article_id: null,
        name: null,
        title: null,
        keywords: null,
        description: null,
        active: null,
        content: null,
        mappings: []
    }
});

window.ArticleCollection = Backbone.Collection.extend({
    model: Article,
    url: "api/articles"
});