<?php

// namespace Drstk\Callback\Niec;



function drstk_niec_callback() {
    echo '<input type="checkbox" name="drstk_niec" ';
    if (get_option('drstk_niec') == 'on') {
        echo 'checked="checked"';
    }
    echo '/>Yes</label>';
}


function drstk_niec_metadata_callback() {
    $niec_facet_options = drstk_facets_get_option('niec', true);;
    $niec_facets_to_display = get_option('drstk_niec_metadata');
    echo "<table class='drstk_facets drstk_niec_facets'><tbody id='niec_facets_sortable'>";
    if (is_array($niec_facets_to_display)) {
        foreach ($niec_facets_to_display as $option) {
            echo '<tr><td style="padding:0;"><input type="checkbox" name="drstk_niec_metadata[]" value="' . $option . '" checked="checked"/> <label> <span class="dashicons dashicons-sort"></span> ' . titleize($option) . '</label></td>';
            echo '<td style="padding:0;" class="title"><input type="text" name="drstk_niec_' . $option . '_title" value="' . get_option('drstk_niec_' . $option . '_title') . '"></td></tr>';
        }
    }
    foreach ($niec_facet_options as $option) {
        if (!is_array($niec_facets_to_display) || (is_array($niec_facets_to_display) && !in_array($option, $niec_facets_to_display))) {
            echo '<tr><td style="padding:0;"><input type="checkbox" name="drstk_niec_metadata[]" value="' . $option . '"/> <label> <span class="dashicons dashicons-sort"></span> ' . titleize($option) . '</label></td>';
            echo '<td style="padding:0;display:none" class="title"><input type="text" name="drstk_niec_' . $option . '_title" value="' . get_option('drstk_niec_' . $option . '_title') . '"></td></tr>';
        }
    }
    echo "</tbody></table>";
}

function drstk_niec_metadata_title_callback() {
    echo '';
}






