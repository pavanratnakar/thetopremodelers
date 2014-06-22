window.Paginator = Backbone.View.extend({
    className: 'pagination-center',
    initialize:function () {
        this.model.bind("reset", this.render, this);
        this.render();
    },
    render:function () {
        var items = this.model.models,
            len = items.length,
            pageCount = Math.ceil(len / 8);

        $(this.el).html('<ul/>');
        $('ul', this.el).addClass('pagination');
        for (var i=0; i < pageCount; i++) {
            $('ul', this.el).append("<li" + ((i + 1) === this.options.page ? " class='active'" : "") + "><a href='#"+this.options.type+"/page/"+(i+1)+"'>" + (i+1) + "</a></li>");
        }
        return this;
    }
});