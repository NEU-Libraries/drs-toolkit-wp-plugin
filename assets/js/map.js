jQuery(document).ready(function($) {

    var items = getItemsFromJqueryArray($('.coordinates'));

    var mymap = createMap('map');

    addTileLayerToMap(mymap);

    addPopupsToItems(items, mymap);
    var bounds = getBoundsForMap(items);

    mymap.fitBounds(bounds);
});//end doc ready

function getCordinatesFromString(input) {
    if (!input) {
        return null;
    }
    return [
        input.split(',')[0].trim(),
        input.split(',')[1].trim()
    ];
}

function getItemsFromJqueryArray(jqArray) {
    var items = [];
    jqArray.each(function(index) {
        items.push({
            title: jQuery(jqArray[index]).data('title'),
            coordinates: getCordinatesFromString(jQuery(jqArray[index]).data('coordinates'))
        });
    });

    return items;
}

function createMap(mapID) {
    if (!mapID) {
        return null;
    }
    return L.map(mapID);
}

function getBoundsForMap(items) {
    var bounds = [];
    jQuery.each(items, function(index, item) {
        bounds.push(item.coordinates);
    });
    return bounds;
}

function addPopupsToItems(items, map) {
    var markers = L.markerClusterGroup();

    jQuery.each(items, function(index, item) {
        var marker = L.marker(new L.LatLng(item.coordinates[0], item.coordinates[1]), { title: item.title });
        marker.bindPopup(item.title);
        markers.addLayer(marker);
    });
    map.addLayer(markers);
}

function addTileLayerToMap(map) {
    L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
        attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>',
        maxZoom: 18,
        id: 'dharammaniar.pfnog3b9',
        accessToken: 'pk.eyJ1IjoiZGhhcmFtbWFuaWFyIiwiYSI6ImNpbTN0cjJmMTAwYmtpY2tyNjlvZDUzdXMifQ.8sUclClJc2zSBNW0ckJLOg'
    }).addTo(map);
}