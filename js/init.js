var herve = {
    navigation : function(){
        $('.art-hmenu li').last().addClass('last');
    },
    footer : function(){
        $('#footer p a').last().addClass('last');
    },
    button : function(){
        $("body").delegate(".back-button",{
            "mouseenter":function(){
                $(this).find('a img').attr({
                    'src' : $.url+'/images/global/buttons/back-button-hover.jpg'
                });
            },
            "mouseleave":function(){
                $(this).find('a img').attr({
                    'src' : $.url+'/images/global/buttons/back-button.jpg'
                });
            }
        });
    },
    getNth : function(s, c, n) {
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
$(document).ready(function(){
    herve.navigation();
    herve.footer();
    herve.button();
    herve_social.sharethis();
});