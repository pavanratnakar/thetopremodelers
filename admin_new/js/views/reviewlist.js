window.ReviewListView = Backbone.View.extend({
    initialize: function (options) {
        this.options = options || {};
        this.render();
    },
    render: function () {
        var reviews = this.model.models,
            len = reviews.length,
            startPos = (this.options.page - 1) * 100,
            endPos = Math.min(startPos + 100, len);

        $(this.el).html('<ul class="list-group thumbnails"></ul>');
        for (var i = startPos; i < endPos; i++) {
            $('.thumbnails', this.el).append(new ReviewListItemView({model: reviews[i]}).render().el);
        }
        $(this.el).append(new Paginator({model: this.model, page: this.options.page, type:'reviews'}).render().el);
        return this;
    }
});

window.ReviewListItemView = Backbone.View.extend({
    tagName: "li",
    className: "list-group-item",
    initialize: function () {
        this.model.bind("change", this.render, this);
        this.model.bind("destroy", this.close, this);
    },
    render: function () {
        $(this.el).html(this.template(this.model.toJSON()));
        return this;
    }
});