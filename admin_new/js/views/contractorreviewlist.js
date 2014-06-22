window.ContractorReviewListView = Backbone.View.extend({
    initialize: function () {
        this.render();
    },
    render: function () {
        var contractorReviews = this.model.models;

        $(this.el).html('<ul class="list-group thumbnails"></ul>');
        for (var i = 0; i < contractorReviews.length; i++) {
            $('.thumbnails', this.el).append(new ContractorReviewListItemView({model: contractorReviews[i]}).render().el);
        }
        $(this.el).append(new Paginator({model: this.model, page: 100}).render().el);
        return this;
    }
});

window.ContractorReviewListItemView = Backbone.View.extend({
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