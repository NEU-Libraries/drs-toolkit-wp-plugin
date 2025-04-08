<?php

// namespace Drstk\Util;

function create_post_type() {
    if (get_option('drstk_item_extensions') == "on") {
        register_post_type(
            'drstk_item_extension',
            array(
                'labels' => array(
                    'name' => __('Item Pages Custom Text'),
                    'singular_name' => __('Item Page Custom Text')
                ),
                'public' => true,
                'has_archive' => true,
                'supports' => array('title', 'editor', 'revisions'),
            )
        );
    }
}


/**
 * Basic validation and standardization of the $url_base entry;
 * should return a safe string that ends with a forward slash
 * (and does not begin with one).
 */
function drstk_home_url_validation($input) {
    $url_base = '';
    $parts = explode("/", $input);
    foreach ($parts as $part) {
        if ($part != '') {
            $safe_part = sanitize_title($part);
            if ($safe_part) {
                $url_base .= sanitize_title($part);
                $url_base .= '/';
            }
        }
    }
    return $url_base;
}

