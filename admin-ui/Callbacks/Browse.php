<?php

// namespace Drstk\Callback\Browse;


function drstk_browse_page_title_callback() {
    echo '<input type="text" name="drstk_browse_page_title" value="';
    if (get_option('drstk_browse_page_title') == '') {
        echo 'Browse';
    } else {
        echo get_option('drstk_browse_page_title');
    }
    echo '" />';
}

function drstk_browse_metadata_callback() {
    $browse_meta_options = array('Title', 'Creator', 'Abstract/Description', 'Date Created');
    $options = get_option('drstk_browse_metadata');
    foreach ($browse_meta_options as $option) {
        echo '<label><input type="checkbox" name="drstk_browse_metadata[]" value="' . $option . '"';
        if (is_array($options) && in_array($option, $options)) {
            echo 'checked="checked"';
        }
        echo '/> ' . $option . '</label><br/>';
    }
}


function drstk_default_browse_per_page_callback() {
    $per_page_options = array("10" => "10", "20" => "20", "50" => "50");
    $default_per_page = get_option('drstk_default_browse_per_page');
    echo '<select name="drstk_default_browse_per_page">';
    foreach ($per_page_options as $val => $option) {
        echo '<option value="' . $val . '"';
        if ($default_per_page == $val) {
            echo 'selected="true"';
        }
        echo '/> ' . $option . '</option>';
    }
    echo '</select><br/>';
}

function drstk_browse_show_facets_callback() {
    echo '<input type="checkbox" name="drstk_browse_show_facets" ';
    if (get_option('drstk_browse_show_facets') == 'on') {
        echo 'checked="checked"';
    }
    echo '/>Yes</label>';
}


function drstk_default_sort_callback() {
    $sort_options = array("title_ssi%20asc" => "Title A-Z", "title_ssi%20desc" => "Title Z-A", "score+desc%2C+system_create_dtsi+desc" => "Relevance", "creator_ssi%20asc" => "Creator A-Z", "creator_ssi%20desc" => "Creator Z-A", "system_modified_dtsi%20asc" => "Date (earliest to latest)", "system_modified_dtsi%20desc" => "Date (latest to earliest)");
    $default_sort = get_option('drstk_default_sort');
    echo '<select name="drstk_default_sort">';
    foreach ($sort_options as $val => $option) {
        echo '<option value="' . $val . '"';
        if ($default_sort == $val) {
            echo 'selected="true"';
        }
        echo '/> ' . $option . '</option>';
    }
    echo '</select><br/>';
}




