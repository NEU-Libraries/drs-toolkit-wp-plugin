jQuery(document).ready(function($) {

    var items = [];

    $('.coordinates').each(function(index) {

        items.push({
            title: $($('.coordinates')[index]).data('title'),
            coordinates: [
                $($('.coordinates')[index]).data('coordinates').split(',')[0].trim(),
                $($('.coordinates')[index]).data('coordinates').split(',')[1].trim()
            ]
        });
    });

    var mymap = L.map('map');

    var bounds = [];
    $.each(items, function(index, item) {
        console.log(item);
        bounds.push(item.coordinates);
        L.marker(item.coordinates)
            .addTo(mymap)
            .bindPopup("<b>" + item.title + "</b>")
            .openPopup();
    });
    mymap.fitBounds(bounds);
    mymap.setZoom(13);

    L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
        attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>',
        maxZoom: 18,
        id: 'dharammaniar.pfnog3b9',
        accessToken: 'pk.eyJ1IjoiZGhhcmFtbWFuaWFyIiwiYSI6ImNpbTN0cjJmMTAwYmtpY2tyNjlvZDUzdXMifQ.8sUclClJc2zSBNW0ckJLOg'
    }).addTo(mymap);
});//end doc ready
