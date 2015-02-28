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

        "contractorMapping/add/:id": "addContractorMapping",
        "contractorMappings/add/:id": "addContractorMappings",
        "contractorMapping/:id": "contractorMappingDetails",

        "articles": "acticlesList",

        "articleMappings": "addArticleMappings"

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
            }),
            placeList = new PlaceCollection();

        placeList.fetch({
            success: function(){
                $('#content').html(new ContractorReviewView({
                    model: contractorReview,
                    placeModel: placeList
                }).render().el);
            }
        });
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
                // var placeCategoryList = new PlaceCategoryCollection([], {
                //     id: placeList.models[0].get('place_id')
                // });
                // placeCategoryList.fetch({
                //     success: function(){
                //         var categorySectionList = new CategorySectionCollection([], {
                //             id: placeCategoryList.models[0].get('placeCategory_id')
                //         });
                //         categorySectionList.fetch({
                //             success: function(){
                //                 $('#content').html(new ContractorMappingView({
                //                     model: contractorMapping,
                //                     placeModels: placeList,
                //                     placeCategoryModels: placeCategoryList,
                //                     categorySectionModels: categorySectionList
                //                 }).render().el);
                //             }
                //         });
                //     }
                // });
            }
        });
        this.headerView.selectMenuItem();
    },

    addContractorMappings: function(id) {
        var contractor = new Contractor({id: id}),
            placeList = new PlaceCollection(),
            sectionList = new SectionCollection();

        placeList.fetch({
            success: function(){
                sectionList.fetch({
                    success: function(){
                        contractor.fetch({
                            success: function(){
                                // var categorySectionList = new CategorySectionCollection();

                                var contractorMapping = new ContractorMapping({id: id});

                                contractorMapping.fetch({
                                    success: function () {
                                        $('#content').html(new ContractorMappingsView({
                                            model: contractorMapping,
                                            contractorModel: contractor,
                                            placeModel: placeList,
                                            sectionModel: sectionList
                                        }).render().el);
                                    }
                                });
                            }
                        });
                    }
                });
            }
        });

        this.headerView.selectMenuItem();
    },

    contractorMappingDetails: function(id) {
        var contractorMapping = new ContractorMapping({id: id});

        contractorMapping.fetch({
            success: function(){
                // var categorySection = new CategorySection({
                //     id: contractorMapping.get('categorySection_id')
                // });
                // categorySection.fetch({
                //     success: function(){
                //         var placeCategory = new PlaceCategory({
                //             id: categorySection.get('placeCategory_id')
                //         });
                //         placeCategory.fetch({
                //             success: function(){
                //                 var place = new Place({
                //                     id: placeCategory.get('place_id')
                //                 });
                //                 place.fetch({
                //                     success: function(){
                //                         $('#content').html(new ContractorMappingView({
                //                             model: contractorMapping,
                //                             placeModel: place,
                //                             placeCategoryModel: placeCategory,
                //                             categorySectionModel: categorySection
                //                         }).render().el);
                //                     }
                //                 });
                //             }
                //         });
                //     }
                // });
            }
        });
        this.headerView.selectMenuItem();
    },

    articlesList: function(page) {
        var p = page ? parseInt(page, 10) : 1,
            articleList = new ArticleCollection();

        articleList.fetch({success: function(){
            $("#content").html(new ArticleListView({model: articleList, page: p}).el);
        }});
        this.headerView.selectMenuItem('list-articles');
    },

    addArticleMappings: function() {
        var articleList = new ArticleCollection(),
            categoryList = new CategoryCollection();

        categoryList.fetch({
            success: function(){
                articleList.fetch({
                    success: function(){
                        $('#content').html(new ArticleMappingsView({
                            articleModel: articleList,
                            categoryModel: categoryList
                        }).render().el);
                    }
                });
            }
        });
        this.headerView.selectMenuItem('list-articles');
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
        'ContractorMappingView',
        'ContractorMappingsView',
        'ArticleMappingsView'
    ], function() {
    app = new AppRouter();
    Backbone.history.start();
});