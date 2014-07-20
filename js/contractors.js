var herve_contractors = {
    init : function(){
        this.initsEvents();
        this.viewMore();
    },
    initsEvents : function(){
        var t = this;
        $('.place_select').live('change', function(event){
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
        $('.category_select').live('change', function(event){
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
        $('.find-contractors').live('submit', function(event){
            var place_name = $('.place_select').val(),
                category_name = $('.category_select').val(),
                section_name = $('.section_select').val();
            if (place_name && category_name && section_name) {
                window.open($.url+'/'+place_name+'/'+category_name+'/'+section_name+'/contractors/','_self');
            }
            return false;
        });
        $('.contractorsSort select').live('change', function(event){
            t.findSelection({
                sortType : $(this).val()
            });
        });
        $('.paginate button').live('click', function(event){
            t.findSelection({
                pageNumber : $(this).data('page')
            });
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
    viewMore : function(){
        var showChar = 296;
        var ellipsestext = "...";
        var moretext = "View more";
        var lesstext = "View less";
        $('.description').each(function() {
            var content = $(this).html();
            if (content.length > showChar) {
                var c = content.substr(0, showChar);
                var h = content.substr(showChar-1, content.length - showChar);
                var html = c + '<span class="moreellipses">' + ellipsestext+ '&nbsp;</span><span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="javascript:void(0);" class="morelink">' + moretext + '</a></span>';
                $(this).html(html);
            }
        });
        $(".morelink").click(function(){
            if ($(this).hasClass("less")) {
                $(this).removeClass("less");
                $(this).html(moretext);
            } else {
                $(this).addClass("less");
                $(this).html(lesstext);
            }
            $(this).prev().children('.moreellipses').toggle();
            $(this).prev().children('.morecontent').children('span').toggle();
            return false;
        });
    }
};
$(document).ready(function(){
    herve_contractors.init();
});