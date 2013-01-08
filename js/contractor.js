var herve_contractor = {
    init : function(){
        this.initsEvents();
    },
    initsEvents : function(){
        this.shareThis();
    },
    shareThis : function(){
        stLight.options({publisher: "c2dd522f-4617-4a40-9dc2-2d3ad357cab1", doNotHash: false, doNotCopy: false, hashAddressBar: true});
    }
}
$(document).ready(function(){
    herve_contractor.init();
});