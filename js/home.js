var herve_home = {
    reviewContainer : function(){
        $('#review-container .container').last().addClass('last-child');
    }
}
$(document).ready(function(){
    herve_home.reviewContainer();
});