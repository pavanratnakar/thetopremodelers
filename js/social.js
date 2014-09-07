var herve_social = {
    facebookLoad : function(){
        // Load the SDK's source Asynchronously
        // Note that the debug version is being actively developed and might
        // contain some type checks that are overly strict.
        // Please report such bugs using the bugs tool.
        (function(d, s, id) {
          var js, fjs = d.getElementsByTagName(s)[0];
          if (d.getElementById(id)) return;
          js = d.createElement(s); js.id = id; js.async = true;
          js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&appId=153551161406355&version=v2.0";
          fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    },
    sharethis : function() {
        stLight.options({
            publisher: "c2dd522f-4617-4a40-9dc2-2d3ad357cab1",
            doNotHash: false,
            doNotCopy: false,
            hashAddressBar: false,
            tracking: 'google'
        });
    }
};