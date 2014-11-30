var herve_promotion = {
    init: function () {
        if ($('body').hasClass('video')) {
            this.showVideo();
        }
    },
    showVideo: function() {
        var BV = new $.BigVideo();
        BV.init();
        if ('ontouchstart' in window || navigator.msMaxTouchPoints) {
            BV.show('/images/home/home_bg.jpg');
        } else {
            BV.show('/videos/promotion/promotion_1.mp4', {
                ambient: true,
                doLoop: true,
                controls: false
            });
        }
    }
};
$(document).ready(function(){
    herve_promotion.init();
});