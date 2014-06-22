var AppRouter = Backbone.Router.extend({

    routes: {
        ""                  : "reviewsList",
        "reviews/page/:page"	: "reviewsList",
        "reviews/add"         : "addReview",
        "reviews/:id"         : "reviewDetails",
        "about"             : "about"
    },

    initialize: function () {
        this.headerView = new HeaderView();
        $('.header').html(this.headerView.el);
    },

	reviewsList: function(page) {
        var p = page ? parseInt(page, 10) : 1;
        var reviewList = new ReviewCollection();
        reviewList.fetch({success: function(){
            $("#content").html(new ReviewListView({model: reviewList, page: p}).el);
        }});
        this.headerView.selectMenuItem('home-menu');
    },

    reviewDetails: function (id) {
        var review = new Review({id: id});
        review.fetch({success: function(){
            $("#content").html(new ReviewView({model: review}).el);
        }});
        this.headerView.selectMenuItem();
    },

	addReview: function() {
        var review = new Review();
        $('#content').html(new ReviewView({model: review}).el);
        this.headerView.selectMenuItem('add-menu');
	},

    about: function () {
        if (!this.aboutView) {
            this.aboutView = new AboutView();
        }
        $('#content').html(this.aboutView.el);
        this.headerView.selectMenuItem('about-menu');
    }

});

utils.loadTemplate(['HeaderView', 'ReviewView', 'ReviewListItemView'], function() {
    app = new AppRouter();
    Backbone.history.start();
});