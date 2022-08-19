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

function ceres_drs_tk_block_init()
{
    register_block_type(__DIR__ . '/build');
}
add_action('init', 'ceres_drs_tk_block_init');

add_action(
    'rest_api_init',
    function () {
        add_action('rest_pre_serve_request', function () {
            header('Access-Control-Allow-Origin: *');
        });
    },
    15
);

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
            'You need to run `npm start` or `npm run build` for the "wholesomecode/wholesome-plugin" block first.'
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
        'wholesome-plugin'
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
