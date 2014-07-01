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
        "contractors/:id": "contractorDetails",

        "contractorReviews/:id": "contractorsReviewsList",
        "contractorReview/:id": "contractorReviewDetails",
        "contractorReviews/add/:id": "addContractorReview",

        "contractorMapping/add/:id": "addContractorMapping"
    },

    initialize: function () {
        this.headerView = new HeaderView();
        $('.header').html(this.headerView.el);
    },

    reviewsList: function(page) {
        var p = page ? parseInt(page, 10) : 1,
            reviewList = new ReviewCollection();

        reviewList.fetch({success: function(){
            $("#content").html(new ReviewListView({model: reviewList, page: p}).el);
        }});
        this.headerView.selectMenuItem('list-reviews');
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
        this.headerView.selectMenuItem('add-review');
    },

    contractorsList: function(page) {
        var p = page ? parseInt(page, 10) : 1,
            contractorList = new ContractorCollection();

        contractorList.fetch({success: function(){
            $("#content").html(new ContractorListView({model: contractorList, page: p}).el);
        }});
        this.headerView.selectMenuItem('list-contractors');
    },

    contractorDetails: function (id) {
        var contractor = new Contractor({id: id});
        contractor.fetch({success: function(){
            $("#content").html(new ContractorView({
                model: contractor
            }).el);
        }});
        this.headerView.selectMenuItem();
    },

    addContractor: function() {
        var contractor = new Contractor();
        $('#content').html(new ContractorView({model: contractor}).el);
        this.headerView.selectMenuItem('add-contractor');
    },

    contractorsReviewsList: function(id) {
        var contractorReviewList = new ContractorReviewCollection([], {id: id});

        contractorReviewList.fetch({success: function(){
            $("#content").html(new ContractorReviewListView({model: contractorReviewList}).el);
        }});
        this.headerView.selectMenuItem();
    },

    contractorReviewDetails: function (id) {
        var placeList = new PlaceCollection(),
            contractorReview = new ContractorReview({id: id});

        placeList.fetch({success: function(){
            contractorReview.fetch({success: function(){
                var contractorReviewView = new ContractorReviewView({
                    model: contractorReview,
                    placeModel: placeList
                });
                $("#content").html(contractorReviewView.render().el);
            }});
        }});
        this.headerView.selectMenuItem();
    },

    addContractorReview: function(id) {
        var contractorReview = new ContractorReview({
            contractor_id: id
        });

        $('#content').html(new ContractorReviewView({model: contractorReview}).el);
        this.headerView.selectMenuItem();
    },

    addContractorMapping: function(id) {
        var placeList = new PlaceCollection(),
            contractorMapping = new ContractorMapping({
                contractor_id: id
            });

        // TODO MAKE THIS PARALLEL USING ASYNC
        placeList.fetch({
            success: function(){
                var placeCategoryList = new PlaceCategoryCollection([], {
                    id: placeList.models[0].get('place_id')
                });
                placeCategoryList.fetch({
                    success: function(){
                        var categorySectionList = new CategorySectionCollection([], {
                            id: placeCategoryList.models[0].get('placeCategory_id')
                        });
                        categorySectionList.fetch({
                            success: function(){
                                $('#content').html(new ContractorMappingView({
                                    model: contractorMapping,
                                    placeModel: placeList,
                                    placeCategoryModel: placeCategoryList,
                                    categorySectionModel: categorySectionList
                                }).render().el);
                            }
                        });
                    }
                });
            }
        });
        this.headerView.selectMenuItem();
    }

});

utils.loadTemplate([
        'HeaderView',
        'ReviewView',
        'ReviewListItemView',
        'ContractorView',
        'ContractorListItemView',
        'ContractorReviewView',
        'ContractorReviewListItemView',
        'ContractorMappingView'
    ], function() {
    app = new AppRouter();
    Backbone.history.start();
});