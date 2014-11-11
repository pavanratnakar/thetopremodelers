var herve = {
    navigation: function () {
        var hover = false;
        $("body").delegate(".nav-sidebar li",{
            mouseenter: function(){
                hover = true;
                $('.sub-sidebar').fadeIn("slow");
            },
            mouseleave: function(){
                setTimeout(function() {
                    if (!hover) {
                        $('.sub-sidebar').fadeOut("slow");
                    }
                }, 3000);
                hover = false;
            }
        });
        $("body").delegate(".navbar-icon",{
            click: function(){
                $('.navbar .navbar-nav').toggle();
            }
        });
    },
    getNth: function (s, c, n) {
        var idx;
        var i = 0;
        var newS = '';
        do {
            idx = s.indexOf(c);
            newS += s.substring(0, idx);
            s = s.substring(idx+1);
        } while (++i < n && (newS += c))
            return newS;
    }
};
$(document).ready(function () {
    herve.navigation();
    herve_social.sharethis();
});