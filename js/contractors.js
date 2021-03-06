var herve_contractors = {
    init : function(){
        this.initsEvents();
        this.hideExpandContent();
    },
    initsEvents : function(){
        var t = this;
        $('body').delegate('.place_select', 'change', function (event) {
            $.ajax({url: "/controller/ajaxController.php?ref=categorySelect&place_name="+$('.place_select').val(), dataType: "json", cache: true, async: false, success: function(data, result) {
                if (!result) {
                    alert('Failure to retrieve the Answers.');
                } else {
                    var category_name = null;
                    $.each(data, function(key, value) {
                        category_name = category_name || value['category_name'];
                    });
                    $.ajax({url: "/controller/ajaxController.php?ref=sectionSelect&place_name="+$('.place_select').val()+"&category_name="+category_name, dataType: "json", cache: true, async: false, success: function(data1, result1) {
                        if (!result1) {
                            alert('Failure to retrieve the Categories.');
                        } else {
                            $('.section_select').empty();
                            $.each(data1, function(key, value) {
                                $('.section_select').append($("<option/>", {
                                    value: value['section_name'],
                                    text: value['section_title']
                                }));
                            });
                        }
                    }});
                    $('.category_select').empty();
                    $.each(data, function(key, value) {
                        $('.category_select').append($("<option/>", {
                            value: value['category_name'],
                            text: value['category_title']
                        }));
                    });
                }
            }});
        });
        $('body').delegate('.category_select', 'change', function (event) {
            $.ajax({url: "/controller/ajaxController.php?ref=sectionSelect&place_name="+$('.place_select').val()+"&category_name="+$('.category_select').val(), dataType: "json", cache: true, async: false, success: function(data1, result1) {
                if (!result1) {
                    alert('Failure to retrieve the Categories.');
                } else {
                    $('.section_select').empty();
                    $.each(data1, function(key, value) {
                        $('.section_select').append($("<option/>", {
                            value: value['section_name'],
                            text: value['section_title']
                        }));
                    });
                }
            }});
        });
        $('body').delegate('.find-contractors', 'submit', function (event) {
            var place_name = $('.place_select').val(),
                category_name = $('.category_select').val(),
                section_name = $('.section_select').val();
            if (place_name && category_name && section_name) {
                window.open($.url+'/'+place_name+'/'+category_name+'/'+section_name+'/contractors/','_self');
            }
            return false;
        });
        $('.contractorsSort').delegate('select', 'change', function (event) {
            t.findSelection({
                sortType: $(this).val()
            });
        });
        $('.paginate').delegate('button', 'click', function (event) {
            t.findSelection({
                pageNumber: $(this).data('page')
            });
        });
        $('.expand-callout').delegate('.expand', 'click', function (event) {
            $(this).
                hide().
                closest('.expand-callout').find('.expand-content').show("slow");
        });
    },
    findSelection : function(e){
        var place_name = $('.place_select').val(),
            category_name = $('.category_select').val(),
            section_name = $('.section_select').val(),
            pageNumber = e.pageNumber || $('.paginate').find('.current').data('page') || 1;
            sortType = e.sortType || $('.contractorsSort select').val();
            url = $.url+'/'+place_name+'/'+category_name+'/'+section_name+'/contractors/';

        window.open(herve.getNth(url,'/','7')+'/'+sortType+'/'+pageNumber,'_self');
    },
    hideExpandContent: function () {
        $('.expand-content').hide();
    }
};
$(document).ready(function(){
    herve_contractors.init();
});