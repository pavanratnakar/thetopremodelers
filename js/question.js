var herve_question = {
    init : function(){
        this.form.id = 'questionForm';
        this.form.validate();
        this.shareThis();
    },
    form : {
        id : '',
        validate : function(){
            $("form#"+this.id).validate();
        }
    },
    shareThis : function(){
        stLight.options({publisher: "c2dd522f-4617-4a40-9dc2-2d3ad357cab1", doNotHash: false, doNotCopy: false, hashAddressBar: true});
    }
}
$(document).ready(function(){
    herve_question.init();
});