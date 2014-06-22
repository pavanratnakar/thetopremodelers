// <![CDATA[
var pavan_global=
{
    validateExtend : function(){
        /// EXTEND JQUERY VALIDATOR ///
        $.validator.addMethod("notEqual", function(value, element, param) {
        	if(param){
	   			return this.optional(element) || value !== $(element).attr('rel');
        	}
        }, "Please choose a value!");
        /// EXTEND JQUERY VALIDATOR ///
    }
}
$(document).ready(function()
{
    pavan_global.validateExtend();
});
// ]]>
