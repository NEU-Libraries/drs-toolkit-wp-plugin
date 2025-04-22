<?php

namespace Drstk\AdminUI\Callbacks;

class Leaflet {

    static function apiKey(): void {
        $leaflet_api_key = (get_option('leaflet_api_key') != '') ? get_option('leaflet_api_key') : '';
        echo '<input name="leaflet_api_key" type="text" value="' . $leaflet_api_key . '" style="width:100%;"></input><br/>
        <small>Ie. pk.eyJ1IjoiZGhhcmFtbWFuaWFyIiwiYSI6ImNpbTN0cjJmMTAwYmtpY2tyNjlvZDUzdXMifQ.8sUclClJc2zSBNW0ckJLOg</small>';
    }

    static function projectKey(): void {
        $leaflet_project_key = (get_option('leaflet_project_key') != '') ? get_option('leaflet_project_key') : '';
        echo '<input name="leaflet_project_key" type="text" value="' . $leaflet_project_key . '" style="width:100%;"></input><br/>
        <small>Ie. dharammaniar.pfnog3b9</small>';
    }

}



