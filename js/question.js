var herve_question = {
    init : function(){
        this.form.id = 'questionForm';
        this.form.validate();
    },
    form : {
        id : '',
        validate : function(){
            $("form#"+this.id).validate();
        }
    }
}
$(document).ready(function(){
    herve_question.init();
});