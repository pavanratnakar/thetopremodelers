var herveContact = {
    prepopulate : function(){
        //prepopulate fields that need default values (using rel attribute)
        $('.prepopulate').each(function(){
            $(this).val($(this).attr('rel'));
        });
        //clear default value and add '.not-empty' class on click
        $('.prepopulate').focus( function(){
          if( $(this).val() == $(this).attr('rel') ){
            $(this).val('').addClass('not-empty'); 
          }
        });   
        //restore default value & remove '.not-empty' class if left blank after click
        $('.prepopulate').blur(function(){
          if( $(this).val() =='' ){
            $(this).val( $(this).attr('rel') ).removeClass('not-empty');
          }
        });
    },
    form : {
        id : 'contactForm',
        validate : function(){
            var contactValidator= $("form#"+this.id).validate({
                errorClass: "error",
                rules: {
                    emailId: {
                        required: true,
                        email: true,
                        notEqual:true
                    },
                    firstName: {
                        required: true,
                        minlength: 2,
                        notEqual:true
                    },
                    lastName: {
                        required: true,
                        minlength: 2,
                        notEqual:true
                    },
                    address: {
                        required: true,
                        notEqual:true
                    },
                    city: {
                        required: true,
                        notEqual:true
                    },
                    zip: {
                        required: true,
                        notEqual:true
                    },
                    phone1: {
                        required: true
                    },
                    phone2: {
                        required: true
                    },
                    phone3: {
                        required: true
                    }
                },
                messages: {
                    emailId: "Please enter a valid email address",
                    firstName: {
                        required: "Please enter a first name",
                        minlength: jQuery.format("Your first name must consist of at least {0} characters")
                    },
                    lastName: {
                        required: "Please enter a last name",
                        minlength: jQuery.format("Your last name must consist of at least {0} characters")
                    },
                    address: {
                        required: "Please enter address"
                    },
                    city: {
                        required: "Please enter city"
                    },
                    zip: {
                        required: "Please enter zip code"
                    },
                    phone1: {
                        required: "Please enter number"
                    },
                    phone2: {
                        required: "Please enter number"
                    },
                    phone3: {
                        required: "Please enter number"
                    }
                },
                wrapper: 'li',
                errorPlacement: function(error, element) {
                    error.appendTo($(".formStatus"));
                },
                submitHandler: function() {
                    var email=$('#emailId').val(),
                        name=$('#firstName').val()+' '+$('#lastName').val(),
                        address=$('#address').val(),
                        city = $('#city').val(),
                        zip = $('#zip').val(),
                        phone = $('#phone1').val()+' '+$('#phone2').val()+' '+$('#phone3').val(),
                        contactTime = $('#contactTime').val(),
                        message = $('#message').val(),
                        message = (message==$('#message').attr('rel')) ? 'No message' : message,
                        subscribe = $('#subscribe').val(),
                        questionPair = $('#questionPair').val() ? '&questionPair='+$('#questionPair').val() : '',
                        answerPair = $('#answerPair').val() ? '&answerPair='+$('#answerPair').val() : '',
                        categoryName = $('#categoryName').val() ? '&categoryName='+$('#categoryName').val() : '',
                        placeName = $('#placeName').val() ? '&placeName='+$('#placeName').val() : '',
                        sectionName = $('#sectionName').val() ? '&sectionName='+$('#sectionName').val() : '',
                        contractorName = $('#contractorName').val() ? '&contractorName='+$('#contractorName').val() : '',
                        emailTemplate = $.manageAjax.create('emailTemplate'),
                        parentDiv = $("form#"+herveContact.form.id).parent();

                    emailTemplate.add(
                    {
                        success: function(html) 
                        {
                            $.ajax(
                            {
                                url: $.url+"/mail/sendMail.php",
                                data: "ref=sendMail&email="+email+"&name="+name+"&address="+address+"&city="+city+"&zip="+zip+"&phone="+phone+"&contactTime="+contactTime+"&message="+message+"&subscribe="+subscribe+questionPair+answerPair+categoryName+sectionName+placeName+contractorName+"&jsoncallback=?",
                                dataType: "json",
                                type: "POST",
                                cache: true,
                                beforeSend: function() {
                                    parentDiv.ajaxLoader();
                                },
                                success:function(data)
                                {
                                    $("form#"+herveContact.form.id).parent().ajaxLoaderRemove();
                                        parentDiv.animate({
                                            height: 100
                                        }, 1500);
                                    parentDiv.html('<div class="mailStatus"><h2>We have received your details.</h2><h3>We will get back to you shortly</h3></div>');
                                }
                            });
                        }
                    });
                }
            });
        }
    },
    init : function(){
        this.prepopulate();
        this.form.validate();
    }
}
$(document).ready(function(){
    herveContact.init();
});