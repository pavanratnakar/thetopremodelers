var AppRouter = Backbone.Router.extend({

    routes: {
        "": "reviewsList",
        "reviews": "reviewsList",
        "reviews/page/:page": "reviewsList",
        "reviews/add": "addReview",
        "reviews/:id": "reviewDetails",

        "contractors": "contractorsList",
        "contractors/page/:page": "contractorsList",
        "contractors/add": "addContractor",
        "contractors/:id": "contractorDetails"
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

    contractorsList: function(page) {
        var p = page ? parseInt(page, 10) : 1;
        var contractorList = new ContractorCollection();
        contractorList.fetch({success: function(){
            $("#content").html(new ContractorListView({model: contractorList, page: p}).el);
        }});
        this.headerView.selectMenuItem('home-menu');
    },

    contractorDetails: function (id) {
        var contractor = new Contractor({id: id});
        contractor.fetch({success: function(){
            $("#content").html(new ContractorView({model: contractor}).el);
        }});
        this.headerView.selectMenuItem();
    },

    addContractor: function() {
        var contractor = new Contractor();
        $('#content').html(new ContractorView({model: contractor}).el);
        this.headerView.selectMenuItem('add-menu');
    }

});

utils.loadTemplate(['HeaderView', 'ReviewView', 'ReviewListItemView', 'ContractorView', 'ContractorListItemView'], function() {
    app = new AppRouter();
    Backbone.history.start();
});