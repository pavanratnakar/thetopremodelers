var herve = {
    navigation: function () {
        var hover = false;
        $("body").delegate(".nav-sidebar li",{
            mouseenter: function () {
                if ($(window).width() >= 768) {
                    hover = true;
                    $('.sub-sidebar').fadeIn("slow");
                }
            },
            mouseleave: function(){
                setTimeout(function() {
                    if ($(window).width() >= 768) {
                        if (!hover) {
                            $('.sub-sidebar').fadeOut("slow");
                        }
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
        $("body").delegate(".navbar-sidebar-icon",{
            click: function(){
                $('.nav-sidebar').toggleClass('hidden-xs');
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
    },
    backgroundRotate: function () {
        var backgrounds = $("body").data('bg').split(','),
            random = 0;

        setInterval(function () {
            random = backgrounds[Math.floor(Math.random()*backgrounds.length)];
            $.each(backgrounds, function(index, value) {
                if (random === value) {
                    $("body").addClass('background' + value);
                } else {
                    $("body").removeClass('background' + value);
                }
            });
        }, 3000);
    }
};
$(document).ready(function () {
    $("img").unveil();
    herve.navigation();
    herve_social.sharethis();
    if ($("body").hasClass("background-rotate")) {
        herve.backgroundRotate();
    }
});