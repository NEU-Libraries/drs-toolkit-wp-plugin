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

add_action('admin_menu', 'drstk_plugin_settings_page');
/**
 * Registers the settings page
 */
function drstk_plugin_settings_page()
{
    add_menu_page(
        __('Settings for CERES: Exhibit Toolkit Plugin', 'DRS Settings'),
        __('CERES: Exhbit Toolkit', 'pre-publish-checklist'),
        'manage_options',
        'drstk_admin_menu',
        'drstk_display_settings',
        'dashicons-yes'
    );
}

//this creates the form for the drstk settings page
function drstk_display_settings()
{
    // I (PMJ) always prefer to build up all the HTML, but WP's settings_ stuff doesn't allow that. :(
    ?>
        <div id="drstk-plugin-settings">
            <?php esc_html_e('Requires JavaScript', 'pre-publish-checklist'); ?>
        </div>
        <?php
}

function drstk_plugin_register_settings()
{
    register_setting('drstk_plugin_settings', 'drstk_plugin_example_select', [
        'default' => '',
        'show_in_rest' => true,
        'type' => 'string',
    ]);

    register_setting('drstk_plugin_settings', 'drstk_plugin_example_text', [
        'default' => '',
        'show_in_rest' => true,
        'type' => 'string',
    ]);

    register_setting('drstk_plugin_settings', 'drstk_plugin_example_text_2', [
        'default' => '',
        'show_in_rest' => true,
        'type' => 'string',
    ]);

    register_setting('drstk_plugin_settings', 'drstk_plugin_example_text_3', [
        'default' => '',
        'show_in_rest' => true,
        'type' => 'string',
    ]);

    register_setting('drstk_plugin_settings', 'drstk_plugin_example_toggle', [
        'default' => '',
        'show_in_rest' => true,
        'type' => 'string',
    ]);
}
add_action('init', 'drstk_plugin_register_settings', 10);

function drstk_plugin_admin_scripts()
{
    $dir = __DIR__;

    $script_asset_path = "$dir/build/admin.asset.php";
    if (!file_exists($script_asset_path)) {
        throw new Error(
            'You need to run `npm start` or `npm run build` for the "drs-tk" block first.'
        );
    }
    $admin_js = 'build/admin.js';
    $script_asset = require $script_asset_path;
    wp_enqueue_script(
        'drstk-plugin-admin-editor',
        plugins_url($admin_js, __FILE__),
        $script_asset['dependencies'],
        $script_asset['version']
    );
    wp_set_script_translations('drstk-plugin-block-editor', 'drstk-plugin');

    $admin_css = 'build/admin.css';
    wp_enqueue_style(
        'drstk-plugin-admin',
        plugins_url($admin_css, __FILE__),
        ['wp-components'],
        filemtime("$dir/$admin_css")
    );
}
add_action('admin_enqueue_scripts', 'drstk_plugin_admin_scripts', 10);
