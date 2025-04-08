<?php

namespace Drstk\Callback\Leaflet;



function leaflet_api_key_callback() {
    $leaflet_api_key = (get_option('leaflet_api_key') != '') ? get_option('leaflet_api_key') : '';
    echo '<input name="leaflet_api_key" type="text" value="' . $leaflet_api_key . '" style="width:100%;"></input><br/>
    <small>Ie. pk.eyJ1IjoiZGhhcmFtbWFuaWFyIiwiYSI6ImNpbTN0cjJmMTAwYmtpY2tyNjlvZDUzdXMifQ.8sUclClJc2zSBNW0ckJLOg</small>';
}

function leaflet_project_key_callback() {
    $leaflet_project_key = (get_option('leaflet_project_key') != '') ? get_option('leaflet_project_key') : '';
    echo '<input name="leaflet_project_key" type="text" value="' . $leaflet_project_key . '" style="width:100%;"></input><br/>
    <small>Ie. dharammaniar.pfnog3b9</small>';
}



