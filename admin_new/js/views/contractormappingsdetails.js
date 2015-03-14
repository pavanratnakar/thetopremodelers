window.ContractorMappingsView = Backbone.View.extend({
    initialize: function (e) {
        e = e || {};
        this.contractorModel = e.contractorModel || null;
        this.placeModel = e.placeModel;
        this.sectionModel = e.sectionModel;
        this.map = null;
        this._markers = [],
        this._overlays = [];
    },
    loadMaps: function (fn) {
        // Load Google maps script
        var script = document.createElement('script');
        script.type = 'text/javascript';
        script.src = 'https://maps.googleapis.com/maps/api/js?v=3.exp' + '&signed_in=false&callback='+fn;
        document.body.appendChild(script);
        // End of Load Google maps script
    },
    addMapData: function () {
        var t = this,
            minLat,
            minLon,
            maxLat,
            maxLon,
            southWest = new google.maps.LatLng(minLat, minLon),
            northEast = new google.maps.LatLng(maxLat, maxLon),
            geo = t.placeModel.models[0].get('place_geo').replace(';', ',').split(',')

        t.map = new google.maps.Map(document.getElementById('admin-map'), {
            center: {
                lat: parseFloat(geo[0], 10),
                lng: parseFloat(geo[1], 10)
            },
            zoom: 8
        });
        _.each(t.placeModel.models, function (m, index) {
            geo = m.get('place_geo').replace(';', ',').split(',');
            var marker = new google.maps.Marker({
                position: new google.maps.LatLng(geo[0], geo[1]),
                map: t.map,
                title: m.get('place_title'),
                content: m.get('place_title')
            });
            t._markers.push(marker);
            var overlay = new google.maps.InfoWindow({
                content: m.get('place_title')
            });
            t._overlays.push(overlay);
            google.maps.event.addListener(marker, 'click', function() {
                _.each(t._overlays, function (o) {
                    o.close();
                });
                overlay.open(t.map, marker);
                t.markerSelected(m);
            });
            if (!minLat || !maxLat) {
                minLat = maxLat = geo[0];
                minLon = maxLon = geo[1];
            }
            minLat = Math.min(minLat, geo[0]);
            minLon = Math.min(minLon, geo[1]);
            maxLat = Math.max(maxLat, geo[0]);
            maxLon = Math.max(maxLon, geo[1]);
        });
        t.map.fitBounds(new google.maps.LatLngBounds(new google.maps.LatLng(minLat,minLon), new google.maps.LatLng(maxLat,maxLon)));
    },
    markerSelected: function (model) {
        $('#place-' + model.get('place_id')).
            show().
            siblings('.form-module').hide();
    },
    render: function () {
        var check = true,
            t = this;

        $(this.el).html(this.template(_.extend(
            this.model.toJSON(),
            {
                placeModel: this.placeModel.models
            },
            {
                sectionModel: this.sectionModel.models
            },
            {
                contractorModel: this.contractorModel
            },
            this.contractorModel.mappingGroupedByPlace
        )));
        window.mapCallback = function () {
            t.addMapData();
        };
        this.loadMaps('mapCallback');

        setTimeout(function () {
            $('.place-check').each(function(i, sn) {
                check = true;
                $(sn).closest('div').find('.mapping-check').each(function(j, n) {
                    if (!n.checked) {
                        check = false;
                        return false;
                    }
                });
                if (check) {
                    sn.checked = true;
                }
            });
        }, 100);

        return this;
    },
    events: {
        "click .delete": "deleteMapping",
        "change .mapping-check": "mappingCheckChange",
        "change .place-check": "placeCheckChange"
    },
    change: function (event) {
        // Remove any existing alert message
        utils.hideAlert();

        // Apply the change to the model
        var target = event.target,
            change = {},
            check;

        change[target.name] = target.value;
        this.model.set(change);

        // Run validation rule (if any) on changed item
        check = this.model.validateItem(target.id);
        if (check.isValid === false) {
            utils.addValidationError(target.id, check.message);
        } else {
            utils.removeValidationError(target.id);
        }
    },
    beforeSave: function () {
        var self = this,
            check = this.model.validateAll();

        if (check.isValid === false) {
            utils.displayValidationErrors(check.messages);
            return false;
        }
        this.saveMapping();
        return false;
    },
    saveMapping: function () {
        var self = this;

        this.model.save(null, {
            success: function (model) {
                self.render();
                app.navigate('contractors/' + model.get('contractor_id'), true);
                utils.showAlert('Success!', 'Mapping saved successfully', 'alert-success');
            },
            error: function () {
                utils.showAlert('Error', 'An error occurred while trying to save this item', 'alert-error');
            }
        });
    },
    deleteMapping: function () {
        this.model.destroy({
            success: function () {
                utils.showAlert('Success!', 'Mapping deleted successfully', 'alert-success');
                window.history.back();
            }
        });
        return false;
    },
    mappingClick: function(target) {
        var section_id = target.data('secid'),
            contractor_id = this.contractorModel.get('contractor_id'),
            place_id = target.data('placeid'),
            contractorMapping_id = target.data('mapid');

        var cm = new ContractorMapping({
            section_id: section_id,
            place_id: place_id,
            contractor_id: contractor_id,
            active: 1,
            delete_flag: 0
        });
        if (contractorMapping_id) {
            cm.set('id', contractorMapping_id);
            cm.set('contractorMapping_id', contractorMapping_id);
        }

        if (target.is(':checked')) {
            // add
            cm.save(null, {
                success: function(model){
                    target.data('mapid', model.get('id'));
                    utils.showAlert('Success!', 'Mapping saved successfully', 'alert-success');
                },
                error: function(){
                    utils.showAlert('Error', 'An error occurred while trying to save this item', 'alert-error');
                }
            });
        } else {
            // delete
            cm.destroy({
                success: function () {
                    target.data('mapid', '');
                    utils.showAlert('Success!', 'Mapping deleted successfully', 'alert-success');
                }
            });
        }
    },
    mappingCheckChange: function(e) {
        this.mappingClick($(e.target));
    },
    placeCheckChange: function (e) {
        var t = this,
            target = $(e.target);

        if ($(e.target).is(':checked')) {
            $(e.target).closest('div').find('.mapping-check').each(function(i, n) { //loop through each checkbox
                if (!n.checked) {
                    n.checked = true;
                    t.mappingClick($(n));
                }
            });
        } else {
            $(e.target).closest('div').find('.mapping-check').each(function(i, n) { //loop through each checkbox
                if (n.checked) {
                    n.checked = false;
                    t.mappingClick($(n));
                }
            });
        }
    }
});