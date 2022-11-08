<?php
/**
 * Plugin Name:       CERES: Exhibit Toolkit Plugin
 * Description:       This plugin provides the core functionality of the CERES: Exhibit Toolkit and brings the content of a project from the DRS into Wordpress using the DRS API.
 * Requires at least: 5.9
 * Requires PHP:      7.0
 * Version:           1.3
 * Author:            Digital Scholarship Group, Northeastern University. Eli Zoller, Patrick Murray-John, et al.
 * Text Domain:       drs-tk
 *
 * @package           NEU CERES
 */

function drs_tk_gallery_carousel_init()
{
    register_block_type(__DIR__ . '/build/gallery-carousel');
}
add_action('init', 'drs_tk_gallery_carousel_init');

function drs_tk_timeline_init()
{
    register_block_type(__DIR__ . '/build/timeline');
}
add_action('init', 'drs_tk_timeline_init');

wp_register_script(
    'drstk_timelinejs',
    'https://cdn.knightlab.com/libs/timeline3/latest/js/timeline.js',
    ['jquery']
);
wp_enqueue_script('drstk_timelinejs');
wp_register_style(
    'drstk_cdn_timeline_css',
    'https://cdn.knightlab.com/libs/timeline3/latest/css/timeline.css'
);
wp_enqueue_style('drstk_cdn_timeline_css');

function drs_tk_timeline_v2_init()
{
    // register_block_type(__DIR__ . '/build/timeline_v2/block.json');
    register_block_type_from_metadata(
        __DIR__ . '/build/timeline_v2/block.json',
        ['render_callback' => 'render_timeline_call']
    );
}
add_action('init', 'drs_tk_timeline_v2_init');

function render_timeline_call($attributes)
{
    // $wrapper_attributes = get_block_wrapper_attributes();
    $test = json_encode($attributes);

    $content =
        '<link title="timeline-styles" rel="stylesheet" href="https://cdn.knightlab.com/libs/timeline3/latest/css/timeline.css">
            <script src="https://cdn.knightlab.com/libs/timeline3/latest/js/timeline.js"></script>
            <div id="timeline-embed" style="width: 100%; height: 600px"></div>

            <script type="text/javascript">
                const attributes = ' .
        $test .
        ';
        console.log(attributes.files);


        function convertToTimelineJSON(files) {
            const timelineJSON = files.map((file) => {
                console.log(file);
                return {
                    media: {
                        url: file.fileUrl,
                        caption: "",
                        credit: file.creator,
                    },
                    start_date: {
                        year: file.date,
                    },
                    text: {
                        headline: "",
                        text: file.description.toString(),
                    },
                };
            });
        
            return {
                events: timelineJSON,
            };
        }

                
                const timeline = new TL.Timeline("timeline-embed",convertToTimelineJSON(attributes.files)) ;
            </script>';
    return $content;
    // print_r($attributes);
}
wp_register_style(
    'slick',
    plugins_url('slick/css/slick.css', __FILE__),
    [],
    filemtime(plugin_dir_path(__FILE__) . 'slick/css/slick.css')
);
wp_enqueue_style('slick');

wp_register_script(
    'slick',
    plugins_url('slick/js/slick.min.js', __FILE__),
    ['jquery'],
    filemtime(plugin_dir_path(__FILE__) . 'slick/js/slick.min.js'),
    true
);
wp_enqueue_script('slick');

wp_register_script(
    'drs-tk-gallery-carousel-blocks-frontend',
    plugins_url('slick/js/frontend.js', __FILE__),
    ['jquery'],
    filemtime(plugin_dir_path(__FILE__) . 'slick/js/frontend.js'),
    true
);
wp_enqueue_script('drs-tk-gallery-carousel-blocks-frontend');

add_action(
    'rest_api_init',
    function () {
        add_action('rest_pre_serve_request', function () {
            header('Access-Control-Allow-Origin: *');
        });
    },
    15
);

// ADMIN SETTINGD
function ceres_drstk_plugin_register_settings()
{
    register_setting(
        'ceres_drstk_plugin_settings',
        'ceres_drstk_plugin_collection_id',
        [
            'default' => '',
            'show_in_rest' => true,
            'type' => 'string',
        ]
    );
}
add_action('init', 'ceres_drstk_plugin_register_settings', 10);

function ceres_drstk_plugin_settings_page()
{
    add_options_page(
        __('DRS Toolkit Settings', 'ceres-plugin'),
        __('DRS Toolkit Settings', 'ceres-plugin'),
        'manage_options',
        'ceres_plugin_settings',
        function () {
            ?>
            <div id="ceres-plugin-settings"></div>
            <?php
        }
    );
}
add_action('admin_menu', 'ceres_drstk_plugin_settings_page', 10);

function ceres_drstk_plugin_admin_scripts()
{
    $dir = __DIR__;

    $script_asset_path = "$dir/build/admin.asset.php";
    if (!file_exists($script_asset_path)) {
        throw new Error(
            'You need to run `npm start` or `npm run build` for the "ceres/drstk-plugin" block first.'
        );
    }
    $admin_js = 'build/admin.js';
    $script_asset = require $script_asset_path;
    wp_enqueue_script(
        'ceres-drstk-plugin-admin-editor',
        plugins_url($admin_js, __FILE__),
        $script_asset['dependencies'],
        $script_asset['version']
    );
    wp_set_script_translations(
        'ceres-drstk-plugin-block-editor',
        'ceres-plugin'
    );

    $admin_css = 'build/admin.css';
    wp_enqueue_style(
        'ceres-drstk-plugin-admin',
        plugins_url($admin_css, __FILE__),
        ['wp-components'],
        filemtime("$dir/$admin_css")
    );
}
add_action('admin_enqueue_scripts', 'ceres_drstk_plugin_admin_scripts', 10);

function ceres_drstk_plugin_settings_link($links): array
{
    $label = esc_html__('Settings', 'ceres-plugin');
    $slug = 'ceres_plugin_settings';

    array_unshift(
        $links,
        "<a href='options-general.php?page=$slug'>$label</a>"
    );

    return $links;
}
add_action(
    'plugin_action_links_' . plugin_basename(__FILE__),
    'ceres_drstk_plugin_settings_link',
    10
);
