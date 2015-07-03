var herve_contractor = {
    init : function(){
        this.initEvents();
    },
    initEvents : function(){
        var contrator_name,
            place_name,
            category_name;
        $('body').delegate('.get-quote', 'click', function (event) {
            Boxy.load('/controller/ajaxController.php?ref=getContractorSelection&contractor_name='+$(this).attr('id').replace('contractorSelect-',''),{
                modal : true,
                title : 'Please Select Options for '+$("#top h2").html()
            });
        });
        $('.contractorQuoteSelection').delegate('.place_select', 'change', function (event) {
            var categorySelectContainer = $(this).next('.category_select'),
                sectionSelectContainer = $(this).parent().children('.section_select');
            contrator_name = $(this).closest('form').attr('id').replace('contractorQuoteSelection-','')
            place_name = $(this).val();
            $.ajax({url: "/controller/ajaxController.php?ref=getCategoriesForContractor&place_name="+place_name+"&contrator_name="+contrator_name, dataType: "json", cache: true, async: false, success: function(data1, result1) {
                if (!result1) {
                    alert('Failure to retrieve the Categories.');
                } else {
                    categorySelectContainer.empty();
                    category_name = data1[0]['category_name'];
                    $.each(data1, function(key, value) {
                        categorySelectContainer.append($("<option/>", {
                            value: value['category_name'],
                            text: value['category_title']
                        }));
                    });
                    $.ajax({url: "/controller/ajaxController.php?ref=getSectionsForContractor&place_name="+place_name+"&category_name="+category_name+"&contrator_name="+contrator_name, dataType: "json", cache: true, async: false, success: function(data2, result2) {
                        if (!result2) {
                            alert('Failure to retrieve the Categories.');
                        } else {
                            sectionSelectContainer.empty();
                            $.each(data2, function(key, value) {
                                sectionSelectContainer.append($("<option/>", {
                                    value: value['section_name'],
                                    text: value['section_title']
                                }));
                            });
                        }
                    }});
                }
            }});
        });
        $('.contractorQuoteSelection').delegate('.category', 'change', function (event) {
            var sectionSelectContainer = $(this).parent().children('.section_select'),
                option;
            contrator_name = $(this).closest('form').attr('id').replace('contractorQuoteSelection-','')
            place_name = $(this).parent().children('.place_select').val();
            category_name = $(this).val();
            $.ajax({url: "/controller/ajaxController.php?ref=getSectionsForContractor&place_name="+place_name+"&category_name="+category_name+"&contrator_name="+contrator_name, dataType: "json", cache: true, async: false, success: function(data2, result2) {
                if (!result2) {
                    alert('Failure to retrieve the Categories.');
                } else {
                    sectionSelectContainer.empty();
                    $.each(data2, function(key, value) {
                        sectionSelectContainer.append($("<option/>", {
                            value: value['section_name'],
                            text: value['section_title']
                        }));
                    });
                }
            }});
        });
        $('.contractorQuoteSelection').delegate('.submit', 'click', function (event) {
            event.preventDefault();
            var placeName = $(this).closest('form').find('.place_select').val(),
                categoryName = $(this).closest('form').find('.category_select').val(),
                sectionName = $(this).closest('form').find('.section_select').val(),
                contractorName = $(this).closest('form').attr('id').replace('contractorQuoteSelection-','')
            if (placeName && categoryName && sectionName && contractorName) {
                window.open($.url+'/'+placeName+'/'+categoryName+'/'+sectionName+'/'+contractorName+'/need','_self');
            }
        });
    }
}
$(document).ready(function(){
    herve_contractor.init();
});