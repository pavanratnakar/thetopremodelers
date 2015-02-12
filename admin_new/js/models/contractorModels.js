window.Contractor = Backbone.Model.extend({
    urlRoot: "api/contractors",
    initialize: function () {
        this.validators = {};

        this.validators.contractor_title = function (value) {
            return value.length > 0 ? {isValid: true} : {isValid: false, message: "You must enter a title"};
        };

        this.validators.contractor_address = function (value) {
            return value.length > 0 ? {isValid: true} : {isValid: false, message: "You must enter a address"};
        };

        this.validators.contractor_name = function (value) {
            return value.length > 0 ? {isValid: true} : {isValid: false, message: "You must enter a name"};
        };

        this.validators.delete_flag = function (value) {
            if (typeof(value) !== 'string'){
                value = value + "";
            }
            return value;
        };
    },
    validateItem: function (key) {
        return (this.validators[key]) ? this.validators[key](this.get(key)) : {isValid: true};
    },
    // TODO: Implement Backbone's standard validate() method instead.
    validateAll: function () {

        var messages = {};

        for (var key in this.validators) {
            if(this.validators.hasOwnProperty(key)) {
                var check = this.validators[key](this.get(key));
                if (check.isValid === false) {
                    messages[key] = check.message;
                }
            }
        }

        return _.size(messages) > 0 ? {isValid: false, messages: messages} : {isValid: true};
    },
    mappingGroupedByPlace: function(){
        var o = [],
            i = -1,
            prevPlace;

        _.each(this.get('mappings'), function(mapping){
            if (mapping.place_title !== prevPlace) {
                i++;
                o[i] = {};
                o[i].place = mapping.place_title;
                o[i].sections = [];
                prevPlace = mapping.place_title;
            }
            o[i].sections.push({
                sectionTitle: mapping.section_title,
                contractorMapping_id: mapping.contractorMapping_id
            });
        });
        return o;
    },
    getMappingIdForSectionPlaceId: function(section_title, place_title){
        var c = false;

        _.some(this.get('mappings'), function(mapping){
            if (mapping.section_title === section_title && mapping.place_title === place_title) {
                c = mapping.contractorMapping_id;
                return true;
            }
        });
        return c;
    },
    isMappedToSectionPlaceId: function(section_title, place_title){
        return _.some(this.get('mappings'), function(mapping){
            if (mapping.section_title === section_title && mapping.place_title === place_title) {
                return true;
            }
            return;
        });
    },
    defaults: {
        contractor_id: null,
        contractor_title: "",
        contractor_description: "",
        contractor_phone: "",
        contractor_address: "",
        contractor_name: "",
        contractor_additional_info: "",
        delete_flag: "0",
        reviews: [],
        mappings: []
    }
});

window.ContractorCollection = Backbone.Collection.extend({
    model: Contractor,
    url: "api/contractors"
});