jQuery(document).ready(function($) {

    var coordinates = [];

    coordinates.push($('.coordinates').data('coordinates').split(',')[0].trim());
    coordinates.push($('.coordinates').data('coordinates').split(',')[1].trim());
    var title = $('.coordinates').data('title').trim();
    
    console.log(coordinates);
    console.log(title);
    var mymap = L.map('map').setView(coordinates, 13);
    var marker = L.marker(coordinates).addTo(mymap);
    marker.bindPopup("<b>" + title + "</b>").openPopup();

    L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
        attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>',
        maxZoom: 18,
        id: 'dharammaniar.pfnog3b9',
        accessToken: 'pk.eyJ1IjoiZGhhcmFtbWFuaWFyIiwiYSI6ImNpbTN0cjJmMTAwYmtpY2tyNjlvZDUzdXMifQ.8sUclClJc2zSBNW0ckJLOg'
    }).addTo(mymap);
});//end doc ready
