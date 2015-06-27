window.MetaListView = Backbone.View.extend({
    initialize: function () {
        this.render();
    },
    render: function () {
        var metas = this.model.models,
            len = metas.length,
            startPos = (this.options.page - 1) * 100,
            endPos = Math.min(startPos + 100, len),
            place = '',
            category = '',
            matching = {};

        $(this.el).html('<ul class="list-group thumbnails"></ul>');
        for (var i = startPos; i < endPos; i++) {
            place = '';
            category = '';
            matching = jQuery.parseJSON(metas[i].get('matching') + '') || {};
            if (matching.placeId) {
                place = this.options.placeModel.find(function (item) {
                    return item.get('place_id') === matching.placeId
                });
            }
            if (matching.categoryId) {
                category = this.options.categoryModel.find(function (item) {
                    return item.get('category_id') === matching.categoryId
                });
            }
            $('.thumbnails', this.el).append(new MetaListItemView({
                model: metas[i],
                placeModel: place,
                categoryModel: category
            }).render().el);
        }
        $(this.el).append(new Paginator({model: this.model, page: this.options.page, type:'metas'}).render().el);
        return this;
    }
});

window.MetaListItemView = Backbone.View.extend({
    tagName: "li",
    className: "list-group-item",
    initialize: function () {
        this.model.bind("change", this.render, this);
        this.model.bind("destroy", this.close, this);
    },
    render: function () {
        var placeModel = (this.options.placeModel && this.options.placeModel.toJSON()) || {},
            categoryModel = (this.options.categoryModel && this.options.categoryModel.toJSON()) || {};

        $(this.el).html(this.template({
            model: this.model,
            placeModel: placeModel,
            categoryModel: categoryModel
        }));
        return this;
    }
});