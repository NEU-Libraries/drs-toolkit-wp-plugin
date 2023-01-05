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
    register_block_type(__DIR__ . '/build/timeline_v2/block.json');
    // register_block_type_from_metadata(
    //     __DIR__ . '/build/timeline_v2/block.json',
    //     ['render_callback' => 'render_timeline_call']
    // );
}
add_action('init', 'drs_tk_timeline_v2_init');

wp_register_style(
    'slick',
    plugins_url('slick/css/slick.css', __FILE__),
    [],
    filemtime(plugin_dir_path(__FILE__) . 'lib/slick/css/slick.css')
);
wp_enqueue_style('slick');

wp_register_script(
    'slick',
    plugins_url('slick/js/slick.min.js', __FILE__),
    ['jquery'],
    filemtime(plugin_dir_path(__FILE__) . 'lib/slick/js/slick.min.js'),
    true
);
wp_enqueue_script('slick');

wp_register_script(
    'drs-tk-blocks-frontend',
    plugins_url('lib/frontend.js', __FILE__),
    ['jquery'],
    filemtime(plugin_dir_path(__FILE__) . 'lib/frontend.js'),
    true
);
wp_enqueue_script('drs-tk-blocks-frontend');

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

// // Enqueue lib assets
// $lib_script_path = '/lib/leaflet/jsleaflet.js';
// $lib_style_path = '/lib/leaflet/css/leaflet.css';
// $lib_version = '1.7.1';

// wp_register_style(
//     'lib-css-map-block-leaflet',
//     plugins_url($lib_style_path, __FILE__),
//     [],
//     $lib_version
// );
// wp_register_script(
//     'lib-js-map-block-leaflet',
//     plugins_url($lib_script_path, __FILE__),
//     [],
//     $lib_version,
//     false
// );
// wp_register_style(
//     'lib-css-map-block-leaflet-cluster',
//     plugins_url('/lib/leaflet/css/MarkerCluster.css', __FILE__),
//     ['lib-css-map-block-leaflet'],
//     $lib_version
// );
// wp_register_script(
//     'lib-js-map-block-leaflet-cluster',
//     plugins_url('/lib/leaflet/js/leaflet.markercluster.js', __FILE__),
//     ['lib-js-map-block-leaflet'],
//     $lib_version,
//     false
// );

// // Enqueue the bundled block JS file
// wp_register_script(
//     'js-editor-map-block-leaflet',
//     plugins_url('build/index.js', __FILE__),
//     $asset_file['dependencies'],
//     $asset_file['version']
// );

// // register editor styles
// wp_register_style(
//     'css-editor-map-block-leaflet',
//     plugins_url('build/index.css', __FILE__),
//     [],
//     $asset_file['version']
// );

// register_block_type('drs-tk-leaflet/map-block-leaflet-multimarker', [
//     'editor_script' => 'js-editor-map-block-leaflet',
//     'editor_style' => 'css-editor-map-block-leaflet',
//     'render_callback' => 'map_block_leaflet_multi_marker_render',
//     'script' => 'lib-js-map-block-leaflet-cluster',
//     'style' => 'lib-css-map-block-leaflet-cluster',
//     'attributes' => [
//         'markers' => [
//             'type' => 'array',
//             'default' => [],
//         ],
//         'themeUrl' => [
//             'type' => 'string',
//             'default' =>
//                 'https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png',
//         ],
//         'themeAttribution' => [
//             'type' => 'string',
//             'default' =>
//                 '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a> &copy; <a href="https://carto.com/attributions">CARTO</a>',
//         ],
//         'height' => [
//             'type' => 'number',
//             'default' => 220,
//         ],
//         'themeId' => [
//             'type' => 'number',
//             'default' => 1,
//         ],
//     ],
// ]);

// function map_block_leaflet_multi_marker_render($settings)
// {
//     $classes = 'map_block_leaflet';
//     if (array_key_exists('align', $settings)) {
//         switch ($settings['align']) {
//             case 'wide':
//                 $classes .= ' alignwide';
//                 break;
//             case 'full':
//                 $classes .= ' alignfull';
//                 break;
//         }
//     }

//     $id = uniqid('lmb_');

//     return '
// 	<div id=\'' .
//         $id .
//         '\' class="' .
//         $classes .
//         '" style="height: ' .
//         $settings['height'] .
//         'px"></div>
// 	<script>
// 		document.addEventListener("DOMContentLoaded", function() {
// 			var markets = ' .
//         json_encode($settings['markers']) .
//         ';
// 			var center = [51.505, -0.09];
// 			var layer = L.tileLayer(\'' .
//         $settings['themeUrl'] .
//         '\', {
// 				attribution: \'' .
//         $settings['themeAttribution'] .
//         '\'
// 			})
// 			var map = L.map(' .
//         $id .
//         ', { center: center, layers: [layer]});
// 			map.scrollWheelZoom.disable();
// 			if(markets.length > 0) {
// 				var markers = L.markerClusterGroup();
// 				markets.forEach( function(market) {
// 					L.marker([market.latlng.lat, market.latlng.lng]).bindPopup(market.content).addTo(markers)
// 				})
// 				map.addLayer(markers);
// 				map.fitBounds(markers.getBounds(), {padding: [50, 50]})
// 			}
//       var container = document.getElementById(\'' .
//         $id .
//         '\');
//       var observer = ResizeObserver && new ResizeObserver(function() {
//         map.invalidateSize(true);
//       });
//       observer && observer.observe(container);
// 		});
// 	</script>
// 	';
// }
