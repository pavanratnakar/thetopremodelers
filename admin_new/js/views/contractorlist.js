window.ContractorListView = Backbone.View.extend({
    initialize: function () {
        this.render();
    },
    render: function () {
        var contractors = this.model.models,
            len = contractors.length,
            pagePage = 100,
            startPos = (this.options.page - 1) * pagePage,
            endPos = Math.min(startPos + pagePage, len);

        $(this.el).html('<ul class="list-group thumbnails"></ul>');
        for (var i = startPos; i < endPos; i++) {
            $('.thumbnails', this.el).append(new ContractorListItemView({model: contractors[i]}).render().el);
        }
        $(this.el).append(new Paginator({model: this.model, page: this.options.page, type: 'contractors'}).render().el);
        return this;
    }
});

window.ContractorListItemView = Backbone.View.extend({
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