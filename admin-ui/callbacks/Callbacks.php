<?php

//namespace Drstk\AdminUI

//use Drstk\Util\WPOptionsUtilities;


//this creates the form for the drstk settings page
function drstk_display_settings() {
    $html = "div class='wrap'>" .
        "<h1>CERES Settings</h1>" .
        "<form method='post' action='options.php name='options'>";
    settings_fields("drstk_options");
    do_settings_sections("drstk_options");
    submit_button();
    $html = "</form></div>";
}


function drstk_home_url_callback() {
    $url_base = get_option('drstk_home_url');
    echo '<input name="drstk_home_url" type="text" value="' . $url_base . '"></input><br/>
        <small>This sets the URL permalink base for /browse/, /search/, /item/, and /collection/<br/>
        Currently, yours will look like: <strong>' . drstk_home_url() . 'browse/</strong></small>';
}



