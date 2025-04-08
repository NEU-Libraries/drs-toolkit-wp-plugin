<?php

// namespace Drstk\Callback\Search;


function drstk_search_page_title_callback() {
    echo '<input type="text" name="drstk_search_page_title" value="';
    if (get_option('drstk_search_page_title') == '') {
        echo 'Search';
    } else {
        echo get_option('drstk_search_page_title');
    }
    echo '" />';
}

function drstk_search_placeholder_callback() {
    echo '<input type="text" name="drstk_search_placeholder" value="';
    if (get_option('drstk_search_placeholder') == '') {
        echo 'Search ...';
    } else {
        echo get_option('drstk_search_placeholder');
    }
    echo '" />';
}

function drstk_search_metadata_callback() {
    $search_meta_options = array('Title', 'Creator', 'Abstract/Description', 'Date Created');
    $options = get_option('drstk_search_metadata');
    foreach ($search_meta_options as $option) {
        echo '<label><input type="checkbox" name="drstk_search_metadata[]" value="' . $option . '"';
        if (is_array($options) && in_array($option, $options)) {
            echo 'checked="checked"';
        }
        echo '/> ' . $option . '</label><br/>';
    }
}

function drstk_search_related_content_title_callback() {
    echo '<input type="text" name="drstk_search_related_content_title" value="';
    if (get_option('drstk_search_related_content_title') == '') {
        echo 'Related Content';
    } else {
        echo get_option('drstk_search_related_content_title');
    }
    echo '" />';
}


function drstk_default_search_per_page_callback() {
    $per_page_options = array("10" => "10", "20" => "20", "50" => "50");
    $default_per_page = get_option('drstk_default_search_per_page');
    echo '<select name="drstk_default_search_per_page">';
    foreach ($per_page_options as $val => $option) {
        echo '<option value="' . $val . '"';
        if ($default_per_page == $val) {
            echo 'selected="true"';
        }
        echo '/> ' . $option . '</option>';
    }
    echo '</select><br/>';
}

function drstk_search_show_facets_callback() {
    echo '<input type="checkbox" name="drstk_search_show_facets" ';
    if (get_option('drstk_search_show_facets') == 'on') {
        echo 'checked="checked"';
    }
    echo '/>Yes</label>';
}






