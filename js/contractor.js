var herve_contractor = {
    init : function(){
        this.initEvents();
        this.shareThis();
    },
    initEvents : function(){
        var contrator_name,
            place_name,
            category_name;
        $('.get-quote').live('click', function(event){
            Boxy.load('/controller/ajaxController.php?ref=getContractorSelection&contractor_name='+$(this).attr('id').replace('contractorSelect-',''),{
                modal : true,
                title : 'Please Select Options for '+$("#top h2").html()
            });
        });
        $('.contractorQuoteSelection .place_select').live('change', function(event){
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
        $('.contractorQuoteSelection .category').live('change', function(event){
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
        $('form.contractorQuoteSelection').live('submit', function(event){
            var placeName = $(this).find('.place_select').val(),
                categoryName = $(this).find('.category_select').val(),
                sectionName = $(this).find('.section_select').val(),
                contractorName = $(this).closest('form').attr('id').replace('contractorQuoteSelection-','')
            if (placeName && categoryName && sectionName && contractorName) {
                window.open($.url+'/'+placeName+'/'+categoryName+'/'+sectionName+'/'+contractorName+'/need','_self');
            }
        });
    },
    shareThis : function(){
        stLight.options({publisher: "c2dd522f-4617-4a40-9dc2-2d3ad357cab1", doNotHash: false, doNotCopy: false, hashAddressBar: true});
    }
}
$(document).ready(function(){
    herve_contractor.init();
});