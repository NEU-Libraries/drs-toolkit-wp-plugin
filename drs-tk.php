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
