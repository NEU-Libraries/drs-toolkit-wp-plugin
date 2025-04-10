<?php

namespace Drstk\Util;

class AdminConfig {

    static function createPostType() {
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

    static function homeUrlValidation(string $input): string {
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
}

