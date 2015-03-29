window.ContractorMappingsView = Backbone.View.extend({
    events: {
        "click .delete": "deleteMapping",
        "change .mapping-check": "mappingCheckChange",
        "change .place-check": "placeCheckChange",
        "submit .search-form": "searchByDistance"
    },
    initialize: function (e) {
        e = e || {};
        this.contractorModel = e.contractorModel || null;
        this.placeModel = e.placeModel;
        this.sectionModel = e.sectionModel;
        this.map = null;
        this._markers = [],
        this._overlays = [],
        this.contractorGeo = [],
        this.circle,
        this.minLat,
        this.minLon,
        this.maxLat,
        this.maxLon;
    },
    searchByDistance: function (e) {
        e.preventDefault();
        if ($('.search-form').find('input').val()) {
            this.loadResultsByDistance(parseFloat($('.search-form').find('input').val()));
        }
    },
    loadResultsByDistance: function (distance) {
        var t = this,
            geo,
            d,
            populationOptions;

        t.minLat = t.minLon = t.maxLat = t.maxLon = null;
        _.each(t.placeModel.models, function (m, index) {
            geo = m.get('place_geo').replace(';', ',').split(',');
            d = window.utils.getDistanceFromLatLonInKm(geo[0], geo[1], t.contractorGeo[0], t.contractorGeo[1]);
            if (d <= distance) {
                $('#place-' + m.get('place_id')).show();
                t._markers[index].setVisible(true);
                $('#place-' + m.get('place_id')).find('.mapping-check').each(function (i, n) {
                    if ($('.section-module-section').find('.section-check:eq(' + i + ')').is(':checked')) {
                        if (!n.checked) {
                            n.checked = true;
                            t.mappingClick($(n));
                        }
                    } else {
                        if (n.checked) {
                            n.checked = false;
                            t.mappingClick($(n));
                        }
                    }
                });
            } else {
                $('#place-' + m.get('place_id')).hide();
                t._markers[index].setVisible(false);
                $('#place-' + m.get('place_id')).find('.mapping-check').each(function (i, n) {
                    if (n.checked) {
                        n.checked = false;
                        t.mappingClick($(n));
                    }
                });
            }
            t.setBoundaryLatLon(geo[0], geo[1]);
        });
        if (t.circle) {
            t.circle.setRadius(distance * 1609.3 * 4);
        } else {
            t.circle = new google.maps.Circle({
                strokeColor: 'grey',
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillColor: 'green',
                fillOpacity: 0.35,
                map: t.map,
                center: new google.maps.LatLng(t.contractorGeo[0], t.contractorGeo[1]),
                radius: distance  * 1609.3 * 4
            });
        }
        t.map.fitBounds(new google.maps.LatLngBounds(new google.maps.LatLng(t.minLat, t.minLon), new google.maps.LatLng(t.maxLat, t.maxLon)));
    },
    loadMaps: function (fn) {
        // Load Google maps script
        var script = document.createElement('script');
        script.type = 'text/javascript';
        script.src = 'https://maps.googleapis.com/maps/api/js?v=3.exp' + '&signed_in=false&callback='+fn;
        document.body.appendChild(script);
        // End of Load Google maps script
    },
    addContractorMarker: function (callback) {
        var t = this,
            geocoder = new google.maps.Geocoder();

        geocoder.geocode({
            address: t.contractorModel.get('contractor_address')
        }, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                t.contractorGeo = [results[0].geometry.location.lat(), results[0].geometry.location.lng()];
                t.map.setCenter(results[0].geometry.location);
                var marker = new google.maps.Marker({
                    map: t.map,
                    icon: {
                        path: google.maps.SymbolPath.CIRCLE,
                        fillColor: 'black',
                        scale: 5,
                        strokeColor: 'blue'
                    },
                    position: results[0].geometry.location
                });
                callback();
            } else {
              alert('Geocode was not successful for the following reason: ' + status);
            }
        });
    },
    addPlaceMarkers: function () {
        var t = this,
            geo = t.placeModel.models[0].get('place_geo').replace(';', ',').split(','),
            pos;

        _.each(t.placeModel.models, function (m, index) {
            pos = new google.maps.LatLng(geo[0], geo[1]);
            geo = m.get('place_geo').replace(';', ',').split(',');
            $('#place-' + m.get('place_id')).find('.distance').html(window.utils.getDistanceFromLatLonInKm(geo[0], geo[1], t.contractorGeo[0], t.contractorGeo[1]) + ' mi');
            var marker = new google.maps.Marker({
                position: pos,
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
            t.setBoundaryLatLon(geo[0], geo[1]);
        });
        t.map.fitBounds(new google.maps.LatLngBounds(new google.maps.LatLng(this.minLat, this.minLon), new google.maps.LatLng(this.maxLat, this.maxLon)));
    },
    setBoundaryLatLon: function (lat, lon) {
        if (!this.minLat || !this.maxLat) {
            this.minLat = this.maxLat = lat;
            this.minLon = this.maxLon = lon;
        }
        this.minLat = Math.min(this.minLat, lat);
        this.minLon = Math.min(this.minLon, lon);
        this.maxLat = Math.max(this.maxLat, lat);
        this.maxLon = Math.max(this.maxLon, lon);
    },
    addMapData: function () {
        var t = this;

        var geo = t.placeModel.models[0].get('place_geo').replace(';', ',').split(',');
        t.map = new google.maps.Map(document.getElementById('admin-map'), {
            center: {
                lat: parseFloat(geo[0], 10),
                lng: parseFloat(geo[1], 10)
            },
            zoom: 8
        });
        t.addContractorMarker(function () {
            t.addPlaceMarkers();
        });
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
            t.checkSectionMapping();
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
    checkSectionMapping: function () {
        var checked = false;
        $('.section-module-section').find('.section-check').each(function(i, n) { //loop through each checkbox
            checked = false;
            $('.form-module').each(function (j, f) {
                if ($(f).find('.mapping-check:eq(' + i + ')').is(':checked')) {
                    n.checked = true;
                    checked = true;
                    return;
                }
            });
            if (!checked) {
                n.checked = false;
            }
        });
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