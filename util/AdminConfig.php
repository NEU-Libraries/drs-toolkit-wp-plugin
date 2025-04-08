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
